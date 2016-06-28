<?php

/**
 * Class XmlParserTest
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */

use NFePHP\Serializer\XmlParser;

class XmlParserTest extends PHPUnit_Framework_TestCase
{
    public function testXmlToObj()
    {
        $xml = file_get_contents(dirname(__FILE__) . '/fixtures/nfe.xml');
        $objActual = XmlParser::xmlToObj($xml);
        $serObj = file_get_contents(dirname(__FILE__) . '/fixtures/test.obj');
        $objExpected = unserialize($serObj);
        $this->assertEquals($objExpected, $objActual); 
    }
    
    public function testObjToXml()
    {
        $xmlExpected = file_get_contents(dirname(__FILE__) . '/fixtures/nfe.xml');
        $serObj = file_get_contents(dirname(__FILE__) . '/fixtures/test.obj');
        $obj = unserialize($serObj);
        $xmlActual = XmlParser::objToXml($obj);
        $this->assertEquals($xmlExpected, $xmlActual); 
    }
}
