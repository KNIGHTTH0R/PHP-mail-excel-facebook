<?php
function createTables($db)
{
    $createTableUser = "CREATE TABLE IF NOT EXISTS `users` ( " .
        "`id` INT(11) UNSIGNED NOT NULL," .
        "`email` VARCHAR(255) NOT NULL DEFAULT ''," .
        "`user_name` VARCHAR(255) NOT NULL DEFAULT ''," .
        "PRIMARY KEY  (`id`))";

    $createTableExcel = "CREATE TABLE IF NOT EXISTS `excel` ( " .
        "`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT," .
        "`user_id` INT(11) UNSIGNED NOT NULL," .
        "`val` VARCHAR(255) NOT NULL DEFAULT ''," .
        "FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)," .
        "PRIMARY KEY  (`id`))";

    $db->query($createTableUser);
    $db->query($createTableExcel);
}

function insertUser($db, $id, $email, $userName)
{
    $sql = "INSERT INTO `users` (`id`, `email`, `user_name`) VALUES ('$id', '$email', '$userName')";
    return $db->query($sql);
}

function insertExcel($db, $userId, $val)
{
    $sql = "INSERT INTO `excel` (`user_id`, `val`) VALUES ('$userId', '$val')";
    return $db->query($sql);
}

function getAllUser($db)
{
    $for_return = array();
    $sql = 'SELECT * FROM `users`';
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $res = array();
            $res['id'] = $row['id'];
            $res['user_name'] = $row['user_name'];
            $res['email'] = $row['email'];
            array_push($for_return, $res);
        }
    }
    return $for_return;
}

function getUserById($db, $id)
{
    $for_return = array();
    $sql = "SELECT * FROM `users` WHERE `id`='$id'";
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $res = array();
            $res['id'] = $row['id'];
            $res['user_name'] = $row['user_name'];
            $res['email'] = $row['email'];
            array_push($for_return, $res);
        }
    }
    return $for_return;
}

function getAllExcel($db)
{
    $for_return = array();
    $sql = 'SELECT * FROM `excel`';
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $res = array();
            $res['id'] = $row['id'];
            $res['user_id'] = $row['user_id'];
            $res['val'] = $row['val'];
            array_push($for_return, $res);
        }
    }
    return $for_return;
}

function getAllExcelByUserId($db, $userId)
{
    $for_return = array();
    $sql = "SELECT * FROM `excel` WHERE `user_id`=$userId";
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $res = array();
            $res['id'] = $row['id'];
            $res['user_id'] = $row['user_id'];
            $res['val'] = $row['val'];
            array_push($for_return, $res);
        }
    }
    return $for_return;
}

function connect()
{
    $url = getenv('CLEARDB_DATABASE_URL');
    if ($url) {
        $url = parse_url($url);
        $host = $url["host"];
        $user = $url["user"];
        $pwd = $url["pass"];
        $db_name = substr($url["path"], 1);
    } else {
        $host = 'localhost';
        $user = 'root';
        $pwd = '';
        $db_name = 'uploadexcel';
    }
    $db = new mysqli($host, $user, $pwd, $db_name);
    if ($db->connect_errno > 0) {
        return null;
    }
    createTables($db);
    return $db;
}