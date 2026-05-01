<?php
session_start();
session_unset();
session_destroy();

// ✅ ALWAYS redirect to correct project path
header("Location: http://localhost/mini_sweeters_pro/auth/login.php");
exit;
