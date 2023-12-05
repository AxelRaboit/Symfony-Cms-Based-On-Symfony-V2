<?php

namespace App\Service\Media;

use App\Entity\Image;
use App\Enum\MediaEnum;
use App\Manager\Backend\Content\Media\MediaManager;
use Symfony\Component\HttpFoundation\File\File;
use ZipArchive;

class MediaService
{
    public function __construct(
        private readonly MediaManager $mediaManager,
        private readonly string       $imageDirectoryNoSlash
    ){}


    /**
     * @throws \Exception
     */
    public function prepareMediaForUpload(Image $image): bool
    {
        $extension = $image->getImageFile()->guessExtension();

        if (in_array($extension, [
                MediaEnum::MEDIA_EXTENSION_JPG,
                MediaEnum::MEDIA_EXTENSION_JPEG,
                MediaEnum::MEDIA_EXTENSION_PNG,
                MediaEnum::MEDIA_EXTENSION_WEBP,
            ]
        )) {
            $this->mediaManager->mediaImageCreate($image);

            return true;

        } elseif ($extension == MediaEnum::MEDIA_EXTENSION_ZIP) {
            $zip = new ZipArchive();
            if ($zip->open($image->getImageFile()->getPathname()) === TRUE) {
                // Loop through the files inside the zip
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $fileinfo = pathinfo($filename);

                    if (in_array(strtolower($fileinfo['extension']), [
                        MediaEnum::MEDIA_EXTENSION_JPG,
                        MediaEnum::MEDIA_EXTENSION_JPEG,
                        MediaEnum::MEDIA_EXTENSION_PNG,
                        MediaEnum::MEDIA_EXTENSION_WEBP,
                    ])) {
                        // Extract file
                        $tmpPath = $this->imageDirectoryNoSlash . '/' . MediaEnum::MEDIA_TEMP_DIRECTORY . '/' . $filename;
                        $zip->extractTo($this->imageDirectoryNoSlash . '/' . MediaEnum::MEDIA_TEMP_DIRECTORY . '/', $filename);

                        // Final path for the file with the unique name
                        $finalUniqueName = uniqid() . '.' . $fileinfo['extension'];
                        $finalPath = $this->imageDirectoryNoSlash . '/' . $finalUniqueName;

                        rename($tmpPath, $finalPath);
                        $file = new File($finalPath);
                        $newImage = new Image();
                        $newImage->setImageFile($file);
                        $newImage->setName($finalUniqueName);

                        $this->mediaManager->mediaImageCreate($newImage);
                    }
                }
                $zip->close();
            }

            return true;

        } else {
            return false;
        }
    }
}