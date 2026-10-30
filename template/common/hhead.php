<?php

function hhead(?callable $headExt = null){
    ?>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="<?php echo $GLOBALS['logoUrl']?>">
        <title><?php echo $GLOBALS['dbUserInfo']->nama?></title>

        <!-- Bootstrap -->
        <link href="<?php echo HOST ?>asset/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo HOST ?>asset/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="<?php echo HOST ?>asset/vendors/nprogress/nprogress.css" rel="stylesheet">

        <!-- PNotify -->
        <link href="<?php echo HOST?>asset/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
        <link href="<?php echo HOST?>asset/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="<?php echo HOST ?>asset/css/custom.min.css" rel="stylesheet">

        <?php if($headExt != null) ($headExt)(); ?>
    </head>

    <?php
}