---
title: "Creating Apple Wallet Passes in Batch With MakePass and Shortcuts"
lang: en
---

!["MakePass – Pass Batch"](/media/2021/11/makepass-batch-header.png)

### TL;DR

You can download a shortcut [**here**][shortcut-url] that parses a `CSV` text and generates multiple passes in batch using [**MakePass**][makepass]:

<video autoplay loop playsinline style="width: 100%;" src="/media/2021/11/makepass-batch-shortcut-demo.mp4"></video>

### Introduction

[**MakePass**][makepass] is a mighty Apple Wallet pass editor, with it you can create and customize a myriad of passes with complex layouts including images, barcodes, colors and text fields, but its the most powerful and versatile feature is its integration with the [Shortcuts app](https://support.apple.com/guide/shortcuts/welcome/ios) in iOS, iPad and macOS.

!["MakePass – “Create Pass” Action"](/media/2021/11/makepass-batch-create-pass-action.png)

**MakePass** includes multiple Shortcuts actions that allow you to create, preview and share Apple Wallet passes. The **Create Pass** action exposes all the power of the MakePass pass editor directly in Shortcuts so you can customize all the properties of your pass. The **Create Pass** action includes an **Import Pass File** parameter so you can import a template pass file whose properties can be overridden by the action parameters.

### Process

First you should create a pass file which will be used as a template. This template file will be set in the **Import Pass File** parameter of the **Create Pass** action which uses it as the base canvas over which to override any other parameter. If you do not import a pass file, the **Create Pass** action will use a blank template by default. You can create your own pass template file in MakePass or use [this one](/resources/downloads/UpMarket_Coupon.pkpass.zip) as an example.

Afterwards, to create multiple Apple Wallet passes in batch you should import the information which will be inserted in the pass template for each new pass. To do so, we can use the popular `CSV` (comma-separated values) format:

```csv
John Appleseed,CD-0000001,2021-01-01 12:00
Chandler Bing,CD-0000002,2021-01-02 12:00
Mario Gómez,CD-0000003,2021-01-03 12:00
Ursula von Köriet,CD-0000004,2021-01-04 12:00
Alejandro Couñago,CD-0000005,2021-01-05 12:00
```

In this example `CSV` document, each line represent a pass which will be generated. Each line includes 3 components which will be added to the pass template (a name in a text field like `"John Appleseed"`, a QR barcode payload like `"CD-0000001"` and a relevant date like `"2021-01-01 12:00"`). To load this `CSV` in Shortcuts we can use the **Text** action, load the contents from a file or use the shortcut content input. In this example we will use the **Text** action.

Once we have the `CSV` text loaded in the shortcut, we can split it by line and iterate over them. In each iteration we can split the line by commas and we can extract each of the pass components needed to fill the pass template:

!["MakePass – Batch Shortcut – 1"](/media/2021/11/makepass-batch-shortcut-1.png)

After extracting the components we can simply set our pass template file in the **Import Pass File** parameter and fill the other custom parameters in the **Create Pass** action to complete the template:

!["MakePass – Batch Shortcut – 2"](/media/2021/11/makepass-batch-shortcut-2.png)

And _voilà!_, running the shortcut will generate 5 passes, each one with its own custom properties:

!["MakePass – Batch Shortcut – 3"](/media/2021/11/makepass-batch-shortcut-3.png)

To complete the shortcut, we can add an action to open the passes in macOS or share them with an app in iOS and iPadOS. You can also use the **Save File** action to store the pass files in your device.

!["MakePass – Batch Shortcut – 4"](/media/2021/11/makepass-batch-shortcut-4.png)

### Shortcut

You can get the complete shortcut [**here**][shortcut-url].

[makepass]: https://pvieito.com/apps?redirect=makepass&utm_campaign=pvieito-post-makepass-batch#app-makepass
[shortcut-url]: https://www.icloud.com/shortcuts/ccf22b148d6b40e3b211c412efea7958
