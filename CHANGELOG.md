# Changelog for RESTPresenter

## [0.7.4](https://github.com/xtend-packages/rest-presenter/compare/0.7.3...0.7.4) (2024-04-27)


### Bug Fixes

* Update provider stub replace breeze with sanctum kit ([899fa3a](https://github.com/xtend-packages/rest-presenter/commit/899fa3aaa4b42ba509da1e805473c62e4e6c9d32))

## [0.7.3](https://github.com/xtend-packages/rest-presenter/compare/0.7.2...0.7.3) (2024-04-27)


### Code Refactoring

* Filament scan for app class definitions + check if installed ([a3e55cd](https://github.com/xtend-packages/rest-presenter/commit/a3e55cd0a48613cd549456d0cc3b6e935e90fa8e))

## [0.7.2](https://github.com/xtend-packages/rest-presenter/compare/0.7.1...0.7.2) (2024-04-26)


### Bug Fixes

* Include TypeGuard directly remove dep until package is released ([42196d6](https://github.com/xtend-packages/rest-presenter/commit/42196d664003f6eb5908f918b60099f5706206d9))

## [0.7.1](https://github.com/xtend-packages/rest-presenter/compare/0.7.0...0.7.1) (2024-04-18)


### Bug Fixes

* Make sure lunar tests all pass ([1ce9a47](https://github.com/xtend-packages/rest-presenter/commit/1ce9a470e2026847baaa76ae730f3465559e1ef8))

## [0.7.0](https://github.com/xtend-packages/rest-presenter/compare/0.6.1...0.7.0) (2024-04-17)


### Features

* phpstan max level + type-guard ([a779bec](https://github.com/xtend-packages/rest-presenter/commit/a779becf8dc39cc61b45c9eeff7a98b2e513df5f))


### Bug Fixes

* If we have not filters return empty array ([66db589](https://github.com/xtend-packages/rest-presenter/commit/66db5899cc975706d4b1d6c49195521666548a6f))
* Install & configure rector for laravel + apply fixes ([e305e0c](https://github.com/xtend-packages/rest-presenter/commit/e305e0c589507aaf9e5a4dfa5fdfcca4b82e11e3))
* Resolve issues raised by phpstan ([9f30742](https://github.com/xtend-packages/rest-presenter/commit/9f3074204648c583b4efc0403206bf658152d58b))
* Resolve TypeError when passing optional arguments ([af19de1](https://github.com/xtend-packages/rest-presenter/commit/af19de10b1ec820cda0be6f70fd7a889f5855010))
* Setup strict pint rules + apply fixes ([a607023](https://github.com/xtend-packages/rest-presenter/commit/a6070230915f1c04450318eed2fb293b5d15222e))
* Tests now run but some fail after refactor ([1f4f75d](https://github.com/xtend-packages/rest-presenter/commit/1f4f75d91bfb78ed393318017b71bee2a85b29a7))

## [0.6.1](https://github.com/xtend-packages/rest-presenter/compare/0.6.0...0.6.1) (2024-04-14)


### Bug Fixes

* For those not using filament now checks if the resources exist ([4a64afe](https://github.com/xtend-packages/rest-presenter/commit/4a64afe669db1344a86819a1cb4770edc35414a8))

## [0.6.0](https://github.com/xtend-packages/rest-presenter/compare/0.5.3...0.6.0) (2024-04-14)


### Features

* Reset password and generate temp password to login ([5022992](https://github.com/xtend-packages/rest-presenter/commit/5022992db55c8f34cd6496d791971cdb0ee424ea))
* Update Sanctum Auth Kit + Register Route Actions ([7b1d965](https://github.com/xtend-packages/rest-presenter/commit/7b1d9653771b74be7398051611a69e2cba7d4c71))


### Bug Fixes

* Allow actions to set auth middleware + translatable message ([6352404](https://github.com/xtend-packages/rest-presenter/commit/6352404023bdec1a03c601082a6243c4bef8b985))
* AutoDiscovery with middleware ([312ef14](https://github.com/xtend-packages/rest-presenter/commit/312ef14c0a35ff85660a616f570a645c45441eb5))
* Temp remove verification email does not work with token auth flow ([489b99d](https://github.com/xtend-packages/rest-presenter/commit/489b99db6db4e7c859405d951cc6503cf81a88b5))

## [0.5.3](https://github.com/xtend-packages/rest-presenter/compare/0.5.2...0.5.3) (2024-04-07)


### Bug Fixes

* All custom resources and presenters are now generated correctly ([aea569a](https://github.com/xtend-packages/rest-presenter/commit/aea569a819289334b8a32fc7a8c455e8c3f29f2e))

## [0.5.2](https://github.com/xtend-packages/rest-presenter/compare/0.5.1...0.5.2) (2024-04-07)


### Bug Fixes

* Support multi-word resources ([898f7ea](https://github.com/xtend-packages/rest-presenter/commit/898f7eab1cdc2dd79e186b9d7fbd6f0c86781722))

## [0.5.1](https://github.com/xtend-packages/rest-presenter/compare/0.5.0...0.5.1) (2024-04-07)


### Bug Fixes

* sqlite nullable check condition ([ce12803](https://github.com/xtend-packages/rest-presenter/commit/ce1280321dd0c911f4fc1d341d360d108ae0c906))
* Temp disable TS formatting as it conflicts with .prettierrc.js ([27cb548](https://github.com/xtend-packages/rest-presenter/commit/27cb548d196a5d2977a7d096272161f0cb382ce5))


### Documentation

* Example RESTPresenter insomnia collection ([6dcd24d](https://github.com/xtend-packages/rest-presenter/commit/6dcd24dcbfb742bf3d86256ecd05103e1f5c4472))
* Inform users about minimum stability requirement ([ab327e6](https://github.com/xtend-packages/rest-presenter/commit/ab327e68078127c3159bb096c86213b890c38d7b))
* Update docs explain about presenters + update starter kits links ([a5dcc43](https://github.com/xtend-packages/rest-presenter/commit/a5dcc43c2e830f21f57dab58f12b12aa1abeaf7b))

## [0.5.0](https://github.com/xtend-packages/rest-presenter/compare/0.4.0...0.5.0) (2024-04-06)


### Features

* Add TS support to generate data ([62e84cb](https://github.com/xtend-packages/rest-presenter/commit/62e84cb53f9c00cfe3ecdefde56acb294ccbd698))
* TypeScript now generated for data with our own custom writer ([597c17b](https://github.com/xtend-packages/rest-presenter/commit/597c17b67cdd93e21e7f251d43649021cfa66864))


### Bug Fixes

* Move spatie typescript transformer can not be a dev dependency ([ed67257](https://github.com/xtend-packages/rest-presenter/commit/ed67257d2bfde273cf85113a7fa6ccd837960f3f))
* User resource needs to use package model ([927e97f](https://github.com/xtend-packages/rest-presenter/commit/927e97fcbd6c35317b8bae6e7dabe55db5abb0b1))

## [0.4.0](https://github.com/xtend-packages/rest-presenter/compare/0.3.4...0.4.0) (2024-04-05)


### Features

* Auto generates presenter & DTO from column properties ([84c06cf](https://github.com/xtend-packages/rest-presenter/commit/84c06cfb858574c2459df6ef59a2ca9afced796c))
* Fields returned now in the correct table order + prepend id ([64d774a](https://github.com/xtend-packages/rest-presenter/commit/64d774abd803dc3d78f04b8b254e911a3b3b265b))
* Filament auto-discover resources ([227807a](https://github.com/xtend-packages/rest-presenter/commit/227807a5a9892d4b5314124cb8f531f410047095))
* Register filament route resources ([35a7592](https://github.com/xtend-packages/rest-presenter/commit/35a7592f878330cab6884068b8116bb2f3952de3))


### Bug Fixes

* phpstan ignore ([205757b](https://github.com/xtend-packages/rest-presenter/commit/205757bd7273a3ac1ff3691cb69535bc214bfb44))
* ResourceController to allow middleware + sanctum UserResource ([89572ed](https://github.com/xtend-packages/rest-presenter/commit/89572edf2bc3e0fde38a528b37026d9ec53f271e))
* Routes need to registered after package config is registered ([23fb789](https://github.com/xtend-packages/rest-presenter/commit/23fb7897e7616a6ff500a4408506ec438e74e1ec))
* Sqlite workaround to set json field type ([14b01c1](https://github.com/xtend-packages/rest-presenter/commit/14b01c1593a4a0f13af95b2c8ebba2ba9f8ca3b5))
* Support for nullable types prepends question mark ([a29b881](https://github.com/xtend-packages/rest-presenter/commit/a29b88169088f441a14175f0b5a7704ad84f7b9f))

## [0.3.4](https://github.com/xtend-packages/rest-presenter/compare/0.3.3...0.3.4) (2024-04-04)


### Bug Fixes

* Allow to define middleware on controllers ([cc4cf55](https://github.com/xtend-packages/rest-presenter/commit/cc4cf558f0aa166785ea7becc5e6021e88cfc40e))
* Default response to exclude hidden fields ([8434f92](https://github.com/xtend-packages/rest-presenter/commit/8434f9217a79ddbc5a5c11c4b9e16e85e92cad6b))

## [0.3.3](https://github.com/xtend-packages/rest-presenter/compare/0.3.2...0.3.3) (2024-04-03)


### Bug Fixes

* Extending kits pass extend type to get correct stub ([60378e9](https://github.com/xtend-packages/rest-presenter/commit/60378e9df7058fe89ccf7d9d04d182eb29c9287a))
* We should not prompt for resource when extending kits ([052a675](https://github.com/xtend-packages/rest-presenter/commit/052a675874d5b5d2f493944d35ab8e5338ed3073))

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
