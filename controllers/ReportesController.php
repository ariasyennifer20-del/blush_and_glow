<?php
// controllers/ReportesController.php

require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php'; // 1. Importar el modelo de pedidos

class ReportesController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario sea administrador
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }
    }

    public function index() {
        $pageTitle = "Panel de Ventas y Reportes | Blush & Glow";

        $pedidoModel = new PedidoModel();
        
        // 2. Obtener los pedidos reales de la base de datos con los nombres de los clientes
        $ventasRecientes = $pedidoModel->obtenerPedidosAdmin();

        // 3. Calcular métricas reales basadas en los pedidos de la base de datos
        $totalIngresos = 0;
        $totalPedidos = count($ventasRecientes);
        $totalUnidades = 0;

        foreach ($ventasRecientes as $pedido) {
            $totalIngresos += $pedido['total'];
            
            // Opcional: Si quieres sumar las unidades de los productos de cada pedido
            $detalles = $pedidoModel->obtenerDetallePedido($pedido['pedido_id']);
            foreach ($detalles as $detalle) {
                $totalUnidades += $detalle['cantidad'];
            }
        }

        $ticketPromedio = $totalPedidos > 0 ? ($totalIngresos / $totalPedidos) : 0;

        // Estructura de métricas con datos reales
        $metricas = [
            'total_ingresos'     => $totalIngresos,
            'total_pedidos'      => $totalPedidos,
            'ticket_promedio'    => $ticketPromedio,
            'productos_vendidos' => $totalUnidades
        ];

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/reportes.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
?>