<?php
include_once 'function/init.php';
include_once ROOTDIR . 'template/cpanel.php';

include_once ROOTDIR . 'class/DBUserInfo.php';
$profil = (new DBUserInfo())->get(1);


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
    function () use ($profil){
        ?>

        <div id="panelContainer" class="">
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color3">
                        <div class="x_title">
                            <h2>Profil Usaha</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <p>Semua data profil usaha ini (kecuali logo) akan ditampilkan pada Kop Faktur. Untuk input Info 1 & 2 dapat berisi kota domisili usaha, telepon, nomor WhatsApp, atau alamat URL website. Sedangkan data logo digunakan untuk tampilan dari sistem.</p>
                            <br/><br/>
                            <form id="form_profil_bisnis" class="form-horizontal form-label-left" data-parsley-validate novalidate>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama (Max 30 Karakter)<span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input name="nama" type="text" class="form-control" placeholder="Nama" required
                                               value="<?php echo $profil->nama;?>" data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat (Max 30 Karakter)<span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                    <textarea name="alamat" class="form-control" rows="3" placeholder='Alamat' data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter"
                                          required><?php echo $profil->alamat;?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Info 1 (Max 30 Karakter)<span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                    <textarea name="info1" class="form-control" rows="3" placeholder='Bisa berisi Kota Domisili usaha, Telepon, nomor WhatsApp, atau alamat URL Website' data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter"
                                              required><?php echo $profil->info1;?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Info 2 (Max 30 Karakter)<span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                    <textarea name="info2" class="form-control" rows="3" placeholder='Bisa berisi Kota Domisili usaha, Telepon, nomor WhatsApp, atau alamat URL Website' data-parsley-maxlength="30" data-parsley-maxlength-message="Tidak boleh lebih dari 30 karakter"
                                              required><?php echo $profil->info2;?></textarea>
                                    </div>
                                </div>

                                <br/>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Logo Usaha</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input name="logo" class="restrict" type="file" accept="image/*" onchange="loadImgPreview(event)" />
                                        <br/>
                                        <img id="logo_preview" src="<?php echo $GLOBALS['logoUrl']; ?>" alt="logo" style="height: 100px; width: auto;"/>
                                    </div>
                                </div>


                                <div class="ln_solid"></div>
                                <div class="form-group restrict">
                                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    },
    function () use ($profil){
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
            let target = JSON.parse('<?php echo json_encode(array(
                'id' => 1,
                'nama' => $profil->nama,
                'alamat' => $profil->alamat,
                'info1' => $profil->info1,
                'info2' => $profil->info2,
                'logo' => $GLOBALS['logoUrl'],
            ));?>');

            function loadImgPreview(event){
                let output = document.getElementById('logo_preview');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    output.style.display = 'block';
                    URL.revokeObjectURL(output.src) // free memory
                }
            }

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

            function updateAjax(data) {
                $.ajax({
                    url: encodeURI(`<?php echo HOST?>api.php?user_info=${JSON.stringify({
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
                            //nAlert('Sukses merubah akun', ALERT_SUCCESS);

                            location.reload();
                        } else {
                            nAlert((resp.msg !== undefined)? resp.msg : 'Gagal merubah akun', ALERT_FAIL);
                        }
                    }
                });
            }

            function onSubmitCallback(data){
                let removeKeys = [];
                for(let e of data.entries()){
                    if((e[0] === 'logo' && e[1].name === '') || (target[e[0]].toString() === e[1])){
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

                    updateAjax(data);
                }
            }


            $(document).ready( function () {
                let formId = 'form_profil_bisnis';

                init_parsley(formId);

                $(`#${formId}`).parsley().on('form:submit', function (formInstance) {
                    // if you want to prevent submit in any condition after validation success -> return it false
                    //console.log(formInstance);

                    onSubmitCallback(new FormData(document.getElementById(formId)));

                    return false;
                });

            } );
        </script>
        <?php
    },
);