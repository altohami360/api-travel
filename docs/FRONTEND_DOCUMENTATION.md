# Frontend Components and JavaScript Documentation

## Overview

This document provides comprehensive documentation for the frontend components, JavaScript functionality, and styling in the Travel & Tour API application. The frontend is built using Laravel Blade templates with Vite for asset compilation and uses TailwindCSS for styling.

## Table of Contents

1. [Build System](#build-system)
2. [JavaScript Components](#javascript-components)
3. [Blade Templates](#blade-templates)
4. [Styling](#styling)
5. [Asset Management](#asset-management)
6. [Frontend API Integration](#frontend-api-integration)

---

## Build System

### Vite Configuration

**Location:** `vite.config.js`

The application uses Vite as the build tool for modern frontend development:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

**Configuration Details:**
- **Laravel Plugin:** Integrates Vite with Laravel for seamless development
- **Input Files:** Processes `app.css` and `app.js` as entry points
- **Hot Reload:** Automatically refreshes browser on file changes during development

**Build Commands:**
```bash
# Development server with hot reload
npm run dev

# Production build
npm run build

# Preview production build
npm run preview
```

---

## JavaScript Components

### Main Application Entry Point

**Location:** `resources/js/app.js`

```javascript
import './bootstrap';
```

**Description:**
- Simple entry point that imports the bootstrap configuration
- Additional JavaScript modules and components can be imported here
- Processed by Vite during build

### Bootstrap Configuration

**Location:** `resources/js/bootstrap.js`

#### Axios HTTP Client Setup

```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

**Features:**
- **Global Axios Instance:** Makes axios available globally as `window.axios`
- **CSRF Protection:** Automatically includes `X-Requested-With` header
- **Cookie-based CSRF:** Handles CSRF tokens from cookies automatically

**Usage Examples:**
```javascript
// GET request to API
window.axios.get('/api/travels')
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error('Error:', error);
    });

// POST request with data
window.axios.post('/api/endpoint', {
    name: 'Travel Package',
    description: 'Amazing travel experience'
})
.then(response => {
    console.log('Success:', response.data);
})
.catch(error => {
    console.error('Error:', error.response.data);
});
```

#### Laravel Echo Configuration (Commented)

```javascript
// Real-time broadcasting setup (currently disabled)
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     // ... additional configuration
// });
```

**Purpose:**
- Prepared for real-time features using Laravel Echo and Pusher
- Can be enabled by uncommenting and configuring environment variables
- Useful for real-time notifications, chat, or live updates

---

## Blade Templates

### Welcome Template

**Location:** `resources/views/welcome.blade.php`

#### Template Structure

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Inline TailwindCSS Styles -->
    <style>
        /* Compiled TailwindCSS classes */
    </style>
</head>
<body class="antialiased">
    <!-- Template content -->
</body>
</html>
```

#### Key Features

##### Responsive Navigation
```blade
@if (Route::has('login'))
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
        @auth
            <a href="{{ url('/home') }}">Home</a>
        @else
            <a href="{{ route('login') }}">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
            @endif
        @endauth
    </div>
@endif
```

**Features:**
- **Conditional Authentication:** Shows different links based on user authentication status
- **Responsive Design:** Uses Tailwind classes for mobile-first responsive layout
- **Named Routes:** Uses Laravel's named route system for URL generation

##### Dynamic Content Areas
```blade
<div class="max-w-7xl mx-auto p-6 lg:p-8">
    <div class="flex justify-center">
        <!-- Laravel Logo SVG -->
        <svg viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg" 
             class="h-16 w-auto bg-gray-100 dark:bg-gray-900">
            <!-- SVG path data -->
        </svg>
    </div>
    
    <div class="mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <!-- Feature cards -->
        </div>
    </div>
</div>
```

**Layout Features:**
- **Container:** Max-width container with responsive padding
- **Centered Logo:** Flexbox centering with SVG logo
- **Grid Layout:** Responsive grid that adapts from 1 to 2 columns
- **Dark Mode Support:** Built-in dark mode classes

#### Blade Directives Used

##### Authentication Directives
- `@if (Route::has('login'))` - Check if login route exists
- `@auth` - Content for authenticated users
- `@else` - Content for guests
- `@endauth` - End authentication block

##### Localization
- `{{ str_replace('_', '-', app()->getLocale()) }}` - Get app locale for HTML lang attribute
- `{{ url('/home') }}` - Generate URL for home page
- `{{ route('login') }}` - Generate URL for named route

---

## Styling

### TailwindCSS Integration

The application uses TailwindCSS for styling, with the compiled CSS inlined in the welcome template.

#### Key Utility Classes Used

##### Layout & Positioning
```css
.relative          /* position: relative */
.fixed             /* position: fixed */
.flex              /* display: flex */
.grid              /* display: grid */
.justify-center    /* justify-content: center */
.items-center      /* align-items: center */
```

##### Responsive Grid System
```css
.grid-cols-1       /* 1 column grid */
.md:grid-cols-2    /* 2 columns on medium screens+ */
.gap-6             /* 1.5rem gap */
.lg:gap-8          /* 2rem gap on large screens+ */
```

##### Spacing & Sizing
```css
.p-6               /* padding: 1.5rem */
.lg:p-8            /* padding: 2rem on large screens */
.mt-16             /* margin-top: 4rem */
.max-w-7xl         /* max-width: 80rem */
.h-16              /* height: 4rem */
.w-auto            /* width: auto */
```

##### Colors & Theming
```css
.bg-white          /* white background */
.bg-gray-100       /* light gray background */
.dark:bg-gray-900  /* dark gray background in dark mode */
.text-gray-600     /* gray text color */
.stroke-red-500    /* red stroke color for SVG */
```

##### Interactive States
```css
.hover:text-gray-900:hover     /* hover state styling */
.focus:outline:focus           /* focus state styling */
.motion-safe:hover:scale-[1.01]:hover  /* hover scale animation */
```

#### Dark Mode Support

The template includes comprehensive dark mode support:

```css
/* Light mode */
.bg-gray-100
.text-gray-600

/* Dark mode variants */
.dark:bg-gray-900
.dark:text-gray-400
.dark:bg-gray-800/50
.dark:ring-white/5
```

**Implementation:**
- Uses CSS media query `@media (prefers-color-scheme: dark)`
- Automatically adapts based on user's system preference
- Consistent color scheme across all components

### Custom CSS

**Location:** `resources/css/app.css`

Currently empty, ready for custom styles:

```css
/* Custom application styles can be added here */
/* TailwindCSS directives can also be included */

@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom component styles */
.btn-primary {
    @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
}
```

---

## Asset Management

### Development Workflow

#### Starting Development Server
```bash
# Install dependencies
npm install

# Start development server with hot reload
npm run dev
```

#### Building for Production
```bash
# Create optimized production build
npm run build

# The built assets will be placed in public/build/
```

### Asset Loading in Templates

#### Vite Integration
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Features:**
- **Development:** Loads assets from Vite dev server
- **Production:** Loads optimized, versioned assets
- **Hot Module Replacement:** Instant updates during development

### File Structure
```
resources/
├── css/
│   └── app.css          # Main CSS file
├── js/
│   ├── app.js           # Main JavaScript entry
│   └── bootstrap.js     # Bootstrap configuration
└── views/
    └── welcome.blade.php # Main template
```

---

## Frontend API Integration

### Making API Requests

#### Using Axios (Recommended)
```javascript
// Get all travels
async function fetchTravels() {
    try {
        const response = await window.axios.get('/api/travels');
        return response.data;
    } catch (error) {
        console.error('Error fetching travels:', error);
        throw error;
    }
}

// Get tours for a specific travel
async function fetchTours(travelSlug, filters = {}) {
    try {
        const params = new URLSearchParams(filters);
        const response = await window.axios.get(
            `/api/travels/${travelSlug}/tours?${params}`
        );
        return response.data;
    } catch (error) {
        console.error('Error fetching tours:', error);
        throw error;
    }
}
```

#### Using Fetch API
```javascript
// Alternative using native fetch
async function fetchTravelsWithFetch() {
    try {
        const response = await fetch('/api/travels', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}
```

### Error Handling

#### Axios Error Handling
```javascript
window.axios.get('/api/travels')
    .then(response => {
        // Handle success
        console.log(response.data);
    })
    .catch(error => {
        if (error.response) {
            // Server responded with error status
            console.error('Error Status:', error.response.status);
            console.error('Error Data:', error.response.data);
        } else if (error.request) {
            // Request was made but no response received
            console.error('No response received:', error.request);
        } else {
            // Something else happened
            console.error('Error:', error.message);
        }
    });
```

### CSRF Protection

#### Automatic CSRF Handling
```javascript
// CSRF token is automatically included in requests
// when using Axios with the bootstrap configuration

// For forms, include CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Add to form data
const formData = new FormData();
formData.append('_token', csrfToken);
```

---

## Component Examples

### Travel Listing Component (Example)

```javascript
class TravelListing {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.travels = [];
    }
    
    async loadTravels() {
        try {
            this.travels = await window.axios.get('/api/travels')
                .then(response => response.data);
            this.render();
        } catch (error) {
            this.renderError(error);
        }
    }
    
    render() {
        const html = this.travels.map(travel => `
            <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                <h3 class="text-xl font-semibold mb-2">${travel.name}</h3>
                <p class="text-gray-600 mb-4">${travel.description}</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        ${travel.number_of_days} days, ${travel.number_of_nights} nights
                    </span>
                    <a href="/travels/${travel.slug}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        View Tours
                    </a>
                </div>
            </div>
        `).join('');
        
        this.container.innerHTML = html;
    }
    
    renderError(error) {
        this.container.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Error:</strong> Unable to load travels. Please try again later.
            </div>
        `;
    }
}

// Usage
document.addEventListener('DOMContentLoaded', () => {
    const travelListing = new TravelListing('travels-container');
    travelListing.loadTravels();
});
```

### Tour Filter Component (Example)

```javascript
class TourFilter {
    constructor(formId, resultsId) {
        this.form = document.getElementById(formId);
        this.results = document.getElementById(resultsId);
        this.currentTravel = null;
        
        this.bindEvents();
    }
    
    bindEvents() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.filterTours();
        });
    }
    
    async filterTours() {
        const formData = new FormData(this.form);
        const filters = Object.fromEntries(formData.entries());
        
        try {
            const tours = await window.axios.get(
                `/api/travels/${this.currentTravel}/tours`,
                { params: filters }
            ).then(response => response.data);
            
            this.renderTours(tours);
        } catch (error) {
            this.renderError(error);
        }
    }
    
    renderTours(tours) {
        const html = tours.map(tour => `
            <div class="border rounded-lg p-4 mb-4">
                <h4 class="font-semibold">${tour.name}</h4>
                <p class="text-gray-600">${tour.travel}</p>
                <div class="flex justify-between mt-2">
                    <span>${tour.starting_at} - ${tour.ending_at}</span>
                    <span class="font-bold">$${tour.price}</span>
                </div>
            </div>
        `).join('');
        
        this.results.innerHTML = html || '<p>No tours found matching your criteria.</p>';
    }
    
    setTravel(travelSlug) {
        this.currentTravel = travelSlug;
    }
}
```

---

## Performance Considerations

### Asset Optimization

#### Vite Optimizations
- **Code Splitting:** Automatic code splitting for better loading performance
- **Tree Shaking:** Removes unused code from bundles
- **Minification:** Compresses JavaScript and CSS for production
- **Cache Busting:** Automatic versioning for cache invalidation

#### Image Optimization
```javascript
// Lazy loading images
const images = document.querySelectorAll('img[data-src]');
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            observer.unobserve(img);
        }
    });
});

images.forEach(img => imageObserver.observe(img));
```

### JavaScript Performance

#### Debouncing API Calls
```javascript
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Usage for search input
const searchInput = document.getElementById('search');
const debouncedSearch = debounce(async (query) => {
    const results = await window.axios.get(`/api/search?q=${query}`);
    displayResults(results.data);
}, 300);

searchInput.addEventListener('input', (e) => {
    debouncedSearch(e.target.value);
});
```

---

## Best Practices

### JavaScript Best Practices

1. **Use Modern ES6+ Syntax**
   ```javascript
   // Use const/let instead of var
   const apiUrl = '/api/travels';
   
   // Use arrow functions
   const fetchData = async () => await window.axios.get(apiUrl);
   
   // Use destructuring
   const { data, status } = await fetchData();
   ```

2. **Error Handling**
   ```javascript
   // Always handle errors gracefully
   try {
       const data = await fetchData();
       renderData(data);
   } catch (error) {
       console.error('Error:', error);
       showErrorMessage('Failed to load data');
   }
   ```

3. **Async/Await over Promises**
   ```javascript
   // Preferred
   async function loadTravels() {
       const response = await window.axios.get('/api/travels');
       return response.data;
   }
   
   // Instead of
   function loadTravels() {
       return window.axios.get('/api/travels')
           .then(response => response.data);
   }
   ```

### CSS Best Practices

1. **Use Tailwind Utilities**
   ```html
   <!-- Preferred -->
   <div class="bg-white rounded-lg shadow-md p-6">
   
   <!-- Instead of custom CSS -->
   <div class="card">
   ```

2. **Responsive Design First**
   ```html
   <!-- Mobile first, then larger screens -->
   <div class="text-sm md:text-base lg:text-lg">
   ```

3. **Consistent Spacing**
   ```html
   <!-- Use consistent spacing scale -->
   <div class="p-4 mb-6 mt-8">
   ```

---

This documentation covers all the frontend components, JavaScript functionality, and styling approaches used in the Travel & Tour API application. The frontend is designed to be simple, maintainable, and ready for expansion with additional features and components.