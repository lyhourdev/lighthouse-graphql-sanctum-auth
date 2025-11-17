# Changelog / ប្រវត្តិការផ្លាស់ប្តូរ

All notable changes to this project will be documented in this file.
ការផ្លាស់ប្តូរសំខាន់ៗទាំងអស់នៃ project នេះនឹងត្រូវបានកត់ត្រាក្នុង file នេះ។

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
ទម្រង់គឺផ្អែកលើ [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
និង project នេះគោរពតាម [Semantic Versioning](https://semver.org/spec/v2.0.0.html)។

## [Unreleased] / [មិនបានចេញ]

### Added / បានបន្ថែម
- Initial release / ការចេញផ្សាយដំបូង
- Laravel Sanctum Authentication integration / ការរួមបញ្ចូល Laravel Sanctum Authentication
- Lighthouse GraphQL integration / ការរួមបញ្ចូល Lighthouse GraphQL
- Spatie Laravel Permission support / ការគាំទ្រ Spatie Laravel Permission
- Multi-tenant support (Single DB + Multi DB) / ការគាំទ្រ Multi-tenant (Single DB + Multi DB)
- Device Management / ការគ្រប់គ្រង Device
- Audit Logging System / ប្រព័ន្ធ Audit Logging
- Enterprise Directives:
  - `@hasRole` - Role-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ role
  - `@hasPermission` - Permission-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ permission
  - `@ownership` - Resource ownership verification / ការផ្ទៀងផ្ទាត់ ownership resource
  - `@belongsToTenant` - Multi-tenant isolation / ការញែក multi-tenant
  - `@audit` - Audit logging / Audit logging
- Helper classes for common operations / Helper classes សម្រាប់ operations ទូទៅ
- Traits for models (HasApiTokens, HasTenant, HasOwnership, HasAuditLog, HasDevices) / Traits សម្រាប់ models
- GraphQL Mutations and Queries for authentication and permissions / GraphQL Mutations និង Queries សម្រាប់ authentication និង permissions
- Comprehensive documentation (bilingual EN + KM) / Documentation ពេញលេញ (ទ្វេភាសា EN + KM)
- Frontend integration examples (Vue, React, Next.js, Angular, etc.) / ឧទាហរណ៍ការរួមបញ្ចូល Frontend
- Database seeders for permissions and roles / Database seeders សម្រាប់ permissions និង roles

### Planned / គ្រោង
- 2FA (Google Authenticator) implementation / ការអនុវត្ត 2FA (Google Authenticator)
- IP Filtering implementation / ការអនុវត្ត IP Filtering
- Refresh Token System enhancements / ការកែលម្អ Refresh Token System

## [1.0.0] - 2025-01-XX

### Added / បានបន្ថែម
- Initial release / ការចេញផ្សាយដំបូង

