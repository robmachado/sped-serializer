<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Serializer\XmlParser;

$filename = 'nfe.xml';
$data = XmlParser::xmlToObj($filename);
$obj = null;

/*
echo '<pre>';
print_r($data);
echo '</pre>';
echo '<BR><BR><BR>';
*/

$xml = XmlParser::objToXml($data);
header('Content-type: text/xml; charset=UTF-8');
echo $xml;
