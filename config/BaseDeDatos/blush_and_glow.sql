-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-09-2026 a las 16:31:34
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blush_and_glow`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `carrito_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`carrito_id`, `usuario_id`, `fecha_actualizacion`) VALUES
(1, 2, '2026-08-10 14:30:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_items`
--

CREATE TABLE `carrito_items` (
  `item_id` int(11) NOT NULL,
  `carrito_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrito_items`
--

INSERT INTO `carrito_items` (`item_id`, `carrito_id`, `producto_id`, `cantidad`) VALUES
(1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `nombre`, `descripcion`) VALUES
(1, 'Maquillaje', 'Bases, labiales, rubores, sombras y correctores de alta calidad.'),
(2, 'Skincare', 'Sérums, mascarillas, limpiadores e hidratantes para el cuidado facial.'),
(3, 'Brochas & Accesorios', 'Herramientas de aplicación suaves y esponjas profesionales.'),
(4, 'Pestañas & Ojos', 'Mascara de pestañas, delineadores y pestañas postizas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `detalle_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`detalle_id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 16, 2, '5.00'),
(2, 2, 7, 1, '32.00'),
(3, 3, 16, 1, '5.00'),
(4, 4, 7, 1, '32.00'),
(5, 5, 16, 1, '5.00'),
(6, 6, 7, 1, '32.00'),
(7, 7, 4, 1, '24.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `pedido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','completado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `direccion_envio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `usuario_id`, `total`, `estado`, `direccion_envio`, `fecha_pedido`) VALUES
(1, 1, '10.00', 'pagado', 'calle20', '2026-08-31 13:16:52'),
(2, 1, '32.00', 'pagado', 'calle20', '2026-08-31 13:38:22'),
(3, 1, '5.00', 'pagado', 'calle15', '2026-08-31 15:24:41'),
(4, 4, '32.00', 'pagado', 'clle15', '2026-08-31 15:25:56'),
(5, 4, '5.00', 'pagado', 'callw15', '2026-08-31 15:48:31'),
(6, 1, '32.00', 'pagado', 'calle20', '2026-09-07 12:57:08'),
(7, 4, '24.50', 'pagado', 'calle20', '2026-09-07 13:02:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `imagen_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default-product.jpg',
  `es_destacado` tinyint(1) DEFAULT '0',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `es_destacado`, `fecha_creacion`) VALUES
(1, 1, 'Rubor tipo Crema Silk Glow', 'Aporta un acabado jugoso, natural y luminoso a tus mejillas con fórmula de larga duración.', '18.50', 45, 'https://m.media-amazon.com/images/I/613MwIsAG3L._AC_SL1500_.jpg', 1, '2026-08-10 14:30:08'),
(2, 1, 'Labial Velvet Matte - Nude Rose', 'Labial líquido de acabado mate terciopelo sin resecar los labios.', '14.00', 60, 'https://www.lolastore.co/wp-content/uploads/Captura-de-pantalla-2024-03-19-174236.png', 1, '2026-08-10 14:30:08'),
(3, 1, 'Base Fluida Satin Finish', 'Base ligera de cobertura media construible con efecto piel luminosa.', '28.00', 30, 'https://beautycreations.es/wp-content/uploads/2024/10/Copia-de-FEED-BEAUTY-CREATIONS-2.png', 0, '2026-08-10 14:30:08'),
(4, 2, 'Sérum Hidratante Ácido Hialurónico', 'Fórmula ultra hidratante que restaura la barrera cutánea y aporta firmeza.', '24.50', 49, 'https://www.lorealparis.cl/-/media/project/loreal/brand-sites/oap/americas/latam/products/skin-care/revitalift/serum-rostro-ojos/pack-acido-hialurnico-srum-ojos-srum-rostro.png?w=360&rev=58cf848d611548e888d3ffd933fc25e6&hash=77653F60B2643D5E3F642D833BAC78', 1, '2026-08-10 14:30:08'),
(5, 2, 'Mascarilla Facial Calmante de Rosas', 'Mascarilla nocturna que calma la irritación y devuelve la luminosidad.', '16.00', 25, 'https://m.media-amazon.com/images/I/81-eq0CgH8L._SL1500_.jpg', 0, '2026-08-10 14:30:08'),
(6, 2, 'Limpiador Facial Espumoso Soft Touch', 'Limpia profundamente los poros sin retirar la hidratación natural de la piel.', '19.00', 40, 'https://tse3.mm.bing.net/th/id/OIP.P-QiAa1LdUXRCuqxjKQYHwHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0, '2026-08-10 14:30:08'),
(7, 3, 'Set de Brochas Soft Touch (8 piezas)', 'Cerdas sintéticas ultra suaves aptas para productos líquidos y en polvo.', '32.00', 17, 'https://tse4.mm.bing.net/th/id/OIP.zGly9G4cF7KlgDsACr2SfgHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1, '2026-08-10 14:30:08'),
(8, 4, 'Mascara de Pestañas Volume & Lift', 'Pestañas con volumen extremo y elevación durante 24 horas sin grumos.', '15.50', 55, 'https://tse3.mm.bing.net/th/id/OIP.-_W0JZiYmRkpz3tJcTPnrgHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0, '2026-08-10 14:30:08'),
(9, 1, 'sombras mate y escarchadas', 'Una sombra es una zona oscura que se forma cuando un objeto bloquea la luz, proyectando una silueta o área de oscuridad detrás de él.', '30.00', 10, 'https://makschool.es/wp-content/uploads/2022/02/20210407-20210324-sarompas-20210324-OKSANA-BODEGONES8258.jpg', 0, '2026-08-24 13:42:24'),
(10, 1, 'gloshos', 'El brillo de labios, más conocido como brillo labial, es un producto cosmético utilizado principalmente para resaltar los labios con un toque de brillantez y color.', '6.00', 10, 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjVVwJ65rO7M1Yru-J_9mmmWFX214X3-BY-RgXLT0oaAefg5eOVDop_UfxK2eSRJbUQmeBXyaFdNXGhi9aMxWoYjvKcNSeJ5QoOVYckxReqoTZB6zFpFJK4yPGV-hEWQ9FbgxSPy8Qm63xgjgf86r9D74IG1ngbsj7kF-I5MKgeHM8anDwaTqeqirm_NA/s1600/d', 0, '2026-08-24 13:48:33'),
(11, 1, 'sombras liquidas', 'Las sombras líquidas son un tipo de maquillaje diseñado para dar color a los ojos con una textura fluida y fácil de aplicar.', '5.00', 10, 'https://madalecosmeticos.com/wp-content/uploads/2023/10/Moira-Diamond-Daze-Sombra-liquida_0001_Niveles-1.png', 0, '2026-08-24 13:51:36'),
(12, 1, 'sombras escarchadas', 'Las sombras escarchadas son un tipo de pigmento cosmético que combina una textura fina, brillante y ligeramente cristalina, creando un efecto de \"brillo helado\" que refleja la luz de manera irregular.', '5.00', 10, 'https://www.pigmentta.com/wp-content/uploads/2024/09/Sombras-Escarcha-Party-Glitter-Trendy.jpg', 0, '2026-08-24 13:55:20'),
(13, 1, 'lápiz de ojo', 'Lápiz retráctil: No necesita sacapuntas, ya que la mina se gira para salir, es más cremoso y fácil de aplicar.', '5.00', 10, 'https://www.pigmentta.com/wp-content/uploads/2024/01/Lapiz-de-Ojos-Trendy-1.png', 0, '2026-08-24 13:58:44'),
(14, 1, 'pestañina fortalecedora', 'La pestañina fortalecedora es un producto diseñado para realzar, definir y dar volumen a las pestañas, mejorando su apariencia y brillo.', '6.00', 10, 'https://tse4.mm.bing.net/th/id/OIP.M6MsAPfwWK8ZnoyhyprhxAHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0, '2026-08-24 14:03:10'),
(15, 1, 'delineador', 'Un delineador es un cosmético utilizado para definir y resaltar el contorno de los ojos, aumentando su expresividad y contribuyendo al maquillaje general.', '4.00', 10, 'https://production.na01.natura.com/on/demandware.static/-/Sites-natura-pe-storefront-catalog/default/dwd09cfbe9/products/NATPER-95757_1.jpg', 0, '2026-08-24 14:06:08'),
(16, 1, 'delineador de colores', 'Los delineadores de colores son productos de maquillaje que permiten resaltar la mirada con tonos más allá del clásico negro', '5.00', 6, 'https://m.media-amazon.com/images/I/71GQ5Ht5wcL._SL1001_.jpg', 0, '2026-08-24 14:07:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `rol` enum('cliente','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `email`, `password_hash`, `telefono`, `direccion`, `rol`, `fecha_registro`) VALUES
(1, 'Admin Blush', 'admin@blushglow.com', '$2y$10$pd57RZpcQxKrCxAdRMIncuMi4R9CuGMkR5PXFXf9AP02ziBUoayYa', '555-0100', 'Oficina Central #102', 'admin', '2026-08-10 14:30:08'),
(2, 'Laura Gómez', 'laura@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1b9A2yF2Lw1Bq3c4d5e6f7g8h9i0j1k', '555-0245', 'Calle Rose #45, Apto 3B', 'cliente', '2026-08-10 14:30:08'),
(3, 'Camila Torres', 'camila@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1b9A2yF2Lw1Bq3c4d5e6f7g8h9i0j1k', '555-0389', 'Av. De las Flores #120', 'cliente', '2026-08-10 14:30:08'),
(4, 'Washington Nieto', 'washington79289@gmail.com', '$2y$10$pd57RZpcQxKrCxAdRMIncuMi4R9CuGMkR5PXFXf9AP02ziBUoayYa', '', '', 'cliente', '2026-08-19 13:37:09'),
(5, 'danna', 'dana@gmail.com', '$2y$10$1YWB3EeOW8/Ty/PZrnibI.KfKd2JLD051eKOo8kaptmnXVNT1IXuu', '3104600795', 'calle20', 'cliente', '2026-08-31 14:23:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`carrito_id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_items_carritos` (`carrito_id`),
  ADD KEY `fk_items_productos` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `fk_detalle_pedidos` (`pedido_id`),
  ADD KEY `fk_detalle_productos` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedido_id`),
  ADD KEY `fk_pedidos_usuarios` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `fk_productos_categorias` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `carrito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `fk_carritos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD CONSTRAINT `fk_items_carritos` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`carrito_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_items_productos` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `fk_detalle_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`pedido_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_productos` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
