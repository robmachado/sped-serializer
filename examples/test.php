<?php

//converte xml para simplexml class
$filename = '35160658716523000119550000000413101703406429-nfe.xml';
$nfe = simplexml_load_file($filename);
echo '<pre>';
print_r($nfe);
echo '</pre>';
echo '<BR><BR><BR>';
//converte de simplexml para xml
$dom_sxe = dom_import_simplexml($nfe);
if (!$dom_sxe) {
    echo 'Error while converting XML';
    exit;
}

$dom = new DOMDocument('1.0');
$dom_sxe = $dom->importNode($dom_sxe, true);
$dom_sxe = $dom->appendChild($dom_sxe);
echo '<pre>';
echo $dom->saveXML();
echo '</pre>';
