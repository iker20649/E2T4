# Proyecto E2T4 - Gestión de Peluquería

## Despliegue
Este proyecto está desplegado en una instancia de **AWS (EC2)** utilizando **Docker**.

- **URL de la API:** http://98.93.71.5/api
- **Tecnologías:** Laravel (Backend), Vue.js (Frontend), MySQL y Nginx.

## Cómo ejecutar el Backend (Docker)
1. `docker-compose up -d`
2. `docker exec -it app-container php artisan migrate`

## Cómo ejecutar el Frontend
1. `cd frontend`
2. `npm install`
3. `npm run dev`


3. Credenciales de prueba
Usuario de prueba: admin@test.com

Contraseña: 12345678
