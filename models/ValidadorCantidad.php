<?php

class ValidadorCantidad
{
    public static function entero($cantidad): int
    {
        if (is_int($cantidad)) {
            return $cantidad;
        }

        if (is_float($cantidad)) {
            if (floor($cantidad) != $cantidad) {
                return 0;
            }
            return (int) $cantidad;
        }

        if (is_string($cantidad) && preg_match('/^-?\d+$/', trim($cantidad))) {
            return (int) trim($cantidad);
        }

        return 0;
    }

    public static function revisarUnidades($cantidad): array
    {
        $unidades = self::entero($cantidad);

        if ($unidades < 1) {
            return [
                'cantidad' => 0,
                'valida' => false,
                'texto' => 'Cantidad no válida',
            ];
        }

        return [
            'cantidad' => $unidades,
            'valida' => true,
            'texto' => $unidades . ' unidades',
        ];
    }

    public static function mensaje($cantidad, $stock, $nombre = 'Producto'): ?string
    {
        $unidades = self::entero($cantidad);
        $disponible = self::entero($stock);

        if ($disponible < 0) {
            $disponible = 0;
        }

        if ($unidades < 1) {
            return 'La cantidad de unidades de "' . $nombre . '" debe ser mayor a cero.';
        }

        if ($unidades > $disponible) {
            return 'Stock insuficiente para "' . $nombre . '" (solicitaste '
                . $unidades . ' unidades y solo hay ' . $disponible . ' disponibles).';
        }

        return null;
    }

    public static function sumar($actual, $agregar, $stock, $nombre = 'Producto'): array
    {
        $agregar = self::entero($agregar);

        if ($agregar < 1) {
            return [
                'cantidad' => null,
                'error' => 'La cantidad de unidades de "' . $nombre . '" debe ser mayor a cero.',
            ];
        }

        $total = self::entero($actual) + $agregar;
        $error = self::mensaje($total, $stock, $nombre);

        if ($error !== null) {
            return ['cantidad' => null, 'error' => $error];
        }

        return ['cantidad' => $total, 'error' => null];
    }

    public static function fijar($cantidad, $stock, $nombre = 'Producto'): array
    {
        $error = self::mensaje($cantidad, $stock, $nombre);

        if ($error !== null) {
            return ['cantidad' => null, 'error' => $error];
        }

        return ['cantidad' => self::entero($cantidad), 'error' => null];
    }

    public static function descontar($stock, $cantidad, $nombre = 'Producto'): array
    {
        $error = self::mensaje($cantidad, $stock, $nombre);

        if ($error !== null) {
            return ['stock' => self::entero($stock), 'error' => $error];
        }

        return [
            'stock' => self::entero($stock) - self::entero($cantidad),
            'error' => null,
        ];
    }
}
