<div class="container" style="padding-top: 50px;">
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-sm btn-primary" href="clear_db.php">Clear Database and files.</a>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Files Uploaded:</legend>
                <ul>
                    <?php
                    $dist = getcwd() . '/../' . 'files/';
                    if ($handle = opendir($dist)) {
                        while (false !== ($file = readdir($handle))) {
                            if ($file != "." && $file != ".." &&
                                strtolower(substr($file, strrpos($file, '.') + 1)) == 'xls' ||
                                strtolower(substr($file, strrpos($file, '.') + 1)) == 'xlsx'
                            ) {
                                echo '<li>' . $file . '</li>';
                            }
                        }
                        closedir($handle);
                    }
                    ?>
                </ul>
            </fieldset>
            <?php
            function makeTable($data, $title)
            {
                $head = count($data) > 0 ? array_keys($data[0]) : array();
                ?>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo $title ?></legend>
                    <table class="table table-bordered">
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
                </fieldset>
                <?php
            }

            ?>
            <?php
            require_once 'db.php';
            $db = connect();
            if ($db) {
                $users = getAllUser($db);
                makeTable($users, 'All Users:');
                $excels = getAllExcel($db);
                makeTable($excels, 'All Excel:');
                $db->close();
            } else {
                $_SESSION[$SESSION_DB_MESSAGE] = 'Can\'t connect to database.';
            }
            ?>
        </div>
    </div>
</div>