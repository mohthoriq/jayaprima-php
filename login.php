<?php
include_once 'function/init.php';
include_once ROOTDIR . 'class/DBAkun.php';
include_once ROOTDIR . 'template/hlogin.php';

$info = '';

if(isset($_POST['submit'])) {
    $dbAkun = new DBAkun(true);

    $akun = $dbAkun->getWhereUsernamePass($_POST['username'], md5($_POST['password']));

    if($akun != null){
        if($akun->isActive) {
//            echo 'login success';
            $_SESSION['user_id'] = $akun->id;

            header("Location: index.php");
        }
        else{
            $info = 'Akun dinonaktifkan';
        }
    }
    else{
        $info = 'Username atau Password tidak valid';
    }
}

hlogin($info);
?>



