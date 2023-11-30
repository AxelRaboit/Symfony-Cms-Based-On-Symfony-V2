<?php

namespace App\Service\Media;

use App\Entity\Image;
use App\Manager\Backend\Content\Media\MediaManager;

class MediaService
{
    public function __construct(private readonly MediaManager $mediaManager){}

    /**
     * @throws \Exception
     */
    public function prepareImageForUpload(Image $image):void
    {
        // TODO: Add logic to prepare image for upload like resize, crop, etc.

        $this->mediaManager->mediaImageCreate($image);
    }
}