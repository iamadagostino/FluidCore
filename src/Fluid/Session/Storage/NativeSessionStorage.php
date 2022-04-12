<?php

declare(strict_types=1);

namespace Fluid\Session\Storage;

class NativeSessionStorage extends AbstractSessionStorage
{
    /**
     * Main constructor method.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSession(string $key, mixed $value) : void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSessionArray(string $key, mixed $value) : void
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSession(string $key, mixed $default = null) : mixed
    {
        if ($this->hasSession($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return void
     */
    public function deleteSession(string $key) : void
    {
        if ($this->hasSession($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function invalidateSession() : void
    {
        $_SESSION = array();

        if (ini_set('session.use_cookies', 1)) {
            $params = session_get_cookie_params();
            setcookie(
                $this->getSessionName(),
                '',
                time() - $params['lifetime'],
                $params['path'],
                $params['domain'],
                $params['httponly']
            );

            session_unset();
            session_destroy();
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function flushSession(string $key, mixed $default = null) : mixed
    {
        if ($this->hasSession($key)) {
            $value = $_SESSION[$key];
            $this->deleteSession($key);

            return $value;
        }

        return $default;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return boolean
     */
    public function hasSession(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }
}
