# Changelog

## 2.0.2 — 2026-10-05

### Added

- Complete Module Lifecycle reference integration with Core-owned update and
  uninstall operations.
- Database-state detection for missing, current, update-required, and invalid
  schema states.
- Explicit Install SQL and Update SQL lifecycle actions.
- Module-owned `example_schema` and `example_records` table declarations.
- Data Lifecycle actions for Delete Data and canonical Data Reset.
- Complete Create, Read, Update, and Delete record operations.
- Packaged schema, reference data, and the 1.9.0-to-2.0.0 migration.
- CSRF-protected, POST-only mutation actions with an explicit action allowlist.
- Lifecycle and CRUD documentation for module developers.
- Focused Admin tabs for Records, Database, Data, and Module lifecycle concerns.
