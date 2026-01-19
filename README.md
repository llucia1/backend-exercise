# Books API

API REST desarrollada con Symfony para la consulta de libros, utilizando como fuente
de datos la API pública Gutendex (Project Gutenberg).

El objetivo del proyecto es exponer una API clara y desacoplada que facilite
la construcción de aplicaciones cliente (web o nativas), aplicando buenas
prácticas de arquitectura y testing.


## Tecnologías

- PHP 8.2
- Symfony
- Arquitectura Hexagonal (Ports & Adapters)
- Domain-Driven Design (DDD)
- Redis (cache)
- Docker
- PHPUnit
- Behat
- OpenAPI / Swagger


## Funcionalidades

- Obtener un libro por su ID
- Buscar libros por texto (título o autor)
- Cache de resultados mediante Redis
- Tests unitarios sin llamadas reales a la API externa
- Tests funcionales de la API con Behat
- Documentación automática de la API


## Endpoints

### Obtener libro por ID

GET /api/v1/books/{id}

Respuesta ejemplo:

{
  "id": 1342,
  "title": "Pride and Prejudice",
  "subjects": ["Courtship", "Love stories"],
  "authors": [
    {
      "name": "Austen, Jane",
      "birth_year": 1775,
      "death_year": 1817
    }
  ]
}


### Buscar libros

GET /api/v1/books/search/{query}

Ejemplo:

GET /api/v1/books/search/dickens


## Arquitectura

El proyecto sigue una Arquitectura Hexagonal clara, separando responsabilidades
por capas y evitando el acoplamiento entre dominio e infraestructura.

Estructura principal:

src/
 └── Books
     ├── Domain
     │   ├── Exception
     │   ├── Model
     │   ├── VO
     │   └── Repository
     ├── Application
     │   ├── Service
     │   └── Response
     ├── Infrastructure
     │   ├── ThirdParty
     │   └── Cache
     └── Presentation
         └── Rest


### Dominio

- Contiene los modelos de negocio y Value Objects
- No depende de Symfony, Redis ni de la API externa
- Define las interfaces de repositorio (puertos)


### Application

- Implementa los casos de uso
- Orquesta el dominio
- Devuelve respuestas específicas para la capa de presentación


### Infrastructure

- Implementa los repositorios
- Contiene la integración con Gutendex
- Implementa cache con Redis mediante decorador


### Presentation

- Controladores REST
- Validación de entrada
- Serialización de respuestas


## Decisiones de diseño

### No se ha utilizado una capa Common

No se ha creado un módulo o carpeta "Common" de forma explícita.

Motivo:
- El proyecto es pequeño y autocontenido
- No se comparten abstracciones entre bounded contexts
- Evita crear una capa artificial sin una necesidad real

Las funcionalidades compartidas se resuelven mediante:
- Interfaces de dominio
- Decoradores
- Servicios propios de Symfony cuando es apropiado

### CORS no se ha modelado como Bounded Context ni como lógica de dominio en este proyecto.


Motivo:
- El objetivo del ejercicio es Books BC
- No existen otros BC consumidores ni escenarios de integración reales
- Introducir:
    - un BC de CORS
    - eventos de dominio
    - buses (Command/Event)
    - añadiría complejidad artificial sin aportar valor funcional



## Cache con Redis

Se implementa un decorador de repositorio (CachedBookRepository).

Funcionamiento:
1. Se consulta primero Redis
2. Si no existe el dato:
   - Se consulta la API Gutendex
   - Se guarda el resultado en Redis
3. Se devuelve siempre un modelo de dominio

TTL configurado: 5 minutos

El dominio no conoce Redis ni la estrategia de cache.


## Tests

### Tests unitarios

- Se testea la capa Application
- No se realizan llamadas reales a la API externa

Ejecución:

make symfony-test


### Tests funcionales (Behat)

- Prueban la API completa
- Kernel real de Symfony

Ejecución:

make symfony-behat

Todos los escenarios pasan correctamente.


## Documentación API

La API está documentada con OpenAPI / Swagger mediante anotaciones.

Ruta:

/api/doc


## Docker

El proyecto se ejecuta mediante Docker.

Servicios:
- API Symfony
- Redis

Levantar el entorno:

make docker-up

Acceder al contenedor de la API:

make docker-access-api


## Instalación

1. Clonar el repositorio
2. Crear los ficheros de entorno a partir de los ejemplos

   cp .env.example .env
   cp .env.test.example .env.test

3. Levantar Docker

   make docker-up

4. Instalar dependencias

   make docker-composer-install

5. Acceder a la API

   http://localhost:8080


## Variables de entorno

Los ficheros .env y .env.test no se versionan.

Se incluyen:
- .env.example
- .env.test.example

Ejemplo de variables:

APP_ENV=dev
REDIS_URL=redis://redis:6379
CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$


## Notas finales

- No se realizan llamadas reales a la API externa en tests
- Redis se integra mediante decorador
- Arquitectura preparada para evolucionar a persistencia propia


## Autor

Ejercicio técnico desarrollado con Symfony aplicando buenas prácticas de
arquitectura, testing y desacoplamiento.
