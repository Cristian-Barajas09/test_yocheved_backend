<?php

namespace App\Lib\Msdocx;

use App\Lib\Msdocx\BeautifulDocx;
use ZipArchive;

class Reader
{
    public function __construct(
        private ZipArchive $zip,
        private string $path
    ) {
    }

    public function readDocxFile(): array
    {
        if ($this->zip->open($this->path) === TRUE) {
            $content = $this->zip->getFromName("word/document.xml");
            $this->zip->close();
            return $this->extractContent($content);
        } else {
            throw new \Exception('No se pudo abrir el archivo .docx.');
        }
    }

    private function extractContent($xmlContent)
    {
        // Cargar el contenido XML
        $xml = new \SimpleXMLElement($xmlContent);

        $namespaces = $xml->getNamespaces(true);

        $xml->registerXPathNamespace('w', $namespaces['w']);



        $bs = new BeautifulDocx($xml);

        $tables = $bs->extractContent();


        return $tables;
    }
}
