<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# Weather API

Proyecto en Laravel que permite registrar usuarios, autenticarse con Sanctum y consultar datos del clima mediante la integración con Weather API. Soporta almacenamiento de historial, favoritos, manejo de errores, soporte para multidioma y documentación Swagger.

---

## 🚀 Instalación y configuración del proyecto

1. Clona el repositorio:
   ```bash
   git clone https://github.com/jhandres187/api-with-weather.git
   cd api-with-weather
   ```

2. Instala las dependencias:
   ```bash
   composer install
   ```

3. Copia el archivo de entorno y configura tus variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configura tu base de datos y el API key de Weather API en `.env`:
   ```
   DB_DATABASE=weather_db
   DB_USERNAME=root
   DB_PASSWORD=

   WEATHER_API_KEY=tu_api_key_aqui
   ```

5. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

---

## 🌐 Documentación con Swagger

La documentación de la API está disponible mediante Swagger.

1. Generar anotaciones (si no lo has hecho):
   ```bash
   php artisan l5-swagger:generate
   ```

2. Accede a la interfaz:
   ```
   http://localhost:8000/api/documentation
   ```

---

## 🧪 Pruebas

Para ejecutar los tests con PHPUnit:

```bash
php artisan test
```

Los tests incluyen validaciones de:

- Servicios
- Endpoints protegidos

## ¿Dudas o sugerencias?

Si Encuentras un error, tienes alguna duda o quieres proponer una mejora, no dudes ¡Estare para ayudarte!
---




