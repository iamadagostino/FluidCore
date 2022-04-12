<?php

declare(strict_types=1);

namespace Fluid\Session;

use Throwable;
use Fluid\Session\SessionInterface;
use Fluid\Session\Exception\SessionException;
use Fluid\Session\SessionStorage\SessionStorageInterface;
use Fluid\Session\Exception\SessionInvalidArgumentException;

class Session implements SessionInterface
{
    /**
     * @var SessionStorageInterface
     */
    protected SessionStorageInterface $storage;

    /**
     * @var string
     */
    protected string $sessionName;

    /**
     * @var const
     */
    protected const SESSION_REGEX = '/^[a-zA-Z0-9_\.]{1,64}$/';

    /**
     * Checks whether the Session's key is valid according to the defined regular expression
     *
     * @param string $key
     * @return boolean
     */
    protected function isSessionKeyValid(string $key) : bool
    {
        return (preg_match((string)self::SESSION_REGEX, $key) === 1);
    }

    /**
     * Checks whether the Session's key is valid
     *
     * @param string $key
     * @return void
     */
    protected function ensureSessionKeyIsValid(string $key) : void
    {
        if ($this->isSessionKeyValid($key) === false) {
            throw new SessionInvalidArgumentException($key . ' is not a valid Session\'s key.');
        }
    }

    /**
     * Main constructor method
     *
     * @param string $sessionName
     * @param SessionStorageInterface|null $storage
     * @throws SessionInvalidArgumentException
     */
    public function __construct(string $sessionName, SessionStorageInterface $storage = null)
    {
        if ($this->isSessionKeyValid($sessionName) === false) {
            throw new SessionInvalidArgumentException($sessionName . ' is not a valid Session name.');
        }

        $this->sessionName = $sessionName;
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws SessionInvalidArgumentException
     * @throws SessionException
     */
    public function set(string $key, mixed $value): void
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->setSession($key, $value);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the Session\'s storage. ' . $throwable);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws SessionInvalidArgumentException
     * @throws SessionException
     */
    public function setArray(string $key, mixed $value): void
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->setSessionArray($key, $value);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the Session\'s storage. '  . $throwable);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws SessionInvalidArgumentException
     * @throws SessionException
     */
    public function get(string $key, mixed $default = null) : mixed
    {
        $this->ensureSessionKeyIsValid($key);
      
        try {
            return $this->storage->getSession($key, $default);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the Session\'s storage. '  . $throwable);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return boolean
     * @throws SessionInvalidArgumentException
     * @throws SessionException
     */
    public function delete(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            return $this->storage->deleteSession($key);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the Session\'s storage. '  . $throwable);
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function invalidate(): void
    {
        $this->storage->invalidateSession();
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return void
     * @throws SessionInvalidArgumentException
     * @throws SessionException
     */
    public function flush(string $key, mixed $default = null) : void
    {
        $this->ensureSessionKeyIsValid($key);
      
        try {
            $this->storage->flushSession($key, $default);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the Session\'s storage. '  . $throwable);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function has(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
      
        return $this->storage->hasSession($key);
    }
}
