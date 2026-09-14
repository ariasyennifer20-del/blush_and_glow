<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Blush & Glow'; ?></title>
    <link rel="stylesheet" href="/BLUSH_AND_GLOW/public/css/styles.css">
</head>
<body>

    <!-- Header / Navbar -->
    <header class="navbar">
        <div class="logo">
            <a href="index.php">Blush & Glow</a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php?c=producto&a=catalogo">Catálogo</a></li>
                <li><a href="index.php?c=producto&a=categoria&id=1">Maquillaje</a></li>
                <li><a href="index.php?c=producto&a=categoria&id=2">Skincare</a></li>
                
            </ul>
        </nav>
        
      <!-- views/layout/header.php -->
<div class="user-actions" style="display: flex; align-items: center; gap: 15px;">
    <?php if (isset($_SESSION['usuario'])): ?>
        <!-- Nombre del usuario con enlace al perfil -->
        <a href="index.php?c=cliente&a=perfil" style="text-decoration: none; font-weight: 600; color: #333;">
            👤 <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>
        </a>

        <!-- Opción Mis Pedidos para el cliente -->
        <a href="index.php?c=cliente&a=perfil" style="text-decoration: none; font-weight: 500; color: var(--accent-color, #d4a373);">
            🛍️ Mis Pedidos
        </a>

        <!-- Opciones exclusivas de Administrador -->
        <?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
            <a href="index.php?c=adminProducto&a=index">📦 Productos</a>
            <a href="index.php?c=adminUsuario&a=index">👥 Clientes</a>
        <?php endif; ?>

        <a href="index.php?c=usuario&a=logout" style="color: #d9534f; text-decoration: none;">Cerrar Sesión</a>
    <?php else: ?>
        <a href="index.php?c=usuario&a=login" class="btn-account">Mi Cuenta</a>
    <?php endif; ?>
</div>
    </header>