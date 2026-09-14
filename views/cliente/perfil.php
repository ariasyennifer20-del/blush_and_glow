<!-- views/cliente/perfil.php -->
<main class="catalog-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
    <h2 style="color: var(--text-dark, #333); margin-bottom: 1rem;">Mis Pedidos</h2>

    <div style="overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: var(--primary-color, #f8c8dc); text-align: left; color: #333;">
                    <th style="padding: 1rem;">ID Pedido</th>
                    <th style="padding: 1rem;">Fecha</th>
                    <th style="padding: 1rem;">Total</th>
                    <th style="padding: 1rem;">Estado</th>
                    <th style="padding: 1rem; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 1rem; font-weight: bold;">#<?php echo $pedido['pedido_id']; ?></td>
                            <td style="padding: 1rem; color: #555;">
                                <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                            </td>
                            <td style="padding: 1rem; font-weight: 500;">
                                $<?php echo number_format($pedido['total'], 2); ?>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="background: #e3f2fd; color: #1976d2; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">
                                    <?php echo htmlspecialchars($pedido['estado'] ?? 'Procesando'); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="index.php?c=pedido&a=detalle&id=<?php echo $pedido['pedido_id']; ?>" 
                                   style="color: var(--accent-color, #d4a373); text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #777;">
                            Aún no has realizado ningún pedido.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>