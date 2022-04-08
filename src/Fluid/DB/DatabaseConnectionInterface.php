<?php
declare(strict_types=1);

namespace Fluid\DB;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Create a new DB connection
     *
     * @return PDO
     */
    public function open() : PDO;

    /**
     * Close DB connection
     *
     * @return void
     */
    public function close() : void;
}
