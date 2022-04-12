<?php

declare(strict_types=1);

namespace Fluid\Session;

interface SessionInterface
{
    /**
     * Sets a specific value to a specific Session's key.
     *
     * @param string $key       The key of the item to store.
     * @param mixed $value      The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function set(string $key, mixed $value) : void;

    /**
     * Sets the specific value to a specific Session's array key.
     *
     * @param string $key       The key of the item to store.
     * @param mixed $value      The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function setArray(string $key, mixed $value) : void;

    /**
     * Gets / Returns the value of a specific Session's key.
     *
     * @param string $key       The key of the item to return.
     * @param mixed $default    The default value to return if the requested value can't be found.
     * @return mixed
     * @throws SessionInvalidArgumentException
     */
    public function get(string $key, mixed $default = null) : mixed;
  
    /**
     * Removes the value for the specified key from the session.
     *
     * @param string $key       The key of the item that will be unset.
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function delete(string $key) : bool;

    /**
     * Destroy the session along with session cookies.
     *
     * @return void
     */
    public function invalidate() : void;

    /**
     * Returns the requested value and remove it from the session.
     *
     * @param string $key       The key to retrieve and remove the value for.
     * @param mixed $default    The default value to return if the requested value can't be found.
     * @return void
     */
    public function flush(string $key, mixed $default = null) : void;

    /**
     * Determines whether an item is present in the session.
     *
     * @param string $key       The session item key.
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function has(string $key) : bool;
}
