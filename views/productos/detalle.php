<!-- views/productos/detalle.php -->
<main class="catalog-container">
    <div style="max-width: 900px; margin: 2rem auto; background-color: var(--white); padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
        
        <!-- Imagen del Producto -->
        <div class="detail-image" style="text-align: center;">
            <img src="/BLUSH_AND_GLOW/public/img/<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                 style="width: 100%; max-width: 380px; border-radius: 10px; object-fit: cover;"
                 onerror="this.src='https://via.placeholder.com/380x380?text=Blush+%26+Glow';">
        </div>

        <!-- Información y Opciones de Compra -->
        <div class="detail-info">
            <span class="category-tag" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">
                <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
            </span>

            <h1 style="font-size: 2rem; margin: 0.5rem 0 1rem 0; color: var(--text-dark);">
                <?php echo htmlspecialchars($producto['nombre']); ?>
            </h1>

            <p style="font-size: 1.8rem; font-weight: bold; color: var(--accent-color); margin-bottom: 1.5rem;">
                $<?php echo number_format($producto['precio'], 2); ?>
            </p>

            <p class="description" style="font-size: 1rem; line-height: 1.6; color: #555; margin-bottom: 2rem;">
                <?php echo htmlspecialchars($producto['descripcion']); ?>
            </p>

            <!-- Formulario para seleccionar cantidad y añadir -->
            <form action="index.php?c=carrito&a=agregar&id=<?php echo $producto['producto_id']; ?>" method="POST">
                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <label for="cantidad" style="font-weight: 600;">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" 
                           style="width: 70px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; text-align: center;">
                    <span style="font-size: 0.85rem; color: #888;">(Stock disponible: <?php echo $producto['stock']; ?>)</span>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="flex: 1; padding: 0.9rem; font-size: 1rem; border: none; cursor: pointer; text-align: center;">
                        Añadir al Carrito
                    </button>
                    <a href="index.php?c=producto&a=catalogo" class="btn-secondary" style="padding: 0.9rem 1.2rem; text-decoration: none; display: inline-block;">
                        Volver
                    </a>
                </div>
            </form>
        </div>

    </div>
</main>