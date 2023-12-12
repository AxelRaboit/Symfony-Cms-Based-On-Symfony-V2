<?php

namespace App\Manager\Backend\Content\Menu;

use App\Entity\Menu;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MenuManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws Exception
     */
    public function menuCreate(Menu $menu): void
    {
        $this->save($menu);
    }

    public function menuDelete(Menu $menu): void
    {
        $this->remove($menu);
    }

    /**
     * @throws Exception
     */
    public function menuEdit(Menu $menu): void
    {
        $this->save($menu);
    }
}