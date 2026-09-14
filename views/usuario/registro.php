<!-- views/usuario/registro.php -->
<main class="catalog-container">
    <div style="max-width: 500px; margin: 2rem auto; background-color: var(--white); padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
        <h2 style="text-align: center; margin-bottom: 0.5rem; color: var(--text-dark);">Crea tu Cuenta</h2>
        <p style="text-align: center; color: #777; font-size: 0.95rem; margin-bottom: 2rem;">Únete a Blush & Glow y disfruta de recomendaciones exclusivas.</p>

        <!-- Mensaje de error -->
        <?php if (!empty($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 0.8rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?c=usuario&a=registro" method="POST">
            <div style="margin-bottom: 1.2rem;">
                <label for="nombre" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej: Maria Perez" 
                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>"
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label for="email" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Correo Electrónico *</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label for="password" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Contraseña *</label>
                <input type="password" id="password" name="password" required placeholder="Crea una contraseña segura" 
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label for="telefono" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Teléfono (Opcional)</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ej: 555-0123" 
                       value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>"
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.8rem;">
                <label for="direccion" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Dirección de Envío (Opcional)</label>
                <textarea id="direccion" name="direccion" rows="3" placeholder="Ej: Calle Principal #123, Apto 4B" 
                          style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; resize: vertical;"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; cursor: pointer; border: none;">
                Crear Cuenta
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #666;">
            ¿Ya tienes una cuenta? 
            <a href="index.php?c=usuario&a=login" style="color: var(--accent-color); font-weight: bold; text-decoration: none;">Inicia sesión</a>
        </p>
    </div>
</main>