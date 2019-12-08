CREATE USER 'web'@'%' IDENTIFIED WITH mysql_native_password BY 'E2ZAmXjDfXV0hjXC';
CREATE DATABASE IF NOT EXISTS `web`;
GRANT ALL PRIVILEGES ON `web`.* TO 'web'@'%';

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

USE `web`;

CREATE TABLE `flags` ( `id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(64) NOT NULL , `flag` VARCHAR(64) NOT NULL , `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Admin password: x2cg9qDwSQFA5JVDQT6h
INSERT INTO `users` (`username`, `password`, `rank`) VALUES ('admin', '$2y$10$bfnoIh6OssM0VK9NLwbkFOnZSZTTWTvoXZZwxKY5uiq7XFXMo/Wym', '7');

-- Test password: hdxGm6EgD9JvmmFNxT4x
INSERT INTO `users` (`username`, `password`, `rank`) VALUES ('test', '$2y$10$7sgiRgo/N8tJlfGBiKEkzObJrAXw3vInGhj4nBsw9PYLhPRHF125S', '0');