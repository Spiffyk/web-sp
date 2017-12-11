<?php
class Mem {

    private static $memcached;

    /**
     * @return Memcached|null a {@link Memcached} instance if the <code>memcached</code> module is loaded, otherwise
     * <code>null</code>
     */
    public static function get(): ?Memcached {
        global $memcache_enabled;
        if (!$memcache_enabled || !extension_loaded("memcached")) {
            return null;
        }

        if (Mem::$memcached == null) {
            Mem::$memcached = new Memcached();
            Mem::$memcached->addServer("127.0.0.1", 11211);
        }

        return Mem::$memcached;
    }

    /**
     * If caching is enabled and the <code>memcached</code> PHP extension is available, calls the <code>$if</code>
     * function and passes it the managed {@link Memcached} instance. Otherwise, it calls the <code>$else</code>
     * function (if provided) with no parameters. It returns whatever the called function has returns
     * or <code>null</code> if nothing is returned.
     *
     * @param callable $if the function to call if caching <b>is</b> available
     * @param callable|null $else the function to call if caching <b>is not</b> available
     * @return mixed|null the return value of the called function or <code>null</code> if nothing is returned
     */
    public static function ifCached(callable $if, ?callable $else = null): ?mixed {
        $memcached = Mem::get();
        if ($memcached == null) {
            if ($else == null) {
                return null;
            }
            $retval = $else();
            return isset($retval) ? $retval : null;
        }

        $retval = $if($memcached);
        return isset($retval) ? $retval : null;
    }

    /**
     * Creates a key for use with {@link Memcached}.
     *
     * @param string $key custom string
     * @return string a key in the format "<code>efphp_[database prefix][$key]</code>"
     */
    public static function key(string $key): string {
        global $db_prefix;
        return "efphp_" . $db_prefix . $key;
    }
}