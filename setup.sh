#!/bin/bash

# Two-Tier Examination System - Setup Script
# This script automates the installation process

set -e

echo "========================================="
echo "Two-Tier Examination System - Setup"
echo "========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    print_success ".env file created"
else
    print_warning ".env file already exists, skipping..."
fi

# Install Composer dependencies
echo ""
echo "Installing PHP dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
    print_success "PHP dependencies installed"
else
    print_error "Composer not found! Please install Composer first."
    exit 1
fi

# Install NPM dependencies
echo ""
echo "Installing Node.js dependencies..."
if command -v npm &> /dev/null; then
    npm install
    print_success "Node.js dependencies installed"
else
    print_warning "NPM not found! Skipping Node.js dependencies..."
fi

# Generate application key
echo ""
echo "Generating application key..."
php artisan key:generate --force
print_success "Application key generated"

# Set permissions
echo ""
echo "Setting directory permissions..."
chmod -R 775 storage bootstrap/cache
print_success "Permissions set"

# Check database connection
echo ""
echo "Checking database connection..."
if php artisan db:show &> /dev/null; then
    print_success "Database connection successful"

    # Ask to run migrations
    echo ""
    read -p "Do you want to run database migrations? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan migrate
        print_success "Migrations completed"

        # Ask to run seeders
        echo ""
        read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan db:seed
            print_success "Database seeded with sample data"
            echo ""
            print_warning "Default credentials:"
            echo "  Admin - Email: admin@example.com | Password: password"
            echo "  Guru  - Email: guru@example.com  | Password: password"
        fi
    fi
else
    print_error "Database connection failed!"
    echo ""
    echo "Please configure your database settings in .env file:"
    echo "  DB_HOST=127.0.0.1"
    echo "  DB_PORT=3306 (or 8889 for MAMP)"
    echo "  DB_DATABASE=twotier"
    echo "  DB_USERNAME=root"
    echo "  DB_PASSWORD=your_password"
    echo ""
    echo "Then create the database:"
    echo "  mysql -u root -p"
    echo "  CREATE DATABASE twotier CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    echo ""
fi

# Build assets
echo ""
read -p "Do you want to build frontend assets? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if command -v npm &> /dev/null; then
        npm run build
        print_success "Assets built successfully"
    else
        print_warning "NPM not found! Skipping asset build..."
    fi
fi

# Done
echo ""
echo "========================================="
print_success "Setup completed!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Configure your database in .env file (if not done)"
echo "2. Run migrations: php artisan migrate"
echo "3. Seed database: php artisan db:seed (optional)"
echo "4. Start server: php artisan serve"
echo "5. Visit: http://localhost:8000"
echo ""
echo "For more information, see SETUP.md"
echo ""
