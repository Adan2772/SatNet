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

## Usuarios del panel

`/usuarios` administra las cuentas que pueden iniciar sesión (nombre, correo, contraseña) — no confundir con `Cliente`, que son los clientes del ISP. No hay roles todavía: cualquier usuario autenticado puede gestionar a los demás. La única regla es que nadie puede eliminar su propia cuenta desde ahí, para no quedarse fuera del panel a mitad de sesión.

## Recordatorios automáticos

`php artisan satnet:evaluar-suscripciones` envía el recordatorio de pago a las suscripciones que hoy inician su periodo de tolerancia, y queda registrado como tarea programada diaria en `routes/console.php`. Para que corra sola en producción, el servidor necesita el cron de Laravel apuntando a `php artisan schedule:run` cada minuto.

En local, los correos (recordatorios y recibos) no se envían de verdad: `MAIL_MAILER=log` los escribe en `storage/logs/laravel.log`. Configura un proveedor SMTP real en `.env` antes de salir a producción.

## Configuración propia de SATNET

`config/satnet.php` (variables `SATNET_TOLERANCIA_DIAS` y `SATNET_EVALUACION_HORA` en `.env`):

- **Tolerancia de pago:** días después del vencimiento antes de marcar a un cliente como "vencido" (por defecto 5).
- **Hora de evaluación:** hora a la que corre el recordatorio automático diario (por defecto 08:00).

## Estructura del dominio

`Cliente` → `Suscripcion` (plan + día de pago) → `Pago` → `Recibo`. El estado de cobro (`al_dia` / `tolerancia` / `vencido`) se calcula al vuelo a partir de `fecha_proximo_pago`, nunca se guarda en base de datos.

Cada `Suscripcion` puede tener un `Enlace` (1 a 1): nombre, IP asignada, MAC, tipo de antena/CPE, nodo o torre de distribución, número de serie, fecha de instalación, estado (activo / suspendido / falla) y coordenadas. Es información técnica del punto de servicio, independiente del estado de cobro — un cliente puede estar al día y tener el enlace caído, o viceversa. Se edita desde la ficha del cliente.

Dar de baja a un cliente (`clientes.toggle-activo`) no lo borra: pone `clientes.activo` en falso, lo que automáticamente lo saca del dashboard, del filtro de cobro y de los recordatorios (`Suscripcion::scopeActivas`), sin perder su historial.

## Pruebas

```bash
php artisan test
```

33 tests cubren la lógica de cobro (cálculo de tolerancia, ciclo de pago, casos límite de fin de mes), el flujo de pagos (registrar, editar, anular), recordatorios automáticos, búsqueda y baja de clientes, el enlace técnico (incluida la validación de IP duplicada) y el reporte/exportación CSV. También incluyen guardas de regresión para los dos bugs reales que se encontraron durante el desarrollo (el binding de ruta de `planes` y el envío de correo que se quedaba encolado sin worker).

## Reportes

`/reportes/pagos` muestra el total cobrado en un rango de fechas (por defecto, el mes en curso) con exportación a CSV. El dashboard incluye el historial de ingresos de los últimos 6 meses.
