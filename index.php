<?php
// C:\xampp\htdocs\blush_and_glow\index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Obtener parámetros de la URL
$controller = isset($_GET['c']) ? $_GET['c'] : 'home';
$action     = isset($_GET['a']) ? $_GET['a'] : 'index';

// 2. Formatear el nombre de la clase (Ej: adminProducto -> AdminProductoController)
$controllerClass = ucfirst($controller) . 'Controller';

// 3. Definir exactamente la variable $controllerPath que busca tu código
$controllerPath = __DIR__ . '/controllers/' . $controllerClass . '.php';

// 4. Verificar existencia del archivo
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    if (class_exists($controllerClass)) {
        $object = new $controllerClass();
        
        if (method_exists($object, $action)) {
            $object->$action();
        } else {
            echo "<h1>404 - Acción no encontrada</h1>";
            echo "<p>El método '$action' no existe en el controlador '$controllerClass'.</p>";
        }
    } else {
        echo "<h1>404 - Clase no encontrada</h1>";
        echo "<p>La clase '$controllerClass' no existe dentro del archivo.</p>";
    }
} else {
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p>No se encontró el controlador en: <code>" . htmlspecialchars($controllerPath) . "</code></p>";
}