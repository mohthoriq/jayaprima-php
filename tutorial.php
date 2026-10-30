<?php
include_once 'function/init.php';

header("X-Frame-Options: SAMEORIGIN");

const ADMIN_SUPER = 0;
const ADMIN = 1;

$akun = Akun::Cast($_SESSION['akun']);
$isSuperAdmin = $akun->level_akses == ADMIN_SUPER;

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
                            <h2>List Panduan Aplikasi</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/><br/>
                            <table id="tabel_tutorial" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Topik</th>
                                    <th>Judul</th>
                                    <th>Level Akses</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal_view" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="modal-label"></h4>
                        </div>
                        <div class="modal-body" style="display: flex; flex-direction: row; align-items: stretch;">
                            <iframe style="flex: 1" id="iframe_video" src="" width="640" height="480" allow="accelerometer; autoplay; encrypted-media; gyroscope;" allowfullscreen></iframe>
                        </div>
                        <div class="modal-footer"></div>
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
            let $iFrame;
            let $modalLabel;
            let modalContainerId = 'modal_view';

            function showVideoModal(topik, url){
                if($modalLabel.html() !== topik) {
                    $iFrame.attr('src', url);
                    $modalLabel.html(topik);
                }
                showModal(modalContainerId);
            }

            function lvlAksesToHtml(id) {
                let isAdminSuper = id === <?php echo ADMIN_SUPER; ?>;
                let color = (<?php echo (int)(!$isSuperAdmin); ?> && isAdminSuper)? 'txt_red' : 'txt_green';
                return (isAdminSuper)? `<p class="${color}">Admin Super</p>` : `<p class="${color}">Admin</p>` ;
            }

            function genButton(topik, url) {
                return `<button class="btn btn-success" onclick="showVideoModal('${topik}','${url}')">Lihat</button>`;
            }

            let data0 = [
                ['Profil Usaha', 'Mengganti Profil Usaha', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1AKuLvG5QIkRPW-tlB9deimALy0sLYNNM/preview'],

                ['Manajemen Akun', 'Menambah Admin Baru', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1ce2a4CLcJTkVHgxH8LZ-350My9ss-bZu/preview'],
                ['Manajemen Akun', 'Mengedit Data Admin', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1b14JedDO6-0xamHBl9Hx5rJDdserq_N4/preview'],
                ['Manajemen Akun', 'Mengaktifkan/Menonaktifkan Admin', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1TIIsqrCEpZOTYlGUHqb9JkgW8OhZZa_M/preview'],

                ['Tutup Buku', 'Penjelasan Tutup Buku', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/12qm-c74k6kI2cq8Vk6DGCIb2S3j3Z-6g/preview'],
                ['Tutup Buku', 'Melakukan Tutup Buku', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1ETYaM298hW8QExZ8xe0P2Lw5N6_YPbp0/preview'],
                ['Tutup Buku', 'Melihat Data Terdahulu', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1kndPei6VG4V_qtNUXjUnhxDphVnwe-tZ/preview'],
            ];
            let data1 = [
                ['Supplier', 'Menambah Supplier Baru', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1EfWR6CvRE5Uz8V_ml1eJjN5IyVlncjOA/preview'],
                ['Supplier', 'Mengedit Supplier', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1NsajoImsXNKAbCt7GQFf99pePayOBADW/preview'],

                ['Pelanggan', 'Menambah Pelanggan Baru', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/18qgrQwyi-IVlkHicWWeEbHGZjtdL7ewL/preview'],
                ['Pelanggan', 'Mengedit Pelanggan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1bI8RLsxGACAwllnmHSzrzKtY_UT3q4sU/preview'],

                ['Barang', 'Menambah Kategori Barang Baru', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1XBNRehB1nlVHXdaxHFtkMGbaP25HlFW-/preview'],
                ['Barang', 'Mengedit Kategori Barang', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1qQpruUTx0ALFVIpshbywEis4Aqk5axqY/preview'],
                ['Barang', 'Menambah Satuan Barang Baru', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1CSc2piUWQp8l25cT0nyfeCaNLMxF8Z1f/preview'],
                ['Barang', 'Mengedit Satuan Barang', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1WErHjVpLBA0Qm4IAfnh93sn6StKZq3kH/preview'],
                ['Barang', 'Menambah Produk Baru', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1SviDdIgfr-JAD5lEA8LsLE3c0gssCDzm/preview'],
                ['Barang', 'Mengedit Produk', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1tm24bPjyn4t3oeZ1HVdb4Nt1Lsk8s-Yk/preview'],
                ['Barang', 'Menambah Barang Masuk', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1DHpLt_3jbw4Mnr4TdbZVAtc33z2Cdl8V/preview'],
                ['Barang', 'Mengedit Barang Masuk', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1WFMLNFXMX6EVzJo616bifGArzaZCD-Oc/preview'],
                ['Barang', 'Menambah Barang Keluar / Faktur Penjualan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1zJABvR7xLzk5SeLjrSTm_l6j5BSs9_WU/preview'],
                ['Barang', 'Melihat List Barang Faktur Penjualan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1wpzFUdTzOSUImThRhjF6TdcXfUn4XeYh/preview'],
                ['Barang', 'Mengedit Faktur Penjualan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1VRiFWRZdM5z1ctM4wOjo8sZFSBt4KsKz/preview'],
                ['Barang', 'Mencetak Faktur Penjualan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1tv9LHbdf0uXAwyLyLyWEh3ZZpRUSSgUc/preview'],
                ['Barang', 'Melihat Semua List Barang Keluar', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1oKn85RWUAaU1Y2s4BYmbW4V84l7M6lOL/preview'],
                ['Barang', 'Melihat Sisa Stok', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1MfLGF_GEH_5SNaaq0phbTqMScltz3U4Z/preview'],

                ['Hutang', 'Menambah Hutang Pembelian', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/19HosUMCHXYAea-yDx9H6fhhJV5_2fIek/preview'],
                ['Hutang', 'Mengedit Hutang Pembelian', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1Ikzg0E7hFsmuN7Js0B0fFz325ZjDNQOW/preview'],
                ['Hutang', 'Set Lunas/Hutang pada Hutang Pembelian', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1G866LYNNqtwxGdYBQuB3747LbDsYDCXU/preview'],
                ['Hutang', 'Melihat dan Set Lunas pada Hutang Pelanggan', lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1WAeTjRFH0Qm8DWyUos9qsioVtv61ySbD/preview'],
                ['Hutang', 'Set Jatuh Tempo Hutang Pembelian dan Pelanggan', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>), 'https://drive.google.com/file/d/1Ld6CARyMJWYDqX__eVgkffvNhkthRct1/preview'],
            ];
            let data = [
                ['Antarmuka', 'Pengenalan Antarmuka', lvlAksesToHtml(<?php echo ADMIN_SUPER; ?>) + ' & ' + lvlAksesToHtml(<?php echo ADMIN; ?>), 'https://drive.google.com/file/d/1KbAt4tAXUBjJuQT96YWl4KRip4LHCAjR/preview'],
            ].concat((<?php echo (int)$isSuperAdmin; ?>)
                ? data0.concat(data1) : data1.concat(data0)
            );

            let PanelPanduan;

            function initPanel(){
                data.forEach(value => {
                    value[3] = genButton(value[1], value[3]);
                });

                $iFrame = $('#iframe_video');
                $modalLabel = $('#modal-label');

                $(`#${modalContainerId}`).on('hidden.bs.modal', function () {
                    $iFrame.attr('src', $iFrame.attr('src'));
                });

                PanelPanduan = $('#tabel_tutorial').DataTable({
                    data: data,
                    order: [],
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