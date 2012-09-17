<?php
// Author: John Wright
// Modified by: Julian Paredes <@jparedes>
// Website: http://johnwright.me/blog
//
// The Original code is live at:
// http://johnwright.me/code-examples/sparql-query-in-code-rest-php-and-json-tutorial.php


function getUrlDbpediaAbstract($term)
{
   $format = 'json';

   $query = <<<EOT
PREFIX p: <http://dbpedia.org/property/>
PREFIX dbpedia: <http://dbpedia.org/resource/>
PREFIX category: <http://dbpedia.org/resource/Category:>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX geo: <http://www.georss.org/georss/>

SELECT DISTINCT *
WHERE {
   ?person rdf:type dbpedia-owl:Person ;
         rdfs:label "$term"@en;
         dbpedia-owl:birthDate ?birthDate .
         OPTIONAL { ?person  dbpedia-owl:deathDate ?deathDate }
}
EOT;

/*
$query = <<<EOT
PREFIX dbp: <http://dbpedia.org/resource/>
PREFIX dbp2: <http://dbpedia.org/ontology/>

SELECT ?deathDate
WHERE {
    dbp:$term dbp2:deathDate ?deathDate
}
EOT;
*/
 
   $searchUrl = 'http://dbpedia.org/sparql?'
      .'query='.urlencode($query)
      .'&format='.$format;

   return $searchUrl;
}


function request($url){

   if (!function_exists('curl_init')){
      die('CURL is not installed!');
   }

   $ch= curl_init();
   curl_setopt($ch,CURLOPT_URL,$url);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

   //Here you find more options for curl: http://www.php.net/curl_setopt
   $response = curl_exec($ch);

   curl_close($ch);

   return $response;
}


function printArray($array, $spaces = "")
{
   $retValue = "";

   if(is_array($array))
   {
      $spaces = $spaces."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

      $retValue = $retValue."<br/>";

      foreach(array_keys($array) as $key) {
         $retValue = $retValue.$spaces."<strong>".$key."</strong>".printArray($array[$key],$spaces);
      }
      $spaces = substr($spaces, 0, -30);
   }
   else $retValue = $retValue." - ".$array."<br/>";
   return $retValue;
}

function sanitize($qry) {


     $separador = ' ';

     if (empty($qry)) return $qry; //aseguro no nulos

     $qry=str_replace('_',$separador,$qry); //reemplazos de espacios
     
     $terms=explode($separador,$qry); //tokenizer para hacer uppercase
     foreach ($terms as $value) {
        if (!empty($return)) $return.=' ';
        $return.=ucwords(strtolower($value));
     }
     
    return $return;
}

function pluralize ($yrs) {
    if ($yrs>1) return 'años';  else return 'año';
}
?>
