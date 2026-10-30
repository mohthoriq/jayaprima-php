<?php

class VSisaStok{
    public int $id;
    public string $nama_kategori;
    public string $nama_produk;
    public int $final_qty;
    public string $nama_satuan_brg;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama_kategori,
            $this->nama_produk,
            $this->final_qty,
            $this->nama_satuan_brg,
        ] = $arr;
    }

    static public function cast($obj): VSisaStok {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama_kategori', 'dt' => 0),
                array('db' =>'nama_produk', 'dt' => 1),
                array('db' =>'final_qty', 'dt' => 2, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'nama_satuan_brg', 'dt' => 3),
            )
        );
    }
}

class DBVSisaStok extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_sisa_stok
            AS 
            SELECT 
                p.id AS id,
                kb.nama AS nama_kategori,
                p.nama AS nama_produk,
                (
                    ifnull(rbm.final_qty, 0) - (ifnull(rbk.final_qty, 0) + ifnull(rbk.final_bonus_qty, 0))
                ) AS final_qty,
                sb.nama AS nama_satuan_brg

            FROM produk AS p
            LEFT JOIN kategori_brg AS kb
                ON p.id_kategori_brg = kb.id
            LEFT JOIN satuan_brg AS sb
                ON p.id_satuan_brg = sb.id
            LEFT JOIN (
                SELECT 
                    bm.nama_kategori_brg,
                    bm.nama_produk,
                    bm.id_produk,
                    sum(bm.final_qty) AS final_qty,
                    bm.nama_satuan_brg_produk
                FROM v_brg_masuk AS bm
                GROUP BY bm.id_produk
            ) AS rbm
                ON p.id = rbm.id_produk
            LEFT JOIN (
                SELECT 
                    bk.nama_kategori_brg,
                    bk.nama_produk,
                    bk.id_produk,
                    sum(bk.final_qty) AS final_qty,
                    sum(
                        CASE WHEN bk.final_bonus_qty IS NULL THEN 0 
                        ELSE bk.final_bonus_qty END
                    ) AS final_bonus_qty,
                    bk.nama_satuan_brg_produk
                FROM v_brg_keluar AS bk
                GROUP BY bk.id_produk
            ) as rbk
                ON p.id = rbk.id_produk

            ORDER BY final_qty ASC
        ");
    }

    public function get(int $id): ?VSisaStok {
        $stmt = $this->db->prepare('SELECT * FROM v_sisa_stok WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VSisaStok($result);
    }
}