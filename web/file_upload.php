<?php
echo '<h3>Hi, ' . $profile['name'] . '</h3>';
echo '<a href="logout.php">Logout</a><br/>';

$PROFILE_ID = 'id';
$PROFILE_EMAIL = 'email';
$PROFILE_NAME = 'name';
$SESSION_EXCEL_MESSAGE = 'excel_message';
$SESSION_UPLOAD_MESSAGE = 'upload_message';
$SESSION_DB_MESSAGE = 'db_message';
$SESSION_MAIL_MESSAGE = 'mail_message';
if (!(isset($profile[$PROFILE_ID]) || isset($profile['email']) || isset($profile['name'])))
    die('No profile information');

$user = array();

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
                        $_SESSION[$SESSION_UPLOAD_MESSAGE] = 'Excel file have been uploaded';
                        $db = connect();
                        if ($db) {
                            $userDb = getUserById($db, $profile[$PROFILE_ID]);
                            if (count($userDb) == 0)
                                insertUser($db, $profile[$PROFILE_ID], $profile[$PROFILE_EMAIL], $profile[$PROFILE_NAME]);
                            $_SESSION[$SESSION_DB_MESSAGE] = 'File\'s content ' .
                                (insertExcel($db, $profile[$PROFILE_ID], $data['data']) ? 'have been' : 'can\'t be') .
                                ' saved into database.';
                        } else {
                            $_SESSION[$SESSION_DB_MESSAGE] = 'Can\'t connect to database.';
                        }
                        $_SESSION[$SESSION_MAIL_MESSAGE] = 'Excel file ' . (setMail($toMail, $uploaded['dist_fileName']) ?
                                'have been' : 'can\'t be') . ' sent to ' . $toMail;
                    } else {
                        $_SESSION[$SESSION_UPLOAD_MESSAGE] = 'Excel file can\'t be uploaded.';
                    }
                } else {
                    $_SESSION[$SESSION_EXCEL_MESSAGE] = 'Fail to read file\'s content.';
                }
            } else {
                $_SESSION[$SESSION_EXCEL_MESSAGE] = 'Invalid file format.';
            }
        } else {
            $_SESSION[$SESSION_EXCEL_MESSAGE] = 'File is not Excel type.';
        }
    } else {
        $_SESSION[$SESSION_EXCEL_MESSAGE] = 'File is error.';
    }
    header("Location: ./");
}
?>
<div> <?php if (isset($_SESSION[$SESSION_EXCEL_MESSAGE])) echo $_SESSION[$SESSION_EXCEL_MESSAGE]; ?> </div>
<div> <?php if (isset($_SESSION[$SESSION_UPLOAD_MESSAGE])) echo $_SESSION[$SESSION_UPLOAD_MESSAGE]; ?> </div>
<div> <?php if (isset($_SESSION[$SESSION_DB_MESSAGE])) echo $_SESSION[$SESSION_DB_MESSAGE]; ?> </div>
<div> <?php if (isset($_SESSION[$SESSION_MAIL_MESSAGE])) echo $_SESSION[$SESSION_MAIL_MESSAGE]; ?> </div>