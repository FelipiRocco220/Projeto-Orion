<?php
include_once('session.inc.php');
$_SESSION['user_id'] = null;
header("Location: ../../index.php");