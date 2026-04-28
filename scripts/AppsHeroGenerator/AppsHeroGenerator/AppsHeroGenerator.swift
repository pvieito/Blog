//
//  AppsHeroGenerator.swift
//  AppsHeroGenerator
//
//  Created by Pedro José Pereira Vieito on 11/9/17.
//  Copyright © 2017 Pedro José Pereira Vieito. All rights reserved.
//

import Foundation
import FoundationKit
import LoggerKit
import AppStoreSearchKit
import Plot
import PythonKit
import ArgumentParser

@main
struct AppsHeroGenerator: ParsableCommand {
    static var configuration: CommandConfiguration {
        return CommandConfiguration(commandName: String(describing: Self.self))
    }
    
    @Option(name: .shortAndLong, parsing: .upToNextOption, help: "Input query.")
    var input: [String] = []
    
    @Option(name: .shortAndLong, help: "App Store country code.")
    var country: String?

    @Option(name: .shortAndLong, help: "Filter by developer name.")
    var developer: String?

    @Option(name: .shortAndLong, parsing: .upToNextOption, help: "Lookup apps by store ID.")
    var lookup: [Int] = []

    @Option(name: .shortAndLong, parsing: .upToNextOption, help: "Exclude apps.")
    var exclude: [String] = []
    
    @Option(name: .shortAndLong, parsing: .upToNextOption, help: "Platforms supported by each app.")
    var platforms: [String] = []

    @Option(name: .shortAndLong, parsing: .upToNextOption, help: "Score of each app.")
    var scores: [String] = []

    @Option(name: [.customShort("r"), .long], help: "Import scores from an App Store Connect report.")
    var scoresReport: String?

    @Option(name: .shortAndLong, help: "Output hero HTML path.")
    var output: String?
    
    @Flag(name: .shortAndLong, help: "Verbose mode.")
    var verbose: Bool = false
    
    func validate() throws {
        guard !self.input.isEmpty || !self.lookup.isEmpty else {
            throw ValidationError("No input or lookup specified.")
        }
    }
    
    func run() throws {
        do {
            Logger.logMode = .commandLine
            Logger.logLevel = verbose ? .debug : .info
            
            var items: [AppStoreSearchItem] = []
            for searchQuery in self.input {
                items += try AppStoreSearchManager.searchStore(with: searchQuery, country: self.country)
            }

            if !self.lookup.isEmpty {
                items += try AppStoreSearchManager.lookupStore(identifiers: self.lookup)
            }

            items = items.dropDuplicates()

            if let developer = self.developer {
                items = items.filter {
                    $0.seller?.range(of: developer, options: [.caseInsensitive, .diacriticInsensitive]) != nil
                }
            }

            guard !items.isEmpty else {
                Logger.log(notice: "No results available.")
                return
            }
                        
            var heroApps: [String: HeroApp] = [:]
            
            for item in items {
                if !self.exclude.isEmpty {
                    var matched = false
                    for filterOutPattern in self.exclude {
                        if item.name.matchesRegularExpression(pattern: filterOutPattern) {
                            matched = true
                            break
                        }
                    }
                    
                    if matched {
                        continue
                    }
                }
                
                let name = item.name.split(separator: " ").first!.trimmingCharacters(in: CharacterSet.alphanumerics.inverted)
                
                var subtitle = item.name.replacingOccurrences(of: name, with: "").trimmingCharacters(in: CharacterSet.alphanumerics.inverted)
                if subtitle.isEmpty {
                    subtitle = name
                }
                
                let briefDescription = item.briefDescription ?? ""
                
                let heroAppIdentifier = name.lowercased()
                
                var itemScore = Double(item.userRatingCount ?? 0) * (item.userRating ?? 0) / 5
                
                
                var scores = self.scores
                if let scoresReportPath = self.scoresReport {
                    let pd = try Python.attemptImport("pandas")
                    let df = pd.read_csv(scoresReportPath, skiprows: 3)
                    scores = Array<String>((df[df.columns[4]].astype("int").astype("str") + ":" + df[df.columns[5]].astype("str")).tolist())!
                }
                for score in scores {
                    let scoreComponents = score.split(separator: ":")
                    guard scoreComponents.count == 2,
                        let scoreStoreIdentifier = Int(scoreComponents[0]),
                        scoreStoreIdentifier == item.identifier else {
                        continue
                    }
                    
                    if let scoreValue = Double(scoreComponents[1]) {
                        itemScore = scoreValue
                    }
                }
                
                var heroApp: HeroApp
                if let baseHeroApp = heroApps[heroAppIdentifier] {
                    heroApp = baseHeroApp
                    heroApp.orderScore = (heroApp.orderScore ?? 0) + itemScore
                }
                else {
                    heroApp = HeroApp(
                        name: name,
                        subtitle: subtitle,
                        description: briefDescription,
                        platformsInfo: [],
                        orderScore: itemScore)
                }

                let platform = HeroApp.PlatformInfo.Platform(appStoreItemType: item.kind)
                let platformInfo = HeroApp.PlatformInfo(
                    platform: platform,
                    storeIdentifier: item.identifier,
                    storeURL: item.storeURL!,
                    orderScore: itemScore)
                var platformInfoArray: [HeroApp.PlatformInfo] = []
                
                for platform in self.platforms {
                    let platformComponents = platform.split(separator: ":")
                    guard platformComponents.count == 2,
                        let platformStoreIdentifier = Int(platformComponents[0]),
                        platformStoreIdentifier == platformInfo.storeIdentifier else {
                        continue
                    }
                    
                    for platformName in platformComponents[1].split(separator: ",") {
                        var platformInfo = platformInfo
                        if let platform = HeroApp.PlatformInfo.Platform(rawValue: String(platformName)) {
                            platformInfo.platform = platform
                            platformInfoArray.append(platformInfo)
                        }
                    }
                }
                
                if platformInfoArray.isEmpty {
                    platformInfoArray.append(platformInfo)
                }
                
                for platformInfo in platformInfoArray {
                    if !heroApp.platformsInfo.map(\.platform).contains(platformInfo.platform) {
                        heroApp.platformsInfo.append(platformInfo)
                    }
                }
                
                heroApps[heroAppIdentifier] = heroApp
            }
                        
            let articleHeroApps = Array(heroApps.values)
                .sorted(by: { $0.name < $1.name })
                .sorted(by: { $0.orderScore ?? 0 > $1.orderScore ?? 0 })
            
            for articleHeroApp in articleHeroApps {
                Logger.log(important: articleHeroApp.name)
                Logger.log(info: "Subtitle: \(articleHeroApp.subtitle)")
                Logger.log(info: "Order Score: \(articleHeroApp.orderScore ?? 0)")
            }
            
            let articleNode = Node<HTML.BodyContext>.article(
                .id("app-blocks"),
                .attribute(named: "lang", value: "en"),
                .forEach(articleHeroApps, \.htmlNode))
            let outputHTML = articleNode.render(indentedBy: .spaces(4))
            
            if let outputPath = self.output {
                do {
                    let outputURL = URL(fileURLWithPath: outputPath)
                    Logger.log(verbose: "Hero HTML:\n\(outputHTML)")
                    
                    try outputHTML.write(to: outputURL, atomically: true, encoding: .utf8)
                    Logger.log(success: "Generated Hero HTML saved at “\(outputURL.path)”.")
                }
                catch {
                    Logger.log(error: error)
                }
            }
            else {
                print(outputHTML)
            }
        }
        catch {
            Logger.log(fatalError: error)
        }
    }
}

