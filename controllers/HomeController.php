<?php
// controllers/HomeController.php

require_once __DIR__ . '/../models/ProductoModel.php';

class HomeController {

    public function index() {
        // Instanciar el modelo y obtener productos destacados
        $productoModel = new ProductoModel();
        $productosDestacados = $productoModel->obtenerDestacados();

        // Título dinámico para la cabecera
        $pageTitle = "Inicio | Blush & Glow";

        // Carga de vistas (Layout)
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/home/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}