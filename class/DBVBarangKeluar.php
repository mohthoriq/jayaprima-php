<?php

class VBarangKeluar{
    public int $id;
    public int $id_faktur_penjualan;
    public string $kode_faktur_penjualan;
    public int $tanggal_faktur_penjualan;
    public int $id_produk;
    public string $nama_produk;
    public int $id_kategori_brg;
    public string $nama_kategori_brg;
    public string $disc_dari_supplier;
    public int $modal;
    public int $raw_qty;
    public int $id_satuan_brg_raw_qty;
    public string $nama_satuan_brg_raw_qty;
    public string $singkatan_satuan_brg_raw_qty;
    public int $final_qty;
    public int $id_satuan_brg_produk;
    public string $nama_satuan_brg_produk;
    public string $singkatan_satuan_brg_produk;
    public int $harga_satuan;
    public int $harga_total;
    public int $untung_bersih;
    public string $persen_disc;
    public ?int $bonus_qty;
    public ?int $id_satuan_brg_bonus_qty;
    public ?string $nama_satuan_brg_bonus_qty;
    public ?string $singkatan_satuan_brg_bonus_qty;
    public ?int $final_bonus_qty;
    public int $id_akun_pj;
    public string $nama_akun;

    public function __construct(array $arr){
        [
            $this->id,
            $this->id_faktur_penjualan,
            $this->kode_faktur_penjualan,
            $this->tanggal_faktur_penjualan,
            $this->id_produk,
            $this->nama_produk,
            $this->id_kategori_brg,
            $this->nama_kategori_brg,
            $this->disc_dari_supplier,
            $this->modal,
            $this->raw_qty,
            $this->id_satuan_brg_raw_qty,
            $this->nama_satuan_brg_raw_qty,
            $this->singkatan_satuan_brg_raw_qty,
            $this->final_qty,
            $this->id_satuan_brg_produk,
            $this->nama_satuan_brg_produk,
            $this->singkatan_satuan_brg_produk,
            $this->harga_satuan,
            $this->harga_total,
            $this->untung_bersih,
            $this->persen_disc,
            $this->bonus_qty,
            $this->id_satuan_brg_bonus_qty,
            $this->nama_satuan_brg_bonus_qty,
            $this->singkatan_satuan_brg_bonus_qty,
            $this->final_bonus_qty,
            $this->id_akun_pj,
            $this->nama_akun,
        ] = $arr;
    }

    static public function cast($obj): VBarangKeluar {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'tanggal_faktur_penjualan', 'dt' => 0, 'formatter' => function($d, $row){
                    if($d == null) return '';

                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'nama_produk', 'dt' => 1),
                array('db' =>'raw_qty', 'dt' => 2, 'formatter' => function($d, $row){
                    $fd = number_format($d,0,'','.');
                    return "$fd ${row['nama_satuan_brg_raw_qty']}";
                }),
                array('db' =>'final_qty', 'dt' => 3, 'formatter' => function($d, $row){
                    $fd = number_format($d,0,'','.');
                    return "$fd ${row['nama_satuan_brg_produk']}";
                }),
                array('db' =>'modal', 'dt' => 4, 'formatter' => function($d, $row){
                    $fd = number_format($d,0,'','.');
                    return "$fd <b>/</b> ${row['nama_satuan_brg_produk']}";
                }),

                array('db' =>'harga_satuan', 'dt' => 5, 'formatter' => function($d, $row){
                    $hargaSatuan = (int) $row['harga_satuan'];
                    $modal = (int) $row['modal'];

                    $mProfit = '';
                    if($modal != 0) {
                        $mProfit = (($hargaSatuan - $modal) / $modal) * 100;

                        $mProfit = sprintf('%.2f', $mProfit);
                        $mProfit = "<br/><div style='font-size: 12px;'>Setara dengan<br/>modal + $mProfit%</div>";
                    }

                    $fd = number_format($d,0,'','.');
                    return "$fd <b>/</b> ${row['nama_satuan_brg_produk']}$mProfit";
                }),
                array('db' =>'persen_disc', 'dt' => 6, 'formatter' => function($d, $row){
                    $dMsg = '';
                    if($d != null && strpos($d, '+') !== false){
                        $calc = function ($d1, $d2) { return $d1 + $d2 - ($d1 * $d2 / 100); };
                        $dList = explode('+', $d);
                        $aDisc = ($calc)((float)(str_replace(',','.', $dList[0])), (float)(str_replace(',','.', $dList[1])));
                        for($i=2; $i<sizeof($dList); $i++){
                            $aDisc = ($calc)($aDisc, (float)(str_replace(',','.', $dList[$i])));
                        }

                        $aDisc = (float)sprintf("%.8f", $aDisc);
                        $decimals = strlen(substr(strrchr((string)$aDisc, "."), 1));

                        $p = number_format($aDisc, $decimals, ',','.');
                        $dMsg = "<br/><div style='font-size: 12px'>Setara dengan<br/>$p%</div>";
                    }

                    return ($d == null)? '' : "$d%$dMsg";
                }),
                array('db' =>'bonus_qty', 'dt' => 7, 'formatter' => function($d, $row){
                    $fd = number_format($d,0,'','.');
                    return "$fd ${row['nama_satuan_brg_bonus_qty']}";
                }),
                array('db' =>'harga_total', 'dt' => 8, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'disc_dari_supplier', 'dt' => 9, 'formatter' => function($d, $row){
                    $dMsg = '';
                    if($d != null && strpos($d, '+') !== false){
                        $calc = function ($d1, $d2) { return $d1 + $d2 - ($d1 * $d2 / 100); };
                        $dList = explode('+', $d);
                        $aDisc = ($calc)((float)(str_replace(',','.', $dList[0])), (float)(str_replace(',','.', $dList[1])));
                        for($i=2; $i<sizeof($dList); $i++){
                            $aDisc = ($calc)($aDisc, (float)(str_replace(',','.', $dList[$i])));
                        }

                        $aDisc = (float)sprintf("%.8f", $aDisc);
                        $decimals = strlen(substr(strrchr((string)$aDisc, "."), 1));

                        $p = number_format($aDisc, $decimals, ',','.');
                        $dMsg = "<br/><div style='font-size: 12px'>Setara dengan<br/>$p%</div>";
                    }

                    return ($d == null)? '' : "$d%$dMsg";
                }),
                array('db' =>'untung_bersih', 'dt' => 10, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),

                array('db' =>'nama_kategori_brg', 'dt' => 11),
                array('db' =>'nama_akun', 'dt' => 12, 'formatter' => function($d, $row){
                    if($d == null) return '';

                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 13, 'formatter' => function($d, $row){
                    return "";
                }),

                array('db' =>'id', 'dt' => 14),
                array('db' =>'id_faktur_penjualan', 'dt' => 15),
                array('db' =>'id_produk', 'dt' => 16),
                array('db' =>'raw_qty', 'dt' => 17),
                array('db' =>'id_satuan_brg_raw_qty', 'dt' => 18),
                array('db' =>'modal', 'dt' => 19),
                array('db' =>'harga_satuan', 'dt' => 20),
                array('db' =>'persen_disc', 'dt' => 21),
                array('db' =>'bonus_qty', 'dt' => 22),
                array('db' =>'id_satuan_brg_bonus_qty', 'dt' => 23),
                array('db' =>'harga_total', 'dt' => 24),
                array('db' =>'disc_dari_supplier', 'dt' => 25),
                array('db' =>'untung_bersih', 'dt' => 26),


                array('db' =>'nama_satuan_brg_raw_qty', 'dt' => 27),
                array('db' =>'nama_satuan_brg_produk', 'dt' => 28),
                array('db' =>'nama_satuan_brg_bonus_qty', 'dt' => 29),
                array('db' =>'id_akun_pj', 'dt' => 30),
            )
        );
    }
}

class DBVBarangKeluar extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_brg_keluar
            AS 
                SELECT 
                    bk.id AS id,
                    bk.id_faktur_penjualan AS id_faktur_penjualan,
                    fp.kode AS kode_faktur_penjualan,
                    fp.tanggal AS tanggal_faktur_penjualan,
                    bk.id_produk AS id_produk,
                    p.nama AS nama_produk,
                    p.id_kategori_brg AS id_kategori_brg,
                    kbp.nama AS nama_kategori_brg,
                    bk.disc_dari_supplier AS disc_dari_supplier,
                    bk.modal AS modal,
                    bk.qty AS raw_qty,
                    bk.id_satuan_brg_qty AS id_satuan_brg_raw_qty,
                    sbrq.nama AS nama_satuan_brg_raw_qty,
                    sbrq.singkatan AS singkatan_satuan_brg_raw_qty,

                    CASE 
                        WHEN pbrq.qty IS NULL
                        THEN bk.qty
                        ELSE bk.qty * pbrq.qty 
                    END AS final_qty,

                    p.id_satuan_brg AS id_satuan_brg_produk,
                    sbp.nama AS nama_satuan_brg_produk,
                    sbp.singkatan AS singkatan_satuan_brg_produk,
                    bk.harga_satuan AS harga_satuan,
                    bk.harga_total AS harga_total,
                    bk.untung_bersih AS untung_bersih,
                    bk.persen_disc AS persen_disc,
                    bk.bonus_qty AS bonus_qty,
                    bk.id_satuan_brg_bonus_qty AS id_satuan_brg_bonus_qty,
                    sbbq.nama AS nama_satuan_brg_bonus_qty,
                    sbbq.singkatan AS singkatan_satuan_brg_bonus_qty,

                    CASE 
                        WHEN pbbq.qty IS NULL
                        THEN bk.bonus_qty
                        ELSE bk.bonus_qty * pbbq.qty 
                    END AS final_bonus_qty,

                    fp.id_akun_pj AS id_akun_pj,
                    a.nama AS nama_akun

                FROM brg_keluar AS bk
                LEFT JOIN faktur_penjualan AS fp
                    ON bk.id_faktur_penjualan = fp.id
                LEFT JOIN produk AS p
                    ON bk.id_produk = p.id
                LEFT JOIN kategori_brg AS kbp
                    ON p.id_kategori_brg = kbp.id
                LEFT JOIN satuan_brg AS sbrq
                    ON bk.id_satuan_brg_qty = sbrq.id
                LEFT JOIN satuan_brg AS sbp
                    ON p.id_satuan_brg = sbp.id
                LEFT JOIN akun AS a
                    ON fp.id_akun_pj = a.id

                LEFT JOIN satuan_brg AS sbbq
                    ON bk.id_satuan_brg_bonus_qty = sbbq.id

                LEFT JOIN produk_bundle AS pbrq
                    ON p.id = pbrq.id_produk AND bk.id_satuan_brg_qty = pbrq.id_satuan_brg

                LEFT JOIN produk_bundle AS pbbq
                    ON p.id = pbbq.id_produk AND bk.id_satuan_brg_bonus_qty = pbbq.id_satuan_brg

                ORDER BY fp.tanggal DESC;
        ");
    }

    public function get(int $id): ?VBarangKeluar {
        $stmt = $this->db->prepare('SELECT * FROM v_brg_keluar WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VBarangKeluar($result);
    }

    public function getByIdFaktur(int $idFaktur): array {
        $stmt = $this->db->prepare('
            SELECT * FROM v_brg_keluar 
            WHERE id_faktur_penjualan=:id_faktur_penjualan
            ORDER BY nama_produk
        ');
        $stmt->bindValue(':id_faktur_penjualan', $idFaktur, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $ret = array();
        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret, new VBarangKeluar($res));
        }

        return $ret;
    }
}