# Administrción
Sabrina Basteiro

INDICACIONES:

* Descargar el repositorio ubicado en: https://github.com/sbasteiro/administrado

* Ejecutar:
</br>
composer install
</br>
cp .env.example .env
</br>
php artisan key:generate
</br></br></br>
* Correr el comando siguiente para ejecutar la migración más reciente, cree que es la más reciente:
</br>
php artisan migrate

* Completar con la información de su entorno, en el archivo .env que se generó, los valores de 
</br>
DB_DATABASE, DB_USERNAME, DB_PASSWORD, API_ENDPOINT, ACCESS_TOKEN, RETRIES

* La función que se utilizó para encontrar las zonas por los puntos de los polígonos, está en esta URL:
</br> https://itecnote.com/tecnote/php-find-point-in-polygon-php/



1) PARA MAYOR VOLUMEN DIARIO:

* Guardado de zonas, sin llamar a la API cada 10 min.
</br></br>
* Utilizar un sistema de colas como SNS, ActiveMQ o Kafka. 
Suscribir servidores escalados horizontalmente a las colas y guardar la informacion en un sistema de cache como Redis o Memcache.
Y hacer que laravel como cliente, busque los envíos desde el sistema de cache compartido.
</br></br>

2) PARA MEJORAS DE CHALLENGE:

* Indicar si los polígonos se requieren por una función o por php plano
</br></br>
* En la sección de Envios Pendientes, Envios Entregados, Envios Borrados no realiza el filtro para la misma.
</br></br>
* Al tocar el logo de ADMINISTRADO que se muestren los envios pendientes y entregados.
</br></br>
* Aclarar que ID del envío es un identificador único y que si la API me está dando envíos que ya tengo, no debería seguir paginando.
</br></br>
* Definir en la consigna si las zonas van a cambiarse o son estáticas.
