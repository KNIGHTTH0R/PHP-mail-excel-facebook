<?php
echo '<h3>Hi, $profile["name"]</h3>';
echo '<br/>';
echo '<a href="logout.php">Logout</a><br/>';

$user = array(
    'id' => $profile['id'],
    'email' => $profile['email'],
    'user_name' => $profile['name']
);

include 'PHPExcel/PHPExcel/IOFactory.php';
require 'PHPMailer/PHPMailerAutoload.php';
require 'db.php';

$file_name = 'excel';
if (isset($_FILES[$file_name]) && isset($_POST['email'])) {

    function setMail($toMail, $attach)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = 'raksa.e@gmail.com';
        $mail->Password = '2012thenameilove';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('raksa.e@gmail.com');
        $mail->addAddress($toMail);

        $mail->addAttachment($attach);

        $mail->Subject = 'Uploaded Excel file';
        $mail->Body = 'Detail Uploader <b>with attach excel file!</b>';
        $mail->AltBody = 'name:Eng Raksa\n id:123';

        return $mail->send();
    }

    function isValidExcel($inputFile)
    {
        $types = array('Excel2007', 'Excel5');
        foreach ($types as $type) {
            $reader = PHPExcel_IOFactory::createReader($type);
            if ($reader->canRead($inputFile)) {
                return true;
            }
        }
        return false;
    }

    function isUploaded($file, $fileName)
    {
        $result = array();
        $info = pathinfo($fileName);
        $ext = $info['extension'];
        $name = $info['filename'];
        $i = 0;
        $suf = '';
        $dist = 'files/';
        do {
            $newFileName = $name . $suf . '.' . $ext;
            $suf = '_' . $i++;
        } while (file_exists($dist . $newFileName));
        if (!file_exists($dist)) {
            mkdir($dist, 0755, true);
        }
        $result['success'] = move_uploaded_file($file, $dist . $newFileName);
        $result['fileName'] = $newFileName;
        $result['dist_fileName'] = $newFileName;
        return $result;
    }

    function readDataFromExcel($inputFile)
    {
        $result = array();
        try {
            $output = '';
            $inputFileType = PHPExcel_IOFactory::identify($inputFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);
            $allSheet = $objPHPExcel->getAllSheets();
            foreach ($allSheet as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($columnIndex = 0; $columnIndex <= $highestColumnIndex; $columnIndex++) {
                        $cell = $worksheet->getCellByColumnAndRow($columnIndex, $row);
                        $val = $cell->getValue();
                        if ($val) $output .= $val . " ;";
                    }
                    $output .= ' | ';
                }
            }
            $result['success'] = true;
            $result['data'] = $output;
        } catch (Exception $e) {
            $result['success'] = false;
        }
        return $result;
    }

    $toMail = $_POST['email'];
    if ($_FILES[$file_name]['name']) {
        if (!$_FILES[$file_name]['error']) {
            $inputFile = $_FILES[$file_name]['name'];
            $inputTmpFile = $_FILES[$file_name]['tmp_name'];
            $extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
            if ($extension == 'XLSX' || $extension == 'XLS') {
                if (isValidExcel($inputTmpFile)) {
                    $data = readDataFromExcel($inputTmpFile);
                    if ($data['success']) {
                        $uploaded = isUploaded($_FILES[$file_name]['tmp_name'], $inputFile);
                        if ($uploaded['success']) {
                            echo 'Excel file have been uploaded<br/>';

                            $db = connect();
                            if ($db) {
                                $userDb = getUserById($db, $user['id']);
                                if (count($userDb) == 0)
                                    insertUser($db, $user['id'], $user['email'], $user['user_name']);
                                insertExcel($db, $user['id'], $data['data']);
                            } else {
                                echo 'Can\'t connect to database';
                            }
                            if (setMail($toMail, $uploaded['dist_fileName'])) {
                                echo 'Excel file have been sent to ' . $toMail . '<br/>';
                            } else {
                                echo 'Excel can\'t be sent to ' . $toMail . '<br/>';
                            }
                        }
                    }
                } else {
                    echo 'Invalid file format<br/>';
                }
            } else {
                echo 'File is not Excel type<br/>';
            }
        }
    }
}

?>


<form action="." method="POST" enctype="multipart/form-data">
    <br/>
    <div>
        <label form="excel">Select excel file:</label>
        <input id="excel" type="file" name="excel" onchange="checkFile(this);" accept=".xls, .xlsx" required>
    </div>
    <br/>
    <div>
        <label form="email">Email to receive:</label>
        <input id="email" type="email" name="email" required>
    </div>
    <br/>
    <input type="submit" value="Upload">
</form>
<script type="text/javascript" language="javascript">
    function checkFile(sender) {
        var validExts = new Array(".xlsx", ".xls");
        var fileExt = sender.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (fileExt && validExts.indexOf(fileExt) < 0) {
            alert("Invalid file selected, valid files are of " + validExts.toString() + " types.");
            sender.value = '';
            return false;
        }
        else return true;
    }
</script>