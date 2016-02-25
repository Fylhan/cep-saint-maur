<?php
try {
    require (__DIR__ . '/src/common.php');
    $container['router']->dispatch();
} catch (Exception $e) {
    echo 'Internal Error: ' . $e->getMessage();
}