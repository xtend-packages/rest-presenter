# Changelog for RESTPresenter

## [0.3.2](https://github.com/xtend-packages/rest-presenter/compare/0.3.1...0.3.2) (2024-04-02)


### Bug Fixes

* Correct namespace for DB not Lunar 👀 ([c5857b1](https://github.com/xtend-packages/rest-presenter/commit/c5857b11c89905264c2f891d18a06d4d92f1ece0))

## [0.3.1](https://github.com/xtend-packages/rest-presenter/compare/0.3.0...0.3.1) (2024-04-02)


### Bug Fixes

* Issues with installer after modifying types of stubs ([b66e3a7](https://github.com/xtend-packages/rest-presenter/commit/b66e3a7d6febc348f22f7da2cfe78a75035d37a2))

## [0.3.0](https://github.com/xtend-packages/rest-presenter/compare/0.2.5...0.3.0) (2024-03-31)


### Features

* Generate filters based on the type of relation ([0db1e35](https://github.com/xtend-packages/rest-presenter/commit/0db1e35daa2746b52ec61eeaec05a950fc013a0b))
* Presenters with Data InteractsWithDbSchema ([181a90e](https://github.com/xtend-packages/rest-presenter/commit/181a90ede6b7173681374da9ca0bcc4da98f40fc))


### Bug Fixes

* Allow nullable dates ([09cb3cb](https://github.com/xtend-packages/rest-presenter/commit/09cb3cba4666e83a2fdea56c2f1d95eca644dd01))
* Camel case DTO not currently supported with model attributes ([3216ec3](https://github.com/xtend-packages/rest-presenter/commit/3216ec33734f59a9759724b310a767e578a00737))
* HasMany filter stub called wrong method ([ee2ca55](https://github.com/xtend-packages/rest-presenter/commit/ee2ca5552084877b2a0f29270331eac03ce700fe))
* Need the correct presenterKey to apply presenter ([4595d60](https://github.com/xtend-packages/rest-presenter/commit/4595d6027333071935c0fa945aa2d69a56ecb3ac))
* phpstan errors ([18375e3](https://github.com/xtend-packages/rest-presenter/commit/18375e319f3fa5a94a4aa60bbf73b14da94aa2bf))
* Remove Filters and Presenters namespace not needed here ([66fa2a5](https://github.com/xtend-packages/rest-presenter/commit/66fa2a5977e53f667a4e2db85ca12914f370af6e))
* Remove XtendRouter Custom path ([5383447](https://github.com/xtend-packages/rest-presenter/commit/538344737ff51ee17e1c36863772536e0a7e2cef))
* Support json to pass through as value ([801692d](https://github.com/xtend-packages/rest-presenter/commit/801692d63fb29089d431c673195b18fda39a1309))
* Tests ([6c03952](https://github.com/xtend-packages/rest-presenter/commit/6c039528d13511e4c31b8817a64261cc63bea972))


### Documentation

* Update README for release ([afa929b](https://github.com/xtend-packages/rest-presenter/commit/afa929b682201e34c271b485cc76bbdef37ce115))
* Update README.md to include Resource Generation ([03990b4](https://github.com/xtend-packages/rest-presenter/commit/03990b4f99b189ef204156e3780155e95529ffa8))

## [0.2.5](https://github.com/xtend-packages/rest-presenter/compare/0.2.4...0.2.5) (2024-03-28)


### Bug Fixes

* Load breeze routes directly from XtendRouter ([6d8b052](https://github.com/xtend-packages/rest-presenter/commit/6d8b052fe017d816860cbe4c338073c2a3a21c23))

## [0.2.4](https://github.com/xtend-packages/rest-presenter/compare/0.2.3...0.2.4) (2024-03-28)


### Bug Fixes

* Route register with correct name and prefix ([18eb1ec](https://github.com/xtend-packages/rest-presenter/commit/18eb1ec248a65034b378d9cfbae75d0c0e22a3dc))
* use getAttribute for the default model response ([47e1d3e](https://github.com/xtend-packages/rest-presenter/commit/47e1d3e2d3f6079287c3d614f4abb8a93a99b062))
* Users grouped by Auth middleware & register XtendRouter for Tests ([06cfe19](https://github.com/xtend-packages/rest-presenter/commit/06cfe19de4735169c29928cf1abe534a7388e755))

## [0.2.3](https://github.com/xtend-packages/rest-presenter/compare/0.2.2...0.2.3) (2024-03-27)


### Bug Fixes

* lunarphp/core move to dev dependency ([1c14c78](https://github.com/xtend-packages/rest-presenter/commit/1c14c78b42f232cec812cbf7240beb0b1413aecb))
* Make sure custom resources directory is created with setup ([724a347](https://github.com/xtend-packages/rest-presenter/commit/724a347148941a4675627f5b8db42c7e3fa79094))


### Code Refactoring

* Remove api routes register via XtendRouter with AutoDiscover ([19a0b40](https://github.com/xtend-packages/rest-presenter/commit/19a0b4019c82614bf9099da91a639bf4912e6ee0))

## 0.2.2 (2024-03-27)


### Code Refactoring

* XtendRoute more control over routes with better IDE support ([f0843cc](https://github.com/xtend-packages/rest-presenter/commit/f0843cc837394f460405d91822834dd2e058a8fd))

# Changelog for REST Presenter

## 0.2.1 (26-03-2024)

Alpha release:

### Fixes

* Move Lunar to suggest dependency
* file_exist instead of class_exist to prevent errors

## 0.2.0 (26-03-2024)

Alpha release:

### Features

* Lunar API Starter Kit
* RESTPresenter Setup Command 
* Extend API Kits

## 0.1.0 (22-03-2024)

Initial pre-alpha release:

### Features

* Initial Setup Command
* PEST Test Setup + Testbench env
* Resource Base Controller
* Traits + Tests for Resource Controller
* Install Sanctum
* API Route Setup
* Install Laravel Data + Default Data Layers & Tests 
* Default Presenter + Tests
* Setup Config + Middleware
* Users Resource + Tests
* Setup Test User Model + Factory
* PEST Test Helper File
* Auth Breeze Starter Kit

### Continuous Integration Setup

* Setup Code Stying Workflow (Github Action)
* Setup Package Tests Workflow (Github Action)
