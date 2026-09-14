<!-- views/productos/catalogo.php -->
<main class="catalog-container">
    <div style="display: flex; gap: 2rem; margin-top: 1rem; align-items: flex-start;">
        
        <!-- Menú Lateral de Filtros -->
        <aside style="width: 220px; background-color: var(--white); padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); flex-shrink: 0;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem;">Categorías</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 0.8rem;">
                    <a href="index.php?c=producto&a=catalogo" style="text-decoration: none; color: var(--text-dark); font-weight: 500;">
                        Ver Todo
                    </a>
                </li>
                <li style="margin-bottom: 0.8rem;">
                    <a href="index.php?c=producto&a=categoria&id=1" style="text-decoration: none; color: var(--text-dark); font-weight: 500;">
                        Maquillaje
                    </a>
                </li>
                <li style="margin-bottom: 0.8rem;">
                    <a href="index.php?c=producto&a=categoria&id=2" style="text-decoration: none; color: var(--text-dark); font-weight: 500;">
                        Skincare
                    </a>
                </li>
                <li style="margin-bottom: 0.8rem;">
                    <a href="index.php?c=producto&a=categoria&id=3" style="text-decoration: none; color: var(--text-dark); font-weight: 500;">
                        Brochas & Accesorios
                    </a>
                </li>
                <li style="margin-bottom: 0.8rem;">
                    <a href="index.php?c=producto&a=categoria&id=4" style="text-decoration: none; color: var(--text-dark); font-weight: 500;">
                        Pestañas & Ojos
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Listado de Productos -->
        <section style="flex-grow: 1;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.8rem;"><?php echo htmlspecialchars($categoriaSeleccionada); ?></h2>

            <?php if (!empty($productos)): ?>
                <div class="product-grid">
                    <?php foreach ($productos as $producto): ?>
                        <article class="product-card">
                            <?php if ($producto['es_destacado']): ?>
                                <span class="badge">Destacado</span>
                            <?php endif; ?>
                            
                            <!-- <a href="index.php?c=producto&a=detalle&id=<?php echo $producto['producto_id']; ?>" style="text-decoration: none; color: inherit;">
                                <img src="public/img/<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                     onerror="this.src='https://via.placeholder.com/250x250?text=Blush+%26+Glow';">
                            </a> -->




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

                            
                            <span class="category-tag"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></span>
                            
                            <h3>
                                <a href="index.php?c=producto&a=detalle&id=<?php echo $producto['producto_id']; ?>" style="text-decoration: none; color: inherit;">
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </a>
                            </h3>
                            
                            <p class="description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            
                            <div class="price-action">
                                <span class="price">$<?php echo number_format($producto['precio'], 2); ?></span>
                                <a href="index.php?c=carrito&a=agregar&id=<?php echo $producto['producto_id']; ?>" class="btn-secondary">
                                    Añadir al Carrito
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #666; font-size: 1.1rem; text-align: center; margin-top: 3rem;">
                    No se encontraron productos disponibles en esta categoría.
                </p>
            <?php endif; ?>
        </section>

    </div>
</main>