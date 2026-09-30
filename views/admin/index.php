<?php
// views/admin/index.php
?>
<main style="max-width: 1200px; margin: 0 auto; padding: 2rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #b85b74; margin: 0; font-size: 28px;">Gestión de Productos</h1>
            <p style="color: #666; margin-top: 5px; font-size: 14px;">Administra el inventario de Blush & Glow</p>
        </div>
        <a href="index.php?c=adminProducto&a=crear" style="background-color: #b85b74; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            + Agregar Nuevo Producto
        </a>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #fff0f5; border-bottom: 2px solid #f8c8dc; color: #b85b74;">
                    <th style="padding: 14px 18px; font-size: 14px;">ID</th>
                    <th style="padding: 14px 18px; font-size: 14px;">Imagen</th>
                    <th style="padding: 14px 18px; font-size: 14px;">Nombre del Producto</th>
                    <th style="padding: 14px 18px; font-size: 14px;">Precio</th>
                    <th style="padding: 14px 18px; font-size: 14px;">Stock</th>
                    <th style="padding: 14px 18px; font-size: 14px; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <?php 
                            // 1. Detección segura del ID sin provocar Notice: Undefined index
                            $id = $producto['id'] ?? $producto['id_producto'] ?? $producto['producto_id'] ?? 0;

                            // 2. Formateo y validación de la URL de la imagen
                            $img = trim($producto['imagen_url'] ?? $producto['imagen'] ?? '');
                            if (empty($img)) {
                                $src = 'https://via.placeholder.com/60/f8c8dc/b85b74?text=Blush';
                            } elseif (strpos($img, 'http') === 0) {
                                $src = $img;
                            } else {
                                // Agrega .jpg si el valor guardado en BD no trae extensión
                                if (!preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $img)) {
                                    $img .= '.jpg';
                                }
                                $src = 'assets/img/' . $img;
                            }
                        ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px 18px; color: #666; font-weight: 500;"><?= $id ?></td>
                            <td style="padding: 12px 18px;">
                                <img src="<?= htmlspecialchars($src) ?>" 
                                     alt="<?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?>" 
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/60/f8c8dc/b85b74?text=Blush';"
                                     style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #f8c8dc; background-color: #fff0f5;">
                            </td>
                            <td style="padding: 12px 18px; font-weight: 600; color: #333;"><?= htmlspecialchars($producto['nombre'] ?? '') ?></td>
                            <td style="padding: 12px 18px; color: #b85b74; font-weight: bold;">$<?= number_format((float)($producto['precio'] ?? 0), 2) ?></td>
                            <td style="padding: 12px 18px; color: #444;"><?= (int)($producto['stock'] ?? 0) ?> uds.</td>
                            <td style="padding: 12px 18px; text-align: center;">
                                <a href="index.php?c=adminProducto&a=editar&id=<?= $id ?>" 
                                   style="color: #0d6efd; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 14px;">
                                    Editar
                                </a>
                                <a href="index.php?c=adminProducto&a=eliminar&id=<?= $id ?>" 
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?')" 
                                   style="color: #dc3545; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #777;">
                            No hay productos registrados en este momento.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>