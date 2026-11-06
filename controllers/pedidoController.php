<?php
// controllers/PedidoController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ValidadorCantidad.php';
require_once __DIR__ . '/../models/AccesoPedido.php';
require_once __DIR__ . '/../models/TokenFormulario.php';
require_once __DIR__ . '/../models/CarritoPrecios.php';
require_once __DIR__ . '/../models/CarritoModel.php';

class PedidoController {

    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        if (isset($_SESSION['usuario']) && empty($_SESSION['carrito'])) {
            $usuarioId = $_SESSION['usuario']['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;
            if ($usuarioId) {
                $_SESSION['carrito'] = (new CarritoModel())->cargar($usuarioId);
            }
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

        $errorStock = $this->validarLineasCarrito();
        if ($errorStock !== null) {
            $_SESSION['flash_error'] = $errorStock;
            header("Location: index.php?c=carrito&a=ver");
            exit();
        }

        $this->mostrarCheckout();
    }

    // Procesar y guardar el pedido en la BD
    public function procesar() {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['carrito'])) {
            header("Location: index.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tokenEnviado = $_POST['checkout_token'] ?? '';
            $tokenGuardado = $_SESSION['checkout_token'] ?? '';
            unset($_SESSION['checkout_token']);

            if (!TokenFormulario::esValido($tokenEnviado, $tokenGuardado)) {
                $this->mostrarCheckout("Este pago ya fue enviado o el formulario venció. Revisa el resumen e inténtalo otra vez.");
                return;
            }

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
                $this->mostrarCheckout("No se pudo asociar el pedido a tu cuenta. Cierra sesión e ingresa nuevamente.");
                return;
            }

            $direccion = trim($_POST['direccion_envio'] ?? '');

            if (empty($direccion)) {
                $this->mostrarCheckout("La dirección de envío es obligatoria.");
                return;
            }

            $errorStock = $this->validarLineasCarrito();
            $total = $this->totalCarrito();
            if ($errorStock !== null) {
                $this->mostrarCheckout($errorStock);
                return;
            }

            try {
                // Iniciar transacción SQL para garantizar integridad de datos
                $this->db->beginTransaction();

                // 1. Insertar el encabezado del pedido
                $sqlPedido = "INSERT INTO pedidos (usuario_id, total, estado, direccion_envio, fecha_pedido) VALUES (:usuario_id, :total, 'pendiente', :direccion_envio, NOW())";
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

                $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE producto_id = :producto_id AND stock >= :cantidad";
                $stmtStock = $this->db->prepare($sqlStock);

                foreach ($_SESSION['carrito'] as $item) {
                    $prodId = $item['producto_id'] ?? $item['id'] ?? $item['id_producto'] ?? null;
                    $cant   = ValidadorCantidad::entero($item['cantidad'] ?? $item['qty'] ?? 0);
                    $precio = $item['precio'] ?? $item['valor'] ?? 0;
                    $nombre = $item['nombre'] ?? 'Producto';

                    if ($cant < 1) {
                        throw new RuntimeException(
                            'La cantidad de unidades de "' . $nombre . '" debe ser mayor a cero.'
                        );
                    }

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

                    if ($stmtStock->rowCount() !== 1) {
                        throw new RuntimeException(
                            'Stock insuficiente para "' . $nombre . '" al confirmar la compra.'
                        );
                    }
                }

                // Confirmar la transacción
                $this->db->commit();

                // Vaciar el carrito de la sesión y el guardado en base de datos
                $_SESSION['carrito'] = [];
                (new CarritoModel())->reemplazar($usuarioId, []);

                // Redirigir a la vista de confirmación
                header("Location: index.php?c=pedido&a=confirmacion&id=" . $pedidoId);
                exit();

            } catch (RuntimeException $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                $this->mostrarCheckout($e->getMessage());
                return;
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                $this->mostrarCheckout("No se pudo procesar la compra. Inténtalo de nuevo.");
                return;
            }
        }
    }

    private function totalCarrito() {
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $precio = (float) ($item['precio'] ?? $item['valor'] ?? 0);
            $cant = ValidadorCantidad::entero($item['cantidad'] ?? $item['qty'] ?? 0);
            if ($cant > 0 && $precio >= 0) {
                $total += $precio * $cant;
            }
        }
        return $total;
    }

    private function validarLineasCarrito() {
        foreach ($_SESSION['carrito'] as $clave => $item) {
            $prodId = $item['producto_id'] ?? $item['id'] ?? $item['id_producto'] ?? null;
            $cant = $item['cantidad'] ?? $item['qty'] ?? 0;
            $nombre = $item['nombre'] ?? 'Producto';

            if (!$prodId) {
                return 'Hay un producto del carrito sin identificador.';
            }

            $stmtCheck = $this->db->prepare("SELECT stock, nombre, precio FROM productos WHERE producto_id = ?");
            $stmtCheck->execute([$prodId]);
            $prodDb = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$prodDb) {
                return '"' . $nombre . '" ya no está disponible.';
            }

            $_SESSION['carrito'][$clave] = CarritoPrecios::conPrecioActual(
                $item,
                $prodDb['precio'],
                $prodDb['nombre']
            );

            $error = ValidadorCantidad::mensaje($cant, $prodDb['stock'], $prodDb['nombre']);
            if ($error !== null) {
                return $error;
            }
        }

        return null;
    }

    private function nuevoTokenCheckout() {
        $token = TokenFormulario::generar();
        $_SESSION['checkout_token'] = $token;
        return $token;
    }

    private function mostrarCheckout($error = null) {
        $total = $this->totalCarrito();
        $checkoutToken = $this->nuevoTokenCheckout();
        $pageTitle = "Finalizar Compra | Blush & Glow";
        require_once dirname(__DIR__) . '/views/layout/header.php';
        require_once dirname(__DIR__) . '/views/pedido/checkout.php';
        require_once dirname(__DIR__) . '/views/layout/footer.php';
    }

    private function sesionPuedeVerPedido($pedido) {
        $sesionId = $_SESSION['usuario']['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;
        $rol = $_SESSION['usuario']['rol'] ?? '';
        $dueno = is_array($pedido) ? ($pedido['usuario_id'] ?? null) : null;
        return AccesoPedido::puedeVer($dueno, $sesionId, $rol);
    }

    // Método para mostrar la confirmación del pedido
    public function confirmacion() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }

        $pedidoId = $_GET['id'] ?? null;
        if ($pedidoId) {
            $pedido = (new PedidoModel())->obtenerPorId($pedidoId);
            if (!$this->sesionPuedeVerPedido($pedido)) {
                header("Location: index.php?c=cliente&a=perfil");
                exit();
            }
        }

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
            $pedido = $pedidoModel->obtenerPorId($pedidoId);

            if (!$this->sesionPuedeVerPedido($pedido)) {
                header("Location: index.php?c=cliente&a=perfil");
                exit();
            }

            $detalles = $pedidoModel->obtenerDetallePedido($pedidoId);

            $pageTitle = "Detalle del Pedido #" . $pedidoId;
            
            require_once dirname(__DIR__) . '/views/layout/header.php';
            require_once dirname(__DIR__) . '/views/cliente/pedido/detalle.php';
            require_once dirname(__DIR__) . '/views/layout/footer.php';
        } else {
            header("Location: index.php?c=cliente&a=perfil");
            exit();
        }
    }
}