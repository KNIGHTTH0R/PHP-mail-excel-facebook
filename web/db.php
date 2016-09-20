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

    if (!$db->query($createTableUser)) {
        echo "Table creation failed: (" . $db->errno . ") " . $db->error . '<br/>';
    }
    if (!$db->query($createTableExcel)) {
        echo "Table creation failed: (" . $db->errno . ") " . $db->error . '<br/>';
    }
}

function insertUser($db, $id, $email, $userName)
{
    $sql = "INSERT INTO `users` (`id`, `email`, `user_name`) VALUES ('$id', '$email', '$userName')";
    if ($db->query($sql)) {
        echo 'New user record created successfully' . '<br/>';
    } else {
        echo "Error: " . $sql . "<br>" . $db->error . '<br/>';
    }
}

function insertExcel($db, $userId, $val)
{
    $sql = "INSERT INTO `excel` (`user_id`, `val`) VALUES ('$userId', '$val')";
    if ($db->query($sql)) {
        echo "New excel record created successfully" . '<br/>';
    } else {
        echo "Error: " . $sql . "<br>" . $db->error . '<br/>';
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
    $user = 'ahladang_upload';
//    $user = 'root';
    $pwd = 'testuploadexcel';
//    $pwd = '';
    $db_name = 'ahladang_uploadexcel';
//    $db_name = 'uploadexcel';
    $db = new mysqli('localhost', $user, $pwd, $db_name);
    if ($db->connect_errno > 0) {
        return null;
    }
    createTables($db);
    return $db;
}