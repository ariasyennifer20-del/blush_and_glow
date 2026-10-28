<?php
// models/UsuarioModel.php

require_once __DIR__ . '/../config/Database.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Método 'crear' para que coincida con la llamada de UsuarioController.php
    public function crear($nombre, $email, $password, $rol = 'cliente', $telefono = null, $direccion = null) {
        // Si el controlador ya le envió la contraseña hasheada, la usamos directamente
        // De lo contrario, la hasheamos aquí.
        $passwordHash = (password_get_info($password)['algo'] !== 0) 
            ? $password 
            : password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (nombre, email, password_hash, telefono, direccion, rol) 
                VALUES (:nombre, :email, :password_hash, :telefono, :direccion, :rol)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'        => $nombre,
            ':email'         => $email,
            ':password_hash' => $passwordHash,
            ':telefono'      => $telefono,
            ':direccion'     => $direccion,
            ':rol'           => $rol
        ]);
    }

    // Registrar un nuevo cliente (Mantenido para compatibilidad)
    public function registrar($nombre, $email, $password, $telefono = null, $direccion = null) {
        return $this->crear($nombre, $email, $password, 'cliente', $telefono, $direccion);
    }

    // Buscar un usuario por su correo electrónico
    public function obtenerPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Verificar si un correo electrónico ya está registrado
    public function existeEmail($email) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    // Obtener un usuario o cliente por su ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE usuario_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Actualizar un usuario o cliente existente
    public function actualizar($id, $nombre, $email, $telefono, $direccion, $rol, $password = null) {
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, email = :email, telefono = :telefono, 
                        direccion = :direccion, rol = :rol, password_hash = :password_hash 
                    WHERE usuario_id = :id";
            $params = [
                ':id'            => $id,
                ':nombre'        => $nombre,
                ':email'         => $email,
                ':telefono'      => $telefono,
                ':direccion'     => $direccion,
                ':rol'           => $rol,
                ':password_hash' => $passwordHash
            ];
        } else {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, email = :email, telefono = :telefono, 
                        direccion = :direccion, rol = :rol 
                    WHERE usuario_id = :id";
            $params = [
                ':id'        => $id,
                ':nombre'    => $nombre,
                ':email'     => $email,
                ':telefono'  => $telefono,
                ':direccion' => $direccion,
                ':rol'       => $rol
            ];
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Obtener la lista completa de usuarios/clientes
    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios ORDER BY usuario_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}