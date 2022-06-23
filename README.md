# SIMPLE 2.0

## Requerimientos

### Requisitos mínimos del servidor

* Procesador: CPU 2 core de 2da generación
* Memoria RAM: 4 GB
* Espacio en disco duro: 10 GB
* Sistema Operativo: Se recomienda usar Debian o Ubuntu (otras distribuciones Linux no hay experiencia)

### Software y librerías

* NodeJS >= 8.11.3 
* NPM >= 5.6.0
* MySQL 5.7 ó MariaDB 10.2
* PHP 7.1
* Docker >=  19.0.*
* Librerías PHP necesarias:
    * OpenSSL
    * PDO
    * PDO_MYSQL
    * Mbstring
    * Tokenizer
    * curl
    * mcrypt
    * Ctype
    * XML
    * JSON
    * GD
    * SOAP
    * bcmath

## Instalación
Existen 2 alternativas de instalación. La primera(recomendable) es ir al directorio `setup/` y seguir los pasos del 
README de ese directorio utilizando Docker. La segunda alternativa es continuar en este archivo y seguir cada uno de los pasos.


### Mysql >= 5.7
Si estas usando una versión mayor o igual a MySQL 5.7, deberas desactivar el only_full_group_by, para eso en el sql mode deberás tener las siguientes lineas (my.cnf).

    sql-mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"


### Creación de directorios 

Debe crear los siguientes directorios con permisos de escritura en la ruta `public/uploads`

* certificados
* connectors
* datos
* documentos
* firmas
* logos
* logos_certificados
* swagger
* timbres
* tmp


### Permisos de directorio

Es posible que deba configurar algunos permisos. Los directorios dentro de `storage` y `bootstrap/cache` deben ser editables por su servidor web o Laravel no se ejecutará.

### Variables de entorno

El siguiente paso es copiar el archivo .env.example a .env y editar las variables de configuración de acuerdo a tu servidor:

```
cp .env.example .env
```

Descripción de variables de entorno a utilizar

```
APP_NAME: Nombre de la aplicación.
APP_ENV: Entorno de ejecución.
APP_KEY: llave de la aplicacion, se auto genera con php artisan key:generate.
APP_DEBUG: true o false.
APP_LOG_LEVEL: Nivel de log (EMERGENCY, ALERT, CRITICAL, ERROR, WARNING, NOTICE, INFO, DEBUG).
APP_URL: URL de tu aplicación incluir http.
APP_MAIN_DOMAIN: Dominio de tu aplicación, incluir http.
ANALYTICS: Código de Seguimiento de google analytics

DB_CONNECTION: Tipo de conexión de tu Base de datos, para este proyecto por defecto se usa mysql.
DB_HOST: host donde se aloja tu Base de Datos.
DB_PORT: puerto por donde se esta disponiendo tu Base De Datos en el Host.
DB_DATABASE: Nombre Base de datos (Debe estar previamente creada).
DB_USERNAME: Usuario Base de datos.
DB_PASSWORD: Contraseña Base de datos.

MAIL_DRIVER: soporta ("smtp", "sendmail", "mailgun", "mandrill", "ses", "sparkpost", "log", "array").
MAIL_HOST: Aquí puede proporcionar la dirección de host del servidor.
MAIL_PORT: Este es el puerto utilizado por su aplicación para entregar correos electrónicos a los usuarios de la aplicación.
MAIL_ENCRYPTION: Aquí puede especificar el protocolo de cifrado que se debe usar cuando la aplicación envía mensajes de correo electrónico.
MAIL_USERNAME: Si su servidor requiere un nombre de usuario para la autenticación, debe configurarlo aquí.
MAIL_PASSWORD: Si su servidor requiere una contraseña para la autenticación, debe configurarlo aquí.


RECAPTCHA_SECRET_KEY: reCaptcha secret key, proporcionado por Google.
RECAPTCHA_SITE_KEY: reCaptcha site key, proporcionado por Google.

JS_DIAGRAM: Libreria que se va a utilizar para hacer los diagramas de flujo, default: jsplumb (Gratuita y libre uso).

SCOUT_DRIVER: driver para agregar búsquedas de texto completo a sus modelos Eloquent.
ELASTICSEARCH_INDEX: Nombre lógico que interpretara elasticsearch como índice.
ELASTICSEARCH_HOST: Aquí puede proporcionar la dirección de host de elasticsearch.

AWS_S3_MAX_SINGLE_PART: Al superar este límite en bytes, los archivos se subirán a Amazon S3 usando multipartes.

DOWNLOADS_FILE_MAX_SIZE: Al momento de descargar trámites que no posean archivos subidos a Amazon S3, se compara el total a descargar con esta variable en Mega bytes, si es mayor que la variable, se usará un JOB para empaquetar y luego enviar el enlace de descarga por correo electrónico a la dirección registrada para ese nombre de usuario. Si es menor que esta variable, se descargará de forma directa sin un Job. Si no se especifica usa por omisión 500 MB.
DOWNLOADS_MAX_JOBS_PER_USER: Cantidad máxima de JOBS de archivos a descargar simultáneos permitidos por cada usuario.
```

### Instalar las dependencias con composer

Laravel utiliza `Composer` para administrar sus dependencias. Entonces, antes de usar este proyecto desarrollado en Laravel, 
asegúrese de tener Composer instalado en su máquina. Y ejecute el siguiente comando.
 
```
composer install
```

Luego, la instalación de las librerías JS necesarias:

```
npm install
```

Compilación de JS

```
npm run prod
```

Luego, Migración y Semillas de la base de datos:

```
php artisan migrate --seed
```

## Actualizaciones

Cada vez que se realice un pull del proyecto, este deberá ser acompañado de la siguiente lista de ejecución de comandos.

```
npm install
npm run production
composer install
php artisan migrate --force
vendor/bin/phpunit
```

## Elasticsearch

Para crear el índice:

```
php artisan elasticsearch:admin create
```

Para indexar todo (Realizar esto en instalación inicial):

```
php artisan elasticsearch:admin index
```

Para indexar solo páginas:

```
php artisan elasticsearch:admin index pages
```

## Creación de usuarios en Frontend, Backend y Manager

Para crear un usuario perteneciente a Frontend, basta con ejecutar este comando especificando email, contraseña y opcionalmente la cuenta:

```
php artisan simple:frontend {email} {password} {cuenta?}
php artisan simple:frontend mail@example.com 123456 1
```

Para crear un usuario perteneciente al Backend, basta con ejecutar este comando especificando email y contraseña:

```
php artisan simple:backend {email} {password}
php artisan simple:backend mail@example.com 123456
```

Y para crear un usuario perteneciente al Manager,

```
php artisan simple:manager {user} {password}
php artisan simple:manager siturra qwerty
```

## Generar la llave de aplicación

```
php artisan key:generate
```

## Tests con PHPUnit

Listado de Tests:

- Verificar que las librerías de PHP requeridas por SIMPLE, estan habilitadas (VerifyLibrariesAvailableTest)
- Validación de Reglas Customizadas (CustomValidationRulesTest)
- Creación de Usuarios (Front, Backend, Manager) (CreateUsersTest)
- Motor de Reglas SIMPLE BPM (RulesTest)

Para ejecutar los Tests solo debes ejecutar el siguiente comando:

```
vendor/bin/phpunit
```

## Adicionales 

Si desea poder utilizar una acción de tipo Soap, debe tener habilitada la librería Soap en su php.ini

## Queue worker para indexar contenido de trámites
Para indexar el contenido de los trámites cada vez que se avanza dentro del flujo, es necesario dejar corriendo el worker con el siguiente comando:

```
php artisan queue:work --timeout=0
```
## Disclaimer

Este software se suministra por los propietarios del copyright y colaboradores “Como Está” y cualquier garantías expresa o implícita, incluyendo, pero no limitado a, las garantías implícitas de comercialización y aptitud para un propósito particular son rechazadas. en ningún caso los propietarios del copyright y colaboradores serán responsables por ningún daño directo, indirecto, incidental, especial, ejemplar o cosecuencial (incluyendo, pero no limitado a, la adquisición o sustitución de bienes o servicios; la pérdida de uso, de datos o de beneficios; o interrupción de la actividad empresarial) o por cualquier teoría de responsabilidad, ya sea por contrato, responsabilidad estricta o agravio (incluyendo negligencia o cualquier otra causa) que surja de cualquier manera del uso de este software, incluso si se ha advertido de la posibilidad de tales daños.


### Alertas

| Dependencias  | Severidad  | Código  |
|------------|------------|------------|
|  highcharts |  Alta | GHSA-gr4j-r575-g665  |
|  laravel/framework | Alta  |  GHSA-3p32-j457-pg5x |



| Dependencias  | Severidad  | Ubicación  | Código  |
|---------------|------------|------------|---------|
|  Incomplete string escaping or encoding | Alta  | resources/assets/js/helpers/jquery-ui/js/jquery-ui.js#L1757 ago by CodeQLg  | CWE-20 / CWE-116  |
|  Incomplete string escaping or encoding | Alta  | resources/assets/js/helpers/jquery-ui/js/jquery-ui.js#L414 ago by CodeQLg  | CWE-20 / CWE-116  |
|  Incomplete string escaping or encoding | Alta  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/qunit.js#L158 ago by CodeQLg  | CWE-20 / CWE-116  |
|  Incomplete string escaping or encoding | Alta  | (Library) resources/assets/js/helpers/calendar/js/moment-2.2.1.js#L ago by CodeQLg  | CWE-20 / CWE-116  |
|  Incomplete string escaping or encoding | Alta  | (Library) resources/assets/calendar/js/moment-2.2.1.js#L ago by CodeQLg  | CWE-20 / CWE-116  |
|  Incomplete string escaping or encoding | Alta  |  (Library) public/calendar/js/moment-2.2.1.js#L ago by CodeQLg | CWE-20 / CWE-116  |
|  Incomplete multi-character sanitization | Alta  | (Library) resources/assets/js/helpers/jquery-ui/js/jquery-1.8.3.js#L748 ago by CodeQLg  | CWE-20 / CWE-116  |
| Incomplete multi-character sanitization  | Alta  |  (Library) resources/assets/js/helpers/jquery-ui/development-bundle/jquery-1.8.3.js#L748 ago by CodeQLg |  CWE-20 / CWE-116 |
| Useless regular-expression character escape  | Alta  | resources/assets/js/helpers/accion_soap.js#L1 ago by CodeQLg  | CWE-20  |
|  Unsafe expansion of self-closing HTML tag | Media  | (Library) resources/assets/js/helpers/jquery-ui/js/jquery-1.8.3.js#L631 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe expansion of self-closing HTML tag | Media  | (Library) resources/assets/js/helpers/jquery-ui/js/jquery-1.8.3.js#L588 ago by CodeQLg  | CWE-79 / CWE-116  |
| Unsafe expansion of self-closing HTML tag  | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/jquery-1.8.3.js#L631 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe expansion of self-closing HTML tag | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/jquery-1.8.3.js#L588 ago by CodeQLg  | CWE-79 / CWE-116  |
| Prototype-polluting function  | Media  | (Library) resources/assets/js/helpers/jquery-ui/js/jquery-1.8.3.js#L34 ago by CodeQLg  | CWE-78 / CWE-79 / CWE-94 / CWE-400 / CWE-915  |
| Prototype-polluting function  | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/jquery-1.8.3.js#L34 ago by CodeQLg  | CWE-78 / CWE-79 / CWE-94 / CWE-400 / CWE-915  |
| Prototype-polluting function  | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/globalize.js#L34 ago by CodeQLg  | CWE-78 / CWE-79 / CWE-94 / CWE-400 / CWE-915  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/jquery-ui/js/jquery-ui.js#L823 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  |  resources/assets/js/helpers/collapse.js#L14 ago by CodeQLg | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/calendar/js/calendar.js#L106 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/calendar/js/calendar-custom.js#L108 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L168 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L167 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L123 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  |  resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L52 ago by CodeQLg | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/js/calendar.js#L106 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/js/calendar-custom.js#L108 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L168 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L167 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L123 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L52 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  |  public/calendar/js/calendar.js#L106 ago by CodeQLg | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L168 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  |  public/calendar/components/bootstrap3/js/bootstrap.js#L167 ago by CodeQLg | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | public/calendar/js/calendar-custom.js#L108 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L123 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe jQuery plugin | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L52 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  | resources/assets/js/helpers/jquery-ui/js/jquery-ui.js#L738 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/jquery.bgiframe-2.1.2.js#L2 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  |  (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/jquery.bgiframe-2.1.2.js#L2 ago by CodeQLg | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/jquery.bgiframe-2.1.2.js#L2 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/jquery.bgiframe-2.1.2.js#L2 ago by CodeQLg  | CWE-79 / CWE-116  |
|  Unsafe HTML constructed from library input | Media  | (Library) resources/assets/js/helpers/jquery-ui/development-bundle/external/jquery.bgiframe-2.1.2.js#L1 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/jquery-ui/development-bundle/demos/droppable/photo-manager.html#L10 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/diagrama-procesos.js#L4 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/collapse.js#L16 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  |  resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L178 ago by CodeQLg | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L132 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L105 ago by CodeQLg | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L78 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L66 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L65 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L46 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/js/helpers/calendar/components/bootstrap3/js/bootstrap.js#L10 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L178 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L132 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L105 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L78 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L66 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L65 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L46 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | resources/assets/calendar/components/bootstrap3/js/bootstrap.js#L10 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L178 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L132 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L105 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L78 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L66 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L65 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L46 ago by CodeQLg  | CWE-79 / CWE-116  |
|  DOM text reinterpreted as HTML | Media  | public/calendar/components/bootstrap3/js/bootstrap.js#L10 ago by CodeQL  | CWE-79 / CWE-116  |

