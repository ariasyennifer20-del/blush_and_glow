<!-- views/pedido/checkout.php -->
<main class="catalog-container">
    <div style="max-width: 800px; margin: 2rem auto; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Formulario de Envío -->
        <div style="background-color: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h2 style="margin-bottom: 1.5rem;">Datos de Envío</h2>

            <?php if (!empty($error)): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?c=pedido&a=procesar" method="POST">
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Cliente:</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>" disabled 
                           style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; background-color: #f9f9f9;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="direccion_envio" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Dirección Completa de Envío *</label>
                    <textarea id="direccion_envio" name="direccion_envio" rows="4" required placeholder="Ej: Calle 123 #45-67, Apto 201, Ciudad"
                              style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; border: none; cursor: pointer;">
                    Confirmar y Pagar
                </button>
            </form>
        </div>

        <!-- Resumen del Pedido -->
        <div style="background-color: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: fit-content;">
            <h3 style="border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">Tu Pedido</h3>
            
            <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <li style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.95rem;">
                        <span><?php echo htmlspecialchars($item['nombre']); ?> (x<?php echo $item['cantidad']; ?>)</span>
                        <span style="font-weight: 600;">$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 1rem 0;">

            <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold;">
                <span>Total a Pagar:</span>
                <span style="color: var(--accent-color);">$<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

    </div>
</main>