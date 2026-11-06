<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: #333;">Finalizar Compra</h2>
                    <p class="text-muted">Revisa tus productos seleccionados y confirma los detalles de envío.</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger rounded-3"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="row g-4 align-items-start">
                    <!-- Resumen del Carrito -->
                    <div class="col-md-7">
                        <h5 class="fw-semibold mb-3" style="color: #444;">Resumen de tus productos</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cant.</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                                        <?php 
                                            $nombre = $item['nombre'] ?? 'Producto';
                                            $precio = $item['precio'] ?? $item['valor'] ?? 0;
                                            $cant   = $item['cantidad'] ?? $item['qty'] ?? 1;
                                        ?>
                                        <tr>
                                            <td class="fw-medium"><?php echo htmlspecialchars($nombre); ?></td>
                                            <td>$<?php echo number_format($precio, 2); ?></td>
                                            <td><?php echo $cant; ?></td>
                                            <td class="fw-semibold">$<?php echo number_format($precio * $cant, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 bg-light rounded-3 mt-3 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-secondary">Total a pagar:</span>
                            <span class="fs-4 fw-bold text-dark">$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <!-- Formulario de Envío -->
                    <div class="col-md-5">
                        <div class="p-4 border rounded-4 bg-white h-100 shadow-sm">
                            <h5 class="fw-semibold mb-3" style="color: #444;">Detalles de Envío</h5>
                            
                            <form action="index.php?c=pedido&a=procesar" method="POST">
                                <div class="mb-3">
                                    <label for="direccion_envio" class="form-label text-secondary small fw-bold">Dirección de entrega *</label>
                                    <textarea name="direccion_envio" id="direccion_envio" class="form-control rounded-3 py-2" rows="3" required placeholder="Ej: Calle 10 # 20-30, Barrio..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn w-100 text-white rounded-pill py-2 fw-semibold shadow-sm mt-3" style="background-color: #d4a373; border: none; transition: opacity 0.2s;">
                                    Confirmar y Pagar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>