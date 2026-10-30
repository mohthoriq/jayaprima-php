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
                            <h2>List Barang Masuk</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <span class="btn btn-primary" onclick="PanelBMasuk.addNewDataPanel()">Tambah Barang Masuk Baru</span>
                            <br/><br/><br/>

                            <table id="tabel_brg_masuk" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Qty Satuan Terkecil</th>
                                    <th>Modal</th>
                                    <th>Kategori</th>
                                    <th>Diinput Oleh</th>
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
            let PanelBMasuk;

            function initPanel(){
                PanelBMasuk = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_brg_masuk',
                    dataTablesSchema: 'v_brg_masuk',
                    panelName: 'barang masuk',
                    dbSchema: 'brg_masuk',
                    actionEditClass: 'btnVBarangMasukEdit',
                    DataTableItem: function (that, row = undefined){
                        this.nama_produk = '';
                        this.id = '';
                        this.tanggal = '';
                        this.id_produk = '';
                        this.raw_qty = '';
                        this.id_satuan_brg_raw_qty = '';
                        this.nama_satuan_brg_raw_qty = '';

                        if(row !== undefined) {
                            this.nama_produk = that.nullSafe(row[1]);
                            this.id = that.nullSafe(row[8]);
                            this.tanggal = that.nullSafe(row[9]);
                            this.id_produk = that.nullSafe(row[10]);
                            this.raw_qty = that.nullSafe(row[11]);
                            this.id_satuan_brg_raw_qty = that.nullSafe(row[12]);
                            this.nama_satuan_brg_raw_qty = that.nullSafe(row[13]);
                        }
                    },
                    DataTableOpts: {
                        order: [[0, 'desc']],
                    },
                    addNewPanelBuilder: {
                        isPanelFormInput: true,
                        contentBuilder: (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            return `
                    <form id="${formId}" class="form-horizontal form-label-left" data-parsley-validate novalidate>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Produk<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nama_produk" id="autocomplete_produk" type="text" class="form-control" placeholder="Nama Produk" required onchange="setTimeout(()=>{changeTitlePanel('${panelTitleId}', '${actionTitle}: ' + this.value)}, 800)"
                                    value="${dataTableItem.nama_produk}">
                                <input name="id_produk" id="id_produk" type="text" hidden
                                    value="${dataTableItem.id_produk}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Qty<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="format_qty" id="format_qty" type="text" class="form-control" placeholder="Qty" required
                                    value="${(dataTableItem.raw_qty.length > 0)? parseInt(dataTableItem.raw_qty).toLocaleString('id-ID') : ''}">
                                <input name="qty" id="qty" type="text" hidden
                                    value="${dataTableItem.raw_qty}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Satuan Barang<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nama_satuan_brg" id="autocomplete_satuan_brg" type="text" class="form-control" placeholder="Nama Satuan" required
                                    value="${dataTableItem.nama_satuan_brg_raw_qty}">
                                <input name="id_satuan_brg" id="id_satuan_brg" type="text" hidden
                                    value="${dataTableItem.id_satuan_brg_raw_qty}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input name="format_tanggal" type="text" class="form-control" id="format_tanggal" placeholder="Tanggal Masuk" aria-describedby="inputSuccess2Status" required>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                    <input name="tanggal" id="tanggal" type="text" hidden
                                        value="${dataTableItem.tanggal}"/>
                                </div>
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
                            changeTitlePanel(panelTitleId, actionTitle + ': ' + dataTableItem.nama_produk);

                            init_autocomplete(
                                'produk',
                                '<?php echo HOST?>',
                                'produk',
                                formId,
                                'autocomplete_produk',
                                'id_produk'
                            );

                            $('#autocomplete_produk').on('change',()=>{
                                $('#autocomplete_satuan_brg').val('');
                            });

                            initCurrency($(`#${formId} #format_qty`), $(`#${formId} #qty`));

                            init_autocomplete(
                                'satuan barang',
                                '<?php echo HOST?>',
                                'v_produk_bundle',
                                formId,
                                'autocomplete_satuan_brg',
                                'id_satuan_brg',
                                function (){
                                    let val = $('#id_produk').val();
                                    val = (val.length > 0)? parseInt(val): null;

                                    return {
                                        id_produk: val,
                                    };
                                }
                            );

                            initDaterangePickerSingleInput(
                                formId,
                                'format_tanggal',
                                'tanggal',
                                (dataTableItem.tanggal === '')
                                    ? null
                                    : parseInt(dataTableItem.tanggal)
                            );
                        }
                    },

                    filterDateRange: {
                        dbFieldName: 'tanggal',
                    },
                });
            }

            $(document).ready( function () {
                initPanel();

                PanelBMasuk.init();
            } );
        </script>
        <?php
    },
);