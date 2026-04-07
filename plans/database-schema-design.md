# Sentosaku Landing Page - Comprehensive Database Schema Design

**Project:** Sentosaku Landing Page  
**Version:** 1.0  
**Date:** 2026-04-04  
**Author:** Database Architecture Team

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Database Technology Recommendation](#database-technology-recommendation)
3. [Core Tables Design](#core-tables-design)
4. [Entity Relationship Diagram](#entity-relationship-diagram)
5. [Detailed Schema Specifications](#detailed-schema-specifications)
6. [ORM/Library Recommendation](#ormlibrary-recommendation)
7. [Migration Strategy](#migration-strategy)
8. [Performance Considerations](#performance-considerations)
9. [Security Considerations](#security-considerations)
10. [Future Scalability](#future-scalability)
11. [SQL DDL Statements](#sql-ddl-statements)
12. [Prisma Schema Example](#prisma-schema-example)

---

## Executive Summary

This document outlines a comprehensive database schema design for the Sentosaku landing page project. The design supports dynamic content management for a Next.js-based web & mobile development studio landing page, replacing the current hardcoded data approach with a robust, scalable database solution.

**Key Design Decisions:**
- **Database:** PostgreSQL with Prisma ORM
- **Architecture:** Relational schema with audit trails
- **Performance:** Optimized for read-heavy operations with caching
- **Scalability:** Extensible design supporting future features
- **Security:** Multi-layered security approach

---

## Database Technology Recommendation

### Recommended Technology: PostgreSQL

**Justification:**

#### 1. Data Structure Alignment
- **Relational Nature:** The landing page data has clear relationships (projects → technologies, testimonials → ratings, etc.)
- **Structured Data:** All entities have well-defined schemas with typed fields
- **ACID Compliance:** Ensures data integrity for content management operations

#### 2. Scalability Requirements
- **Read-Heavy Workload:** Landing pages are primarily read operations (90% reads, 10% writes)
- **Horizontal Scaling:** PostgreSQL supports read replicas for high availability
- **Connection Pooling:** Efficient handling of concurrent requests
- **Performance:** Handles millions of queries per second with proper indexing

#### 3. Performance Needs
- **Indexing:** Advanced indexing (B-tree, GIN, partial indexes) for fast lookups
- **Query Optimization:** Sophisticated query planner for optimal execution
- **JSONB Support:** Flexible storage for arrays (technologies, features) without separate tables
- **Caching:** Built-in query plan caching

#### 4. Next.js Integration
- **Prisma Support:** Excellent Prisma ORM support with type-safe queries
- **Edge Compatibility:** Works with Vercel Postgres and Neon (serverless PostgreSQL)
- **TypeScript Integration:** Native TypeScript support with Prisma Client
- **Migration System:** Automated migrations with version control

#### 5. Cost Considerations
- **Free Tier Options:**
  - Neon: Free tier with 0.5GB storage, 500 hours compute
  - Supabase: Free tier with 500MB storage, 2GB bandwidth
  - Railway: Free tier with 1GB storage
- **Managed Services:** Vercel Postgres, AWS RDS, Google Cloud SQL
- **Self-Hosted:** Cost-effective for high-traffic scenarios

### Alternative Technologies Considered

| Technology | Pros | Cons | Verdict |
|------------|------|------|---------|
| **PostgreSQL** | Full-featured, ACID, JSONB, excellent Prisma support | Slightly higher learning curve | ✅ **RECOMMENDED** |
| MySQL | Widely used, good performance | Less advanced features, weaker JSON support | Not recommended |
| MongoDB | Flexible schema, good for unstructured data | Overkill for structured data, weaker ACID | Not recommended |
| SQLite | Simple, serverless | Limited scalability, no concurrent writes | Not recommended |

---

## Core Tables Design

### Overview

The database consists of 6 core tables:

1. **projects** - Stores portfolio/project information
2. **testimonials** - Stores client testimonials
3. **stats** - Stores statistics/metrics
4. **clients** - Stores client company information
5. **process_info** - Stores process metrics
6. **audit_logs** - Tracks all data modifications

### Table Relationships

```
projects (independent)
  ├── technologies (JSONB array)
  └── features (JSONB array)

testimonials (independent)
  └── rating (1-5 scale)

stats (independent)

clients (independent)

process_info (independent)

audit_logs (tracks all tables)
```

---

## Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SENTOSAKU LANDING PAGE DATABASE                       │
│                              Entity Relationship Diagram                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     projects    │       │   testimonials  │       │      stats      │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │       │ id (PK)         │       │ id (PK)         │
│ slug (UNIQUE)   │       │ text            │       │ value           │
│ title           │       │ author          │       │ label           │
│ category        │       │ title           │       │ display_order   │
│ description     │       │ initials        │       │ is_active       │
│ status          │       │ rating          │       │ created_at      │
│ image           │       │ display_order   │       │ updated_at      │
│ project_url     │       │ is_active       │       │ deleted_at      │
│ technologies    │       │ created_at      │       │                 │
│ features        │       │ updated_at      │       │                 │
│ display_order   │       │ deleted_at      │       │                 │
│ is_active       │       │                 │       │                 │
│ created_at      │       │                 │       │                 │
│ updated_at      │       │                 │       │                 │
│ deleted_at      │       │                 │       │                 │
└─────────────────┘       └─────────────────┘       └─────────────────┘

┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     clients     │       │  process_info   │       │   audit_logs    │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │       │ id (PK)         │       │ id (PK)         │
│ name            │       │ label           │       │ table_name      │
│ initial         │       │ value           │       │ record_id       │
│ logo_url        │       │ unit            │       │ action          │
│ display_order   │       │ display_order   │       │ old_values      │
│ is_active       │       │ is_active       │       │ new_values      │
│ created_at      │       │ created_at      │       │ changed_by      │
│ updated_at      │       │ updated_at      │       │ changed_at      │
│ deleted_at      │       │ deleted_at      │       │                 │
└─────────────────┘       └─────────────────┘       └─────────────────┘

Legend:
  PK  - Primary Key
  UK  - Unique Key
  FK  - Foreign Key
  IX  - Index
```

---

## Detailed Schema Specifications

### 1. Projects Table

**Purpose:** Stores portfolio/project information displayed on the landing page.

**Table Name:** `projects`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier |
| title | VARCHAR(255) | NOT NULL | Project title |
| category | VARCHAR(100) | NOT NULL | Project category/type |
| description | TEXT | NOT NULL | Project description |
| status | VARCHAR(50) | NOT NULL | Current status (e.g., "Beta publik") |
| image | VARCHAR(500) | NOT NULL | URL to project image |
| project_url | VARCHAR(500) | NOT NULL | URL to live project |
| technologies | JSONB | NOT NULL, DEFAULT '[]'::jsonb | Array of technologies used |
| features | JSONB | NOT NULL, DEFAULT '[]'::jsonb | Key features of the project |
| display_order | INTEGER | NOT NULL, DEFAULT 0 | Display order on page |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | Whether project is visible |
| created_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Last update timestamp |
| deleted_at | TIMESTAMPTZ | NULLABLE | Soft delete timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `UNIQUE INDEX idx_projects_slug (slug)`
- `INDEX idx_projects_category (category)`
- `INDEX idx_projects_status (status)`
- `INDEX idx_projects_display_order (display_order)`
- `INDEX idx_projects_is_active (is_active)`
- `INDEX idx_projects_created_at (created_at)`
- `GIN INDEX idx_projects_technologies (technologies)` - For searching technologies
- `GIN INDEX idx_projects_features (features)` - For searching features

**Constraints:**
- `CHECK (char_length(slug) >= 3)` - Minimum slug length
- `CHECK (char_length(title) >= 2)` - Minimum title length
- `CHECK (jsonb_typeof(technologies) = 'array')` - Validate technologies is array
- `CHECK (jsonb_typeof(features) = 'array')` - Validate features is array
- `CHECK (rating BETWEEN 1 AND 5)` - If rating is added in future

**Example Data:**
```json
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "slug": "pulse-market",
  "title": "Pulse Market",
  "category": "Platform Web Fintech",
  "description": "Marketplace investasi responsif dengan pembayaran multi-dompet dan dasbor investor yang akurat.",
  "status": "Beta publik",
  "image": "/api/placeholder/400/250",
  "project_url": "https://pulse-market.example.com",
  "technologies": ["React", "Node.js", "MongoDB", "Stripe API"],
  "features": ["Multi-wallet payments", "Real-time analytics", "Investor dashboard"],
  "display_order": 1,
  "is_active": true,
  "created_at": "2026-04-04T09:49:00.000Z",
  "updated_at": "2026-04-04T09:49:00.000Z",
  "deleted_at": null
}
```

---

### 2. Testimonials Table

**Purpose:** Stores client testimonials with ratings.

**Table Name:** `testimonials`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| text | TEXT | NOT NULL | Testimonial content |
| author | VARCHAR(255) | NOT NULL | Author's name |
| title | VARCHAR(255) | NOT NULL | Author's title/company |
| initials | VARCHAR(10) | NOT NULL | Author's initials for avatar |
| rating | INTEGER | NOT NULL, DEFAULT 5, CHECK (rating BETWEEN 1 AND 5) | Star rating (1-5) |
| display_order | INTEGER | NOT NULL, DEFAULT 0 | Display order on page |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | Whether testimonial is visible |
| created_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Last update timestamp |
| deleted_at | TIMESTAMPTZ | NULLABLE | Soft delete timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `INDEX idx_testimonials_author (author)`
- `INDEX idx_testimonials_rating (rating)`
- `INDEX idx_testimonials_display_order (display_order)`
- `INDEX idx_testimonials_is_active (is_active)`
- `INDEX idx_testimonials_created_at (created_at)`
- `FULLTEXT INDEX idx_testimonials_text (text)` - For text search

**Constraints:**
- `CHECK (char_length(author) >= 2)` - Minimum author name length
- `CHECK (char_length(initials) >= 2 AND char_length(initials) <= 10)` - Initials length
- `CHECK (rating >= 1 AND rating <= 5)` - Valid rating range

**Example Data:**
```json
{
  "id": "660e8400-e29b-41d4-a716-446655440001",
  "text": "Tim Sentosaku sangat profesional dalam mengembangkan aplikasi mobile kami. Proses yang transparan dan hasil yang melebihi ekspektasi.",
  "author": "John Doe",
  "title": "CEO, TechCorp Indonesia",
  "initials": "JD",
  "rating": 5,
  "display_order": 1,
  "is_active": true,
  "created_at": "2026-04-04T09:49:00.000Z",
  "updated_at": "2026-04-04T09:49:00.000Z",
  "deleted_at": null
}
```

---

### 3. Stats Table

**Purpose:** Stores statistics/metrics displayed on the landing page.

**Table Name:** `stats`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| value | VARCHAR(100) | NOT NULL | The value to display |
| label | VARCHAR(255) | NOT NULL | Label describing the statistic |
| display_order | INTEGER | NOT NULL, DEFAULT 0 | Display order on page |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | Whether stat is visible |
| created_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Last update timestamp |
| deleted_at | TIMESTAMPTZ | NULLABLE | Soft delete timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `INDEX idx_stats_label (label)`
- `INDEX idx_stats_display_order (display_order)`
- `INDEX idx_stats_is_active (is_active)`
- `INDEX idx_stats_created_at (created_at)`

**Constraints:**
- `CHECK (char_length(value) >= 1)` - Minimum value length
- `CHECK (char_length(label) >= 2)` - Minimum label length

**Example Data:**
```json
{
  "id": "770e8400-e29b-41d4-a716-446655440002",
  "value": "32",
  "label": "Produk digital sukses",
  "display_order": 1,
  "is_active": true,
  "created_at": "2026-04-04T09:49:00.000Z",
  "updated_at": "2026-04-04T09:49:00.000Z",
  "deleted_at": null
}
```

---

### 4. Clients Table

**Purpose:** Stores client company information.

**Table Name:** `clients`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Client company name |
| initial | VARCHAR(10) | NOT NULL | Initials for logo display |
| logo_url | VARCHAR(500) | NULLABLE | URL to client logo |
| display_order | INTEGER | NOT NULL, DEFAULT 0 | Display order on page |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | Whether client is visible |
| created_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Last update timestamp |
| deleted_at | TIMESTAMPTZ | NULLABLE | Soft delete timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `UNIQUE INDEX idx_clients_name (name)`
- `INDEX idx_clients_initial (initial)`
- `INDEX idx_clients_display_order (display_order)`
- `INDEX idx_clients_is_active (is_active)`
- `INDEX idx_clients_created_at (created_at)`

**Constraints:**
- `CHECK (char_length(name) >= 2)` - Minimum name length
- `CHECK (char_length(initial) >= 2 AND char_length(initial) <= 10)` - Initials length

**Example Data:**
```json
{
  "id": "880e8400-e29b-41d4-a716-446655440003",
  "name": "TechCorp",
  "initial": "TC",
  "logo_url": null,
  "display_order": 1,
  "is_active": true,
  "created_at": "2026-04-04T09:49:00.000Z",
  "updated_at": "2026-04-04T09:49:00.000Z",
  "deleted_at": null
}
```

---

### 5. Process Info Table

**Purpose:** Stores process information displayed in the hero panel.

**Table Name:** `process_info`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| label | VARCHAR(255) | NOT NULL | Label for the process item |
| value | NUMERIC(10,2) | NOT NULL | Numeric value |
| unit | VARCHAR(50) | NOT NULL | Unit of measurement |
| display_order | INTEGER | NOT NULL, DEFAULT 0 | Display order on page |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | Whether process info is visible |
| created_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | Last update timestamp |
| deleted_at | TIMESTAMPTZ | NULLABLE | Soft delete timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `INDEX idx_process_info_label (label)`
- `INDEX idx_process_info_display_order (display_order)`
- `INDEX idx_process_info_is_active (is_active)`
- `INDEX idx_process_info_created_at (created_at)`

**Constraints:**
- `CHECK (char_length(label) >= 2)` - Minimum label length
- `CHECK (value >= 0)` - Non-negative value
- `CHECK (char_length(unit) >= 1)` - Minimum unit length

**Example Data:**
```json
{
  "id": "990e8400-e29b-41d4-a716-446655440004",
  "label": "Proyek Selesai",
  "value": 150,
  "unit": "produk",
  "display_order": 1,
  "is_active": true,
  "created_at": "2026-04-04T09:49:00.000Z",
  "updated_at": "2026-04-04T09:49:00.000Z",
  "deleted_at": null
}
```

---

### 6. Audit Logs Table

**Purpose:** Tracks all data modifications for audit and rollback purposes.

**Table Name:** `audit_logs`

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | Unique identifier |
| table_name | VARCHAR(100) | NOT NULL | Name of the table affected |
| record_id | UUID | NOT NULL | ID of the affected record |
| action | VARCHAR(20) | NOT NULL | Action type (INSERT, UPDATE, DELETE) |
| old_values | JSONB | NULLABLE | Previous values (for UPDATE/DELETE) |
| new_values | JSONB | NULLABLE | New values (for INSERT/UPDATE) |
| changed_by | VARCHAR(255) | NOT NULL | User/system that made the change |
| changed_at | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() | When the change occurred |

**Indexes:**
- `PRIMARY KEY (id)`
- `INDEX idx_audit_logs_table_name (table_name)`
- `INDEX idx_audit_logs_record_id (record_id)`
- `INDEX idx_audit_logs_action (action)`
- `INDEX idx_audit_logs_changed_by (changed_by)`
- `INDEX idx_audit_logs_changed_at (changed_at)`
- `GIN INDEX idx_audit_logs_old_values (old_values)`
- `GIN INDEX idx_audit_logs_new_values (new_values)`

**Constraints:**
- `CHECK (action IN ('INSERT', 'UPDATE', 'DELETE'))` - Valid actions

**Example Data:**
```json
{
  "id": "aa0e8400-e29b-41d4-a716-446655440005",
  "table_name": "projects",
  "record_id": "550e8400-e29b-41d4-a716-446655440000",
  "action": "UPDATE",
  "old_values": {"title": "Pulse Market", "status": "Beta publik"},
  "new_values": {"title": "Pulse Market v2", "status": "Production"},
  "changed_by": "admin@sentosaku.com",
  "changed_at": "2026-04-04T10:00:00.000Z"
}
```

---

## ORM/Library Recommendation

### Recommended ORM: Prisma

**Justification:**

#### 1. Type Safety
- **Auto-generated Types:** Prisma Client generates TypeScript types from schema
- **Compile-time Validation:** Type errors caught at build time
- **IntelliSense:** Full autocomplete in VS Code

#### 2. Developer Experience
- **Declarative Schema:** Single source of truth for database schema
- **Auto Migrations:** `prisma migrate dev` handles schema changes
- **Studio UI:** Visual database browser and editor

#### 3. Performance
- **Connection Pooling:** Built-in connection pooling
- **Query Optimization:** Efficient query generation
- **Batch Operations:** Support for bulk inserts/updates

#### 4. Next.js Integration
- **Edge Runtime:** Works with Next.js Edge functions
- **Server Actions:** Native support for Next.js Server Actions
- **API Routes:** Seamless integration with Next.js API routes

#### 5. Ecosystem
- **Active Community:** Large, active community and documentation
- **Extensions:** Prisma Pulse for real-time subscriptions
- **Accelerate:** Global edge caching for queries

### Alternative ORMs Considered

| ORM | Pros | Cons | Verdict |
|-----|------|------|---------|
| **Prisma** | Type-safe, great DX, auto-migrations | Slight learning curve | ✅ **RECOMMENDED** |
| Drizzle | Lightweight, SQL-like, fast | Less mature, smaller community | Good alternative |
| TypeORM | Mature, feature-rich | Heavy, complex setup | Not recommended |
| Sequelize | Widely used | No TypeScript support out-of-box | Not recommended |

---

## Migration Strategy

### Phase 1: Preparation (Week 1)

#### 1.1 Environment Setup
- [ ] Set up PostgreSQL database (Neon/Supabase/Vercel Postgres)
- [ ] Configure database connection in `.env` file
- [ ] Install Prisma CLI and client
- [ ] Set up database connection pooling

#### 1.2 Prisma Initialization
```bash
npm install prisma @prisma/client
npx prisma init
```

#### 1.3 Environment Variables
```env
# .env
DATABASE_URL="postgresql://user:password@host:5432/sentosa_db?schema=public"
```

### Phase 2: Schema Development (Week 1-2)

#### 2.1 Create Prisma Schema
- [ ] Define all tables in `schema.prisma`
- [ ] Add indexes and constraints
- [ ] Define relationships (if any)
- [ ] Add enums for status fields

#### 2.2 Generate Prisma Client
```bash
npx prisma generate
```

#### 2.3 Create Initial Migration
```bash
npx prisma migrate dev --name init
```

### Phase 3: Data Migration (Week 2)

#### 3.1 Create Seed Script
- [ ] Create `prisma/seed.ts` file
- [ ] Migrate data from `src/lib/data.ts` to database
- [ ] Add UUID generation for all records
- [ ] Set display_order based on array index

#### 3.2 Execute Seed Script
```bash
npx prisma db seed
```

#### 3.3 Validate Data
- [ ] Verify all records migrated correctly
- [ ] Check relationships and foreign keys
- [ ] Validate JSONB arrays (technologies, features)
- [ ] Test data integrity

### Phase 4: API Integration (Week 2-3)

#### 4.1 Update API Routes
- [ ] Update `src/app/api/projects/route.ts`
- [ ] Update `src/app/api/testimonials/route.ts`
- [ ] Update `src/app/api/stats/route.ts`
- [ ] Update `src/app/api/clients/route.ts`
- [ ] Create `src/app/api/process-info/route.ts`

#### 4.2 Implement Database Queries
```typescript
// Example: projects route
import { PrismaClient } from '@prisma/client'
const prisma = new PrismaClient()

export async function GET() {
  const projects = await prisma.project.findMany({
    where: { is_active: true, deleted_at: null },
    orderBy: { display_order: 'asc' }
  })
  return NextResponse.json({ success: true, data: projects })
}
```

#### 4.3 Add Error Handling
- [ ] Implement try-catch blocks
- [ ] Add logging for errors
- [ ] Return appropriate error responses
- [ ] Handle database connection errors

### Phase 5: Testing & Validation (Week 3)

#### 5.1 Unit Testing
- [ ] Test all API endpoints
- [ ] Test database queries
- [ ] Test CRUD operations
- [ ] Test edge cases

#### 5.2 Integration Testing
- [ ] Test full data flow
- [ ] Test API responses
- [ ] Test caching behavior
- [ ] Test error scenarios

#### 5.3 Performance Testing
- [ ] Measure query response times
- [ ] Test with concurrent requests
- [ ] Verify caching effectiveness
- [ ] Check database connection pooling

### Phase 6: Deployment (Week 4)

#### 6.1 Production Database Setup
- [ ] Create production database
- [ ] Configure production environment variables
- [ ] Set up SSL connections
- [ ] Configure connection pooling

#### 6.2 Deploy Migrations
```bash
npx prisma migrate deploy
```

#### 6.3 Seed Production Data
```bash
npx prisma db seed
```

#### 6.4 Monitor Deployment
- [ ] Monitor database connections
- [ ] Check query performance
- [ ] Verify data integrity
- [ ] Set up alerts

### Phase 7: Cleanup (Week 4)

#### 7.1 Remove Hardcoded Data
- [ ] Remove `src/lib/data.ts` fallback data
- [ ] Remove `migrationConfig` from data.ts
- [ ] Update `src/lib/api.ts` to use database
- [ ] Clean up unused imports

#### 7.2 Update Documentation
- [ ] Update README with database setup instructions
- [ ] Document API endpoints
- [ ] Add database schema documentation
- [ ] Create troubleshooting guide

### Rollback Plan

If issues arise during migration:

1. **Immediate Rollback:**
   - Revert API routes to use fallback data
   - Disable database queries in `migrationConfig`

2. **Database Rollback:**
   ```bash
   npx prisma migrate resolve --rolled-back [migration-name]
   ```

3. **Data Restoration:**
   - Restore from database backup
   - Re-seed with original data

---

## Performance Considerations

### 1. Query Frequency Analysis

Based on landing page usage patterns:

| Query Type | Frequency | Optimization Priority |
|------------|-----------|----------------------|
| Get all active projects | Very High (every page load) | ⭐⭐⭐⭐⭐ |
| Get all active testimonials | Very High (every page load) | ⭐⭐⭐⭐⭐ |
| Get all active stats | Very High (every page load) | ⭐⭐⭐⭐⭐ |
| Get all active clients | Very High (every page load) | ⭐⭐⭐⭐⭐ |
| Get all active process info | Very High (every page load) | ⭐⭐⭐⭐⭐ |
| Single project by slug | Medium (project modal) | ⭐⭐⭐ |
| Update project | Low (admin only) | ⭐⭐ |
| Create new record | Low (admin only) | ⭐⭐ |

### 2. Caching Strategy

#### 2.1 Application-Level Caching (Next.js)

```typescript
// Use Next.js built-in caching
export const revalidate = 3600 // Revalidate every hour

// Or use incremental static regeneration
export const dynamic = 'force-static'
export const revalidate = 3600
```

#### 2.2 Database-Level Caching

- **Query Plan Caching:** PostgreSQL automatically caches query plans
- **Connection Pooling:** Reuse database connections
- **Read Replicas:** Offload read queries to replicas

#### 2.3 CDN Caching

- **Static Assets:** Cache images, logos, etc.
- **API Responses:** Cache with appropriate headers
- **Edge Caching:** Use Vercel Edge Network

### 3. Indexing Strategy

#### 3.1 Primary Indexes
- All tables have UUID primary keys
- UUIDs provide good distribution across indexes

#### 3.2 Secondary Indexes

**Projects Table:**
```sql
CREATE INDEX idx_projects_slug ON projects(slug);
CREATE INDEX idx_projects_category ON projects(category);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_display_order ON projects(display_order);
CREATE INDEX idx_projects_is_active ON projects(is_active);
CREATE INDEX idx_projects_created_at ON projects(created_at);
CREATE INDEX idx_projects_active_ordered ON projects(is_active, display_order);
```

**Testimonials Table:**
```sql
CREATE INDEX idx_testimonials_author ON testimonials(author);
CREATE INDEX idx_testimonials_rating ON testimonials(rating);
CREATE INDEX idx_testimonials_display_order ON testimonials(display_order);
CREATE INDEX idx_testimonials_is_active ON testimonials(is_active);
CREATE INDEX idx_testimonials_active_ordered ON testimonials(is_active, display_order);
```

**Stats, Clients, Process Info Tables:**
```sql
CREATE INDEX idx_[table]_display_order ON [table](display_order);
CREATE INDEX idx_[table]_is_active ON [table](is_active);
CREATE INDEX idx_[table]_active_ordered ON [table](is_active, display_order);
```

#### 3.3 GIN Indexes (for JSONB)

```sql
-- For searching within arrays
CREATE INDEX idx_projects_technologies ON projects USING GIN(technologies);
CREATE INDEX idx_projects_features ON projects USING GIN(features);
```

#### 3.4 Partial Indexes (for active records)

```sql
CREATE INDEX idx_projects_active ON projects(id, slug, title, display_order)
WHERE is_active = true AND deleted_at IS NULL;
```

### 4. Connection Pooling

#### 4.1 Prisma Connection Pooling

```typescript
// prisma/schema.prisma
datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
  // Connection pool settings
  directUrl = env("DIRECT_URL") // For migrations
}
```

#### 4.2 Connection Pool Configuration

```env
# .env
DATABASE_URL="postgresql://user:password@host:5432/sentosa_db?schema=public&connection_limit=10&pool_timeout=20"
```

**Recommended Settings:**
- **Connection Limit:** 10-20 (based on server size)
- **Pool Timeout:** 20 seconds
- **Statement Timeout:** 30 seconds (prevent long-running queries)

### 5. Query Optimization

#### 5.1 Selective Field Selection

```typescript
// Bad: Fetch all fields
const projects = await prisma.project.findMany()

// Good: Fetch only needed fields
const projects = await prisma.project.findMany({
  select: {
    id: true,
    slug: true,
    title: true,
    category: true,
    image: true,
    projectUrl: true,
  }
})
```

#### 5.2 Efficient Filtering

```typescript
// Good: Use composite index
const projects = await prisma.project.findMany({
  where: {
    is_active: true,
    deleted_at: null,
  },
  orderBy: {
    display_order: 'asc',
  }
})
```

#### 5.3 Batch Operations

```typescript
// For bulk inserts
await prisma.project.createMany({
  data: projectsArray,
  skipDuplicates: true,
})
```

### 6. Monitoring & Optimization

#### 6.1 Query Performance Monitoring

```typescript
// Enable query logging
const prisma = new PrismaClient({
  log: ['query', 'info', 'warn', 'error'],
})
```

#### 6.2 Slow Query Analysis

```sql
-- Find slow queries
SELECT query, mean_exec_time, calls
FROM pg_stat_statements
ORDER BY mean_exec_time DESC
LIMIT 10;
```

#### 6.3 Index Usage Analysis

```sql
-- Check index usage
SELECT schemaname, tablename, indexname, idx_scan
FROM pg_stat_user_indexes
ORDER BY idx_scan ASC;
```

---

## Security Considerations

### 1. SQL Injection Prevention

#### 1.1 Parameterized Queries (Prisma)

```typescript
// Prisma automatically uses parameterized queries
const project = await prisma.project.findUnique({
  where: { slug: userSlug } // Safe from SQL injection
})
```

#### 1.2 Input Validation

```typescript
// Validate input before querying
import { z } from 'zod'

const projectSchema = z.object({
  slug: z.string().min(3).max(255).regex(/^[a-z0-9-]+$/),
})

const validated = projectSchema.parse(userInput)
const project = await prisma.project.findUnique({
  where: { slug: validated.slug }
})
```

### 2. Data Validation

#### 2.1 Database-Level Validation

```sql
-- Check constraints
ALTER TABLE projects
ADD CONSTRAINT chk_project_slug_length
CHECK (char_length(slug) >= 3 AND char_length(slug) <= 255);

ALTER TABLE projects
ADD CONSTRAINT chk_project_slug_format
CHECK (slug ~ '^[a-z0-9-]+$');
```

#### 2.2 Application-Level Validation

```typescript
// Use Zod for runtime validation
import { z } from 'zod'

const createProjectSchema = z.object({
  title: z.string().min(2).max(255),
  slug: z.string().min(3).max(255).regex(/^[a-z0-9-]+$/),
  category: z.string().min(1).max(100),
  description: z.string().min(10).max(5000),
  status: z.enum(['Draft', 'Beta publik', 'Production', 'Archived']),
  technologies: z.array(z.string()).min(1),
  features: z.array(z.string()).min(1),
})
```

### 3. Access Control

#### 3.1 Read-Only Public Access

```typescript
// Public API routes (read-only)
export async function GET() {
  const projects = await prisma.project.findMany({
    where: { is_active: true, deleted_at: null },
    select: { /* public fields only */ }
  })
  return NextResponse.json({ success: true, data: projects })
}
```

#### 3.2 Admin-Only Write Access

```typescript
// Admin API routes (write access)
import { verifyAdminToken } from '@/lib/auth'

export async function POST(request: Request) {
  // Verify admin access
  const admin = await verifyAdminToken(request)
  if (!admin) {
    return NextResponse.json(
      { success: false, error: 'Unauthorized' },
      { status: 401 }
    )
  }

  // Create project
  const data = await request.json()
  const project = await prisma.project.create({ data })
  return NextResponse.json({ success: true, data: project })
}
```

#### 3.3 Row-Level Security (PostgreSQL)

```sql
-- Enable RLS
ALTER TABLE projects ENABLE ROW LEVEL SECURITY;

-- Public can only read active records
CREATE POLICY projects_public_read ON projects
FOR SELECT
USING (is_active = true AND deleted_at IS NULL);

-- Admins can do everything
CREATE POLICY projects_admin_all ON projects
FOR ALL
USING (pg_has_role('admin_role', 'MEMBER'));
```

### 4. Sensitive Data Handling

#### 4.1 Environment Variables

```env
# .env (never commit to version control)
DATABASE_URL="postgresql://user:password@host:5432/sentosa_db"
JWT_SECRET="your-secret-key"
ADMIN_API_KEY="your-admin-key"
```

#### 4.2 Connection Security

```typescript
// Use SSL in production
const prisma = new PrismaClient({
  datasources: {
    db: {
      url: process.env.DATABASE_URL + '?sslmode=require'
    }
  }
})
```

#### 4.3 Audit Logging

```typescript
// Log all write operations
export async function createProject(data: any) {
  const project = await prisma.project.create({ data })
  
  // Log to audit table
  await prisma.auditLog.create({
    data: {
      table_name: 'projects',
      record_id: project.id,
      action: 'INSERT',
      new_values: project,
      changed_by: 'admin@sentosaku.com',
    }
  })
  
  return project
}
```

### 5. Rate Limiting

```typescript
// Implement rate limiting for API routes
import { Ratelimit } from '@upstash/ratelimit'
import { Redis } from '@upstash/redis'

const ratelimit = new Ratelimit({
  redis: Redis.fromEnv(),
  limiter: Ratelimit.slidingWindow(10, '10 s'),
})

export async function GET(request: Request) {
  const ip = request.headers.get('x-forwarded-for') ?? 'anonymous'
  const { success } = await ratelimit.limit(ip)
  
  if (!success) {
    return NextResponse.json(
      { success: false, error: 'Too many requests' },
      { status: 429 }
    )
  }
  
  // ... rest of the handler
}
```

### 6. Data Encryption

#### 6.1 At Rest Encryption

- Use PostgreSQL's built-in encryption
- Enable SSL/TLS for connections
- Encrypt backups

#### 6.2 In Transit Encryption

```env
# Force SSL connections
DATABASE_URL="postgresql://user:password@host:5432/sentosa_db?sslmode=require"
```

---

## Future Scalability

### 1. Extensible Schema Design

#### 1.1 Multi-Language Support (i18n)

**Option A: Separate Translation Tables**

```sql
CREATE TABLE project_translations (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  project_id UUID NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
  language VARCHAR(10) NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  UNIQUE(project_id, language)
);

CREATE INDEX idx_project_translations_project_id ON project_translations(project_id);
CREATE INDEX idx_project_translations_language ON project_translations(language);
```

**Option B: JSONB Translations**

```sql
ALTER TABLE projects ADD COLUMN translations JSONB DEFAULT '{}';

-- Example value:
{
  "en": { "title": "Pulse Market", "description": "..." },
  "id": { "title": "Pulse Market", "description": "..." }
}
```

**Recommendation:** Option B for simplicity, Option A for scalability

#### 1.2 Blog/Articles Support

```sql
CREATE TABLE blog_posts (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  slug VARCHAR(255) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  excerpt TEXT,
  content TEXT NOT NULL,
  featured_image VARCHAR(500),
  author_id UUID REFERENCES users(id),
  category_id UUID REFERENCES blog_categories(id),
  tags JSONB DEFAULT '[]'::jsonb,
  status VARCHAR(50) NOT NULL DEFAULT 'Draft',
  published_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);

CREATE INDEX idx_blog_posts_slug ON blog_posts(slug);
CREATE INDEX idx_blog_posts_status ON blog_posts(status);
CREATE INDEX idx_blog_posts_published_at ON blog_posts(published_at);
CREATE INDEX idx_blog_posts_tags ON blog_posts USING GIN(tags);
```

#### 1.3 Case Studies Support

```sql
CREATE TABLE case_studies (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  slug VARCHAR(255) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  client_id UUID REFERENCES clients(id),
  project_id UUID REFERENCES projects(id),
  challenge TEXT NOT NULL,
  solution TEXT NOT NULL,
  results TEXT NOT NULL,
  metrics JSONB DEFAULT '{}'::jsonb,
  images JSONB DEFAULT '[]'::jsonb,
  status VARCHAR(50) NOT NULL DEFAULT 'Draft',
  published_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

#### 1.4 Team Members Support

```sql
CREATE TABLE team_members (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  slug VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL,
  bio TEXT,
  avatar VARCHAR(500),
  linkedin_url VARCHAR(500),
  github_url VARCHAR(500),
  twitter_url VARCHAR(500),
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

#### 1.5 Services/Pricing Support

```sql
CREATE TABLE services (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  slug VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  icon VARCHAR(100),
  features JSONB DEFAULT '[]'::jsonb,
  price_range VARCHAR(100),
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

### 2. Performance Scaling

#### 2.1 Read Replicas

```typescript
// Configure read replica
const prisma = new PrismaClient({
  datasources: {
    db: {
      url: process.env.DATABASE_URL,
    },
    db_replica: {
      url: process.env.DATABASE_REPLICA_URL,
    },
  },
})

// Use replica for read operations
const projects = await prisma.$queryRaw`SELECT * FROM projects WHERE is_active = true`
```

#### 2.2 Database Sharding

For very large datasets, consider:
- Horizontal sharding by project category
- Time-based sharding for audit logs
- Geographic sharding for multi-region deployment

#### 2.3 Caching Layers

```typescript
// Redis caching
import { Redis } from '@upstash/redis'
const redis = Redis.fromEnv()

export async function getProjects() {
  const cacheKey = 'projects:active'
  const cached = await redis.get(cacheKey)
  
  if (cached) {
    return JSON.parse(cached as string)
  }
  
  const projects = await prisma.project.findMany({
    where: { is_active: true, deleted_at: null },
    orderBy: { display_order: 'asc' }
  })
  
  await redis.setex(cacheKey, 3600, JSON.stringify(projects))
  return projects
}
```

### 3. Advanced Features

#### 3.1 Full-Text Search

```sql
-- Add full-text search
ALTER TABLE projects ADD COLUMN search_vector tsvector;

CREATE INDEX idx_projects_search ON projects USING GIN(search_vector);

-- Update search vector trigger
CREATE OR REPLACE FUNCTION projects_search_vector_update() RETURNS trigger AS $$
BEGIN
  NEW.search_vector :=
    setweight(to_tsvector('english', coalesce(NEW.title, '')), 'A') ||
    setweight(to_tsvector('english', coalesce(NEW.description, '')), 'B') ||
    setweight(to_tsvector('english', coalesce(NEW.category, '')), 'C');
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER projects_search_vector_trigger
BEFORE INSERT OR UPDATE ON projects
FOR EACH ROW EXECUTE FUNCTION projects_search_vector_update();
```

```typescript
// Search query
const results = await prisma.$queryRaw`
  SELECT * FROM projects
  WHERE search_vector @@ plainto_tsquery(${searchTerm})
  AND is_active = true
  ORDER BY ts_rank(search_vector, plainto_tsquery(${searchTerm})) DESC
`
```

#### 3.2 Analytics & Tracking

```sql
CREATE TABLE page_views (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  path VARCHAR(500) NOT NULL,
  referrer VARCHAR(500),
  user_agent TEXT,
  ip_address VARCHAR(45),
  viewed_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_page_views_path ON page_views(path);
CREATE INDEX idx_page_views_viewed_at ON page_views(viewed_at);
```

#### 3.3 A/B Testing

```sql
CREATE TABLE experiments (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name VARCHAR(255) NOT NULL,
  description TEXT,
  start_date TIMESTAMPTZ NOT NULL,
  end_date TIMESTAMPTZ,
  status VARCHAR(50) NOT NULL DEFAULT 'Draft',
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE experiment_variants (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  experiment_id UUID NOT NULL REFERENCES experiments(id) ON DELETE CASCADE,
  name VARCHAR(255) NOT NULL,
  traffic_percentage INTEGER NOT NULL,
  config JSONB NOT NULL,
  UNIQUE(experiment_id, name)
);
```

### 4. Migration Path

#### 4.1 Incremental Schema Evolution

```typescript
// Prisma handles schema evolution automatically
// Just update schema.prisma and run migration

// Example: Add new field
model Project {
  // ... existing fields
  githubUrl    String?
  demoVideoUrl String?
}
```

```bash
npx prisma migrate dev --name add_github_and_demo_urls
```

#### 4.2 Data Migration Scripts

```typescript
// prisma/migrations/xxxxx_add_github_and_demo_urls/migration.ts
import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  // Migrate existing data
  const projects = await prisma.project.findMany()
  
  for (const project of projects) {
    // Extract GitHub URL from project_url if present
    const githubUrl = extractGitHubUrl(project.project_url)
    
    if (githubUrl) {
      await prisma.project.update({
        where: { id: project.id },
        data: { githubUrl }
      })
    }
  }
}

main()
```

---

## SQL DDL Statements

### Complete Database Schema

```sql
-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- ============================================
-- PROJECTS TABLE
-- ============================================
CREATE TABLE projects (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  slug VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  status VARCHAR(50) NOT NULL,
  image VARCHAR(500) NOT NULL,
  project_url VARCHAR(500) NOT NULL,
  technologies JSONB NOT NULL DEFAULT '[]'::jsonb,
  features JSONB NOT NULL DEFAULT '[]'::jsonb,
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ,
  
  CONSTRAINT chk_project_slug_length CHECK (char_length(slug) >= 3 AND char_length(slug) <= 255),
  CONSTRAINT chk_project_slug_format CHECK (slug ~ '^[a-z0-9-]+$'),
  CONSTRAINT chk_project_title_length CHECK (char_length(title) >= 2),
  CONSTRAINT chk_project_technologies_type CHECK (jsonb_typeof(technologies) = 'array'),
  CONSTRAINT chk_project_features_type CHECK (jsonb_typeof(features) = 'array'),
  CONSTRAINT uq_projects_slug UNIQUE (slug)
);

-- Indexes for projects
CREATE INDEX idx_projects_slug ON projects(slug);
CREATE INDEX idx_projects_category ON projects(category);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_display_order ON projects(display_order);
CREATE INDEX idx_projects_is_active ON projects(is_active);
CREATE INDEX idx_projects_created_at ON projects(created_at);
CREATE INDEX idx_projects_active_ordered ON projects(is_active, display_order) WHERE deleted_at IS NULL;
CREATE INDEX idx_projects_technologies ON projects USING GIN(technologies);
CREATE INDEX idx_projects_features ON projects USING GIN(features);

-- ============================================
-- TESTIMONIALS TABLE
-- ============================================
CREATE TABLE testimonials (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  text TEXT NOT NULL,
  author VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  initials VARCHAR(10) NOT NULL,
  rating INTEGER NOT NULL DEFAULT 5,
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ,
  
  CONSTRAINT chk_testimonial_author_length CHECK (char_length(author) >= 2),
  CONSTRAINT chk_testimonial_initials_length CHECK (char_length(initials) >= 2 AND char_length(initials) <= 10),
  CONSTRAINT chk_testimonial_rating_range CHECK (rating >= 1 AND rating <= 5)
);

-- Indexes for testimonials
CREATE INDEX idx_testimonials_author ON testimonials(author);
CREATE INDEX idx_testimonials_rating ON testimonials(rating);
CREATE INDEX idx_testimonials_display_order ON testimonials(display_order);
CREATE INDEX idx_testimonials_is_active ON testimonials(is_active);
CREATE INDEX idx_testimonials_created_at ON testimonials(created_at);
CREATE INDEX idx_testimonials_active_ordered ON testimonials(is_active, display_order) WHERE deleted_at IS NULL;

-- ============================================
-- STATS TABLE
-- ============================================
CREATE TABLE stats (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  value VARCHAR(100) NOT NULL,
  label VARCHAR(255) NOT NULL,
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ,
  
  CONSTRAINT chk_stat_value_length CHECK (char_length(value) >= 1),
  CONSTRAINT chk_stat_label_length CHECK (char_length(label) >= 2)
);

-- Indexes for stats
CREATE INDEX idx_stats_label ON stats(label);
CREATE INDEX idx_stats_display_order ON stats(display_order);
CREATE INDEX idx_stats_is_active ON stats(is_active);
CREATE INDEX idx_stats_created_at ON stats(created_at);
CREATE INDEX idx_stats_active_ordered ON stats(is_active, display_order) WHERE deleted_at IS NULL;

-- ============================================
-- CLIENTS TABLE
-- ============================================
CREATE TABLE clients (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name VARCHAR(255) NOT NULL,
  initial VARCHAR(10) NOT NULL,
  logo_url VARCHAR(500),
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ,
  
  CONSTRAINT chk_client_name_length CHECK (char_length(name) >= 2),
  CONSTRAINT chk_client_initial_length CHECK (char_length(initial) >= 2 AND char_length(initial) <= 10),
  CONSTRAINT uq_clients_name UNIQUE (name)
);

-- Indexes for clients
CREATE INDEX idx_clients_name ON clients(name);
CREATE INDEX idx_clients_initial ON clients(initial);
CREATE INDEX idx_clients_display_order ON clients(display_order);
CREATE INDEX idx_clients_is_active ON clients(is_active);
CREATE INDEX idx_clients_created_at ON clients(created_at);
CREATE INDEX idx_clients_active_ordered ON clients(is_active, display_order) WHERE deleted_at IS NULL;

-- ============================================
-- PROCESS_INFO TABLE
-- ============================================
CREATE TABLE process_info (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  label VARCHAR(255) NOT NULL,
  value NUMERIC(10,2) NOT NULL,
  unit VARCHAR(50) NOT NULL,
  display_order INTEGER NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  deleted_at TIMESTAMPTZ,
  
  CONSTRAINT chk_process_info_label_length CHECK (char_length(label) >= 2),
  CONSTRAINT chk_process_info_value_positive CHECK (value >= 0),
  CONSTRAINT chk_process_info_unit_length CHECK (char_length(unit) >= 1)
);

-- Indexes for process_info
CREATE INDEX idx_process_info_label ON process_info(label);
CREATE INDEX idx_process_info_display_order ON process_info(display_order);
CREATE INDEX idx_process_info_is_active ON process_info(is_active);
CREATE INDEX idx_process_info_created_at ON process_info(created_at);
CREATE INDEX idx_process_info_active_ordered ON process_info(is_active, display_order) WHERE deleted_at IS NULL;

-- ============================================
-- AUDIT_LOGS TABLE
-- ============================================
CREATE TABLE audit_logs (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  table_name VARCHAR(100) NOT NULL,
  record_id UUID NOT NULL,
  action VARCHAR(20) NOT NULL,
  old_values JSONB,
  new_values JSONB,
  changed_by VARCHAR(255) NOT NULL,
  changed_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  
  CONSTRAINT chk_audit_action_valid CHECK (action IN ('INSERT', 'UPDATE', 'DELETE'))
);

-- Indexes for audit_logs
CREATE INDEX idx_audit_logs_table_name ON audit_logs(table_name);
CREATE INDEX idx_audit_logs_record_id ON audit_logs(record_id);
CREATE INDEX idx_audit_logs_action ON audit_logs(action);
CREATE INDEX idx_audit_logs_changed_by ON audit_logs(changed_by);
CREATE INDEX idx_audit_logs_changed_at ON audit_logs(changed_at);
CREATE INDEX idx_audit_logs_old_values ON audit_logs USING GIN(old_values);
CREATE INDEX idx_audit_logs_new_values ON audit_logs USING GIN(new_values);

-- ============================================
-- TRIGGER FOR UPDATED_AT
-- ============================================
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Apply trigger to all tables with updated_at
CREATE TRIGGER update_projects_updated_at BEFORE UPDATE ON projects
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_testimonials_updated_at BEFORE UPDATE ON testimonials
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_stats_updated_at BEFORE UPDATE ON stats
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_clients_updated_at BEFORE UPDATE ON clients
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_process_info_updated_at BEFORE UPDATE ON process_info
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- ============================================
-- TRIGGER FOR AUDIT LOGGING
-- ============================================
CREATE OR REPLACE FUNCTION audit_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO audit_logs (table_name, record_id, action, new_values, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'INSERT', to_jsonb(NEW), current_user);
        RETURN NEW;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO audit_logs (table_name, record_id, action, old_values, new_values, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW), current_user);
        RETURN NEW;
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO audit_logs (table_name, record_id, action, old_values, changed_by)
        VALUES (TG_TABLE_NAME, OLD.id, 'DELETE', to_jsonb(OLD), current_user);
        RETURN OLD;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Apply audit trigger to all tables
CREATE TRIGGER audit_projects_trigger AFTER INSERT OR UPDATE OR DELETE ON projects
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

CREATE TRIGGER audit_testimonials_trigger AFTER INSERT OR UPDATE OR DELETE ON testimonials
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

CREATE TRIGGER audit_stats_trigger AFTER INSERT OR UPDATE OR DELETE ON stats
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

CREATE TRIGGER audit_clients_trigger AFTER INSERT OR UPDATE OR DELETE ON clients
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

CREATE TRIGGER audit_process_info_trigger AFTER INSERT OR UPDATE OR DELETE ON process_info
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

-- ============================================
-- SAMPLE DATA INSERTION
-- ============================================

-- Insert Projects
INSERT INTO projects (slug, title, category, description, status, image, project_url, technologies, features, display_order, is_active) VALUES
('pulse-market', 'Pulse Market', 'Platform Web Fintech', 'Marketplace investasi responsif dengan pembayaran multi-dompet dan dasbor investor yang akurat.', 'Beta publik', '/api/placeholder/400/250', 'https://pulse-market.example.com', '["React", "Node.js", "MongoDB", "Stripe API"]'::jsonb, '["Multi-wallet payments", "Real-time analytics", "Investor dashboard"]'::jsonb, 1, true),
('nexa-courier', 'Nexa Courier', 'Suite Mobile Logistik', 'Aplikasi kurir dan dispatcher dilengkapi pelacakan armada live serta intelijen rute otomatis.', 'Siap Play Store', '/api/placeholder/400/250', 'https://nexa-courier.example.com', '["React Native", "Firebase", "Google Maps API"]'::jsonb, '["Live fleet tracking", "Route optimization", "Real-time dispatch"]'::jsonb, 2, true),
('lumacare', 'LumaCare', 'SaaS Kesehatan', 'Penjadwalan klinik, telekonsultasi, dan portal pasien sesuai standar keamanan kesehatan.', 'Rollout Series B', '/api/placeholder/400/250', 'https://lumacare.example.com', '["Vue.js", "Python Django", "PostgreSQL", "WebRTC"]'::jsonb, '["Appointment scheduling", "Teleconsultation", "Patient portal"]'::jsonb, 3, true),
('orbit-travel', 'Orbit Travel', 'Progressive Web App', 'Pengalaman booking offline-first dengan modul loyalti untuk brand perjalanan modern.', 'v2 berjalan', '/api/placeholder/400/250', 'https://orbit-travel.example.com', '["PWA", "Service Workers", "IndexedDB"]'::jsonb, '["Offline booking", "Loyalty program", "Modern travel experience"]'::jsonb, 4, true),
('studiolink', 'StudioLink', 'Alat Kreator', 'Workspace lintas perangkat agar kreator bisa briefing, review, dan rilis konten lebih cepat.', 'Review App Store', '/api/placeholder/400/250', 'https://studiolink.example.com', '["Next.js", "WebRTC", "Cloudinary", "Figma API"]'::jsonb, '["Cross-device workspace", "Content review", "Quick publishing"]'::jsonb, 5, true),
('flux-analytics', 'Flux Analytics', 'Dasbor Data', 'BI tertanam bagi tim sales & operasional dengan kontrol akses granular dan notifikasi instan.', 'Pilot enterprise', '/api/placeholder/400/250', 'https://flux-analytics.example.com', '["React", "D3.js", "Python", "Apache Spark"]'::jsonb, '["Embedded BI", "Granular access control", "Instant notifications"]'::jsonb, 6, true);

-- Insert Testimonials
INSERT INTO testimonials (text, author, title, initials, rating, display_order, is_active) VALUES
('Tim Sentosaku sangat profesional dalam mengembangkan aplikasi mobile kami. Proses yang transparan dan hasil yang melebihi ekspektasi.', 'John Doe', 'CEO, TechCorp Indonesia', 'JD', 5, 1, true),
('Platform web yang dikembangkan Sentosaku meningkatkan efisiensi operasional kami hingga 40%. Highly recommended!', 'Sarah Rahman', 'CTO, FinanceHub Asia', 'SR', 5, 2, true),
('Kolaborasi yang sangat menyenangkan. Tim yang responsif dan solutif, selalu siap membantu kami mencapai target digital.', 'Michael Pratama', 'Founder, HealthPlus', 'MP', 5, 3, true);

-- Insert Stats
INSERT INTO stats (value, label, display_order, is_active) VALUES
('32', 'Produk digital sukses', 1, true),
('18', 'Rilis mobile per kuartal', 2, true),
('3.5x', 'Iterasi lebih cepat', 3, true);

-- Insert Clients
INSERT INTO clients (name, initial, logo_url, display_order, is_active) VALUES
('TechCorp', 'TC', NULL, 1, true),
('FinanceHub', 'FH', NULL, 2, true),
('HealthPlus', 'HP', NULL, 3, true),
('EduSmart', 'ES', NULL, 4, true),
('LogisticsPro', 'LP', NULL, 5, true),
('RetailMax', 'RM', NULL, 6, true),
('DataFlow', 'DF', NULL, 7, true),
('CloudBase', 'CB', NULL, 8, true);

-- Insert Process Info
INSERT INTO process_info (label, value, unit, display_order, is_active) VALUES
('Proyek Selesai', 150, 'produk', 1, true),
('Kepuasan Klien', 98, '%', 2, true),
('Waktu Rilis', 7, 'hari', 3, true);

-- ============================================
-- VIEWS FOR COMMON QUERIES
-- ============================================

-- View for active projects
CREATE VIEW active_projects AS
SELECT id, slug, title, category, description, status, image, project_url, technologies, features, display_order, created_at, updated_at
FROM projects
WHERE is_active = true AND deleted_at IS NULL
ORDER BY display_order ASC;

-- View for active testimonials
CREATE VIEW active_testimonials AS
SELECT id, text, author, title, initials, rating, display_order, created_at, updated_at
FROM testimonials
WHERE is_active = true AND deleted_at IS NULL
ORDER BY display_order ASC;

-- View for active stats
CREATE VIEW active_stats AS
SELECT id, value, label, display_order, created_at, updated_at
FROM stats
WHERE is_active = true AND deleted_at IS NULL
ORDER BY display_order ASC;

-- View for active clients
CREATE VIEW active_clients AS
SELECT id, name, initial, logo_url, display_order, created_at, updated_at
FROM clients
WHERE is_active = true AND deleted_at IS NULL
ORDER BY display_order ASC;

-- View for active process info
CREATE VIEW active_process_info AS
SELECT id, label, value, unit, display_order, created_at, updated_at
FROM process_info
WHERE is_active = true AND deleted_at IS NULL
ORDER BY display_order ASC;

-- ============================================
-- GRANT PERMISSIONS
-- ============================================

-- Grant read access to application user
GRANT SELECT ON ALL TABLES IN SCHEMA public TO sentosa_app;

-- Grant write access to admin user
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO sentosa_admin;

-- Grant usage on sequences
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO sentosa_app, sentosa_admin;
```

---

## Prisma Schema Example

```prisma
// prisma/schema.prisma

generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
}

// ============================================
// MODELS
// ============================================

model Project {
  id           UUID    @id @default(uuid()) @db.Uuid
  slug         String  @unique @db.VarChar(255)
  title        String  @db.VarChar(255)
  category     String  @db.VarChar(100)
  description  String  @db.Text
  status       String  @db.VarChar(50)
  image        String  @db.VarChar(500)
  projectUrl   String  @map("project_url") @db.VarChar(500)
  technologies Json    @default("[]") @db.JsonB
  features     Json    @default("[]") @db.JsonB
  displayOrder Int     @default(0) @map("display_order")
  isActive     Boolean @default(true) @map("is_active")
  createdAt    DateTime @default(now()) @map("created_at") @db.Timestamptz(6)
  updatedAt    DateTime @default(now()) @updatedAt @map("updated_at") @db.Timestamptz(6)
  deletedAt    DateTime? @map("deleted_at") @db.Timestamptz(6)

  @@index([slug])
  @@index([category])
  @@index([status])
  @@index([displayOrder])
  @@index([isActive])
  @@index([createdAt])
  @@index([isActive, displayOrder])
  @@map("projects")
}

model Testimonial {
  id           UUID    @id @default(uuid()) @db.Uuid
  text         String  @db.Text
  author       String  @db.VarChar(255)
  title        String  @db.VarChar(255)
  initials     String  @db.VarChar(10)
  rating       Int     @default(5)
  displayOrder Int     @default(0) @map("display_order")
  isActive     Boolean @default(true) @map("is_active")
  createdAt    DateTime @default(now()) @map("created_at") @db.Timestamptz(6)
  updatedAt    DateTime @default(now()) @updatedAt @map("updated_at") @db.Timestamptz(6)
  deletedAt    DateTime? @map("deleted_at") @db.Timestamptz(6)

  @@index([author])
  @@index([rating])
  @@index([displayOrder])
  @@index([isActive])
  @@index([createdAt])
  @@index([isActive, displayOrder])
  @@map("testimonials")
}

model Stat {
  id           UUID    @id @default(uuid()) @db.Uuid
  value        String  @db.VarChar(100)
  label        String  @db.VarChar(255)
  displayOrder Int     @default(0) @map("display_order")
  isActive     Boolean @default(true) @map("is_active")
  createdAt    DateTime @default(now()) @map("created_at") @db.Timestamptz(6)
  updatedAt    DateTime @default(now()) @updatedAt @map("updated_at") @db.Timestamptz(6)
  deletedAt    DateTime? @map("deleted_at") @db.Timestamptz(6)

  @@index([label])
  @@index([displayOrder])
  @@index([isActive])
  @@index([createdAt])
  @@index([isActive, displayOrder])
  @@map("stats")
}

model Client {
  id           UUID    @id @default(uuid()) @db.Uuid
  name         String  @unique @db.VarChar(255)
  initial      String  @db.VarChar(10)
  logoUrl      String? @map("logo_url") @db.VarChar(500)
  displayOrder Int     @default(0) @map("display_order")
  isActive     Boolean @default(true) @map("is_active")
  createdAt    DateTime @default(now()) @map("created_at") @db.Timestamptz(6)
  updatedAt    DateTime @default(now()) @updatedAt @map("updated_at") @db.Timestamptz(6)
  deletedAt    DateTime? @map("deleted_at") @db.Timestamptz(6)

  @@index([name])
  @@index([initial])
  @@index([displayOrder])
  @@index([isActive])
  @@index([createdAt])
  @@index([isActive, displayOrder])
  @@map("clients")
}

model ProcessInfo {
  id           UUID    @id @default(uuid()) @db.Uuid
  label        String  @db.VarChar(255)
  value        Decimal @db.Decimal(10, 2)
  unit         String  @db.VarChar(50)
  displayOrder Int     @default(0) @map("display_order")
  isActive     Boolean @default(true) @map("is_active")
  createdAt    DateTime @default(now()) @map("created_at") @db.Timestamptz(6)
  updatedAt    DateTime @default(now()) @updatedAt @map("updated_at") @db.Timestamptz(6)
  deletedAt    DateTime? @map("deleted_at") @db.Timestamptz(6)

  @@index([label])
  @@index([displayOrder])
  @@index([isActive])
  @@index([createdAt])
  @@index([isActive, displayOrder])
  @@map("process_info")
}

model AuditLog {
  id         UUID    @id @default(uuid()) @db.Uuid
  tableName  String  @map("table_name") @db.VarChar(100)
  recordId   UUID    @map("record_id") @db.Uuid
  action     String  @db.VarChar(20)
  oldValues  Json?   @map("old_values") @db.JsonB
  newValues  Json?   @map("new_values") @db.JsonB
  changedBy  String  @map("changed_by") @db.VarChar(255)
  changedAt  DateTime @default(now()) @map("changed_at") @db.Timestamptz(6)

  @@index([tableName])
  @@index([recordId])
  @@index([action])
  @@index([changedBy])
  @@index([changedAt])
  @@map("audit_logs")
}
```

---

## Conclusion

This comprehensive database schema design provides a robust foundation for the Sentosaku landing page project. The design prioritizes:

- **Performance:** Optimized for read-heavy operations with strategic indexing
- **Scalability:** Extensible schema supporting future features
- **Security:** Multi-layered security with audit logging
- **Maintainability:** Clear structure with comprehensive documentation
- **Developer Experience:** Type-safe queries with Prisma ORM

The recommended PostgreSQL + Prisma stack offers excellent integration with Next.js, strong community support, and proven scalability for production workloads.

**Next Steps:**
1. Review and approve this schema design
2. Set up PostgreSQL database (Neon/Supabase/Vercel Postgres)
3. Initialize Prisma and create initial migration
4. Implement data migration from hardcoded files
5. Update API routes to use database
6. Deploy and monitor performance

---

**Document Version:** 1.0  
**Last Updated:** 2026-04-04  
**Status:** Ready for Implementation
