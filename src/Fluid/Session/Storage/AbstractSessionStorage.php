<?php

declare(strict_types=1);

namespace Fluid\Session\Storage;

use Fluid\Session\SessionStorage\SessionStorageInterface;

abstract class AbstractSessionStorage implements SessionStorageInterface
{
    /**
     * @var array
     */
    protected array $options = [];

    /**
     * Main abstract constructor method.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->iniSet();

        // Destroy any existing session started with session.auto_start.
        if ($this->isSessionStarted()) {
            session_unset();
            session_destroy();
        }

        $this->start();
    }

    /**
     * Sets the name of the session.
     *
     * @param string $sessionName
     * @return void
     */
    public function setSessionName(string $sessionName) : void
    {
        session_name($sessionName);
    }

    /**
     * Returns the current name of the session.
     *
     * @return string
     */
    public function getSessionName() : string
    {
        return session_name();
    }

    /**
     * Sets the ID of the session.
     *
     * @param string $sessionID
     * @return void
     */
    public function setSessionID(string $sessionID) : void
    {
        session_id($sessionID);
    }

    /**
     * Returns the current ID of the session.
     *
     * @return string
     */
    public function getSessionID() : string
    {
        return session_id();
    }

    /**
     * Sets PHP INI settings
     *
     * @return void
     */
    public function iniSet() : void
    {
        ini_set('session.gc_maxlifetime', $this->options['gc_maxlifetime']);
        ini_set('session.gc_divisor', $this->options['gc_divisor']);
        ini_set('session.gc_probability', $this->options['gc_probability']);
        ini_set('session.cookie_lifetime', $this->options['cookie_lifetime']);
        ini_set('session.use_cookies', $this->options['use_cookies']);
    }

    /**
     * Prevent session within the cli, even though sessions can't run within
     * the command line. Also, checking if is active a Session's ID and it's
     * not empty, else return false.
     *
     * @return boolean
     */
    public function isSessionStarted()
    {
        return php_sapi_name() !== 'cli' ? $this->getSessionID !== '' : false;
    }

    /**
     * Start the session if there isn't any other PHP session already started.
     *
     * @return void
     */
    public function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Define the session_set_cookie_params method via the $this->options
     * parameters which will be defined within the core Config directory.
     *
     * @return void
     */
    public function start()
    {
        $this->setSessionName($this->options['session_name']);
        $domain = (isset($this->options['domain']) ? $this->options['domain'] : isset($_SERVER['SERVER_NAME']));
        $secure = (isset($this->options['secure']) ? $this->options['secure'] : isset($_SERVER['HTTPS']));

        session_set_cookie_params($this->options['lifetime'], $this->options['path'], $domain, $secure, $this->options['httponly']);

        $this->startSession();
    }
}
