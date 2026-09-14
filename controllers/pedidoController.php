<?php
// controllers/PedidoController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PedidoModel.php';

class PedidoController {

    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getConnection();
    }

    // Vista de Checkout / Confirmación de Compra
    public function checkout() {
        // Redirigir al login si el usuario no está autenticado
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }

        // Redirigir al catálogo si el carrito está vacío
        if (empty($_SESSION['carrito'])) {
            header("Location: index.php?c=producto&a=catalogo");
            exit();
        }

        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $pageTitle = "Finalizar Compra | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/pedido/checkout.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Procesar y guardar el pedido en la BD
    public function procesar() {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['carrito'])) {
            header("Location: index.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_SESSION['usuario']['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;
            $direccion = trim($_POST['direccion_envio'] ?? '');

            if (empty($direccion)) {
                $error = "La dirección de envío es obligatoria.";
                require_once __DIR__ . '/../views/layout/header.php';
                require_once __DIR__ . '/../views/pedido/checkout.php';
                require_once __DIR__ . '/../views/layout/footer.php';
                return;
            }

            // Calcular el total
            $total = 0;
            foreach ($_SESSION['carrito'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            try {
                // Iniciar transacción SQL para garantizar integridad de datos
                $this->db->beginTransaction();

                // 1. Insertar el encabezado del pedido
                $sqlPedido = "INSERT INTO pedidos (usuario_id, total, estado, direccion_envio) VALUES (:usuario_id, :total, 'pagado', :direccion_envio)";
                $stmtPedido = $this->db->prepare($sqlPedido);
                $stmtPedido->execute([
                    ':usuario_id'      => $usuarioId,
                    ':total'           => $total,
                    ':direccion_envio' => $direccion
                ]);

                $pedidoId = $this->db->lastInsertId();

                // 2. Insertar los detalles del pedido y actualizar stock
                $sqlDetalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
                $stmtDetalle = $this->db->prepare($sqlDetalle);

                $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE producto_id = :producto_id";
                $stmtStock = $this->db->prepare($sqlStock);

                foreach ($_SESSION['carrito'] as $item) {
                    $stmtDetalle->execute([
                        ':pedido_id'       => $pedidoId,
                        ':producto_id'     => $item['producto_id'],
                        ':cantidad'        => $item['cantidad'],
                        ':precio_unitario' => $item['precio']
                    ]);

                    $stmtStock->execute([
                        ':cantidad'    => $item['cantidad'],
                        ':producto_id' => $item['producto_id']
                    ]);
                }

                // Confirmar la transacción
                $this->db->commit();

                // Vaciar el carrito de la sesión
                unset($_SESSION['carrito']);

                // Redirigir a la vista de confirmación
                header("Location: index.php?c=pedido&a=confirmacion&id=" . $pedidoId);
                exit();

            } catch (Exception $e) {
                // Cancelar transacción en caso de error
                $this->db->rollBack();
                die("Error al procesar la compra: " . $e->getMessage());
            }
        }
    }

    // Método para mostrar la confirmación del pedido
    public function confirmacion() {
        $pageTitle = "Pedido Confirmado | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/pedido/confirmacion.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Método para ver los ítems/productos comprados en un pedido específico
    public function detalle() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }

        $pedidoId = $_GET['id'] ?? null;

        if ($pedidoId) {
            $pedidoModel = new PedidoModel();
            
            // Obtener productos asociados al pedido usando el modelo
            $detalles = $pedidoModel->obtenerDetallePedido($pedidoId);

            $pageTitle = "Detalle del Pedido #" . $pedidoId;
            
            require_once __DIR__ . '/../views/layout/header.php';
            require_once __DIR__ . '/../views/pedido/detalle.php';
            require_once __DIR__ . '/../views/layout/footer.php';
        } else {
            header("Location: index.php?c=cliente&a=perfil");
            exit();
        }
    }

}