<?php
// controllers/AdminUsuarioController.php

require_once __DIR__ . '/../models/UsuarioModel.php';

class AdminUsuarioController {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Seguridad: Verificar rol de administrador
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?c=usuario&a=login");
            exit();
        }
        $this->usuarioModel = new UsuarioModel();
    }

    // Listar todos los usuarios
    public function index() {
        $usuarios = $this->usuarioModel->obtenerTodos();
        $pageTitle = "Gestión de Clientes | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/usuarios_index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Crear un nuevo cliente / administrador
    public function crear() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre']);
            $email     = trim($_POST['email']);
            $password  = $_POST['password'];
            $telefono  = trim($_POST['telefono']);
            $direccion = trim($_POST['direccion']);
            $rol       = $_POST['rol'];

            if ($this->usuarioModel->existeEmail($email)) {
                $error = "El correo electrónico ya está registrado.";
            } else {
                $this->usuarioModel->registrar($nombre, $email, $password, $telefono, $direccion);
                // Si el rol seleccionado es distinto de cliente, lo actualizamos
                if ($rol !== 'cliente') {
                    $u = $this->usuarioModel->obtenerPorEmail($email);
                    $this->usuarioModel->actualizar($u['usuario_id'], $nombre, $email, $telefono, $direccion, $rol);
                }
                header("Location: index.php?c=adminUsuario&a=index");
                exit();
            }
        }

        $pageTitle = "Nuevo Usuario | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/usuarios_form.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Editar un usuario existente
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $usuario = $this->usuarioModel->obtenerPorId($id);

        if (!$usuario) {
            header("Location: index.php?c=adminUsuario&a=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre']);
            $email     = trim($_POST['email']);
            $password  = $_POST['password'];
            $telefono  = trim($_POST['telefono']);
            $direccion = trim($_POST['direccion']);
            $rol       = $_POST['rol'];

            $this->usuarioModel->actualizar($id, $nombre, $email, $telefono, $direccion, $rol, $password);
            header("Location: index.php?c=adminUsuario&a=index");
            exit();
        }

        $pageTitle = "Editar Usuario | Blush & Glow";
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/admin/usuarios_form.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Eliminar usuario
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Evitar que el admin activo se elimine a sí mismo
        if ($id > 0 && $id !== (int)$_SESSION['usuario']['id']) {
            $this->usuarioModel->eliminar($id);
        }

        header("Location: index.php?c=adminUsuario&a=index");
        exit();
    }
}