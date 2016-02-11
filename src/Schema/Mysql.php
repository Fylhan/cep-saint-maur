<?php
namespace Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE ".DB_PREFIX."news (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            date_start INT,
            date_update INT,
            date_end INT,
            state INT DEFAULT 0,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8");
}
