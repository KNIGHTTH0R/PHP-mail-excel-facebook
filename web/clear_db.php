<?php
require_once 'db.php';
$db = connect();
if ($db) {
    clearAll($db);
    function deleteForExt($ext)
    {
        foreach (glob(getcwd() . '/../' . 'files/*.' . $ext) as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }
    }

    deleteForExt('xls');
    deleteForExt('xlsx');

    $db->close();
} else {
    $_SESSION[$SESSION_DB_MESSAGE] = 'Can\'t connect to database.';
}
header("Location: index.php");