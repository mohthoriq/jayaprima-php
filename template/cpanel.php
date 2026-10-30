<?php

include_once ROOTDIR . 'template/common/hhead.php';
include_once ROOTDIR . 'template/common/hLeftNav.php';
include_once ROOTDIR . 'template/common/hTopNav.php';
include_once ROOTDIR . 'template/common/hfooter.php';
include_once ROOTDIR . 'template/common/hjs.php';
include_once ROOTDIR . 'template/common/hModal.php';

function cpanel(
    ?callable $headExt = null,
    ?callable $bodyExt = null,
    ?callable $jsExt = null
){
    $showSideMenu = $_SESSION['SHOW_LEFT_MENU'] ?? true;
    ?>

    <html lang="id, in">
    <?php hhead(function () use ($headExt){
        if($headExt != null)($headExt)();
        ?>

        <style>
            .panel_color1{
                background-color: #003D53;
            }
            .panel_color2{
                background-color: #047D6F;
            }
            .panel_color3{
                background-color: #264653;
            }
            .panel_color4{
                background-color: #004a42;
            }

            .panel_color1,
            .panel_color2,
            .panel_color3,
            .panel_color4{
                color: white;
            }

            .odd, .odd + .child,
            .panel_color1 .odd + .child:hover,
            .panel_color2 .odd + .child:hover,
            .panel_color3 .odd + .child:hover,
            .panel_color4 .odd + .child:hover{
                background-color: #F7F7F7 !important;
            }

            .even, .even + .child,
            .panel_color1 .even + .child:hover,
            .panel_color2 .even + .child:hover,
            .panel_color3 .even + .child:hover,
            .panel_color4 .even + .child:hover{
                background-color: #EEEEEE !important;
            }

            .panel_color1 th,
            .panel_color2 th,
            .panel_color3 th,
            .panel_color4 th{
                color: white;
            }

            .min_box, .fbtn{
                padding: 6px 12px;
                font-size: 14px;
                font-weight: 400;
                border: 1px solid transparent;
                border-radius: 4px;
            }

            .filter_btn{
                padding: unset;
            }
            .filter_btn button{
                margin: 0 0 0 0;
            }
            .filter_date{
                padding: unset !important;
                margin-bottom: 10px
            }
            .filter_layout p:first-child{
                width: 12ch;
            }
            .filter_layout p:nth-child(3){
                width: 2ch;
            }
            .filter_layout p{
                padding: unset;
                display: inline-flex;
            }
            .filter_layout div{
                margin: unset; padding: unset; width: 21ch;
            }

            @media (max-width: 602px) {
                .filter_btn{
                    margin-top: 10px;
                    margin-bottom: 30px;
                }
            }
            @media (max-width: 480px) {
                .filter_layout div:nth-child(2), .filter_layout div:nth-child(4){
                    width: 100%;
                }
            }

            .txt_green, .txt_red, .txt_yellow, .txt_gray { font-weight: bold; display: inline-block; }
            .txt_green { color: #26B99A; }
            .txt_red { color: #d9534f; }
            .txt_yellow { color: #d58512; }
            .txt_gray{ color: #4B5F71; }
            .d-inline { display: inline-block; }
            .va-top { vertical-align: top; }
            .required{ color: red; }
        </style>

        <?php
    }); ?>

    <body class="<?php echo ($showSideMenu)? 'nav-md': 'nav-sm' ?>">
    <div class="container body">
        <div class="main_container">
            <?php
            hLeftNav();
            hTopNav();
            ?>

            <div class="right_col" role="main">
                <?php if($bodyExt != null) ($bodyExt)(); ?>
            </div>

            <?php hfooter(); ?>
        </div>
    </div>

    <?php hjs($jsExt); ?>
    <script src="<?php echo HOST?>asset/js/min/hjsPanel.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function() {
            $('.dataTables_info').parent().removeClass('col-sm-5').addClass('col-sm-12');
            $('.dataTables_paginate').parent().removeClass('col-sm-7').addClass('col-sm-12').css('padding-top', '8px');

            let $menuBtn = $('#menu_toggle');
            let isMenuShow = <?php echo ($showSideMenu)? 'true' : 'false' ?>;

            $menuBtn.click(()=>{
                isMenuShow = !isMenuShow;

                $.ajax({
                    url: encodeURI(`<?php echo HOST?>api.php?user_info=${JSON.stringify({action: 'setLeftMenu', value: isMenuShow})}`),
                    dataType: 'json',
                    method: 'GET',
                    success: function (data) {},
                });
            });
        });
    </script>

    </body>
    </html>

    <?php
}