<!-- views/usuario/login.php -->
<main class="catalog-container">
    <div style="max-width: 450px; margin: 2rem auto; background-color: var(--white); padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
        <h2 style="text-align: center; margin-bottom: 0.5rem; color: var(--text-dark);">¡Hola de nuevo!</h2>
        <p style="text-align: center; color: #777; font-size: 0.95rem; margin-bottom: 2rem;">Ingresa a tu cuenta para continuar comprando.</p>

        <!-- Mensaje de registro exitoso -->
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'registrado'): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 0.8rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center;">
                ¡Cuenta creada con éxito! Ahora puedes iniciar sesión.
            </div>
        <?php endif; ?>

        <!-- Mensaje de error -->
        <?php if (!empty($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 0.8rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?c=usuario&a=login" method="POST">
            <div style="margin-bottom: 1.2rem;">
                <label for="email" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Correo Electrónico *</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com" 
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.8rem;">
                <label for="password" style="display: block; font-weight: 500; margin-bottom: 0.4rem;">Contraseña *</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" 
                       style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; cursor: pointer; border: none;">
                Iniciar Sesión
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #666;">
            ¿Aún no tienes cuenta? 
            <a href="index.php?c=usuario&a=registro" style="color: var(--accent-color); font-weight: bold; text-decoration: none;">Regístrate aquí</a>
        </p>
    </div>
</main>