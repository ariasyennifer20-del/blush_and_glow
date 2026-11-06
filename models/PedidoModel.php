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
        $sql = "SELECT *
                FROM pedidos
                WHERE usuario_id = :usuario_id
                ORDER BY fecha_pedido DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles del pedido con productos y subtotales
    public function obtenerDetallePedido($pedidoId) {
        $sql = "SELECT
                    p.nombre AS producto,
                    p.imagen_url,
                    dp.precio_unitario,
                    dp.cantidad AS cantidad,
                    (dp.precio_unitario * dp.cantidad) AS subtotal
                FROM detalle_pedidos dp
                INNER JOIN productos p
                    ON p.producto_id = dp.producto_id
                WHERE dp.pedido_id = :id_pedido";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pedido' => $pedidoId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar un nuevo pedido
    public function crearPedido($usuarioId, $total, $direccionEnvio) {
        $sql = "INSERT INTO pedidos
                (usuario_id, total, estado, direccion_envio, fecha_pedido)
                VALUES
                (:usuario_id, :total, 'Pendiente', :direccion_envio, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':total' => $total,
            ':direccion_envio' => $direccionEnvio
        ]);

        return $this->db->lastInsertId();
    }

    // Registrar los productos y sus cantidades en el detalle del pedido
    public function registrarDetallePedido($pedidoId, $productoId, $precioUnitario, $cantidad) {
        $cantidad = (int) $cantidad;

        if ($cantidad < 1) {
            throw new InvalidArgumentException("La cantidad debe ser mayor a cero.");
        }

        $sql = "INSERT INTO detalle_pedidos
                (pedido_id, producto_id, precio_unitario, cantidad)
                VALUES
                (:pedido_id, :producto_id, :precio_unitario, :cantidad)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':pedido_id' => $pedidoId,
            ':producto_id' => $productoId,
            ':precio_unitario' => $precioUnitario,
            ':cantidad' => $cantidad
        ]);
    }

    // Obtener TODOS los pedidos para el administrador
    public function obtenerPedidosAdmin() {
        $sql = "SELECT
                    p.pedido_id,
                    COALESCE(u.nombre, 'Cliente Desconocido') AS cliente,
                    p.fecha_pedido,
                    p.total,
                    p.estado,
                    p.direccion_envio
                FROM pedidos p
                LEFT JOIN usuarios u
                    ON p.usuario_id = u.usuario_id
                ORDER BY p.fecha_pedido DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar el estado del pedido
    public function actualizarEstado($pedidoId, $nuevoEstado) {
        $sql = "UPDATE pedidos
                SET estado = :estado
                WHERE pedido_id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':estado' => $nuevoEstado,
            ':id' => $pedidoId
        ]);
    }

    // Eliminar un pedido y sus detalles asociados de forma segura
    public function eliminarPedido($pedidoId) {
        try {
            $this->db->beginTransaction();

            // Eliminar detalles del pedido
            $stmtDetalle = $this->db->prepare(
                "DELETE FROM detalle_pedidos WHERE pedido_id = ?"
            );
            $stmtDetalle->execute([$pedidoId]);

            // Eliminar pedido principal
            $stmtPedido = $this->db->prepare(
                "DELETE FROM pedidos WHERE pedido_id = ?"
            );
            $stmtPedido->execute([$pedidoId]);

            $this->db->commit();

            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw new Exception(
                "No se pudo eliminar el pedido: " . $e->getMessage()
            );
        }
    }
}
?>