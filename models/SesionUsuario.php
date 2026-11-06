<?php

class SesionUsuario
{
    public static function desdeFila(array $usuario): array
    {
        $id = $usuario['usuario_id'] ?? $usuario['id'] ?? $usuario['id_usuario'] ?? null;

        if ($id === null || $id === '') {
            $id = null;
        } else {
            $id = (int) $id;
        }

        return [
            'usuario_id' => $id,
            'id' => $id,
            'nombre' => $usuario['nombre'] ?? '',
            'email' => $usuario['email'] ?? '',
            'rol' => $usuario['rol'] ?? 'cliente',
        ];
    }
}
