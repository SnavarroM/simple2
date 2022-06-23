# Instalación local SIMPLE 2 (BETA)

Si eres desarrollador esta guía te ofrecerá un camino alternativo para la instalación de la plataforma SIMPLE mediante el uso de Docker. Es una guía hecha por desarrolladores para desarrolladores, la cual agradecemos cualquier sugerencia/comentario para mejorarlo. No es una guía diseñada por administradores de sistemas o de infraestructura. Desafortunadamente si eres dev ops, esta no es una lectura que te recomendemos totalmente si es que necesitas levantar un ambiente de test más elaborado y definitivamente no lo recomendamos para un ambiente productivo, puede que tal vez te sirva de referencia, pero no es el objetivo de este apartado.

Es de mucha utilidad que te encuentres familiarizado(a) con el uso de contenedores y herramientas como `Docker` y `docker-compose`. Si ya has trabajado con ellas se te hará más sencillo comprender la documentación y de ser necesario modificar los archivos y configuraciones que aquí encontrarás. Por otro lado, si no has trabajado con docker y docker-compose te invitamos a que te tomes una pausa en este punto y regreses luego de revisar de que se trata cada una de ellas, como funcionan y cómo modificarlas según tu necesidad. No es necesario contar con un nivel avanzado de docker, sin embargo entender como manipular un contenedor o sus configuraciones será de gran ventaja ya que durante la instalación o posterior a ella pueden ocurrir errores o situaciones inesperadas. Esta guía se ha probado en un ambiente Linux, más precisamente Ubuntu, si bien está construida en Docker también puedes tratar de levantar el proyecto en Windows 10 o Mac, sin embargo en estos Sistemas Operativos no te podemos garantizar que todo se ejecute perfectamente ya que nuestro SIMPLE no corre en ninguno de esos 2 ambientes y por el momento no tenemos suficiente información al respecto, pero te invitamos a apoyarnos con este tema. Por otro lado aunque trabajes con Ubuntu u otras distribuciones, los errores siempre pueden ocurrir y en esos casos te tocará revisar cada paso, modificar los archivos, volver a probar y así con mucha fé hasta que funcione. Cabe mencionar que SIMPLE es una plataforma PHP Laravel 5.5, por lo que si conoces Laravel correrás con ventaja.

Suerte!


Sitio oficial de Docker:
[https://www.docker.com/](https://www.docker.com/)

Enlaces adicionales:
- [https://docs.docker.com/desktop/](https://docs.docker.com/desktop/)


Herramienta docker-compose:
[https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)

Es importante mencionar que esta guía es un versión de prueba enfocada en los desarrolladores que busquen instalar SIMPLE en su equipo personal en un ambiente local. Al ser una versión beta no estará libre de errores o problemas que puedan surgir a lo largo de la instalación. Si se te presenta algún error puedes revisar nuestro apartado de [errores detectados durante la instalación y como tratar de solucionalos](../docs/errores/readme.md). Si no encuentras aquí lo que necesitas e implementas tu propia solución, te invitamos a que puedas compartir el problema que tuviste y cómo lo solucionaste, así podremos agregarlo en este apartado y ayudar a otras personas que puedan enfrentarse a una situación igual o similar en el futuro.


## Definición de archivos

Para comenzar la instalación y como te comentamos al principio es necesario tener instalado en tu equipo docker y su herramienta docker-compose.

Dentro de este directorio `setup/local/` encontrarás todos los archivos necesarios para la instalación utilizando docker, por ejemplo:

### `.env.example`
Este es el archivo de nuestras variables de entorno, lo primero que debes hacer antes de ejecutar cualquier cosa es crear un archivo `.env` a partir de este archivo de ejemplo:

`cp .env.example .env`

> Para las variables que deban definir el valor de HOST de alguno de los servicios (contenedores), el de base de datos por ejemplo, puedes simplemente agregar el nombre del servicio y éste se encargará internamente de resolver cuál es la ip del contenedor, por ej:  `DB_HOST=mysql_service`.

Los contenedores de este ejemplo mapean o asocian sus puertos por defecto a su equivalente en localhost, por ejemplo el contenedor mysql 3306 apuntará a tu local 3306 y podrás acceder a él como si lo tuvieses instalado en tu equipo. Si ya tienes un mysql instalado para evitar conflicto en el uso del puerto, puedes cambiarlo en el archivo de variables de entorno y decirle por ej. que DB_PORT sea igual 3308, así cuando levantes el contenedor con `docker-compose` mapeará el 3306 del contenedor a tu 3308 local.

> Para efectos de este ejemplo de instalación la variable de entorno `APP_KEY` ya vendrá con un valor generado por defecto desde `.env.example`. Definitivamente no se recomienda mantener esa llave a la hora de utilizar este proyecto en un ambiente que no sea tu propio computador o ambiente local. Para generar tu propia llave revisa el apartado de `key:generate` de la herramienta [artisan](https://laravel.com/docs/5.5/artisan.) de Laravel.



### `Dockerfile`
En él contrarás las especificaciones para instalar la versión de `PHP` y las diferentes librerías necesarias para que pueda correr nuestra aplicación `SIMPLE` [(laravel 5.5)](https://laravel.com/docs/5.5). Adicionalmente en este archivo se define la instalación de `Node js` para el procesamiento de los assets `(imagenes, css y js)`. Todas estas librerías resultarán en una imagen docker la cual se utilizará para levantar el contenedor de la aplicación.

### `docker-compose.yml`
En este archivo definimos los diferentes servicios que luego se disponibilzarán desde su contenedor respectivo.

* Servicio ***app***, define el contenedor de la aplicación, en este contenedor es donde corre `laravel` y en donde se deben ejecutar las migraciones o la compilación de asstes con `npm`.
* Servicio ***webserver***, define un contenedor `nginx` que se encarga simplemente de servir la aplicación hacia el exterior `(a tu navegador)`.
* Servicio ***mysql_service***, define un contenedor de base de datos `mysql 5.7` y sus parámetros de acceso.
* Servicio ***elastic***, define un contenedor de `Elastic Search` para el indexado de trámites.
    > Este servicio es parte de una funcionalidad que está en proceso de refactorización (eliminación). Por el momento la aplicación necesita que exista elasticsearch para funcionar correctamente. Pero en un mediano a corto plazo se eliminará y no será necesario contar con un servicio de elasticsearch.

Los servicios de `base de datos` y `elasticsearch` cuentan con un volumen definido para cada uno, lo que te permitirá persistir tus datos aún cuando elimines y vuelvas a crear los contenedores todas las veces que necesites.

De modo similar el código de la aplicación laravel estará mapeado a los contenedores de ***app*** y ***webserver***, por lo cual, desde tu IDE favorito cada cambio que realices a un archivo se verá automáticamente reflejado dentro de cada contenedor respectivamente.

> Si desde tu editor modificas una variable de entorno, si bien también se actualizará el archivo, pero tendrás que reiniciar o reconstruir tus contenedores.

Contamos con dos directorios para redefinir ciertos valores dentro de los contenedores de ***app*** y ***webserver***. 

Para modificar las variables de php puedes agregar estas variables dentro del archivo `setup/local/php/local.ini`.

Para modificar la configuración de `app.conf` de `nginx` puedes hacerlo en `setup/local/nginx/conf.d/app.conf`.

Con esto cada vez que levantes tus contenedores con `docker-compose up -d` estos valores se escribirán en la ruta correspondiente de su contenedor, lo puedes revisar en el apartado de **volumes:** de los servicios de app y webserver.

Para este ejemplo se usa un contenedor `nginx` particularmente, sin embargo si quieres puedes construir tu propio servicio utilizando apache por ejemplo y modificar el archivo `docker-compose.yml` según corresponda a tu implementación, pero el ejemplo dentro de este apartado contempla solamente nginx.

> Recuerda que todas estas configuraciones son solo de ejemplo de como poder hacerlo, siempre puedes modificarlas según tu necesidad.

### `install.sh`
Este archivo bash es simplemente para acortar la instrucción que ejecuta el `build` de `docker-compose` y además puedes agregarle tareas adicionales para que se ejecuten en una sola instrucción inicial. Este archivo levanta todos los contenedores y una vez completado ingresa al contenedor de la aplicación desde donde ejecuta otro archivo `start.sh`. Esta es un forma de agrupar cierto grupo de instrucciones dentro de un contexto, puedes crear tus propios archivos bash que te faciliten otras tareas como migraciones, script de npm, etc.

### `start.sh`
En este archivo se definen las siguientes instrucciones:
- Se dan permisos a ciertos directorios de laravel, logs, cache, uploads, etc.
- Instalación de las dependencias de laravel `composer install`.
- Instalación y compilación inicial de los assets `npm install` && `npm run dev`.
- Se corren las migraciones de base de datos y los seeds iniciales.
- Se crean usuarios para acceder a las secciones de backend y otro para el manager.
- Se crea el índice en elasticsearch.

> Este archivo está pensado para ser ejecutado solo la primera vez, ya que contiene elementos que solo necesitamos ejecutarlos una vez y al principio, por ejemplo las migraciones iniciales y los seeds (en esta parte siempre se reestablece la bdd), así que ten cuidado de respaldar tus datos si es que ya tienes información registrada. Siempre puedes modificar estos archivos a tu conveniencia, crear nuevos o también entrar directamente al contenedor de la aplicación y ejecutar lo que necesites desde ahí, como: `php artisan, migraciones, npm, etc`.


## Manos a la obra

Ahora que ya más o menos sabes a que te enfrentarás puedes comenzar con la instalación.

Repasemos un poco: 
- Recuerda, debes estar dentro del directorio `setup/local/`
- Crea y ajusta tus variables de entorno en tu archivo `.env`
- Ejecuta el comando `bash install.sh`

### Ejecutando bash install.sh
A partir de este punto se comenzarán a construir los diferentes elementos necesarios para armar nuestros contenedores:
- Primero, comenzará a levantar el contenedor de la aplicación y para ello deberá construir la imagen (a partir de nuestro `Dockerfile`).
- Luego pasará a los siguientes y verificará si tienes instaladas las imagenes necesarias para cada contenedor, ya sea mysql 5.7, nginx, todo lo definido como "`image:`" dentro de cada servicio de nuestro `docker-compose.yml` y si no cuentas con ellas, las irá descargando. (La primera vez es la que más se demorará, según tu conexión a internet). Una vez descargada levantará cada contenedor.
- Finalmente si todo ha ido bien hasta este punto, se ejecutará desde el contenedor de la aplicación el comando `start.sh` y todas las instrucciones ahí definidas.
- Si no tuvimos errores hasta acá finalmente ya puedes abrir tu navegador en localhost:8000 o el puerto que hayas definido en tu `.env`.

Si desafortunadamente se te presentaron problemas revisa nuestro apartado de [errores detectados durante la instalación y como tratar de solucionalos](../docs/errores/readme.md).


# Post instalación
Una vez se ha realizado correctamente la instalación, cada contenedor quedará ejecutandose automáticamente y podrás acceder a la aplicación escribiendo la ruta de localhost en tu navegador más el puerto por defecto o el que le hayas definido. Por ej: http://localhost:8000/

Si revisaste el archivo `start.sh` habrás notado que antes de finalizar la instalación se crean dos usuarios y sus respectivas contraseñas, uno para la sección de backend y otro para la sección de manager.

En el `Manager`: http://localhost:8000/manager/ podrás gestionar las cuentas, todo tipo de usuarios, definición de anuncios, estados de reportes, alertas, entre otros.

En el `Backend`: http://localhost:8000/backend/ podrás encontrar la gran mayoría de las funcionalidades de SIMPLE, la definición y modelado de procesos y todo lo que ello implica, formularios, eventos, acciones, etc. Realizar seguimiento, gestión de reportes, un apartado de configuración para manejar los usuarios de tu institución, registro de firma electrónica, entre otros.
