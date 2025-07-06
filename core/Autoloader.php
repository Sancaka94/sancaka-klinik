<?php
// File: core/Autoloader.php

class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {
            // Tentukan path ke direktori-direktori class Anda
            $paths = [
                BASE_PATH . '/app/controllers/',
                BASE_PATH . '/app/models/',
                BASE_PATH . '/core/'
            ];

            foreach ($paths as $path) {
                $file = $path . $class . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }
}
