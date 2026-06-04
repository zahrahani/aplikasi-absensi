<?php 
/**
 * =====================================================
 * HELPER FUNCTIONS - REGISTER CLASS
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class);
    $file = ROOT_PATH . $path . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        die("File tidak ditemukan: " . $file);
    }
});


// if( str_contains(CONTROLLERS_PATH, $folder) ) {
// 		$class = end($path);
// 		require_once CONTROLLERS_PATH . '/' . $class . '.php';
// 	}