<?php

require_once '../config/config.php';

try {
    $pdo = new \PDO("mysql:host=" . SERVER_NAME . ";dbname=" . DB_NAME, USER_NAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'start migration';
    $sql = <<<'SQL'
    CREATE TABLE IF NOT EXISTS `users` (
        `id` INT(10) UNSIGNED AUTO_INCREMENT,
        `email` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        `password` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        `confirm_email` BOOLEAN DEFAULT FALSE,
        `created_at` DATETIME DEFAULT NOW(),
        PRIMARY KEY (`id`)
    );
    CREATE TABLE IF NOT EXISTS `lists` (
        `id` INT(10) UNSIGNED AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        `user_id` INT(10) UNSIGNED NOT NULL,
        `created_at` DATETIME DEFAULT NOW(),
        PRIMARY KEY (`id`),
        FOREIGN KEY `FK_USER_LIST` (`user_id`) REFERENCES `users`(`id`)
    );
    CREATE TABLE IF NOT EXISTS `tasks` (
        `id` INT(10) UNSIGNED AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        `user_id` INT(10) UNSIGNED NOT NULL,
        `list_id` INT(10) UNSIGNED NOT NULL,
        `completed` BOOLEAN NOT NULL DEFAULT FALSE,
        `position` INT(11) NOT NULL  DEFAULT 1,
        `created_at` DATETIME DEFAULT NOW(),
        `completed_at` DATETIME DEFAULT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY `FK_USER_TASK` (`user_id`) REFERENCES `users`(`id`),
        FOREIGN KEY `FK_LIST_TASK` (`list_id`) REFERENCES `lists`(`id`)
    );

    SQL;
    $pdo->exec($sql);
    echo PHP_EOL;
    echo 'end migration';
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}
