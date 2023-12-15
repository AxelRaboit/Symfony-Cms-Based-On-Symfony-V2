<?php

namespace App\Service\UserBackend\Information;

readonly class UserBackendInformationService
{
    public function __construct(
        private string $pictureProfileDirectoryNoSlash
    ) {
    }

    public function userBackendInformationPictureProfileFileDelete(string $pictureProfileName): void
    {
        $pictureProfilePath = $this->pictureProfileDirectoryNoSlash.'/'.$pictureProfileName;

        if (file_exists($pictureProfilePath)) {
            unlink($pictureProfilePath);
        }
    }
}
