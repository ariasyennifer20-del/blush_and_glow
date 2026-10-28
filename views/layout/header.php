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
                <li><a href="index.php?c=usuario&a=quienesSomos">Quiénes somos</a></li>
                <!-- Opción Tratamiento de datos disponible siempre en el menú -->
                <li><a href="#" id="btnTratamientoDatos" style="cursor: pointer;">Tratamiento de datos</a></li>
            </ul>
        </nav>
        
        <div class="user-actions" style="display: flex; align-items: center; gap: 15px;">
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="index.php?c=cliente&a=perfil" style="text-decoration: none; font-weight: 600; color: #333;">
                    👤 <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>
                </a>

                <a href="index.php?c=cliente&a=perfil" style="text-decoration: none; font-weight: 500; color: #d4a373;">
                    🛍️ Mis Pedidos
                </a>

                <!-- Opciones exclusivas de Administrador -->
                <?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
                    <a href="index.php?c=adminProducto&a=index" style="text-decoration: none; font-weight: 500;">📦 Productos</a>
                    <a href="index.php?c=reportes&a=index" style="text-decoration: none; font-weight: 500;">📊 Reportes</a>
                    <a href="index.php?c=adminUsuario&a=index" style="text-decoration: none; font-weight: 500;">👥 Clientes</a>
                <?php endif; ?>

                <a href="index.php?c=usuario&a=quienesSomos">Quiénes somos</a>
                <a href="index.php?c=usuario&a=logout" style="color: #d9534f; text-decoration: none;">Cerrar Sesión</a>
            <?php else: ?>
                <a href="index.php?c=usuario&a=login" class="btn-account">Mi Cuenta</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Ventana Modal: Tratamiento de Datos -->
    <div id="modalTratamientoDatos" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center;">
        <div style="background-color: #ffffff; padding: 2.5rem; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.15); font-family: sans-serif; position: relative;">
            <span id="cerrarModalDatos" style="position: absolute; top: 12px; right: 16px; font-size: 1.5rem; font-weight: bold; color: #888; cursor: pointer;">&times;</span>
            
            <h3 style="color: #b85b74; margin-top: 0; margin-bottom: 1rem; text-align: center; font-size: 1.3rem;">
                Tratamiento de Datos
            </h3>
            
            <p style="color: #444; line-height: 1.6; font-size: 0.95rem; text-align: justify; margin-bottom: 1.5rem;">
                El presente proyecto tiene fines exclusivamente académicos. Los datos utilizados durante el desarrollo, las pruebas y la demostración del sistema son ficticios y fueron creados únicamente con fines de simulación. El sistema no recolecta, almacena ni procesa datos personales reales de estudiantes, docentes o terceros.
            </p>

            <div style="text-align: center;">
                <button id="btnAceptarDatos" style="background-color: #b85b74; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.95rem;">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnTratamiento = document.getElementById('btnTratamientoDatos');
        const modalTratamiento = document.getElementById('modalTratamientoDatos');
        const btnCerrar = document.getElementById('cerrarModalDatos');
        const btnAceptar = document.getElementById('btnAceptarDatos');

        if (btnTratamiento && modalTratamiento) {
            btnTratamiento.addEventListener('click', function (e) {
                e.preventDefault();
                modalTratamiento.style.display = 'flex';
            });

            btnCerrar.addEventListener('click', function () {
                modalTratamiento.style.display = 'none';
            });

            btnAceptar.addEventListener('click', function () {
                modalTratamiento.style.display = 'none';
            });

            window.addEventListener('click', function (e) {
                if (e.target === modalTratamiento) {
                    modalTratamiento.style.display = 'none';
                }
            });
        }
    });
    </script>