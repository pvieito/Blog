---
title: "ChatGPT for Mac app was storing conversations in an unprotected location"
lang: en
---

_This is a recap of [some posts I published on Threads during the past week][threads]._

### TL;DR

The OpenAI ChatGPT for Mac app stored user conversations in plain text in a non-protected location, making them accessible to any running app or malware. After public disclosure, OpenAI released an update encrypting the conversations but did not implement sandboxing.

### Introduction

The OpenAI ChatGPT app on macOS is not sandboxed and stored all conversations in **plain text** in a non-protected location:

```bash
~/Library/Application Support/com.openai.chat/conversations-{uuid}/
```

This approach is somewhat typical for non-sandboxed apps on macOS, but a high-profile chat app like ChatGPT should be more careful with user data. For example, Apple started blocking access to user private data 6 years ago with the introduction of macOS 10.14 Mojave. Before that, any non-sandboxed app could access any user file. With macOS Mojave, Apple began requiring explicit user permission to access sensitive files like the Calendar, Contacts, Mail or Messages databases. Later, Apple extended this requirement to the Desktop and Documents directories, and with macOS 14 Sonoma, any file stored by a sandboxed third-party app in its sandbox container is automatically protected. This protection prevents malware or untrusted apps from exfiltrating user data without triggering a permission prompt.

Unfortunately, OpenAI opted out of sandboxing the ChatGPT app on macOS and stored conversations in plain text in a non-protected location, disabling all these built-in defenses. This meant that any running app, process, or malware could read all your ChatGPT conversations without any permission prompt.

### Example

Here you can see how easily any other app could access any ChatGPT conversation without any permission prompt:

<center>
<video autoplay loop playsinline muted style="max-width: 100%;" src="/media/2024/07/chatgptstealer-demo.mp4"></video>
</center>

You can check the source code of this demo app, [**ChatGPTStealer**, on GitHub](https://github.com/pvieito/ChatGPTStealer).

### Aftermath

Initially, I reported this issue to OpenAI through their [security bug reporting account in BugCrowd](https://bugcrowd.com/openai), but they marked the report as "Not Applicable" as "in-order for an attacker to leverage this, they would need physical access to the victim's device."

As I disagreed with that consideration, I decided to post this issue publicly on [Threads][threads] & [Mastodon][mastodon] to raise awareness and encourage OpenAI to fix this issue and hopefully sandbox the ChatGPT app on macOS. These posts gained attention and were eventually covered by [The Verge](https://www.theverge.com/2024/7/3/24191636/openai-chatgpt-mac-app-conversations-plain-text), [Ars Technica](https://arstechnica.com/ai/2024/07/chatgpts-much-heralded-mac-app-was-storing-conversations-as-plain-text/), [9to5Mac](https://9to5mac.com/2024/07/03/chatgpt-macos-conversations-plain-text/), and others.

Following these publications, OpenAI finally acknowledged the issue and released ChatGPT 1.2024.171 for Mac, which now encrypts the conversations. The conversations are now stored in a new location:

```bash
~/Library/Application Support/com.openai.chat/conversations-v2-{uuid}/
```

These files are now encrypted with a key named `com.openai.chat.conversations_v2_cache` stored securely in the macOS Keychain and the old plain-text conversations are removed after upgrading to the new version. However, the app is still not sandboxed, so the conversations are still stored in a non-protected location, but now at least encrypted so other apps can't read them without user-granted access to the Keychain key.

Interestingly, [macOS Sequoia will introduce Group Containers](https://developer.apple.com/wwdc24/10123?time=743), so non-sandboxed apps like ChatGPT could improve their security by moving sensitive data to a Group Container directory. This way, any other process or app trying to access the data would be blocked by the system, and a permission prompt would be presented to the user.

[threads]: https://www.threads.net/@pvieito/post/C85NVV6hvF6?xmt=AQGz2aGyO79t7rDf_sy_9CCnwG61rDGEsdERLEa6TbqZ0g
[mastodon]: https://mastodon.social/@pvieito/112713171065724442