# proyecto_final_empleados

Proyecto base (skeleton) para el **Proyecto Final - Gestión de Empleados** (XAMPP, PHP, MySQL, Bootstrap).
Nombre del proyecto: `proyecto_final_empleados`
Empresa: M.S.C COMPANY

## Contenido del paquete
- Estructura básica MVC ligera en `app/`
- Archivos públicos en `public/` (CSS, JS, index.php)
- SQL para crear la base de datos `database.sql`
- Script de seed para crear el administrador `seed/create_admin.php`
- `.gitignore` y `README` con instrucciones
- Recursos: Bootstrap 5, DataTables, Chart.js, AOS, GSAP, SweetAlert2 (CDNs en las vistas)

## Requisitos
- XAMPP con PHP 8.x y MySQL (MariaDB)
- Colocar la carpeta `proyecto_final_empleados` dentro de `htdocs` (ejemplo: `C:\xampp\htdocs\proyecto_final_empleados`)
- Base de datos por defecto: `proyecto_final_empleados`
- Usuario MySQL por defecto en XAMPP: `root` y contraseña vacía (sin contraseña).
  > Si tu instalación usa credenciales distintas, edita `app/config/database.php`.

## Pasos rápidos (instalación local)
1. Copia la carpeta `proyecto_final_empleados` a `htdocs`.
2. Abre `phpMyAdmin` (http://localhost/phpmyadmin) y crea la base de datos importando `sql/database.sql`.
3. Importante: después de crear la base, ejecuta el script seed para crear el admin:
   - Opción A: abrir en navegador `http://localhost/proyecto_final_empleados/seed/create_admin.php`
   - Opción B: ejecutar por CLI:
     ```
     php seed/create_admin.php
     ```
   Usuario admin por defecto:
   - Email: `admin@gmail.com`
   - Contraseña: `admin123`

4. Accede a la app: `http://localhost/proyecto_final_empleados/public/`

## Enlazar con GitHub (rápido)
En la terminal de VS Code (desde la carpeta `proyecto_final_empleados`):
```bash
git init
git add .
git commit -m "Initial project skeleton"
git branch -M main
# crea el repo en GitHub y reemplaza <TU_REPO_URL>
git remote add origin <TU_REPO_URL>
git push -u origin main
```

## Notas
- Este paquete es un **esqueleto** con vistas y controladores base. Contiene placeholders y ejemplos de implementación para que puedas extenderlo.
- Las librerías externas se cargan desde CDN para facilitar la puesta en marcha sin node/npm.
