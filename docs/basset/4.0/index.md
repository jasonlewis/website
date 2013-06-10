title: Basset Documentation
---
## <a name="introduction"></a> Introduction

Basset is a better asset management package for the Laravel framework. Basset shares the same philosophy as Laravel. Development should be an enjoyable and fulfilling experience. When it comes to managing your assets it can become quite complex and a pain in the backside. These days developers are able to use a range of pre-processors such as Sass, Less, and CoffeeScript. Basset is able to handle the processing of these assets instead of relying on a number of individual tools.

<a name="features"></a>
## Features

- Organize assets into collections.
- Build and serve static assets within your production environment.
- Build assets individually within your development environment to maintain debug tool friendliness.
- Apply a range of filters using the powerful Assetic library.
- Pre-compress assets with Gzip.
- Deploy built collections to a Content Delivery Network.

<a name="known-issues"></a>
## Known Issues

- Unknown issue with `Assetic\Filter\StylusFilter` on Windows environment.
- Unknown issue with `Assetic\Filter\LessFilter` (not the `LessphpFilter`) on Windows environment.

<a name="changes"></a>
## Changes

- [Basset 4.0.0 Beta 1](#changes-4.0.0-beta-1)

<a name="changes-4.0.0-beta-1"></a>
#### Basset 4.0.0 Beta 1

- Collections are displayed with `basset_javascripts()` and `basset_stylesheets()`.
- Simplified the asset finding process.
- Can no longer prefix paths with `path:` for an absolute path, use a relative path from public directory instead.
- Requirements can be applied to filters to prevent application if certain conditions are not met.
- Filters can find any missing constructor arguments such as the path to Node, Ruby, etc.
- Default `application` collection is bundled.
- `basset:compile` command is now `basset:build`.
- Old collection builds are cleaned automatically but can be cleaned manually with `basset --tidy-up`.
- Packages can be registered with `Basset::package()` and assets can be added using the familiar namespace syntax found throughout Laravel.
- `Csso` support with `CssoFilter`.
- Fixed issues with `UriRewriteFilter`.
- Development collections are pre-built before every page load.
- Build and serve pre-compressed collections.
- Use custom format when displaying collections.
- Added in Blade view helpers: `@javascripts`, `@stylesheets`, and `@assets`.
- Assets maintain the order that they were added.