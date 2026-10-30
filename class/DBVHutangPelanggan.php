<?php

include_once ROOTDIR . 'class/DBVFakturPenjualan.php';

class VHutangPelanggan extends VFakturPenjualan {
    static public function cast($obj): VHutangPelanggan {
        return $obj;
    }

    static public function dataTables() : array{
        $deadLineDays = $GLOBALS['dbUserInfo']->deadLineHutangPjDays;

        return array(
            'id',
            array(
                array('db' =>'tanggal', 'dt' => 0, 'formatter' => function($d, $row){
                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'tanggal', 'dt' => 1, 'formatter' => function($d, $row) use($deadLineDays) {
                    $nowD = time();
                    $deadD = strtotime("+$deadLineDays day", ((int) $d)/1000);
                    $diffD = $deadD - $nowD;
                    $remainDays = round($diffD / (60 * 60 * 24));

                    $class = ($remainDays > 10)? 'txt_green' : 'txt_red';

                    return "<p class='$class'>$remainDays hari</p>";
                }),
                array('db' =>'sisa', 'dt' => 2, 'formatter' => function($d, $row){
                    //$d = abs($d);
                    return number_format($d,0,'','.');
                }),
                array('db' =>'kode', 'dt' => 3),
                array('db' =>'nama_pelanggan', 'dt' => 4),
                array('db' =>'nohp_pelanggan', 'dt' => 5),
                array('db' =>'nama_akun', 'dt' => 6, 'formatter' => function($d, $row){
                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),

                array('db' =>'id', 'dt' => 7, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-primary btnViewBarangKeluar' value='$id'>Lihat Barang</button>
                        <button class='btn btn-success btnPrintFaktur' value='$id'>Cetak Faktur</button>
                        <button class='btn btn-warning btnVFakturPenjualanEdit' value='$id'>Edit</button>
                    ";
                }),
                array('db' =>'id_akun_pj', 'dt' => 8),
                array('db' =>'id', 'dt' => 9),
            )
        );
    }
}

class DBVHutangPelanggan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_hutang_pelanggan
            AS 
            SELECT * 
            FROM v_faktur_penjualan 
            WHERE sisa < 0
            ORDER BY tanggal
        ");
    }

    public function get(int $id): ?VHutangPelanggan {
        $stmt = $this->db->prepare('SELECT * FROM v_hutang_pembelian WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VHutangPelanggan($result);
    }
}