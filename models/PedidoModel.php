<?php
// models/PedidoModel.php

require_once __DIR__ . '/../config/Database.php';

class PedidoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Obtener pedidos por ID de usuario (Para el cliente en su perfil)
    public function obtenerPorUsuarioId($usuarioId) {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }

    // Obtener detalles del pedido (Productos que compró)
    public function obtenerDetallePedido($pedidoId) {
        $sql = "SELECT dp.*, p.nombre, p.imagen_url 
                FROM detalle_pedidos dp
                JOIN productos p ON dp.producto_id = p.producto_id
                WHERE dp.pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pedido_id' => $pedidoId]);
        return $stmt->fetchAll();
    }

    // NUEVO: Registrar un nuevo pedido realizado por un usuario normal
    public function crearPedido($usuarioId, $total, $direccionEnvio) {
        $sql = "INSERT INTO pedidos (usuario_id, total, estado, direccion_envio, fecha_pedido) 
                VALUES (:usuario_id, :total, 'Pendiente', :direccion_envio, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':total' => $total,
            ':direccion_envio' => $direccionEnvio
        ]);
        
        // Retorna el ID del pedido recién creado para usarlo en detalle_pedidos
        return $this->db->lastInsertId();
    }

    // NUEVO: Obtener TODOS los pedidos para que el Administrador los vea en su panel
    public function obtenerPedidosAdmin() {
        $sql = "SELECT p.pedido_id, u.nombre AS cliente, p.fecha_pedido, p.total, p.estado, p.direccion_envio 
                FROM pedidos p 
                JOIN usuarios u ON p.usuario_id = u.usuario_id 
                ORDER BY p.fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>