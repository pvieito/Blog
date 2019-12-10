---
title: "HomeKit Automation: Extracting HomeKit Pairing Keys from macOS"
lang: en
---

!["HomeKit Pairing Keys"](/media/2019/12/extract-homekit-pairing-keys-header.png)

### Introduction

While the Home app allows you to read and control your HomeKit-based devices on macOS, iPadOS and iOS, sometimes you want more control. You may want to bridge some sensor to other protocol, expose you home temperature on a public API, or simply export the historical data to a CSV file. Either way, currently the `HomeKit` framework is private API on macOS, and it is not available on other platforms like Linux or Windows.

Fortunately there are some cross-platform implementations of the HomeKit Accessory Protocol that support controller-mode functionality:

- [HomeKit Python](https://github.com/jlusiardi/homekit_python)
- [HAP Controller Node](https://github.com/mrstegeman/hap-controller-node)

In particular `homekit_python` supports reading and writing HomeKit characteristics on paired devices as well as generating additional pairings. The main problem is that typically HomeKit devices only support one main pairing controller, thus, once it is paired with the Apple Home app it can only be controlled with the pairing keys managed by the `homed` system daemon which are gated by the `HomeKit` framework.

Fortunately, the HomeKit pairing keys are stored on the iCloud Keychain. Unfortunately (_but reasonably_), the system tries hard to hide these pairing keys. In particular, they do not appear on the Keychain app nor can be read with the `Security` framework `SecItem*` family of APIs without some private entitlements granting access to the keychain access group `com.apple.hap.pairing`.

### Process

First of all, we have to subvert the AMFI security model to be able to sign arbitrary executables with private entitlements. To do it, we have to disable **System Integrity Protection** and **AMFI**. Reboot on **Recovery OS** and on the Terminal app disable the protections:

```bash
$ csrutil disable
$ nvram boot-args=amfi_get_out_of_my_way=0x1
$ reboot
```

After reboot, we can use [**KeychainTool**](https://github.com/pvieito/KeychainKit) to dump the HomeKit keychain items. 

```bash
$ git clone https://github.com/pvieito/KeychainKit.git
$ cd KeychainKit
$ cat KeychainTool/KeychainTool.entitlements
# The all-powerful * `keychain-access-groups` entitlement which grants its bearer permission to read all keychain items:
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>keychain-access-groups</key>
    <array>
        <string>*</string>
    </array>
</dict>
</plist>
```

Now we will compile and run `KeychainTool` to dump all the keychain entries in the `com.apple.hap.pairing` access group.

Bear in mind that `KeychainTool` uses [`CodeSignKit`](https://github.com/pvieito/CodeSignKit) to self sign its executable with private entitlements before relaunching itself. If a `codesign` error is shown set the `CODESIGNKIT_DEFAULT_IDENTITY` environment variable to the name of your Apple Developer certificate as presented on the Keychain app.

```
$ swift run KeychainTool -g com.apple.hap.pairing
[*] HomeKit Pairing Identity (com.apple.hap.pairing)
[ ] Account: 7C73D188-BF12-4B8C-B7A5-5842D71C24EA
[ ] Key: “21159cfa6032438be197d668b3562e262441965789f95634d6460d4cce5cc706+d2ed8558b369b4ee1fbf4f9eb8d687ee2799aba5608efc2712d8175697bd8ad8”
[*] Paired HomeKit Accessory: CC:0D:07:E4:F7:54 (com.apple.hap.pairing)
[ ] Account: CC:0D:07:E4:F7:54
[ ] Key: “3a4473f1efe5e378fdd329826936f34b674fcb97c8aa5bd9818abde46963f864”
[*] Paired HomeKit Accessory: 58:CA:96:CE:66:5F (com.apple.hap.pairing)
[ ] Account: 58:CA:96:CE:66:5F
[ ] Key: “44d34407d583aee3b12b774a6eb15ee96c527fa83af1db66ac90f60494bbbc29”
```

*Voilà!* Here we have all we need:

- The **HomeKit Pairing Identity** entry contains the pairing identifier stored as the item account name: `7C73D188-BF12-4B8C-B7A5-5842D71C24EA` and the LTP and LTS keys required by the HomeKit protocol separated by a `+` sign on the entry key payload: `21159cfa6032438be197d668b3562e262441965789f95634d6460d4cce5cc706`, `d2ed8558b369b4ee1fbf4f9eb8d687ee2799aba5608efc2712d8175697bd8ad8 `
- Each accessory has its own **Paired HomeKit Accessory** keychain entry named containing its paring key. For example, the device `58:CA:96:CE:66:E9` has the LTP key `44d34407d583aee3b12b774a6eb15ee96c527fa83af1db66ac90f60494bbbc29`.

Now we can use the pairing keys to set up `homekit_python` on any device and platform:

```bash
$ python3 -m pip install "homekit[IP]" --user
$ python3 -m homekit.discover
# `homekit_python` will list all HomeKit devices found on the network:
Name: Eve Extend XXXX._hap._tcp.local.
Url: http_impl://192.168.0.123:8080
Configuration number (c#): 5
Feature Flags (ff): Supports HAP Pairing (Flag: 1)
Device ID (id): 58:CA:96:CE:66:5F  # Same Device ID as in the keychain entry.
Model Name (md): Eve Extend XXXXXXXX
Protocol Version (pvieito): 1.1
State Number (s#): 1
Status Flags (sf): Accessory has been paired. (Flag: 0)
Category Identifier (ci): Bridge (Id: 2)
$ mkdir ~/.homekit_python
$ python3 -m homekit.init_controller_storage -f ~/.homekit_python/pairing.json
$ nano ~/.homekit_python/pairing.json
# Write the pairing credentials of each the device:
{
  "EveExtend": {
    "AccessoryPairingID": "58:CA:96:CE:66:E9",
    "AccessoryLTPK": "44d34407d583aee3b12b774a6eb15ee96c527fa83af1db66ac90f60494bbbc29",
    "iOSPairingId": "7C73D188-BF12-4B8C-B7A5-5842D71C24EA",
    "iOSDeviceLTSK": "d2ed8558b369b4ee1fbf4f9eb8d687ee2799aba5608efc2712d8175697bd8ad8",
    "iOSDeviceLTPK": "21159cfa6032438be197d668b3562e262441965789f95634d6460d4cce5cc706",
    "AccessoryIP": "192.168.0.123",
    "AccessoryPort": 8080,
    "Connection": "IP"
  }
}
$ python3 -m homekit.identify -f ~/.homekit_python/pairing.json -a EveExtend
# The HomeKit device should identify itself (for example blinking an LED).
$ python3 -m homekit.get_accessories -f ~/.homekit_python/pairing.json -a EveExtend
# Shows all the accesories exposed by the device.
$ python3 -m homekit.get_characteristic -f ~/.homekit_python/pairing.json -a EveExtend -c 3.38
# Shows the value of a given characteristic, for example the room relative humidity:
{
    "3.38": {
        "value": 54.4525146484375
    }
}
```

Finally, remember to re-enable System Integrity Protection and reboot your Mac:

```bash
$ csrutil clear
```
