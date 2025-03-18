# Changelog

## v3.3.0

- Add Laravel ^12.0 compatibility

## v3.2.0

- Add Laravel ^11.0 compatibility

## v3.1.0

- Add Laravel ^10.0 compatibility
- Set minimum Laravel version to ^9.33

## v3.0.0

- Add Laravel ^9.0 compatibility
- Remove PHP 7.4 compatibility
- Remove Laravel ^7.0 compatibility

## v2.0.2

- Filter values are now stored in a collection
- Move to PHPCSFixer 3.0.*

## v2.0.0

- [BC] Remove the various builder shortcuts, and instead just use the builder dynamically
- [BC] Minimum PHP version defined at 8.0.0
- [BC] Minimum Laravel version defined at 7.29.0
- Allow using an array to create a query filter
- Add a config file to determine the default path in request parameters
- Add a command to create a QueryFilter
- Enhance tests

## 1.1.0 - 2016-05-12

- Add tests
- Fix PostgreSQL issues with the `like()` helper
- Filter functions must now be camelCase to comply with PSR2

## 1.0.0 - 2016-05-09

- First release
