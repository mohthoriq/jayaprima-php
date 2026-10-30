<?php
    include_once ROOTDIR . 'template/common/hhead.php';
    include_once ROOTDIR . 'template/common/hjs.php';

    if(!WebBlocker::isOnBlocked()){
        header("Location: ". HOST . "index.php");
        return;
    }

    $wb = WebBlocker::readConfig();
?>
<!DOCTYPE html>
<html lang="en">

<?php hhead(); ?>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
            <div class="col-middle">
                <div class="text-center text-center">
                    <h1 class="error-number" id="progress"><?php echo $wb->progress; ?></h1>
                    <h2 id="title"><?php echo $wb->title; ?></h2>
                    <p id="content"><?php echo $wb->msg; ?></p>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<?php hjs(function (){
    ?>

    <script type="application/javascript">
        let $progress = $('#progress');
        let $title = $('#title');
        let $content = $('#content');

        function getStatus(){
            $.ajax({
                url: encodeURI(`<?php echo HOST?>api.php?user_info=${JSON.stringify({
                    action: 'getBlockStatus',
                })}`),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: (resp) => {
                    if(resp['isBlocked']){
                        $progress.html(resp['progress']);
                        $title.html(resp['title']);
                        $content.html(resp['msg']);

                        setTimeout(getStatus, 5000);
                    }
                    else{
                        window.location = '<?php echo HOST;?>login.php';
                    }
                }
            });
        }

        $(document).ready( function () {
            getStatus();
        });
    </script>

    <?php
}); ?>

</body>
</html>
