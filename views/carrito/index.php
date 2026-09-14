<!-- views/carrito/index.php -->
<main class="catalog-container">
    <h2>Tu Carrito de Compras</h2>

    <?php if (!empty($carrito)): ?>
        <div class="cart-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
            
            <!-- Tabla de Productos -->
            <section class="cart-items">
                <table style="width: 100%; border-collapse: collapse; background-color: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <thead>
                        <tr style="background-color: var(--primary-color); text-align: left;">
                            <th style="padding: 1rem;">Producto</th>
                            <th style="padding: 1rem;">Precio</th>
                            <th style="padding: 1rem;">Cantidad</th>
                            <th style="padding: 1rem;">Subtotal</th>
                            <th style="padding: 1rem;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $item): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                    <img src="public/img/<?php echo htmlspecialchars($item['imagen_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;"
                                         onerror="this.src='https://via.placeholder.com/50';">
                                    <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                                </td>
                                <td style="padding: 1rem;">$<?php echo number_format($item['precio'], 2); ?></td>
                                <td style="padding: 1rem;">
                                    <form action="index.php?c=carrito&a=actualizar" method="POST" style="display: flex; gap: 0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $item['producto_id']; ?>">
                                        <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" style="width: 50px; padding: 0.3rem; border: 1px solid #ccc; border-radius: 4px;">
                                        <button type="submit" class="btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Actualizar</button>
                                    </form>
                                </td>
                                <td style="padding: 1rem; font-weight: bold;">
                                    $<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <a href="index.php?c=carrito&a=eliminar&id=<?php echo $item['producto_id']; ?>" 
                                       style="color: #d9534f; text-decoration: none; font-weight: bold;">Quitar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top: 1.5rem; text-align: left;">
                    <a href="index.php?c=carrito&a=vaciar" class="btn-secondary" style="color: #d9534f; border-color: #d9534f;">Vaciar Carrito</a>
                </div>
            </section>

            <!-- Resumen de la Compra -->
            <aside class="cart-summary" style="background-color: var(--white); padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); height: fit-content;">
                <h3 style="border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">Resumen de Compra</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                    <span>Envío:</span>
                    <span style="color: green; font-weight: bold;">Gratis</span>
                </div>
                
                <hr style="border: 0; border-top: 1px solid #eee; margin: 1rem 0;">
                
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; margin-bottom: 1.5rem;">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>

                <a href="index.php?c=pedido&a=checkout" class="btn-primary" style="display: block; text-align: center; width: 100%;">Proceder al Pago</a>
            </aside>

        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 4rem 1rem;">
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 1.5rem;">Tu carrito está vacío actualmente.</p>
            <a href="index.php?c=producto&a=catalogo" class="btn-primary">Descubrir Productos</a>
        </div>
    <?php endif; ?>
</main>