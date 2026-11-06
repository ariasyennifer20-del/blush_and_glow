<?php

class AccesoPedido
{
    public static function puedeVer($duenoId, $sesionId, $rol): bool
    {
        if ($rol === 'admin') {
            return true;
        }

        if ($duenoId === null || $sesionId === null || $duenoId === '' || $sesionId === '') {
            return false;
        }

        return (int) $duenoId === (int) $sesionId;
    }
}
