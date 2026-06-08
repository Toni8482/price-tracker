# Comparador de Perfumes Online

## Descripción

Aplicación web que permite recopilar información de perfumes desde diferentes tiendas online y centralizarla en una única plataforma para facilitar la comparación de precios.

La aplicación está compuesta por:

* Frontend desarrollado en Vue 3.
* Backend desarrollado en Symfony.
* Base de datos MySQL.
* Microservicio en Node.js con Puppeteer para la recopilación automática de datos.
* Entorno de desarrollo gestionado mediante Docker Compose.

## Tecnologías utilizadas

### Frontend

* HTML5
* CSS3
* JavaScript
* Vue 3

### Backend

* PHP
* Symfony
* Doctrine ORM

### Base de datos

* MySQL

### Scraping

* Node.js
* Puppeteer

### Herramientas

* Docker
* Docker Compose
* Adminer
* Postman

## Arquitectura

La aplicación se encuentra dividida en varios servicios Docker:

* **php**: backend Symfony.
* **vue**: frontend Vue 3.
* **db**: base de datos MySQL.
* **scraper-service**: microservicio Node.js encargado de la recopilación de datos.
* **adminer**: administración de la base de datos.

## Instalación

### Clonar el repositorio

```bash
git clone https://github.com/Toni8482/price-tracker.git
cd price-tracker/
```

### Levantar el entorno Docker

```bash
docker compose up -d --build
```

### Accesos

Frontend:
http://localhost:5173

Backend:
http://localhost:8000

Adminer:
http://localhost:8080

## Base de datos

La aplicación utiliza MySQL como sistema gestor de base de datos.

Para ejecutar las migraciones:

```bash
docker-compose exec php bash 
php bin/console doctrine:migrations:migrate
```

## Recopilación de datos

La recopilación de perfumes se realiza mediante un microservicio desarrollado en Node.js utilizando Puppeteer.

Para iniciar el proceso:

```bash
docker-compose exec php bash 
php bin/console app:scrape-perfumes
```

El comando envía la lista de urls al microservicio, que navega automáticamente por las webs de perfumes y devuelve los datos obtenidos para su almacenamiento en la base de datos.

## Funcionalidades

* Catálogo de perfumes.
* Vista detalle de producto.
* Comparación de perfumes similares.
* Sistema de favoritos.
* Gestión de usuarios.
* Gestión de roles.
* Diseño responsive.
* Recopilación automática de datos.

