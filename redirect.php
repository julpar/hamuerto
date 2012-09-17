<?php
$term=$_REQUEST['qry'];

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if (!empty ($term)) $extra=$term;

header("Location: http://$host$uri/$extra");
exit();
?>