# Intranet Corporativa Modular

Portal web integral para la gestión de comunicación interna, eventos, documentos y administración corporativa. El proyecto utiliza PHP nativo con arquitectura modular inspirada en Laravel, interfaz responsive con Bootstrap 5 y estilo neumórfico.

## Requisitos del sistema

- Servidor web Apache con soporte para PHP 8.1 o superior
- Extensión PDO para MySQL habilitada
- Servidor de base de datos MySQL 8+
- Composer opcional para gestionar dependencias externas (no requerido para ejecutar el proyecto)
- Sistema operativo Linux (Ubuntu Server recomendado)

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/usuario/intranet.git
   cd intranet
   ```

2. **Configurar la base de datos**
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Actualizar credenciales**
   Edita `config/config.php` y establece los parámetros correctos para tu entorno MySQL.

4. **Publicar en Apache**
   - Copia el contenido del directorio del proyecto a `/var/www/intranet`.
   - Configura un VirtualHost apuntando a `public/index.php` como documento de entrada.
   - Asegúrate de habilitar HTTPS mediante Let’s Encrypt u otro proveedor.

5. **Permisos de carpetas**
   ```bash
   sudo chown -R www-data:www-data storage uploads
   sudo chmod -R 775 storage uploads
   ```

6. **Cache y optimizaciones recomendadas**
   - Habilita `opcache` en PHP.
   - Configura compresión gzip y cache de recursos estáticos en Apache.

## Configuración rápida

1. Abre el sitio en el navegador y accede con el usuario administrador inicial:
   - Usuario: `admin@intranet.test`
   - Contraseña: `Secret123*`
2. Actualiza los datos de la organización desde el módulo **Administración**.
3. Personaliza colores, logotipo y tipografía.
4. Crea usuarios adicionales y asigna roles.

## Arquitectura

- `public/index.php`: Front controller y enrutador ligero.
- `app/Controllers`: Controladores para cada módulo funcional.
- `app/Models`: Abstracciones de acceso a datos (PDO + consultas SQL).
- `resources/views`: Plantillas PHP con componentes reutilizables y estilo neumórfico.
- `storage/uploads`: Archivos cargados (anuncios, organigrama, documentos, branding).
- `routes/web.php`: Definición de rutas HTTP.
- `config/config.php`: Parámetros globales del sitio.

## Módulos

1. **Dashboard**
   - Widgets reordenables por usuario.
   - Frase del día consumida desde API externa.
   - Estadísticas rápidas y accesos a módulos clave.

2. **Calendario de eventos**
   - Vista mensual/semanal con FullCalendar.
   - CRUD de eventos para roles Administrador/Publicador.

3. **Directorio corporativo**
   - Búsqueda por nombre, puesto o correo.
   - Importación manual y acciones rápidas (correo, teléfono).

4. **Tablón de anuncios**
   - Estilo tipo muro con soporte para imágenes.
   - Orden configurable y acciones moderadas por roles.

5. **Organigrama**
   - Representación jerárquica con tarjetas neumórficas.
   - Carga de fotografías y relación jefe/colaborador.

6. **Botonera de enlaces rápidos**
   - Enlaces generales y personales con control de visibilidad.

7. **Sitios embebidos**
   - Iframes responsivos, configuración de tamaño por registro.

8. **Repositorio de documentos**
   - Árbol de carpetas y permisos por rol.
   - Descargas seguras y registros auditables.

9. **Administración**
   - Branding, gestión de usuarios y métricas de visitas.

## Roles y permisos

| Rol                 | Descripción                                          |
|---------------------|------------------------------------------------------|
| Administrador       | Control total: usuarios, branding, módulos completos |
| Publicador          | CRUD en módulos de contenido                          |
| Usuario Final       | Acceso de solo lectura + enlaces personales          |

El middleware de autorización se implementa en los controladores mediante la función `authorize()` incluida en `app/Support/helpers.php`.

## Internacionalización

La carpeta `lang/es` contiene los mensajes base. Puedes añadir nuevos archivos o idiomas replicando la estructura y consumiéndolos mediante la función `translate()`.

## Seguridad

- Sesiones estrictas, tokens CSRF y sanitización de entradas.
- Subida de archivos confinada a `storage/uploads` con permisos restringidos.
- Rutas protegidas con control de roles.

## Despliegue en Apache

Ejemplo de VirtualHost:

```apache
<VirtualHost *:80>
    ServerName intranet.empresa.local
    DocumentRoot /var/www/intranet/public

    <Directory /var/www/intranet/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/intranet-error.log
    CustomLog ${APACHE_LOG_DIR}/intranet-access.log combined
</VirtualHost>
```

Habilita el sitio y reinicia Apache:

```bash
sudo a2ensite intranet.conf
sudo systemctl reload apache2
```

## Importación de datos

1. Crea la base de datos:
   ```sql
   CREATE DATABASE intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   GRANT ALL PRIVILEGES ON intranet.* TO 'intranet_user'@'%' IDENTIFIED BY 'password_seguro';
   FLUSH PRIVILEGES;
   ```
2. Importa `database.sql`.
3. Verifica que los roles y usuarios semilla se hayan cargado correctamente.

## Registro de versiones

| Versión | Fecha       | Notas |
|---------|-------------|-------|
| 1.0.0   | 2025-01-15  | Versión inicial estable con módulos principales |

## Licencia

Proyecto interno. Distribución restringida.
