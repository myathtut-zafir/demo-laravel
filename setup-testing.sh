#!/bin/bash

# Test Setup Script for Laravel
# This script prepares the testing environment

echo "🔧 Setting up Laravel testing environment..."

# Check if .env.testing exists
if [ ! -f .env.testing ]; then
    echo "❌ .env.testing file not found!"
    exit 1
fi

# Generate application key for testing if not set
if ! grep -q "APP_KEY=base64:" .env.testing; then
    echo "🔑 Generating application key for testing..."
    php artisan key:generate --env=testing
else
    echo "✅ Application key already set in .env.testing"
fi

# Create database directory if it doesn't exist
if [ ! -d "database" ]; then
    mkdir -p database
    echo "📁 Created database directory"
fi

# Create SQLite database file for persistent testing (optional)
if [ ! -f "database/testing.sqlite" ]; then
    touch database/testing.sqlite
    echo "📊 Created database/testing.sqlite"
else
    echo "✅ database/testing.sqlite already exists"
fi

# Run migrations for testing database
echo "🔄 Running migrations for testing database..."
php artisan migrate --env=testing --force

echo ""
echo "✅ Testing environment setup complete!"
echo ""
echo "You can now run tests with:"
echo "  php artisan test"
echo "  php artisan test --filter=ObjectStoreControllerTest"
echo "  php artisan test --coverage"
echo ""
