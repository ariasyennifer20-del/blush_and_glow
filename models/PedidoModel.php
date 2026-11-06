<?php
// models/PedidoModel.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/ReglasPedido.php';

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

    public function obtenerPorId($pedidoId) {
        $sql = "SELECT pedido_id, usuario_id, total, estado, direccion_envio, fecha_pedido
                FROM pedidos
                WHERE pedido_id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $pedidoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    // Actualizar el estado del pedido y ajustar el stock si se cancela o se reactiva
    public function actualizarEstado($pedidoId, $nuevoEstado) {
        if (!ReglasPedido::esEstadoValido($nuevoEstado)) {
            throw new InvalidArgumentException("Estado no válido.");
        }

        $this->db->beginTransaction();

        try {
            $stmtActual = $this->db->prepare("SELECT estado FROM pedidos WHERE pedido_id = :id FOR UPDATE");
            $stmtActual->execute([':id' => $pedidoId]);
            $estadoActual = $stmtActual->fetchColumn();

            if ($estadoActual === false) {
                throw new RuntimeException("El pedido no existe.");
            }

            $movimiento = ReglasPedido::movimientoStock($estadoActual, $nuevoEstado);
            if ($movimiento === 'devolver') {
                $this->ajustarStockDelPedido($pedidoId, 1);
            } elseif ($movimiento === 'descontar') {
                $this->ajustarStockDelPedido($pedidoId, -1);
            }

            $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado WHERE pedido_id = :id");
            $stmt->execute([
                ':estado' => $nuevoEstado,
                ':id' => $pedidoId
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function ajustarStockDelPedido($pedidoId, $signo) {
        $stmtLineas = $this->db->prepare(
            "SELECT producto_id, cantidad FROM detalle_pedidos WHERE pedido_id = :id"
        );
        $stmtLineas->execute([':id' => $pedidoId]);
        $lineas = $stmtLineas->fetchAll();

        if ($signo < 0) {
            $sql = "UPDATE productos SET stock = stock - :cantidad WHERE producto_id = :producto_id AND stock >= :cantidad";
        } else {
            $sql = "UPDATE productos SET stock = stock + :cantidad WHERE producto_id = :producto_id";
        }
        $stmt = $this->db->prepare($sql);

        foreach ($lineas as $linea) {
            $stmt->execute([
                ':cantidad' => (int) $linea['cantidad'],
                ':producto_id' => $linea['producto_id'],
            ]);
            if ($signo < 0 && $stmt->rowCount() !== 1) {
                throw new RuntimeException("No hay stock suficiente para reactivar el pedido.");
            }
        }
    }

}
?>