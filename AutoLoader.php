<?php

class AutoLoader {
    public static function load($class) {
        $prefix = 'Tsa\\';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Another namespace.
            return;
        }

        $class_name = substr($class, $len);

        $file = rtrim(__DIR__, DS) . DS . strtr($class_name, '\\', DS) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
}

spl_autoload_register(array('AutoLoader', 'load'));

// end of script
