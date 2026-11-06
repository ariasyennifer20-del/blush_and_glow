<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white text-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                        ✓
                    </div>
                </div>
                
                <h2 class="fw-bold mb-2" style="color: #333;">Pedido registrado</h2>
                <p class="text-muted mb-4">
                    Tu pedido quedó <strong>pendiente</strong> y el inventario se reservó. El pago se confirma cuando el pedido pase a pagado. Gracias por confiar en <strong>Blush & Glow</strong>.
                </p>

                <?php $pedidoId = $_GET['id'] ?? ''; ?>
                <?php if ($pedidoId): ?>
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <span class="text-secondary small d-block">Número de pedido:</span>
                        <span class="fs-5 fw-bold text-dark">#<?php echo htmlspecialchars($pedidoId); ?></span>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <a href="index.php?c=cliente&a=perfil" class="btn text-white rounded-pill py-2 fw-semibold shadow-sm" style="background-color: #d4a373; border: none;">
                        Ver Mis Pedidos
                    </a>
                    <a href="index.php?c=producto&a=catalogo" class="btn btn-outline-secondary rounded-pill py-2 fw-semibold">
                        Seguir Comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>