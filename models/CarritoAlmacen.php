<?php

class CarritoAlmacen
{
    public static function fusionar(array $local, array $guardado): array
    {
        foreach ($guardado as $clave => $item) {
            $productoId = (int) ($item['producto_id'] ?? $clave);

            if ($productoId < 1) {
                continue;
            }

            if (!isset($local[$productoId])) {
                $item['producto_id'] = $productoId;
                $item['cantidad'] = (int) ($item['cantidad'] ?? 0);
                $local[$productoId] = $item;
                continue;
            }

            $local[$productoId]['cantidad'] = (int) $local[$productoId]['cantidad'] + (int) ($item['cantidad'] ?? 0);
        }

        return $local;
    }
}
