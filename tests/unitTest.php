<?php

class unitTest extends \PHPUnit_Framework_TestCase
{

    public function test_that_the_xml_file_can_be_loaded()
    {
        $xml = simplexml_load_file('app/data.xml');
        $this->assertEquals(true,file_exists('app/data.xml'));
    }

    public function test_xml2array_method_by_inserting_some_xml_data()
    {
        include 'App/main.php';

        $xmlData = "<?xml version='1.0' encoding='UTF-8'?>".
        "<data><product><priipCloudProductTemplate>otc</priipCloudProductTemplate>".
        "<priipCloudProductType>fxSwap</priipCloudProductType>".
        "<productIdentifier>RBI_fxSwap_EURUSD_long_1Y2D_EUR</productIdentifier>".
        "</product></data>";

        $loadXml = simplexml_load_string($xmlData);

        $newArray = xml2array($loadXml);

        $this->assertEquals($newArray['product'][0],
        array('priipCloudProductTemplate'=>'otc', 'priipCloudProductType'=>'fxSwap', 'productIdentifier'=>'RBI_fxSwap_EURUSD_long_1Y2D_EUR'));
    }

    public function test_array2json_method_by_inserting_some_array_data()
    {
        $arrayData = array('priipCloudProductTemplate'=>'otc','priipCloudProductType'=>'fxSwap','productIdentifier'=>'RBI_fxSwap_EURUSD_long_1Y2D_EUR');

        $newJson = array2json($arrayData);
        $this->assertEquals($newJson, '{"priipCloudProductTemplate":"otc","priipCloudProductType":"fxSwap","productIdentifier":"RBI_fxSwap_EURUSD_long_1Y2D_EUR"}');

    }

}
