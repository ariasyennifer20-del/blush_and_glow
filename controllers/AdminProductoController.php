<?php
// controllers/AdminProductoController.php

require_once __DIR__ . '/../models/ProductoModel.php';

class AdminProductoController {
    private $productoModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario sea administrador
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }
        $this->productoModel = new ProductoModel();
    }

    public function index() {
        $productos = $this->productoModel->obtenerTodos();
        $pageTitle = "Gestión de Productos | Blush & Glow";
        
        // Carga de vistas con rutas absolutas
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/index.php'; // o productos_index.php según el nombre de tu vista
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Crear un nuevo producto
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre      = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio      = (float)$_POST['precio'];
            $stock       = (int)$_POST['stock'];
            $categoriaId = (int)$_POST['categoria_id'];
            $imagenUrl   = trim($_POST['imagen_url']) ?: 'default.jpg';
            $esDestacado = isset($_POST['es_destacado']) ? 1 : 0;

            $this->productoModel->crear($nombre, $descripcion, $precio, $stock, $categoriaId, $imagenUrl, $esDestacado);
            header("Location: index.php?c=adminProducto&a=index");
            exit();
        }

        $pageTitle = "Nuevo Producto | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/form.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Editar un producto existente
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            header("Location: index.php?c=adminProducto&a=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre      = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio      = (float)$_POST['precio'];
            $stock       = (int)$_POST['stock'];
            $categoriaId = (int)$_POST['categoria_id'];
            $imagenUrl   = trim($_POST['imagen_url']);
            $esDestacado = isset($_POST['es_destacado']) ? 1 : 0;

            $this->productoModel->actualizar($id, $nombre, $descripcion, $precio, $stock, $categoriaId, $imagenUrl, $esDestacado);
            header("Location: index.php?c=adminProducto&a=index");
            exit();
        }

        $pageTitle = "Editar Producto | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/form.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Eliminar producto
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->productoModel->eliminar($id);
        }
        header("Location: index.php?c=adminProducto&a=index");
        exit();
    }
}