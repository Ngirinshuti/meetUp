<?php 

class Session
{
    // seen keys session key
    protected static string $seen_key = "session.seen";

    /**
     * Initialize session
     *
     * @return void
     */
    public static function init()
    {
        Session::set(Session::$seen_key, []);
    }

    public static function clearSeen()
    {
    }

    /**
     * Check if session key is set
     *
     * @param string $key session key
     * 
     * @return boolean
     */
    public static function has(string $key):bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Get a session key and set as seen
     *
     * @param string $key         session key
     * @param mixed|null $default default value
     * 
     * @return mixed
     */
    public static function see(string $key, mixed $default = null):mixed
    {
        $value = Session::get($key, $default);
        Session::remove($key);
        return $value;
    }

    /**
     * Get session key value
     *
     * @param string $key         key
     * @param mixed|null $default default value
     * 
     * @return mixed
     */
    public static function get(string $key, mixed $default=null):mixed
    {
        return Session::has($key) ? $_SESSION[$key] : $default;
    }

    /**
     * Set session key & value
     *
     * @param string $key  session key
     * @param mixed $value session value
     * 
     * @return void
     */
    public static function set(string $key, mixed $value):void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset session key
     *
     * @param string $key key
     * 
     * @return void
     */
    public static function remove(string $key):void
    {
        if (Session::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function clear():void
    {
        session_unset();
        session_destroy();
    }
}