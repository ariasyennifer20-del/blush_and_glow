<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">
                
                <!-- Encabezado de la Vista -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold m-0" style="color: #333;">Detalle del Pedido #<?php echo htmlspecialchars($_GET['id'] ?? ''); ?></h2>
                        <p class="text-muted m-0">Consulta los productos y cantidades solicitados en este pedido.</p>
                    </div>
                    <a href="javascript:history.back()" class="btn rounded-pill px-4 py-2 fw-semibold text-white shadow-sm" style="background-color: #d4a373; border: none;">
                        &larr; Volver
                    </a>
                </div>

                <?php if (!empty($detalles)): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead style="background-color: #fcf6f0; color: #b85b74;">
                                <tr>
                                    <th class="py-3">Producto</th>
                                    <th class="py-3 text-center">Precio Unitario</th>
                                    <th class="py-3 text-center">Cantidad</th>
                                    <th class="py-3 text-end">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $totalPedido = 0; ?>
                                <?php foreach ($detalles as $detalle): ?>
                                    <?php $totalPedido += $detalle['subtotal']; ?>
                                    <tr>
                                        <td class="fw-medium text-dark">
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if (!empty($detalle['imagen_url'])): ?>
                                                    <img src="<?= htmlspecialchars($detalle['imagen_url']) ?>" alt="Img" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($detalle['producto']) ?></span>
                                            </div>
                                        </td>

                                        <td class="text-center text-secondary">
                                            $<?= number_format($detalle['precio_unitario'], 2) ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                                                <?= (int) $detalle['cantidad'] ?>
                                            </span>
                                        </td>

                                        <td class="text-end fw-semibold" style="color: #b85b74;">
                                            $<?= number_format($detalle['subtotal'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Financiero del Pedido -->
                    <div class="p-4 rounded-4 mt-4 d-flex justify-content-between align-items-center shadow-sm" style="background-color: #fcf6f0; border: 1px solid #f3e5dc;">
                        <div>
                            <span class="text-muted small d-block uppercase fw-bold tracking-wider">Estado Financiero</span>
                            <span class="fw-bold fs-5" style="color: #b85b74;">Total del Pedido:</span>
                        </div>
                        <span class="fs-3 fw-bold text-dark">$<?= number_format($totalPedido, 2) ?></span>
                    </div>

                <?php else: ?>
                    <div class="alert alert-warning rounded-3 text-center my-4">
                        No se encontraron productos o detalles asociados a este pedido.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>