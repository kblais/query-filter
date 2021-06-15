# Changelog

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
