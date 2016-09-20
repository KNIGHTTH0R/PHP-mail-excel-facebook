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
        "`file` VARCHAR(255) NOT NULL DEFAULT ''," .
        "`user_id` INT(11) UNSIGNED NOT NULL," .
        "`val` VARCHAR(255) NOT NULL DEFAULT ''," .
        "FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)," .
        "PRIMARY KEY  (`id`))";

    if (!$db->query($createTableUser)) error_log($db->error);
    if (!$db->query($createTableExcel)) error_log($db->error);
}

function insertUser($db, $id, $email, $userName)
{
    $sql = "INSERT INTO `users` (`id`, `email`, `user_name`) VALUES ('$id', '$email', '$userName')";
    if ($db->query($sql)) {
        return true;
    } else {
        error_log($db->error);
        return false;
    }
}

function insertExcel($db, $userId, $file, $val)
{
    $sql = "INSERT INTO `excel` (`user_id`, `file`, `val`) VALUES ('$userId', '$file', '$val')";
    if ($db->query($sql)) {
        return true;
    } else {
        error_log($db->error);
        return false;
    }
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
            var_dump($row);
        }
    } else {
        error_log($db->error);
    }
    var_dump($for_return);
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
    } else {
        error_log($db->error);
    }
    return $for_return;
}

function getAllExcel($db)
{
    $for_return = array();
    $sql = 'SELECT `u`.`user_name`, `e`.`id`, `e`.`user_id`, `e`.`file`, `e`.`val` FROM `users` AS u, `excel` AS e WHERE `u`.`id`=`e`.`user_id`';
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $res = array();
            $res['user_name'] = $row['user_name'];
            $res['id'] = $row['id'];
            $res['user_id'] = $row['user_id'];
            $res['file'] = $row['file'];
            $res['val'] = $row['val'];
            array_push($for_return, $res);
        }
    } else {
        error_log($db->error);
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
            $res['file'] = $row['file'];
            $res['val'] = $row['val'];
            array_push($for_return, $res);
        }
    } else {
        error_log($db->error);
    }
    return $for_return;
}

function clearAll($db)
{
    if (!$db->query("DELETE FROM users")) error_log($db->error);
    if (!$db->query("DELETE FROM excel")) error_log($db->error);
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
        error_log($db->error);
        return null;
    }
    createTables($db);
    return $db;
}