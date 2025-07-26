# Travel & Tour API - Complete Documentation

## 📋 Overview

This repository contains a comprehensive Laravel-based REST API for managing travel packages and tours. The application provides public endpoints to retrieve travel information and associated tours with advanced filtering and sorting capabilities.

**Key Features:**
- 🌍 Travel package management
- 🎯 Tour filtering and sorting
- 🔍 Advanced search capabilities
- 📱 Mobile-responsive frontend
- 🎨 Modern UI with TailwindCSS
- 🚀 Built with Laravel 10 & Vite

## 📚 Documentation Index

This documentation is organized into several comprehensive guides:

### 🔗 Quick Navigation

| Document | Description | Target Audience |
|----------|-------------|-----------------|
| **[API Documentation](API_DOCUMENTATION.md)** | Complete REST API reference with endpoints, examples, and schemas | Frontend developers, API consumers |
| **[PHP Classes Documentation](PHP_CLASSES_DOCUMENTATION.md)** | Detailed documentation of all PHP classes, methods, and functions | Backend developers, maintainers |
| **[Frontend Documentation](FRONTEND_DOCUMENTATION.md)** | Frontend components, JavaScript functions, and styling guide | Frontend developers, UI/UX developers |

---

## 🚀 Quick Start

### Prerequisites

- **PHP** ^8.1
- **Composer** (latest)
- **Node.js** ^16.x
- **NPM** or **Yarn**
- **MySQL** or **PostgreSQL**

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd travel-tour-api

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database setup
php artisan migrate
php artisan db:seed  # Optional: seed with sample data

# 6. Start development servers
php artisan serve    # Backend server (http://localhost:8000)
npm run dev         # Frontend assets with hot reload
```

### First API Call

```bash
# Test the API
curl -X GET "http://localhost:8000/api/travels" \
  -H "Accept: application/json"
```

---

## 🏗️ Architecture Overview

### Backend Architecture

```
app/
├── Http/
│   ├── Controllers/Api/V1/     # API Controllers
│   ├── Requests/               # Form Request Validation
│   ├── Resources/              # API Response Formatting
│   └── Middleware/             # Custom Middleware
├── Models/                     # Eloquent Models
└── Providers/                  # Service Providers

routes/
├── api.php                     # API Routes
└── web.php                     # Web Routes
```

### Frontend Architecture

```
resources/
├── js/
│   ├── app.js                  # Main JavaScript entry
│   └── bootstrap.js            # Axios & Echo configuration
├── css/
│   └── app.css                 # Custom styles
└── views/
    └── welcome.blade.php       # Main template
```

---

## 🔌 API Endpoints

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/travels` | Get all public travel packages |
| `GET` | `/api/travels/{slug}/tours` | Get tours for a specific travel |

### Example Requests

#### Get All Travels
```bash
GET /api/travels
```

**Response:**
```json
[
  {
    "id": "uuid",
    "name": "Jordan 360°",
    "slug": "jordan-360",
    "description": "Discover the beauty of Jordan...",
    "number_of_days": 8,
    "number_of_nights": 7
  }
]
```

#### Get Tours with Filters
```bash
GET /api/travels/jordan-360/tours?dataFrom=2024-01-01&priceFrom=1000&sortBy=price&sortOrder=asc
```

**Response:**
```json
[
  {
    "name": "Jordan Explorer",
    "travel": "Jordan 360°",
    "starting_at": "2024-02-01",
    "ending_at": "2024-02-08",
    "price": "1299.00"
  }
]
```

---

## 🗄️ Database Schema

### Core Tables

#### `travels`
- **Purpose:** Store travel packages
- **Key Fields:** `id`, `name`, `slug`, `description`, `is_public`, `number_of_days`
- **Relationships:** One-to-many with `tours`

#### `tours`
- **Purpose:** Store individual tours
- **Key Fields:** `id`, `travel_id`, `name`, `starting_at`, `ending_at`, `price`
- **Relationships:** Belongs-to `travels`

#### `users` & `roles`
- **Purpose:** User authentication and authorization
- **Relationships:** Many-to-many between users and roles

---

## 🎨 Frontend Features

### Modern UI Components

- **Responsive Design:** Mobile-first approach with TailwindCSS
- **Dark Mode Support:** Automatic system preference detection
- **Interactive Elements:** Hover states and smooth transitions
- **Accessibility:** ARIA labels and keyboard navigation

### JavaScript Functionality

- **Axios Integration:** Pre-configured HTTP client with CSRF protection
- **Real-time Ready:** Laravel Echo configuration for WebSocket support
- **Modern ES6+:** Arrow functions, async/await, destructuring
- **Error Handling:** Comprehensive error management

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=TravelTest

# Run with coverage
php artisan test --coverage
```

### Test Structure

```
tests/
├── Feature/                    # Integration tests
│   ├── TravelApiTest.php
│   └── TourApiTest.php
└── Unit/                       # Unit tests
    ├── TravelModelTest.php
    └── TourModelTest.php
```

---

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travel_api
DB_USERNAME=root
DB_PASSWORD=

# Application
APP_NAME="Travel API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Frontend (Optional)
VITE_PUSHER_APP_KEY=
VITE_PUSHER_APP_CLUSTER=mt1
```

### Cache Configuration

```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📈 Performance Optimization

### Backend Optimizations

1. **Database Indexing**
   ```sql
   -- Add indexes for frequently queried fields
   CREATE INDEX idx_travels_is_public ON travels(is_public);
   CREATE INDEX idx_tours_travel_id ON tours(travel_id);
   CREATE INDEX idx_tours_starting_at ON tours(starting_at);
   ```

2. **Query Optimization**
   ```php
   // Use eager loading to prevent N+1 queries
   $travels = Travel::with('tours')->where('is_public', true)->get();
   ```

3. **Caching Strategy**
   ```php
   // Cache frequently accessed data
   $travels = Cache::remember('public_travels', 3600, function () {
       return Travel::where('is_public', true)->get();
   });
   ```

### Frontend Optimizations

1. **Asset Optimization**
   - Vite automatically handles code splitting and tree shaking
   - Images are optimized and lazy-loaded
   - CSS is purged of unused classes

2. **API Request Optimization**
   ```javascript
   // Debounce search requests
   const debouncedSearch = debounce(searchFunction, 300);
   
   // Cache API responses
   const cache = new Map();
   ```

---

## 🔒 Security Features

### API Security

- **CSRF Protection:** Automatic token validation
- **Rate Limiting:** Prevent abuse of API endpoints
- **Input Validation:** Comprehensive request validation
- **SQL Injection Prevention:** Eloquent ORM with parameter binding

### Authentication Ready

```php
// Laravel Sanctum is configured for API authentication
// Uncomment in routes/api.php to enable:
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('travels', [TravelController::class, 'store']);
// });
```

---

## 🚀 Deployment

### Production Checklist

1. **Environment Setup**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false
   
   # Configure database
   DB_CONNECTION=mysql
   DB_HOST=your-production-host
   ```

2. **Optimization Commands**
   ```bash
   # Install production dependencies
   composer install --optimize-autoloader --no-dev
   
   # Cache configuration
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Build frontend assets
   npm run build
   ```

3. **Server Configuration**
   - Set document root to `public/` directory
   - Configure URL rewriting for Laravel
   - Set appropriate file permissions
   - Configure SSL certificate

### Docker Deployment

```dockerfile
# Example Dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files
COPY . /var/www/html
WORKDIR /var/www/html

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html
```

---

## 🤝 Contributing

### Development Workflow

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes**
4. **Write tests** for new functionality
5. **Run the test suite**
   ```bash
   php artisan test
   npm run test  # If frontend tests exist
   ```
6. **Submit a pull request**

### Code Standards

- **PHP:** Follow PSR-12 coding standards
- **JavaScript:** Use ESLint configuration
- **Commits:** Use conventional commit messages
- **Documentation:** Update relevant documentation

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added for new functionality

## Documentation
- [ ] Documentation updated
- [ ] API documentation updated if needed
```

---

## 📞 Support & Resources

### Getting Help

- **Documentation:** Check the comprehensive docs in `/docs/`
- **Issues:** Report bugs or request features via GitHub Issues
- **Discussions:** Join community discussions for questions

### Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/guide/)
- [Axios Documentation](https://axios-http.com/docs/intro)

### Community

- **Laravel Community:** [Laravel.io](https://laravel.io)
- **Stack Overflow:** Tag questions with `laravel`, `php`, `api`
- **Discord:** Join Laravel Discord server

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🏷️ Version History

### v1.0.0 (Current)
- ✅ Basic travel and tour API endpoints
- ✅ Advanced filtering and sorting
- ✅ Comprehensive documentation
- ✅ Modern frontend with TailwindCSS
- ✅ Production-ready configuration

### Planned Features
- 🔄 User authentication and authorization
- 🔄 Admin panel for travel management
- 🔄 Real-time notifications
- 🔄 Advanced search with Elasticsearch
- 🔄 Image upload and management
- 🔄 Multi-language support

---

## 📊 Project Statistics

- **PHP Files:** 15+
- **JavaScript Files:** 3
- **API Endpoints:** 2 public endpoints
- **Database Tables:** 5 core tables
- **Test Coverage:** Comprehensive test suite
- **Documentation Pages:** 4 detailed guides

---

**Happy Coding! 🚀**

*For detailed technical documentation, please refer to the specific documentation files linked above.*