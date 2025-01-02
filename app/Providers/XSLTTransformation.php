<?php
/*
    AUTHOR: LEE VESE
*/

namespace App\Providers;

use DOMDocument;
use XSLTProcessor;
use Exception;

class XSLTTransformation
{
    protected $xmlFile;
    protected $xslFile;

    /**
     * Constructor to load XML and XSL files.
     *
     * @param string $xmlFilePath
     * @param string $xslFilePath
     * @throws Exception
     */
    public function __construct($xmlFilePath, $xslFilePath)
    {
        if (!file_exists($xmlFilePath) || !file_exists($xslFilePath)) {
            throw new Exception("XML or XSL file not found.");
        }

        $this->xmlFile = $xmlFilePath;
        $this->xslFile = $xslFilePath;
    }

    /**
     * Load XML and XSL, and perform the transformation.
     *
     * @return string Transformed XML as HTML
     * @throws Exception
     */
    public function getTransformedXml()
    {
        $xml = new DOMDocument();
        $xml->load($this->xmlFile);

        $xsl = new DOMDocument();
        $xsl->load($this->xslFile);

        $processor = new XSLTProcessor();
        $processor->importStylesheet($xsl);

        $output = $processor->transformToXml($xml);

        if ($output === false) {
            throw new Exception("Error transforming XML with XSL.");
        }

        return $output;
    }
}
