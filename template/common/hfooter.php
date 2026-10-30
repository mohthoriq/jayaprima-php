<?php

function hfooter(){
    ?>

    <footer>
        <div class="pull-right">
            <p><?php echo $GLOBALS['dbUserInfo']->nama?> - ©<?php echo $_SESSION['DBYEAR']?> All Rights Reserved.</p>
        </div>
        <div class="clearfix"></div>
    </footer>

    <?php
}
