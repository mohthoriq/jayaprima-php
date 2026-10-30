<?php

//include_once ROOTDIR . 'class/DBHutangPembelian.php';

class VBarangMasuk{
    public int $id;
    public int $tanggal;
    public int $id_produk;
    public string $nama_produk;
    public int $id_kategori_brg;
    public string $nama_kategori_brg;
    public int $modal;
    public int $raw_qty;
    public int $id_satuan_brg_raw_qty;
    public string $nama_satuan_brg_raw_qty;
    public int $final_qty;
    public int $id_satuan_brg_produk;
    public string $nama_satuan_brg_produk;
    public string $singkatan_satuan_brg_produk;
    public int $id_akun_pj;
    public string $nama_akun;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->id_produk,
            $this->nama_produk,
            $this->id_kategori_brg,
            $this->nama_kategori_brg,
            $this->modal,
            $this->raw_qty,
            $this->id_satuan_brg_raw_qty,
            $this->nama_satuan_brg_raw_qty,
            $this->final_qty,
            $this->id_satuan_brg_produk,
            $this->nama_satuan_brg_produk,
            $this->singkatan_satuan_brg_produk,
            $this->id_akun_pj,
            $this->nama_akun,
        ] = $arr;
    }

    static public function cast($obj): VBarangMasuk {
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
                array('db' =>'nama_kategori_brg', 'dt' => 5),
                array('db' =>'nama_akun', 'dt' => 6, 'formatter' => function($d, $row){
                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 7, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnVBarangMasukEdit' value='$id'>Edit</button>
                    ";
                }),

                array('db' =>'id', 'dt' => 8),
                array('db' =>'tanggal', 'dt' => 9),
                array('db' =>'id_produk', 'dt' => 10),
                array('db' =>'raw_qty', 'dt' => 11),
                array('db' =>'id_satuan_brg_raw_qty', 'dt' => 12),
                array('db' =>'nama_satuan_brg_raw_qty', 'dt' => 13),

                array('db' =>'nama_satuan_brg_produk', 'dt' => 14),
                array('db' =>'id_akun_pj', 'dt' => 15),
            )
        );
    }
}

class DBVBarangMasuk extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_brg_masuk
            AS 
                SELECT 
                    bm.id AS id,
                    bm.tanggal AS tanggal,
                    bm.id_produk AS id_produk,
                    p.nama AS nama_produk,
                    p.id_kategori_brg AS id_kategori_brg,
                    kbp.nama AS nama_kategori_brg,
                    p.modal AS modal,
                    bm.qty AS raw_qty,
                    bm.id_satuan_brg AS id_satuan_brg_raw_qty,
                    sbrq.nama AS nama_satuan_brg_raw_qty,

                    CASE 
                        WHEN pb.qty IS NULL
                        THEN bm.qty
                        ELSE bm.qty * pb.qty 
                    END AS final_qty,

                    p.id_satuan_brg AS id_satuan_brg_produk,
                    sbp.nama AS nama_satuan_brg_produk,
                    sbp.singkatan AS singkatan_satuan_brg_produk,
                    bm.id_akun_pj AS id_akun_pj,
                    a.nama AS nama_akun

                FROM brg_masuk AS bm
                INNER JOIN produk AS p
                    ON bm.id_produk = p.id
                INNER JOIN kategori_brg AS kbp
                    ON p.id_kategori_brg = kbp.id
                INNER JOIN satuan_brg AS sbrq
                    ON bm.id_satuan_brg = sbrq.id
                INNER JOIN satuan_brg AS sbp
                    ON p.id_satuan_brg = sbp.id
                INNER JOIN akun AS a
                    ON bm.id_akun_pj = a.id

                LEFT JOIN produk_bundle AS pb
                    ON p.id = pb.id_produk AND bm.id_satuan_brg = pb.id_satuan_brg

                ORDER BY bm.tanggal DESC;
        ");
    }

    public function get(int $id): ?VBarangMasuk {
        $stmt = $this->db->prepare('SELECT * FROM v_brg_masuk WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VBarangMasuk($result);
    }
}