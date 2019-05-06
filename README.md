# Cervantes-Virtual-Plugin
Plugin para realizar una búsqueda en Cervantes Virtual, sobre temas relacionados con el cine.

El plugin <b> Cervantes Virtual Plugin </b> muestra información sobre una obra, dicha información se muestra en el panel derecho de nuestro post.

Tenemos dos ficheros:

<b>cervantes-virtual.php</b></br>
Este fichero tiene que estar en el directorio <b> wp-content/plugins </b> 

<b>single-movie.php</b></br>
Este fichero llama a nuestro método <b>search_cervantes_virtual($containsTitle)</b> y muestra el resultado obtenido. Este fichero estará en el directorio <b>wp-content/shapely-child</b>

<b>Uso</b></br>
Cuando creamos una nueva entrada, usando un <i>custom field</i>, donde introduciremos el título del tema para que realice la búsqueda en la librería <b>Cervantes Virtual.</b>
