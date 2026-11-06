<?php
// views/admin/dashboard.php
?>
<main style="max-width: 1100px; margin: 0 auto; padding: 2rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <p style="background:#f8d7da; color:#721c24; padding:0.8rem 1rem; border-radius:6px;"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></p>
    <?php endif; ?>
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

    <!-- Sección de Pedidos Recientes de Clientes -->
    <section style="background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #b85b74; margin-top: 0; margin-bottom: 1rem; font-size: 1.4rem;">Pedidos Recientes de Clientes</h2>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr style="background: #fdf2f4; color: #b85b74; border-bottom: 2px solid #f3d1d7;">
                        <th style="padding: 12px;">ID Pedido</th>
                        <th style="padding: 12px;">Cliente</th>
                        <th style="padding: 12px;">Fecha</th>
                        <th style="padding: 12px;">Total</th>
                        <th style="padding: 12px;">Estado</th>
                        <th style="padding: 12px;">Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidos)): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; font-weight: bold; color: #333;">#<?php echo $pedido['pedido_id']; ?></td>
                                <td style="padding: 12px; color: #555;"><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td style="padding: 12px; color: #555;"><?php echo $pedido['fecha_pedido']; ?></td>
                                <td style="padding: 12px; color: #27ae60; font-weight: bold;">$<?php echo number_format($pedido['total'], 2); ?></td>
                                <td style="padding: 12px;">
                                    <form action="index.php?c=admin&a=estado" method="POST" style="display:flex; gap:0.4rem; align-items:center;">
                                        <input type="hidden" name="pedido_id" value="<?php echo (int) $pedido['pedido_id']; ?>">
                                        <select name="estado" style="padding:0.3rem;">
                                            <?php foreach (['pendiente', 'pagado', 'enviado', 'completado', 'cancelado'] as $estado): ?>
                                                <option value="<?php echo $estado; ?>" <?php echo $pedido['estado'] === $estado ? 'selected' : ''; ?>><?php echo $estado; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" style="background:#b85b74; color:#fff; border:none; border-radius:4px; padding:0.3rem 0.6rem;">Guardar</button>
                                    </form>
                                </td>
                                <td style="padding: 12px; color: #666;"><?php echo htmlspecialchars($pedido['direccion_envio']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #888;">No hay pedidos registrados todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>