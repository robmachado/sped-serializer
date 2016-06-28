<?php

namespace NFePHP\Serializer;

/**
 * do original de Anil Chauhan <meetanilchauhan@gmail.com>
 */

use XMLWriter;
use DOMDocument;

class XmlParser
{
    private static $xml;
    
    /**
     * Instância variáveis
     */
    private static function load()
    {
        if (is_null(self::$xml)) {
            self::$xml = new XMLWriter();
            self::$xml->openMemory();
            self::$xml->startDocument('1.0', 'utf-8');
            self::$xml->setIndent(false);
        }
    }
    
    /**
     * Converte um Objeto StdClass em XML
     * @param StdClass $obj
     * @return string
     */
    public static function objToXml($obj)
    {
        self::load();
        self::getObject2XML(self::$xml, $obj);
        self::$xml->endElement();
        $xml = self::$xml->outputMemory(true);
        if (strpos($xml, 'xmlns') === false) {
            $xml = self::addAttibuteNS($xml);
        }
        $xml = str_replace("\n", "", $xml);
        return $xml."\n";
    }
 
    /**
     * Converte um XML em um objeto StdClass
     * @param string $xml
     * @return bool | StdClass
     */
    public static function xmlToObj($xml = '')
    {
        if (empty($xml)) {
            return false;
        }
        $xmlString = $xml;
        if (is_file($xmlString)) {
            $xmlString = file_get_contents($xmlString);
        }
        $xmlString = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xmlString);
        $xmlString = '<root>'.$xmlString.'</root>';
        $resp = simplexml_load_string($xmlString);
        $std = json_encode($resp);
        $std = str_replace('@attributes', 'attributes', $std);
        $std = json_decode($std);
        return $std;
    }
    
    /**
     * Adiciona os atributos namespace nas devidas tags
     * @param string $xml
     */
    private static function addAttibuteNS($xml)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $aTags = [
            'nfeProc' => 'http://www.portalfiscal.inf.br/nfe',
            'NFe' => 'http://www.portalfiscal.inf.br/nfe',
            'cteProc' => 'http://www.portalfiscal.inf.br/cte',
            'CTe' => 'http://www.portalfiscal.inf.br/cte',
            'mdfeProc' => 'http://www.portalfiscal.inf.br/mdfe',
            'MDFe' => 'http://www.portalfiscal.inf.br/mdfe',
            'Signature' => 'http://www.w3.org/2000/09/xmldsig#'
        ];
        foreach ($aTags as $tag => $ns) {
            $node = $dom->getElementsByTagName($tag)->item(0);
            if (! empty($node)) {
                $domAttribute = $dom->createAttribute('xmlns');
                $domAttribute->value = $ns;
                $node->appendChild($domAttribute);
            }
        }
        return $dom->saveXML();
    }
    
    /**
     * Converte um objeto StdClass para XML
     * @param XMLWriter $xml
     * @param StdClass $data
     */
    private static function getObject2XML(XMLWriter $xml, $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'attributes') {
                self::getAttibutes($xml, $value);
                continue;
            }
            if (is_object($value)) {
                $xml->startElement($key);
                self::getObject2XML($xml, $value);
                $xml->endElement();
                continue;
            } elseif (is_array($value)) {
                self::getArray2XML($xml, $key, $value);
            }
            if (is_string($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }
    
    /**
     * Cria os atributos de uma TAG
     * @param XMLWriter $xml
     * @param StdClass $data
     */
    private static function getAttibutes(XMLWriter $xml, $data)
    {
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                self::getAttibutes($xml, $value);
                continue;
            } elseif (is_array($value)) {
                self::getArray2XML($xml, $key, $value);
            }
            if (is_string($value)) {
                $xml->writeAttribute($key, $value);
                $xml->endAttribute();
            }
        }
    }
    
    /**
     * Converte os dados de array para XML
     * @param XMLWriter $xml
     * @param string $keyParent
     * @param StdClass $data
     */
    private static function getArray2XML(XMLWriter $xml, $keyParent, $data)
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
                self::getObject2XML($xml, $value);
            } elseif (is_array($value)) {
                self::getArray2XML($xml, $key, $value);
                continue;
            }
            if (is_numeric($key)) {
                $xml->endElement();
            }
        }
    }
}
