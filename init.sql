CREATE DATABASE IF NOT EXISTS `upm_dp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT USAGE ON *.* TO `pex`@`localhost` IDENTIFIED BY 'pex';
GRANT ALL PRIVILEGES ON `upm_dp`.* TO `pex`@`localhost`;
FLUSH PRIVILEGES;