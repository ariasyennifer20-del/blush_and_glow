<?php
// controllers/ProductoController.php

require_once __DIR__ . '/../models/ProductoModel.php';

class ProductoController {

    private $productoModel;

    public function __construct() {
        $this->productoModel = new ProductoModel();
    }

    // Muestra el catálogo completo de productos
    public function catalogo() {
        $productos = $this->productoModel->obtenerTodos();
        $categoriaSeleccionada = "Todos los Productos";
        $pageTitle = "Catálogo | Blush & Glow";

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/catalogo.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Filtra productos por ID de categoría
    public function categoria() {
        $categoriaId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($categoriaId > 0) {
            $productos = $this->productoModel->obtenerPorCategoria($categoriaId);
            
            // Asignar nombre según la categoría seleccionada
            if (!empty($productos)) {
                $categoriaSeleccionada = $productos[0]['categoria_nombre'];
            } else {
                $categoriaSeleccionada = "Categoría sin productos";
            }
        } else {
            // Si el ID es inválido, redirige al catálogo completo
            header("Location: index.php?c=producto&a=catalogo");
            exit();
        }

        $pageTitle = htmlspecialchars($categoriaSeleccionada) . " | Blush & Glow";

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/catalogo.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Vista de detalle de un producto individual
    public function detalle() {
        $productoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $producto = $this->productoModel->obtenerPorId($productoId);

        if (!$producto) {
            header("Location: index.php?c=producto&a=catalogo");
            exit();
        }

        $pageTitle = htmlspecialchars($producto['nombre']) . " | Blush & Glow";

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/detalle.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}