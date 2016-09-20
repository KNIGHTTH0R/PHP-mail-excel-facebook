<?php
session_start();
session_unset();
$_SESSION['facebook_access_token'] = NULL;
header("Location: ./");