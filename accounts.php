<?php
include_once 'function/init.php';
include_once ROOTDIR . 'template/cpanel.php';

$akun = Akun::Cast($_SESSION['akun']);
$isSuperAdmin = (int)$akun->level_akses === 0;
$isGetID = array_key_exists('id', $_GET);
$isWriteable = $isSuperAdmin || ($isGetID && $_GET['id'] == $akun->id);


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
    function () use ($isSuperAdmin){
        ?>

        <div id="panelContainer" class="">
            <div class="clearfix"></div>

            <?php if($isSuperAdmin) {?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color3">
                        <div class="x_title">
                            <h2>List Akun</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <span class="btn btn-primary" onclick="addNewAkunPanel()">Tambah Akun Baru</span>
                            <br/><br/>
                            <table id="tabel_akun" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Status Akun</th>
                                    <th>Level Akses</th>
                                    <th>No HP</th>
                                    <th>Alamat</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <?php
    },
    function () use ($isGetID, $isSuperAdmin, $isWriteable){
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
            class DataTableItem{
                nama = '';
                isActive = '';
                level_akses = '';
                nohp = '';
                alamat = '';
                username = '';
                password = '';
                id = '';
                foto = '';

                constructor(row = undefined) {
                    if(row !== undefined) {
                        // this.foto = row[0];
                        this.nama = this.nullSafe(row[1]);
                        this.level_akses = (this.nullSafe(row[3]) === 'Admin Super')? 0 : 1;
                        this.nohp = this.nullSafe(row[4]);
                        this.alamat = this.nullSafe(row[5]);
                        this.username = this.nullSafe(row[6]);
                        this.password = this.nullSafe(row[7]);
                        this.id = row[9];
                        this.isActive = this.nullSafe(row[10]);

                        this.foto = encodeURI(`<?php echo HOST . 'api.php?akun'?>={"foto":${this.id}}`);
                    }
                }

                nullSafe(val){
                    return (val === null)? '': val;
                }
            }

            let isRedirect = <?php echo ($isGetID)? 'true' : 'false'; ?>;
            function dataTableGetId(dataId){
                let dataTable =
                    <?php
                        function buildGetData($isGetID){
                            if(!$isGetID) return '[[]]';

                            $p = (array)(new DBAkun())->get($_GET['id']);
                            $dts = Akun::dataTables()[1];
                            $ret = [];
                            foreach ($dts as $dt) {
                                array_push(
                                    $ret,
                                    array_key_exists('formatter', $dt)
                                        ? ($dt['formatter'])($p[$dt['db']], $p)
                                        : ((string)$p[$dt['db']])
                                );
                            }
                            return json_encode(array($ret));
                        }

                        if($isSuperAdmin) {?>
                        (isRedirect)
                            ? (<?php echo buildGetData($isGetID); ?>)
                            : $('#tabel_akun').DataTable().rows().data();
                    <?php
                    }
                    else {
                        echo buildGetData($isGetID);
                    }?>


                let target = null;
                for(let i=0; i<dataTable.length; i++){
                    let a = new DataTableItem(dataTable[i]);
                    if(a.id === dataId){
                        target = a;
                        break;
                    }
                }
                return target;
            }

            function loadImgPreview(event){
                let output = document.getElementById('foto_preview');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    output.style.display = 'block';
                    URL.revokeObjectURL(output.src) // free memory
                }
            }

            /* PARSLEY usernamecheck*/
            window.Parsley.addValidator('usernamecheck', {
                validateString: function (value, requirement) {
                    let xhr = $.ajax({
                        url: encodeURI('<?php echo HOST?>api.php?akun={"isUsernameExists":"'+ value +'"}'),
                        dataType: 'json',
                        method: 'GET',
                    });

                    return xhr.then(function (data) {
                        console.log(`username ${value} => ` + JSON.stringify(data));

                        if (data.result === false) {
                            return true;
                        } else {
                            return $.Deferred().reject();
                        }
                    });
                },
                messages: {
                    en: 'Username sudah ada!',
                },
                priority: 32
            });
            function init_parsley(formId) {
                let id = `#${formId}`;
                if( typeof (parsley) === 'undefined'){ return; }
                console.log('init_parsley');

                let validateFront = function() {
                    if (true === $(id).parsley().isValid()) {
                        $('.bs-callout-info').removeClass('hidden');
                        $('.bs-callout-warning').addClass('hidden');
                    } else {
                        $('.bs-callout-info').addClass('hidden');
                        $('.bs-callout-warning').removeClass('hidden');
                    }
                };

                $(id).parsley().options.requiredMessage = "Ini wajib diisi";
                // $.Parsley.options.requiredMessage = "ini wajib diisi";

                $/*.listen*/('parsley:field:validate', function() {
                    validateFront();
                });

                $(`${id} .btn`).on('click', function() {
                    $(id).parsley().validate();
                    validateFront();
                });

                try {
                    hljs.initHighlightingOnLoad();
                } catch (err) {}
            }

            function addAkunPanel(actionTitle, isWrap, onSubmitCallback, dataTableItem = new DataTableItem()){
                let formId = `form_${panelCounter}`;

                return addPanel(
                    actionTitle,
                    isWrap,
                    (panelId, panelTitleId) => {
                        return `
                    <form id="${formId}" class="form-horizontal form-label-left" data-parsley-validate novalidate>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required restrict">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nama" type="text" class="form-control" placeholder="Nama" required onchange="changeTitlePanel('${panelTitleId}', '${actionTitle}: ' + this.value)"
                                    value="${dataTableItem.nama}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea name="alamat" class="form-control" rows="3" placeholder='Alamat'>${dataTableItem.alamat}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No HP </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="nohp" type="text" class="form-control" placeholder="No HP"
                                    value="${dataTableItem.nohp}">
                            </div>
                        </div>
                        <div class="form-group restrict">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Username <span class="required restrict">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input id="add_username" name="username" type="text" class="form-control" placeholder="Username" required data-parsley-usernamecheck=""
                                    onkeyup="((el)=>{ el.value = el.value.toLowerCase(); })(this)"
                                    value="${dataTableItem.username}">
                            </div>
                        </div>
                        <div class="form-group restrict">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Password <span class="required restrict">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="input-group">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" required
                                        value="${dataTableItem.password}">
                                    <span class="input-group-btn">
                                        <i class="fa fa-eye" id="togglePassword" style="height: 30px; width: auto; font-size: 25px; margin: 5px;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Level Akses <span class="required restrict">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select id="level_akses" name="level_akses" class="select2_single form-control" tabindex="-1" required>
                                    <option></option>
                                    <option value="0" ${(dataTableItem.level_akses === 0)? 'selected="selected"' : ''}>Admin Super</option>
                                    <option value="1" ${(dataTableItem.level_akses === 1)? 'selected="selected"' : ''}>Admin</option>
                                </select>
                            </div>
                        </div>

                        <br/>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto Profil</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="foto" class="restrict" type="file" accept="image/*" onchange="loadImgPreview(event)" />
                                <br/>
                                <img id="foto_preview" src="${dataTableItem.foto}" alt="foto profil" style="height: 100px; width: auto; ${(dataTableItem.foto === '')? 'display: none;' : ''}"/>
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="form-group restrict">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>

                    </form>
                        `;
                    },
                    (panelId, panelTitleId) => {
                        <?php if($isGetID && !$isSuperAdmin) {?>
                        $(`#${panelId} .panel_toolbox`).remove();
                        $(`#${formId} #level_akses`).prop('disabled', true );
                        <?php } ?>
                        <?php if(!$isWriteable) {?>
                        $(`#${formId} .restrict`).remove();
                        $(`#${formId} :input`).prop('disabled', true );
                        <?php } ?>

                        changeTitlePanel(panelTitleId, actionTitle + ': ' + dataTableItem.nama);

                        init_parsley(formId);

                        $(`#${formId}`).parsley().on('form:submit', function (formInstance) {
                            // if you want to prevent submit in any condition after validation success -> return it false
                            //console.log(formInstance);

                            onSubmitCallback(new FormData(document.getElementById(formId)), panelId);

                            return false;
                        });

                        let togglePassword = document.getElementById(formId).querySelector("#togglePassword");
                        let password = document.getElementById(formId).querySelector("#password");
                        togglePassword?.addEventListener("click", function () {
                            // toggle the type attribute
                            let type = password.getAttribute("type") === "password" ? "text" : "password";
                            password.setAttribute("type", type);

                            // toggle the icon
                            if(type === "text") {
                                togglePassword.classList.remove("fa-eye");
                                togglePassword.classList.add('fa-eye-slash');
                            }
                            else{
                                togglePassword.classList.add("fa-eye");
                                togglePassword.classList.remove('fa-eye-slash');
                            }
                        });
                    }
                ).concat([formId]);
            }

            <?php if($isSuperAdmin) {?>
            function addNewAkunPanel(){
                addAkunPanel('Buat Akun Baru', false, (data, panelId)=>{
                    if(confirm('Apakah data sudah OK?')) {
                        $.ajax({
                            url: encodeURI(`<?php echo HOST?>api.php?akun=${JSON.stringify({
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
                                    //tutup panel
                                    closePanel(panelId);

                                    //reload dataTable
                                    $('#tabel_akun').DataTable().ajax.reload();

                                    nAlert('Sukses menambah akun baru', ALERT_SUCCESS);
                                } else {
                                    nAlert((resp.msg !== undefined)? resp.msg : 'Gagal menambah akun baru', ALERT_FAIL);
                                }
                            }
                        });
                    }
                });
            }
            function toggleStatusAkun(dataId){
                let target = dataTableGetId(dataId);

                let isAktif = target.isActive === '1';
                let info = (isAktif)? 'menonaktifkan' : 'mengaktifkan';

                if(confirm(`Apakah anda ingin ${info} akun ${target.nama}?`)) {
                    //tambahkan id update target
                    let data = new FormData();
                    data.append('id', target.id);
                    data.append('isActive', (isAktif)? '0' : '1');

                    updateAjax(data);
                }
            }
            <?php } ?>

            function updateAjax(data, panelId = null) {
                $.ajax({
                    url: encodeURI(`<?php echo HOST?>api.php?akun=${JSON.stringify({
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
                            <?php if($isSuperAdmin) { ?>
                            //tutup panel
                            if(panelId !== null) closePanel(panelId);

                            //reload dataTable
                            $('#tabel_akun').DataTable().ajax.reload();

                            nAlert('Sukses merubah akun', ALERT_SUCCESS);
                            <?php } else { ?>
                            location.reload();
                            <?php } ?>
                        } else {
                            nAlert((resp.msg !== undefined)? resp.msg : 'Gagal merubah akun', ALERT_FAIL);
                        }
                    }
                });
            }
            function addEditAkunPanel(dataId){
                let target = dataTableGetId(dataId);

                let [panelId, panelTitleId, formId] = addAkunPanel(
                    <?php echo ($isWriteable? "'Edit akun'" : "'Akun'");?>,
                    false,

                    <?php if($isWriteable){ ?>
                    (data, panelId)=>{
                        let removeKeys = [];
                        for(let e of data.entries()){
                            if((e[0] === 'foto' && e[1].name === '') || (target[e[0]].toString() === e[1])){
                                removeKeys.push(e[0]);
                            }
                        }
                        removeKeys.forEach((k)=>{
                            data.delete(k);
                        });

                        let count=0;
                        for(let e of data.entries()) if (e.length > 0) count++;

                        if(count === 0) {
                            nAlert('Data masih sama', ALERT_INFO);
                            return;
                        }

                        if(confirm('Apakah data sudah OK?')) {
                            //tambahkan id update target
                            data.append('id', target.id);

                            updateAjax(data, panelId);
                        }
                    }
                    <?php } else { ?>
                    ()=>{} <?php } ?>,
                    target
                );

                let el = $(`#${formId} #add_username`);
                el.removeAttr('data-parsley-usernamecheck');
                el.prop('disabled', true);
            }


            $(document).ready( function () {
                let $tabelAkun = $('#tabel_akun');

                <?php if($isGetID){ ?>
                    addEditAkunPanel('<?php echo $_GET['id']?>');
                <?php } ?>


                <?php if($isSuperAdmin) {?>
                isRedirect = false;

                document.addEventListener("click", function(event) {
                    let target = event.target;
                    if (target.classList.contains('btnAkunActiveCtrl')) {
                        toggleStatusAkun(target.getAttribute('value'));
                    }
                    else if (target.classList.contains('btnAkunEdit')) {
                        addEditAkunPanel(target.getAttribute('value'));
                    }
                });

                $tabelAkun.DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    ajax: {
                        url: '<?php echo HOST?>api.php?datatables=<?php echo urlencode('akun') ?>',
                        type: 'POST'
                    },
                    // drawCallback: function( settings ) {},
                    language: {
                        decimal:        "",
                        emptyTable:     "Tidak ada data di dalam tabel",
                        info:           "Menampilkan _START_ sampai _END_ (total data: _TOTAL_)",
                        infoEmpty:      "Menampilkan 0 data",
                        infoFiltered:   "(filtered from _MAX_ total entries)",
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
                <?php } ?>
            } );
        </script>
        <?php
    },
);