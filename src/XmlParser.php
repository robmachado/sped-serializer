<?php

namespace NFePHP\Serializer;

/**
 * do original de Anil Chauhan <meetanilchauhan@gmail.com>
 */

use XMLWriter;
use DOMDocument;

class XmlParser
{
    private $xml;
    private $xmlns = '';
    
    /**
     * Construtor
     * Instancia o XMLWriter
     */
    public function __construct()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'utf-8');
        $this->xml->setIndent(false);
    }
 
    /**
     * Converte um Objeto StdClass em XML
     * @param StdClass $obj
     * @return string
     */
    public function objToXML($obj)
    {
        $this->getObject2XML($this->xml, $obj);
        $this->xml->endElement();
        $xml = $this->xml->outputMemory(true);
        return $this->addAttibuteNS($xml);
    }
 
    /**
     * Converte um XML em um objeto StdClass
     * @param string $xml
     * @return StdClass
     */
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
        $std = json_encode($resp);
        //remove o @ do marcador de attibutos do SimpleXML, isso é necessário para permitir a leitura do
        //campo diretamente do objeto gerado pois o @ causa erro na leitura
        $std = str_replace('@', '', $std);
        //esta parte do codigo, abaixo é muito RUIM mas sem isso não teremos o namespace, pois não é exportado para 
        //o json string
        //$std = str_replace('"nfeProc":{"attributes":{', '"nfeProc":{"attributes":{"xmlns":"'.$this->xmlns.'",', $std);
        //$std = str_replace('"NFe":{', '"NFe":{"attributes":{"xmlns":"'.$this->xmlns.'"},', $std);
        //teria que adptar para CTe e outros ou simplesmente não exportar isso e deixar por conta do construtor do xml
        //usando DOM para inserir o namespace
        $std = json_decode($std);
        return $std;
    }
    
    /**
     * Adiciona os atributos namespace nas devidas tags
     * @param string $xml
     */
    private function addAttibuteNS($xml)
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
            'MDFe' => 'http://www.portalfiscal.inf.br/mdfe'
        ];
        foreach ($aTags as $tag => $ns) {
            $node = $dom->getElementsByTagName($tag)->item(0);
            if (! empty($node)) {
                $domAttribute = $dom->createAttribute('xmlns');
                $domAttribute->value = $ns;
                $node->appendChild($domAttribute);
            }
            $node = null;
        }
        return $dom->saveXML();
    }
    
    /**
     * Converte um objeto StdClass para XML
     * @param XMLWriter $xml
     * @param StdClass $data
     */
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
    
    /**
     * Cria os atributos de uma TAG
     * @param XMLWriter $xml
     * @param StdClass $data
     */
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
    
    /**
     * Converte os dados de array para XML
     * @param XMLWriter $xml
     * @param string $keyParent
     * @param StdClass $data
     */
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
