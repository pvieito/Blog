---
description: Launch and maintain a local preview of the Blog, including drafts and future posts.
---

# Blog Preview

Use the `blog-guide` skill first, then apply this workflow.

## Workflow

1. Complete the asset regeneration option selected by the user.
2. Run `JekyllTool --input "$PWD" --preview` from the repository root.
3. Keep the preview process running until the user asks to stop it.

The preview includes drafts and future posts and opens the local URL automatically. If startup fails, report the Jekyll error and do not claim that the preview is available.
