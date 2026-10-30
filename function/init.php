<?php
include_once 'config/var.php';
include_once 'class/WebBlocker.php';

if(WebBlocker::isOnBlocked()) {
    if(isset($_SESSION['user_id'])) session_destroy();

    include_once ROOTDIR . 'template/webblocker.php';

    exit();
}

if (isset($_SESSION['user_id'])) {
    include_once ROOTDIR . 'class/DBAkun.php';
    $_SESSION['akun'] = (new DBAkun(true))->get($_SESSION['user_id']);
    $_SESSION['fotoProfilUrl'] = HOST . 'api.php?akun=' . urlencode(json_encode(array('foto' => $_SESSION['akun']->id)));

    if($_SESSION['akun']->isActive == 0){
        header("Location: ". HOST . "logout.php");
    }
    else if(basename($_SERVER["REQUEST_URI"]) == 'login.php'){
        header("Location: ". HOST . "index.php");
    }
    else if(isset($_SESSION["LAST_REQUEST_URL"])){
        $redirect = $_SESSION["LAST_REQUEST_URL"];
        unset($_SESSION["LAST_REQUEST_URL"]);
        header("Location: ". $redirect);
    }
}
else{
    if(basename($_SERVER["REQUEST_URI"]) != 'login.php'){
        $_SESSION["LAST_REQUEST_URL"] = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        header("Location: ". HOST . "login.php");
    }
}
