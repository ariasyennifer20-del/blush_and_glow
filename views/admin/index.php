<!-- views/admin/index.php (o productos_index.php) -->
<main class="catalog-container" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Gestión de Productos</h2>
        <a href="index.php?c=adminProducto&a=crear" class="btn-primary" style="padding: 0.8rem 1.2rem; text-decoration: none;">+ Nuevo Producto</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: var(--primary-color); text-align: left;">
                <th style="padding: 1rem;">ID</th>
                <th style="padding: 1rem;">Imagen</th>
                <th style="padding: 1rem;">Nombre</th>
                <th style="padding: 1rem;">Precio</th>
                <th style="padding: 1rem;">Stock</th>
                <th style="padding: 1rem;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem;"><?php echo $p['producto_id']; ?></td>
                        <td style="padding: 1rem;">
                            <img src="<?php echo htmlspecialchars($p['imagen_url']); ?>" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td style="padding: 1rem; font-weight: 500;"><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td style="padding: 1rem;">$<?php echo number_format($p['precio'], 2); ?></td>
                        <td style="padding: 1rem;"><?php echo $p['stock']; ?></td>
                        <td style="padding: 1rem;">
                            <a href="index.php?c=adminProducto&a=editar&id=<?php echo $p['producto_id']; ?>" style="color: var(--accent-color); font-weight: bold; text-decoration: none; margin-right: 1rem;">Editar</a>
                            <a href="index.php?c=adminProducto&a=eliminar&id=<?php echo $p['producto_id']; ?>" onclick="return confirm('¿Eliminar producto?');" style="color: #d9534f; font-weight: bold; text-decoration: none;">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="padding: 1.5rem; text-align: center; color: #666;">No hay productos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>