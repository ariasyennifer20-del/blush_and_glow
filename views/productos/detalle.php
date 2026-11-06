<?php
$imagenSrc = filter_var($producto['imagen_url'], FILTER_VALIDATE_URL)
    ? $producto['imagen_url']
    : ($basePath ?? '') . '/public/img/' . $producto['imagen_url'];
$stock = (int) ($producto['stock'] ?? 0);
?>
<main class="catalog-container">
    <article class="product-card" style="max-width: 860px; margin: 0 auto; display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
        <img src="<?php echo htmlspecialchars($imagenSrc); ?>"
             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
             style="width: 100%; height: 320px; object-fit: cover; border-radius: 8px;"
             onerror="this.src='https://via.placeholder.com/320x320?text=Blush+%26+Glow';">

        <div>
            <span class="category-tag"><?php echo htmlspecialchars($producto['categoria_nombre'] ?? ''); ?></span>
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
            <p class="description"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></p>
            <p class="price">$<?php echo number_format((float) $producto['precio'], 2); ?></p>
            <p style="margin: 0.8rem 0; color: #666;">Disponibles: <?php echo $stock; ?></p>

            <?php if ($stock > 0): ?>
                <form action="index.php?c=carrito&a=agregar&id=<?php echo (int) $producto['producto_id']; ?>" method="POST" style="display: flex; gap: 0.8rem; align-items: center;">
                    <label for="cantidad">Cantidad</label>
                    <input id="cantidad" type="number" name="cantidad" value="1" min="1" max="<?php echo $stock; ?>" style="width: 70px; padding: 0.4rem;">
                    <button type="submit" class="btn-secondary">Añadir al carrito</button>
                </form>
            <?php else: ?>
                <p style="color: #d9534f; font-weight: 600;">Agotado</p>
            <?php endif; ?>

            <p style="margin-top: 1.5rem;"><a href="index.php?c=producto&a=catalogo">Volver al catálogo</a></p>
        </div>
    </article>
</main>
