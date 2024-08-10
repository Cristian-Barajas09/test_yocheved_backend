<?php
namespace App\Lib\Msdocx;
use SimpleXMLElement;


class BeautifulDocx {

    private $tags = [
        'p' => 'paragraph',
        'tbl' => 'table',
        'tr' => 'tableRow',
        'tc' => 'tableCell',
        't' => 'text',
        'sectPr' => 'sectionProperties',
        'rPr' => 'runProperties',
        'r' => 'run',
        'trPr' => 'tableRowProperties',
    ];

    public function __construct(
        private SimpleXMLElement $xml
    ){}

    public function extractContent(): array
    {
        $content = [];
        foreach ($this->xml->xpath('//w:body') as $body) {
            $content = array_merge($content, $this->extractBody($body));

        }
        return $content;
    }

    public function extractBody(SimpleXMLElement $body): array
    {
        $content = [];


        // dd($body->children());
        // Extraer párrafos y tablas en el orden en que aparecen
        foreach ($body->children("w",true) as $element) {
            $tag = $element->getName();

            if (isset($this->tags[$tag])) {

                $content[][$this->tags[$tag]] = $this->{'extract' . ucfirst($this->tags[$tag])}($element);
            }
        }


        return $content;
    }

    public function extractParagraph(SimpleXMLElement $paragraph): string
    {
        $text = '';
        foreach ($paragraph->xpath('.//w:t') as $element) {
            $text .= (string) $element;
        }
        return $text;
    }

    public function extractTable(SimpleXMLElement $table): array
    {
        $rows = [];
        foreach ($table->xpath('.//w:tr') as $row) {
            $rows[][$this->tags["tr"]] = $this->extractTableRow($row);
        }
        return $rows;
    }

    public function extractTableRow(SimpleXMLElement $row): array
    {
        $cells = [];
        foreach ($row->xpath('.//w:tc') as $cell) {


            $cells[][$this->tags["tc"]] = $this->extractTableCell($cell);
        }

        return $cells;
    }



    public function extractTableCell(SimpleXMLElement $cell): string
    {
        $text = '';
        foreach ($cell->xpath('.//w:t') as $element) {
            $text .= (string) $element;
        }
        return $text;
    }

    public function extractText(SimpleXMLElement $text): string
    {
        return (string) $text;
    }

    public function extractSectionProperties(SimpleXMLElement $sectionProperties): array
    {
        return [];
    }

    public function extractRunProperties(SimpleXMLElement $runProperties): array
    {
        return [];
    }

    public function extractRun(SimpleXMLElement $run): string
    {
        $text = '';
        foreach ($run->xpath('.//w:t') as $element) {
            $text .= (string) $element;
        }
        return $text;
    }
}
