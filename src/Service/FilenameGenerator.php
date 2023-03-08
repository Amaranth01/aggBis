<?php

namespace App\Service;

class FilenameGenerator
{
    public function getUniqFilename(): string {
        return uniqid() . ".png";
    }
}