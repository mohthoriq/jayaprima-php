<?php
include_once 'function/init.php';
include_once ROOTDIR . 'template/cpanel.php';


function sizeReformat($bytes, $decimals = 2): string {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    /** @noinspection PhpIllegalStringOffsetInspection */
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor] . 'B';
}


$sInfo = new stdClass();

$free= disk_free_space(ROOTDIR);
$storage = disk_total_space(ROOTDIR);
$used = $storage - $free;

$sInfo->labelTotal = sizeReformat($storage);
$sInfo->labelUsed = sizeReformat($used);
$sInfo->labelFree = sizeReformat($free);

$sInfo->percentUsed = round(($used / $storage) * 100,2);
$sInfo->percentFree = round(($free / $storage) * 100,2);

//kecil dari 100MB
$sInfo->isStorageLowSpace = ($free/1_048_576) < 100;


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
            .tile_count .tile_stats_count:before{
                margin-top: 0;
            }
            .tile_count .tile_stats_count{
                padding: 10px 10px 0 20px;
            }
            th{
                text-align: center;
                vertical-align: middle !important;
            }
            #tabel_pendapatan_bln {
                font-size: 13px;
            }
            #tabel_pendapatan_bln th,
            #tabel_pendapatan_bln td{
                padding-top: 5px;
                padding-bottom: 5px;
            }
            #table_sum_p_bln th, #table_sum_p_bln td{
                padding-top: 10px;
                padding-bottom: 10px;
            }
            #table_sum_p_bln td{
                padding-left: 20px;
                font-size: 20px;
                font-weight: bold;
                text-align: end;
            }
            #table_sum_p_bln th{
                text-align: start;
            }
            #table_sum_p_bln tr:nth-child(2){
                color: #d58512;
            }
            #table_sum_p_bln tr:nth-child(3){
                color: #26B99A;
            }

            .server-info{
                font-size: 16px;
                font-weight: bold;
            }
        </style>
        <?php
    },
    function () use ($sInfo){
        ?>

        <div id="panelContainer" class="">
            <div class="page-title">
                <div class="x_title">
                    <h3 style="padding-bottom: unset; margin-bottom: unset;"><b>Total</b></h3>
                </div>

                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-cube"></i> Hutang Pembelian</span>
                        <div class="count" id="count_hutang_pembelian">0</div>
                        <span class="count_bottom"></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Hutang Pelanggan</span>
                        <div class="count" id="count_hutang_pelanggan">0</div>
                        <span class="count_bottom"></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-industry"></i> Supplier</span>
                        <div class="count" id="count_supplier">0</div>
                        <span class="count_bottom"></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-users"></i> Pelanggan</span>
                        <div class="count" id="count_pelanggan">0</div>
                        <span class="count_bottom"></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-cubes"></i> Produk</span>
                        <div class="count green" id="count_produk">0</div>
                        <span class="count_bottom"></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-sitemap"></i> Admin Aktif</span>
                        <div class="count" id="count_admin_aktif">0</div>
                        <span class="count_bottom"></span>
                    </div>
                </div>

                <div class="row x_title"></div>
            </div>
            <div class="clearfix"></div>
            <br/><br/><br/>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="dashboard_graph">

                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Grafik Pendapatan Bulanan</h3>
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe>
                            <canvas id="lineChart" width="460" height="230" style="width: 460px; height: 230px;"></canvas>
                            <br/><br/>
                            <label for="sumTarget">Jumlahkan semua: </label>
                            <select name="sumTarget" id="sumTarget" style="font-weight: bold">
                                <option class="red" value="phutang">Hutang Pelanggan</option>
                                <option class="green" value="kas" selected="selected">Kas</option>
                                <option class="blue" value="total">Total</option>
                            </select>
                            <table id="table_sum_p_bln">
                                <tr>
                                    <th>Kotor</th>
                                    <td id="totalPKotor">0</td>
                                </tr>
                                <tr>
                                    <th>Modal</th>
                                    <td id="totalModal">0</td>
                                </tr>
                                <tr>
                                    <th>Bersih</th>
                                    <td id="totalPBersih">0</td>
                                </tr>
                            </table>
                            <div id="sumMsg" style="margin-top: 15px; margin-bottom: 40px;"></div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12 bg-white">
                            <table id="tabel_pendapatan_bln" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th data-toggle="tooltip" data-placement="top" data-original-title="Total pendapatan kotor atau uang masuk, sebelum ditambah Pajak Pertambahan Nilai">Kotor<br/>(Tanpa PPN)</th>
                                    <th data-toggle="tooltip" data-placement="top" data-original-title="Total modal dari pendapatan kotor">Modal</th>
                                    <th data-toggle="tooltip" data-placement="top" data-original-title="Total pendapatan bersih dari pendapatan kotor">Bersih</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <br/><br/>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel panel_color2">
                        <div class="x_title" style="height: auto;">
                            <h2>Kategori Teratas</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: none;">
                            <table id="tabel_kategori_top" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel panel_color1">
                        <div class="x_title" style="height: auto;">
                            <h2>Pelanggan Teratas</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: none;">
                            <table id="tabel_pelanggan_top" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Total Belanja</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="x_panel panel_color4">
                    <div class="x_title">
                        <h2>Pendapatan Harian</h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table id="tabel_pendapatan_hrn" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th data-toggle="tooltip" data-placement="top" data-original-title="Total pendapatan kotor atau uang masuk, sebelum ditambah Pajak Pertambahan Nilai">Kotor<br/>(Tanpa PPN)</th>
                                <th data-toggle="tooltip" data-placement="top" data-original-title="Total modal dari pendapatan kotor">Modal</th>
                                <th data-toggle="tooltip" data-placement="top" data-original-title="Total pendapatan bersih dari pendapatan kotor">Bersih</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <?php if($sInfo != null) {?>
            <div class="row">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Info Penyimpanan</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content server-info">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe>
                            <canvas id="serverInfo" width="460" height="230" style="width: 460px; height: 230px;"></canvas>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <br/>
                            <div>Total : <span id="storage_size"><?php echo $sInfo->labelTotal ?></span></div>
                            <div style="color: #337AB7;">Digunakan : <span id="storage_used"><?php echo $sInfo->labelUsed ?></span></div>
                            <div id="storage_free" style="color: #26B99A;">Tersisa : <?php echo $sInfo->labelFree ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php
    },
    function () use ($sInfo) {
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

        <script src="<?php echo HOST?>asset/vendors/Chart.js/dist/Chart.min.js"></script>

        <script type="application/javascript">
            let grafikPdptn = null;

            let PanelPdptnBulanan;
            let PanelPdptnHarian;
            let PanelTopPl;
            let PanelTopKtgr;

            function initBerandaVal(){
                $.ajax({
                    url: encodeURI(`<?php echo HOST?>api.php?v_beranda=${JSON.stringify({
                        action: 'get',
                    })}`),
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (resp) => {
                        if (resp) {
                            let $hPb = $('#count_hutang_pembelian');
                            $hPb.html(resp['hutang_pembelian'].toLocaleString('id-ID'));
                            $hPb.addClass(
                                (resp['hutang_pembelian'] > 0)? 'red' : 'green'
                            );

                            let $hPl = $('#count_hutang_pelanggan');
                            $hPl.html(resp['hutang_pelanggan'].toLocaleString('id-ID'));
                            $hPl.addClass(
                                (resp['hutang_pelanggan'] > 0)? 'red' : 'green'
                            );

                            $('#count_supplier').html(resp['supplier'].toLocaleString('id-ID'));
                            $('#count_pelanggan').html(resp['pelanggan'].toLocaleString('id-ID'));
                            $('#count_produk').html(resp['produk'].toLocaleString('id-ID'));
                            $('#count_admin_aktif').html(resp['admin_aktif'].toLocaleString('id-ID'));
                        } else {
                            nAlert((resp.msg !== undefined)? resp.msg : `Gagal mendapatkan data beranda`, ALERT_FAIL);
                        }
                    }
                });
            }

            function reformDate(date){
                let d = new Date(date);
                return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            }

            function initPanel(){
                PanelPdptnBulanan = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_pendapatan_bln',
                    dataTablesSchema: 'v_pendapatan_bulanan',
                    actionEditClass: '-----',
                    DataTableOpts: {
                        targets: 'no-sort',
                        bSort: false,
                        order: [],
                        //paging: false,
                        pageLength: 12,
                        lengthChange: false,
                        searching: false,
                        info: false,
                        drawCallback: function(settings){
                            let api = this.api();
                            let data = api.rows( {page:'current'} ).data();

                            let label = [];

                            let pHutangKotor = [];
                            let pHutangBersih = [];

                            let pKasKotor = [];
                            let pKasBersih = [];

                            let pTotalKotor = [];
                            let pTotalBersih = [];

                            let $sumTarget = $('#sumTarget');
                            let $sumMsg = $('#sumMsg');
                            let outId = ['#totalPKotor', '#totalModal', '#totalPBersih'];
                            let sumHutangPelanggan = [0,0,0];
                            let sumKas = [0,0,0];
                            let sumTotal = [0,0,0];
                            let applySumToOut = (sum, kotor, bersih) => {
                                for(let i=0; i<outId.length; i++) $(outId[i]).html(sum[i].toLocaleString('id-ID'));

                                if(grafikPdptn === null) grafikPdptn = new Chart(document.getElementById('lineChart'), {
                                    type: 'line',
                                    data: {
                                        labels: label,
                                        datasets: [{
                                            label: "Bersih",
                                            backgroundColor: "rgba(38, 185, 154, 0.51)",
                                            borderColor: "rgba(38, 185, 154, 0.7)",
                                            pointBorderColor: "rgba(38, 185, 154, 0.7)",
                                            pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                                            pointHoverBackgroundColor: "#fff",
                                            pointHoverBorderColor: "rgba(220,220,220,1)",
                                            pointBorderWidth: 1,
                                            data: bersih
                                        }, {
                                            label: "Kotor",
                                            backgroundColor: "rgba(3, 88, 106, 0.3)",
                                            borderColor: "rgba(3, 88, 106, 0.70)",
                                            pointBorderColor: "rgba(3, 88, 106, 0.70)",
                                            pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
                                            pointHoverBackgroundColor: "#fff",
                                            pointHoverBorderColor: "rgba(151,187,205,1)",
                                            pointBorderWidth: 1,
                                            data: kotor
                                        }]
                                    },
                                });
                                else {
                                    grafikPdptn.data.datasets[0].data = bersih;
                                    grafikPdptn.data.datasets[1].data = kotor;
                                    grafikPdptn.update();
                                }
                            };

                            for(let i=0; i<data.length; i++) {
                                let v = data[i];
                                label.push(v[0]);

                                for(let j=0; j<v.length-1; j++) {
                                    let nominal = parseInt(v[j + 1]);
                                    switch (j+1){
                                        case 4:
                                            sumHutangPelanggan[0] += nominal;
                                            pHutangKotor.push(nominal);
                                            break;
                                        case 5:
                                            sumHutangPelanggan[1] += nominal;
                                            break;
                                        case 6:
                                            sumHutangPelanggan[2] += nominal;
                                            pHutangBersih.push(nominal);
                                            break;

                                        case 7:
                                            sumKas[0] += nominal;
                                            pKasKotor.push(nominal);
                                            break;
                                        case 8:
                                            sumKas[1] += nominal;
                                            break;
                                        case 9:
                                            sumKas[2] += nominal;
                                            pKasBersih.push(nominal);
                                            break;
                                    }
                                }
                            }

                            for(let i=0; i<outId.length; i++) sumTotal[i] = sumHutangPelanggan[i] + sumKas[i];
                            for(let i=0; i<label.length; i++) {
                                pTotalKotor[i] = pHutangKotor[i] + pKasKotor[i];
                                pTotalBersih[i] = pHutangBersih[i] + pKasBersih[i];
                            }

                            $sumTarget.off('change');
                            $sumTarget.on('change', ()=>{
                                let sum = null;
                                let dKotor = null;
                                let dBersih = null;

                                switch ($sumTarget.find(":selected").val()) {
                                    case 'phutang':
                                        $sumTarget.addClass('red');
                                        $sumTarget.removeClass('green');
                                        $sumTarget.removeClass('blue');

                                        sum = sumHutangPelanggan;
                                        dKotor = pHutangKotor;
                                        dBersih = pHutangBersih;

                                        $sumMsg.html('<span class="red" style="font-weight: bold">Hutang Pelanggan</span> adalah Pendapatan Tertahan, yakni pendapatan yang tertahan karena Pelanggan yang berhutang');
                                        break;
                                    case 'kas':
                                        $sumTarget.removeClass('red');
                                        $sumTarget.addClass('green');
                                        $sumTarget.removeClass('blue');

                                        sum = sumKas;
                                        dKotor = pKasKotor;
                                        dBersih = pKasBersih;

                                        $sumMsg.html('<span class="green" style="font-weight: bold">Kas</span> adalah Pendapatan Aktual, yakni pendapatan yang masuk kas');
                                        break;
                                    case 'total':
                                        $sumTarget.removeClass('red');
                                        $sumTarget.removeClass('green');
                                        $sumTarget.addClass('blue');

                                        sum = sumTotal;
                                        dKotor = pTotalKotor;
                                        dBersih = pTotalBersih;

                                        $sumMsg.html('<span class="blue" style="font-weight: bold">Total</span> adalah Total Pendapatan Aktual ditambah Pendapatan Tertahan');
                                        break;
                                }
                                if(sum !== null){
                                    (applySumToOut)(sum, dKotor, dBersih);
                                }
                            });
                            $sumTarget.change();
                        }
                    },
                });

                PanelPdptnHarian = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_pendapatan_hrn',
                    dataTablesSchema: 'v_pendapatan_harian',
                    actionEditClass: '-----',
                    DataTableOpts: {
                        order: [],
                    },
                    filterDateRange: {
                        dbFieldName: 'tanggal',
                        reformGetDataCallback: function (dtParms) {
                            if(dtParms.fStartDate !== null && dtParms.fEndDate !== null) {
                                dtParms.fStartDate = reformDate(dtParms.fStartDate);
                                dtParms.fEndDate = reformDate(dtParms.fEndDate);
                            }
                        }
                    },
                });

                PanelTopPl = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_pelanggan_top',
                    dataTablesSchema: 'v_top_pelanggan',
                    actionEditClass: '-----',
                    DataTableOpts: {
                        order: [],
                        searching: false,
                    }
                });

                PanelTopKtgr = new Panel({
                    host: '<?php echo HOST?>',
                    $tableId: '#tabel_kategori_top',
                    dataTablesSchema: 'v_top_kategori',
                    actionEditClass: '-----',
                    DataTableOpts: {
                        order: [],
                        searching: false,
                    }
                });


                <?php if($sInfo != null) { ?>
                new Chart(document.getElementById('serverInfo'), {
                    type:"doughnut",
                    tooltipFillColor:"rgba(51, 51, 51, 0.55)",
                    data:{
                        labels:['Digunakan (%)','Sisa (%)'],
                        datasets:[{
                            data: <?php echo json_encode(array($sInfo->percentUsed, $sInfo->percentFree))?>,
                            backgroundColor:[
                                '#337AB7',
                                '<?php echo ($sInfo->isStorageLowSpace)? '#E74C3C' : '#26B99A'; ?>'
                            ],
                            hoverBackgroundColor:[
                                '#337AB7',
                                '<?php echo ($sInfo->isStorageLowSpace)? '#E74C3C' : '#26B99A'; ?>'
                            ],
                        }]
                    },
                    options:{
                        legend: {
                            position: 'top',
                        },
                        responsive: true,
                    },
                });

                    <?php if($sInfo->isStorageLowSpace) { ?>
                alert(`Penyimpanan kurang dari 100MB, silahkan hapus Data Terdahulu (Data Tutup Buku), atau tingkatkan kapasitas penyimpanan, agar tidak terjadi error pada sistem`);
                $('#storage_free').attr('style','color: #E74C3C');
                    <?php }?>
                <?php } ?>
            }

            $(document).ready( function () {
                initBerandaVal();
                initPanel();

                PanelPdptnHarian.init();
                PanelPdptnBulanan.init();
                PanelTopPl.init();
                PanelTopKtgr.init();
            } );
        </script>
        <?php
    },
);

?>




