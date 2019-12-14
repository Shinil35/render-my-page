CREATE USER 'web'@'%' IDENTIFIED WITH mysql_native_password BY 'E2ZAmXjDfXV0hjXC';
CREATE DATABASE IF NOT EXISTS `web`;
GRANT ALL PRIVILEGES ON `web`.* TO 'web'@'%';

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

USE `web`;

CREATE TABLE `flags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL,
  `flag` VARCHAR(64) NOT NULL,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` mediumtext NULL,
  `processed` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`);


ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pages`
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


-- Admin password: x2cg9qDwSQFA5JVDQT6h
INSERT INTO `users` (`username`, `password`, `rank`) VALUES ('admin', '$2y$10$bfnoIh6OssM0VK9NLwbkFOnZSZTTWTvoXZZwxKY5uiq7XFXMo/Wym', '7');

-- Test password: hdxGm6EgD9JvmmFNxT4x
INSERT INTO `users` (`username`, `password`, `rank`) VALUES ('test', '$2y$10$7sgiRgo/N8tJlfGBiKEkzObJrAXw3vInGhj4nBsw9PYLhPRHF125S', '0');

-- Insert flag page
INSERT INTO `pages` (`user_id`, `name`, `content`, `created`, `image`, `processed`) VALUES ('1', 'Flag', '<h1><strong>OMG!</strong></h1><h1>You found my biscuits..</h1><p><br></p><img src="/img/biscuits.jpg" style="height: 200px"><p><br></p><h1><strong style="color: rgb(178, 107, 0);"><em>flag{}</em></strong></h1><p><br></p><p><br></p><p><br></p><p><br></p>', CURRENT_TIMESTAMP, NULL, '0');