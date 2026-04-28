---
title: "Inside Claude Cowork: How Anthropic Runs Claude Code in a Local VM on Your Mac"
lang: en
---

<picture>
  <source srcset="/media/2026/claude-cowork.web.avif" type="image/avif">
  <source srcset="/media/2026/claude-cowork.web.webp" type="image/webp">
  <img src="/media/2026/claude-cowork.web.png" alt="Claude Cowork">
</picture>

**[Claude Cowork](https://claude.com/product/cowork)** is a feature of the Claude Desktop app that allows Claude to execute code, manipulate files, and perform complex tasks autonomously. This post documents a deep investigation into how it works under the hood, covering the architecture, security layers, and interesting implementation details.

### TL;DR

Claude Cowork runs a full Linux virtual machine locally using native virtualization. Inside this VM, it executes Claude Code CLI within a multi-layered sandbox. Network access is restricted to a strict allowlist, and MCP servers from Claude Desktop are dynamically passed through to the VM. Multiple conversations share a single VM instance, but each gets its own isolated session.

## Contents
{:.no_toc}
* Contents
{:toc}

## Introduction

When you start a Cowork session in Claude Desktop, Claude can suddenly run Python scripts, process videos with ffmpeg, create PowerPoint presentations, and more. But how does it actually work? Is it a Docker container? A remote server?

Cowork is currently only available on macOS, so this investigation was conducted on that platform from both sides: from within the execution environment (Claude's perspective) and from the host system (the user's perspective), using Claude Code 1.1.799 (2e02b6). The findings reveal a thoughtful and robust architecture.

## Architecture

The core architecture consists of three main layers:

```
┌────────────────────────────────────────────────────────────────────────┐
│                              macOS Host                                │
│                                                                        │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │  Claude.app (Electron)                                         │   │
│   │    ├── UI Renderer                                             │   │
│   │    ├── MCP Servers (Slack, Atlassian, etc.)                    │   │
│   │    ├── Network Proxy Service                                   │   │
│   │    └── Virtualization.framework                                │   │
│   └────────────────────────────────────────────────────────────────┘   │
│                                    │                                   │
│         ┌──────────────────────────┼──────────────────────────┐        │
│         │  stdio    VirtioFS    MCP SDK    HTTP/SOCKS         │        │
│         │  pipes    mounts      protocol   proxy              │        │
│         └──────────────────────────┼──────────────────────────┘        │
│                                    ▼                                   │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │  Ubuntu 22.04 VM (ARM64)                                       │   │
│   │                                                                │   │
│   │    /sessions/<name>/mnt/ ◄── VirtioFS ──► ~/<shared-folder>/   │   │
│   │                                                                │   │
│   │    ┌────────────────────────────────────────────────────────┐  │   │
│   │    │  bubblewrap sandbox + seccomp (per session)            │  │   │
│   │    │                                                        │  │   │
│   │    │    Claude Code CLI v2.1.15                             │  │   │
│   │    │      --model claude-opus-4-5-20251101                  │  │   │
│   │    │      --mcp-config {...}                                │  │   │
│   │    │      --allowedTools Task,Bash,Grep,...                 │  │   │
│   │    │                                                        │  │   │
│   │    └────────────────────────────────────────────────────────┘  │   │
│   │                                                                │   │
│   │    Network: :3128 (HTTP) / :1080 (SOCKS) → Allowlist           │   │
│   │    Disks: rootfs.img (10GB) + sessiondata.img (36MB)           │   │
│   │                                                                │   │
│   └────────────────────────────────────────────────────────────────┘   │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
```

Claude Cowork is **not** a separate model, it is [Claude Code CLI](https://github.com/anthropics/claude-code) running inside the VM, orchestrated by Claude Desktop:

| Component | Value |
|-----------|-------|
| CLI Binary | `/usr/local/bin/claude` (ELF 64-bit ARM aarch64) |
| CLI Version | 2.1.15 (Claude Code) |
| Model | `claude-opus-4-5-20251101` |
| I/O Format | `stream-json` (bidirectional) |
| Session Resume | `--resume <session-uuid>` |

The CLI is launched with the following arguments:

```bash
/usr/local/bin/claude \
  --output-format stream-json \
  --input-format stream-json \
  --model claude-opus-4-5-20251101 \
  --resume <session-uuid> \
  --allowedTools Task,Bash,Glob,Grep,Read,Edit,Write,... \
  --mcp-config '{"mcpServers": {...}}' \
  --permission-mode default \
  --plugin-dir /sessions/<name>/mnt/.skills
```

## Isolation

### Virtual Machine

The execution environment is a complete Ubuntu 22.04 LTS virtual machine running on ARM64 architecture:

```
PRETTY_NAME="Ubuntu 22.04.5 LTS"
Linux claude 6.8.0-90-generic aarch64 GNU/Linux
```

This is not a Docker container or a lightweight sandbox, it is a full VM with its own kernel, managed by Apple's Virtualization Framework. The VM runs via `com.apple.Virtualization.VirtualMachine`, the same technology used by tools like [UTM](https://mac.getutm.app) and [Tart](https://tart.run).

Resources allocated:
- 4 vCPUs (ARM64)
- 3.8 GB RAM
- ~10 GB virtual disk (sparse)

### VM Bundle Structure

The VM files are stored locally at:

```
~/Library/Application Support/Claude/vm_bundles/claudevm.bundle/
```

| File | Size | Purpose |
|------|------|---------|
| `rootfs.img` | 10 GB | Ubuntu root filesystem (sparse) |
| `sessiondata.img` | 36 MB | Persistent session data (`/sessions`) |
| `efivars.fd` | 128 KB | UEFI boot variables |
| `macAddress` | 17 B | Virtual MAC address |
| `machineIdentifier` | 70 B | Unique VM identifier |
| `.rootfs.img.origin` | 40 B | SHA1 hash for image verification |

The `rootfs.img` is a sparse file, meaning it does not actually consume 10 GB on disk, only the space needed for actual data.

### Security Layers

The VM employs multiple security layers:

```
┌──────────────────────────────────────────────┐
│  macOS Host                                  │
│    └── Apple Virtualization Framework        │
│          └── Ubuntu 22.04 VM                 │
│                └── bubblewrap sandbox        │
│                      └── seccomp filter      │
│                            └── Claude Code   │
└──────────────────────────────────────────────┘
```

- **VM isolation**: Full hardware-level separation
- **bubblewrap (bwrap)**: Linux sandboxing tool
- **seccomp**: System call filtering
- **Network isolation**: Traffic routed through local proxy (ports 3128/1080)

### Multi-Session Architecture

A single VM instance serves multiple Cowork conversations simultaneously. Each conversation gets its own isolated session:

```
/sessions/
├── intelligent-loving-darwin/  ← Chat session 1
├── dreamy-optimistic-babbage/  ← Chat session 2
└── ...
```

Session names are randomly generated using a Docker-container-like pattern: `adjective-adjective-scientist`.

| Resource | Shared? | Notes |
|----------|---------|-------|
| `/tmp/` | Yes | Sessions can see each other's temporary files |
| `/sessions/<name>/` | No | Permissions `drwxr-x---` block cross-session access |
| User (UID) | No | Each active session has its own Linux user (e.g., `uid=1002`) |
| Kernel/Processes | Yes | Same VM, same kernel |
| Network proxy | Yes | Same allowlist rules |

This was verified by creating a file in `/tmp/` from one session and successfully reading it from another, while attempting `ls /sessions/dreamy-optimistic-babbage/` returned `Permission denied`.

Interestingly, inactive sessions show `nobody:nogroup` as owner, while the active session has its own dedicated user. This suggests sessions are "depersonalized" when closed.

## File Sharing

User folders are shared between macOS and the VM using [VirtioFS](https://virtio-fs.gitlab.io), Apple's high-performance paravirtualized filesystem:

```
/mnt/.virtiofs-root/shared/Downloads → /sessions/.../mnt/Downloads
```

This enables bidirectional file access: Claude can read and write to the user's selected folder, and changes appear instantly on both sides.

### Path Translation

Claude Desktop performs smart path translation in the UI. When Claude executes:

```bash
cp report.pdf /sessions/intelligent-loving-darwin/mnt/Downloads/
```

The user sees:

```bash
cp report.pdf ~/Downloads/
```

This translation is context-aware: it only rewrites paths that are actual file arguments, not string literals in other commands.

## Networking

### Allowlist

All network traffic from the VM is routed through a local proxy with a strict allowlist. Direct DNS lookups are blocked (`socket(): Operation not permitted`).

**Allowed domains:**

| Domain | Status | Purpose |
|--------|--------|---------|
| `api.anthropic.com` | 200 | Anthropic API |
| `pypi.org` | 200 | Python packages |
| `registry.npmjs.org` | 200 | Node packages |

**Blocked domains:**

| Domain | Response |
|--------|----------|
| `google.com` | `403 Forbidden - blocked-by-allowlist` |
| `api.github.com` | `403 Forbidden - blocked-by-allowlist` |
| Any other domain | `403 Forbidden - blocked-by-allowlist` |

This means Claude can install dependencies via `pip` and `npm`, but cannot make arbitrary HTTP requests from the VM—`curl` to non-allowlisted domains is blocked, and the same applies to the `WebFetch` tool. However, `WebSearch` works as it uses the Anthropic API endpoint.

### Host-VM Communication

Communication between Claude Desktop (macOS) and Claude Code (VM) uses multiple channels:

```
┌─────────────────────────────────────────────────────────────────┐
│  macOS (Claude Desktop)                                         │
│                                                                 │
│    ┌─────────────────┐                                          │
│    │  Electron App   │                                          │
│    │                 │                                          │
│    │  stdio pipes ───┼──────────────────────┐                   │
│    │                 │                      │                   │
│    │  Unix sockets ──┼───┐                  │                   │
│    └─────────────────┘   │                  │                   │
│                          │                  │                   │
└──────────────────────────│──────────────────│───────────────────┘
                           │ virtiofs mount   │ pipes
┌──────────────────────────│──────────────────│───────────────────┐
│  VM                      │                  │                   │
│                          ▼                  ▼                   │
│    /tmp/claude-http-*.sock     pipe:[xxxxx] (stdin)             │
│    /tmp/claude-socks-*.sock    pipe:[xxxxx] (stdout)            │
│           │                         │                           │
│           ▼                         ▼                           │
│    socat → localhost:3128    Claude Code CLI                    │
│    socat → localhost:1080      --input-format stream-json       │
│    (HTTP/SOCKS proxy)          --output-format stream-json      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

| Channel | Mechanism | Purpose |
|---------|-----------|---------|
| **Messages** | Unix pipes (stdin/stdout) | JSON stream for user messages and responses |
| **HTTP Proxy** | Unix socket → socat → `:3128` | HTTP requests (pip, npm, API calls) |
| **SOCKS Proxy** | Unix socket → socat → `:1080` | Other network protocols |
| **MCP** | SDK protocol via pipes | Communication with MCP servers on host |
| **Files** | VirtioFS mount | Shared folder access |

The Unix sockets (`/tmp/claude-*.sock`) are mounted from macOS into the VM via VirtioFS, allowing `socat` processes inside the VM to bridge network traffic to the host's proxy.

## MCP

One of the most interesting aspects of the architecture is how [MCP (Model Context Protocol)](https://modelcontextprotocol.io) servers are integrated. Claude Desktop's MCP servers are passed to the VM and made available to Claude Code. The MCP configuration is injected via command-line argument:

```json
{
  "mcpServers": {
    "ea93ae0e-73b4-4d43-9bb0-2c3720b9d627": {"type": "sdk", "name": "..."},
    "4b26c136-8d30-4046-b6ad-2e41dde789ea": {"type": "sdk", "name": "..."},
    "Claude in Chrome": {"type": "sdk", "name": "Claude in Chrome"},
    "My Custom Server": {"type": "sdk", "name": "My Custom Server"},
    "cowork": {"type": "sdk", "name": "cowork"}
  }
}
```

Official Anthropic integrations use UUIDs as identifiers (e.g., `ea93ae0e-...` for Atlassian, `4b26c136-...` for Slack), while third-party and custom servers use human-readable names. The `cowork` server is built-in and provides tools like `request_cowork_directory` and `allow_cowork_file_delete`.

MCP servers configured in Claude Desktop are dynamically passed through to the VM. When a new MCP server is added to Claude Desktop, it becomes available to Cowork sessions. MCP servers use `"type": "sdk"` which means they communicate through the Claude SDK rather than stdio pipes, enabling Claude inside the VM to interact with applications running on the host.

## Pre-installed Tools

The VM comes with a comprehensive toolkit:

**Languages:**

| Language | Version |
|----------|---------|
| Python | 3.10.12 |
| Node.js | 22.22.0 |
| Ruby | 3.0.2 |
| TypeScript | 5.9.3 |

**CLI Tools:**
- `ffmpeg`/`ffprobe` 4.4.2 — Video/audio processing
- `git` 2.34.1 — Version control
- `pandoc` 2.9.2 — Document conversion
- `ImageMagick` 6.9.11 — Image manipulation
- `ripgrep` — Fast search
- `jq` 1.6 — JSON processing
- `sqlite3` — Database

**Node.js Packages:**
- `docx` — Word document creation
- `pptxgenjs` — PowerPoint generation
- `pdf-lib` — PDF manipulation
- `sharp` — Image processing

**Python Packages:**
- `beautifulsoup4` — Web scraping
- `camelot-py` — PDF table extraction
- `pandas`, `numpy`, `matplotlib` — Data analysis

## Security Implications

The architecture provides strong isolation:

1. **No direct host access**: Claude cannot access arbitrary files on macOS, only the explicitly shared folder
2. **Network allowlist**: Only package registries (pypi, npm) and Anthropic's API are accessible; arbitrary web requests are blocked
3. **Syscall restrictions**: seccomp limits what system calls can be made
4. **Per-session sandboxing**: Each conversation runs in its own bubblewrap sandbox with a dedicated user
5. **DNS blocked**: Direct DNS lookups fail; all traffic must go through the proxy

**Potential concerns:**
- Sessions sharing `/tmp/` could theoretically leak information between conversations
- The VM persists between sessions, so artifacts may remain in shared spaces

This is a significant improvement over running code directly on the host or in a simple container.

## Conclusion

Claude Cowork represents a thoughtful approach to enabling AI code execution. By running a full Linux VM with multiple security layers, Anthropic has created an environment that is both powerful (full Ubuntu with extensive tooling) and isolated (VM + sandbox + seccomp + network allowlist).

Key architectural decisions:

1. **Claude Code as the engine**: Cowork is Claude Code CLI running inside a sandboxed VM, not a separate product
2. **Single VM, multiple sessions**: Efficient resource usage while maintaining per-session isolation
3. **Apple Virtualization Framework**: Native ARM64 performance on Apple Silicon
4. **VirtioFS**: Fast, bidirectional file sharing with the host
5. **MCP passthrough**: Desktop MCP servers are dynamically shared with the VM via SDK protocol
6. **Network allowlist**: Permits dependency installation while blocking arbitrary web access
7. **Smart path translation**: Seamless UX by rewriting VM paths to host paths in the UI

This architecture allows Claude to perform complex tasks—video processing, document generation, data analysis—while maintaining strong security boundaries. It is a practical solution to the challenge of giving AI systems the ability to execute code safely.