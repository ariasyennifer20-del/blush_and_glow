<?php

require_once __DIR__ . '/../config/Database.php';

class CarritoModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function cargar($usuarioId): array
    {
        $carritoId = $this->buscarId($usuarioId);
        if (!$carritoId) {
            return [];
        }

        $sql = "SELECT ci.producto_id, ci.cantidad, p.nombre, p.precio, p.imagen_url
                FROM carrito_items ci
                INNER JOIN productos p ON p.producto_id = ci.producto_id
                WHERE ci.carrito_id = :carrito_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':carrito_id' => $carritoId]);

        $carrito = [];
        foreach ($stmt->fetchAll() as $fila) {
            $productoId = (int) $fila['producto_id'];
            $carrito[$productoId] = [
                'producto_id' => $productoId,
                'nombre' => $fila['nombre'],
                'precio' => $fila['precio'],
                'imagen_url' => $fila['imagen_url'],
                'cantidad' => (int) $fila['cantidad'],
            ];
        }

        return $carrito;
    }

    public function reemplazar($usuarioId, array $items): void
    {
        $this->db->beginTransaction();

        try {
            $carritoId = $this->asegurar($usuarioId);
            $borrar = $this->db->prepare("DELETE FROM carrito_items WHERE carrito_id = :carrito_id");
            $borrar->execute([':carrito_id' => $carritoId]);

            $insertar = $this->db->prepare(
                "INSERT INTO carrito_items (carrito_id, producto_id, cantidad)
                 VALUES (:carrito_id, :producto_id, :cantidad)"
            );

            foreach ($items as $item) {
                $productoId = (int) ($item['producto_id'] ?? 0);
                $cantidad = (int) ($item['cantidad'] ?? 0);
                if ($productoId < 1 || $cantidad < 1) {
                    continue;
                }
                $insertar->execute([
                    ':carrito_id' => $carritoId,
                    ':producto_id' => $productoId,
                    ':cantidad' => $cantidad,
                ]);
            }

            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function buscarId($usuarioId)
    {
        $stmt = $this->db->prepare("SELECT carrito_id FROM carritos WHERE usuario_id = :usuario_id LIMIT 1");
        $stmt->execute([':usuario_id' => $usuarioId]);
        $id = $stmt->fetchColumn();
        return $id ? (int) $id : null;
    }

    private function asegurar($usuarioId): int
    {
        $id = $this->buscarId($usuarioId);
        if ($id) {
            return $id;
        }

        $stmt = $this->db->prepare("INSERT INTO carritos (usuario_id) VALUES (:usuario_id)");
        $stmt->execute([':usuario_id' => $usuarioId]);
        return (int) $this->db->lastInsertId();
    }
}
