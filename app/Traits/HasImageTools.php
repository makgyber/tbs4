<?php

namespace App\Traits;

use File;
use Image;
use Storage;

trait HasImageTools
{
    public function bulkImageReplicator(array $imageList): array
    {
        $newImageList = [];
        foreach ($imageList as $image) {
            $newImageList[] = $this->imageReplicator($image);
        }
        return $newImageList;
    }

    public function imageReplicator(array $media): array
    {
        $newFileName = str_replace(".webp", "-1.webp", $media['file']);
        Storage::copy('public/'.$media['file'], 'public/'.$newFileName);
        $media['file'] = $newFileName;
        return $media;
    }

    public function rotate(string $filePath, int $angle = -90): void
    {
        $fullFilePath = Storage::path('public/'.$filePath);
        logger('fullFilePath: '.$fullFilePath);
        $cropFilePath = str_replace('.webp', '-300x_.webp', $fullFilePath);
        logger('cropFilePath: '.$cropFilePath);
        $originalImage = Image::make($fullFilePath);
        $originalImage->rotate($angle)->save($fullFilePath);
        if (File::exists($cropFilePath)) {
            $cropImage = Image::make($cropFilePath);
            $cropImage->rotate($angle)->save($cropFilePath);
        }
    }
}
