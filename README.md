# Gestión Documentaria - Archivo Central UNDAC

Sistema web para la gestión documentaria del Archivo Central de la Universidad Nacional Daniel Alcides Carrión (UNDAC). Permite administrar, registrar y consultar documentos de manera eficiente y centralizada.

## Características principales

- Registro y consulta de documentos.
- Gestión de usuarios y roles.
- Búsqueda avanzada de archivos.
- Interfaz moderna con Vue.js y Vite.
- Soporte para trabajo en red local con IP dinámica.

## Requisitos previos

- PHP >= 8.1
- Composer
- Node.js y npm
- MySQL o MariaDB
- Laragon (recomendado para entorno local en Windows)

## Instalación

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/tuusuario/gestion_documentaria.git
   cd gestion_documentaria
   ```

2. **Instala las dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Instala las dependencias de Node.js:**
   ```bash
   npm install
   ```

4. **Copia el archivo de entorno y genera la clave de aplicación:**
   ```bash
   cp .env.example .env   # En Windows: copy .env.example .env
   php artisan key:generate
   ```

5. **Configura la base de datos en el archivo `.env`:**
   ```
   DB_DATABASE=gestion_documentaria
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

6. **Ejecuta las migraciones:**
   ```bash
   php artisan migrate
   ```

7. **Compila los assets:**
   ```bash
   npm run dev
   ```

8. **Inicia el servidor de desarrollo:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## Uso en red local con IP dinámica

- El sistema está preparado para funcionar en una red local donde la IP del servidor puede cambiar.
- Puedes acceder desde otras computadoras usando la IP o el nombre de host del servidor:
  ```
  http://<IP_DEL_SERVIDOR>:8000
  ```
  o
  ```
  http://<NOMBRE_DEL_HOST>:8000
  ```
- Asegúrate de que los puertos 8000 (Laravel) y 5173 (Vite) estén permitidos en el firewall de Windows.
## Configuración de Vite para acceso en red local

Para que la interfaz Vue.js funcione correctamente en otras computadoras de la red local, debes configurar el archivo `vite.config.js`:

1. Abre `vite.config.js` en la raíz del proyecto.
2. Asegúrate de que la sección `server` tenga la siguiente configuración:

   ```js
   export default defineConfig({
     server: {
       host: '0.0.0.0', // Permite conexiones desde cualquier IP de la red local
       port: 5173,      // O el puerto que prefieras
     },
     // ...otras configuraciones...
   });
   ```

3. Si tu IP cambia frecuentemente, accede desde otros equipos usando la IP actual de tu PC y el puerto configurado (por defecto, 5173):

   ```
   http://<IP_DEL_SERVIDOR>:5173
   ```

4. Asegúrate de permitir el puerto 5173 en el firewall de Windows.

## Licencia

Este proyecto está bajo la licencia MIT.

---

**Desarrollado por el Maycol Lopez Gavino.**
