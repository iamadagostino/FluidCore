<?php

declare(strict_types=1);

namespace Fluid\Session;

use Fluid\Session\SessionStorage\SessionStorageInterface;
use Fluid\Session\Exception\SessionStorageInvalidArgumentException;

class SessionFactory
{
    /**
     * Main constructor method
     */
    public function __construct()
    {
    }

    /**
     * Factory method which creates the specified cache along with the
     * specified kind of Session Storage.
     * After creating the session, it will be registered at the Session Manager.
     *
     * @param string $sessionName
     * @param string $storageString
     * @return SessionInterface
     * @throws SessionStorageInvalidArgumentException
     */
    public function create(string $sessionName, string $storageString) : SessionInterface
    {
        $storageObject = new $storageString();

        if (!$storageObject instanceof SessionStorageInterface) {
            throw new SessionStorageInvalidArgumentException($storageString . 'is not a valid Session\'s storage object');
        }

        return new Session($sessionName, $storageObject);
    }
}
