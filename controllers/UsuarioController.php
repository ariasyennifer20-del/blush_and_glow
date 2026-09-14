<?php
// controllers/UsuarioController.php

require_once __DIR__ . '/../models/UsuarioModel.php';

class UsuarioController {

    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->usuarioModel = new UsuarioModel();
    }

    // Vista y procesamiento del Registro de Usuario
    public function registro() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            $telefono  = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Por favor completa todos los campos obligatorios.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El correo electrónico no es válido.";
            } elseif ($this->usuarioModel->existeEmail($email)) {
                $error = "El correo electrónico ya se encuentra registrado.";
            } else {
                $registrado = $this->usuarioModel->registrar($nombre, $email, $password, $telefono, $direccion);

                if ($registrado) {
                    header("Location: index.php?c=usuario&a=login&mensaje=registrado");
                    exit();
                } else {
                    $error = "Ocurrió un error al procesar el registro. Inténtalo de nuevo.";
                }
            }
        }

        $pageTitle = "Registro | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/usuario/registro.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Vista y procesamiento del Inicio de Sesión
    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Ingresa tu correo y contraseña.";
            } else {
                $usuario = $this->usuarioModel->obtenerPorEmail($email);

                // Validar existencia del usuario y verificar contraseña
                if ($usuario && password_verify($password, $usuario['password_hash'])) {
                    // Iniciar sesión y guardar datos básicos del usuario
                    $_SESSION['usuario'] = [
                        'id'     => $usuario['usuario_id'],
                        'nombre' => $usuario['nombre'],
                        'email'  => $usuario['email'],
                        'rol'    => $usuario['rol']
                    ];

                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Credenciales incorrectas. Verifica tu correo y contraseña.";
                }
            }
        }

        $pageTitle = "Iniciar Sesión | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/usuario/login.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Cerrar sesión
    public function logout() {
        unset($_SESSION['usuario']);
        session_destroy();
        header("Location: index.php");
        exit();
    }
}