<!-- views/admin/usuarios_index.php -->
<main class="catalog-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Gestión de Clientes y Usuarios</h2>
        <a href="index.php?c=adminUsuario&a=crear" class="btn-primary">+ Nuevo Usuario</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; background-color: var(--white); box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden;">
        <thead>
            <tr style="background-color: var(--primary-color); text-align: left;">
                <th style="padding: 1rem;">ID</th>
                <th style="padding: 1rem;">Nombre</th>
                <th style="padding: 1rem;">Email</th>
                <th style="padding: 1rem;">Teléfono</th>
                <th style="padding: 1rem;">Rol</th>
                <th style="padding: 1rem;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 1rem;"><?php echo $u['usuario_id']; ?></td>
                    <td style="padding: 1rem; font-weight: 500;"><?php echo htmlspecialchars($u['nombre']); ?></td>
                    <td style="padding: 1rem;"><?php echo htmlspecialchars($u['email']); ?></td>
                    <td style="padding: 1rem;"><?php echo htmlspecialchars($u['telefono'] ?: 'N/A'); ?></td>
                    <td style="padding: 1rem;">
                        <span style="background: <?php echo $u['rol'] === 'admin' ? '#f8c8dc' : '#e0e0e0'; ?>; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.85rem; font-weight: bold;">
                            <?php echo ucfirst($u['rol']); ?>
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <a href="index.php?c=adminUsuario&a=editar&id=<?php echo $u['usuario_id']; ?>" style="color: var(--accent-color); font-weight: bold; text-decoration: none; margin-right: 1rem;">Editar</a>
                        <?php if ($u['usuario_id'] !== $_SESSION['usuario']['id']): ?>
                            <a href="index.php?c=adminUsuario&a=eliminar&id=<?php echo $u['usuario_id']; ?>" onclick="return confirm('¿Seguro de eliminar este usuario?');" style="color: #d9534f; font-weight: bold; text-decoration: none;">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>