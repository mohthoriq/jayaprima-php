<?php

function hModal(string $elId, string $title, callable $body, callable $footer){
    ?>

    <div id="<?php echo $elId?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close modal_close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="<?php echo $elId?>_title"><?php echo $title?></h4>
                </div>
                <div class="modal-body">
                    <?php ($body)();?>
                </div>
                <div class="modal-footer">
                    <?php ($footer)();?>
                </div>
            </div>
        </div>
    </div>

    <?php
}

?>

