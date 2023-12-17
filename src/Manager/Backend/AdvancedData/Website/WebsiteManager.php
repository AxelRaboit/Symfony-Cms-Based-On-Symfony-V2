<?php

namespace App\Manager\Backend\AdvancedData\Website;

use App\Entity\Website;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
    ) {
        parent::__construct($em);
    }

    public function websiteCreate(Website $website): void
    {
        $this->save($website);
    }

    public function websiteDelete(Website $website): void
    {
        $this->remove($website);
    }

    public function websiteEdit(Website $website): void
    {
        $this->save($website);
    }
}
