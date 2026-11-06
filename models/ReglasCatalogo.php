<?php

class ReglasCatalogo
{
    public static function puedeEliminarProducto($pedidosAsociados): bool
    {
        return (int) $pedidosAsociados < 1;
    }

    public static function puedeEliminarUsuario($pedidosAsociados): bool
    {
        return (int) $pedidosAsociados < 1;
    }
}
