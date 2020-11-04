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

## [1.0.1] - 2020.11.04

### Added

- Added removal of "Bearer " from beginning of `$jwt` string passed to the `decode` method in case the entire `Authorization` header was used.

## [1.0.0] - 2020.10.19

### Added

- Initial release.