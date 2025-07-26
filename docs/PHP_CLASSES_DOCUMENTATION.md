# PHP Classes and Functions Documentation

## Overview

This document provides comprehensive documentation for all PHP classes, methods, and functions in the Travel & Tour API application.

## Table of Contents

1. [Controllers](#controllers)
2. [Models](#models)
3. [Requests](#requests)
4. [Resources](#resources)
5. [Middleware](#middleware)
6. [Service Providers](#service-providers)

---

## Controllers

### TravelController

**Namespace:** `App\Http\Controllers\Api\V1`  
**Location:** `app/Http/Controllers/Api/V1/TravelController.php`

#### Class Description
Handles HTTP requests related to travel packages, providing endpoints to retrieve public travel information.

#### Methods

##### `index()`

**Purpose:** Retrieves all public travel packages  
**HTTP Method:** GET  
**Route:** `/api/travels`  
**Return Type:** `JsonResponse`

```php
public function index(): JsonResponse
```

**Description:**
- Fetches all travel packages where `is_public` is `true`
- Returns data formatted using `TravelResources`
- No authentication required

**Example Usage:**
```php
// Controller method implementation
public function index()
{
    $travels = Travel::where('is_public', true)->get();
    return response()->json(TravelResources::collection($travels));
}
```

**Response Format:**
```json
[
  {
    "id": "uuid",
    "name": "Travel Name",
    "slug": "travel-slug",
    "description": "Travel Description",
    "number_of_days": 7,
    "number_of_nights": 6
  }
]
```

---

### TourController

**Namespace:** `App\Http\Controllers\Api\V1`  
**Location:** `app/Http/Controllers/Api/V1/TourController.php`

#### Class Description
Handles HTTP requests related to tours, providing endpoints to retrieve tours for specific travel packages with filtering and sorting capabilities.

#### Methods

##### `index(Travel $travel, TourListRequest $request)`

**Purpose:** Retrieves tours for a specific travel package with optional filters  
**HTTP Method:** GET  
**Route:** `/api/travels/{travel:slug}/tours`  
**Return Type:** `JsonResponse`

```php
public function index(Travel $travel, TourListRequest $request): JsonResponse
```

**Parameters:**
- `$travel` (Travel): Travel model instance resolved by route model binding
- `$request` (TourListRequest): Validated request containing filter parameters

**Description:**
- Uses Laravel's route model binding to resolve Travel by slug
- Applies conditional filters based on request parameters
- Supports date range filtering (`dataFrom`, `dataTo`)
- Supports price range filtering (`priceFrom`, `priceTo`)
- Supports sorting by price (`sortBy`, `sortOrder`)
- Returns data formatted using `TourResources`

**Filter Logic:**
```php
$tours = $travel->tours()
    ->when($request->dataFrom, function ($query) use ($request) {
        $query->where('starting_data', '>=', $request->dataFrom);
    })
    ->when($request->dataTo, function ($query) use ($request) {
        $query->where('ending_data', '<=', $request->dataTo);
    })
    ->when($request->priceFrom, function($query) use ($request) {
        $query->where('price', '>=', $request->priceFrom);
    })
    ->when($request->priceTo, function ($query) use ($request) {
        $query->where('price', '<=', $request->priceTo);
    })
    ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
        $query->orderBy($request->sortBy, $request->sortOrder);
    })
    ->get();
```

**Example Usage:**
```bash
GET /api/travels/jordan-360/tours?dataFrom=2024-01-01&priceFrom=1000&sortBy=price&sortOrder=asc
```

---

## Models

### Travel Model

**Namespace:** `App\Models`  
**Location:** `app/Models/Travel.php`  
**Extends:** `Illuminate\Database\Eloquent\Model`

#### Class Description
Eloquent model representing travel packages with relationships to tours.

#### Traits Used
- `HasFactory`: Enables model factories for testing/seeding
- `HasUuids`: Uses UUIDs as primary keys instead of auto-incrementing integers

#### Properties

##### `$table`
```php
protected $table = 'travels';
```
Specifies the database table name.

##### `$fillable`
```php
public $fillable = [
    'name',
    'slug',
    'description',
    'is_public',
    'number_of_days'
];
```
Defines mass-assignable attributes for security.

#### Methods

##### `tours(): HasMany`

**Purpose:** Defines one-to-many relationship with Tour model  
**Return Type:** `HasMany`

```php
public function tours(): HasMany
{
    return $this->hasMany(Tour::class);
}
```

**Usage Examples:**
```php
// Get travel with all tours
$travel = Travel::with('tours')->find($id);

// Get tours for a specific travel
$tours = $travel->tours;

// Query tours through relationship
$expensiveTours = $travel->tours()->where('price', '>', 100000)->get();
```

##### `numberOfNight(): Attribute`

**Purpose:** Computed attribute that calculates number of nights  
**Return Type:** `Attribute`

```php
public function numberOfNight(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attribute) => $attribute['number_of_days'] - 1
    );
}
```

**Description:**
- Automatically calculates nights as days minus one
- Accessed as `$travel->number_of_nights`
- Read-only computed attribute

**Usage Example:**
```php
$travel = Travel::find($id);
echo $travel->number_of_nights; // If number_of_days = 7, this returns 6
```

---

### Tour Model

**Namespace:** `App\Models`  
**Location:** `app/Models/Tour.php`  
**Extends:** `Illuminate\Database\Eloquent\Model`

#### Class Description
Eloquent model representing individual tours belonging to travel packages.

#### Traits Used
- `HasFactory`: Enables model factories
- `HasUuids`: Uses UUIDs as primary keys

#### Properties

##### `$fillable`
```php
public $fillable = [
    'travel_id',
    'name',
    'starting_at',
    'ending_at',
    'price'
];
```

#### Methods

##### `travel(): BelongsTo`

**Purpose:** Defines inverse relationship with Travel model  
**Return Type:** `BelongsTo`

```php
public function travel(): BelongsTo
{
    return $this->belongsTo(Travel::class);
}
```

**Usage Examples:**
```php
// Get tour with travel information
$tour = Tour::with('travel')->find($id);

// Access travel through relationship
$travelName = $tour->travel->name;
```

##### `price(): Attribute`

**Purpose:** Accessor/Mutator for price handling  
**Return Type:** `Attribute`

```php
public function price(): Attribute
{
    return Attribute::make(
        get: fn ($value) => $value / 100,
        set: fn ($value) => $value * 100
    );
}
```

**Description:**
- **Accessor:** Converts stored cents to dollars for display
- **Mutator:** Converts input dollars to cents for storage
- Ensures consistent decimal handling

**Usage Examples:**
```php
// Creating a tour - price is automatically converted to cents
$tour = Tour::create([
    'name' => 'Jordan Explorer',
    'price' => 1299.99 // Stored as 129999 cents
]);

// Accessing price - automatically converted to dollars
echo $tour->price; // Returns 1299.99
```

---

### User Model

**Namespace:** `App\Models`  
**Location:** `app/Models/User.php`  
**Extends:** `Illuminate\Foundation\Auth\User`

#### Class Description
Eloquent model for user authentication and authorization.

#### Traits Used
- `HasApiTokens`: Laravel Sanctum API token functionality
- `HasFactory`: Model factory support
- `Notifiable`: Laravel notification system
- `HasUuids`: UUID primary keys

#### Properties

##### `$fillable`
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

##### `$hidden`
```php
protected $hidden = [
    'password',
    'remember_token',
];
```

##### `$casts`
```php
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];
```

#### Methods

##### `roles(): BelongsToMany`

**Purpose:** Many-to-many relationship with Role model  
**Return Type:** `BelongsToMany`

```php
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class);
}
```

**Usage Examples:**
```php
// Get user with roles
$user = User::with('roles')->find($id);

// Attach role to user
$user->roles()->attach($roleId);

// Check if user has specific role
$hasAdminRole = $user->roles()->where('name', 'admin')->exists();
```

---

### Role Model

**Namespace:** `App\Models`  
**Location:** `app/Models/Role.php`  
**Extends:** `Illuminate\Database\Eloquent\Model`

#### Class Description
Simple model for user role management.

#### Traits Used
- `HasFactory`: Model factory support
- `HasUuids`: UUID primary keys

#### Properties

##### `$fillable`
```php
public $fillable = ['name'];
```

**Usage Examples:**
```php
// Create a role
$role = Role::create(['name' => 'admin']);

// Find role by name
$adminRole = Role::where('name', 'admin')->first();
```

---

## Requests

### TourListRequest

**Namespace:** `App\Http\Requests`  
**Location:** `app/Http/Requests/TourListRequest.php`  
**Extends:** `Illuminate\Foundation\Http\FormRequest`

#### Class Description
Form request class that handles validation for tour listing endpoint.

#### Methods

##### `authorize(): bool`

**Purpose:** Determines if the user is authorized to make this request  
**Return Type:** `bool`

```php
public function authorize(): bool
{
    return true;
}
```

**Description:**
- Currently returns `true` for all requests (public endpoint)
- Can be modified to implement authorization logic

##### `rules(): array`

**Purpose:** Defines validation rules for request parameters  
**Return Type:** `array`

```php
public function rules(): array
{
    return [
        'dataFrom' => ['required', 'date'],
        'dataTo' => 'date',
        'priceFrom' => 'numeric',
        'priceTo' => 'numeric',
        'sortBy' => Rule::in(['price']),
        'sortOrder' => Rule::in(['asc', 'desc'])
    ];
}
```

**Validation Rules Explained:**
- `dataFrom`: Required date field for filtering tour start dates
- `dataTo`: Optional date field for filtering tour end dates
- `priceFrom`: Optional numeric field for minimum price filter
- `priceTo`: Optional numeric field for maximum price filter
- `sortBy`: Optional field, only accepts 'price' value
- `sortOrder`: Optional field, accepts 'asc' or 'desc'

##### `messages(): array`

**Purpose:** Custom validation error messages  
**Return Type:** `array`

```php
public function messages(): array
{
    return [
        'sortBy' => "The 'sortBy' parameter accept only 'price' value",
        'sortOrder' => "The 'sortOrder' parameter accept only 'asc' or 'desc' value"
    ];
}
```

---

## Resources

### TravelResources

**Namespace:** `App\Http\Resources`  
**Location:** `app/Http/Resources/TravelResources.php`  
**Extends:** `Illuminate\Http\Resources\Json\JsonResource`

#### Class Description
API resource for transforming Travel model data into JSON responses.

#### Methods

##### `toArray(Request $request): array`

**Purpose:** Transform the resource into an array  
**Return Type:** `array`

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'description' => $this->description,
        'number_of_days' => $this->number_of_days,
        'number_of_nights' => $this->number_of_nights
    ];
}
```

**Description:**
- Transforms Travel model into standardized API response format
- Includes computed `number_of_nights` attribute
- Excludes sensitive fields like timestamps unless needed

**Usage Example:**
```php
// Single resource
return new TravelResources($travel);

// Resource collection
return TravelResources::collection($travels);
```

---

### TourResources

**Namespace:** `App\Http\Resources`  
**Location:** `app/Http/Resources/TourResources.php`  
**Extends:** `Illuminate\Http\Resources\Json\JsonResource`

#### Class Description
API resource for transforming Tour model data into JSON responses.

#### Methods

##### `toArray(Request $request): array`

**Purpose:** Transform the resource into an array  
**Return Type:** `array`

```php
public function toArray(Request $request): array
{
    return [
        'name' => $this->name,
        'travel' => $this->travel->name,
        'starting_at' => $this->starting_at,
        'ending_at' => $this->ending_at,
        'price' => number_format($this->price, 2)
    ];
}
```

**Description:**
- Transforms Tour model with related Travel name
- Formats price with 2 decimal places using `number_format()`
- Includes date fields in standard format

**Usage Example:**
```php
// Single resource
return new TourResources($tour);

// Resource collection
return TourResources::collection($tours);
```

---

## Middleware

### Standard Laravel Middleware

The application uses standard Laravel middleware located in `app/Http/Middleware/`:

#### Authenticate
**Location:** `app/Http/Middleware/Authenticate.php`  
**Purpose:** Handles user authentication redirects

#### EncryptCookies
**Location:** `app/Http/Middleware/EncryptCookies.php`  
**Purpose:** Encrypts and decrypts cookies

#### PreventRequestsDuringMaintenance
**Location:** `app/Http/Middleware/PreventRequestsDuringMaintenance.php`  
**Purpose:** Blocks requests during maintenance mode

#### RedirectIfAuthenticated
**Location:** `app/Http/Middleware/RedirectIfAuthenticated.php`  
**Purpose:** Redirects authenticated users away from guest-only pages

#### TrimStrings
**Location:** `app/Http/Middleware/TrimStrings.php`  
**Purpose:** Trims whitespace from request strings

#### TrustHosts
**Location:** `app/Http/Middleware/TrustHosts.php`  
**Purpose:** Configures trusted hosts

#### TrustProxies
**Location:** `app/Http/Middleware/TrustProxies.php`  
**Purpose:** Configures trusted proxies

#### ValidateSignature
**Location:** `app/Http/Middleware/ValidateSignature.php`  
**Purpose:** Validates signed URLs

#### VerifyCsrfToken
**Location:** `app/Http/Middleware/VerifyCsrfToken.php`  
**Purpose:** CSRF token verification

---

## Helper Functions and Utilities

### Global Helper Functions

Laravel provides several global helper functions that are commonly used throughout the application:

#### `response()`
```php
// Create JSON response
return response()->json($data);

// Create response with status code
return response()->json($data, 201);
```

#### `route()`
```php
// Generate URL for named route
$url = route('api.travels.index');

// Generate URL with parameters
$url = route('api.tours.index', ['travel' => $travel->slug]);
```

#### `config()`
```php
// Get configuration value
$appName = config('app.name');

// Get with default value
$cacheDriver = config('cache.default', 'file');
```

---

## Best Practices and Conventions

### Model Conventions
1. Use singular names for models (`Travel`, not `Travels`)
2. Use `$fillable` for mass assignment protection
3. Define relationships with proper return types
4. Use accessors/mutators for data transformation

### Controller Conventions
1. Keep controllers thin - delegate business logic to services
2. Use form requests for validation
3. Use resource classes for API responses
4. Follow RESTful naming conventions

### Request Validation
1. Use form request classes for complex validation
2. Provide custom error messages for better UX
3. Validate early and fail fast

### Resource Transformation
1. Use API resources for consistent response formatting
2. Hide sensitive data from API responses
3. Transform data types appropriately (dates, numbers, etc.)

---

## Testing Examples

### Model Testing
```php
// Test model relationships
public function test_travel_has_many_tours()
{
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create(['travel_id' => $travel->id]);
    
    $this->assertTrue($travel->tours->contains($tour));
}

// Test computed attributes
public function test_number_of_nights_calculation()
{
    $travel = Travel::factory()->create(['number_of_days' => 7]);
    
    $this->assertEquals(6, $travel->number_of_nights);
}
```

### Controller Testing
```php
// Test API endpoints
public function test_can_get_public_travels()
{
    Travel::factory()->create(['is_public' => true]);
    Travel::factory()->create(['is_public' => false]);
    
    $response = $this->getJson('/api/travels');
    
    $response->assertStatus(200)
             ->assertJsonCount(1);
}
```

---

## Performance Considerations

### Database Queries
1. Use eager loading to prevent N+1 queries
2. Add database indexes for frequently queried fields
3. Use query scopes for reusable query logic

### Caching
1. Cache frequently accessed data
2. Use Redis for session and cache storage in production
3. Implement API response caching for static data

### API Optimization
1. Use pagination for large datasets
2. Implement rate limiting
3. Use appropriate HTTP status codes
4. Minimize response payload size

---

This documentation covers all the major PHP classes and functions in the Travel & Tour API application. Each class is documented with its purpose, methods, relationships, and usage examples to help developers understand and maintain the codebase effectively.