<?php
?>
<form action="." method="POST" enctype="multipart/form-data">
    <br/>
    <div>
        <label for="excel">Select excel file:</label>
        <input id="excel" type="file" name="excel" onchange="checkFile(this);" accept=".xls, .xlsx" required>
    </div>
    <br/>
    <div>
        <label for="email">Email to receive:</label>
        <input id="email" type="email" name="email" required>
    </div>
    <br/>
    <input type="submit" value="Upload">
</form>
<script type="text/javascript" language="javascript">
    function checkFile(sender) {
        var validExts = [".xlsx", ".xls"];
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
