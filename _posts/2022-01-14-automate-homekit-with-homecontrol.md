---
title: "Automate HomeKit with HomeControl Automation URLs"
lang: en
---

!["HomeControl – Automation"](/media/2022/01/homecontrol-automation-post.png)

### Introduction

[**HomeControl**][homecontrol] is a powerful Mac app that allows you to control your HomeKit setup directly from the menu bar.

**HomeControl** also includes full automation support for all the actions available in the app (triggering scenes, switching a device or device group status, changing the primary home and also changing device properties) with “x-callback-url”-compatible **Automation URLs** which can be easily invoked from **AppleScript, Terminal and other apps**.

### Automation URLs

HomeControl Automation URLs can be easily generated from the “Customize Items” section available in the HomeControl preferences window. Just right-click an actionable item like a device or scene and select “Copy Automation URL”.

![HomeControl – Copy Automation URL](/media/2022/01/homecontrol-app-automation-url.png)

A HomeControl Automation URL will be now available in your pasteboard. By default, Automation URLs run the same action that is executed when clicking on the item in the HomeControl menu:

- Scenes will be triggered.
- Devices will toggle their status (on to off and viceversa).
- Homes will be set as the HomeKit primary home.

You can customize these default Automation URL to unleash a lot of more actions.

#### Device Activation Modes

Device and device group Automation URLs can be modified so they always activate or deactivate the item instead of toggling its state by changing the `activation-mode` parameter to `activate` or `deactivate` instead of `toggle`:

- Toggle: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=switch-device-status&item-type=device&item-name=Outlet&room-name=Principal&home-name=Home&`**<span style="color:var(--default-text-color);">`activation-mode=toggle`</span>**<span style="color:var(--soft-gray-color);">`&authentication-token=TOKEN`</span>
- Activate: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=switch-device-status&item-type=device&item-name=Outlet&room-name=Principal&home-name=Home&`**<span style="color:var(--default-text-color);">`activation-mode=activate`</span>**`&authentication-token=TOKEN`</span>
- Deactivate: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=switch-device-status&item-type=device&item-name=Outlet&room-name=Principal&home-name=Home&`**<span style="color:var(--default-text-color);">`activation-mode=deactivate`</span>**`&authentication-token=TOKEN`</span>

#### Current Home Actions

You can also remove the `home-name` parameter of the URL and it will be invoked on the device or scene named as the `item-name` available in the current primary home. This is handy if you have the same scene available in multiple homes and want to trigger the scene in the one you are at the moment the automation is triggered.

#### Device Property Actions

There is a forth type of automation action that is not available directly with the “Copy Automation URL” shortcut, the `change-device-property` action. This action allows you to change a property of a device like light brightness, light color, thermostat mode, thermostat temperature or blinds position by setting the `property-type` and `property-value` parameters. These are some examples of what is posible with this powerful action type:

- Set lightbulb named "Light" to 70% brightness: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Light&`**<span style="color:var(--default-text-color);">`property-type=light-brightness&property-value=70`</span>**`&authentication-token=TOKEN`</span>
- Set lightbulb named "Light" to yellow color (hue 60°): <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Light&`**<span style="color:var(--default-text-color);">`property-type=light-hue&property-value=60`</span>**`&authentication-token=TOKEN`</span>
- Set lightbulb named "Light" to warm color temperature (3200K): <span style="color:var(--soft-gray-color);">`homecontrol//x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Light&`**<span style="color:var(--default-text-color);">`property-type=light-color-temperature&property-value=3200`</span>**`&authentication-token=TOKEN`</span>
- Set thermostat named "Air Conditioner" to 21°C target temperature: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Air%20Conditioner&`**<span style="color:var(--default-text-color);">`property-type=thermostat-temperature&property-value=21`</span>**`&authentication-token=TOKEN`</span>
- Set thermostat named "Air Conditioner" to "Auto" mode: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Air%20Conditioner&`**<span style="color:var(--default-text-color);">`property-type=thermostat-mode&property-value=auto`</span>**`&authentication-token=TOKEN`</span>
- Set blinds named "Blinds" to 70%: <span style="color:var(--soft-gray-color);">`homecontrol://x-callback-url/run-action?action-type=change-device-property&item-type=device&item-name=Blinds&`**<span style="color:var(--default-text-color);">`property-type=position&property-value=70`</span>**`&authentication-token=TOKEN`</span>

### Automation & Scripting

You can easily invoke [**HomeControl**][homecontrol] Automation URLs from AppleScript, Terminal or other apps like [Shortcuts](https://support.apple.com/guide/shortcuts/welcome/ios), [Keyboard Maestro](https://www.keyboardmaestro.com/) or [Stream Deck](https://www.elgato.com/es/stream-deck).

#### AppleScript

```applescript
tell application "HomeControl" to open location "homecontrol://…"
```

#### Terminal

```bash
$ open -g "homecontrol://…"
```

#### Other Apps

Simply use an “Open URL” action and set it to launch the Automation URL. Some apps also support running Terminal scripts or AppleScript.

[homecontrol]: https://pvieito.com/apps?redirect=homecontrol&utm_campaign=pvieito-post-homecontrol-automation#app-homecontrol
