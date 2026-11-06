<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">



                <!-- Encabezado -->

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                    <div>

                        <h2 class="fw-bold m-0" style="color: #333;">

                            Detalle del Pedido #<?= htmlspecialchars($_GET['id'] ?? '') ?>

                        </h2>

                        <p class="text-muted m-0">

                            Consulta los productos y cantidades solicitados en este pedido.

                        </p>

                    </div>



                    <a href="javascript:history.back()"

                       class="btn rounded-pill px-4 py-2 fw-semibold text-white shadow-sm"

                       style="background-color: #d4a373; border: none;">

                        &larr; Volver

                    </a>

                </div>



                <?php if (!empty($detalles)): ?>



                    <?php

                    $totalPedido = 0;

                    $totalUnidades = 0;

                    ?>



                    <div class="table-responsive">

                        <table class="table align-middle">



                            <thead style="background-color: #fcf6f0; color: #b85b74;">

                                <tr>

                                    <th class="py-3">Producto</th>

                                    <th class="py-3 text-center">Precio Unitario</th>

                                    <th class="py-3 text-center">Cantidad de unidades</th>

                                    <th class="py-3 text-end">Subtotal</th>

                                </tr>

                            </thead>



                            <tbody>



                                <?php foreach ($detalles as $detalle): ?>



                                    <?php

                                    // Obtener cantidad y precio del producto

                                    $revision = ValidadorCantidad::revisarUnidades($detalle['cantidad'] ?? null);

                                    $cantidad = $revision['cantidad'];

                                    $precio = (float) $detalle['precio_unitario'];

                                    $subtotal = $revision['valida'] ? $precio * $cantidad : 0;

                                    if ($revision['valida']) {
                                        $totalUnidades += $cantidad;
                                        $totalPedido += $subtotal;
                                    }

                                    ?>



                                    <tr>

                                        <td class="fw-medium text-dark">

                                            <div class="d-flex align-items-center gap-3">



                                                <?php if (!empty($detalle['imagen_url'])): ?>

                                                    <img src="<?= htmlspecialchars($detalle['imagen_url']) ?>"

                                                         alt="Producto"

                                                         class="rounded"

                                                         style="width: 45px; height: 45px; object-fit: cover;">

                                                <?php endif; ?>



                                                <span>

                                                    <?= htmlspecialchars($detalle['producto']) ?>

                                                </span>



                                            </div>

                                        </td>



                                        <td class="text-center text-secondary">

                                            $<?= number_format($precio, 2) ?>

                                        </td>



                                        <!-- Cantidad solicitada -->

                                        <td class="text-center">

                                         <span class="cantidad-unidades px-3 py-2 rounded-pill fw-bold" style="background:#f8f9fa; color:#212529; border:1px solid #dee2e6;"><?= (int) ($detalle['cantidad'] ?? 0) ?></span>

</td>



                                        <!-- Subtotal -->

                                        <td class="text-end fw-semibold" style="color: #b85b74;">

                                            $<?= number_format($subtotal, 2) ?>

                                        </td>

                                    </tr>



                                <?php endforeach; ?>



                            </tbody>

                        </table>

                    </div>



                    <!-- Resumen del pedido -->

                    <div class="p-4 rounded-4 mt-4 shadow-sm"

                         style="background-color: #fcf6f0; border: 1px solid #f3e5dc;">



                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                            <span class="fw-bold text-muted">

                                Total de productos solicitados:

                            </span>



                            <span class="fw-bold fs-5" style="color: #b85b74;">

                                <?= $totalUnidades ?> unidades

                            </span>

                        </div>



                        <hr style="border-color: #ead8ca;">



                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div>

                                <span class="text-muted small d-block fw-bold">

                                    Estado Financiero

                                </span>



                                <span class="fw-bold fs-5" style="color: #b85b74;">

                                    Total del Pedido:

                                </span>

                            </div>



                            <span class="fs-3 fw-bold text-dark">

                                $<?= number_format($totalPedido, 2) ?>

                            </span>

                        </div>



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