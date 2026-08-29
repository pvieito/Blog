#!/bin/bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
REPO_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

HERO_GENERATOR_DIR="$SCRIPT_DIR/AppsHeroGenerator"
SCORES_REPORT="$REPO_DIR/extra/AppsSalesReports/itunes_sales_table-2023-11-26-2023-12-25.csv"

echo "Building AppsHeroGenerator..."
cd "$HERO_GENERATOR_DIR"
DeveloperBuildTool

echo "Generating hero HTML..."
DeveloperRunTool --x-input AppsHeroGenerator \
    -l 1450989464 `# MakePass` \
    -l 1547121417 `# HomeControl` \
    -l 1188020834 `# OverPicture` \
    -l 6450742423 `# BrowserMask` \
    -l 1502913466 `# ChatShare` \
    -l 1553547811 `# HomeBot` \
    -l 1545802199 `# BrowserSwitch` \
    -l 931232011 `# AirWeight` \
    -l 1200274125 `# MakePDF` \
    -l 1434634466 `# VoiceExpress` \
    -l 1555029611 `# InstaReload` \
    -l 6444080295 `# MenuBot` \
    -p "1450989464:macOS,iOS,iPadOS" \
    -p "1434634466:macOS,iPadOS,iOS" \
    -p "1200274125:macOS,iPadOS,iOS" \
    -p "931232011:macOS,iPadOS,iOS,watchOS,tvOS,visionOS" \
    -p "1553547811:macOS,iOS,iPadOS" \
    -p "6450742423:macOS,iOS,iPadOS" \
    -p "1188020834:macOS,iOS,iPadOS" \
    -r "$SCORES_REPORT" \
    -o "$REPO_DIR/_includes/hero.html"

echo "Generating lossy app icons..."
"$SCRIPT_DIR/ImagesWebOptimizer.sh"

echo "Done."
