<?php

class CarritoPrecios
{
    public static function conPrecioActual(array $item, $precio, $nombre = null): array
    {
        $item['precio'] = (float) $precio;

        if ($nombre !== null && $nombre !== '') {
            $item['nombre'] = $nombre;
        }

        unset($item['valor']);

        return $item;
    }
}
