<?php

declare(strict_types=1);

namespace Fluid\Session\SessionStorage;

interface SessionStorageInterface
{
    /**
     * Wrapper for session_name with explicit argument to set a session_name.
     *
     * @param string $sessionName
     * @return void
     */
    public function setSessionName(string $sessionName) : void;

    /**
     * Wrapper for session_name that returns the Session's name.
     *
     * @return string
     */
    public function getSessionName() : string;

    /**
     * Wrapper for session_id with explicit argument to set a session_id.
     *
     * @param string $sessionID
     * @return void
     */
    public function setSessionID(string $sessionID) : void;

    /**
     * Wrapper for session_id that returns the Session's ID.
     *
     * @return string
     */
    public function getSessionID() : string;

    /**
     * Sets a specific value to a specific Session's key.
     *
     * @param string $key       The key of the item to store.
     * @param mixed $value      The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionException
     */
    public function setSession(string $key, mixed $value) : void;

    /**
     * Sets the specific value to a specific Session's array key.
     *
     * @param string $key       The key of the item to store.
     * @param mixed $value      The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function setSessionArray(string $key, mixed $value) : void;

    /**
     * Gets / Returns the value of a specific Session's key.
     *
     * @param string $key       The key of the item to return.
     * @param mixed $default    The default value to return if the requested value can't be found.
     * @return mixed
     * @throws SessionInvalidArgumentException
     */
    public function getSession(string $key, mixed $default = null) : mixed;

    /**
     * Removes the value for the specified key from the session.
     *
     * @param string $key       The key of the item that will be unset.
     * @return void
     */
    public function deleteSession(string $key) : void;

    /**
     * Destroy the session along with session cookies.
     *
     * @return void
     */
    public function invalidateSession() : void;

    /**
     * Returns the requested value and remove it from the session.
     *
     * @param string $key       The key to retrieve and remove the value for.
     * @param mixed $default    The default value to return if the requested value can't be found.
     * @return mixed
     */
    public function flushSession(string $key, mixed $default = null) : mixed;

    /**
     * Determines whether an item is present in the session.
     *
     * @param string $key       The session item key.
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function hasSession(string $key) : bool;
}
