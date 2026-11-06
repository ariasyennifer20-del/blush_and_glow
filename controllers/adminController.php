<?php
// controllers/AdminController.php

require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ReglasPedido.php';

class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario tenga rol de administrador
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }
    }

    // Panel principal / Dashboard
    public function index() {
        $pageTitle = "Panel de Administración | Blush & Glow";
        
        // 2. Instanciar el modelo y obtener todos los pedidos de los clientes
        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->obtenerPedidosAdmin(); // Esta función trae los pedidos con el nombre del cliente

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function estado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=admin&a=index");
            exit();
        }

        $pedidoId = (int) ($_POST['pedido_id'] ?? 0);
        $estado = $_POST['estado'] ?? '';

        try {
            if ($pedidoId < 1 || !ReglasPedido::esEstadoValido($estado)) {
                throw new InvalidArgumentException("Estado no válido.");
            }
            (new PedidoModel())->actualizarEstado($pedidoId, $estado);
        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header("Location: index.php?c=admin&a=index");
        exit();
    }

    // Vista del Plan de Administración
    public function plan() {
        $pageTitle = "Plan de Administración | Blush & Glow";
        
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/administracion.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}