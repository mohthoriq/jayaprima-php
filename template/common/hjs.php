<?php

function hjs(?callable $hjsExt = null){
    ?>

    <!-- jQuery -->
    <script src="<?php echo HOST ?>asset/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo HOST ?>asset/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo HOST ?>asset/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo HOST ?>asset/vendors/nprogress/nprogress.js"></script>

    <!-- PNotify -->
    <script src="<?php echo HOST ?>asset/vendors/pnotify/dist/pnotify.js"></script>
    <script src="<?php echo HOST ?>asset/vendors/pnotify/dist/pnotify.buttons.js"></script>

    <?php if($hjsExt != null) ($hjsExt)(); ?>

    <!-- Custom Theme Scripts -->
    <script src="<?php echo HOST ?>asset/js/min/custom.min.js"></script>

    <?php
}
