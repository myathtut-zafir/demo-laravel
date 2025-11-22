# Testing Guide

This document provides information about running tests in this Laravel application.

## Test Environment Setup

### Configuration Files

- **`.env.testing`** - Environment configuration specifically for testing
- **`phpunit.xml`** - PHPUnit configuration file
- **`setup-testing.sh`** - Automated setup script for testing environment

### Database Configuration

The testing environment uses **SQLite** with two options:

1. **In-Memory Database** (Default - Fastest)
   - Configured in `phpunit.xml` as `:memory:`
   - Database is created and destroyed for each test run
   - No persistence between test runs
   - Fastest option for CI/CD

2. **File-Based SQLite** (Optional - For debugging)
   - Located at `database/testing.sqlite`
   - Persists between test runs
   - Useful for debugging test data
   - To use, update `.env.testing`: `DB_DATABASE=database/testing.sqlite`

### Key Testing Configurations

The `.env.testing` file includes optimized settings for testing:

- **`APP_ENV=testing`** - Sets environment to testing
- **`DB_CONNECTION=sqlite`** - Uses SQLite database
- **`DB_DATABASE=:memory:`** - In-memory database for speed
- **`BCRYPT_ROUNDS=4`** - Reduced bcrypt rounds for faster tests
- **`CACHE_STORE=array`** - Array cache driver (no persistence)
- **`SESSION_DRIVER=array`** - Array session driver
- **`QUEUE_CONNECTION=sync`** - Synchronous queue processing
- **`MAIL_MAILER=array`** - Array mail driver (no actual emails sent)

## Running Tests

### Basic Commands

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ObjectStoreControllerTest.php

# Run specific test method
php artisan test --filter=test_store_creates_object_store_successfully

# Run tests with coverage
php artisan test --coverage

# Run tests with minimum coverage threshold
php artisan test --coverage --min=80

# Run only Feature tests
php artisan test --testsuite=Feature

# Run only Unit tests
php artisan test --testsuite=Unit
```

### Using PHPUnit Directly

```bash
# Run with PHPUnit
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit tests/Feature/ObjectStoreControllerTest.php

# Run with coverage report
./vendor/bin/phpunit --coverage-html coverage
```

## Test Structure

### Feature Tests

Located in `tests/Feature/`

- Test complete features and workflows
- Test HTTP requests and responses
- Test database interactions
- Use `RefreshDatabase` trait to reset database

Example:
```php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectStoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->postJson('/api/object-store', [
            'key' => 'test',
            'value' => ['data' => 'value']
        ]);

        $response->assertStatus(201);
    }
}
```

### Unit Tests

Located in `tests/Unit/`

- Test individual methods and classes
- Test business logic in isolation
- No database or HTTP interactions

## Current Test Coverage

### ObjectStoreController Tests

**File:** `tests/Feature/ObjectStoreControllerTest.php`

**Test Cases:**
1. ✅ `test_store_creates_object_store_successfully` - Basic creation
2. ✅ `test_store_saves_timestamp_correctly` - Timestamp validation
3. ✅ `test_store_handles_simple_values` - Simple string values
4. ✅ `test_store_handles_complex_nested_arrays` - Complex nested data
5. ✅ `test_store_handles_empty_values` - Empty arrays
6. ✅ `test_store_allows_multiple_records` - Multiple record creation
7. ✅ `test_store_handles_boolean_values` - Boolean data types
8. ✅ `test_store_handles_numeric_values` - Numeric data types

**Total:** 8 tests, 28 assertions

## CI/CD Integration

### GitHub Actions Workflows

1. **PHPUnit Tests** (`.github/workflows/phpunit.yml`)
   - Runs on push/PR to main, master, develop
   - Sets up PHP 8.2 with required extensions
   - Runs migrations and tests
   - Generates code coverage reports

2. **Laravel Pint** (`.github/workflows/pint.yml`)
   - Checks code style on push/PR
   - Ensures code follows Laravel standards

## Best Practices

### Writing Tests

1. **Use Descriptive Names**
   ```php
   public function test_store_creates_object_store_successfully(): void
   ```

2. **Arrange-Act-Assert Pattern**
   ```php
   // Arrange
   $payload = ['key' => 'test', 'value' => 'data'];
   
   // Act
   $response = $this->postJson('/api/object-store', $payload);
   
   // Assert
   $response->assertStatus(201);
   ```

3. **Use Database Assertions**
   ```php
   $this->assertDatabaseHas('object_stores', ['key' => 'test']);
   $this->assertDatabaseCount('object_stores', 1);
   ```

4. **Test Edge Cases**
   - Empty values
   - Null values (if allowed)
   - Large datasets
   - Invalid inputs

### Database Testing

1. **Always use `RefreshDatabase` trait**
   ```php
   use Illuminate\Foundation\Testing\RefreshDatabase;
   
   class MyTest extends TestCase
   {
       use RefreshDatabase;
   }
   ```

2. **Use factories for test data**
   ```php
   $user = User::factory()->create();
   ```

3. **Clean up after tests**
   - `RefreshDatabase` handles this automatically

## Troubleshooting

### Common Issues

**Issue:** Tests fail with database errors
- **Solution:** Ensure migrations are run: `php artisan migrate --env=testing`

**Issue:** APP_KEY not set error
- **Solution:** Run `./setup-testing.sh` or manually set APP_KEY in `.env.testing`

**Issue:** Tests are slow
- **Solution:** Ensure using in-memory SQLite (`:memory:`)
- **Solution:** Reduce `BCRYPT_ROUNDS` in `.env.testing`

**Issue:** Coverage reports not generating
- **Solution:** Install Xdebug: `pecl install xdebug`

## Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Database Testing](https://laravel.com/docs/database-testing)
- [HTTP Tests](https://laravel.com/docs/http-tests)
