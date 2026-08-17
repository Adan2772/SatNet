# SATNET

Plataforma web para que proveedores pequeños y medianos de internet controlen clientes, planes y cobros: tolerancia de pago automática, recordatorios y recibos que se envían solos.

Construida en PHP 8.4 + Laravel 13, MySQL/SQLite, Tailwind CSS 4.

## Poner en marcha el proyecto

```bash
composer install
npm install && npm run build   # o `npm run dev` mientras se desarrolla
cp .env.example .env           # si no existe ya
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Esto crea una base de datos SQLite local (`database/database.sqlite`) con datos de ejemplo: 3 ofertas de internet y 6 clientes con distintos estados de cobro.

**Acceso de prueba:** `admin@satnet.test` / `password`

## Recordatorios automáticos

`php artisan satnet:evaluar-suscripciones` envía el recordatorio de pago a las suscripciones que hoy inician su periodo de tolerancia, y queda registrado como tarea programada diaria en `routes/console.php`. Para que corra sola en producción, el servidor necesita el cron de Laravel apuntando a `php artisan schedule:run` cada minuto.

En local, los correos (recordatorios y recibos) no se envían de verdad: `MAIL_MAILER=log` los escribe en `storage/logs/laravel.log`. Configura un proveedor SMTP real en `.env` antes de salir a producción.

## Configuración propia de SATNET

`config/satnet.php` (variables `SATNET_TOLERANCIA_DIAS` y `SATNET_EVALUACION_HORA` en `.env`):

- **Tolerancia de pago:** días después del vencimiento antes de marcar a un cliente como "vencido" (por defecto 5).
- **Hora de evaluación:** hora a la que corre el recordatorio automático diario (por defecto 08:00).

## Estructura del dominio

`Cliente` → `Suscripcion` (plan + día de pago) → `Pago` → `Recibo`. El estado de cobro (`al_dia` / `tolerancia` / `vencido`) se calcula al vuelo a partir de `fecha_proximo_pago`, nunca se guarda en base de datos.
