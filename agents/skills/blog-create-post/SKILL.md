---
description: Create or revise Blog posts in the author's established style, with practical examples, selective links, and local drafts for review.
---

# Blog Create Post

Use `writing-style-guide` for general editorial guidance, with the Blog-specific conventions below. Use `blog-preview` for previews and `blog-deploy` only when publication is explicitly requested; those skills own preparation and asset regeneration.

## Read Before Writing

Read several published posts relevant to the subject and format, spanning different years. Use them for voice and presentation, not as evidence that old technical behavior still applies. Do not use the Claude Cowork post as a style reference or treat unreviewed drafts as established authorial style.

Useful starting points, relative to the repository root:

- `_posts/2013-12-20-instalacion-r.md`: concrete explanation supported by photos and measured results.
- `_posts/2014-09-10-administrador-mac.md`: a short practical guide with only the necessary steps.
- `_posts/2019-12-09-extract-homekit-pairing-keys.md`: commands, output, and interpretation tied together.
- `_posts/2021-11-24-wallet-passes-batch-makepass.md`: a complete automation from input data to visible results.
- `_posts/2022-01-14-automate-homekit-with-homecontrol.md`: app entry points, parameter examples, and integration with other tools.
- `_posts/2023-08-31-makepass-ai.md`: a visual product guide built around input and output examples.
- `_posts/2024-09-22-cve-2024-40801.md`: technical claims supported by reproducible examples and primary sources.

## Voice and Structure

- Lead with what the reader can do or the concrete finding. Use short, connected paragraphs and direct instructions. Preserve a personal voice when describing the author's actual experience; never invent experience on their behalf.
- Introduce tools through what the reader can do with them: “You can use jq to parse, filter, and transform the JSON output.” Follow with the concrete example rather than interrupting the sentence with a dictionary definition.
- Explain through examples and results. Keep the prose brief without omitting what the reader needs to reproduce the workflow.
- Choose the structure for the subject. A short tip may need no sections; a guide may need setup, examples, and a combined workflow. Do not force an introduction, TL;DR, conclusion, or table of contents into every post.
- The title belongs in Jekyll frontmatter. Start post body sections at `###`, with `####` and deeper for subsections; never add `##` body headings. This overrides the general writing guide for posts, even where older articles differ.
- Name sections after the task or subject. Avoid generic promotional padding, exhaustive option catalogs, repeated summaries, and implementation details that do not help the reader.

## Examples and Links

- For app guides, identify the exact UI entry point and any required access or subscription before the example that needs it. Label convenience setup, such as a shell symlink, as an optional tip.
- Put the first working command before optional installation or shell conveniences. Make environment setup explicit when examples cross Terminal sessions, scripts, or schedulers.
- Show a small working example first. For automation guides, also include a useful combined workflow that consumes results, such as a script that selects records and acts on them.
- Show the relevant output beside commands and explain how the next step uses it. Include structured output when useful for scripting; do not make every example use JSON. Label illustrative output and placeholders clearly, and verify field names and behavior against the actual interface.
- Use generic example names in the post's language. Never copy private device names, tokens, identifiers, or personal paths into examples. Quote shell arguments only where needed.
- When discussing AI agents, show a concrete request and explain the commands, inputs, and outputs involved rather than making a vague claim about compatibility.
- Link the featured app and non-obvious tools needed to follow the examples, such as jq, with installation guidance when useful. Link supporting sources and related posts where they add substance. Do not link every familiar term, basic format, or built-in utility; avoid repeating the same link throughout the text.
- Prefer reference-style links for reused destinations and follow existing app-link conventions. Check destinations and link definitions.
- Use relevant marketing artwork for app posts and screenshots or demos where they explain a step or result. Follow existing media paths and picture markup; do not invent assets or regenerate unrelated images.

## Prepare and Review

1. Inspect the working tree and preserve the author's edits. Create the post in `_posts/YYYY-MM-DD-slug.md`, following adjacent frontmatter with `title` and `lang`; use `published: false` when the post must remain a draft.
2. Verify product claims, UI labels, commands, outputs, and availability using the app, source, help, or primary documentation. Historical posts are style references, not current technical authority.
3. Reread the complete post for flow, useful detail, restrained linking, and unnecessary repetition. Verify local media references, frontmatter, code syntax, script success and failure branches with synthetic inputs, and example data without executing actions on the user's devices or accounts merely to illustrate them.
4. Return the local files for review. Creating or revising a post does not authorize staging, committing, pushing, or publishing. Follow explicit authorization for each requested next phase and keep draft status unchanged unless asked.
