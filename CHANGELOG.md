# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

- `Added` for new features.
- `Changed` for changes in existing functionality.
- `Deprecated` for soon-to-be removed features.
- `Removed` for now removed features.
- `Fixed` for any bug fixes.
- `Security` in case of vulnerabilities

## [2.0.0] - 2023.01.26

### Added

- Added support for PHP 8.

## [1.1.1] - 2021.03.23

### Fixed

- Fixed bug where `iat` and `nbf` claims were not validating due to time drift.
Validation now allows a window of 10 seconds.

## [1.1.0] - 2020.11.15

### Added

- Added the ability to optionally validate signature and claims using the `decode` method.
This is useful as it allows the contents of the JWT to be returned, even if it is invalid or expired.

- Added the methods `validateSignature` and `validateClaims`.

## [1.0.1] - 2020.11.04

### Added

- Added removal of "Bearer " from beginning of `$jwt` string passed to the `decode` method in case the entire `Authorization` header was used.

## [1.0.0] - 2020.10.19

### Added

- Initial release.