![Administrado](https://administrado-assets.s3.amazonaws.com/img/logo_mail.png "Administrado")

# Administrado challenge
Sabrina Basteiro

INDICACIONES:
Correr el comando siguiente para ejecutar la migración más reciente, cree que es la más reciente:
php artisan migrate

1) PARA MAYOR VOLUMEN DIARIO:

* En primer lugar se podrían guardar las zonas en dos tablas (zonas y cordenadas) donde se realice una relación y en la tabla de shipping tenga solo como zona un id relacionado. Esto se puede guardar mediante un cron pero que no corra todos los días o un simple endpoint que lo haga manualmente, porque las zonas no cambian generalmente, correrlo a mano si se agregan zonas o si en un caso excepcional las zonas cambian. Esto ahorraría la llamada al endpoint cada vez que se guarda un envío.
El código se podría optimizar si el endpoint de zonas viene con otro orden: El id que nombra la zona, todos los puntos X y todos los puntos Y, separados. Pero esto es para poder hacerlo como lo resolví yo. Encontré varias librerias de laravel para manejar polígonos, pero ninguna era compatible, asi que decidí hacerlo lógicamente con php.
</br></br>
* Las acciones en el challenge cuando terminan refrescan la página, podrían refrescar el TD y sería más rápido (esto es de vista nada más y para mejorar el uso).
</br></br>
ESTE PODRIA IR EN LOS DOS: La API siempre te va a dar los envíos por orden de fecha creada y nunca se van
a sumar envíos viejos, por lo que no hagas requests innecesarias.
* Creo que aunque no vengan envíos viejos, tendría que haber algún campo identificatorio (yo lo hice con el "id_shipping", es probable que en la lógica de negocio se permitan repetidos, pero si lo dejaba así se me iba a llenar la base de datos) para asegurarse que un envío no se va a cargar dos veces, sería un paso más para asegurar la integridad del código.
</br></br>
* Cuando se toca el logo de administrados o lo que es igual cuando inicia la página sólo muestra los pendientes, eso se logra también haciendo click en "Envíos pendientes" así que en el general lo que decidí hacer es mostrar pendientes y entregados, para sumar una vista extra y que no sea igual al filtro. Sería una mejora para la experiencia de usuario.



2) PARA MEJORAS DE CHALLENGE:

* Seguramente exista una librería en Laravel para definir los polígonos, encontré varias pero ninguna fue compatible. Quizas informar que libreria usar, salvo que se quiera llegar a la lógica sin usar laravel que me parece bien también.
</br></br>
* En la sección de Envios Pendientes, Envios Entregados, Envios Borrados el filtro no es para esa selección. Yo lo hice distinto, para que se pueda filtrar en cada una de las tablas formadas por esos filtros. Esto también permite buscar los borrados por los campos seleccionados.

