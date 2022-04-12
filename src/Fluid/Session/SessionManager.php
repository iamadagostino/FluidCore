<?php

declare(strict_types=1);

namespace FLuid\Session;

use Fluid\Session\SessionFactory;
use Fluid\Session\Storage\NativeSessionStorage;

class SessionManager
{
    /**
     * Create an instance of the Session Factory and pass in the default
     * Session Storage that will fetch the Session name and array of options
     * from the core YAML's configuration files.
     *
     * @return void
     */
    public function initialize()
    {
        $factory = new SessionFactory();
        
        return $factory->create('', NativeSessionStorage::class, array());
    }
}
