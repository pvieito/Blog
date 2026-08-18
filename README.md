# Blog

Source for [pvieito.com](https://pvieito.com), built with Jekyll. It contains the site's pages, posts, layouts, and published resources.

## Development

Use `JekyllTool --build` to build the site and `JekyllTool --preview` to preview it locally. Preserve Jekyll-reserved names and stable public URLs; use PascalCase for internal tools and code.

Generated output and machine-local state live in hidden `*.nosync` directories behind relative compatibility links, keeping them out of iCloud Drive synchronization.

Run `scripts/AppsHeroBuilder.sh` to rebuild the apps hero and regenerate all optimized images. To regenerate only the `.web.png`, `.web.avif`, and `.web.webp` variants for every `.raw.png` source image, run `scripts/ImagesWebOptimizer.sh`; image optimization requires ImageMagick.
