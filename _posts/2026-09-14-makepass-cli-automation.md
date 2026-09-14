---
title: "MakePass CLI: Create Apple Wallet Passes from Terminal, Scripts and AI Agents"
lang: en
---

<picture>
  <source srcset="/media/2026/09/makepass-cli-post-header.web.avif" type="image/avif">
  <source srcset="/media/2026/09/makepass-cli-post-header.web.webp" type="image/webp">
  <img src="/media/2026/09/makepass-cli-post-header.web.png" alt="MakePass – Terminal and AI Agents">
</picture>

[**MakePass**][makepass] includes a command-line interface (CLI) on Mac to create and customize Apple Wallet passes, find passes in Wallet, and export them from Terminal, scripts, or AI agents. Command-line automation requires **MakePass Ultra**.

### Get Started

Open **“Automate with Command Line…”** in the MakePass app. This section includes the tool’s path, your **Automation Token**, and commands ready to copy into Terminal.

Set your Automation Token and open the command help (these paths assume MakePass is installed in Applications):

```bash
export MAKEPASS_AUTOMATION_TOKEN='YOUR_TOKEN'
/Applications/MakePass.app/Contents/MacOS/makepass-cli --help
```

Replace `YOUR_TOKEN` with the token shown in the app. Commands require this token to ensure they come from a trusted source; the environment variable supplies it for this Terminal session.

**Tip:** To run `makepass-cli` by name, create a symbolic link:

```bash
sudo mkdir -p /usr/local/bin
sudo ln -s /Applications/MakePass.app/Contents/MacOS/makepass-cli /usr/local/bin/makepass-cli
```

If `/usr/local/bin` is not in your `PATH`, add `export PATH="/usr/local/bin:$PATH"` to your shell profile (`~/.zprofile` for zsh), then open a new Terminal window and set the token again.

The examples below use `makepass-cli`. You can always substitute the full path instead of creating the link.

### Create a Pass

Create a pass with a QR code and open it in your default pass app:

```bash
makepass-cli create-pass --barcode-format qr --barcode-payload 123456 --header-title Example --open
```

Create a boarding pass with custom fields:

```bash
makepass-cli create-pass --pass-style boarding-pass --transit-type air --barcode-payload 123456 --front-fields From:BCN To:VGO --open
```

Use `makepass-cli create-pass --help` for all the options, including images, colors, dates, and importing an existing pass as a template.

**File access:** the tool reads and writes files inside its sandbox. For automatic transfers to and from other folders, install the optional helper script using the installation command shown by `makepass-cli --help`.

To get the generated file’s path as JSON, add `--json`:

```bash
makepass-cli create-pass --barcode-format qr --barcode-payload 123456 --header-title Example --json
```

The result contains the generated file’s path. For example:

```json
{"path":"/path/to/generated-pass.pkpass"}
```

`--silence` returns just that path. Creation and export use this same output format. The default file is temporary; use `--output` to choose a destination for files you want to keep. Opening a pass does not add it to Wallet automatically.

### Find and Export Passes

Find passes containing “Boarding” in their names, descriptions, identifiers, or fields, ignoring capitalization:

```bash
makepass-cli list-passes-in-wallet --filter-query Boarding --json
```

The output is an array of passes exposed by Wallet to MakePass, or `[]` if nothing matches; it is not a complete inventory of Wallet on another device. Records include `id`, `name`, `organization`, `description`, `pass_type_identifier`, and `serial_number`; `relevant_date` is included when available.

You can use [jq][jq] to parse, filter, and transform the JSON output ([install jq][jq-install]). Enable `pipefail` so a failed command is not hidden by `jq`. For example, keep only the pass identifier, name, and organization:

```bash
set -o pipefail
makepass-cli list-passes-in-wallet --filter-query Boarding --json |
  jq '[.[] | {id, name, organization}]'
```

Illustrative output (the identifier is a placeholder):

```json
[
  {"id":"PASS_ID", "name":"Boarding Pass", "organization":"Example Airline"}
]
```

Export a pass using an identifier returned by that command:

```bash
makepass-cli export-pass-from-wallet PASS_ID
```

### Export Matching Passes in Batch

This Bash script finds matching passes, exports each one, and prints the resulting file paths. Set the token first; the script requires `jq` and the file-access helper described above so the tool can write to the selected folder:

```bash
#!/bin/bash
set -euo pipefail

output_directory="$HOME/Downloads/Wallet Exports"
mkdir -p "$output_directory"
passes=$(makepass-cli list-passes-in-wallet --filter-query Boarding --json)

jq -r '.[].id' <<< "$passes" | while IFS= read -r pass_id; do
  makepass-cli export-pass-from-wallet "$pass_id" \
    --output "$output_directory" --json | jq -r '.path'
done
```

The script prints one exported file path per line. If no passes match, nothing is exported. Existing destination files are replaced. A failed command returns a nonzero exit code and stops the script; completed exports remain. In JSON mode, execution errors go to standard error as `{"error":{"message":"…"}}`, keeping standard output available for results. Startup and argument errors may use plain text.

### Use with AI Agents

An agent with terminal access to your Mac can build a workflow from the same commands. Give it the tool’s path and configure the token in its environment, then ask:

> Create one membership pass for each row in this CSV. Use the member number as a QR code, put the name in a front field, and save the passes in a folder on my Desktop.

The agent reads `create-pass --help`, maps the CSV columns to options such as `--barcode-payload` and `--front-fields`, and runs `create-pass --json` for each row. It collects each returned `path` and reports any failures. Writing to the Desktop requires the file-access helper above.

[makepass]: /apps?redirect=makepass&utm_campaign=pvieito-post-makepass-cli#app-makepass
[jq]: https://jqlang.org/
[jq-install]: https://jqlang.org/download/#macos
