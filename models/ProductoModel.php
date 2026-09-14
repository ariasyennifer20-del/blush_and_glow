<?php
// models/ProductoModel.php

require_once __DIR__ . '/../config/Database.php';

class ProductoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener destacados
    public function obtenerDestacados() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.es_destacado = 1 
                ORDER BY p.producto_id DESC 
                LIMIT 6";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener por ID
    public function obtenerPorId($id) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.producto_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Obtener por categoría
    public function obtenerPorCategoria($categoriaId) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.categoria_id = :categoria_id 
                ORDER BY p.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':categoria_id' => $categoriaId]);
        return $stmt->fetchAll();
    }

    // Guardar un nuevo producto
    public function crear($nombre, $descripcion, $precio, $stock, $categoriaId, $imagenUrl, $esDestacado) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen_url, es_destacado) 
                VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen_url, :es_destacado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':precio'       => $precio,
            ':stock'        => $stock,
            ':categoria_id' => $categoriaId,
            ':imagen_url'   => $imagenUrl,
            ':es_destacado' => $esDestacado
        ]);
    }

    // Actualizar producto
    public function actualizar($id, $nombre, $descripcion, $precio, $stock, $categoriaId, $imagenUrl, $esDestacado) {
        $sql = "UPDATE productos 
                SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, 
                    categoria_id = :categoria_id, imagen_url = :imagen_url, es_destacado = :es_destacado 
                WHERE producto_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':precio'       => $precio,
            ':stock'        => $stock,
            ':categoria_id' => $categoriaId,
            ':imagen_url'   => $imagenUrl,
            ':es_destacado' => $esDestacado
        ]);
    }

    // Eliminar producto
    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE producto_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}