# Copilot Instructions - Price Tracker

## Project Overview

**Price Tracker** es una aplicación web que scrapea componentes de diferentes sitios (PCComponentes, libros en books.toscrape.com) y almacena los datos para análisis de precios.

- **Backend**: Symfony 7.4 (PHP 8.2+) con Doctrine ORM
- **Frontend**: Vue 3 con Vite y Vue Router
- **Database**: MySQL 8.0
- **Containers**: Docker Compose
- **Scraping**: Servicio Node.js con Puppeteer para sitios con JavaScript renderizado

## Architecture Overview

```
price-tracker/
├── backend/                    # Symfony application
│   ├── src/
│   │   ├── Command/           # CLI commands para scraping
│   │   ├── Controller/        # API endpoints (REST)
│   │   ├── Entity/            # Doctrine entities (Product, Book, PCComponent, etc)
│   │   ├── Repository/        # Database queries
│   │   └── Service/           # Scraper services (ProductScraper, etc)
│   ├── config/                # Symfony config (bundles, services, routes)
│   ├── migrations/            # Doctrine migrations
│   └── public/index.php        # Entry point
├── frontend/                   # Vue 3 SPA
│   ├── src/
│   │   ├── App.vue
│   │   ├── components/        # Vue components
│   │   ├── router/            # Vue Router configuration
│   │   └── services/          # API clients (axios)
│   └── vite.config.js
├── scraper-service/           # Node.js microservice
│   ├── index.js               # Express server con Puppeteer
│   └── Dockerfile
└── docker-compose.yml         # Orchestration
```

## Core Patterns

### 1. **Scraper Pattern** (Product Scraper Example)

Cada scraper sigue este flujo:

```
Entity (MongoDB like structure in MySQL)
    ↓
Repository (Query data)
    ↓
Service (Business logic - scraping)
    ↓
Command (CLI entry point)
```

**Componentes principales:**

1. **Entity** (`src/Entity/Product.php`): Define estructura de datos
   - Attributes de Doctrine ORM: `#[ORM\Entity]`, `#[ORM\Column]`
   - Getters/Setters para propiedades
   - Ejemplo: `createAt: DateTimeImmutable`, `price: float`, `name: string`

2. **Repository** (`src/Repository/ProductRepository.php`): Queries a la BD
   - Extiende `ServiceEntityRepository`
   - Métodos custom como `findByUrl()` para lógica específica
   - Auto-wired en servicios

3. **Service** (`src/Service/ProductScraper.php`): Lógica del scraping
   - Inyecta `HttpClientInterface` para requests HTTP
   - Inyecta `EntityManagerInterface` para persistencia
   - Usa `DOMDocument` + `XPath` para parsing HTML
   - Patrón: `scrape()` → `scrapeDetail()` → `persist()`
   - **Nota**: Para sitios con JS renderizado, usa microservicio Node.js

4. **Command** (`src/Command/ScrapeProductsCommand.php`): CLI
   - `#[AsCommand(name: '...', description: '...')]` attribute
   - Inyecta el Service en constructor
   - Retorna `Command::SUCCESS` o `Command::FAILURE`
   - Ejecutar: `php bin/console app:scrape-products`

### 2. **Nuevos Scrapers** (PCComponentes Pattern)

Para crear un nuevo scraper:

```php
// 1. Crear Entity → src/Entity/NewComponent.php
#[ORM\Entity(repositoryClass: NewComponentRepository::class)]
class NewComponent { /* properties + getters/setters */ }

// 2. Crear Repository → src/Repository/NewComponentRepository.php
class NewComponentRepository extends ServiceEntityRepository { }

// 3. Crear Service → src/Service/NewComponentScraper.php
class NewComponentScraper {
    public function scrape(string $url, string $category): void { }
}

// 4. Crear Command → src/Command/ScrapeNewComponentsCommand.php
#[AsCommand(name: 'app:scrape-newcomponents')]
class ScrapeNewComponentsCommand extends Command { }

// 5. Crear Migration → php bin/console make:migration
// Ejecutar: php bin/console doctrine:migrations:migrate
```

### 3. **API Controllers Pattern**

```php
#[Route('/api/products', methods: ['GET'])]
public function listProducts(EntityManagerInterface $em): JsonResponse {
    $products = $em->getRepository(Product::class)->findAll();
    return $this->json($data);
}
```

- Endpoints bajo `/api/` para distinguir de vistas
- Retornar `JsonResponse` con structured data
- CORS habilitado en `config/packages/nelmio_cors.yaml`

### 4. **Frontend (Vue 3) Pattern**

```javascript
// components/*.vue - Component-based architecture
// router/index.js - Define rutas
// services/*.js - Axios clients para API

// Llamar API:
import axios from 'axios';
const response = await axios.get('http://localhost:8000/api/products');
```

## Development Workflows

### Setup & Run

```bash
# Inicial
docker-compose up -d
cd backend && php bin/console doctrine:migrations:migrate

# Frontend dev mode: auto-reload en http://localhost:5173
# Backend dev mode: http://localhost:8000
# Database admin: http://localhost:8080 (Adminer)
```

### Database Workflows

```bash
# Ver/editar BD
# Acceder a: http://localhost:8080 (Adminer)
# User: user | Password: password | DB: tracker

# Crear entidad (genera Entity + Repository)
php bin/console make:entity

# Crear migración desde cambios en Entity
php bin/console make:migration

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate

# Rollback
php bin/console doctrine:migrations:migrate prev
```

### Scraping Workflows

```bash
# Ejecutar scraper específico
php bin/console app:scrape-products

# Para sitios JS-heavy (PCComponentes)
# 1. Microservicio debe estar running (docker-compose up)
# 2. Service realiza POST a http://scraper-service:3000/scrape
# 3. Node.js renderiza page con Puppeteer
# 4. Retorna HTML parsed al backend
```

## Project-Specific Conventions

### Naming Conventions

- **Entities**: Singular, PascalCase (`Product`, `PCComponent`, `Book`)
- **Repositories**: Entity name + `Repository` (`ProductRepository`)
- **Services**: Feature name + `Scraper` (`ProductScraper`, `PCComponentScraper`)
- **Commands**: `app:scrape-{resource}` (`app:scrape-products`, `app:scrape-pccomponents`)
- **Routes**: Plural endpoints (`/api/products`, `/api/pc-components`)

### Coding Standards

- **PHP 8.2+**: Type hints, named arguments, attributes
- **Entity Properties**: Private con getters/setters, nunca public
- **Immutable Dates**: Usar `\DateTimeImmutable` (no `DateTime`)
- **Error Handling**: Servicios lanzan `RuntimeException`, Commands capturan y muestran
- **Doctrine Queries**: Usar Repository methods, no raw SQL

### Environment Configuration

- **Backend config**: `backend/config/services.yaml`
- **Database credentials**: `docker-compose.yml` (dev only - usar env vars en prod)
- **CORS settings**: `backend/config/packages/nelmio_cors.yaml`
- **Routes**: `backend/config/routes.yaml` (auto discovery of controllers)

## Inter-Component Communication

### Backend → Frontend

```
Controller (HTTP response)
    ↓
axios.get('/api/products')
    ↓
Vue Component (reactivity)
    ↓
Render
```

### Backend → Scraper Service

```
Service (PHP)
    ↓
HttpClient::request POST to http://scraper-service:3000/scrape
    ↓
Node.js (Puppeteer)
    ↓
Parsed HTML response
    ↓
Persist to DB
```

## Common Tasks

### Adding a New Scraper Source

1. **Analyze site structure** - Inspect HTML/selectors needed
2. **Create Entity** - Define what data to store
3. **Create Repository** - If custom queries needed
4. **Create Service** - Implement scraping logic
   - Use `DOMDocument` for static HTML
   - Use microservice POST for JS-rendered sites
5. **Create Command** - Wire everything together
6. **Create Migration** - Generate table structure
7. **Test** - Run command manually: `php bin/console app:scrape-...`

### Modifying Existing Data Model

1. Edit Entity class (add/remove properties)
2. Run: `php bin/console make:migration`
3. Review generated migration file
4. Run: `php bin/console doctrine:migrations:migrate`

### Working with the Scraper Microservice

- Located: `/home/toni/price-tracker/scraper-service`
- **Health check**: `GET http://localhost:3000/health`
- **POST /scrape**: `{ url, selector }` → Returns parsed HTML elements
- **Uses Puppeteer** in headless mode with no-sandbox for Docker compatibility

## Files to Reference

- **Scraper Example**: [backend/src/Service/ProductScraper.php](../backend/src/Service/ProductScraper.php) - Complete implementation
- **Command Pattern**: [backend/src/Command/ScrapeProductsCommand.php](../backend/src/Command/ScrapeProductsCommand.php)
- **API Example**: [backend/src/Controller/ApiProductController.php](../backend/src/Controller/ApiProductController.php)
- **Entity Example**: [backend/src/Entity/Product.php](../backend/src/Entity/Product.php)
- **Docker Setup**: [docker-compose.yml](../docker-compose.yml)

## Important Notes for Agents

⚠️ **Do NOT**:
- Commit vendor/ or node_modules/
- Use `DateTime` instead of `DateTimeImmutable` in Entities
- Hardcode URLs - use environment variables
- Leave migrations without descriptions

✅ **DO**:
- Always update migrations after Entity changes
- Handle scraping errors gracefully with try-catch
- Use constructor injection for dependencies (no Service Locator)
- Test scrapers with small data sets first (limit pages/items)
- Check if URL already exists in DB before persisting (avoid duplicates)
