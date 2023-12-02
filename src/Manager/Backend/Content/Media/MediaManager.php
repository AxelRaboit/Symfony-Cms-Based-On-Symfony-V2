<?php

namespace App\Manager\Backend\Content\Media;

use App\Entity\Image;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;

class MediaManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws \Exception
     */
    public function mediaImageCreate(Image $image): void
    {
        $this->save($image);
    }

    public function mediaImageDelete(Image $image): void
    {
        $this->remove($image);
    }
}