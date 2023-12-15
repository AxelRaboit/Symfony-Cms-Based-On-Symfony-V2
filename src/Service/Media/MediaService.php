<?php

namespace App\Service\Media;

use App\Entity\Image;
use App\Enum\MediaEnum;
use App\Manager\Backend\Content\Media\MediaManager;
use Symfony\Component\HttpFoundation\File\File;

class MediaService
{
    public function __construct(
        private readonly MediaManager $mediaManager,
        private readonly string $imageDirectoryNoSlash
    ) {
    }

    /**
     * @throws \Exception
     */
    public function prepareMediaForUpload(Image $image): bool
    {
        if (null === $image->getImageFile()) {
            throw new \Exception('No file provided');
        }

        $extension = $image->getImageFile()->guessExtension();

        if (in_array(
            $extension,
            [
                MediaEnum::MEDIA_EXTENSION_JPG,
                MediaEnum::MEDIA_EXTENSION_JPEG,
                MediaEnum::MEDIA_EXTENSION_PNG,
                MediaEnum::MEDIA_EXTENSION_WEBP,
            ]
        )) {
            $this->mediaManager->mediaImageCreate($image);

            return true;
        } elseif (MediaEnum::MEDIA_EXTENSION_ZIP == $extension) {
            $zip = new \ZipArchive();
            if (true === $zip->open($image->getImageFile()->getPathname())) {
                for ($i = 0; $i < $zip->numFiles; ++$i) {
                    /** @var string $filename */
                    $filename = $zip->getNameIndex($i);
                    $fileInfo = pathinfo($filename);

                    if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), [
                            MediaEnum::MEDIA_EXTENSION_JPG,
                            MediaEnum::MEDIA_EXTENSION_JPEG,
                            MediaEnum::MEDIA_EXTENSION_PNG,
                            MediaEnum::MEDIA_EXTENSION_WEBP,
                        ])) {
                        $tmpPath = $this->imageDirectoryNoSlash.'/'.MediaEnum::MEDIA_TEMP_DIRECTORY.'/'.$filename;
                        $zip->extractTo($this->imageDirectoryNoSlash.'/'.MediaEnum::MEDIA_TEMP_DIRECTORY.'/', $filename);

                        $finalUniqueName = uniqid().'.'.$fileInfo['extension'];
                        $finalPath = $this->imageDirectoryNoSlash.'/'.$finalUniqueName;

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
