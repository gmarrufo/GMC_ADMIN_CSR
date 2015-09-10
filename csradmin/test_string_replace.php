<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<head>
       <title>Title here!</title>
</head>
<body>


<?php
$keyword = "This* is# a @@test";

$var = $keyword;
$stripped = ereg_replace("[^A-Za-z0-9 ]", "", $var);

$clean = preg_replace("/^[^a-z0-9]?(.*?)[^a-z0-9]?$/i", "$1", $text);

echo $stripped;

?>


</body>
</html>
