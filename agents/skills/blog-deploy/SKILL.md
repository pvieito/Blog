---
description: Deploy the Blog by publishing its prepared changes to the GitHub Pages source branch and verifying publication.
---

# Blog Deploy

Use the `blog-guide` skill first, then apply this workflow.

## Preconditions

- Proceed only when the user explicitly asks to deploy.
- Verify that GitHub Pages publishes the repository root from `main`; stop if the repository configuration differs.
- Review the working tree and report which uncommitted site changes the deployment will include.

## Workflow

1. Complete the asset regeneration option selected by the user.
2. Validate the prepared site with `JekyllTool --input "$PWD" --build`.
3. Review and commit the intended changes on the current branch, then publish them to `main`: when already on `main`, push directly; otherwise, follow the requested or existing branch integration flow, without creating a pull request unless explicitly requested.
4. Wait for the GitHub Pages build associated with the published commit and verify that `https://pvieito.com` is available.

Do not use `JekyllTool --deploy` or synchronize `_site` directly. Publishing a commit to `main`, either through a direct push or a branch merge, triggers the production build and publication through GitHub Pages. Do not claim deployment success after only pushing or merging; confirm that GitHub Pages reports the published commit as built.
