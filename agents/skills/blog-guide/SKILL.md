---
description: Guide shared preparation and asset regeneration decisions for Blog preview and deployment workflows.
---

# Blog Guide

Use this skill as the shared preparation workflow for Blog previews and deployments.

## Asset Regeneration

Before previewing or deploying, ask the user which preparation to perform and wait for the answer:

- Rebuild the apps hero and all optimized images with `scripts/AppsHeroBuilder.sh`.
- Regenerate only the optimized `.web.png`, `.web.avif`, and `.web.webp` variants with `scripts/ImagesWebOptimizer.sh`.
- Skip asset regeneration.

Run the selected script from the repository root. Do not run the image optimizer separately after `AppsHeroBuilder.sh`, because the hero workflow already runs it.

## Change Safety

Inspect the working tree before and after regeneration. Preserve unrelated changes and report any generated source changes before continuing.
