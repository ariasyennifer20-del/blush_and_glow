<?php

require_once __DIR__ . '/../models/SesionUsuario.php';
require_once __DIR__ . '/../models/AccesoPedido.php';
require_once __DIR__ . '/../models/TokenFormulario.php';
require_once __DIR__ . '/../models/ReglasCatalogo.php';
require_once __DIR__ . '/../models/CarritoPrecios.php';
require_once __DIR__ . '/../models/CarritoAlmacen.php';
require_once __DIR__ . '/../models/ReglasPedido.php';

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

$sesion = SesionUsuario::desdeFila([
    'usuario_id' => '4',
    'nombre' => 'Laura',
    'email' => 'laura@example.com',
    'rol' => 'cliente',
]);
probar('la sesión guarda el usuario_id real', $sesion['usuario_id'] === 4 && $sesion['id'] === 4);

$sinId = SesionUsuario::desdeFila([
    'nombre' => 'Ana',
    'email' => 'ana@example.com',
    'rol' => 'cliente',
]);
probar('sin columna de id la sesión no inventa uno', $sinId['usuario_id'] === null && $sinId['id'] === null);

probar('el dueño ve su pedido', AccesoPedido::puedeVer(4, 4, 'cliente') === true);
probar('otro cliente no ve el pedido', AccesoPedido::puedeVer(4, 9, 'cliente') === false);
probar('el admin ve cualquier pedido', AccesoPedido::puedeVer(4, 1, 'admin') === true);
probar('sin id de sesión no se ve el pedido', AccesoPedido::puedeVer(4, null, 'cliente') === false);

$token = TokenFormulario::generar();
probar('el token del checkout se acepta una vez', TokenFormulario::esValido($token, $token) === true);
probar('un token distinto se rechaza', TokenFormulario::esValido($token, TokenFormulario::generar()) === false);
probar('un token vacío se rechaza', TokenFormulario::esValido('', $token) === false);

probar('un producto con pedidos no se borra', ReglasCatalogo::puedeEliminarProducto(2) === false);
probar('un producto sin pedidos sí se borra', ReglasCatalogo::puedeEliminarProducto(0) === true);
probar('un cliente con pedidos no se borra', ReglasCatalogo::puedeEliminarUsuario(1) === false);
probar('un cliente sin pedidos sí se borra', ReglasCatalogo::puedeEliminarUsuario(0) === true);

$actualizado = CarritoPrecios::conPrecioActual(
    ['nombre' => 'Labial viejo', 'precio' => 10, 'valor' => 10, 'cantidad' => 2],
    '14.50',
    'Labial nuevo'
);
probar(
    'el checkout usa el precio actual de la base',
    $actualizado['precio'] === 14.5
    && $actualizado['nombre'] === 'Labial nuevo'
    && !isset($actualizado['valor'])
    && $actualizado['cantidad'] === 2
);

$checkout = file_get_contents(__DIR__ . '/../controllers/pedidoController.php');
probar(
    'el estado inicial del pedido usa el valor del enum',
    strpos($checkout, "'pendiente'") !== false && strpos($checkout, "'Pendiente'") === false
);

$ficha = file_get_contents(__DIR__ . '/../views/productos/detalle.php');
probar('la ficha de producto muestra el nombre del producto', strpos($ficha, '$producto[\'nombre\']') !== false);
probar('la ficha de producto ya no es un detalle de pedido', strpos($ficha, 'Detalle del Pedido') === false);

$fusion = CarritoAlmacen::fusionar(
    [1 => ['producto_id' => 1, 'cantidad' => 1, 'nombre' => 'Labial']],
    [1 => ['producto_id' => 1, 'cantidad' => 2, 'nombre' => 'Labial'], 3 => ['producto_id' => 3, 'cantidad' => 1, 'nombre' => 'Sérum']]
);
probar('el carrito guardado se suma al de la sesión', $fusion[1]['cantidad'] === 3 && $fusion[3]['cantidad'] === 1);

probar('un pedido pendiente no cuenta como ingreso', ReglasPedido::cuentaComoIngreso('pendiente') === false);
probar('un pedido pagado cuenta como ingreso', ReglasPedido::cuentaComoIngreso('pagado') === true);
probar('cancelar un pedido devuelve el stock', ReglasPedido::movimientoStock('pendiente', 'cancelado') === 'devolver');
probar('reactivar un pedido descuenta el stock', ReglasPedido::movimientoStock('cancelado', 'pagado') === 'descontar');
probar('pasar de pendiente a pagado no mueve el stock otra vez', ReglasPedido::movimientoStock('pendiente', 'pagado') === 'ninguno');
probar('el estado con mayúscula no es válido', ReglasPedido::esEstadoValido('Pendiente') === false);

echo $fallos === 0
    ? "\nTodas las pruebas pasaron.\n"
    : "\n$fallos prueba(s) fallaron.\n";

exit($fallos === 0 ? 0 : 1);
