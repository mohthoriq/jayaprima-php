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
                            <h2>List Pelanggan</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <span class="btn btn-primary" onclick="PanelCustomer.addNewDataPanel()">Tambah Pelanggan Baru</span>
                            <br/><br/>
                            <table id="tabel_pelanggan" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>Kota</th>
                                    <th>Alamat</th>
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
            let PanelCustomer;

            function initPanel(){
                PanelCustomer = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_pelanggan',
                    dataTablesSchema: 'pelanggan',
                    panelName: 'pelanggan',
                    dbSchema: 'pelanggan',
                    actionEditClass: 'btnPelangganEdit',
                    DataTableItem: function (that, row = undefined){
                        this.nama = '';
                        this.nohp = '';
                        this.alamat = '';
                        this.kota = '';
                        this.id = '';


                        if(row !== undefined) {
                            this.nama = that.nullSafe(row[0]);
                            this.nohp = that.nullSafe(row[1]);
                            this.kota = that.nullSafe(row[2]);
                            this.alamat = that.nullSafe(row[3]);
                            this.id = row[5];
                        }
                    },

                    addNewPanelBuilder: {
                        isPanelFormInput: true,
                        contentBuilder: (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            return `
                    <form id="${formId}" class="form-horizontal form-label-left" data-parsley-validate novalidate>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama (Max 30 Karakter)<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nama" type="text" class="form-control" placeholder="Nama" required onchange="changeTitlePanel('${panelTitleId}', '${actionTitle}: ' + this.value)"
                                    value="${dataTableItem.nama}" data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat (Max 30 Karakter)<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea name="alamat" class="form-control" rows="3" placeholder='Alamat' data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter"
                                    required>${dataTableItem.alamat}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kota (Max 30 Karakter)<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="kota" type="text" class="form-control" placeholder="Kota" required
                                    value="${dataTableItem.kota}" data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No HP </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nohp" type="text" class="form-control" placeholder="No HP"
                                    value="${dataTableItem.nohp}" data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter">
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>

                    </form>
                        `;
                        },
                        onDrawCallback: (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            changeTitlePanel(panelTitleId, actionTitle + ': ' + dataTableItem.nama);
                        }
                    },
                });
            }

            $(document).ready( function () {
                initPanel();

                PanelCustomer.init();
            } );
        </script>
        <?php
    },
);