# Travel & Tour API Documentation

## Overview

This is a Laravel-based REST API for managing travel packages and tours. The application provides endpoints to retrieve travel information and associated tours with filtering and sorting capabilities.

**Base URL:** `/api`  
**API Version:** v1  
**Content-Type:** `application/json`

## Table of Contents

1. [Authentication](#authentication)
2. [API Endpoints](#api-endpoints)
3. [Models](#models)
4. [Request/Response Examples](#requestresponse-examples)
5. [Error Handling](#error-handling)
6. [Database Schema](#database-schema)

---

## Authentication

Currently, the API endpoints are public and do not require authentication. However, the application includes Laravel Sanctum for future authentication implementation.

---

## API Endpoints

### 1. Get All Public Travels

**Endpoint:** `GET /api/travels`  
**Description:** Retrieves all public travel packages.  
**Controller:** `App\Http\Controllers\Api\V1\TravelController@index`

#### Response Format
```json
[
  {
    "id": "uuid",
    "name": "Travel Package Name",
    "slug": "travel-package-slug",
    "description": "Detailed description of the travel package",
    "number_of_days": 7,
    "number_of_nights": 6
  }
]
```

#### Example Request
```bash
curl -X GET "http://your-domain.com/api/travels" \
  -H "Accept: application/json"
```

#### Example Response
```json
[
  {
    "id": "9a0e8c8f-8b1a-4c2d-9e3f-4a5b6c7d8e9f",
    "name": "Jordan 360°",
    "slug": "jordan-360",
    "description": "Jordan 360°: the perfect tour to discover the suggestive Wadi Rum desert, the ancient beauty of Petra, and much more.",
    "number_of_days": 8,
    "number_of_nights": 7
  },
  {
    "id": "9a0e8c8f-8b1a-4c2d-9e3f-4a5b6c7d8e9g",
    "name": "Iceland: hunting for the Northern Lights",
    "slug": "iceland-northern-lights",
    "description": "Why visit Iceland in winter? Because it is between October and March that you have the best chances of seeing the Northern Lights.",
    "number_of_days": 5,
    "number_of_nights": 4
  }
]
```

---

### 2. Get Tours for a Travel Package

**Endpoint:** `GET /api/travels/{travel:slug}/tours`  
**Description:** Retrieves tours for a specific travel package with optional filtering and sorting.  
**Controller:** `App\Http\Controllers\Api\V1\TourController@index`

#### Path Parameters
- `travel:slug` (string, required): The slug of the travel package

#### Query Parameters
- `dataFrom` (date, required): Filter tours starting from this date (YYYY-MM-DD)
- `dataTo` (date, optional): Filter tours ending before this date (YYYY-MM-DD)
- `priceFrom` (numeric, optional): Filter tours with price greater than or equal to this value
- `priceTo` (numeric, optional): Filter tours with price less than or equal to this value
- `sortBy` (string, optional): Sort field. Accepted values: `price`
- `sortOrder` (string, optional): Sort order. Accepted values: `asc`, `desc`

#### Response Format
```json
[
  {
    "name": "Tour Name",
    "travel": "Travel Package Name",
    "starting_at": "2024-01-15",
    "ending_at": "2024-01-22",
    "price": "1999.00"
  }
]
```

#### Example Request
```bash
curl -X GET "http://your-domain.com/api/travels/jordan-360/tours?dataFrom=2024-01-01&priceFrom=1000&sortBy=price&sortOrder=asc" \
  -H "Accept: application/json"
```

#### Example Response
```json
[
  {
    "name": "Jordan Explorer",
    "travel": "Jordan 360°",
    "starting_at": "2024-02-01",
    "ending_at": "2024-02-08",
    "price": "1299.00"
  },
  {
    "name": "Jordan Adventure Plus",
    "travel": "Jordan 360°",
    "starting_at": "2024-03-15",
    "ending_at": "2024-03-22",
    "price": "1599.00"
  }
]
```

---

## Models

### Travel Model

**Location:** `app/Models/Travel.php`  
**Table:** `travels`

#### Attributes
- `id` (UUID): Primary key
- `name` (string): Travel package name
- `slug` (string): URL-friendly identifier
- `description` (text): Detailed description
- `is_public` (boolean): Whether the travel is publicly visible
- `number_of_days` (integer): Duration in days
- `created_at` (timestamp): Creation timestamp
- `updated_at` (timestamp): Last update timestamp

#### Computed Attributes
- `number_of_nights` (integer): Calculated as `number_of_days - 1`

#### Relationships
- `tours()`: One-to-many relationship with Tour model

#### Fillable Fields
```php
[
    'name',
    'slug', 
    'description',
    'is_public',
    'number_of_days'
]
```

#### Example Usage
```php
// Get all public travels
$travels = Travel::where('is_public', true)->get();

// Get travel with tours
$travel = Travel::with('tours')->where('slug', 'jordan-360')->first();

// Access computed attribute
$nights = $travel->number_of_nights;
```

---

### Tour Model

**Location:** `app/Models/Tour.php`  
**Table:** `tours`

#### Attributes
- `id` (UUID): Primary key
- `travel_id` (UUID): Foreign key to travels table
- `name` (string): Tour name
- `starting_at` (date): Tour start date
- `ending_at` (date): Tour end date
- `price` (integer): Price in cents (stored as integer, accessed as decimal)
- `created_at` (timestamp): Creation timestamp
- `updated_at` (timestamp): Last update timestamp

#### Relationships
- `travel()`: Belongs-to relationship with Travel model

#### Price Accessor/Mutator
The price is stored in cents in the database but accessed as a decimal value:
- **Storage:** `$1999.00` → `199900` (cents)
- **Retrieval:** `199900` → `1999.00` (dollars)

#### Fillable Fields
```php
[
    'travel_id',
    'name',
    'starting_at',
    'ending_at',
    'price'
]
```

#### Example Usage
```php
// Create a new tour
$tour = Tour::create([
    'travel_id' => $travel->id,
    'name' => 'Jordan Explorer',
    'starting_at' => '2024-02-01',
    'ending_at' => '2024-02-08',
    'price' => 1299.00 // Will be stored as 129900 cents
]);

// Get tours for a travel package
$tours = $travel->tours()->where('starting_at', '>=', '2024-01-01')->get();
```

---

### User Model

**Location:** `app/Models/User.php`  
**Table:** `users`

#### Attributes
- `id` (UUID): Primary key
- `name` (string): User's full name
- `email` (string): User's email address
- `password` (string): Hashed password
- `email_verified_at` (timestamp): Email verification timestamp
- `remember_token` (string): Remember token for authentication
- `created_at` (timestamp): Creation timestamp
- `updated_at` (timestamp): Last update timestamp

#### Relationships
- `roles()`: Many-to-many relationship with Role model

#### Hidden Fields
```php
[
    'password',
    'remember_token'
]
```

#### Fillable Fields
```php
[
    'name',
    'email',
    'password'
]
```

---

### Role Model

**Location:** `app/Models/Role.php`  
**Table:** `roles`

#### Attributes
- `id` (UUID): Primary key
- `name` (string): Role name
- `created_at` (timestamp): Creation timestamp
- `updated_at` (timestamp): Last update timestamp

#### Fillable Fields
```php
['name']
```

---

## Request/Response Examples

### Travel List Request

```bash
# Get all public travels
curl -X GET "http://your-domain.com/api/travels" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
```

**Response:**
```json
[
  {
    "id": "9a0e8c8f-8b1a-4c2d-9e3f-4a5b6c7d8e9f",
    "name": "Jordan 360°",
    "slug": "jordan-360",
    "description": "Jordan 360°: the perfect tour to discover the suggestive Wadi Rum desert, the ancient beauty of Petra, and much more.",
    "number_of_days": 8,
    "number_of_nights": 7
  }
]
```

### Tour List Request with Filters

```bash
# Get tours with price and date filters
curl -X GET "http://your-domain.com/api/travels/jordan-360/tours?dataFrom=2024-01-01&dataTo=2024-12-31&priceFrom=1000&priceTo=2000&sortBy=price&sortOrder=asc" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
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
  },
  {
    "name": "Jordan Adventure Plus",
    "travel": "Jordan 360°",
    "starting_at": "2024-03-15",
    "ending_at": "2024-03-22",
    "price": "1599.00"
  }
]
```

---

## Error Handling

### Validation Errors

When request validation fails, the API returns a 422 status code with validation errors:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "dataFrom": [
      "The dataFrom field is required."
    ],
    "sortBy": [
      "The 'sortBy' parameter accept only 'price' value"
    ],
    "sortOrder": [
      "The 'sortOrder' parameter accept only 'asc' or 'desc' value"
    ]
  }
}
```

### Not Found Errors

When a travel package is not found, the API returns a 404 status code:

```json
{
  "message": "No query results for model [App\\Models\\Travel] non-existent-slug"
}
```

### Common HTTP Status Codes

- `200 OK`: Successful request
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `500 Internal Server Error`: Server error

---

## Database Schema

### Tables Overview

1. **travels**: Stores travel packages
2. **tours**: Stores individual tours linked to travel packages
3. **users**: Stores user accounts
4. **roles**: Stores user roles
5. **role_user**: Pivot table for user-role relationships

### Travels Table Schema

```sql
CREATE TABLE travels (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_public BOOLEAN DEFAULT TRUE,
    number_of_days INTEGER NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tours Table Schema

```sql
CREATE TABLE tours (
    id UUID PRIMARY KEY,
    travel_id UUID NOT NULL,
    name VARCHAR(255) NOT NULL,
    starting_at DATE NOT NULL,
    ending_at DATE NOT NULL,
    price INTEGER NOT NULL, -- Stored in cents
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (travel_id) REFERENCES travels(id)
);
```

### Users Table Schema

```sql
CREATE TABLE users (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Roles Table Schema

```sql
CREATE TABLE roles (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Request Validation Rules

### Tour List Request Validation

**Class:** `App\Http\Requests\TourListRequest`

```php
[
    'dataFrom' => ['required', 'date'],
    'dataTo' => 'date',
    'priceFrom' => 'numeric',
    'priceTo' => 'numeric',
    'sortBy' => Rule::in(['price']),
    'sortOrder' => Rule::in(['asc', 'desc'])
]
```

### Custom Validation Messages

```php
[
    'sortBy' => "The 'sortBy' parameter accept only 'price' value",
    'sortOrder' => "The 'sortOrder' parameter accept only 'asc' or 'desc' value"
]
```

---

## API Resources

### Travel Resource

**Class:** `App\Http\Resources\TravelResources`

Transforms Travel model data for API responses:

```php
[
    'id' => $this->id,
    'name' => $this->name,
    'slug' => $this->slug,
    'description' => $this->description,
    'number_of_days' => $this->number_of_days,
    'number_of_nights' => $this->number_of_nights
]
```

### Tour Resource

**Class:** `App\Http\Resources\TourResources`

Transforms Tour model data for API responses:

```php
[
    'name' => $this->name,
    'travel' => $this->travel->name,
    'starting_at' => $this->starting_at,
    'ending_at' => $this->ending_at,
    'price' => number_format($this->price, 2)
]
```

---

## Development Setup

### Requirements

- PHP ^8.1
- Laravel ^10.10
- MySQL/PostgreSQL
- Composer

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd travel-api

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database (if seeders exist)
php artisan db:seed

# Start the development server
php artisan serve
```

### Testing

```bash
# Run PHP unit tests
php artisan test

# Run specific test
php artisan test --filter=TravelTest
```

---

## Contributing

1. Follow PSR-12 coding standards
2. Write comprehensive tests for new features
3. Update documentation for API changes
4. Use meaningful commit messages

---

## License

This project is open-sourced software licensed under the MIT license.