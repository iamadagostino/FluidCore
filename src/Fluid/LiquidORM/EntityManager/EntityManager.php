<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\EntityManager;

use Fluid\LiquidORM\EntityManager\CRUDInterface;

class EntityManager implements EntityManagerInterface
{

  /**
   * @var CRUDInterface
   */
    protected CRUDInterface $crud;
    /**
     * Main contructor class
     *
     * @return void
     */
    public function __construct(CRUDInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     */
    public function getCRUD(): object
    {
        return $this->crud;
    }
}
