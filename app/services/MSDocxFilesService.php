<?php

namespace App\Services;

use App\Lib\Msdocx\Reader;

class MSDocxFilesService
{
    public function readDocxFile(string $path): array
    {
        $reader = new Reader(new \ZipArchive(), $path);
        return $reader->readDocxFile();
    }

}
