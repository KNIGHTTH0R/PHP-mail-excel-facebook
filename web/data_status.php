<h3>Files Uploaded:</h3>
<ul>
    <?php
    $dist = basename(__DIR__) . '/' . 'files/';
    if ($handle = opendir($dist)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." &&
                strtolower(substr($file, strrpos($file, '.') + 1)) == 'xls' ||
                strtolower(substr($file, strrpos($file, '.') + 1)) == 'xlsx'
            ) {
                $thelist .= '<li>' . $file . '</li>';
            }
        }
        closedir($handle);
    }
    ?>
</ul>
<?php
function makeTable($data, $title)
{
    $head = count($data) > 0 ? array_keys($data[0]) : array();
    ?>
    <h3><?php echo $title ?></h3>
    <table border="1" cellpadding="2">
        <thead>
        <tr>
            <?php foreach ($head as $h) echo '<th>' . $h . '</th>'; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $dt) { ?>
            <tr><?php foreach ($head as $h) echo '<td>' . $dt[$h] . '</td>'; ?></tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
}

?>
<?php
require_once 'db.php';
$db = connect();
if ($db) {
    $users = getAllUser($db);
    makeTable($users, 'All Users');
    $excels = getAllExcel($db);
    makeTable($excels, 'All Excel');
    $db->close();
} else {
    $_SESSION[$SESSION_DB_MESSAGE] = 'Can\'t connect to database.';
}