<?php

class TokenFormulario
{
    public static function generar(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function esValido($enviado, $guardado): bool
    {
        if (!is_string($enviado) || !is_string($guardado)) {
            return false;
        }

        if ($enviado === '' || $guardado === '') {
            return false;
        }

        return hash_equals($guardado, $enviado);
    }
}
