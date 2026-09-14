<!-- views/pedido/detalle.php -->
<main class="catalog-container" style="padding: 2rem; max-width: 900px; margin: 0 auto;">
    <a href="index.php?c=cliente&a=perfil" style="text-decoration: none; color: #666; font-weight: bold;">
        &larr; Volver a Mis Pedidos
    </a>

    <h2 style="margin-top: 1rem;">Detalle del Pedido #<?php echo htmlspecialchars($_GET['id'] ?? ''); ?></h2>

    <div style="background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; margin-top: 1rem;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: var(--primary-color, #f8c8dc); text-align: left;">
                    <th style="padding: 1rem;">Producto</th>
                    <th style="padding: 1rem;">Precio Unitario</th>
                    <th style="padding: 1rem;">Cantidad</th>
                    <th style="padding: 1rem;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detalles)): ?>
                    <?php foreach ($detalles as $item): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($item['imagen_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['imagen_url']); ?>" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                            </td>
                            <td style="padding: 1rem;">$<?php echo number_format($item['precio_unitario'] ?? $item['precio'] ?? 0, 2); ?></td>
                            <td style="padding: 1rem;"><?php echo $item['cantidad']; ?></td>
                            <td style="padding: 1rem; font-weight: bold;">
                                $<?php echo number_format(($item['precio_unitario'] ?? $item['precio'] ?? 0) * $item['cantidad'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 1.5rem; text-align: center; color: #777;">
                            No se encontraron detalles para este pedido.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>