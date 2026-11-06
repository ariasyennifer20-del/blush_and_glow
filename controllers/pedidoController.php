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

        // BLOQUEO ESTRICTO EN CHECKOUT
        foreach ($_SESSION['carrito'] as $item) {
            $prodId = $item['producto_id'] ?? $item['id'] ?? $item['id_producto'] ?? null;
            $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;

            if ($prodId) {
                $stmtCheck = $this->db->prepare("SELECT stock, nombre FROM productos WHERE producto_id = ?");
                $stmtCheck->execute([$prodId]);
                $prodDb = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($prodDb) {
                    if ($cant > $prodDb['stock']) {
                        header("Location: index.php?c=producto&a=catalogo&error=stock_excedido");
                        exit();
                    }
                }
            }
        }

        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $precio = $item['precio'] ?? $item['valor'] ?? 0;
            $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;
            $total += $precio * $cant;
        }

        $pageTitle = "Finalizar Compra | Blush & Glow";
        
        require_once dirname(__DIR__) . '/views/layout/header.php';
        require_once dirname(__DIR__) . '/views/pedido/checkout.php';
        require_once dirname(__DIR__) . '/views/layout/footer.php';
    }

    // Procesar y guardar el pedido en la BD
    public function procesar() {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['carrito'])) {
            header("Location: index.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Extracción inteligente y blindada del ID de usuario
            $usuarioId = null;

            if (is_array($_SESSION['usuario'])) {
                $usuarioId = $_SESSION['usuario']['usuario_id'] 
                          ?? $_SESSION['usuario']['id'] 
                          ?? $_SESSION['usuario']['id_usuario'] 
                          ?? $_SESSION['usuario']['user_id'] 
                          ?? null;

                if (!$usuarioId) {
                    $email = $_SESSION['usuario']['email'] ?? $_SESSION['usuario']['correo'] ?? null;
                    if ($email) {
                        $stmtU = $this->db->prepare("SELECT usuario_id FROM usuarios WHERE email = ?");
                        $stmtU->execute([$email]);
                        $rowU = $stmtU->fetch(PDO::FETCH_ASSOC);
                        if ($rowU) {
                            $usuarioId = $rowU['usuario_id'];
                        }
                    }
                }
            } else {
                $val = $_SESSION['usuario'];
                if (is_numeric($val)) {
                    $usuarioId = $val;
                } else {
                    $stmtU = $this->db->prepare("SELECT usuario_id FROM usuarios WHERE email = ? OR nombre = ?");
                    $stmtU->execute([$val, $val]);
                    $rowU = $stmtU->fetch(PDO::FETCH_ASSOC);
                    if ($rowU) {
                        $usuarioId = $rowU['usuario_id'];
                    }
                }
            }

            if (!$usuarioId) {
                die("Error crítico: No se pudo asociar el pedido a ningún usuario. Por favor, cierra sesión e ingresa nuevamente.");
            }

            $direccion = trim($_POST['direccion_envio'] ?? '');

            if (empty($direccion)) {
                $error = "La dirección de envío es obligatoria.";
                require_once dirname(__DIR__) . '/views/layout/header.php';
                require_once dirname(__DIR__) . '/views/pedido/checkout.php';
                require_once dirname(__DIR__) . '/views/layout/footer.php';
                return;
            }

            // VALIDACIÓN DE STOCK FINAL
            foreach ($_SESSION['carrito'] as $item) {
                $prodId = $item['producto_id'] ?? $item['id'] ?? $item['id_producto'] ?? null;
                $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;

                $stmtCheck = $this->db->prepare("SELECT stock, nombre FROM productos WHERE producto_id = ?");
                $stmtCheck->execute([$prodId]);
                $prodDb = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (!$prodDb || $prodDb['stock'] < $cant) {
                    $nombreProd = $prodDb['nombre'] ?? 'Producto';
                    $stockDispo = $prodDb['stock'] ?? 0;
                    $error = "No se puede procesar la compra. Stock insuficiente para '$nombreProd' (Has solicitado $cant unidades y solo hay $stockDispo disponibles).";
                    
                    require_once dirname(__DIR__) . '/views/layout/header.php';
                    require_once dirname(__DIR__) . '/views/pedido/checkout.php';
                    require_once dirname(__DIR__) . '/views/layout/footer.php';
                    return;
                }
            }

            // Calcular el total de forma segura
            $total = 0;
            foreach ($_SESSION['carrito'] as $item) {
                $precio = $item['precio'] ?? $item['valor'] ?? 0;
                $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;
                $total += $precio * $cant;
            }

            try {
                // Iniciar transacción SQL para garantizar integridad de datos
                $this->db->beginTransaction();

                // 1. Insertar el encabezado del pedido
                $sqlPedido = "INSERT INTO pedidos (usuario_id, total, estado, direccion_envio, fecha_pedido) VALUES (:usuario_id, :total, 'pagado', :direccion_envio, NOW())";
                $stmtPedido = $this->db->prepare($sqlPedido);
                $stmtPedido->execute([
                    ':usuario_id'       => $usuarioId,
                    ':total'            => $total,
                    ':direccion_envio' => $direccion
                ]);

                $pedidoId = $this->db->lastInsertId();

                // 2. Insertar los detalles del pedido y descontar unidades
                $sqlDetalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
                $stmtDetalle = $this->db->prepare($sqlDetalle);

                $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE producto_id = :producto_id";
                $stmtStock = $this->db->prepare($sqlStock);

                foreach ($_SESSION['carrito'] as $item) {
                    $prodId = $item['producto_id'] ?? $item['id'] ?? $item['id_producto'] ?? null;
                    $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;
                    $precio = $item['precio'] ?? $item['valor'] ?? 0;

                    $stmtDetalle->execute([
                        ':pedido_id'       => $pedidoId,
                        ':producto_id'     => $prodId,
                        ':cantidad'        => $cant,
                        ':precio_unitario' => $precio
                    ]);

                    $stmtStock->execute([
                        ':cantidad'    => $cant,
                        ':producto_id' => $prodId
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
        require_once dirname(__DIR__) . '/views/layout/header.php';
        require_once dirname(__DIR__) . '/views/pedido/confirmacion.php';
        require_once dirname(__DIR__) . '/views/layout/footer.php';
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
            $detalles = $pedidoModel->obtenerDetallePedido($pedidoId);

            $pageTitle = "Detalle del Pedido #" . $pedidoId;
            
            require_once dirname(__DIR__) . '/views/layout/header.php';
            // Carga unificada para evitar desajustes de rutas en las vistas de detalle
            if (file_exists(dirname(__DIR__) . '/views/cliente/pedido/detalle.php')) {
                require_once dirname(__DIR__) . '/views/cliente/pedido/detalle.php';
            } else {
                require_once dirname(__DIR__) . '/views/pedido/detalle.php';
            }
            require_once dirname(__DIR__) . '/views/layout/footer.php';
        } else {
            header("Location: index.php?c=cliente&a=perfil");
            exit();
        }
    }
}