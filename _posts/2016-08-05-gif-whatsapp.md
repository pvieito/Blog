---
title: GIF Support Coming to WhatsApp
lang: en
---

After our friends from [AndroidTR](https://androidtr.es/2016/08/03/whatsapp-ya-esta-preparado-enviar-gifs-primicia/) found some resources in the new update of WhatsApp for Android, I wanted to go deeper and analyze the iOS version to see if it was prepared to handle GIFs as well.

![New glyphs for GIF support](/media/2016/08/gif-resources.png)
<figcaption>New glyphs for GIF support.</figcaption>

In the last update of WhatsApp for iOS (version 2.16.7), there are the same three new GIF glyphs found in Android and two more, apparently for [Live Photos](https://www.youtube.com/watch?v=YRRBs71p7II) support.

![New methods and classes for GIF support](/media/2016/08/gif-executable.png)
<figcaption>New methods and classes for GIF support.</figcaption>

Then, in the app binary, there are a lot of new methods designed to handle GIFs. They have even created a new class, `WAGIFCreator`, which seems to allow users to create GIFs from scratch with various images or a video.

It seems this feature is complete, but the WhatsApp team has to enable it remotely, as they have previously done with other capabilities like end-to-end encryption.
