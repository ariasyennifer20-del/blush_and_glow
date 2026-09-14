<!-- views/admin/usuarios_form.php -->
<?php $esEdicion = isset($usuario); ?>
<main class="catalog-container">
    <div style="max-width: 550px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2><?php echo $esEdicion ? 'Editar Usuario' : 'Nuevo Usuario'; ?></h2>
        
        <?php if (!empty($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 0.8rem; border-radius: 6px; margin-top: 1rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?c=adminUsuario&a=<?php echo $esEdicion ? 'editar&id=' . $usuario['usuario_id'] : 'crear'; ?>" method="POST" style="margin-top: 1.5rem;">
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Nombre Completo *</label>
                <input type="text" name="nombre" required value="<?php echo $esEdicion ? htmlspecialchars($usuario['nombre']) : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Email *</label>
                <input type="email" name="email" required value="<?php echo $esEdicion ? htmlspecialchars($usuario['email']) : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Contraseña <?php echo $esEdicion ? '(Opcional, dejar en blanco para no cambiar)' : '*'; ?></label>
                <input type="password" name="password" <?php echo $esEdicion ? '' : 'required'; ?> style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Teléfono</label>
                    <input type="text" name="telefono" value="<?php echo $esEdicion ? htmlspecialchars($usuario['telefono']) : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Rol *</label>
                    <select name="rol" required style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box;">
                        <option value="cliente" <?php echo ($esEdicion && $usuario['rol'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                        <option value="admin" <?php echo ($esEdicion && $usuario['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Dirección</label>
                <textarea name="direccion" rows="3" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px; box-sizing: border-box; resize: vertical;"><?php echo $esEdicion ? htmlspecialchars($usuario['direccion']) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; border:none; cursor:pointer;">
                <?php echo $esEdicion ? 'Actualizar Usuario' : 'Guardar Usuario'; ?>
            </button>
        </form>
    </div>
</main>