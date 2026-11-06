<?php

require_once __DIR__ . '/../models/ValidadorCantidad.php';

$fallos = 0;

function probar($nombre, $condicion) {
    global $fallos;
    if ($condicion) {
        echo "OK  $nombre\n";
        return;
    }
    $fallos++;
    echo "FALLO  $nombre\n";
}

$ok = ValidadorCantidad::revisarUnidades(2);
probar('2 unidades es válida', $ok['valida'] === true && $ok['texto'] === '2 unidades' && $ok['cantidad'] === 2);
 
$cero = ValidadorCantidad::revisarUnidades(0);
probar('0 unidades no es válida', $cero['valida'] === false && $cero['cantidad'] === 0 && $cero['texto'] === 'Cantidad no válida');

$negativa = ValidadorCantidad::revisarUnidades(-3);
probar('cantidad negativa no es válida', $negativa['valida'] === false);

$texto = ValidadorCantidad::revisarUnidades('abc');
probar('texto no numérico no es válido', $texto['valida'] === false);

$cadena = ValidadorCantidad::revisarUnidades('4');
probar('cantidad en texto numérico se acepta', $cadena['valida'] === true && $cadena['cantidad'] === 4);

probar(
    'no deja pedir más unidades que el stock',
    ValidadorCantidad::mensaje(6, 5, 'Labial') !== null
);

probar(
    'permite pedir exactamente el stock',
    ValidadorCantidad::mensaje(5, 5, 'Labial') === null
);

probar(
    'rechaza cantidad menor a 1 aunque haya stock',
    ValidadorCantidad::mensaje(0, 10, 'Sérum') !== null
);

$sumarOk = ValidadorCantidad::sumar(2, 1, 5, 'Rubor');
probar('sumar dentro del stock', $sumarOk['error'] === null && $sumarOk['cantidad'] === 3);

$sumarExceso = ValidadorCantidad::sumar(2, 4, 5, 'Rubor');
probar('sumar por encima del stock se rechaza', $sumarExceso['error'] !== null && $sumarExceso['cantidad'] === null);

$sumarNegativa = ValidadorCantidad::sumar(1, -5, 10, 'Brochas');
probar('sumar una cantidad negativa se rechaza', $sumarNegativa['error'] !== null);

$fijar = ValidadorCantidad::fijar(8, 3, 'Base');
probar('fijar más unidades que el stock se rechaza', $fijar['error'] !== null && $fijar['cantidad'] === null);

$detalles = [
    ['producto' => 'Labial', 'precio_unitario' => 10, 'cantidad' => 2, 'imagen_url' => ''],
    ['producto' => 'Sérum', 'precio_unitario' => 20, 'cantidad' => 0, 'imagen_url' => ''],
    ['producto' => 'Base', 'precio_unitario' => 15, 'cantidad' => -1, 'imagen_url' => ''],
];

$totalUnidades = 0;
$totalPedido = 0;
foreach ($detalles as $detalle) {
    $revision = ValidadorCantidad::revisarUnidades($detalle['cantidad'] ?? null);
    if ($revision['valida']) {
        $totalUnidades += $revision['cantidad'];
        $totalPedido += (float) $detalle['precio_unitario'] * $revision['cantidad'];
    }
}

probar('la tabla solo suma unidades válidas', $totalUnidades === 2 && $totalPedido === 20.0);

$_GET['id'] = '9';
ob_start();
require __DIR__ . '/../views/cliente/pedido/detalle.php';
$html = ob_get_clean();

probar('la tabla pinta las unidades válidas', strpos($html, 'cantidad-unidades') !== false && strpos($html, '>2<') !== false);
probar('la tabla muestra cero y la cantidad negativa', strpos($html, '>0<') !== false && strpos($html, '>-1<') !== false);
probar('la tabla no suma la línea inválida al total', strpos($html, '$20.00') !== false && strpos($html, '$30.00') === false);

echo $fallos === 0
    ? "\nTodas las pruebas pasaron.\n"
    : "\n$fallos prueba(s) fallaron.\n";

exit($fallos === 0 ? 0 : 1);
