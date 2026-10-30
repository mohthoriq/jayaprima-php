<?php
include_once 'function/init.php';

$akun = Akun::Cast($_SESSION['akun']);
$isSuperAdmin = $akun->level_akses == 0;

if(isset($_GET['deleteDB']) && $isSuperAdmin && isDbYearExists($_GET['deleteDB']) && sizeof($GLOBALS['dbList']) > 1){
    unlink(DBDIR . $_GET['deleteDB']);
    scanDB();
}

function sizeReformat($bytes, $decimals = 2): string {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    /** @noinspection PhpIllegalStringOffsetInspection */
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor] . 'B';
}

function getListDbs($isSuperAdmin) : array {
    $ret = array();
    $dbCount = sizeof($GLOBALS['dbList']);

    for ($i=0; $i < $dbCount; $i++){
        $db = $GLOBALS['dbList'][$i];

        $vwBtn = ($db == $_SESSION['DBYEAR'])
            ? ''
            : "<button class='btn btn-primary' onclick='changeDb($db)'>Lihat Data</button>";

        $delBtn = ($isSuperAdmin && $dbCount > 1)
            ? "<button class='btn btn-danger' onclick='deleteDb($db)'>Hapus Data</button>"
            : '';

        array_push($ret, array(
            $db,
            sizeReformat(filesize(DBDIR . $db)),
            "$vwBtn$delBtn"
        ));
    }
    return $ret;
}



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
    function () use ($isSuperAdmin) {
        ?>

        <div id="panelContainer" class="">
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel panel_color3">
                        <div class="x_title">
                            <h2>List Data Terdahulu</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <?php if($isSuperAdmin) { ?>
                            <span class="btn btn-primary" onclick="tutupBuku()">Tutup buku data tahun ini</span>
                            <?php } ?>
                            <br/><br/>
                            <table id="tabel_dbs" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Ukuran</th>
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
            let PanelDbs;

            <?php if($isSuperAdmin) { ?>
            function prosesTutupBuku(){
                let tahun = $('#modalTutupBuku #tahunTerbaru').val();
                if(tahun === ''){
                    nAlert('Tahun terbaru tidak boleh kosong', ALERT_FAIL);
                    return;
                }

                let data = PanelDbs.rows().data();
                for(let i=0; i<data.length; i++){
                    let db = data[i][0];

                    if(db === tahun){
                        nAlert(`Tahun ${tahun} sudah ada`, ALERT_FAIL);
                        return;
                    }
                }

                let $btnSubmit = $('#TutupBuku').find(':submit');
                if($btnSubmit.prop('disabled') === true) return;

                $btnSubmit.prop('disabled', true);

                $.ajax({
                    url: encodeURI(`<?php echo HOST; ?>api.php?tutup_buku=${tahun}`),
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (resp) => {
                        $btnSubmit.prop('disabled', false);

                        if (resp.result) {
                            location.reload();
                        } else {
                            nAlert((resp.msg !== undefined)? resp.msg : `Gagal melakukan proses tutup buku`, ALERT_FAIL);
                        }
                    },
                    error: function (request, status, error) {
                        nAlert(`Gagal melakukan proses tutup buku`, ALERT_FAIL);
                        $btnSubmit.prop('disabled', false);
                    }
                });
            }

            function tutupBuku(){
                $('#modalTutupBuku').remove();

                let html = `
                    <div id="modalTutupBuku" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title" id="myModalLabel2">Tutup Buku</h4>
                                </div>
                                <div class="modal-body" style="font-size: 15px;">
                                    <p>Tutup buku adalah proses untuk memisahkan data berdasarkan tahun periode pembukuan. <b>Disarankan untuk melakukan proses ini setiap tahun pembukuan, agar sistem tidak lambat karena memproses data transaksi yang banyak.</b></p>
                                    <p><b>Tahapan tutup buku adalah :</b></p>
                                    <ol>
                                        <li>Menyalin data saat ini, ke tahun terbaru.</li>
                                        <li>Meringkas data untuk tahun terbaru.</li>
                                    </ol>
                                    <p><b>Data yang diringkas adalah :</b></p>
                                    <ol>
                                        <li>Semua hutang pembelian & pelanggan yang lunas akan dihapus.</li>
                                        <li>Semua admin nonaktif, yang tidak terikat dengan pelanggan yang masih hutang, akan dihapus.</li>
                                        <li>Semua barang masuk & keluar akan dijumlahkan berdasarkan produknya.</li>
                                        <li>Semua antrian printer akan dihapus.</li>
                                    </ol>

                                    <p class="txt_yellow">Peringatan : Data setelah tutup buku tidak dapat dirubah, tetapi masih bisa diakses.</p>
                                    <hr/>
                                    <form id="TutupBuku" data-parsley-validate>
                                        <div class="form-group">
                                            <label for="tahunTerbaru" class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Terbaru</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12"><input id="tahunTerbaru" type="text" placeholder="Tahun" data-parsley-type="digits" data-parsley-trigger="keyup" data-parsley-type-message="Input harus angka yang valid" data-parsley-validation-threshold="0" required/></div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 30px">Lakukan Tutup Buku</button>
                                    </form>
                                </div>
                                <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>
                `;
                $('#panelContainer').append(html);

                (new Panel()).init_parsley('TutupBuku');

                $('#TutupBuku').parsley().on('form:submit', function (formInstance) {
                    prosesTutupBuku();

                    return false;
                });

                showModal('modalTutupBuku');
            }

            function deleteDb(name){
                if(confirm(`Apakah anda ingin menghapus data ${name} ?\n\nPeringatan: Data yg dihapus tidak dapat dikembalikan lagi.`)){
                    window.location = `<?php echo HOST; ?>dbs.php?deleteDB=${name}`;
                }
            }

            <?php } ?>

            function changeDb(name){
                window.location = `<?php echo HOST; ?>dbs.php?DBYEAR=${name}`;
            }

            function initPanel(){
                PanelDbs = $('#tabel_dbs').DataTable({
                    data: <?php echo json_encode(getListDbs($isSuperAdmin)); ?>,
                    order: [[0, 'desc']],
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
            }

            $(document).ready( function () {
                initPanel();
            } );
        </script>
        <?php
    },
);