<?php

class AutoLoader {
    public static function load($class) {
        foreach (NAMESPACE_LIST as $key => $value) {
            $namespace = $value;
            $prefix = $namespace . '\\';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // Another namespace.
                continue;
            }

            $class_name = substr($class, $len);

            $file = rtrim(__DIR__, DS) . LIB_BASE_DIR . $namespace . DS . strtr($class_name, '\\', DS) . '.php';

            if (is_file($file)) {
                require $file;
            }
        }
    }
}

define('DS', DIRECTORY_SEPARATOR);
define('LIB_BASE_DIR', DS);
define('NAMESPACE_LIST', array('Coroutine', 'Server'));

spl_autoload_register(array('AutoLoader', 'load'));

// end of script
