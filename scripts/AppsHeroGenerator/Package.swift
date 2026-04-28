// swift-tools-version:5.9

import PackageDescription

let package = Package(
    name: "AppsHeroGenerator",
    platforms: [
        .macOS(.v13)
    ],
    products: [
        .executable(name: "AppsHeroGenerator", targets: ["AppsHeroGenerator"]),
    ],
    dependencies: [
        .package(url: "git@github.com:pvieito/LoggerKit.git", branch: "master"),
        .package(url: "git@github.com:pvieito/FoundationKit.git", branch: "master"),
        .package(url: "git@github.com:pvieito/AppStoreSearchKit.git", branch: "master"),
        .package(url: "https://github.com/apple/swift-argument-parser", from: "1.0.0"),
        .package(url: "git@github.com:pvieito/PythonKit.git", branch: "master"),
        .package(url: "https://github.com/johnsundell/Plot.git", from: "0.1.0")
    ],
    targets: [
        .executableTarget(
            name: "AppsHeroGenerator",
            dependencies: ["LoggerKit", "FoundationKit", "AppStoreSearchKit", "Plot", "PythonKit", .product(name: "ArgumentParser", package: "swift-argument-parser")],
            path: "AppsHeroGenerator"
        )
    ]
)
