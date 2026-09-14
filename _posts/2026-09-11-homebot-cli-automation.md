---
title: "HomeBot CLI: Control Your Home from Terminal, Scripts and AI Agents"
lang: en
published: false
---

<picture>
  <source srcset="/media/2026/09/homebot-cli-post-header.web.avif" type="image/avif">
  <source srcset="/media/2026/09/homebot-cli-post-header.web.webp" type="image/webp">
  <img src="/media/2026/09/homebot-cli-post-header.web.png" alt="HomeBot – Terminal and AI Agents">
</picture>

[**HomeBot**][homebot] includes a command-line interface (CLI) on Mac to read HomeKit devices and sensors, trigger scenes, and change device settings from Terminal, scripts, or AI agents.

### Get Started

Open **“Automate with Command Line…”** in the HomeBot app. This section includes the tool’s path, your **Automation Token**, and examples using your HomeKit items. Allow HomeKit access when prompted.

Set your Automation Token and open the command help (these paths assume HomeBot is installed in Applications):

```bash
export HOMEBOT_AUTOMATION_TOKEN='YOUR_TOKEN'
/Applications/HomeBot.app/Contents/MacOS/homebot-cli --help
```

Replace `YOUR_TOKEN` with the token shown in the app. Commands require this token to ensure they come from a trusted source; the environment variable supplies it for this Terminal session.

**Tip:** To run `homebot-cli` by name, create a symbolic link:

```bash
sudo mkdir -p /usr/local/bin
sudo ln -s /Applications/HomeBot.app/Contents/MacOS/homebot-cli /usr/local/bin/homebot-cli
```

If `/usr/local/bin` is not in your `PATH`, add `export PATH="/usr/local/bin:$PATH"` to your shell profile (`~/.zprofile` for zsh), then open a new Terminal window and set the token again.

The examples below use `homebot-cli`. You can always substitute the full path instead of creating the link.

### Find Devices and Sensors

List your HomeKit items and their status. Add `--verbose` to include identifiers and supported limits and modes:

```bash
homebot-cli get-home-items
```

Read sensor measurements as structured JSON:

```bash
homebot-cli get-home-items --item-is-sensor true --json
```

`--json` returns an array of items with `snake_case` keys. Each record includes its name, identifier, home, status, and available actions or properties. Sensor values use the units listed in `--help`; temperatures are in degrees Celsius. Unavailable measurements and empty capability lists are omitted.

You can use [jq][jq] to parse, filter, and transform the JSON output ([install jq][jq-install]). Enable `pipefail` so a failed command is not hidden by `jq`. For example, keep only temperature readings and their item and home names:

```bash
set -o pipefail
homebot-cli get-home-items --item-is-sensor true --json |
  jq '[.[] | select(.temperature_sensor_measurement != null) |
    {item_name, home_name, temperature_sensor_measurement}]'
```

Illustrative output:

```json
[
  {
    "item_name": "Room Sensor",
    "home_name": "Main Home",
    "temperature_sensor_measurement": 26.5
  }
]
```

### Run Actions

Replace the example home, device, and scene names with yours; names must match exactly, including capitalization. Turn on a lamp:

```bash
homebot-cli run-home-action --action-type switch-device-status --home-name 'Main Home' --item-name 'Desk Lamp' --activation-mode activate
```

Trigger a scene:

```bash
homebot-cli run-home-action --action-type trigger-scene --home-name 'Main Home' --item-name 'Good Night'
```

Check a brightness change without applying it:

```bash
homebot-cli run-home-action --action-type change-device-property --home-name 'Main Home' --item-name 'Desk Lamp' --property-type light-brightness --property-value 50 --dry-run
```

Remove `--dry-run` to apply the change. A successful dry run does not guarantee that the device will accept the write; check its supported properties and limits. Actions run on **every matching item**; use `get-home-items` with the same selection options to inspect the targets first. Names can match several items; use returned `item_identifier` values with `--item-identifier` to target specific ones. Each command’s `--help` lists its options and measurement units.

Add `--json` to an action to receive an array of the resulting items, with the same structure as `get-home-items`. With `--dry-run`, those records describe the existing state, not a predicted result.

All selected items are attempted. If any action fails, the command returns a nonzero exit code after finishing; successful changes remain applied. In JSON mode, no result array is returned on failure; the execution error is written to standard error as `{"error":{"message":"…"}}`. Startup and argument errors may use plain text. Re-read the affected items before retrying a failed batch.

For example, turn on a lamp and extract its resulting status:

```bash
set -o pipefail
homebot-cli run-home-action --action-type switch-device-status \
  --home-name 'Main Home' --item-name 'Desk Lamp' \
  --activation-mode activate --json |
  jq '[.[] | {item_name, item_is_active}]'
```

Illustrative output:

```json
[
  {"item_name": "Desk Lamp", "item_is_active": true}
]
```

### Combine Readings and Actions

This Bash script uses `jq` to turn on a fan when any temperature sensor in the selected room reports more than 25 °C. Set the token first and replace the example names with yours:

```bash
#!/bin/bash
set -euo pipefail

home_name='Main Home'
room_name='Living Room'
readings=$(homebot-cli get-home-items --home-name "$home_name" \
  --room-name "$room_name" --item-is-sensor true --json)

should_activate=$(jq 'any(.[]; .temperature_sensor_measurement != null and
  .temperature_sensor_measurement > 25)' <<< "$readings")

if [[ "$should_activate" == true ]]; then
  homebot-cli run-home-action --home-name "$home_name" \
    --room-name "$room_name" --item-type device --item-name Fan \
    --action-type switch-device-status --activation-mode activate --json
fi
```

It leaves the fan unchanged when there is no matching reading above the threshold. Readings use the available cached HomeKit status. The action returns its results as JSON. To schedule the script, configure the executable path and token in the scheduler’s environment too.

### Use with AI Agents

An agent with terminal access to your Mac can use the same workflow. Give it the tool’s path and configure the token in its environment, then ask:

> Find the lights in my living room, show me which are on, and set those lights to 30% brightness. Check the selection with a dry run before making changes.

The agent reads `--help`, queries `get-home-items --json`, and selects items supporting `light-brightness`. It uses their identifiers for `run-home-action --dry-run`, then applies the requested change and inspects the JSON results. No manual transcription of device names or status is needed.

[homebot]: /apps?redirect=homebot&utm_campaign=pvieito-post-homebot-cli#app-homebot
[jq]: https://jqlang.org/
[jq-install]: https://jqlang.org/download/#macos
