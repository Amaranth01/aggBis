<?php

namespace App\Service;

use Doctrine\DBAL\Driver\OCI8\Exception\Error;

class PlaceholderImageService
{
    private string $saveDirectory;
    private FilenameGenerator $generator;
    private string $placeholderServiceProviderUrl = "https://placehold.co/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;

    public function __construct(FilenameGenerator $generator, string $saveDirectory)
    {
        $this->generator = $generator;
        $this->saveDirectory = $saveDirectory;
    }

    /**
     * Return the downloaded image contents
     * @param int $imageWidth
     * @param int $imageHeight
     * @return string
     * @throws Error
     */
    public function getNewImageStream(int $imageWidth, int $imageHeight): string {
        if($imageWidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("The requested image format is to small, please provide us a larder format");
        }
        $contents = file_get_contents("{$this->placeholderServiceProviderUrl}/{$imageWidth}x{$imageHeight}");
        if(!$contents) {
            throw new \Error("Placeholder image cannot be downloaded");
        }
        return $contents;
    }

    /**
     * @param int $imageWidth
     * @param int $imageHeight
     * @param string $filename
     * @return bool
     * @throws Error
     */
    public function getNewImageAdSave(int $imageWidth, int $imageHeight, string $filename) : bool {
        $file = __DIR__ . "/../../uploads/$filename";
        $contents = $this->getNewImageStream($imageWidth, $imageHeight);
        $bytes = file_put_contents($file, $contents);
        return file_exists($file) && $bytes;
    }
}