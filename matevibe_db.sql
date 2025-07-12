-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-06-2025 a las 17:33:16
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `matevibe_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(5, 'Accesorios'),
(3, 'Bombillas'),
(1, 'Mates'),
(4, 'Termos'),
(2, 'Yerbas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL CHECK (`precio` >= 0),
  `origen` varchar(255) NOT NULL,
  `durabilidad` tinyint(3) UNSIGNED NOT NULL CHECK (`durabilidad` between 1 and 5),
  `categoria_id` tinyint(3) UNSIGNED NOT NULL,
  `gramos` varchar(50) DEFAULT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `origen`, `durabilidad`, `categoria_id`, `gramos`, `imagen`, `destacado`, `creado_en`) VALUES
(6, 'Bombilla Pico De Loro', 'Con un diseño de pico de loro , esta bombilla cuenta con un ancho de 3 cm, lo que permite una excelente circulación del líquido y una experiencia óptima al beber.', 7500.00, 'Argentino', 3, 3, '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR4GsaA8XkrP3rQjtOiA4TzPQDAppsJpFe5Tg&s', 1, '2025-06-26 05:31:51'),
(7, 'Bombilla De Alpaca y Bronce Labrada', 'Es la pieza que filtra la yerba mate y permite disfrutar del sabor puro de esta bebida tan especial. Y aquí es donde la alpaca brilla (literalmente).', 9700.00, 'Argentino', 3, 3, '', 'https://digaloconmatepormayor.com/wp-content/uploads/2024/03/descarga-2024-03-07T005439.487.jpeg', 1, '2025-06-26 05:33:32'),
(11, 'Canarias', 'Preparada sin palos y gracias a una molienda muy fina, la Yerba Maté Canarias es una infusión amarga tradicional acorde con el consumo de mate en Uruguay.', 9500.00, 'Brasileño', 2, 2, '1 kilo', 'https://http2.mlstatic.com/D_NQ_NP_845351-MLA83207451627_032025-O.webp', 1, '2025-06-26 15:39:15'),
(13, 'Termo Contigo Ashland Chill', 'Agua caliente garantizada hasta por 35 horas. Capacidad de 1.2 litros. Con sistema antigoteo que evita pérdidas. Pico multidireccional que lo hace más práctico y funcional.', 70000.00, 'China', 4, 4, '', 'https://tiendachemate.com.ar/wp-content/uploads/2023/07/TermosMesa-de-trabajo-13.jpg', 1, '2025-06-26 16:19:49'),
(14, 'Termo Media Manija 1L', 'Contiene doble capa de acero inoxidable, manija y pico matero de gran precisión. Capacidad de 1 litro. Conserva el agua fría y caliente de manera óptima durante 8hs.', 28500.00, 'China', 4, 4, '', 'https://acdn-us.mitiendanube.com/stores/003/308/279/products/img_96211-27108639ba81ef7b9b16884381425433-640-0.jpeg', 1, '2025-06-26 16:26:31'),
(15, 'Matero Bolso Canasta', '100% CUERO, lo que te asegura durabilidad y tranquilidad a la hora de su uso en exteriores. También es fácil de limpiar y no necesita cuidados especiales.', 25000.00, 'Argentino', 5, 5, '', 'https://http2.mlstatic.com/D_627236-MLA85015108772_052025-C.jpg', 1, '2025-06-26 16:38:59'),
(16, 'Zet Azucarera y Yerbera Marwal', 'recipientes diseñados para almacenar y servir yerba mate y azúcar, respectivamente.', 19000.00, 'Argentino', 5, 5, '', 'https://torresyliva.com/wp-content/uploads/81083-1.jpg', 1, '2025-06-26 16:42:31'),
(17, 'Canarias Serena', 'La Yerba Mate Canarias Serena es una mezcla especial de la tradicional yerba mate Canarias con un toque de hierbas aromáticas.', 8500.00, 'Uruguay', 2, 2, '1 kilo', 'https://http2.mlstatic.com/D_NQ_NP_943833-MLA83504640563_042025-O.webp', 1, '2025-06-26 16:44:05'),
(18, 'Zet Azucarera y Yerbera de Ecocuero', 'n set de yerbera y azucarera de ecocuero consiste en dos recipientes, uno para la yerba mate y otro para el azúcar, ambos fabricados con cuero ecológico.', 12000.00, 'Argentino', 5, 5, '', 'https://http2.mlstatic.com/D_NQ_NP_897872-MLA46887507376_072021-O.webp', 1, '2025-06-26 16:49:02'),
(19, 'Termo Stanley Original Mate System Classic 1.2 Litros', 'Los termos de líquidos Stanley son unos termos muy resistentes y con unas prestaciones de conservación de la temperatura excepcionales.', 120000.00, 'Argentino', 4, 4, '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSiRBmRibBe72BaLYCZgb2Cyabp8brP08Gn3g&s', 1, '2025-06-26 16:51:39'),
(20, 'Mate de Madera Virola Acero', 'Un mate de madera con virola de acero, usualmente de algarrobo, es una pieza artesanal que combina la tradición del mate con la durabilidad y estética moderna.', 20000.00, 'Uruguay', 1, 1, '', 'https://tiendachemate.com.ar/wp-content/uploads/2023/08/Mate_Algarrobo_panzabaja_1.jpg', 1, '2025-06-26 16:54:03'),
(21, 'Bombilla Semi Recta De Acero Inoxidable Pico De Bronce', 'la Bombilla Semi Recta de Acero Inoxidable con Pico de Bronce, una pieza artesanal diseñada para los amantes del mate.', 7500.00, 'Argentino', 3, 3, '', 'https://d22fxaf9t8d39k.cloudfront.net/8af734a7db3a64aef312b880193be4913819c30a3c688dc28979f86a13318411193723.jpg', 1, '2025-06-26 16:57:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
