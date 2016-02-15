<?php
namespace Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE " . DB_PREFIX . "news (
            id INTEGER PRIMARY KEY,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            date_start INTEGER,
            date_update INTEGER,
            date_end INTEGER,
            state INTEGER DEFAULT 0
        )
    ");
    
    $pdo->exec("
        CREATE TABLE " . DB_PREFIX . "upload (
            id INTEGER PRIMARY KEY,
            title TEXT NULL,
            image TEXT NOT NULL,
            thumb TEXT NULL,
            date int
        )
    ");
}
