<?php
// controllers/ClienteController.php

require_once __DIR__ . '/../models/PedidoModel.php';

class ClienteController {

    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }

        // Se verifica si la clave en la sesión es 'id' o 'usuario_id'
        $usuarioId = $_SESSION['usuario']['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;

        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->obtenerPorUsuarioId($usuarioId);

        $pageTitle = "Mis Pedidos | Blush & Glow";
        
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/cliente/perfil.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}