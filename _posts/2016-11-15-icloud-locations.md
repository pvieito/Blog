---
title: iCloud Locations in macOS
lang: en
---

## Sandboxed vs. Non Sandboxed

In macOS a given app can or cannot be sandboxed and since macOS Sierra non App Store apps can access iCloud APIs so could be a non-sandboxed app using this locations to sync data.

So if you want to access a sandboxed iCloud location you should go to the app container and use it as the home path.

	$SANDBOXED_CONTAINER = ~/Library/Container/com.example.app/Data

## iCloud Documents

Each app can have one or more document containers inside named with its Bundle ID:

	~/Library/Mobile Documents/
	
The infomation and details of each container are stored here:

	~/Library/Application Support/CloudDocs/session/containers

The info is stored in a plist file with the name of the container for example:

	iCloud.com.pvieito.Example.plist

And the icons are stored in a folder with the name of the container:

	iCloud.com.pvieito.Example/40x40_iOS.png
	iCloud.com.pvieito.Example/80x80_iOS.png
	iCloud.com.pvieito.Example/120x120_iOS.png

## Key-Value Store

This synced data is stored as a property list file in:

	~/Library/SyncedPreferences/
	$(SANDBOXED_CONTAINER)/Library/SyncedPreferences/

## CloudKit

CloudKit works online but macOS stores a cache of its contents in a folder appropriately called *CloudKit*:

	~/Caches/CloudKit
	$(SANDBOXED_CONTAINER)/CloudKit
