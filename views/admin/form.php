<!-- views/admin/form.php -->
<?php $esEdicion = isset($producto); ?>
<main class="catalog-container">
    <div style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2><?php echo $esEdicion ? 'Editar Producto' : 'Nuevo Producto'; ?></h2>
        
        <form action="index.php?c=adminProducto&a=<?php echo $esEdicion ? 'editar&id=' . $producto['producto_id'] : 'crear'; ?>" method="POST">
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Nombre del Producto *</label>
                <input type="text" name="nombre" required value="<?php echo $esEdicion ? htmlspecialchars($producto['nombre']) : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Descripción</label>
                <textarea name="descripcion" rows="3" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;"><?php echo $esEdicion ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Precio ($) *</label>
                    <input type="number" step="0.01" name="precio" required value="<?php echo $esEdicion ? $producto['precio'] : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Stock *</label>
                    <input type="number" name="stock" required value="<?php echo $esEdicion ? $producto['stock'] : '0'; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Categoría *</label>
                    <select name="categoria_id" required style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                        <option value="1" <?php echo ($esEdicion && $producto['categoria_id'] == 1) ? 'selected' : ''; ?>>Maquillaje</option>
                        <option value="2" <?php echo ($esEdicion && $producto['categoria_id'] == 2) ? 'selected' : ''; ?>>Skincare</option>
                        <option value="3" <?php echo ($esEdicion && $producto['categoria_id'] == 3) ? 'selected' : ''; ?>>Brochas & Accesorios</option>
                        <option value="4" <?php echo ($esEdicion && $producto['categoria_id'] == 4) ? 'selected' : ''; ?>>Pestañas & Ojos</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">URL o Nombre de la Imagen</label>
                <input type="text" name="imagen_url" value="<?php echo $esEdicion ? htmlspecialchars($producto['imagen_url']) : ''; ?>" placeholder="https://... o nombre-imagen.jpg" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight:500;">
                    <input type="checkbox" name="es_destacado" value="1" <?php echo ($esEdicion && $producto['es_destacado']) ? 'checked' : ''; ?>> Product Destacado
                </label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; border:none; cursor:pointer;">
                <?php echo $esEdicion ? 'Actualizar Producto' : 'Guardar Producto'; ?>
            </button>
        </form>
    </div>
</main><!-- views/admin/form.php -->
<?php $esEdicion = isset($producto); ?>
<main class="catalog-container">
    <div style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2><?php echo $esEdicion ? 'Editar Producto' : 'Nuevo Producto'; ?></h2>
        
        <form action="index.php?c=adminProducto&a=<?php echo $esEdicion ? 'editar&id=' . $producto['producto_id'] : 'crear'; ?>" method="POST" style="margin-top: 1.5rem;">
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Nombre del Producto *</label>
                <input type="text" name="nombre" required value="<?php echo $esEdicion ? htmlspecialchars($producto['nombre']) : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Descripción</label>
                <textarea name="descripcion" rows="3" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;"><?php echo $esEdicion ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Precio ($) *</label>
                    <input type="number" step="0.01" name="precio" required value="<?php echo $esEdicion ? $producto['precio'] : ''; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Stock *</label>
                    <input type="number" name="stock" required value="<?php echo $esEdicion ? $producto['stock'] : '0'; ?>" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">Categoría *</label>
                    <select name="categoria_id" required style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
                        <option value="1" <?php echo ($esEdicion && $producto['categoria_id'] == 1) ? 'selected' : ''; ?>>Maquillaje</option>
                        <option value="2" <?php echo ($esEdicion && $producto['categoria_id'] == 2) ? 'selected' : ''; ?>>Skincare</option>
                        <option value="3" <?php echo ($esEdicion && $producto['categoria_id'] == 3) ? 'selected' : ''; ?>>Brochas & Accesorios</option>
                        <option value="4" <?php echo ($esEdicion && $producto['categoria_id'] == 4) ? 'selected' : ''; ?>>Pestañas & Ojos</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom: 0.4rem; font-weight:500;">URL o Nombre de la Imagen</label>
                <input type="text" name="imagen_url" value="<?php echo $esEdicion ? htmlspecialchars($producto['imagen_url']) : ''; ?>" placeholder="https://... o nombre-imagen.jpg" style="width:100%; padding: 0.8rem; border: 1px solid #ddd; border-radius:6px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight:500;">
                    <input type="checkbox" name="es_destacado" value="1" <?php echo ($esEdicion && $producto['es_destacado']) ? 'checked' : ''; ?>> Product Destacado
                </label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; border:none; cursor:pointer;">
                <?php echo $esEdicion ? 'Actualizar Producto' : 'Guardar Producto'; ?>
            </button>
        </form>
    </div>
</main>