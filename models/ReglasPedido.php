<?php

class ReglasPedido
{
    public static function estadosValidos(): array
    {
        return ['pendiente', 'pagado', 'enviado', 'completado', 'cancelado'];
    }

    public static function esEstadoValido($estado): bool
    {
        return is_string($estado) && in_array($estado, self::estadosValidos(), true);
    }

    public static function cuentaComoIngreso($estado): bool
    {
        return in_array($estado, ['pagado', 'enviado', 'completado'], true);
    }

    public static function movimientoStock($estadoActual, $estadoNuevo): string
    {
        if ($estadoActual === $estadoNuevo) {
            return 'ninguno';
        }

        if ($estadoNuevo === 'cancelado') {
            return 'devolver';
        }

        if ($estadoActual === 'cancelado') {
            return 'descontar';
        }

        return 'ninguno';
    }
}
