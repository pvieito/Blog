---
description: Guide shared preparation and asset regeneration decisions for Blog preview and deployment workflows.
---

# Blog Guide

Use this skill as the shared preparation workflow for Blog previews and deployments.

## Asset Regeneration

Before previewing or deploying, ask the user which preparation to perform and wait for the answer:

- Rebuild the apps hero and all optimized images with `extra/Scripts/AssetsBuilder.sh all`.
- Rebuild only the apps hero with `extra/Scripts/AssetsBuilder.sh apps-hero`.
- Regenerate only the optimized `.web.png`, `.web.avif`, and `.web.webp` variants with `extra/Scripts/AssetsBuilder.sh images`.
- Skip asset regeneration.

Run the selected command from the repository root.

## Change Safety

Inspect the working tree before and after regeneration. Preserve unrelated changes and report any generated source changes before continuing.
