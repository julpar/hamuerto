<?php
const _VIVO = 'Nop';
const _MUERTO = 'Sip';
const _DONTKNOW = 'No sep';

require 'dbpedia.inc.php';

$term=$_REQUEST['qry'];

if (!empty ($term)) {

   $term=sanitize($term);

   $requestURL = getUrlDbpediaAbstract($term);
   $responseArray = json_decode(request($requestURL),true);

   $bindings=$responseArray["results"]["bindings"];

   if (count($bindings)==0)
        $rta = _DONTKNOW;
   else {
       $deathDate = $bindings[0]["deathDate"]["value"];
       $birthDate = $bindings[0]["birthDate"]["value"];
       
      //Veamos si está vivo
       $parsedDate = date_parse_from_format("Y-m-d",$deathDate);       
       $rta = ($parsedDate['error_count']>0) ?  _VIVO : _MUERTO; //En el estado se guarda el output que se mostrará

       if ($rta==_VIVO)
       $parsedDate = date_parse_from_format("Y-m-d",$birthDate );


       //Calculo de Edad Actual / Años de Muerto
       $date = new DateTime($parsedDate['year']."-".$parsedDate['month']."-".$parsedDate['day']);
       $datenow = new DateTime();
       $diff = $datenow->diff($date);
    }
    
} else $rta = 'Quién?';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Esta muerto... <?php echo $term; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<body style="text-align: center; padding-top: 200px;">
<a href="https://github.com/julpar/hamuerto"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub"></a>
<?php /*
<h1>Debug----</h1>
<h1>DBPedia Abstract for<?php echo $term ?></h1>
<h3>Request URL:</h3><?php echo $requestURL ?><br/>
<h3>Parsed Response: </h3><?php echo var_ump($responseArray); ?><br/>
*/?>
<a href="rss.xml"
    style="font-weight: bold;
          font-size: 120pt;
          font-family: Arial, sans-serif;
          text-decoration: none;
          color: black;"
    title="RSS"
    id="answer">
    <?php echo $rta; ?>
</a>

<br/>

<a href="rss.xml"
style="font-weight: bold;
      font-size: 40pt;
      font-family: Arial, sans-serif;
      text-decoration: none;
      color: black;"
title="RSS"
id="edad">
<?php if ($rta==_VIVO) { 
 echo "De hecho vive y tiene " . $diff->y . " años"; 
} else if ($rta==_MUERTO) {

 echo "De hecho lleva " . $diff->y . " " .pluralize($diff->y). " sin vida..";
} ?>
</a>

<?php if (empty($term)) { ?>
<form name="simple_bar" method="get" action="redirect.php" >
    <div align="center">      
    <input type="text" name="qry" size="30" maxlength="50"><input type="submit" value="Murio?">
    </div>
</form>
<?php } ?>
</body>
</html>