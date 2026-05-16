<?php 
session_start();

// 1. Kosongkan seluruh data session dari memori server global
$_SESSION = array();

// 2. Hancurkan cookie session ID yang tersimpan di browser klien secara permanen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Hancurkan penyimpanan session metadata di server
session_destroy();

// 4. Melempar kembali pengguna ke login page dengan parameter status aman
header("location:login.php?pesan=logout");
exit();
?>