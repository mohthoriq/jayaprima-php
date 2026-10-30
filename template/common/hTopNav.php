<?php

function hTopNav(){
    $akun = Akun::Cast($_SESSION['akun']);
    $fotoProfilUrl = $_SESSION['fotoProfilUrl'];
    $dropDownDbList = '';
    if(is_array($GLOBALS['dbList'])){
        $currFile = strtok(basename($_SERVER["REQUEST_URI"]), '?');

        foreach ($GLOBALS['dbList'] as $fileName){
            $dropDownDbList .= "<li><a href=\"$currFile?DBYEAR=$fileName\">$fileName</a></li>";
        }
    }

    ?>

    <div class="top_nav">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $fotoProfilUrl ?>" alt=""><?php echo $akun->nama ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <li><a href="<?php echo HOST?>accounts.php?id=<?php echo $akun->id?>" target="_blank" rel="noopener noreferrer"> Profil</a></li>
                            <li><a href="<?php echo HOST?>logout.php"><i class="fa fa-sign-out pull-right"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Data: <?php echo $_SESSION['DBYEAR'] ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <?php echo $dropDownDbList; $dropDownDbList=null;?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <?php
}
