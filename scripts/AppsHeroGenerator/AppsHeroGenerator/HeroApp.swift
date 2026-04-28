//
//  HeroApp.swift
//  AppsHeroGenerator
//
//  Created by Pedro José Pereira Vieito on 24/04/2020.
//  Copyright © 2017 Pedro José Pereira Vieito. All rights reserved.
//

import Foundation
import AppStoreSearchKit
import Plot

struct HeroApp {
    struct PlatformInfo {
        enum Platform: String, CaseIterable, CustomStringConvertible {
            case macOS
            case iOS
            case iPadOS
            case watchOS
            case tvOS
            case visionOS
            
            init(appStoreItemType: AppStoreSearchItem.ItemType) {
                switch appStoreItemType {
                case .iOS:
                    self = .iOS
                case .macOS:
                    self = .macOS
                }
            }
            
            var displayPriority: Int {
                switch self {
                case .macOS:
                    return 1
                case .iOS:
                    return 2
                case .iPadOS:
                    return 3
                case .visionOS:
                    return 4
                case .watchOS:
                    return 5
                case .tvOS:
                    return 6
                }
            }
            
            var displayLogoPriority: Int {
                return self.displayPriority
            }
            
            var description: String {
                return self.rawValue
            }
        }
        
        var platform: Platform
        var storeIdentifier: Int
        var storeURL: URL
        var orderScore: Double?
    }
    
    var name: String
    var subtitle: String
    var description: String
    var platformsInfo: [PlatformInfo]
    var orderScore: Double?
}

extension HeroApp {
    private var identifier: String {
        return name.lowercased()
    }
}

extension HeroApp {
    var htmlNode: Node<HTML.BodyContext> {
        let displayPlatformsInfo =
        self.platformsInfo.sorted(by: { $0.platform.displayPriority < $1.platform.displayPriority })
        let mostPopularPlatformInfo = displayPlatformsInfo
            .sorted(by: { $0.orderScore ?? 0 > $1.orderScore ?? 0 })[0]
        let appRedirectionURL = "/apps?redirect=\(self.identifier)#app-\(self.identifier)"
        return .div(
            .class("content"),
            .class("app-block"),
            .id("app-\(self.identifier)"),
            .attribute(named: "app-name", value: self.name),
            .attribute(named: "app-store-identifier", value: String(mostPopularPlatformInfo.storeIdentifier)),
            .attribute(named: "app-store-url", value: mostPopularPlatformInfo.storeURL.absoluteString),
            .style("margin: 3em 0 4em; overflow: auto;"),
            .div(
                .style("float: left; width: 30%;"),
                .a(
                    .class("app-block-link"),
                    .href(appRedirectionURL),
                    .picture(
                        .source(.srcset("/resources/apps/\(self.name).web.avif"), .type("image/avif")),
                        .source(.srcset("/resources/apps/\(self.name).web.webp"), .type("image/webp")),
                        .img(
                            .attribute(named: "style", value: "width: 85%; margin-left: 0;"),
                            .src("/resources/apps/\(self.name).web.png")
                        )
                    )
                )
            ),
            .div(
                .style("float: right; width: 70%; padding-top: 12px;"),
                .header(
                    .style("overflow: auto;"),
                    .h2(
                        .class("title"),
                        .style("margin-top: 0;"),
                        .a(
                            .class("app-block-link"),
                            .href(appRedirectionURL),
                            .text(self.name)
                        )
                    ),
                    .p(
                        .class("meta"),
                        .i(.span(.text(self.subtitle))),
                        .forEach(displayPlatformsInfo) { platformInfo in
                            return .span(
                                .class("lang"),
                                .text(platformInfo.platform.description)
                            )
                        }
                    )
                ),
                .div(
                    .class("content"),
                    .span(.text(self.description))
                )
            )
        )
    }
}
