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
                    <div class="x_panel panel_color1" style="height: auto;">
                        <div class="x_title">
                            <h2>List Kategori Barang</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: none;">
                            <span class="btn btn-primary" onclick="PanelKategoriBrg.addNewDataPanel()">Tambah Kategori Baru</span>
                            <br/><br/>
                            <table id="tabel_kategori_brg" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nama</th>
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
                    <div class="x_panel panel_color2" style="height: auto;">
                        <div class="x_title">
                            <h2>List Satuan Barang</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: none;">
                            <span class="btn btn-primary" onclick="PanelSatuanBrg.addNewDataPanel()">Tambah Satuan Baru</span>
                            <br/><br/>
                            <table id="tabel_satuan_brg" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Singkatan</th>
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
                            <h2>List Produk</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <span class="btn btn-primary" onclick="PanelVProduk.addNewDataPanel()">Tambah Produk Baru</span>
                            <br/><br/>
                            <table id="tabel_produk" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Modal</th>
                                    <th>Satuan Terkecil</th>
                                    <th>Kategori</th>
                                    <th>Bundel</th>
                                    <th>Diskon dari Supplier</th>
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

            let PanelKategoriBrg;
            let PanelSatuanBrg;
            let PanelVProduk;

            function calcActualDisc(disc){
                if(disc.indexOf('+') === -1) return '';

                let calc = (d1, d2) => d1+d2-(d1*d2/100);

                let dList = disc.split('+');
                let aDisc = calc(parseFloat(dList[0].replace(',','.')), parseFloat(dList[1].replace(',','.')));
                for(let i=2; i<dList.length; i++){
                    aDisc = calc(aDisc, parseFloat(dList[i].replace(',','.')));
                }

                let p = parseFloat(aDisc.toFixed(8));
                return p.toLocaleString('id-ID');
            }
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

            function initPanel(){
                PanelKategoriBrg = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_kategori_brg',
                    dataTablesSchema: 'kategori_brg',
                    panelName: 'kategori',
                    dbSchema: 'kategori_brg',
                    actionEditClass: 'btnKategoriBrgEdit',
                    DataTableItem: function (that, row = undefined){
                        this.id = '';
                        this.nama = '';

                        if(row !== undefined) {
                            this.nama = that.nullSafe(row[0]);
                            this.id = that.nullSafe(row[2]);
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


                PanelSatuanBrg = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_satuan_brg',
                    dataTablesSchema: 'satuan_brg',
                    panelName: 'satuan',
                    dbSchema: 'satuan_brg',
                    actionEditClass: 'btnSatuanBrgEdit',
                    DataTableItem: function (that, row = undefined){
                        this.id = '';
                        this.nama = '';
                        this.singkatan = '';

                        if(row !== undefined) {
                            this.nama = that.nullSafe(row[0]);
                            this.singkatan = that.nullSafe(row[1]);
                            this.id = that.nullSafe(row[3]);
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
                           <label class="control-label col-md-3 col-sm-3 col-xs-12">Singkatan (3 Karakter)<span class="required">*</span></label>
                           <div class="col-md-9 col-sm-9 col-xs-12">
                               <input name="singkatan" type="text" class="form-control" placeholder="Singkatan" required oninput="this.value = this.value.toUpperCase()"
                                   value="${dataTableItem.singkatan}" data-parsley-minlength="3" data-parsley-maxlength="3" data-parsley-minlength-message="Tidak boleh kurang dari 3 karakter" data-parsley-maxlength-message="Tidak boleh lebih dari 3 karakter">
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


                PanelVProduk = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_produk',
                    dataTablesSchema: 'v_produk',
                    panelName: 'produk',
                    dbSchema: 'produk',
                    actionEditClass: 'btnVProdukEdit',
                    DataTableItem: function (that, row = undefined){
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
                    },

                    bundleCounter: 0,
                    addBundlePanel: function (formId, vProdukBundle = new VProdukBundle(), onDrawCallback){
                        this.bundleCounter++;
                        let bundleElId = `bundle_${this.bundleCounter}`;

                        let html = `
                            <div id="${bundleElId}" class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <ul class="nav navbar-right panel_toolbox" style="min-width:unset">
                                                <li><a class="close-link" onclick="PanelVProduk.removeBundle('${bundleElId}')"><i class="fa fa-close"></i></a></li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="col-md-9 col-sm-9 col-xs-12" style="padding: unset">
                                                <input name="${bundleElId}_id" id="${bundleElId}_id" type="text" hidden value="${vProdukBundle.id}"/>

                                                <div class="input-group">
                                                    <span class="input-group-btn"><span class="min_box" style="padding: 5px;">Satu</span></span>
                                                    <input name="format_${bundleElId}_satuan_brg" id="autocomplete_${bundleElId}_satuan_brg" type="text" class="form-control" placeholder="Satuan Barang" required
                                                        value="${vProdukBundle.nama_satuan_brg}">
                                                </div>
                                                <input name="${bundleElId}_id_satuan_brg" id="${bundleElId}_id_satuan_brg" type="text" hidden
                                                    value="${vProdukBundle.id_satuan_brg}"/>

                                                <div class="input-group">
                                                    <span class="input-group-btn"><span class="min_box" style="padding: 5px;">Sama dengan</span></span>
                                                    <input name="format_${bundleElId}_qty" id="format_${bundleElId}_qty" type="text" class="form-control" placeholder="Qty" required
                                                        value="${(vProdukBundle.qty.length > 0)? parseInt(vProdukBundle.qty).toLocaleString('id-ID'): ''}">
                                                    <span class="input-group-btn"><span class="min_box satuan_terkecil" style="padding: 5px;"></span></span>
                                                </div>
                                                <input name="${bundleElId}_qty" id="${bundleElId}_qty" type="text" hidden
                                                    value="${vProdukBundle.qty}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $(`#${formId} #bundelContainer`).append(html);

                        onDrawCallback(bundleElId);

                        return [bundleElId];
                    },
                    removeBundle: function (bundleElId) {
                        delete this.autocompleteListEl[bundleElId];

                        if($(`#${bundleElId}_id`).val() === '') {
                            //insert mode
                            $(`#${bundleElId}`).remove();
                        }
                        else{
                            //update mode, set data for delete
                            $(`#${bundleElId}`).attr('style', 'display: none;');
                            $(`#${bundleElId}_id_satuan_brg`).val('');
                            $(`#${bundleElId}_qty`).val('');
                        }
                    },

                    autocompleteListEl: {},
                    addDataBundlePanel: function (formId, vProdukBundle){
                        this.addBundlePanel(
                            formId,
                            vProdukBundle,
                            (bundleElId)=>{
                                this.autocompleteListEl[bundleElId] = $(`#autocomplete_${bundleElId}_satuan_brg`);
                                this.autocompleteListEl[bundleElId].on('change', ()=>{
                                    setTimeout(()=>{
                                        let c = this.autocompleteListEl[bundleElId].val();

                                        if(c === '') return;

                                        let isUnique = true;
                                        let keys = Object.keys(this.autocompleteListEl);
                                        for(let i=0; i<keys.length; i++){
                                            if(keys[i] === bundleElId) continue;

                                            let e = this.autocompleteListEl[keys[i]];
                                            if(e.val() === c) {
                                                isUnique = false;
                                                break;
                                            }
                                        }

                                        if(!isUnique){
                                            this.autocompleteListEl[bundleElId].val('');
                                            nAlert('Data sudah ada', ALERT_NOTICE);
                                        }
                                    }, 800);
                                })

                                init_autocomplete(
                                    'satuan barang',
                                    '<?php echo HOST?>',
                                    'satuan_brg',
                                    formId,
                                    `autocomplete_${bundleElId}_satuan_brg`,
                                    `${bundleElId}_id_satuan_brg`
                                );

                                $(`#${formId} .satuan_terkecil`).text($(`#${formId} #autocomplete_format_satuan_brg`).val());

                                initCurrency($(`#${formId} #format_${bundleElId}_qty`), $(`#${formId} #${bundleElId}_qty`));
                            }
                        );
                    },
                    addNewDataBundlePanel: function (formId){
                        this.addDataBundlePanel(formId, undefined);
                    },
                    addEditDataBundlePanel: function (formId, vProdukBundle){
                        this.addDataBundlePanel(formId, vProdukBundle);
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
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Modal<span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input name="format_modal" id="format_modal" type="text" class="form-control" placeholder="Modal" required
                                                    value="${(dataTableItem.modal === '')? '' : parseInt(dataTableItem.modal).toLocaleString('id-ID')}">
                                                <input name="modal" id="modal" type="text" hidden
                                                    value="${dataTableItem.modal}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Satuan Terkecil<span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input name="format_satuan_brg" id="autocomplete_format_satuan_brg" type="text" class="form-control" placeholder="Satuan Barang Terkecil" required
                                                    value="${dataTableItem.format_satuan_brg}">
                                                <input name="id_satuan_brg" id="id_satuan_brg" type="text" hidden
                                                    value="${dataTableItem.id_satuan_brg}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori<span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input name="format_kategori_brg" id="autocomplete_format_kategori_brg" type="text" class="form-control" placeholder="Kategori Barang" required
                                                    value="${dataTableItem.format_kategori_brg}">
                                                <input name="id_kategori_brg" id="id_kategori_brg" type="text" hidden
                                                    value="${dataTableItem.id_kategori_brg}"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Bundel</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <button type="button" class="fbtn btn-primary" onclick="PanelVProduk.addNewDataBundlePanel('${formId}')">Tambah Bundel</button>
                                                <div id="bundelContainer"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon dari supplier (%)</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input name="disc_dari_supplier" id="disc_dari_supplier" type="text" class="form-control" placeholder="Persen"
                                                data-parsley-disc-check="" data-parsley-trigger="keyup"
                                                value="${dataTableItem.disc_dari_supplier}">
                                                <div id="format_container_actual_disc" style="font-size: 12px; width: 100%; margin-top: 10px;">Setara dengan <span id="format_actual_disc"></span> %</div>
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

                            initCurrency($(`#${formId} #format_modal`), $(`#${formId} #modal`));

                            init_autocomplete(
                                'satuan barang',
                                '<?php echo HOST?>',
                                'satuan_brg',
                                formId,
                                'autocomplete_format_satuan_brg',
                                'id_satuan_brg'
                            );

                            dataTableItem.produk_bundle.forEach((v)=>{
                                //console.log(v);
                                PanelVProduk.addEditDataBundlePanel(formId, v);
                            });


                            $(`#${formId} .satuan_terkecil`).text(dataTableItem.format_satuan_brg);

                            let $acSB = $(`#${formId} #autocomplete_format_satuan_brg`);
                            $acSB.on('change', ()=>{
                                setTimeout(()=>{$(`#${formId} .satuan_terkecil`).text($acSB.val());}, 800);
                            });

                            init_autocomplete(
                                'kategori barang',
                                '<?php echo HOST?>',
                                'kategori_brg',
                                formId,
                                'autocomplete_format_kategori_brg',
                                'id_kategori_brg'
                            );

                            let $discDariSupplier = $(`#${formId} #disc_dari_supplier`);
                            let $elPAcDisc  = $(`#${formId} #format_container_actual_disc`);
                            let $elNAcDisc  = $(`#${formId} #format_actual_disc`);

                            function actDiscHandler(){
                                let aDisc = calcActualDisc($discDariSupplier.val());
                                if(aDisc !== '') {
                                    $elPAcDisc.prop('hidden', false);
                                    $elNAcDisc.text(aDisc);
                                }
                                else $elPAcDisc.prop('hidden', true);
                            }
                            actDiscHandler();

                            $discDariSupplier.change(()=>{
                                if(!discCheck($discDariSupplier.val())){
                                    nAlert(errDiscFormatMsg(), ALERT_FAIL);
                                    $discDariSupplier.val('');
                                    return;
                                }

                                actDiscHandler();
                            });
                        }
                    },

                    regexBypassNameFormDataForEdit: /^bundle_[\d]*_id$/,
                });
            }


            $(document).ready( function () {
                initPanel();

                PanelVProduk.init();
                PanelKategoriBrg.init();
                PanelSatuanBrg.init();
            } );
        </script>
        <?php
    },
);