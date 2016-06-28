<?php

namespace NFePHP\Serializer;

/**
 * do original de Anil Chauhan <meetanilchauhan@gmail.com>
 */

use XMLWriter;

class XmlParser
{
    private $xml;
    private $xmlns = '';
    
    public function __construct()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'utf-8');
        $this->xml->setIndent(true);
    }
 
    // Method to convert Object into XML string
    public function objToXML($obj)
    {
        $this->getObject2XML($this->xml, $obj);
        $this->xml->endElement();
        return $this->xml->outputMemory(true);
        
    }
 
    // Method to convert XML string into Object
    public function xmlToObj($xml)
    {
        $xmlString = $xml;
        if (is_file($xmlString)) {
            $xmlString = file_get_contents($xmlString);
        }
        $xmlString = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $xmlString);
        $xmlString = '<root>'.$xmlString.'</root>';
        $resp = simplexml_load_string($xmlString);
        $ns = $resp->getNamespaces(true);
        $this->xmlns = $ns[''];
        $std = json_encode($resp, JSON_PRETTY_PRINT);
        $std1 = str_replace('@', '', $std);
        $std = json_decode($std1, true);
        $aN = $std['nfeProc']['attributes'];
        $aT['nfeProc']['attributes']['xmlns'] = $this->xmlns;
        $aT['nfeProc']['attributes']['versao'] = $aN['versao'];
        $std = array_replace($std, $aT);
        echo "<pre>";
        print_r($std);
        echo "</pre>";
        die;
        return $std;
    }
 
    private function getObject2XML(XMLWriter $xml, $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'attributes') {
                $this->getAttibutes($xml, $value);
                continue;
            }
            if (is_object($value)) {
                $xml->startElement($key);
                $this->getObject2XML($xml, $value);
                $xml->endElement();
                continue;
            } elseif (is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
            }
            if (is_string($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }
 
    private function getAttibutes(XMLWriter $xml, $data)
    {
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $this->getAttibutes($xml, $value);
                continue;
            } elseif (is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
            }
            if (is_string($value)) {
                $xml->writeAttribute($key, $value);
                $xml->endAttribute();
            }
        }    
    }
    
    private function getArray2XML(XMLWriter $xml, $keyParent, $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $xml->writeElement($keyParent, $value);
                continue;
            }
            if (is_numeric($key)) {
                $xml->startElement($keyParent);
            }
            if (is_object($value)) {
                $this->getObject2XML($xml, $value);
            } elseif (is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
                continue;
            }
            if (is_numeric($key)) {
                $xml->endElement();
            }
        }
    }
}
