<?php
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 bg-info ">
            <form action="." method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="excel">Select excel file:</label>
                    <input id="excel" type="file" name="excel" onchange="checkFile(this);" accept=".xls, .xlsx"
                           required>
                </div>
                <div class="form-group">
                    <label for="email">Email to receive:</label>
                    <input id="email" type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
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
        </div>
    </div>
</div>