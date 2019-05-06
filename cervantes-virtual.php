<?php

/*
Plugin Name: Cervantes virtual Plugin
Plugin URI: http://www.cervantesvirtual.com
Description: A plugin to add references related to movies in Cervantes Virtual
Version: 1.0
Author: Javier García Sigüenza, Antonio Min Huan Lei Tam, Kalia Martin Reina
Author URI:
*/

class SPARQLQueryDispatcher
{
    private $endpointUrl;

    public function __construct(string $endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
    }

    public function query(string $sparqlQuery): array
    {

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/sparql-results+json'
                ],
            ],
        ];
        $context = stream_context_create($opts);

        $url = $this->endpointUrl . '?query=' . urlencode($sparqlQuery);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }
}

//Añadimos CSS para el código impreso
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

function register_plugin_styles() {
	wp_register_style('cervantes-virtual-cine', plugins_url('css/plugin.css',__FILE__ ) );
	wp_enqueue_style('cervantes-virtual-cine');
}

//Función que se encarga de hacer la query e imprimir los resultados
function search_cervantes_virtual($containsTitle){
    //Comprobamos que se ha pasado algún texto y sino no se realiza la busqueda.
    if ($containsTitle != ""){
        //Establecemos endpoint y la query.
        $endpointUrl = 'http://data.cervantesvirtual.com/bvmc-lod/repositories/data';
        $sparqlQueryString = "PREFIX dc: <http://purl.org/dc/elements/1.1/> ".
            "PREFIX dcx: <http://purl.org/dc/terms/> ".
            "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ".
            "PREFIX rdaw: <http://rdaregistry.info/Elements/w/> ".
            "PREFIX rdaa: <http://rdaregistry.info/Elements/a/> ".

            "SELECT ?work ?fecha ?auth ?title ?name ?materia WHERE { ".
            "?work dc:subject ?materia . ".
            "?work dcx:created ?fecha . ".
            "?work rdfs:label ?title . ".
            "?work rdaw:author ?auth . ".
            "?auth rdaa:nameOfThePerson ?name . ".
            "FILTER regex (?title, '".$containsTitle."') ".
            "FILTER regex (?materia, 'Cine') ".
            "} LIMIT 5 ";

        $queryDispatcher = new SPARQLQueryDispatcher($endpointUrl);
        $queryResult = $queryDispatcher->query($sparqlQueryString);

        //Comprobamos si ha habido algún resultado y si es así mostramos los datos y le damos formato a los datos.
        if(!empty($queryResult["results"]["bindings"])){
            echo "<aside id='secondary' class='widget-area col-md-4' role='complementary'>";
            echo "<div id='cervantes-virtual-cine' class='widget widget-cv-cine'>";
            echo "<h2 class='widget-title'>Resultados en <a href='http://cervantesvirtual.com'>Cervantes Virtual</a> relacionados con la entrada </h2>";
            echo "<ul>";
            foreach($queryResult["results"]["bindings"] as &$entry){
                echo "<li>";
                echo "<b>Título de la obra: </b> ".$entry["title"]["value"].".</br>";
                echo "<b>Autor: </b>".$entry["name"]["value"].".</br>";
                echo "<b>Tema: </b> ".$entry["materia"]["value"].".</br>";
                echo "<b>Link: </b> <a href='".$entry["work"]["value"]."'>".$entry["work"]["value"]."</a></br>";
                echo "<br>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
            echo "</aside>";
        } 

    }

}

?>

