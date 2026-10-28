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

    // Muestra el formulario de registro y procesa el envío
    public function registro() {
        $error = null;
        $exito = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = trim($_POST['password'] ?? '');
            $telefono  = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            // Expresión regular para validar la contraseña:
            // Mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número.
            $patronPassword = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/';

            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Todos los campos obligatorios (*) son requeridos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Ingresa un correo electrónico válido.";
            } elseif (!preg_match($patronPassword, $password)) {
                $error = "La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número.";
            } else {
                // Verificar si el correo ya está registrado
                if ($this->usuarioModel->obtenerPorEmail($email)) {
                    $error = "El correo electrónico ya se encuentra registrado.";
                } else {
                    // Cifrar la contraseña por seguridad
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $rol = 'cliente'; // Rol por defecto

                    // Nota: Si tu modelo acepta teléfono y dirección, puedes pasarlos aquí. 
                    // Si tu método crear() original solo recibe nombre, email, password y rol, 
                    // asegúrate de actualizar tu UsuarioModel para guardar también teléfono y dirección si lo deseas.
                    $creado = $this->usuarioModel->crear($nombre, $email, $passwordHash, $rol);

                    if ($creado) {
                        $exito = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión.";
                    } else {
                        $error = "Ocurrió un error al registrar la cuenta. Inténtalo de nuevo.";
                    }
                }
            }
        }

        $pageTitle = "Registro de Usuario | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/usuario/registro.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Muestra el formulario de login y autentica al usuario
    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = "Por favor ingresa tu correo y contraseña.";
            } else {
                $usuario = $this->usuarioModel->obtenerPorEmail($email);

                if ($usuario && password_verify($password, $usuario['password_hash'])) {
                    // Guardar datos clave en la sesión
                    $_SESSION['usuario'] = [
                        'id'     => $usuario['id'] ?? $usuario['id_usuario'],
                        'nombre' => $usuario['nombre'],
                        'email'  => $usuario['email'],
                        'rol'    => $usuario['rol']
                    ];

                    // Redirigir según el rol
                    if ($usuario['rol'] === 'admin') {
                        header("Location: index.php?c=adminProducto&a=index");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $error = "Credenciales incorrectas. Verifica tu correo o contraseña.";
                }
            }
        }

        $pageTitle = "Iniciar Sesión | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/usuario/login.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
    // Muestra la vista "Quiénes Somos"
    public function quienesSomos() {
        $pageTitle = "Quiénes Somos | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/usuario/quienes_somos.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Cierra la sesión activa
    public function logout() {
        unset($_SESSION['usuario']);
        session_destroy();
        header("Location: index.php?c=usuario&a=login");
        exit();
    }
}