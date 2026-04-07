# Sentosa CMS - API Documentation

**Version:** 1.0
**Date:** 2026-04-05
**Author:** SentosaTech Team

---

## Table of Contents

1. [Introduction](#introduction)
2. [API Endpoints](#api-endpoints)
3. [CORS Configuration](#cors-configuration)
4. [Authentication](#authentication)
5. [Database Schema](#database-schema)
6. [Response Format](#response-format)
7. [Error Handling](#error-handling)
8. [Frontend Integration](#frontend-integration)
9. [Testing](#testing)
10. [Examples](#examples)

---

## Introduction

The Sentosa CMS API provides RESTful endpoints for managing content displayed on the landing page. All endpoints are public (no authentication required) for read operations.

**Base URL:** `http://localhost:8000/api/v1`

**Available Data:**
- Projects (3 records)
- Testimonials (3 records)
- Statistics (4 records)
- Clients (5 records)
- Process Info (4 records)

---

## API Endpoints

### Projects API

#### Get All Projects

**Endpoint:** `GET /api/v1/projects`

**Description:** Retrieve all active projects ordered by display_order and created_at

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category | string | No | Filter projects by category |

**Example:**
```bash
curl http://localhost:8000/api/v1/projects
curl "http://localhost:8000/api/v1/projects?category=Platform Web Fintech"
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "slug": "pulse-market",
      "title": "Pulse Market",
      "category": "Platform Web Fintech",
      "description": "Marketplace investasi...",
      "status": "Production",
      "image": "https://via.placeholder.com/400x250/4F46E5/FFFFFF?text=Pulse+Market",
      "project_url": "https://pulse-market.example.com",
      "technologies": ["React", "Node.js", "MongoDB", "Stripe API"],
      "features": ["Multi-wallet payments", "Real-time analytics", "Investor dashboard"],
      "display_order": 1,
      "is_active": true,
      "created_at": "2026-04-05T...",
      "updated_at": "2026-04-05T..."
    }
  ],
  "count": 3
}
```

#### Get Single Project

**Endpoint:** `GET /api/v1/projects/{slug}`

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| slug | string | Yes | Project slug |

**Example:**
```bash
curl http://localhost:8000/api/v1/projects/pulse-market
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Project retrieved successfully",
  "data": {
    "id": "uuid-here",
    "slug": "pulse-market",
    "title": "Pulse Market",
    "category": "Platform Web Fintech",
    "description": "Marketplace investasi...",
    "status": "Production",
    "image": "https://...",
    "project_url": "https://...",
    "technologies": ["React", "Node.js", "MongoDB"],
    "features": ["Multi-wallet payments", "Real-time analytics"],
    "display_order": 1,
    "is_active": true
  }
}
```

**Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Project not found"
}
```

---

### Testimonials API

#### Get All Testimonials

**Endpoint:** `GET /api/v1/testimonials`

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| rating | integer | No | Filter testimonials by rating (1-5) |

**Example:**
```bash
curl http://localhost:8000/api/v1/testimonials
curl http://localhost:8000/api/v1/testimonials?rating=5
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Testimonials retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "text": "Tim Sentosaku sangat profesional...",
      "author": "John Doe",
      "title": "CEO, TechCorp Indonesia",
      "initials": "JD",
      "rating": 5,
      "display_order": 1,
      "is_active": true
    }
  ],
  "count": 3
}
```

---

### Stats API

#### Get All Stats

**Endpoint:** `GET /api/v1/stats`

**Description:** Retrieve all active statistics ordered by display_order

**Example:**
```bash
curl http://localhost:8000/api/v1/stats
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Stats retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "value": "32",
      "label": "Produk digital sukses",
      "display_order": 1,
      "is_active": true
    },
    {
      "id": "uuid-here",
      "value": "150",
      "label": "Klien puas",
      "display_order": 2,
      "is_active": true
    }
  ],
  "count": 4
}
```

---

### Clients API

#### Get All Clients

**Endpoint:** `GET /api/v1/clients`

**Description:** Retrieve all active clients ordered by display_order

**Example:**
```bash
curl http://localhost:8000/api/v1/clients
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Clients retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "name": "TechCorp Indonesia",
      "initial": "TC",
      "logo_url": "https://via.placeholder.com/100x100/4F46E5/FFFFFF?text=TC",
      "display_order": 1,
      "is_active": true
    }
  ],
  "count": 5
}
```

---

### Process Info API

#### Get All Process Info

**Endpoint:** `GET /api/v1/process-info`

**Description:** Retrieve all active process info ordered by display_order

**Example:**
```bash
curl http://localhost:8000/api/v1/process-info
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Process info retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "label": "Proyek Selesai",
      "value": "150.00",
      "unit": "produk",
      "display_order": 1,
      "is_active": true
    },
    {
      "id": "uuid-here",
      "label": "Client Puas",
      "value": "95.00",
      "unit": "%",
      "display_order": 2,
      "is_active": true
    }
  ],
  "count": 4
}
```

---

## CORS Configuration

### Current CORS Settings

The API is configured to allow cross-origin requests from any domain during development.

**CORS Headers:**
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin`
- `Access-Control-Allow-Credentials: true`
- `Access-Control-Max-Age: 86400` (24 hours)

### Middleware

File: `app/Http/Middleware/CorsMiddleware.php`

This middleware handles:
- Preflight OPTIONS requests
- CORS headers injection
- Access control for different domains

### Production Configuration

For production, update `app/Http/Middleware/CorsMiddleware.php` to restrict origins:

```php
$allowedOrigins = [
    'http://localhost:3000',
    'https://your-frontend-domain.com',
];

$origin = $request->headers->get('Origin');

if (in_array($origin, $allowedOrigins)) {
    $response->headers->set('Access-Control-Allow-Origin', $origin);
}
```

---

## Authentication

**Current Status:** Public (No authentication required)

All API endpoints are currently public and do not require authentication tokens. This is suitable for public landing page content.

**Future Enhancement:** Admin endpoints with JWT authentication for CRUD operations.

---

## Database Schema

### Projects Table
```sql
CREATE TABLE projects (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    slug VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) NOT NULL,
    image VARCHAR(500) NOT NULL,
    project_url VARCHAR(500) NOT NULL,
    technologies JSONB DEFAULT '[]'::jsonb,
    features JSONB DEFAULT '[]'::jsonb,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);
```

### Testimonials Table
```sql
CREATE TABLE testimonials (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    text TEXT NOT NULL,
    author VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    initials VARCHAR(10) NOT NULL,
    rating INTEGER DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);
```

### Stats Table
```sql
CREATE TABLE stats (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    value VARCHAR(100) NOT NULL,
    label VARCHAR(255) NOT NULL,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);
```

### Clients Table
```sql
CREATE TABLE clients (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) UNIQUE NOT NULL,
    initial VARCHAR(10) NOT NULL,
    logo_url VARCHAR(500),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);
```

### Process Info Table
```sql
CREATE TABLE process_infos (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    label VARCHAR(255) NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);
```

---

## Response Format

### Success Response (200 OK)

```json
{
  "success": true,
  "message": "...successfully",
  "data": [...],
  "count": 10
}
```

### Not Found Response (404)

```json
{
  "success": false,
  "message": "...not found"
}
```

### Error Response (500)

```json
{
  "success": false,
  "message": "Error retrieving...",
  "error": "Detailed error message"
}
```

---

## Error Handling

All API endpoints implement comprehensive error handling:

- Try-catch blocks
- ModelNotFoundException handling (returns 404)
- Generic exception handling (returns 500)
- Consistent error messages
- Proper HTTP status codes

---

## Frontend Integration

### JavaScript (Vanilla JS)

#### Fetch API

```javascript
const API_BASE_URL = 'http://localhost:8000/api/v1';

// Get all projects
async function getProjects(category = null) {
  try {
    const params = category ? { category } : {};
    const response = await fetch(`${API_BASE_URL}/projects?${new URLSearchParams(params)}`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching projects:', error);
    throw error;
  }
}

// Get single project
async function getProject(slug) {
  try {
    const response = await fetch(`${API_BASE_URL}/projects/${slug}`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching project:', error);
    throw error;
  }
}

// Get testimonials
async function getTestimonials(rating = null) {
  try {
    const params = rating ? { rating } : {};
    const response = await fetch(`${API_BASE_URL}/testimonials?${new URLSearchParams(params)}`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching testimonials:', error);
    throw error;
  }
}

// Get stats
async function getStats() {
  try {
    const response = await fetch(`${API_BASE_URL}/stats`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching stats:', error);
    throw error;
  }
}

// Get clients
async function getClients() {
  try {
    const response = await fetch(`${API_BASE_URL}/clients`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching clients:', error);
    throw error;
  }
}

// Get process info
async function getProcessInfo() {
  try {
    const response = await fetch(`${API_BASE_URL}/process-info`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    console.error('Error fetching process info:', error);
    throw error;
  }
}
```

### Axios (Recommended)

#### Installation

```bash
npm install axios
```

#### API Client

```javascript
// src/api/client.js
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Response interceptor
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error.response?.data || error.message);
    return Promise.reject(error);
  }
);

export default apiClient;
```

#### API Functions

```javascript
// src/api/projects.js
import apiClient from './client';

export const getProjects = async (category = null) => {
  const params = category ? { category } : {};
  const response = await apiClient.get('/projects', { params });
  return response.data;
};

export const getProjectBySlug = async (slug) => {
  const response = await apiClient.get(`/projects/${slug}`);
  return response.data;
};

// src/api/testimonials.js
import apiClient from './client';

export const getTestimonials = async (rating = null) => {
  const params = rating ? { rating } : {};
  const response = await apiClient.get('/testimonials', { params });
  return response.data;
};

// src/api/stats.js
import apiClient from './client';

export const getStats = async () => {
  const response = await apiClient.get('/stats');
  return response.data;
};

// src/api/clients.js
import apiClient from './client';

export const getClients = async () => {
  const response = await apiClient.get('/clients');
  return response.data;
};

// src/api/processInfo.js
import apiClient from './client';

export const getProcessInfo = async () => {
  const response = await apiClient.get('/process-info');
  return response.data;
};
```

### React Integration

#### Projects Component

```javascript
import React, { useState, useEffect } from 'react';
import { getProjects } from '../../api/projects';

function ProjectsPage() {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [category, setCategory] = useState('');

  useEffect(() => {
    fetchProjects();
  }, []);

  const fetchProjects = async () => {
    try {
      setLoading(true);
      const result = await getProjects(category || null);
      setProjects(result.data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading projects...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h1>Projects ({projects.count || projects.length})</h1>

      <div className="category-filter">
        <select value={category} onChange={(e) => setCategory(e.target.value)}>
          <option value="">All Categories</option>
          <option value="Platform Web Fintech">Platform Web Fintech</option>
          <option value="E-commerce">E-commerce</option>
          <option value="Mobile App Development">Mobile App Development</option>
        </select>
      </div>

      <div className="projects-grid">
        {projects.map(project => (
          <div key={project.id} className="project-card">
            <img src={project.image} alt={project.title} />
            <h3>{project.title}</h3>
            <p>{project.description}</p>
            <span className="badge">{project.category}</span>
            <span className="badge">{project.status}</span>
            <a href={project.project_url} target="_blank">View Project</a>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ProjectsPage;
```

### Vue.js Integration

#### Projects Component

```vue
<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <h1>Projects ({{ projects.length }})</h1>

      <select v-model="selectedCategory" @change="fetchProjects">
        <option value="">All Categories</option>
        <option value="Platform Web Fintech">Platform Web Fintech</option>
        <option value="E-commerce">E-commerce</option>
        <option value="Mobile App Development">Mobile App Development</option>
      </select>

      <div class="projects-grid">
        <div v-for="project in projects" :key="project.id" class="project-card">
          <img :src="project.image" :alt="project.title" />
          <h3>{{ project.title }}</h3>
          <p>{{ project.description }}</p>
          <span class="badge">{{ project.category }}</span>
          <span class="badge">{{ project.status }}</span>
          <a :href="project.project_url" target="_blank">View Project</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getProjects } from '@/api/projects';

const projects = ref([]);
const loading = ref(false);
const error = ref(null);
const selectedCategory = ref('');

const fetchProjects = async () => {
  try {
    loading.value = true;
    const result = await getProjects(selectedCategory.value || null);
    projects.value = result.data;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProjects();
});
</script>

<style scoped>
.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  padding: 20px 0;
}

.project-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
}

.project-card img {
  max-width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 4px;
}

.project-card h3 {
  margin: 15px 0 10px;
  color: #333;
}

.project-card p {
  color: #666;
  margin-bottom: 10px;
}

.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  margin: 5px;
}

.badge:first-child {
  background: #4F46E5;
  color: white;
}

.badge:last-child {
  background: #DC3545;
  color: white;
}

.project-card a {
  display: inline-block;
  padding: 10px 20px;
  background: #4F46E5;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  margin-top: 10px;
}

.error {
  color: #DC3545;
  padding: 20px;
  background: #FFEAEA;
  border-radius: 8px;
}
</style>
```

---

## Testing

### Browser Testing

Open these URLs in your browser:

```
http://localhost:8000/api/v1/projects
http://localhost:8000/api/v1/projects/pulse-market
http://localhost:8000/api/v1/testimonials
http://localhost:8000/api/v1/stats
http://localhost:8000/api/v1/clients
http://localhost:8000/api/v1/process-info
```

### cURL Testing

```bash
# Get all projects
curl http://localhost:8000/api/v1/projects

# Get single project
curl http://localhost:8000/api/v1/projects/pulse-market

# Filter by category
curl "http://localhost:8000/api/v1/projects?category=Platform Web Fintech"

# Get testimonials
curl http://localhost:8000/api/v1/testimonials

# Filter by rating
curl http://localhost:8000/api/v1/testimonials?rating=5

# Get stats
curl http://localhost:8000/api/v1/stats

# Get clients
curl http://localhost:8000/api/v1/clients

# Get process info
curl http://localhost:8000/api/v1/process-info
```

### Postman/Insomnia Testing

1. Import collection
2. Set Base URL: `http://localhost:8000/api/v1`
3. Test each endpoint
4. Check response JSON
5. Verify CORS headers

---

## Examples

### Example 1: Display Projects on Frontend

```html
<!DOCTYPE html>
<html>
<head>
    <title>Projects - Sentosa</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div id="projects-container">
        <h1>Projects</h1>
        <div class="loading">Loading...</div>
        <div class="projects-grid"></div>
    </div>

    <script>
        const API_BASE_URL = 'http://localhost:8000/api/v1';

        async function loadProjects() {
            try {
                const response = await fetch(`${API_BASE_URL}/projects`);
                const result = await response.json();

                if (result.success) {
                    displayProjects(result.data);
                } else {
                    showError(result.message);
                }
            } catch (error) {
                showError('Error loading projects');
                console.error(error);
            }
        }

        function displayProjects(projects) {
            const container = document.getElementById('projects-container');
            const grid = container.querySelector('.projects-grid');

            grid.innerHTML = projects.map(project => `
                <div class="project-card">
                    <img src="${project.image}" alt="${project.title}" class="project-image">
                    <div class="project-info">
                        <h2 class="project-title">${project.title}</h2>
                        <p class="project-description">${project.description}</p>
                        <div class="project-meta">
                            <span class="badge category">${project.category}</span>
                            <span class="badge status">${project.status}</span>
                        </div>
                        <div class="technologies">
                            ${project.technologies.map(tech => `<span class="tech-badge">${tech}</span>`).join('')}
                        </div>
                        <div class="features">
                            ${project.features.map(feature => `<span class="feature-item">• ${feature}</span>`).join('')}
                        </div>
                        <a href="${project.project_url}" class="btn-primary" target="_blank">View Project</a>
                    </div>
                </div>
            `).join('');

            container.querySelector('.loading').style.display = 'none';
        }

        function showError(message) {
            const container = document.getElementById('projects-container');
            const loading = container.querySelector('.loading');

            loading.innerHTML = `
                <div class="error-message">
                    <span class="error-icon">⚠️</span>
                    ${message}
                </div>
            `;
        }

        loadProjects();
    </script>
</body>
</html>
```

### Example 2: Filter by Category

```javascript
// Filter projects by category
async function filterProjectsByCategory(category) {
    const response = await fetch(`http://localhost:8000/api/v1/projects?category=${encodeURIComponent(category)}`);
    const result = await response.json();
    return result.data;
}

// Usage
const fintechProjects = await filterProjectsByCategory('Platform Web Fintech');
console.log('Fintech Projects:', fintechProjects);
```

### Example 3: Display Testimonials with Stars

```javascript
async function loadTestimonials() {
    const response = await fetch('http://localhost:8000/api/v1/testimonials');
    const result = await response.json();

    const container = document.getElementById('testimonials-container');

    container.innerHTML = result.data.map(testimonial => `
        <div class="testimonial-card">
            <div class="testimonial-avatar">
                ${testimonial.initials}
            </div>
            <div class="testimonial-content">
                <p class="testimonial-text">${testimonial.text}</p>
                <div class="testimonial-author">
                    <strong>${testimonial.author}</strong>
                    <span class="author-title">${testimonial.title}</span>
                </div>
                <div class="testimonial-rating">
                    ${'★'.repeat(testimonial.rating)}
                </div>
            </div>
        </div>
    `).join('');
}
```

### Example 4: Display Stats

```javascript
async function loadStats() {
    const response = await fetch('http://localhost:8000/api/v1/stats');
    const result = await response.json();

    const container = document.getElementById('stats-container');

    container.innerHTML = `
        <div class="stats-container">
            <h1>Statistics</h1>
            <div class="stats-grid">
                ${result.data.map(stat => `
                    <div class="stat-card">
                        <div class="stat-value">${stat.value}</div>
                        <div class="stat-label">${stat.label}</div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}
```

### Example 5: Display Clients Logos

```javascript
async function loadClients() {
    const response = await fetch('http://localhost:8000/api/v1/clients');
    const result = await response.json();

    const container = document.getElementById('clients-container');

    container.innerHTML = `
        <div class="clients-container">
            <h1>Our Clients</h1>
            <div class="clients-grid">
                ${result.data.map(client => `
                    <div class="client-card">
                        <div class="client-logo">
                            ${client.logo_url
                                ? `<img src="${client.logo_url}" alt="${client.name}">`
                                : `<div class="client-initial">${client.initial}</div>`
                            }
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}
```

---

## Troubleshooting

### CORS Errors

If you encounter CORS errors:

1. **Check CORS middleware is registered** in `bootstrap/app.php`
2. **Clear caches:** `php artisan config:clear && php artisan cache:clear`
3. **Check browser console** for detailed error messages
4. **Verify middleware order** in bootstrap/app.php

### API Returns 404

1. **Verify URL** is correct
2. **Check slug** is valid
3. **Verify record** exists in database
4. **Check is_active** is true

### API Returns 500

1. **Check database connection** in `.env`
2. **Check database migrations** are run
3. **Check tables exist** in database
4. **Review logs** in `storage/logs/laravel.log`

### No Data Returned

1. **Check seeder ran** successfully
2. **Verify data exists** in database
3. **Check is_active** flag is true
4. **Check deleted_at** is NULL

---

## Changelog

### Version 1.0 (2026-04-05)
- Initial API release
- Projects API (index, show)
- Testimonials API (index)
- Stats API (index)
- Clients API (index)
- Process Info API (index)
- CORS middleware
- Database seeders with dummy data

---

## Support

For support or questions, contact:
- Email: support@sentosaku.com
- Repository: [GitHub Link]
- Documentation: [Docs Link]

---

**License:** MIT
**Copyright:** © 2026 SentosaTech. All rights reserved.
