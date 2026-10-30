<?php
include_once 'config/var.php';

function safeGet($arr, $key){
    if($arr instanceof stdClass){
        return (property_exists($arr, $key))
            ? $arr->$key
            : null;
    }

    return (array_key_exists($key, $arr))
        ? $arr[$key]
        : null;
}


//client info handler
if(isset($_GET['user_info'])){
    $p = json_decode($_GET['user_info']);

    if(isset($p->logo)){
        header('Content-Type: image/gif');
        echo (new DBUserInfo())->getLogo($p->logo);

        return;
    }
    else if(isset($p->action)){
        switch ($p->action){
            case 'getBlockStatus':{
                include_once ROOTDIR . 'class/WebBlocker.php';
                echo json_encode(WebBlocker::readConfig());

                break;
            }
            case 'setLeftMenu':{
                $_SESSION['SHOW_LEFT_MENU'] = $p->value;
                echo json_encode(array( 'result' => true ));

                break;
            }
        }
    }
}

$isPrinterServerAccess = isset($_GET['token']) && $_GET['token'] === TOKEN;
if($isPrinterServerAccess){
    $p = json_decode($_GET['antrian_printer']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBAntrianPrinter.php';

        switch ($p->action){
            case 'consume':{
                echo json_encode((new DBAntrianPrinter())->consume());

                break;
            }

            case 'delete':{
                echo json_encode(array(
                    'result' => (new DBAntrianPrinter())->delete(
                        $_POST['id'],
                    )
                ));
                break;
            }
        }
        return;
    }

    return;
}

//from here, data is restricted, need access to retrieve data
if (!isset($_SESSION['user_id'])) return;

include_once ROOTDIR . 'class/DBAkun.php';
$akun = (new DBAkun(true))->get($_SESSION['user_id']);
$_SESSION['akun'] = $akun;

//only active account can through
if($akun == null || !$akun->isActive) return;

//only current year DB can be CUD
foreach ($_GET as $G){
    //find action request (insert, update, delete)
    if(strpos($G, 'insert') !== false || strpos($G, 'update') !== false || strpos($G, 'delete') !== false){
        //check if current year DB
        if((int) $_SESSION['DBYEAR'] != (int) $GLOBALS['dbList'][0]) {
            echo json_encode(array(
                'result' => false,
                'msg' => 'Data yang sudah tutup buku, tidak dapat diubah',
            ));
            return;
        }

        //DB is current year
        break;
    }
}

if(isset($_GET['user_info'])){
    $p = json_decode($_GET['user_info']);

    if(isset($p->action)){
        switch ($p->action){
            case 'update':{
                //hanya boleh edit jika akun adalah superadmin
                if($akun->level_akses != 0) return;

                echo json_encode(array(
                    'result' => (new DBUserInfo())->update(
                        1,
                        safeGet($_POST, 'nama'),
                        safeGet($_POST, 'alamat'),
                        safeGet($_POST, 'info1'),
                        safeGet($_POST, 'info2'),
                        (array_key_exists('logo', $_FILES))
                            ? file_get_contents($_FILES['logo']['tmp_name'])
                            : null,
                        safeGet($_POST, 'deadLineHutangPbDays'),
                        safeGet($_POST, 'deadLineHutangPjDays'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['v_beranda'])){
    $p = json_decode($_GET['v_beranda']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBVBeranda.php';

        switch ($p->action){
            case 'get':{
                echo json_encode((new DBVBeranda())->get());

                break;
            }
        }
        return;
    }
}

if(isset($_GET['akun'])){
    $p = json_decode($_GET['akun']);

    if(isset($p->foto)){
        header('Content-Type: image/gif');
        $foto = (new DBAkun())->getFoto($p->foto);

        if($foto == null){
            echo file_get_contents(ROOTDIR . 'asset/default_profil.jpg');
        }
        else{
            echo $foto;
        }

        return;
    }
    else if(isset($p->isUsernameExists)){
        echo json_encode(array(
            'result' => (new DBAkun())->isUsernameExists($p->isUsernameExists)
        ));

        return;
    }
    else if(isset($p->action)){
        switch ($p->action){
            case 'insert':{
                //hanya boleh superadmin yg akses akun
                if($akun->level_akses != 0) return;

                echo json_encode(array(
                    'result' => (new DBAkun())->insert(
                        $_POST['nama'],
                        $_POST['alamat'],
                        $_POST['nohp'],
                        $_POST['username'],
                        $_POST['password'],
                        (isset($_FILES['foto']['tmp_name']) && $_FILES['foto']['tmp_name'] != '')
                            ? file_get_contents($_FILES['foto']['tmp_name'])
                            : null,
                        $_POST['level_akses'],
                    )
                ));
                break;
            }
            case 'update':{
                //hanya boleh edit data lain jika akun adalah superadmin
                if($akun->id != $_POST['id'] && $akun->level_akses != 0) return;

                //hanya boleh superadmin merubah levelakses
                if(isset($_POST['level_akses']) && $akun->level_akses != 0) return;

                echo json_encode(array(
                    'result' => (new DBAkun())->update(
                        $_POST['id'],
                        safeGet($_POST, 'nama'),
                        safeGet($_POST, 'alamat'),
                        safeGet($_POST, 'nohp'),
                        safeGet($_POST, 'username'),
                        safeGet($_POST, 'password'),
                        (array_key_exists('foto', $_FILES))
                            ? file_get_contents($_FILES['foto']['tmp_name'])
                            : null,
                        safeGet($_POST, 'level_akses'),
                        safeGet($_POST, 'isActive'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['supplier'])){
    $p = json_decode($_GET['supplier']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBSupplier.php';

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBSupplier())->autoComplete($_GET['query']));

                break;
            }

            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBSupplier())->insert(
                        $_POST['nama'],
                        $_POST['alamat'],
                        $_POST['nohp'],
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBSupplier())->update(
                        $_POST['id'],
                        safeGet($_POST, 'nama'),
                        safeGet($_POST, 'alamat'),
                        safeGet($_POST, 'nohp'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['pelanggan'])){
    $p = json_decode($_GET['pelanggan']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBPelanggan.php';

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBPelanggan())->autoComplete($_GET['query']));

                break;
            }

            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBPelanggan())->insert(
                        $_POST['nama'],
                        $_POST['alamat'],
                        $_POST['kota'],
                        $_POST['nohp'],
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBPelanggan())->update(
                        $_POST['id'],
                        safeGet($_POST, 'nama'),
                        safeGet($_POST, 'alamat'),
                        safeGet($_POST, 'kota'),
                        safeGet($_POST, 'nohp'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['hutang_pembelian'])){
    $p = json_decode($_GET['hutang_pembelian']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBHutangPembelian.php';

        switch ($p->action){
            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBHutangPembelian())->insert(
                        $_POST['id_supplier'],
                        $_POST['no_faktur'],
                        $_POST['nominal'],
                        $_POST['tanggal_beli'],
                        0,
                        //null,
                        $akun->id,
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBHutangPembelian())->update(
                        $_POST['id'],
                        safeGet($_POST,'id_supplier'),
                        safeGet($_POST,'no_faktur'),
                        safeGet($_POST,'nominal'),
                        safeGet($_POST,'tanggal_beli'),
                        safeGet($_POST,'status_lunas'),
                        safeGet($_POST,'tanggal_lunas'),
                        $akun->id,
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['kategori_brg'])){
    $p = json_decode($_GET['kategori_brg']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBKategoriBrg.php';

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBKategoriBrg())->autoComplete($_GET['query']));

                break;
            }

            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBKategoriBrg())->insert(
                        $_POST['nama'],
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBKategoriBrg())->update(
                        $_POST['id'],
                        safeGet($_POST, 'nama'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['satuan_brg'])){
    $p = json_decode($_GET['satuan_brg']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBSatuanBrg.php';

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBSatuanBrg())->autoComplete($_GET['query']));

                break;
            }

            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBSatuanBrg())->insert(
                        $_POST['nama'],
                        $_POST['singkatan'],
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBSatuanBrg())->update(
                        $_POST['id'],
                        safeGet($_POST, 'nama'),
                        safeGet($_POST, 'singkatan'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['produk'])){
    $p = json_decode($_GET['produk']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBProduk.php';

        $bundle = array();
        if($p->action == 'insert' || $p->action == 'update'){
            include_once ROOTDIR . 'class/DBProdukBundle.php';

            $keys = array_keys($_POST);
            sort($keys);
            $lastMatch = null;
            $i = -1;
            foreach($keys as $k){
                if(preg_match('/^bundle_[\d]*_/', $k) == 0) continue;

                $ex = explode('_', $k, 3);
                if($ex[1] != $lastMatch){
                    $lastMatch = $ex[1];
                    $i++;
                    $bundle[$i] = array($ex[2] => $_POST[$k]);
                }
                else{
                    $bundle[$i] = array_merge($bundle[$i], array($ex[2] => $_POST[$k]));
                }
            }

            function bundleHandler($idProduk, $b): bool {
                //check id bundle
                if(array_key_exists('id', $b) && $b['id'] != ''){
                    //bisa update atau delete
                    if(
                        array_key_exists('id_satuan_brg', $b)
                        && array_key_exists('qty', $b)
                        && $b['id_satuan_brg'] == ''
                        && $b['qty'] == ''
                    ) {
                        //delete mode
                        return (new DBProdukBundle())->delete($b['id']);
                    }

                    //update mode
                    return (new DBProdukBundle())->update(
                        $b['id'],
                        $_POST['id'],
                        safeGet($b, 'id_satuan_brg'),
                        safeGet($b, 'qty'),
                    );
                }
                else{
                    //insert
                    return (new DBProdukBundle())->insert(
                        $idProduk,
                        $b['id_satuan_brg'],
                        $b['qty'],
                    );
                }
            }
        }

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBProduk())->autoComplete($_GET['query']));

                break;
            }

            case 'insert':{
                $dbFakturPJ = new DBProduk();
                $result = $dbFakturPJ->insert(
                    $_POST['nama'],
                    $_POST['id_kategori_brg'],
                    $_POST['id_satuan_brg'],
                    $_POST['modal'],
                    $_POST['disc_dari_supplier'],
                    $akun->id,
                );
                if($result && sizeof($bundle) > 0){
                    $idFakturPJ = $dbFakturPJ->db->lastInsertRowID();

                    foreach ($bundle as $b){
                        $result = bundleHandler($idFakturPJ, $b);
                        if(!$result) break;
                    }
                }

                echo json_encode(array(
                    'result' => $result
                ));
                break;
            }
            case 'update':{
                $result = (new DBProduk())->update(
                    $_POST['id'],
                    safeGet($_POST, 'nama'),
                    safeGet($_POST, 'id_kategori_brg'),
                    safeGet($_POST, 'id_satuan_brg'),
                    safeGet($_POST, 'modal'),
                    safeGet($_POST, 'disc_dari_supplier'),
                    $akun->id,
                );
                if($result && sizeof($bundle) > 0){
                    $dbPB = new DBProdukBundle();
                    foreach ($bundle as $b){
                        $result = bundleHandler($_POST['id'], $b);
                        if(!$result) break;
                    }
                }

                echo json_encode(array(
                    'result' => $result
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['brg_masuk'])){
    $p = json_decode($_GET['brg_masuk']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBBarangMasuk.php';

        switch ($p->action){
//            case 'autocomplete':{
//                echo json_encode((new DBVBarangMasuk())->autoComplete($_GET['query']));
//
//                break;
//            }

            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBBarangMasuk())->insert(
                        $_POST['tanggal'],
                        $_POST['id_produk'],
                        $_POST['qty'],
                        $_POST['id_satuan_brg'],
                        $akun->id,
                    )
                ));
                break;
            }
            case 'update':{
                echo json_encode(array(
                    'result' => (new DBBarangMasuk())->update(
                        $_POST['id'],
                        safeGet($_POST, 'tanggal'),
                        safeGet($_POST, 'id_produk'),
                        safeGet($_POST, 'qty'),
                        safeGet($_POST, 'id_satuan_brg'),
                        safeGet($_POST, 'id_akun_pj'),
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['v_produk_bundle'])){
    $p = json_decode($_GET['v_produk_bundle']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBVProdukBundle.php';

        switch ($p->action){
            case 'autocomplete':{
                echo json_encode((new DBVProdukBundle())->autoComplete($_GET['query'], safeGet($p, 'id_produk')));

                break;
            }
        }
        return;
    }
}

if(isset($_GET['faktur_penjualan'])){
    $p = json_decode($_GET['faktur_penjualan']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBFakturPenjualan.php';

        $bundle = array();
        $param = array();
        $fSubTotal = 0;
        $fTotal = 0;
        $fSisa = 0;

        if(isset($_POST['id']) && $_POST['id'] != ''){
            $faktur = (new DBFakturPenjualan())->get($_POST['id']);
            $fPpn = (int) ($_POST['persen_ppn'] ?? $faktur->persen_ppn);
            $fBayar = (int) ($_POST['bayar'] ?? $faktur->bayar);
        }
        else{
            $fPpn = (int) $_POST['persen_ppn'];
            $fBayar = (int) $_POST['bayar'];
        }

        if($p->action == 'insert' || $p->action == 'update'){
            include_once ROOTDIR . 'class/DBBarangKeluar.php';
            include_once ROOTDIR . 'class/DBVProduk.php';
            include_once ROOTDIR . 'class/DBVProdukBundle.php';

            $keys = array_keys($_POST);
            sort($keys);
            $lastMatch = null;
            $i = -1;
            foreach($keys as $k){
                if(preg_match('/^bundle_[\d]*_/', $k) == 0) continue;

                $ex = explode('_', $k, 3);
                if($ex[1] != $lastMatch){
                    $lastMatch = $ex[1];
                    $i++;
                    $bundle[$i] = array($ex[2] => $_POST[$k]);
                }
                else{
                    $bundle[$i] = array_merge($bundle[$i], array($ex[2] => $_POST[$k]));
                }
            }

            function bundleHandler($idFakturPenjualan, $p): bool {
                [
                    $idBrgKeluar,
                    $idProduk,
                    $supplierDiscStr,
                    $rawQty, //$b['qty'],
                    $idRawSQty, //$b['id_satuan_brg_qty'],
                    $modal, //$b['modal'],
                    $hargaSatuan, //$b['harga_satuan'],
                    $disc,
                    $bonusQty,
                    $idSatuanBrgBonusQty,
                    $total,
                    $untungBersih,
                ] = $p;

                if($idBrgKeluar != null){
                    //bisa update atau delete
                    if($idProduk == null) {
                        //delete mode
                        return (new DBBarangKeluar())->delete($idBrgKeluar);
                    }

                    //update mode
                    return (new DBBarangKeluar())->update(
                        $idBrgKeluar,
                        $idFakturPenjualan,
                        $idProduk,
                        $supplierDiscStr,
                        $rawQty, //$b['qty'],
                        $idRawSQty, //$b['id_satuan_brg_qty'],
                        $modal, //$b['modal'],
                        $hargaSatuan, //$b['harga_satuan'],
                        $disc,
                        $bonusQty,
                        $idSatuanBrgBonusQty,
                        $total,
                        $untungBersih,
                    );
                }
                else{
                    //insert
                    return (new DBBarangKeluar())->insert(
                        $idFakturPenjualan,
                        $idProduk,
                        $supplierDiscStr,
                        $rawQty, //$b['qty'],
                        $idRawSQty, //$b['id_satuan_brg_qty'],
                        $modal, //$b['modal'],
                        $hargaSatuan, //$b['harga_satuan'],
                        $disc,
                        $bonusQty,
                        $idSatuanBrgBonusQty,
                        $total,
                        $untungBersih,
                    );
                }
            }

            function calcActualDisc(string $disc): ?float {
                if(strpos($disc, '+') === false) return null;

                $calc = function ($d1, $d2) { return $d1 + $d2 - ($d1 * $d2 / 100); };
                $dList = explode('+', $disc);
                $aDisc = ($calc)((float)(str_replace(',','.', $dList[0])), (float)(str_replace(',','.', $dList[1])));
                for($i=2; $i<sizeof($dList); $i++){
                    $aDisc = ($calc)($aDisc, (float)(str_replace(',','.', $dList[$i])));
                }

                return (float)sprintf("%.8f", $aDisc);
            }

            function buildParam($b): ?array {
                $idProduk = null;
                $supplierDiscStr = null;
                $rawQty = null;
                $idRawSQty = null;
                $modal = null;
                $hargaSatuan = null;
                $disc = null;
                $total = null;
                $untungBersih = null;

                $idBrgKeluar = (array_key_exists('id', $b) && $b['id'] != '')? (int) $b['id'] : null;

                if(!(isset($b['id_produk']) && $b['id_produk'] === '')){
                    //mode insert/update
                    if(isset($b['id']) && $b['id'] !== ''){
                        $bKeluar = (new DBBarangKeluar())->get($b['id']);
                    }

                    //check id bundle
                    $p = (new DBVProduk())->get($b['id_produk'] ?? $bKeluar->id_produk);
                    if($p == null) return null;

                    $idProduk = $p->id;

                    $rawQty = (int) ($b['qty'] ?? $bKeluar->qty);
                    $idRawSQty = (int) ($b['id_satuan_brg_qty'] ?? $bKeluar->id_satuan_brg_qty);

                    $finalQty = $rawQty;
                    $pbs = json_decode($p->produk_bundle);
                    /** @var ProdukBundle $pb */
                    foreach ($pbs as $pb){
                        if($pb->id_satuan_brg == $idRawSQty){
                            $finalQty = $rawQty * ((int)$pb->qty);
                            break;
                        }
                    }

                    $modal = (int) $p->modal;
                    $hargaSatuan = (int) ($b['harga_satuan'] ?? $bKeluar->harga_satuan);

                    $totalTanpaDisc = $hargaSatuan * $finalQty;
                    $total = $totalTanpaDisc;

                    $disc = (string) ($b['persen_disc'] ?? $bKeluar->persen_disc);

                    $discList = explode('+', $disc);
                    foreach ($discList as $d){
                        $d = (float) str_replace(',', '.', $d);

                        $total = $total - (($total * $d)/100);
                    }
                    $total = (int) $total;

                    $untungBersih = 0;
                    if($idBrgKeluar != null)
                        //update
                        $supplierDiscStr = (string) ($bKeluar->disc_dari_supplier ?? '');
                    else
                        //insert
                        $supplierDiscStr = (string) ($p->disc_dari_supplier ?? '');

                    if($supplierDiscStr !== ''){
                        $supplierDisc = calcActualDisc($supplierDiscStr) ?? ((float)str_replace(',','.', $supplierDiscStr));
                        $customerDisc = calcActualDisc($disc) ?? ((float)str_replace(',','.', $disc));

                        $profit = (float)(sprintf("%.8f", ($supplierDisc - $customerDisc)));
                        $untungBersih = (int)(($totalTanpaDisc*$profit)/100);
                    }
                    else{
                        $totalModal = $modal * $finalQty;
                        $untungBersih = $total - $totalModal;
                    }

                    if($untungBersih < 0){
                        $namaProduk = $p->nama;
                        echo json_encode(array(
                            'result' => false,
                            'msg' => "Tidak dapat menyimpan data, karena untung bersih produk '$namaProduk' minus",
                        ));
                        exit();
                    }
                }

                return array($total, array(
                    $idBrgKeluar,
                    $idProduk,
                    $supplierDiscStr,
                    $rawQty, //$b['qty'],
                    $idRawSQty, //$b['id_satuan_brg_qty'],
                    $modal, //$b['modal'],
                    $hargaSatuan, //$b['harga_satuan'],
                    $disc,
                    (isset($b['bonus_qty'])) ? (($b['bonus_qty'] == '')? null : $b['bonus_qty']) : ($bKeluar->bonus_qty ?? null),
                    (isset($b['id_satuan_brg_bonus_qty'])) ? (($b['id_satuan_brg_bonus_qty'] == '')? null : $b['id_satuan_brg_bonus_qty']) : ($bKeluar->id_satuan_brg_bonus_qty ?? null),
                    $total,
                    $untungBersih,
                ));
            }

            foreach ($bundle as $b){
                $result = buildParam($b);
                if($result == null) return;

                [$stotal, $rp] = $result;
                array_push($param, $rp);
                $fSubTotal += $stotal;
            }

            $fTotal = (int) ($fSubTotal + (($fSubTotal * $fPpn)/100));
            $fSisa = $fBayar - $fTotal;
        }



        switch ($p->action){
            case 'insert':{
                $dbFakturPJ = new DBFakturPenjualan();
                $result = $dbFakturPJ->insert(
                    $_POST['tanggal'],
                    $_POST['kode'],
                    $_POST['id_pelanggan'],
                    $fSubTotal, //$_POST['subtotal'],
                    $fPpn, //$_POST['persen_ppn'],
                    $fTotal, //$_POST['total'],
                    $fBayar, //$_POST['bayar'],
                    $fSisa ,//$_POST['sisa'],
                    $akun->id,
                );
                if($result && sizeof($param) > 0){
                    $idFakturPJ = $dbFakturPJ->db->lastInsertRowID();

                    foreach ($param as $rp){
                        $result = bundleHandler($idFakturPJ, $rp);
                        if(!$result) break;
                    }
                }

                echo json_encode(array(
                    'result' => $result
                ));
                break;
            }
            case 'update':{
                $idFakturPJ = $_POST['id'];

                $result = (new DBFakturPenjualan())->update(
                    $idFakturPJ,
                    safeGet($_POST, 'tanggal'),
                    null, //$_POST['kode'],
                    safeGet($_POST, 'id_pelanggan'),
                    $fSubTotal, //$_POST['subtotal'],
                    $fPpn, //$_POST['persen_ppn'],
                    $fTotal, //$_POST['total'],
                    $fBayar, //$_POST['bayar'],
                    $fSisa ,//$_POST['sisa'],
                    $akun->id,
                );
                if($result && sizeof($param) > 0){
                    foreach ($param as $rp){
                        $result = bundleHandler($idFakturPJ, $rp);
                        if(!$result) break;
                    }
                }

                echo json_encode(array(
                    'result' => $result
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['antrian_printer'])){
    $p = json_decode($_GET['antrian_printer']);

    if(isset($p->action)){
        include_once ROOTDIR . 'class/DBAntrianPrinter.php';

        switch ($p->action){
            case 'insert':{
                echo json_encode(array(
                    'result' => (new DBAntrianPrinter())->insert(
                        $_POST['tanggal'],
                        $_POST['status'],
                        $_POST['template'],
                        $_POST['id_faktur_penjualan'],
                    )
                ));
                break;
            }

            case 'update':{
                echo json_encode(array(
                    'result' => (new DBAntrianPrinter())->update(
                        $_POST['id'],
                        safeGet($_POST, 'tanggal'),
                        safeGet($_POST, 'status'),
                        safeGet($_POST, 'template'),
                        safeGet($_POST, 'id_faktur_penjualan'),
                    )
                ));
                break;
            }

            case 'delete':{
                echo json_encode(array(
                    'result' => (new DBAntrianPrinter())->delete(
                        $_POST['id'],
                    )
                ));
                break;
            }
        }
        return;
    }
}

if(isset($_GET['datatables'])){
    /*
     * DataTables example server-side processing script.
     *
     * Please note that this script is intentionally extremely simple to show how
     * server-side processing can be implemented, and probably shouldn't be used as
     * the basis for a large complex system. It is suitable for simple use cases as
     * for learning.
     *
     * See http://datatables.net/usage/server-side for full details on the server-
     * side processing requirements of DataTables.
     *
     * @license MIT - http://datatables.net/license_mit
     */

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */

    // DB table to use
    $table = $_GET['datatables'];

    // Table's primary key
    $primaryKey = 'id';

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array();
    switch ($_GET['datatables']){
        case 'akun':{
            //hanya boleh superadmin yg akses
            if($akun->level_akses != 0) return;

            include_once ROOTDIR . 'class/DBAkun.php';
            [$primaryKey, $columns] = Akun::dataTables();
            break;
        }
        case 'supplier':{
            include_once ROOTDIR . 'class/DBSupplier.php';
            [$primaryKey, $columns] = Supplier::dataTables();
            break;
        }
        case 'pelanggan':{
            include_once ROOTDIR . 'class/DBPelanggan.php';
            [$primaryKey, $columns] = Pelanggan::dataTables();
            break;
        }
        case 'v_hutang_pembelian':{
            include_once ROOTDIR . 'class/DBVHutangPembelian.php';
            [$primaryKey, $columns] = VHutangPembelian::dataTables();
            break;
        }
        case 'v_produk':{
            include_once ROOTDIR . 'class/DBVProduk.php';
            [$primaryKey, $columns] = VProduk::dataTables();
            break;
        }
        case 'kategori_brg':{
            include_once ROOTDIR . 'class/DBKategoriBrg.php';
            [$primaryKey, $columns] = KategoriBrg::dataTables();
            break;
        }
        case 'satuan_brg':{
            include_once ROOTDIR . 'class/DBSatuanBrg.php';
            [$primaryKey, $columns] = SatuanBrg::dataTables();
            break;
        }
        case 'v_brg_masuk':{
            include_once ROOTDIR . 'class/DBVBarangMasuk.php';
            [$primaryKey, $columns] = VBarangMasuk::dataTables();
            break;
        }
        case 'v_brg_keluar':{
            include_once ROOTDIR . 'class/DBVBarangKeluar.php';
            [$primaryKey, $columns] = VBarangKeluar::dataTables();
            break;
        }
        case 'v_faktur_penjualan':{
            include_once ROOTDIR . 'class/DBVFakturPenjualan.php';
            [$primaryKey, $columns] = VFakturPenjualan::dataTables();
            break;
        }
        case 'v_hutang_pelanggan':{
            include_once ROOTDIR . 'class/DBVHutangPelanggan.php';
            [$primaryKey, $columns] = VHutangPelanggan::dataTables();
            break;
        }
        case 'v_sisa_stok':{
            include_once ROOTDIR . 'class/DBVSisaStok.php';
            [$primaryKey, $columns] = VSisaStok::dataTables();
            break;
        }
        case 'v_antrian_printer':{
            include_once ROOTDIR . 'class/DBVAntrianPrinter.php';
            [$primaryKey, $columns] = VAntrianPrinter::dataTables();
            break;
        }
        case 'v_pendapatan_harian':{
            include_once ROOTDIR . 'class/DBVPendapatanHarian.php';
            [$primaryKey, $columns] = VPendapatanHarian::dataTables();
            break;
        }
        case 'v_pendapatan_bulanan':{
            include_once ROOTDIR . 'class/DBVPendapatanBulanan.php';
            [$primaryKey, $columns] = VPendapatanBulanan::dataTables();
            break;
        }
        case 'v_top_pelanggan':{
            include_once ROOTDIR . 'class/DBVTopPelanggan.php';
            [$primaryKey, $columns] = VTopPelanggan::dataTables();
            break;
        }
        case 'v_top_kategori':{
            include_once ROOTDIR . 'class/DBVTopKategori.php';
            [$primaryKey, $columns] = VTopKategori::dataTables();
            break;
        }
        default: return;
    }

    // SQL server connection information
    $sql_details = array(
        'user' => '',
        'pass' => '',
        'dbpath' => DBDIR . $_SESSION['DBYEAR'],
    );


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP
     * server-side, there is no need to edit below this line.
     */

    include_once ROOTDIR . 'class/SSP.php';

    $customWhere = new class implements SSPCustomWhere{
        public function __invoke(array &$request, array $columns, array &$bindings): string{
            $where = '';
            if(
                array_key_exists('fdbFieldName', $_POST)
                && $_POST['fStartDate'] != null
                && $_POST['fEndDate'] != null
            ){
                $binding = SSP::bind( $bindings, $_POST['fStartDate'], (is_numeric($_POST['fStartDate'])) ? PDO::PARAM_INT : PDO::PARAM_STR);
                $where = "`" . $_POST['fdbFieldName'] . "` >= $binding";

                $binding = SSP::bind( $bindings, $_POST['fEndDate'], (is_numeric($_POST['fEndDate'])) ? PDO::PARAM_INT : PDO::PARAM_STR);
                $where = $where . " AND `".$_POST['fdbFieldName']."` <= $binding";
            }
            else if($_GET['datatables'] === 'v_brg_keluar' && isset($_GET['id_faktur'])){
                $binding = SSP::bind( $bindings, $_GET['id_faktur'], PDO::PARAM_INT );
                $where = "`id_faktur_penjualan` = $binding";
                $request['order'] = array(
                    array('column' => 1, 'dir' => 'asc')
                );
                $request['columns'] = array(
                    array('orderable'=> 'false', 'data' => 0),
                    array('orderable'=> 'true', 'data' => 1),
                );
            }
            else if(
                (
                    $_GET['datatables'] === 'v_produk'
                    || $_GET['datatables'] === 'v_faktur_penjualan'
                )
                && isset($_GET['id'])
            ){
                $binding = SSP::bind( $bindings, $_GET['id'], PDO::PARAM_INT );
                $where = "`id` = $binding";
            }

            return $where;
        }
    };

    echo json_encode(
        SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $customWhere)
    );
}


if(isset($_GET['tutup_buku'])){
    //hanya superadmin boleh akses
    if($akun->level_akses != 0) return;


    $tahunBaru = $_GET['tutup_buku'];

    //set script time limit
    set_time_limit(0);
    ignore_user_abort(true);

    include_once ROOTDIR . 'class/WebBlocker.php';
    $blockStat = new BlockerStatus(
        true,
        'Situs dalam proses Tutup Buku',
        'Mohon tunggu hingga proses selesai',
        '0%'
    );

    WebBlocker::writeConfig($blockStat);

    $resp = json_encode(array( 'result' => true ));
    $len = strlen($resp);

    session_destroy();
    header("Connection: close\r\n");
    header("Content-Encoding: none\r\n");
    header('Content-Type: application/json; charset=utf-8');
    header("Content-Length: $len");

    echo $resp;

    ob_end_flush();
    @ob_flush();
    flush();

    sleep(10);


    $currDB = (int) ($GLOBALS['dbList'][0]);
    include_once ROOTDIR . 'class/TutupBuku.php';
    $TB = new TutupBuku();
    $TB->start(
        $currDB,
        $tahunBaru,
        function ($title, $msg, $progress) use ($blockStat){
            $blockStat->title = $title;
            $blockStat->msg = $msg;
            $blockStat->progress = $progress;
            WebBlocker::writeConfig($blockStat);
        }
    );




    WebBlocker::writeConfig(new BlockerStatus(false));
    exit();
}














