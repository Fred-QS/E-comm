-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 24, 2022 at 04:19 PM
-- Server version: 5.7.32
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laboutique`
--

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`) VALUES
(2, 'fred.geffray@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$C8HtfZshvVm6dTaIW1DWyuu1wafEzMnd9SGrvKJ8Yoq/6gFnQ36/C', 'Frédéric', 'Geffray');

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`) VALUES
(2, 2, 'Maison', 'Frédéric', 'GEFFRAY', NULL, 'Impasse de l\'ancienne Tuilerie, Bélingard Bas', '24240', 'Pomport', 'FR', '0662687011'),
(3, 2, 'Bureau', 'Frédéric', 'GEFFRAY', 'Smile', '2 rue des Jardins de l\'Ars', '33000', 'Bordeaux', 'FR', '0662687011');

--
-- Dumping data for table `carrier`
--

INSERT INTO `carrier` (`id`, `name`, `description`, `price`) VALUES
(1, 'Colissimo', 'Profitez d\'une livraison \"premium\" avec un colis chez dans les 72 prochaines heures.', 990),
(2, 'Chronopost', 'Profitez de la livraison \"express\" pour être livré le lendemain de votre commande.', 1490);

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Manteaux'),
(2, 'Bonnets'),
(3, 'T-shirts'),
(4, 'Echarpes');

--
-- Dumping data for table `header`
--

INSERT INTO `header` (`id`, `title`, `content`, `btn_title`, `btn_url`, `illustration`) VALUES
(1, 'Nouvelle collection', 'Découvrez la collection hivers 2022 créée par nos artisans couturiers.', 'Découvrir', '/nos-produits', '2edb5d934d75027262377a660404b66c1253b23d.jpg'),
(2, 'Le mois de la chemise', 'Un large choix de chemise à petits prix !', 'Visiter', '/nos-produits', '0efdf0e11a738d2530a6d6a6b6e3e80816870726.jpg'),
(3, 'Grand déstockage', 'C\'est l\'heure du grand déstockage ! On vous attend !', 'En profiter', '/nos-produits', 'cd08ffc2c3eed1fd4c35cc333894dac98a3a9b2a.jpg');

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `carrier_name`, `carrier_price`, `delivery`, `reference`, `stripe_session_id`, `state`) VALUES
(4, 2, '2022-07-20 07:53:16', 'Colissimo', 990, 'Frédéric GEFFRAY<br/>0662687011<br/>Impasse de l\'ancienne Tuilerie, Bélingard Bas<br/>24240 Pomport<br/>FR', '20072022-62d7b46c044b77.75754559', 'cs_test_b1yBocvJPu09h8QrLU6So4wyxRjBhDPnR6pjEemzIgqQmtjSzrRPjkshHA', 0),
(5, 2, '2022-07-20 09:35:09', 'Chronopost', 1490, 'Frédéric GEFFRAY<br/>0662687011<br/>Smile<br/>2 rue des Jardins de l\'Ars<br/>33000 Bordeaux<br/>FR', '20072022-62d7cc4d039319.99505074', 'cs_test_b1rZhmpJp39l6ULd8u08kxa0fkro7sLCNlSJCXG915fr81MIzVfjWDosTT', 3),
(6, 2, '2022-07-20 09:37:05', 'Chronopost', 1490, 'Frédéric GEFFRAY<br/>0662687011<br/>Smile<br/>2 rue des Jardins de l\'Ars<br/>33000 Bordeaux<br/>FR', '20072022-62d7ccc157f0f8.01525129', 'cs_test_b1zZ3LGcP6dd2jf3rKzSfOxwSwsZtQoAWUtfdXQdrFVc5T4thOd9R7fK3j', 0),
(7, 2, '2022-07-22 20:08:19', 'Colissimo', 990, 'Frédéric GEFFRAY<br/>0662687011<br/>Smile<br/>2 rue des Jardins de l\'Ars<br/>33000 Bordeaux<br/>FR', '22072022-62db03b3362c03.08034807', 'cs_test_b1r5TdrzIFGqWdY76jeRrziRpKkHIWcbps66bIAgJh7XpyDFjxHaixBc3P', 0);

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `my_order_id`, `product`, `quantity`, `price`, `total`) VALUES
(8, 4, 'Le bonnet du skier', 2, 1200, 2400),
(9, 4, 'L\'écharpe du lover', 1, 1900, 1900),
(10, 5, 'Le bonnet du skier', 1, 1200, 1200),
(11, 5, 'Le T-shirt basic', 2, 990, 1980),
(12, 6, 'Le bonnet du skier', 1, 1200, 1200),
(13, 6, 'Le T-shirt basic', 2, 990, 1980),
(14, 7, 'L\'écharpe du lover', 1, 1900, 1900);

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `slug`, `illustration`, `subtitle`, `description`, `price`, `is_best`) VALUES
(1, 2, 'Bonnet rouge', 'bonnet-rouge', '09c04a7354396c429b6b4cd1dc4eb29dda79c391.jpg', 'Le bonnet parfait pour l\'hivers', '<div>Curabitur aliquet quam id dui posuere blandit. Nulla porttitor accumsan tincidunt. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem.</div>', 900, 1),
(2, 2, 'Le bonnet du skier', 'le-bonnet-du-skier', '79265975865aeaa3e4b7211a4921ad3ae79be7d9.jpg', 'Le bonnet parfait pour le ski', '<div>Curabitur aliquet quam id dui posuere blandit. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Proin eget tortor risus. Nulla porttitor accumsan tincidunt. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus. Cras ultricies ligula sed magna dictum porta.</div>', 1200, 0),
(3, 4, 'L\'écharpe du lover', 'lecharpe-du-lover', '83a1315110b640521706068be6d9bd78f7bcaeaf.jpg', 'L\'écharpe parfaite pour les soirées romantiques', '<div>Curabitur aliquet quam id dui posuere blandit. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Proin eget tortor risus. Nulla porttitor accumsan tincidunt. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus. Cras ultricies ligula sed magna dictum porta.</div>', 1900, 1),
(4, 4, 'L\'écharpe du samedi soir', 'lecharpe-du-samedi-soir', '4786ae5ba864002d84f45c8aa491e6e567ec6e4a.jpg', 'L\'écharpe parfaite pour vos week-ends', '<div>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</div>', 1400, 0),
(5, 1, 'Le manteau de soirée', 'le-manteau-de-soiree', 'fb3004b32e054874ccbb80486c2313100a812e27.jpg', 'Le manteau français pour vois soirées', '<div>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</div>', 6900, 1),
(6, 1, 'Le manteau famille', 'le-manteau-famille', '26618884c4c8cc61a07bdaeb0807d8e30e25e4cc.jpg', 'Le manteau pour vos sorties en famille', '<div>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</div>', 1990, 0),
(7, 3, 'Le T-shirt manches longues', 'le-t-shirt-manches-longues', '6014d3ff1187b6c1d9665b5520c8ceea074f45ee.jpg', 'Le T-shirt taillé pour les hommes', '<div>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</div>', 1990, 0),
(8, 3, 'Le T-shirt basic', 'le-t-shirt-basic', '9bc6f77f19107e92b867c3ed553e6921c9d91f7c.jpg', 'Le T-shirt parfait pour les hommes', '<div>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</div>', 990, 1);

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 2, '62dcf906853374.39468817', '2022-07-24 07:47:18'),
(2, 2, '62dcf92ce60229.36431235', '2022-07-24 07:47:56'),
(3, 2, '62dcf9576675b1.30404621', '2022-07-24 07:48:39');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
