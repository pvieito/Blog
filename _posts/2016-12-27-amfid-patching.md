---
title: "Jailbreaking macOS: Patching AMFI to Allow Arbitrary Entitlements"
lang: en
---

!["Patching amfid"](/media/2017/01/amfid-header.png)

### Introduction

Entitlements are an important part of Apple Security architecture. They allow Apple to limit an OS feature to be only available to Apple-approved processes. Nowadays, even with System Integrity Protection disabled, the AMFI Kernel Extension and `amfid` process dueto will always kill any process at execution with restricted Entitlements not signed by Apple or with a properly Apple-approved embedded provisioning profile.

Unrestricted Entitlements are available to all signed binaries, even ad-hoc (some examples of this are the Sandbox entitlements `com.apple.security.*` or the application identifier one `com.apple.application-identifier`) but they do no give any special capability to the process, on the contrary, they limit its reach.

To allow any Entitlements, even the more interesting Restricted ones, for a Developer ID signed binary we have to modificate the `amfid` process (to allow adhoc signatures too we would have to patch the AMFI Kernel Extension or its underlying dependencies which I didn't try).

### Process

To patch a system daemon, we have to disable macOS System Integrity Protection. After some reverse engineering, it seems one of the main decisions in the `amfid` flow is in the address offset `0x347D`.

!["Patching amfid"](/media/2017/01/amfid-patch.png)
<figcaption>Decision flow of <code>amfid</code>.</figcaption>

Knowing that we can change the following two instructions from:

	test %r14, %r14
	je loc_100003531
	
To this:

	mov %r14, %r15
	jno loc_100003531
	
This way the flow will always jump to `loc_100003531` and `%r14` will become null (`%r15` is always null in this point), so every Developer ID signed process will be validated even without a provisioning profile allowing its Entitlements.

### Code

To achieve this modification we can go the hard way by modifing the binary _in situ_ (it is located at `/usr/libexec/amfid`) or the soft way: patching `amfid` memory at runtime. I preferred the second option so I could restart the unpatched `amfid` code by simply killing it.

To do it I ported to Python 3 a wrapper for Mach VM APIs called [pymach](https://github.com/abarnert/pymach) and added a new function to get the ASRL Slice Offset of the process: [PyMach for Python 3](https://github.com/pvieito/PyMach). With that I wrote this [script](https://gist.github.com/pvieito/c0c9b8fd73255b57927b273d329c5800) for macOS 10.12.2. To use it simply run:

	$ sudo ./amfid_patch.py
	
And answer `yes` when asked if you want to patch the process. _Voilà!_ Now any Developer ID signed binaries will be executed even with restricted Entitlements.

You can set any Entitlement you want, like `com.apple.developer.icloud-container-identifiers` or `com.apple.private.appleaccount.app-hidden-from-icloud-settings` with an arbitrary iCloud container. For a complete list of private Entitlements used by Apple you can go to Jonathan Levin's [Entitlements Database](http://newosxbook.com/ent.jl?osVer=OSX).

### References

* [*OS Internals: Volume III](http://newosxbook.com)
