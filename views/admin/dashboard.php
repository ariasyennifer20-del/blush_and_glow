<?php
// views/admin/dashboard.php
?>
<main style="max-width: 1100px; margin: 0 auto; padding: 2rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h1 style="color: #b85b74; margin-bottom: 0.5rem;">Panel de Administración</h1>
    <p style="color: #666; margin-bottom: 2rem;">Bienvenida al centro de control de Blush & Glow.</p>

    <!-- Tarjetas de Acceso Rápido -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        
        <div style="background: #fff; padding: 1.5rem; border-radius: 10px; border-top: 4px solid #b85b74; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="color: #333; margin-top: 0;">Gestión de Productos</h3>
            <p style="color: #666; font-size: 0.9rem;">Consulta, agrega, edita o elimina productos del catálogo.</p>
            <a href="index.php?c=adminProducto&a=index" style="display: inline-block; background: #b85b74; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                Ir a Productos →
            </a>
        </div>

        <div style="background: #fff; padding: 1.5rem; border-radius: 10px; border-top: 4px solid #b85b74; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="color: #333; margin-top: 0;">Plan de Administración</h3>
            <p style="color: #666; font-size: 0.9rem;">Estructura organizacional, gastos iniciales y flujo operativo.</p>
            <a href="index.php?c=admin&a=plan" style="display: inline-block; background: #b85b74; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                Ver Plan →
            </a>
        </div>

    </div>
</main>