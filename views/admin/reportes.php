<?php
// views/admin/reportes.php
?>
<main style="max-width: 1200px; margin: 0 auto; padding: 2rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Encabezado -->
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #b85b74; margin: 0; font-size: 28px;">Panel de Ventas y Reportes</h1>
        <p style="color: #666; margin-top: 5px; font-size: 14px;">Resumen del rendimiento financiero y métricas de pedidos</p>
    </div>

    <!-- Tarjetas de Métricas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; border-left: 5px solid #b85b74; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <span style="color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase;">Ingresos Totales</span>
            <h2 style="color: #b85b74; margin: 10px 0 0 0; font-size: 26px;">$<?= number_format($metricas['total_ingresos'], 2) ?></h2>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 10px; border-left: 5px solid #b85b74; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <span style="color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase;">Pedidos Procesados</span>
            <h2 style="color: #333; margin: 10px 0 0 0; font-size: 26px;"><?= $metricas['total_pedidos'] ?></h2>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 10px; border-left: 5px solid #b85b74; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <span style="color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase;">Ticket Promedio</span>
            <h2 style="color: #333; margin: 10px 0 0 0; font-size: 26px;">$<?= number_format($metricas['ticket_promedio'], 2) ?></h2>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 10px; border-left: 5px solid #b85b74; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <span style="color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase;">Unidades Vendidas</span>
            <h2 style="color: #333; margin: 10px 0 0 0; font-size: 26px;"><?= $metricas['productos_vendidos'] ?> uds.</h2>
        </div>

    </div>

    <!-- Tabla de Ventas Recientes -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 1.2rem 1.5rem; background-color: #fff0f5; border-bottom: 2px solid #f8c8dc;">
            <h3 style="margin: 0; color: #b85b74; font-size: 18px;">Historial de Ventas Recientes</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #eee; color: #666; font-size: 13px;">
                        <th style="padding: 14px 18px;">ID Pedido</th>
                        <th style="padding: 14px 18px;">Cliente</th>
                        <th style="padding: 14px 18px;">Fecha</th>
                        <th style="padding: 14px 18px;">Monto Total</th>
                        <th style="padding: 14px 18px; text-align: center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventasRecientes as $venta): ?>
                        <tr style="border-bottom: 1px solid #f9f9f9; font-size: 14px;">
                            <td style="padding: 12px 18px; color: #666; font-weight: bold;">#<?= $venta['id'] ?></td>
                            <td style="padding: 12px 18px; color: #333; font-weight: 500;"><?= htmlspecialchars($venta['cliente']) ?></td>
                            <td style="padding: 12px 18px; color: #777;"><?= $venta['fecha'] ?></td>
                            <td style="padding: 12px 18px; color: #b85b74; font-weight: bold;">$<?= number_format($venta['monto'], 2) ?></td>
                            <td style="padding: 12px 18px; text-align: center;">
                                <?php 
                                    $bg = '#fff3cd'; $color = '#856404'; // Pendiente
                                    if ($venta['estado'] === 'Completado') { $bg = '#d4edda'; $color = '#155724'; }
                                    if ($venta['estado'] === 'Enviado') { $bg = '#cce5ff'; $color = '#004085'; }
                                ?>
                                <span style="background-color: <?= $bg ?>; color: <?= $color ?>; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                    <?= $venta['estado'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>