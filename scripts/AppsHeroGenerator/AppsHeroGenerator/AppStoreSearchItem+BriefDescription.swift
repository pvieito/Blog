//
//  AppStoreSearchItem+BriefDescription.swift
//  AppsHeroGenerator
//
//  Created by Pedro José Pereira Vieito on 11/9/25.
//  Copyright © 2025 Pedro José Pereira Vieito. All rights reserved.
//

import Foundation
import AppStoreSearchKit

extension AppStoreSearchItem {
    private static let blockedLinePrefixes = ["NOTE:"]
    
    var briefDescription: String? {
        guard let description = self.description else {
            return nil
        }
        
        let lines = description.components(separatedBy: .newlines)
        var cleanedLines: [String] = []
        
        for line in lines {
            let trimmedLine = line.trimmingCharacters(in: .whitespacesAndNewlines)
            
            if trimmedLine.isEmpty {
                continue
            }
            
            if Self.blockedLinePrefixes.contains(where: { trimmedLine.hasPrefix($0) }) {
                continue
            }
            
            cleanedLines.append(trimmedLine)
            break
        }
        
        guard var result = cleanedLines.first?.trimmingCharacters(in: .whitespacesAndNewlines), !result.isEmpty else {
            return nil
        }
        
        if result.hasSuffix(":") {
            result = result.components(separatedBy: ". ").dropLast().joined(separator: ". ") + "."
        }
        
        return result
    }
}