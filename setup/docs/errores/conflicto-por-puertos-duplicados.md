# Conflicto por puertos duplicados

Si luego de ejecutar el comando `bash install.sh` tenemos un warnig del tipo "*Host is already in use by another container*" significa que alguno los puertos necesarios para levantar los contenedores ya está siendo utilizado en tu equipo por otro contenedor u otra aplicación, el `warning` debería indicar el nombre del servicio que ha no ha podido levantar. Para nuestro ejemplo de instalación los puertos que usan por defecto son:
- Puerto `8000` para el contenedor que levanta la aplicación laravel.
- Puerto `3306` para el contenedor mysql.
- Puerto `9200` para el contenedor de elastic search.

Esto siempre puede ocurrir si ya cuentas con algún otro servicio de mysql que utilice el puerto por defecto o alguna aplicación web que use el típico puerto `8000`.

Para modificarlos debes cambir su valor respectivo en el archivo `.env`:
- `APP_PORT=` para el contenedor que levanta la aplicación larave.
- `DB_PORT=` para el contenedor mysql.
- `ELASTICSEARCH_PORT=` para el contenedor de elastic search.

Si esta alerta se lanza al comienzo de la instalación deberás reinicarla ejecutando nuevamente `bash install.sh`.

Por otro lado si te ocurre después de haber realizado la instalación, debes reinciar los contenedores una vez modificados los puertos en el archivo `.env`.

Si al reiniciarlos te das cuenta de que los valores de los puertos no han cambiado prueba eliminarlos y volver a construirlos.

Por ejemplo con:

`docker-compose down && docker-compose up -d`

** Recuerda que el comando `docker-compose` utiliza lo definido en el archivo *docker-compose.yml* por lo que será sensible a errores si vas a realizar un cambio en la configuración si este se encuentra en ejecución y luego lo desactivas con alguna configuración diferente a la que tenía cuando levantaste los contenedores.

<br/>

* * * * *
### [<- Atras](./readme.md)

* * * * *