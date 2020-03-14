<?php
session_start();
unset($_SESSION['email_id']);
session_destroy();
echo "<script type='text/javascript'>window.location.href = 'http://localhost:8080/dbms/teacher_login.html';</script>";

// header('location : teacher_login.html ')
?>