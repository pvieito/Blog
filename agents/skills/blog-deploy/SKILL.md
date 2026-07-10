---
description: Build and deploy the Blog to its configured production server with JekyllTool.
---

# Blog Deploy

Use the `blog-guide` skill first, then apply this workflow.

## Preconditions

- Proceed only when the user explicitly asks to deploy.
- Verify that `_config.yml` contains `deploy_configuration`; stop and report the missing configuration otherwise.
- Review the working tree and report which uncommitted site changes the deployment will include.

## Workflow

1. Complete the asset regeneration option selected by the user.
2. Run `JekyllTool --input "$PWD" --deploy` from the repository root.
3. Confirm deployment only after the command reports success.

The deployment builds the site and synchronizes `_site` to the configured server using `rsync --delete`. If either phase fails, report the failure and do not retry destructive synchronization without understanding the cause.
