<?php

class VFakturPenjualan{
    public int $id;
    public int $tanggal;
    public string $kode;
    public int $id_pelanggan;
    public string $nama_pelanggan;
    public string $alamat_pelanggan;
    public string $kota_pelanggan;
    public string $nohp_pelanggan;
    public int $subtotal;
    public int $persen_ppn;
    public int $total;
    public int $bayar;
    public int $sisa;
    public int $id_akun_pj;
    public string $nama_akun;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->kode,
            $this->id_pelanggan,
            $this->nama_pelanggan,
            $this->alamat_pelanggan,
            $this->kota_pelanggan,
            $this->nohp_pelanggan,
            $this->subtotal,
            $this->persen_ppn,
            $this->total,
            $this->bayar,
            $this->sisa,
            $this->id_akun_pj,
            $this->nama_akun,
        ] = $arr;
    }

    static public function cast($obj): VFakturPenjualan {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'tanggal', 'dt' => 0, 'formatter' => function($d, $row){
                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'nama_pelanggan', 'dt' => 1),
                array('db' =>'kode', 'dt' => 2),
                array('db' =>'subtotal', 'dt' => 3, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'persen_ppn', 'dt' => 4, 'formatter' => function($d, $row){
                    return ($d == null)? '' : "$d%";
                }),
                array('db' =>'total', 'dt' => 5, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'bayar', 'dt' => 6, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'sisa', 'dt' => 7, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'nama_akun', 'dt' => 8, 'formatter' => function($d, $row){
                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 9, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-primary btnViewBarangKeluar' value='$id'>Lihat Barang</button>
                        <button class='btn btn-success btnPrintFaktur' value='$id'>Cetak Faktur</button>
                        <button class='btn btn-warning btnVFakturPenjualanEdit' value='$id'>Edit</button>
                    ";
                }),


                array('db' =>'id', 'dt' => 10),
                array('db' =>'tanggal', 'dt' => 11),
                array('db' =>'id_pelanggan', 'dt' => 12),
                array('db' =>'subtotal', 'dt' => 13),
                array('db' =>'persen_ppn', 'dt' => 14),
                array('db' =>'total', 'dt' => 15),
                array('db' =>'bayar', 'dt' => 16),
                array('db' =>'sisa', 'dt' => 17),
                array('db' =>'id_akun_pj', 'dt' => 18),
            )
        );
    }
}

class DBVFakturPenjualan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_faktur_penjualan
            AS 
            SELECT
                fp.id AS id,
                fp.tanggal AS tanggal,
                fp.kode AS kode,
                fp.id_pelanggan AS id_pelanggan,
                pl.nama AS nama_pelanggan,
                pl.alamat AS alamat_pelanggan,
                pl.kota AS kota_pelanggan,
                pl.nohp AS nohp_pelanggan,
                fp.subtotal AS subtotal,
                fp.persen_ppn AS persen_ppn,
                fp.total AS total,
                fp.bayar AS bayar,
                fp.sisa AS sisa,
                fp.id_akun_pj AS id_akun_pj,
                a.nama AS nama_akun

            FROM faktur_penjualan AS fp
            INNER JOIN pelanggan AS pl
                ON fp.id_pelanggan = pl.id
            INNER JOIN akun AS a
                ON fp.id_akun_pj = a.id;
        ");
    }

    public function get(int $id): ?VFakturPenjualan {
        $stmt = $this->db->prepare('SELECT * FROM v_faktur_penjualan WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VFakturPenjualan($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, kode FROM v_faktur_penjualan WHERE kode LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }
}