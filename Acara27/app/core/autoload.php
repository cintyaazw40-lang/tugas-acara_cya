
<?php

spl_autoload_register(function ($class) {
    $folders = [
        'config',
        'core',
        'models',
        'controllers'
    ];

    foreach ($folders as $folder) {
        $path = __DIR__ . '/../' . $folder . '/' . $class . '.php';

        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});
