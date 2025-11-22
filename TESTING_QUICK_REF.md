# Quick Testing Reference

## 🚀 Quick Start

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📋 Common Commands

### Run Specific Tests
```bash
# Run specific test file
php artisan test tests/Feature/ObjectStoreControllerTest.php

# Run specific test method
php artisan test --filter=test_store_creates_object_store_successfully

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Coverage Reports
```bash
# Show coverage summary
php artisan test --coverage

# Require minimum coverage
php artisan test --coverage --min=80

# Generate HTML coverage report
./vendor/bin/phpunit --coverage-html coverage
```

### Debugging
```bash
# Stop on first failure
php artisan test --stop-on-failure

# Show detailed output
php artisan test --verbose

# Run in parallel (faster)
php artisan test --parallel
```

## 🔧 Environment Files

- **`.env`** - Development environment
- **`.env.testing`** - Testing environment (auto-loaded by PHPUnit)
- **`phpunit.xml`** - PHPUnit configuration

## 🗄️ Database

**Default:** In-memory SQLite (`:memory:`)
- Fastest option
- No persistence
- Configured in `phpunit.xml`

**Alternative:** File-based SQLite
- Update `.env.testing`: `DB_DATABASE=database/testing.sqlite`
- Useful for debugging

## 📊 Current Test Stats

- **Total Tests:** 8
- **Total Assertions:** 28
- **Test Files:** 1
- **Coverage Target:** 80%

## 🔍 Test Locations

```
tests/
├── Feature/
│   └── ObjectStoreControllerTest.php  (8 tests)
└── Unit/
    └── ExampleTest.php
```

## 💡 Tips

1. Always use `RefreshDatabase` trait in feature tests
2. Use descriptive test method names
3. Follow Arrange-Act-Assert pattern
4. Test edge cases and error scenarios
5. Keep tests fast and isolated

## 📚 More Info

See `TESTING.md` for comprehensive testing guide.
