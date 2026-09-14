<?php
// controllers/CarritoController.php

require_once __DIR__ . '/../models/ProductoModel.php';

class CarritoController {

    public function __construct() {
        // Inicializar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Estructura base del carrito en sesión
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Vista principal del carrito de compras
    public function ver() {
        $carrito = $_SESSION['carrito'];
        $total = $this->calcularTotal();
        $pageTitle = "Carrito de Compras | Blush & Glow";

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/carrito/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Agregar un producto al carrito
    public function agregar() {
        $productoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($productoId > 0) {
            $productoModel = new ProductoModel();
            $producto = $productoModel->obtenerPorId($productoId);

            if ($producto) {
                // Si el producto ya existe en el carrito, se suma la cantidad
                if (isset($_SESSION['carrito'][$productoId])) {
                    $_SESSION['carrito'][$productoId]['cantidad'] += $cantidad;
                } else {
                    // Si es nuevo, se agrega con sus datos básicos
                    $_SESSION['carrito'][$productoId] = [
                        'producto_id' => $producto['producto_id'],
                        'nombre'      => $producto['nombre'],
                        'precio'      => $producto['precio'],
                        'imagen_url'  => $producto['imagen_url'],
                        'cantidad'    => $cantidad
                    ];
                }
            }
        }

        // Redireccionar a la vista del carrito
        header("Location: index.php?c=carrito&a=ver");
        exit();
    }

    // Actualizar cantidad de un elemento del carrito
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $nuevaCantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

            if ($productoId > 0 && isset($_SESSION['carrito'][$productoId])) {
                if ($nuevaCantidad > 0) {
                    $_SESSION['carrito'][$productoId]['cantidad'] = $nuevaCantidad;
                } else {
                    // Si la cantidad es 0 o menor, se remueve el producto
                    unset($_SESSION['carrito'][$productoId]);
                }
            }
        }

        header("Location: index.php?c=carrito&a=ver");
        exit();
    }

    // Eliminar un producto del carrito
    public function eliminar() {
        $productoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($productoId > 0 && isset($_SESSION['carrito'][$productoId])) {
            unset($_SESSION['carrito'][$productoId]);
        }

        header("Location: index.php?c=carrito&a=ver");
        exit();
    }

    // Vaciar todo el carrito
    public function vaciar() {
        $_SESSION['carrito'] = [];
        header("Location: index.php?c=carrito&a=ver");
        exit();
    }

    // Método auxiliar para calcular el precio total del carrito
    private function calcularTotal() {
        $total = 0.00;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
}