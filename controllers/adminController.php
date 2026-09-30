<?php
// controllers/AdminController.php

class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario tenga rol de administrador
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }
    }

    // Panel principal / Dashboard
    public function index() {
        $pageTitle = "Panel de Administración | Blush & Glow";
        
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Vista del Plan de Administración
    public function plan() {
        $pageTitle = "Plan de Administración | Blush & Glow";
        
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/administracion.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}