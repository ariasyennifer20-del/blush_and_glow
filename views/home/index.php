<!-- Banner Principal (Hero) -->
<section class="hero">
    <div class="hero-content">
        <h1>Resalta tu belleza natural</h1>
        <p>Descubre nuestra colección exclusiva de maquillaje y cuidado facial pensada para ti.</p>
        <a href="index.php?c=producto&a=catalogo" class="btn-primary">Ver Catálogo Completo</a>
    </div>
</section>

<!-- Sección de Productos Destacados -->
<main class="catalog-container">
    <h2>Productos Destacados</h2>
    
    <div class="product-grid">
        <?php if (!empty($productosDestacados)): ?>
            <?php foreach ($productosDestacados as $producto): ?>
                <article class="product-card">
                    <?php if ($producto['es_destacado']): ?>
                        <span class="badge">Destacado</span>
                    <?php endif; ?>



<?php 
    // Comprobar si la columna imagen_url es una URL completa o una ruta local
    $imagenSrc = filter_var($producto['imagen_url'], FILTER_VALIDATE_URL) 
        ? $producto['imagen_url'] 
        : '/BLUSH_AND_GLOW/public/img/' . $producto['imagen_url'];
?>

<img src="<?php echo htmlspecialchars($imagenSrc); ?>" 
     alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
     style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px;"
     onerror="this.src='https://via.placeholder.com/250x250?text=Blush+%26+Glow';">
     
     
                    
                    <!-- <img src="public/img/<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                         onerror="this.src='https://via.placeholder.com/250x250?text=Blush+%26+Glow';"> -->
                    
                    <span class="category-tag"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></span>
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    
                    <div class="price-action">
                        <span class="price">$<?php echo number_format($producto['precio'], 2); ?></span>
                        <a href="index.php?c=carrito&a=agregar&id=<?php echo $producto['producto_id']; ?>" class="btn-secondary">Añadir al Carrito</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">No hay productos destacados disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</main>