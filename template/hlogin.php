<?php
include_once ROOTDIR . 'template/common/hhead.php';

function hlogin(string $info) {
    ?>

    <!DOCTYPE html>
    <html lang="id, in">

    <?php hhead(function (){
        ?>

        <!-- Animate.css -->
        <link href="<?php echo HOST ?>asset/vendors/animate.css/animate.min.css" rel="stylesheet">

        <?php
    }); ?>

    <body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form method="POST">
                        <h1>Login</h1>
                        <p style="color: red"><?php echo $info ?></p>
                        <div>
                            <input name="username" type="text" class="form-control" placeholder="Username" required="" />
                        </div>
                        <div>
                            <input name="password" type="password" class="form-control" placeholder="Password" required="" />
                        </div>
                        <div>
                            <button name="submit" class="btn btn-default submit" href="login.php">Masuk</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />

                            <div>
                                <h1><img src="<?php echo $GLOBALS['logoUrl'] ?>" width="auto" height="35px" alt="logo"> <?php echo $GLOBALS['dbUserInfo']->nama?></h1>
                                <p>©<?php echo $_SESSION['DBYEAR']?> All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    </body>
    </html>

    <?php
}