<?php
// controllers/CarritoController.php

require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/ValidadorCantidad.php';

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
        $mensajes = [];

        if (!empty($_SESSION['flash_error'])) {
            $mensajes[] = $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
        }

        $productoModel = new ProductoModel();
        foreach ($carrito as $id => $item) {
            $producto = $productoModel->obtenerPorId($item['producto_id']);
            $nombre = $item['nombre'] ?? 'Producto';

            if (!$producto) {
                $mensajes[] = '"' . $nombre . '" ya no está disponible.';
                $carrito[$id]['stock_actual'] = 0;
                continue;
            }

            $carrito[$id]['stock_actual'] = (int) $producto['stock'];
            $alerta = ValidadorCantidad::mensaje($item['cantidad'], $producto['stock'], $producto['nombre']);
            if ($alerta !== null && !in_array($alerta, $mensajes, true)) {
                $mensajes[] = $alerta;
            }
        }

        $total = $this->calcularTotal();
        $pageTitle = "Carrito de Compras | Blush & Glow";

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/carrito/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Agregar un producto al carrito
    public function agregar() {
        $productoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

        if ($productoId > 0) {
            $productoModel = new ProductoModel();
            $producto = $productoModel->obtenerPorId($productoId);

            if ($producto) {
                $actual = $_SESSION['carrito'][$productoId]['cantidad'] ?? 0;
                $resultado = ValidadorCantidad::sumar(
                    $actual,
                    $cantidad,
                    $producto['stock'],
                    $producto['nombre']
                );

                if ($resultado['error'] !== null) {
                    $_SESSION['flash_error'] = $resultado['error'];
                } else {
                    $_SESSION['carrito'][$productoId] = [
                        'producto_id' => $producto['producto_id'],
                        'nombre'      => $producto['nombre'],
                        'precio'      => $producto['precio'],
                        'imagen_url'  => $producto['imagen_url'],
                        'cantidad'    => $resultado['cantidad']
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
            $nuevaCantidad = $_POST['cantidad'] ?? 1;

            if ($productoId > 0 && isset($_SESSION['carrito'][$productoId])) {
                if (ValidadorCantidad::entero($nuevaCantidad) < 1) {
                    unset($_SESSION['carrito'][$productoId]);
                } else {
                    $productoModel = new ProductoModel();
                    $producto = $productoModel->obtenerPorId($productoId);
                    $nombre = $_SESSION['carrito'][$productoId]['nombre'] ?? 'Producto';

                    if (!$producto) {
                        $_SESSION['flash_error'] = '"' . $nombre . '" ya no está disponible.';
                    } else {
                        $resultado = ValidadorCantidad::fijar(
                            $nuevaCantidad,
                            $producto['stock'],
                            $producto['nombre']
                        );

                        if ($resultado['error'] !== null) {
                            $_SESSION['flash_error'] = $resultado['error'];
                        } else {
                            $_SESSION['carrito'][$productoId]['cantidad'] = $resultado['cantidad'];
                        }
                    }
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