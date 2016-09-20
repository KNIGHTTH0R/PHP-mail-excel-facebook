<?php
require_once 'db.php';
$db = connect();
if ($db) {
    clearAll($db);
    $db->close();
} else {
    $_SESSION[$SESSION_DB_MESSAGE] = 'Can\'t connect to database.';
}