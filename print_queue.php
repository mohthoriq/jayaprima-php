<?php
include_once 'function/init.php';
include_once ROOTDIR . 'template/cpanel.php';

cpanel(
    function (){
        ?>

        <link href="<?php echo HOST?>asset/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo HOST?>asset/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">


        <!-- iCheck -->
        <link href="<?php echo HOST?>asset/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
        <!-- bootstrap-wysiwyg -->
        <link href="<?php echo HOST?>asset/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
        <!-- Select2 -->
        <link href="<?php echo HOST?>asset/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
        <!-- Switchery -->
        <link href="<?php echo HOST?>asset/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
        <!-- starrr -->
        <link href="<?php echo HOST?>asset/vendors/starrr/dist/starrr.css" rel="stylesheet">
        <!-- bootstrap-daterangepicker -->
        <link href="<?php echo HOST?>asset/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <?php
    },
    function (){
        ?>

        <div id="panelContainer" class="">
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color3">
                        <div class="x_title">
                            <h2>List Antrian Printer</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/><br/>
                            <table id="tabel_antrian_print" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Model</th>
                                    <th>No Faktur</th>
                                    <th>Pelanggan</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    },
    function (){
        ?>
        <script src="<?php echo HOST?>asset/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo HOST?>asset/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo HOST?>asset/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>


        <!-- bootstrap-progressbar -->
        <script src="<?php echo HOST?>asset/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo HOST?>asset/vendors/iCheck/icheck.min.js"></script>
        <!-- bootstrap-daterangepicker -->
        <script src="<?php echo HOST?>asset/vendors/moment/min/moment.min.js"></script>
        <script src="<?php echo HOST?>asset/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap-wysiwyg -->
        <script src="<?php echo HOST?>asset/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
        <script src="<?php echo HOST?>asset/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
        <script src="<?php echo HOST?>asset/vendors/google-code-prettify/src/prettify.js"></script>
        <!-- jQuery Tags Input -->
        <script src="<?php echo HOST?>asset/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
        <!-- Switchery -->
        <script src="<?php echo HOST?>asset/vendors/switchery/dist/switchery.min.js"></script>
        <!-- Select2 -->
        <script src="<?php echo HOST?>asset/vendors/select2/dist/js/select2.full.min.js"></script>
        <!-- Parsley -->
        <script src="<?php echo HOST?>asset/vendors/parsleyjs/dist/parsley.min.js"></script>
        <!-- Autosize -->
        <script src="<?php echo HOST?>asset/vendors/autosize/dist/autosize.min.js"></script>
        <!-- jQuery autocomplete -->
        <script src="<?php echo HOST?>asset/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
        <!-- starrr -->
        <script src="<?php echo HOST?>asset/vendors/starrr/dist/starrr.js"></script>


        <script type="application/javascript">
            let PanelAntrianPrint;

            async function deleteAntrian(id){
                return await (async ()=>{
                    let data = new FormData();
                    data.append('id', id);

                    return new Promise((resolve) => {
                        $.ajax({
                            url: encodeURI(`${PanelAntrianPrint.host}api.php?antrian_printer=${JSON.stringify({
                                action: 'delete',
                            })}`),
                            cache: false,
                            contentType: false,
                            processData: false,
                            method: 'POST',
                            data: data,
                            dataType: 'json',
                            success: (resp) => {
                                if (resp.result) {
                                    $(PanelAntrianPrint.$tableId).DataTable().ajax.reload();

                                    nAlert(`Sukses menghapus antrian`, ALERT_SUCCESS);

                                    resolve(true);
                                }
                                else {
                                    nAlert((resp.msg !== undefined)? resp.msg : `Gagal menghapus antrian`, ALERT_FAIL);
                                    resolve(false);
                                }
                            }
                        });
                    });
                })();
            }

            function initPanel(){
                PanelAntrianPrint = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_antrian_print',
                    dataTablesSchema: 'v_antrian_printer',
                    panelName: 'antrian printer',
                    dbSchema: 'antrian_printer',
                    actionEditClass: '-----',
                    actionCustomClass: [{
                        className: 'btnVAntrianPrinterHapus',
                        callback: (that, target)=>{
                            if(confirm('Apakah anda ingin membatalkan antrian ini?')) {
                                deleteAntrian(target.getAttribute('value'));
                            }
                        }
                    }],
                    DataTableItem: function (that, row = undefined){
                        this.txt_template = '';
                        this.kode_faktur = '';
                        this.nama_pelanggan = '';
                        this.id = '';
                        this.status = '';

                        if(row !== undefined) {
                            this.txt_template = that.nullSafe(row[2]);
                            this.kode_faktur = that.nullSafe(row[3]);
                            this.nama_pelanggan = that.nullSafe(row[4]);
                            this.id = that.nullSafe(row[6]);
                            this.status = that.nullSafe(row[7]);
                        }
                    },
                    DataTableOpts: {
                        order: []
                    },
                });
            }

            $(document).ready( function () {
                initPanel();

                PanelAntrianPrint.init();
            } );
        </script>
        <?php
    },
);