<?php

namespace App\Lib\Msdocx;



class Cursor
{
    private array $content = [];

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTables()
    {
        $tables = [];
        foreach ($this->content as $element) {
            if (isset($element['table'])) {
                $tables[] = $element['table'];
            }
        }
        return $tables;
    }

    public function getRows()
    {
        $rows = [];
        foreach ($this->content as $element) {
            if (isset($element['tableRow'])) {
                $rows[] = $element['tableRow'];
            }
        }
        return $rows;
    }

    public function getCells()
    {
        $cells = [];
        /**
         * $ element = [0 => [0 => ["tableCell" => "cell" ] ] ]
         */

        foreach ($this->content as $element) {
            $cells[] = $element;
        }


        return $cells;
    }
}
