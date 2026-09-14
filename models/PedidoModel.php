<?php
// models/PedidoModel.php

require_once __DIR__ . '/../config/Database.php';

class PedidoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Obtener pedidos por ID de usuario
    public function obtenerPorUsuarioId($usuarioId) {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }

    // Obtener detalles del pedido
    public function obtenerDetallePedido($pedidoId) {
        $sql = "SELECT dp.*, p.nombre, p.imagen_url 
                FROM detalle_pedidos dp
                JOIN productos p ON dp.producto_id = p.producto_id
                WHERE dp.pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pedido_id' => $pedidoId]);
        return $stmt->fetchAll();
    }
}