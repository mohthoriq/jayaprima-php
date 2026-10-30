<?php
include_once 'function/init.php';
include_once ROOTDIR . 'template/cpanel.php';

$akun = Akun::Cast($_SESSION['akun']);
$isSuperAdmin = (int)$akun->level_akses === 0;

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

        <style>
            .table-bundle th{
                text-align: center;
            }

            .table-bundle th:nth-child(2),
            .table-bundle td:nth-child(2) *{
                width: 30ch;
                min-width: 30ch;
                vertical-align: middle;
            }

            .table-bundle th:nth-child(3),
            .table-bundle th:nth-child(4),
            .table-bundle th:nth-child(5),
            .table-bundle th:nth-child(9),
            .table-bundle th:nth-child(10),
            .table-bundle th:nth-child(11),

            .table-bundle td:nth-child(3) *,
            .table-bundle td:nth-child(4) *,
            .table-bundle td:nth-child(5) *,
            .table-bundle td:nth-child(9) *,
            .table-bundle td:nth-child(10) *,
            .table-bundle td:nth-child(11) *{
                width: 12ch;
                min-width: 12ch;
                vertical-align: middle;
            }

            .table-bundle th:nth-child(6),
            .table-bundle th:nth-child(7),
            .table-bundle th:nth-child(8),
            .table-bundle th:nth-child(12),
            .table-bundle th:nth-child(13),

            .table-bundle td:nth-child(6) *,
            .table-bundle td:nth-child(7) *,
            .table-bundle td:nth-child(8) *,
            .table-bundle td:nth-child(12) *,
            .table-bundle td:nth-child(13) *{
                width: 17ch;
                min-width: 17ch;
                vertical-align: middle;
            }

            .table-bundle .fa-close{
                color: red;
            }
        </style>
        <?php
    },
    function () use ($isSuperAdmin) {
        ?>

        <div id="panelContainer" class="">
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color2" style="height: auto;">
                        <div class="x_title">
                            <h2>List Hutang Pelanggan</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: none;">
                            <?php if($isSuperAdmin) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 filter_date">
                                <form id="deadLineHutangPj" data-parsley-validate novalidate xmlns="http://www.w3.org/1999/html" style="padding: unset; margin: 0 0 30px 0;">
                                    <div class="d-inline va-top"><p>Jatuh tempo (Hari):</p></div>
                                    <div class="d-inline">
                                        <input name="deadLineHutangPjDays" type="text" class="form-control d-inline" aria-describedby="inputSuccess2Status" style="width: 7ch !important;"
                                               data-parsley-type="digits" required
                                               value="<?php echo $GLOBALS['dbUserInfo']->deadLineHutangPjDays?>">
                                    </div>
                                    <div class="d-inline va-top">&nbsp;&nbsp;<button class="btn btn-primary">Set</button></div>
                                </form>
                            </div>
                            <?php } ?>
                            <br/><br/>
                            <table id="tabel_hutang_pelanggan" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Nominal</th>
                                    <th>No Faktur</th>
                                    <th>Pelanggan</th>
                                    <th>No HP</th>
                                    <th>Diinput Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color3">
                        <div class="x_title">
                            <h2>List Hutang Pembelian</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <span class="btn btn-primary" onclick="PanelHPembelian.addNewDataPanel()">Tambah Hutang Pembelian Baru</span>
                            <br/><br/>
                            <?php if($isSuperAdmin) { ?>
                            <br/>
                            <div class="col-md-12 col-sm-12 col-xs-12 filter_date">
                                <form id="deadLineHutangPb" data-parsley-validate novalidate xmlns="http://www.w3.org/1999/html" style="padding: unset; margin: 0 0 30px 0;">
                                    <div class="d-inline va-top"><p>Jatuh tempo (Hari):</p></div>
                                    <div class="d-inline">
                                        <input name="deadLineHutangPbDays" type="text" class="form-control d-inline" aria-describedby="inputSuccess2Status" style="width: 7ch !important;"
                                            data-parsley-type="digits" required
                                            value="<?php echo $GLOBALS['dbUserInfo']->deadLineHutangPbDays?>">
                                    </div>
                                    <div class="d-inline va-top">&nbsp;&nbsp;<button class="btn btn-primary">Set</button></div>
                                </form>
                            </div>
                            <br/>
                            <?php } ?>
                            <table id="tabel_hutang_pembelian" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tanggal Beli</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>No Faktur</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Lunas</th>
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
    function () use ($isSuperAdmin) {
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
            class VProdukBundle{
                id = '';
                id_produk = '';
                id_satuan_brg = '';
                nama_satuan_brg = '';
                qty = '';
                constructor(o = undefined) {
                    if(o === undefined) return;

                    this.id = o?.id.toString() ?? '';
                    this.id_produk = o?.id_produk.toString() ?? '';
                    this.id_satuan_brg = o?.id_satuan_brg.toString() ?? '';
                    this.nama_satuan_brg = o?.nama_satuan_brg ?? '';
                    this.qty = o?.qty.toString() ?? '';
                }
            }
            const DataTableItemProduk = function (that, row = undefined){
                this.id = '';
                this.nama = '';
                this.id_kategori_brg = '';
                this.id_satuan_brg = '';
                this.singkatan_satuan_brg = '';
                this.modal = '';
                this.id_akun_pj = '';
                this.nama_akun = '';
                /**
                 * @type [VProdukBundle]
                 * */
                this.produk_bundle = [];
                this.format_modal = '';
                this.format_disc_dari_supplier = '';
                this.disc_dari_supplier = '';
                this.format_satuan_brg = '';
                this.format_kategori_brg = '';
                this.format_produk_bundle = '';

                if(row !== undefined) {
                    this.nama = that.nullSafe(row[0]);
                    this.format_modal = that.nullSafe(row[1]);
                    this.format_satuan_brg = that.nullSafe(row[2]);
                    this.format_kategori_brg = that.nullSafe(row[3]);
                    this.format_produk_bundle = that.nullSafe(row[4]);
                    this.disc_dari_supplier = that.nullSafe(row[5]);
                    this.nama_akun = that.nullSafe(row[6]);
                    this.id = that.nullSafe(row[8]);
                    this.id_kategori_brg = that.nullSafe(row[9]);
                    this.id_satuan_brg = that.nullSafe(row[10]);
                    this.singkatan_satuan_brg = that.nullSafe(row[11]);
                    this.id_akun_pj = that.nullSafe(row[12]);
                    this.modal = that.nullSafe(row[13]);

                    let arrPB = row[14];
                    if(typeof arrPB === 'string') arrPB = JSON.parse(arrPB);
                    if(Array.isArray(arrPB) && arrPB.length > 0){
                        this.produk_bundle = [];
                        arrPB.forEach((pb)=>{
                            this.produk_bundle.push(new VProdukBundle(pb));
                        });
                    }
                    else{
                        this.produk_bundle = [];
                    }

                    this.disc_dari_supplier = that.nullSafe(row[15]);
                }
            };

            /**
             * @param {string|null} fakturId
             * @return {PanelBKeluar.DataTableItem[]}
             * */
            async function getBarangFaktur(fakturId){
                if(fakturId === '') return [];

                return await (async ()=>{
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: encodeURI(`<?php echo HOST?>api.php?datatables=${encodeURI('v_brg_keluar')}&${encodeURI('id_faktur')}=${fakturId}`),
                            dataType: 'json',
                            method: 'GET',
                            success: function (data) {   // success callback function
                                let ret = [];
                                data.data.forEach((d)=>{
                                    ret.push(new PanelBKeluar.DataTableItem(PanelBKeluar, d));
                                })
                                resolve(ret);
                            },
                            error: function (jqXhr, textStatus, errorMessage) { // error callback
                                alert('Gagal mendapatkan data barang keluar');
                                console.log('Error: ' + errorMessage);
                                reject([]);
                            }
                        });
                    });
                })();
            }

            /**
             * @param {string|null} fakturId
             * @return {PanelFakturPJ.DataTableItem[]}
             * */
            async function getVFakturPenjualan(fakturId){
                if(fakturId === '') return [];

                return await (async ()=>{
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: encodeURI(`<?php echo HOST?>api.php?datatables=${encodeURI('v_faktur_penjualan')}&${encodeURI('id')}=${fakturId}`),
                            dataType: 'json',
                            method: 'GET',
                            success: function (data) {   // success callback function
                                let ret = [];
                                data.data.forEach((d)=>{
                                    ret.push(new PanelFakturPJ.DataTableItem(PanelFakturPJ, d));
                                })
                                resolve(ret);
                            },
                            error: function (jqXhr, textStatus, errorMessage) { // error callback
                                alert('Gagal mendapatkan data barang keluar');
                                console.log('Error: ' + errorMessage);
                                reject([]);
                            }
                        });
                    });
                })();
            }

            async function getProduk(id){
                if(id === '') return [];

                return await (async ()=>{
                    return new Promise((resolve) => {
                        $.ajax({
                            url: encodeURI(`<?php echo HOST?>api.php?datatables=${encodeURI('v_produk')}&${encodeURI('id')}=${id}`),
                            dataType: 'json',
                            method: 'GET',
                            success: function (data) {   // success callback function
                                let ret = [];
                                data.data.forEach((d)=>{
                                    ret.push(new DataTableItemProduk(PanelBKeluar, d));
                                })
                                resolve(ret);
                            },
                            error: function (jqXhr, textStatus, errorMessage) { // error callback
                                alert('Gagal mendapatkan data barang keluar');
                                console.log('Error: ' + errorMessage);
                                resolve([]);
                            }
                        });
                    });
                })();
            }

            async function printFaktur(fakturId, modelId){
                hideModal('model_faktur');

                return await (async ()=>{
                    let data = new FormData();
                    data.append('tanggal', `${(new Date()).getTime()}`);
                    data.append('status', '0');
                    data.append('template', modelId);
                    data.append('id_faktur_penjualan', fakturId);

                    return new Promise((resolve) => {
                        $.ajax({
                            url: encodeURI(`${PanelFakturPJ.host}api.php?antrian_printer=${JSON.stringify({
                                action: 'insert',
                            })}`),
                            cache: false,
                            contentType: false,
                            processData: false,
                            method: 'POST',
                            data: data,
                            dataType: 'json',
                            success: (resp) => {
                                if (resp.result) {
                                    nAlert(`Sukses menambahkan antrian printer`, ALERT_SUCCESS);
                                    resolve(true);
                                }
                                else {
                                    nAlert((resp.msg !== undefined)? resp.msg : `Gagal menambahkan antrian printer`, ALERT_FAIL);
                                    resolve(false);
                                }
                            },
                            error: function (request, status, error) {
                                nAlert(`Gagal menambahkan antrian printer`, ALERT_FAIL);
                            }
                        });
                    });
                })();
            }

            function calcMProfit(harga_satuan, modal){
                harga_satuan = parseInt(harga_satuan);
                modal = parseInt(modal);

                let p = parseFloat((((harga_satuan - modal)/modal) * 100).toFixed(2));
                return p.toLocaleString('id-ID');
            }

            function calcActualDisc(disc, isFormated = true){
                if(disc.indexOf('+') === -1) return (isFormated)? '' : null;

                let calc = (d1, d2) => d1+d2-(d1*d2/100);

                let dList = disc.split('+');
                let aDisc = calc(parseFloat(dList[0].replace(',','.')), parseFloat(dList[1].replace(',','.')));
                for(let i=2; i<dList.length; i++){
                    aDisc = calc(aDisc, parseFloat(dList[i].replace(',','.')));
                }

                let p = parseFloat(aDisc.toFixed(8));
                return (isFormated)? p.toLocaleString('id-ID') : p;
            }

            window.Parsley.addValidator("requiredIf", {
                validateString : function(value, requirement) {
                    let $req = $(requirement);
                    if(value === '' && $req.val() === '') return true;

                    return value !== '' && $req.val() !== '';
                },
                messages: {
                    en: `Salah satu input ini tidak boleh kosong!`,
                },
                priority: 33
            })

            function discCheck(value){
                if(value === '') return false;

                let isValid = true;
                let v = value.split('+');
                for(let i=0; i<v.length; i++){
                    if(!(/^[\d]*[,]?[\d]+$/.test(v[i]))){
                        isValid = false;
                        break;
                    }
                }

                return isValid;
            }
            function errDiscFormatMsg() { return 'Format diskon tidak valid<br/><br/>contoh:<ul style="padding-left: 3ch;"><li>1</li><li>1,35</li><li>20+5</li></ul>'; }
            window.Parsley.addValidator("discCheck", {
                validateString : function(value) {
                    return discCheck(value);
                },
                messages: {
                    en: errDiscFormatMsg(),
                },
                priority: 33
            })

            let PanelBKeluar;
            let PanelHPelanggan;
            let PanelFakturPJ;
            let PanelHPembelian;

            function toggleStatusHutang(that, target){
                let isHutang = target.status_lunas === '0';

                let info = (isHutang)? 'lunas' : 'hutang';

                if(confirm(`Apakah anda ingin set ${info} pada faktur ${target.no_faktur}?`)) {
                    //tambahkan id update target
                    let data = new FormData();
                    data.append('id', target.id);
                    data.append('status_lunas', (isHutang)? '1' : '0');
                    data.append('tanggal_lunas', (isHutang)? (new Date()).getTime() : '0');

                    that.updateAjax(data);
                }
            }

            function initPanel(){
                PanelBKeluar = new Panel({
                    DataTableItem: function (that, row = undefined){
                        this.nama_produk = '';

                        this.id = '';
                        this.id_faktur_penjualan = '';
                        this.id_produk = '';
                        this.raw_qty = '';
                        this.id_satuan_brg_raw_qty = '';
                        this.modal = '';
                        this.harga_satuan = '';
                        this.persen_disc = '';
                        this.bonus_qty = '';
                        this.id_satuan_brg_bonus_qty = '';
                        this.harga_total = '';
                        this.disc_dari_supplier = '';
                        this.untung_bersih = '';

                        this.nama_satuan_brg_raw_qty = '';
                        this.nama_satuan_brg_bonus_qty = '';


                        if(row !== undefined) {
                            this.nama_produk = that.nullSafe(row[1]);

                            this.id = that.nullSafe(row[14]);
                            this.id_faktur_penjualan = that.nullSafe(row[15]);
                            this.id_produk = that.nullSafe(row[16]);
                            this.raw_qty = that.nullSafe(row[17]);
                            this.id_satuan_brg_raw_qty = that.nullSafe(row[18]);
                            this.modal = that.nullSafe(row[19]);
                            this.harga_satuan = that.nullSafe(row[20]);
                            this.persen_disc = that.nullSafe(row[21]);
                            this.bonus_qty = that.nullSafe(row[22]);
                            this.id_satuan_brg_bonus_qty = that.nullSafe(row[23]);
                            this.harga_total = that.nullSafe(row[24]);
                            this.disc_dari_supplier = that.nullSafe(row[25]);
                            this.untung_bersih = that.nullSafe(row[26]);

                            this.nama_satuan_brg_raw_qty = that.nullSafe(row[27]);
                            this.nama_satuan_brg_bonus_qty = that.nullSafe(row[29]);
                        }
                    },
                });

                PanelHPelanggan = new Panel({
                    $tableId: '#tabel_hutang_pelanggan',
                    DataTableItem: function (that, row = undefined){
                        this.kode = '';
                        this.nama_pelanggan = '';
                        this.nohp_pelanggan = '';
                        this.id = '';

                        if(row !== undefined) {
                            this.kode = that.nullSafe(row[3]);
                            this.nama_pelanggan = that.nullSafe(row[4]);
                            this.nohp_pelanggan = that.nullSafe(row[5]);
                            this.id = that.nullSafe(row[9]);
                        }
                    },
                });

                PanelFakturPJ = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_hutang_pelanggan',
                    dataTablesSchema: 'v_hutang_pelanggan',
                    panelName: 'faktur penjualan',
                    dbSchema: 'faktur_penjualan',
                    actionEditClass: '-----',
                    actionCustomClass: [
                        {
                            className: 'btnVFakturPenjualanEdit',
                            callback: async (that, target)=>{
                                let idFaktur = target.getAttribute('value');
                                let data = (await getVFakturPenjualan(idFaktur))[0];
                                PanelFakturPJ.addEditDataPanel(idFaktur, data);
                            }
                        },
                        {
                            className: 'btnPrintFaktur',
                            callback: (that, target)=>{
                                let idFaktur = target.getAttribute('value');

                                $('#model_faktur').remove();

                                let html = `
                                    <div id="model_faktur" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel2">Model Faktur</h4>
                                                </div>
                                                <div class="modal-body" style="display: flex; flex-direction: row; align-items: stretch;">
                                                    <button type="button" class="btn btn-primary" style="flex: 1; display: inline-block;" onclick="printFaktur('${idFaktur}', '1')">Dengan<br/>Kop Faktur</button>

                                                    <button type="button" class="btn btn-success" style="flex: 1; display: inline-block;" onclick="printFaktur('${idFaktur}', '0')">Tanpa<br/>Kop Faktur</button>
                                                </div>
                                                <div class="modal-footer"></div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#panelContainer').append(html);

                                showModal('model_faktur');
                            }
                        },
                        {
                            className: 'btnViewBarangKeluar',
                            callback: (that, target)=>{
                                let idFaktur = target.getAttribute('value');
                                let f = PanelHPelanggan.dataTableGetId(idFaktur);

                                PanelFakturPJ.viewListBrgKeluar(
                                    idFaktur,
                                    f.kode,
                                    f.nama_pelanggan
                                );
                            }
                        }
                    ],
                    DataTableItem: function (that, row = undefined){
                        this.format_tanggal = '';
                        this.nama_pelanggan = '';
                        this.kode = '';
                        this.format_subtotal = '';
                        this.format_persen_ppn = '';
                        this.format_total = '';
                        this.format_bayar = '';
                        this.format_sisa = '';

                        this.id = '';
                        this.tanggal = '';
                        this.id_pelanggan = '';
                        this.subtotal = '';
                        this.persen_ppn = '';
                        this.total = '';
                        this.bayar = '';
                        this.sisa = '';

                        /**
                         * @type [PanelBKeluar.DataTableItem]
                         * */
                        this.bKeluar_list = [];


                        if(row !== undefined) {
                            this.format_tanggal = that.nullSafe(row[0]);
                            this.nama_pelanggan = that.nullSafe(row[1]);
                            this.kode = that.nullSafe(row[2]);
                            this.format_subtotal = that.nullSafe(row[3]);
                            this.format_persen_ppn = that.nullSafe(row[4]);
                            this.format_total = that.nullSafe(row[5]);
                            this.format_bayar = that.nullSafe(row[6]);
                            this.format_sisa = that.nullSafe(row[7]);

                            this.id = that.nullSafe(row[10]);
                            this.tanggal = that.nullSafe(row[11]);
                            this.id_pelanggan = that.nullSafe(row[12]);
                            this.subtotal = that.nullSafe(row[13]);
                            this.persen_ppn = that.nullSafe(row[14]);
                            this.total = that.nullSafe(row[15]);
                            this.bayar = that.nullSafe(row[16]);
                            this.sisa = that.nullSafe(row[17]);
                        }
                    },
                    DataTableOpts: {
                        order: [],
                        fixedColumns: {
                            leftColumns: 2
                        }
                    },

                    sumSubTotalFakturPj: (formId)=>{},
                    bundleCounter: 0,
                    addBundlePanel: function (formId, barangKeluar = new PanelBKeluar.DataTableItem(PanelBKeluar), onDrawCallback){
                        this.bundleCounter++;
                        let bundleElId = `bundle_${this.bundleCounter}`;

                        let html = `
                            <tr id="${bundleElId}">
                                <td>
                                    <input name="${bundleElId}_id" id="${bundleElId}_id" type="text" hidden value="${barangKeluar.id}"/>
                                    <ul class="nav navbar-right panel_toolbox" style="min-width: unset;">
                                        <li><a class="close-link" onclick="PanelFakturPJ.removeBundle('${formId}', '${bundleElId}')"><i class="fa fa-close"></i></a></li>
                                    </ul>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_nama_produk" id="autocomplete_${bundleElId}_nama_produk" type="text" class="form-control" placeholder="Nama Produk" required
                                        value="${barangKeluar.nama_produk}">
                                    <input name="${bundleElId}_id_produk" id="${bundleElId}_id_produk" type="text" hidden
                                        value="${barangKeluar.id_produk}"/>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_disc_dari_supplier" id="format_${bundleElId}_disc_dari_supplier" type="text" class="form-control" placeholder="Diskon"
                                        data-parsley-disc-check="" data-parsley-trigger="keyup"
                                        value="${barangKeluar.disc_dari_supplier}">
                                    <input name="${bundleElId}_disc_dari_supplier" id="${bundleElId}_disc_dari_supplier" type="text" hidden
                                        value="${barangKeluar.disc_dari_supplier}"/>
                                    <div id="format_${bundleElId}_container_actual_disc_supplier" style="font-size: 12px; width: 100%; margin-top: 10px;">Setara dengan<br/><span id="format_${bundleElId}_actual_disc_supplier"></span> %</div>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_qty" id="format_${bundleElId}_qty" type="text" class="form-control" placeholder="Qty" required
                                        value="${(barangKeluar.raw_qty === '')? '' : parseInt(barangKeluar.raw_qty).toLocaleString('id-ID')}">
                                    <input name="${bundleElId}_qty" id="${bundleElId}_qty" type="text" hidden
                                        value="${barangKeluar.raw_qty}"/>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_nama_satuan_brg_qty" id="autocomplete_${bundleElId}_nama_satuan_brg_qty" type="text" class="form-control" placeholder="Satuan" required
                                        value="${barangKeluar.nama_satuan_brg_raw_qty}">
                                    <input name="${bundleElId}_id_satuan_brg_qty" id="${bundleElId}_id_satuan_brg_qty" type="text" hidden
                                        value="${barangKeluar.id_satuan_brg_raw_qty}"/>
                                </td>
                                <td>
                                    <input id="format_${bundleElId}_satuan_terkecil" type="text" class="form-control" disabled>
                                    <input id="${bundleElId}_satuan_terkecil" type="text" class="form-control hidden" disabled>
                                </td>
                                <td>
                                    <input id="format_${bundleElId}_modal" value="${(barangKeluar.modal === '')? '' : parseInt(barangKeluar.modal).toLocaleString('id-ID')}" type="text" class="form-control" required disabled>
                                    <input id="${bundleElId}_modal" type="text" class="form-control hidden" disabled>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_harga_satuan" id="format_${bundleElId}_harga_satuan" type="text" class="form-control" placeholder="Harga" required
                                        value="${(barangKeluar.harga_satuan === '')? '' : parseInt(barangKeluar.harga_satuan).toLocaleString('id-ID')}">
                                    <input name="${bundleElId}_harga_satuan" id="${bundleElId}_harga_satuan" type="text" class="form-control hidden" placeholder="Harga"
                                        value="${barangKeluar.harga_satuan}">
                                    <div style="font-size: 12px; width: 100%; margin-top: 10px;">Setara dengan</div>
                                    <div style="font-size: 12px; width: 100%; margin-bottom: 10px;">modal + <input name="format_${bundleElId}_mprofit" id="format_${bundleElId}_mprofit" type="text" class="form-control" placeholder="Persen" required
                                        data-parsley-pattern="^[\\d]*[,]?[\\d]*$" data-parsley-pattern-message="format tidak valid"
                                        style="display: inline-block; min-width: 9ch; max-width: 9ch; font-size: 12px; padding-left: 3px; padding-right: 3px;"
                                        value="${(barangKeluar.harga_satuan === '' && barangKeluar.modal === '')? '' : calcMProfit(barangKeluar.harga_satuan, barangKeluar.modal)}"> %</div>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_persen_disc" id="format_${bundleElId}_persen_disc" type="text" class="form-control" placeholder="Diskon" required
                                        data-parsley-disc-check="" data-parsley-trigger="keyup" data-parsley-maxlength="9" data-parsley-maxlength-message="Melebihi batas ukuran kolom Disc pada Faktur"
                                        value="${(barangKeluar.persen_disc === '')? '0' : barangKeluar.persen_disc}">
                                    <input name="${bundleElId}_persen_disc" id="${bundleElId}_persen_disc" type="text" hidden
                                        value="${(barangKeluar.persen_disc === '')? '0' : barangKeluar.persen_disc}"/>
                                    <div id="format_${bundleElId}_container_actual_disc" style="font-size: 12px; width: 100%; margin-top: 10px;">Setara dengan<br/><span id="format_${bundleElId}_actual_disc"></span> %</div>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_bonus_qty" id="format_${bundleElId}_bonus_qty" type="text" class="form-control" placeholder="Qty"
                                        data-parsley-required-if="#${formId} #autocomplete_${bundleElId}_nama_satuan_brg_bonus_qty" data-parsley-validate-if-empty="true"
                                        value="${(barangKeluar.bonus_qty === '')? '' : parseInt(barangKeluar.bonus_qty).toLocaleString('id-ID')}">
                                    <input name="${bundleElId}_bonus_qty" id="${bundleElId}_bonus_qty" type="text" hidden
                                        value="${barangKeluar.bonus_qty}"/>
                                </td>
                                <td>
                                    <input name="format_${bundleElId}_nama_satuan_brg_bonus_qty" id="autocomplete_${bundleElId}_nama_satuan_brg_bonus_qty" type="text" class="form-control" placeholder="Satuan"
                                        data-parsley-required-if="#${formId} #format_${bundleElId}_bonus_qty" data-parsley-validate-if-empty="true"
                                        value="${barangKeluar.nama_satuan_brg_bonus_qty}">
                                    <input name="${bundleElId}_id_satuan_brg_bonus_qty" id="${bundleElId}_id_satuan_brg_bonus_qty" type="text" hidden
                                        value="${barangKeluar.id_satuan_brg_bonus_qty}"/>
                                </td>
                                <td>
                                    <input id="format_${bundleElId}_harga_total" value="${(barangKeluar.harga_total === '')? '' : parseInt(barangKeluar.harga_total).toLocaleString('id-ID')}" type="text" class="form-control" required disabled>
                                </td>
                                <td>
                                    <input id="format_${bundleElId}_untung_bersih" value="${(barangKeluar.untung_bersih === '')? '' : parseInt(barangKeluar.untung_bersih).toLocaleString('id-ID')}" type="text" class="form-control" required disabled>
                                </td>
                            </tr>
                        `;

                        $(`#${formId} #bundelContainer`).append(html);

                        onDrawCallback(bundleElId);

                        return [bundleElId];
                    },
                    removeBundle: function (formId, bundleElId) {
                        let nama = $(`#${formId} #autocomplete_${bundleElId}_nama_produk`).val();
                        nama = (nama === '')? 'ini' : nama;
                        if(!confirm(`Apakah anda ingin menghapus barang ${nama}?`)) return;

                        if($(`#${formId} #${bundleElId}_id`).val() === '') {
                            //insert mode
                            $(`#${formId} #${bundleElId}`).remove();
                        }
                        else{
                            //update mode, set data for delete
                            $(`#${formId} #${bundleElId}`).attr('style', 'display: none;');
                            $(`#${formId} #${bundleElId}_id_produk`).val('');

                            $(`#${formId} #format_${bundleElId}_harga_total`).val('0');
                            this.sumSubTotalFakturPj(formId);
                        }
                    },

                    addDataBundlePanel: function (formId, barangKeluar){
                        let that = PanelFakturPJ;
                        that.addBundlePanel(
                            formId,
                            barangKeluar,
                            (bundleElId)=>{
                                init_autocomplete(
                                    'produk',
                                    '<?php echo HOST?>',
                                    'produk',
                                    formId,
                                    `autocomplete_${bundleElId}_nama_produk`,
                                    `${bundleElId}_id_produk`
                                );

                                let $elProduk = $(`#${formId} #autocomplete_${bundleElId}_nama_produk`);
                                let $elIdProduk = $(`#${formId} #${bundleElId}_id_produk`);
                                let $elNDiscSupplier = $(`#${formId} #format_${bundleElId}_disc_dari_supplier`);
                                let $elVDiscSupplier = $(`#${formId} #${bundleElId}_disc_dari_supplier`);
                                let $elPAcDiscSupplier = $(`#${formId} #format_${bundleElId}_container_actual_disc_supplier`);
                                let $elNAcDiscSupplier = $(`#${formId} #format_${bundleElId}_actual_disc_supplier`);
                                let $elNModal = $(`#${formId} #format_${bundleElId}_modal`);
                                let $elVModal = $(`#${formId} #${bundleElId}_modal`);
                                let $elNQty = $(`#${formId} #format_${bundleElId}_qty`);
                                let $elVQty = $(`#${formId} #${bundleElId}_qty`)
                                let $elNSatuan = $(`#${formId} #autocomplete_${bundleElId}_nama_satuan_brg_qty`);
                                let $elVSatuan = $(`#${formId} #${bundleElId}_id_satuan_brg_qty`);
                                let $elNSatuanK = $(`#${formId} #format_${bundleElId}_satuan_terkecil`);
                                let $elVSatuanK = $(`#${formId} #${bundleElId}_satuan_terkecil`);
                                let $elNHarga = $(`#${formId} #format_${bundleElId}_harga_satuan`);
                                let $elVHarga = $(`#${formId} #${bundleElId}_harga_satuan`);
                                let $elNMProfit = $(`#${formId} #format_${bundleElId}_mprofit`)
                                let $elNDisc = $(`#${formId} #format_${bundleElId}_persen_disc`);
                                let $elVDisc = $(`#${formId} #${bundleElId}_persen_disc`);
                                let $elPAcDisc = $(`#${formId} #format_${bundleElId}_container_actual_disc`);
                                let $elNAcDisc = $(`#${formId} #format_${bundleElId}_actual_disc`);
                                let $elNTotal = $(`#${formId} #format_${bundleElId}_harga_total`);
                                let $elNUntungBersih = $(`#${formId} #format_${bundleElId}_untung_bersih`);

                                let $elNBQty = $(`#${formId} #format_${bundleElId}_bonus_qty`);
                                let $elVBQty = $(`#${formId} #${bundleElId}_bonus_qty`);

                                let $elNSBQty = $(`#${formId} #autocomplete_${bundleElId}_nama_satuan_brg_bonus_qty`);
                                let $elVSBQty = $(`#${formId} #${bundleElId}_id_satuan_brg_bonus_qty`);

                                /**
                                 * @param {VProdukBundle[]} list
                                 * @param {string} idSatBrg
                                 * */
                                function getProdukBundleQty(list, idSatBrg){
                                    for(let i=0; i<list.length; i++){
                                        if(idSatBrg === list[i].id_satuan_brg){
                                            return list[i].qty;
                                        }
                                    }
                                    return null;
                                }

                                function elTotalListener(){
                                    setTimeout(()=>{
                                        let discV = $elVDisc.val();
                                        let qtyVK = $elVSatuanK.val();
                                        let hargaV = $elVHarga.val();
                                        if(discV !== '' && hargaV !== '' && qtyVK !== ''){
                                            hargaV = parseInt(hargaV) * parseInt(qtyVK);
                                            let total = hargaV;

                                            discV.split('+').forEach(disc=>{
                                                disc = Number(disc.replaceAll(',', '.'));
                                                total = total - ((total * disc)/100);
                                            });
                                            total = parseInt(total);

                                            $elNTotal.val(total.toLocaleString('id-ID'));

                                            let untungBersih = 0;
                                            if($elNDiscSupplier.val() !== ''){
                                                let supplierDisc = calcActualDisc($elNDiscSupplier.val(), false) ?? parseFloat($elNDiscSupplier.val().replace(',','.'));
                                                let customerDisc = calcActualDisc($elNDisc.val(), false) ?? parseFloat($elNDisc.val().replace(',','.'));

                                                let profit = parseFloat((supplierDisc - customerDisc).toFixed(8));
                                                untungBersih = parseInt((hargaV*profit)/100);
                                            }
                                            else{
                                                let modal = parseInt($elVModal.val()) * parseInt(qtyVK);
                                                untungBersih = total - modal;
                                            }

                                            $elNUntungBersih.val(untungBersih.toLocaleString('id-ID'));
                                        }
                                        else{
                                            $elNTotal.val('');
                                            $elNUntungBersih.val('');
                                        }
                                        that.sumSubTotalFakturPj(formId);
                                    }, 800);
                                }

                                async function produkChange(){
                                    let p = (await getProduk($elIdProduk.val()))[0];

                                    console.log(p.disc_dari_supplier);
                                    if(p.disc_dari_supplier !== ''){
                                        $elNDiscSupplier.val(p.disc_dari_supplier);
                                        $elNDiscSupplier.change();
                                    }

                                    $elNModal.val((p.modal === '')? '' : parseInt(p.modal).toLocaleString('id-ID'));
                                    $elVModal.val(p.modal);

                                    $elNHarga.change(()=>{
                                        setTimeout(()=> {
                                            let v = $elNHarga.val();

                                            if(v === ''){
                                                $elVHarga.val('');
                                                return;
                                            }

                                            $elNMProfit.val(calcMProfit($elVHarga.val(), $elVModal.val()));
                                            elTotalListener();
                                        }, 700);
                                    });

                                    let satuan_brg = [
                                        {value: p.format_satuan_brg, data: p.id_satuan_brg}
                                    ];

                                    function setSatKecil(){
                                        let satV = $elVSatuan.val();
                                        let qtyV = $elVQty.val();
                                        if(satV !== '' && qtyV !== ''){
                                            let qtyM = getProdukBundleQty(p.produk_bundle, satV);
                                            qtyM = (qtyM === null)? 1 : qtyM;

                                            let qtyK = parseInt(qtyV) * parseInt(qtyM);
                                            $elVSatuanK.val(qtyK);
                                            $elNSatuanK.val(`${qtyK.toLocaleString('id-ID')} ${p.format_satuan_brg}`);
                                        }
                                        else{
                                            $elVSatuanK.val('');
                                            $elNSatuanK.val('');
                                        }
                                        elTotalListener();
                                    }

                                    p.produk_bundle.forEach((e)=>{
                                        satuan_brg.push({
                                            value: e.nama_satuan_brg,
                                            data: e.id_satuan_brg,
                                        });
                                    });

                                    $elNSatuan.autocomplete({
                                        lookup: satuan_brg,
                                        onSelect: function (suggestion) {
                                            $elVSatuan.val(suggestion.data);
                                            setSatKecil();
                                        }
                                    });
                                    $elNSatuan.change(()=>{
                                        setTimeout(()=> {
                                            let v = $elNSatuan.val();

                                            if(v === ''){
                                                $elVSatuan.val('');
                                                return;
                                            }

                                            let f = false;
                                            for (let i = 0; i < satuan_brg.length; i++) {
                                                if (v === satuan_brg[i].value) {
                                                    f = true;
                                                    break;
                                                }
                                            }
                                            if (!f) {
                                                nAlert('Satuan Produk tidak ditemukan, silahkan tambah satuan produk baru jika tidak tersedia');
                                                $elNSatuan.val('');
                                            }
                                        }, 700);
                                    });

                                    $elNQty.change(()=>{ setSatKecil() });

                                    $elNSBQty.autocomplete({
                                        lookup: satuan_brg,
                                        onSelect: function (suggestion) {
                                            $elVSBQty.val(suggestion.data);
                                        }
                                    });
                                    $elNSBQty.change(()=>{
                                        setTimeout(()=> {
                                            let v = $elNSBQty.val();

                                            if(v === ''){
                                                $elVSBQty.val('');
                                                return;
                                            }

                                            let f = false;
                                            for (let i = 0; i < satuan_brg.length; i++) {
                                                if (v === satuan_brg[i].value) {
                                                    f = true;
                                                    break;
                                                }
                                            }
                                            if (!f) {
                                                nAlert('Satuan Produk tidak ditemukan, silahkan tambah satuan produk baru jika tidak tersedia');
                                                $elNSBQty.val('');
                                            }
                                        }, 700);
                                    });

                                }

                                $elProduk.change(()=>{
                                    $elNDiscSupplier.val('');
                                    $elVDiscSupplier.val('');
                                    actDiscHandler($elNDiscSupplier, $elPAcDiscSupplier, $elNAcDiscSupplier);

                                    $elNSatuan.off('change');
                                    $elNSatuan.val('');

                                    $elNSatuanK.val('');

                                    $elNQty.off('change');

                                    $elNModal.val('');
                                    $elNHarga.off('change');
                                    $elNHarga.val('');
                                    $elNMProfit.val('');

                                    $elNSBQty.off('change');
                                    $elNSBQty.val('');

                                    setTimeout(()=> { produkChange(); }, 800);
                                });

                                $(`#${formId} .satuan_terkecil`).text($(`#${formId} #autocomplete_format_satuan_brg`).val());

                                initCurrency($elNQty, $elVQty);

                                initCurrency($elNBQty, $elVBQty);

                                initCurrency($elNHarga, $elVHarga);

                                $elNMProfit.change(()=>{
                                    let modal = $elVModal.val();
                                    let mProfit = $elNMProfit.val();

                                    if(!(/^[\d]*[,]?[\d]*$/.test(mProfit))){
                                        nAlert('Format persen tidak valid', ALERT_FAIL);
                                        return;
                                    }

                                    if(modal === '' || mProfit === '') {
                                        $elNMProfit.val('');
                                        return;
                                    }

                                    mProfit = parseFloat(mProfit.replace(',', '.'));
                                    modal = parseInt(modal);
                                    let harga = parseInt((modal + ((modal * mProfit)/100)));
                                    $elNHarga.val(harga.toLocaleString('id-ID'));
                                    $elVHarga.val(harga);
                                    $elNHarga.change();
                                });

                                function actDiscHandler($elVal, $elPMsg, $elMsg){
                                    let aDisc = calcActualDisc($elVal.val());
                                    if(aDisc !== '') {
                                        $elPMsg.prop('hidden', false);
                                        $elMsg.text(aDisc);
                                    }
                                    else $elPMsg.prop('hidden', true);
                                }
                                actDiscHandler($elNDisc, $elPAcDisc, $elNAcDisc);
                                actDiscHandler($elNDiscSupplier, $elPAcDiscSupplier, $elNAcDiscSupplier);

                                $elNDisc.change(()=>{
                                    if(!discCheck($elNDisc.val())){
                                        nAlert(errDiscFormatMsg(), ALERT_FAIL);
                                        $elNDisc.val('');
                                        $elVDisc.val('');
                                        return;
                                    }
                                    $elVDisc.val($elNDisc.val());
                                    actDiscHandler($elNDisc, $elPAcDisc, $elNAcDisc);
                                    elTotalListener()
                                });

                                $elNDiscSupplier.change(()=>{
                                    if($elNDiscSupplier.val() !== '' && !discCheck($elNDiscSupplier.val())){
                                        nAlert(errDiscFormatMsg(), ALERT_FAIL);
                                        $elNDiscSupplier.val('');
                                        $elVDiscSupplier.val('');
                                        return;
                                    }
                                    $elVDiscSupplier.val($elNDiscSupplier.val());
                                    actDiscHandler($elNDiscSupplier, $elPAcDiscSupplier, $elNAcDiscSupplier);
                                    elTotalListener()
                                });

                                if(barangKeluar !== undefined && barangKeluar.id !== ''){
                                    produkChange().then(()=>{
                                        $elNQty.change();
                                    });
                                }
                            }
                        );
                    },
                    addNewDataBundlePanel: function (formId){
                        this.addDataBundlePanel(formId, undefined);
                    },
                    addEditDataBundlePanel: function (formId, barangKeluar){
                        this.addDataBundlePanel(formId, barangKeluar);
                    },

                    addNewPanelBuilder: {
                        isPanelFormInput: true,
                        contentBuilder: (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            return `
                                <form id="${formId}" class="form-horizontal form-label-left" data-parsley-validate novalidate xmlns="http://www.w3.org/1999/html">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Pelanggan<span class="required">*</span></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="nama_pelanggan" id="autocomplete_nama_pelanggan" type="text" class="form-control" placeholder="Nama Pelanggan" required onchange="setTimeout(()=>{ changeTitlePanel('${panelTitleId}', '${actionTitle}: ' + this.value) }, 800)"
                                                value="${dataTableItem.nama_pelanggan}">
                                            <input name="id_pelanggan" id="id_pelanggan" type="text" hidden
                                                value="${dataTableItem.id_pelanggan}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal<span class="required">*</span></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-prepend input-group">
                                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                <input name="format_tanggal" type="text" class="form-control" id="format_tanggal" placeholder="Tanggal" aria-describedby="inputSuccess2Status">
                                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                                <input name="tanggal" id="tanggal" type="text" hidden
                                                    value="${dataTableItem.tanggal}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No Faktur</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="kode" id="kode" type="text" class="form-control" placeholder="No Faktur" required readonly="readonly"
                                                value="${dataTableItem.kode}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="format_subtotal" id="format_subtotal" type="text" class="form-control" placeholder="Sub Total" required readonly="readonly"
                                                value="${dataTableItem.format_subtotal}">
                                            <input name="subtotal" id="subtotal" type="text" hidden
                                                value="${dataTableItem.subtotal}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">PPN (%)<span class="required">*</span></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="format_persen_ppn" id="format_persen_ppn" type="text" class="form-control" placeholder="Pajak Pertambahan Nilai" required
                                                data-parsley-type="digits" min="0" max="100" step="1" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-range-message="Rentang nilai dari 0 hingga 100"
                                                value="${(dataTableItem.persen_ppn === '') ? '0' : dataTableItem.persen_ppn}">
                                            <input name="persen_ppn" id="persen_ppn" type="text" hidden
                                                value="${(dataTableItem.persen_ppn === '') ? '0' : dataTableItem.persen_ppn}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="format_total" id="format_total" type="text" class="form-control" placeholder="Total" required readonly="readonly"
                                                value="${dataTableItem.format_total}">
                                            <input name="total" id="total" type="text" hidden
                                                value="${dataTableItem.total}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bayar<span class="required">*</span></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="format_bayar" id="format_bayar" type="text" class="form-control" placeholder="Bayar" required
                                                value="${dataTableItem.format_bayar}">
                                            <input name="bayar" id="bayar" type="text" hidden
                                                value="${dataTableItem.bayar}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input name="format_sisa" id="format_sisa" type="text" class="form-control" placeholder="Sisa" required readonly="readonly"
                                                value="${dataTableItem.format_sisa}">
                                            <input name="sisa" id="sisa" type="text" hidden
                                                value="${dataTableItem.sisa}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Barang</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <button type="button" class="fbtn btn-primary" onclick="PanelFakturPJ.addNewDataBundlePanel('${formId}')">Tambah Barang</button>
                                        </div>
                                    </div>
                                    <div class="form-group dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped table-bordered nowrap no-footer table-bundle" cellspacing="0" width="100%" role="grid" style="width: 100%;"
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Produk</th>
                                                        <th>Disc dari</br/>Supplier (%)</th>
                                                        <th>Qty</th>
                                                        <th>Satuan</th>
                                                        <th>Satuan Terkecil</th>
                                                        <th>Modal @</th>
                                                        <th>Harga @</th>
                                                        <th>Disc (%)</th>
                                                        <th>Bonus</th>
                                                        <th>Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Untung Bersih</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="bundelContainer"></tbody>
                                                </table>
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
                        onDrawCallback: async (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            changeTitlePanel(panelTitleId, actionTitle + ': ' + dataTableItem.nama_pelanggan);

                            init_autocomplete(
                                'pelanggan',
                                '<?php echo HOST?>',
                                'pelanggan',
                                formId,
                                'autocomplete_nama_pelanggan',
                                'id_pelanggan'
                            );

                            initDaterangePickerSingleInput(
                                formId,
                                'format_tanggal',
                                'tanggal',
                                (dataTableItem.tanggal === '')
                                    ? null
                                    : parseInt(dataTableItem.tanggal)
                            );

                            let elKode = $(`#${formId} #kode`);
                            let elPl = $(`#${formId} #id_pelanggan`);
                            let generateCode = function (){
                                elKode.val('');
                                setTimeout(()=>{
                                    if(elPl.val() !== ''){
                                        let d = new Date();
                                        elKode.val(`${d.getFullYear()-2000}${numToNDigitStr(d.getMonth()+1, 2)}${numToNDigitStr(d.getDate(), 2)}.${numToNDigitStr(d.getHours(), 2)}${numToNDigitStr(d.getMinutes(), 2)}${numToNDigitStr(d.getSeconds(), 2)}.${elPl.val()}`);
                                    }
                                },800);
                            };
                            $(`#${formId} #autocomplete_nama_pelanggan`).change(generateCode);

                            let $elNSubTotal = $(`#${formId} #format_subtotal`);
                            let $elVSubTotal = $(`#${formId} #subtotal`);
                            let $elNPPN = $(`#${formId} #format_persen_ppn`);
                            let $elVPPN = $(`#${formId} #persen_ppn`);
                            let $elNTotal = $(`#${formId} #format_total`);
                            let $elVTotal = $(`#${formId} #total`);
                            let $elNBayar = $(`#${formId} #format_bayar`);
                            let $elVBayar = $(`#${formId} #bayar`);
                            let $elNSisa = $(`#${formId} #format_sisa`);
                            let $elVSisa = $(`#${formId} #sisa`);
                            initCurrency($elNSubTotal, $elVSubTotal);
                            initCurrency($elNPPN, $elVPPN);
                            initCurrency($elNTotal, $elVTotal);
                            initCurrency($elNBayar, $elVBayar);
                            initCurrency($elNSisa, $elVSisa);

                            function countTotal(){
                                let subTotal = $elVSubTotal.val();
                                let ppn = $elVPPN.val();
                                if(subTotal !== '' && ppn !== ''){
                                    subTotal = parseInt(subTotal);
                                    let total = parseInt(subTotal + ((subTotal * ppn)/100));
                                    $elNTotal.val(total.toLocaleString('id-ID'));
                                    $elVTotal.val(total);

                                    return total;
                                }
                                else{
                                    $elNTotal.val('');
                                    $elVTotal.val('');
                                }

                                return null;
                            }

                            function countSisa(){
                                let total = countTotal();
                                let bayar = $elVBayar.val();

                                if(total !== null && bayar !== ''){
                                    let sisa = parseInt(bayar) - total;
                                    $elNSisa.val(sisa.toLocaleString('id-ID'));
                                    $elVSisa.val(sisa);
                                }
                                else{
                                    $elNSisa.val('');
                                    $elVSisa.val('');
                                }
                            }

                            $elNPPN.change(()=>{ countSisa(); });
                            $elNBayar.change(()=>{ countSisa(); });

                            PanelFakturPJ.sumSubTotalFakturPj = function(formId){
                                let subTotal = 0;
                                let blankFound = false;
                                $(`#${formId} input[id$='_harga_total']`).each((i,e)=>{
                                    if(blankFound) return;

                                    let v = ($(e).val()).replace(/[\D\s._\-]+/g, '');
                                    if(v !== '') subTotal += parseInt(v);
                                    else blankFound = true;
                                });

                                $elNSubTotal.val((blankFound)? '': subTotal.toLocaleString('id-ID'));
                                $elVSubTotal.val((blankFound)? '': subTotal);
                                countSisa();
                            };


                            if(dataTableItem.id !== ''){
                                dataTableItem.bKeluar_list = await getBarangFaktur(dataTableItem.id);
                                dataTableItem.bKeluar_list.forEach((v)=>{
                                    PanelFakturPJ.addEditDataBundlePanel(formId, v);
                                });
                            }


                            // let $acSB = $(`#${formId} #autocomplete_format_satuan_brg`);
                            // $acSB.on('change', ()=>{
                            //     setTimeout(()=>{$(`#${formId} .satuan_terkecil`).text($acSB.val());}, 800);
                            // });
                        }
                    },

                    viewListBrgKeluar: function (idFaktur, noFaktur, namaPelanggan){
                        addPanel(
                            `List Barang Faktur: ${noFaktur}<br/><br/>Pelanggan: ${namaPelanggan}`,
                            false,
                            (panelId, panelTitleId)=>{
                                return `
                                    <table id="tabelf_brgkeluar" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th>Qty</th>
                                                <th>Qty Satuan Terkecil</th>
                                                <th>Modal @</th>
                                                <th>Harga @</th>
                                                <th>Disc</th>
                                                <th>Bonus</th>
                                                <th>Jumlah</th>
                                                <th>Diskon dari<br/>Supplier</th>
                                                <th>Untung Bersih</th>
                                                <th>Kategori</th>
                                                <th>Diinput Oleh</th>
                                            </tr>
                                        </thead>
                                    </table>
                                `;
                            },
                            (panelId, panelTitleId)=>{
                                $(`#${panelId} #tabelf_brgkeluar`).DataTable({
                                    serverSide: true,
                                    ajax: {
                                        url: `${PanelFakturPJ.host}api.php?datatables=v_brg_keluar&id_faktur=${idFaktur}`,
                                        type: 'POST',
                                    },
                                    // drawCallback: function( settings ) {},
                                    language: {
                                        decimal:        "",
                                        emptyTable:     "Tidak ada data di dalam tabel",
                                        info:           "Menampilkan _START_ sampai _END_ (total data: _TOTAL_)",
                                        infoEmpty:      "Menampilkan 0 data",
                                        infoFiltered:   "",
                                        infoPostFix:    "",
                                        thousands:      ",",
                                        lengthMenu:     "Menampilkan _MENU_ data",
                                        loadingRecords: "Loading...",
                                        processing:     "",
                                        search:         "Cari:",
                                        zeroRecords:    "Tidak ada data yang cocok ditemukan",
                                        paginate: {
                                            first:      "First",
                                            last:       "Last",
                                            next:       "Next",
                                            previous:   "Previous"
                                        },
                                        aria: {
                                            sortAscending:  ": activate to sort column ascending",
                                            sortDescending: ": activate to sort column descending"
                                        }
                                    },
                                });
                            }
                        )
                    },

                    regexBypassNameFormDataForEdit: /^bundle_[\d]*_id$/,
                    filterDateRange: {
                        dbFieldName: 'tanggal',
                    },
                });

                PanelHPembelian = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_hutang_pembelian',
                    dataTablesSchema: 'v_hutang_pembelian',
                    panelName: 'hutang',
                    dbSchema: 'hutang_pembelian',
                    actionEditClass: 'btnVHutangPembelianEdit',
                    actionCustomClass: [{
                        className: 'btnVHutangPembelianDebtCtrl',
                        callback: (that, target)=>{
                            toggleStatusHutang(that, that.dataTableGetId(target.getAttribute('value')));
                        }
                    }],
                    DataTableItem: function (that, row = undefined){
                        this.id = '';
                        this.id_supplier = '';
                        this.nama_supplier = '';
                        this.no_faktur = '';
                        this.nominal = '';
                        this.tanggal_beli = '';
                        this.status_lunas = '';
                        this.tanggal_lunas = '';
                        this.id_akun_pj = '';
                        this.nama_akun = '';
                        this.format_nominal = '';
                        this.format_tanggal_beli = '';
                        this.format_status_lunas = '';

                        if(row !== undefined) {
                            this.format_tanggal_beli = that.nullSafe(row[0]);
                            this.format_nominal = that.nullSafe(row[1]);
                            this.format_status_lunas = that.nullSafe(row[2]);
                            this.no_faktur = that.nullSafe(row[3]);
                            this.nama_supplier = that.nullSafe(row[4]);
                            this.tanggal_lunas = that.nullSafe(row[5]);
                            this.nama_akun = that.nullSafe(row[6]);
                            this.id = that.nullSafe(row[8]);
                            this.id_supplier = that.nullSafe(row[9]);
                            this.nominal = that.nullSafe(row[10]);
                            this.tanggal_beli = that.nullSafe(row[11]);
                            this.status_lunas = that.nullSafe(row[12]);
                        }
                    },
                    DataTableOpts: {
                        order: []
                    },
                    addNewPanelBuilder: {
                        isPanelFormInput: true,
                        contentBuilder: (actionTitle, isWrap, panelId, panelTitleId, formId, dataTableItem = new this.DataTableItem(this)) => {
                            return `
                    <form id="${formId}" class="form-horizontal form-label-left" data-parsley-validate novalidate>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No Faktur<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="no_faktur" type="text" class="form-control" placeholder="No Faktur" required onchange="changeTitlePanel('${panelTitleId}', '${actionTitle}: ' + this.value)"
                                    value="${dataTableItem.no_faktur}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Supplier<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nama_supplier" id="autocomplete_supplier" type="text" class="form-control" placeholder="Nama Supplier" required
                                    value="${dataTableItem.nama_supplier}">
                                <input name="id_supplier" id="id_supplier" type="text" hidden
                                    value="${dataTableItem.id_supplier}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nominal<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="format_nominal" id="format_nominal" type="text" class="form-control" placeholder="Nominal" required
                                    value="${dataTableItem.format_nominal}">
                                <input name="nominal" id="nominal" type="text" hidden
                                    value="${dataTableItem.nominal}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Beli<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input name="format_tanggal_beli" type="text" class="form-control" id="format_tanggal_beli" placeholder="Tanggal Beli" aria-describedby="inputSuccess2Status">
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                    <input name="tanggal_beli" id="tanggal_beli" type="text" hidden
                                        value="${dataTableItem.tanggal_beli}"/>
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
                            changeTitlePanel(panelTitleId, actionTitle + ': ' + dataTableItem.no_faktur);

                            init_autocomplete(
                                'supplier',
                                '<?php echo HOST?>',
                                'supplier',
                                formId,
                                'autocomplete_supplier',
                                'id_supplier'
                            );

                            initCurrency($(`#${formId} #format_nominal`), $(`#${formId} #nominal`));

                            initDaterangePickerSingleInput(
                                formId,
                                'format_tanggal_beli',
                                'tanggal_beli',
                                (dataTableItem.tanggal_beli === '')
                                    ? null
                                    : parseInt(dataTableItem.tanggal_beli)
                            );
                        }
                    },

                    filterDateRange: {
                        // parentId: 'filter_range',
                        // startDatePickerId: 'filter_date_start',
                        // endDatePickerId: 'filter_date_end',
                        // btnSetId: 'filter_set',
                        // btnClearId: 'filter_clr',
                        dbFieldName: 'tanggal_beli',
                    },
                });
            }

            $(document).ready( function () {
                initPanel();

                PanelHPembelian.init();
                <?php if($isSuperAdmin) { ?>
                PanelHPembelian.init_parsley('deadLineHutangPb');
                $('#deadLineHutangPb').parsley().on('form:submit', function (formInstance) {
                    // if you want to prevent submit in any condition after validation success -> return it false
                    //console.log(formInstance);
                    let data = new FormData(document.getElementById('deadLineHutangPb'));

                    $.ajax({
                        url: encodeURI(`${PanelHPembelian.host}api.php?user_info=${JSON.stringify({
                            action: 'update',
                        })}`),
                        cache: false,
                        contentType: false,
                        processData: false,
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: (resp) => {
                            if (resp.result) {
                                //reload dataTable
                                $(PanelHPembelian.$tableId).DataTable().ajax.reload();

                                nAlert(`Sukses merubah jatuh tempo`, ALERT_SUCCESS);
                            } else {
                                nAlert((resp.msg !== undefined)? resp.msg : `Gagal merubah jatuh tempo`, ALERT_FAIL);
                            }
                        }
                    });

                    return false;
                });
                <?php } ?>

                PanelFakturPJ.init();
                <?php if($isSuperAdmin) { ?>
                PanelFakturPJ.init_parsley('deadLineHutangPj');
                $('#deadLineHutangPj').parsley().on('form:submit', function (formInstance) {
                    // if you want to prevent submit in any condition after validation success -> return it false
                    //console.log(formInstance);
                    let data = new FormData(document.getElementById('deadLineHutangPj'));

                    $.ajax({
                        url: encodeURI(`${PanelFakturPJ.host}api.php?user_info=${JSON.stringify({
                            action: 'update',
                        })}`),
                        cache: false,
                        contentType: false,
                        processData: false,
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: (resp) => {
                            if (resp.result) {
                                //reload dataTable
                                $(PanelFakturPJ.$tableId).DataTable().ajax.reload();

                                nAlert(`Sukses merubah jatuh tempo`, ALERT_SUCCESS);
                            } else {
                                nAlert((resp.msg !== undefined)? resp.msg : `Gagal merubah jatuh tempo`, ALERT_FAIL);
                            }
                        }
                    });

                    return false;
                });
                <?php } ?>
            } );
        </script>
        <?php
    },
);