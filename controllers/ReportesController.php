<?php
// controllers/ReportesController.php

require_once __DIR__ . '/../models/ProductoModel.php';

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

        // Simulador de datos de ventas (Reemplazar con consultas a tu tabla 'pedidos' o 'ventas')
        $metricas = [
            'total_ingresos' => 1285.00,
            'total_pedidos'  => 34,
            'ticket_promedio'=> 37.79,
            'productos_vendidos' => 82
        ];

        $ventasRecientes = [
            ['id' => 101, 'cliente' => 'Laura Gómez', 'fecha' => '2026-09-20', 'monto' => 45.00, 'estado' => 'Completado'],
            ['id' => 102, 'cliente' => 'Mariana Silva', 'fecha' => '2026-09-21', 'monto' => 68.50, 'estado' => 'Enviado'],
            ['id' => 103, 'cliente' => 'Sofia Torres', 'fecha' => '2026-09-22', 'monto' => 25.00, 'estado' => 'Pendiente'],
            ['id' => 104, 'cliente' => 'Andrea Ruiz', 'fecha' => '2026-09-23', 'monto' => 52.00, 'estado' => 'Completado'],
        ];

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/reportes.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}