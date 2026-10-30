<?php

class VTopKategori{
    public int $id;
    public string $kategori;
    public int $total;

    public function __construct(array $arr){
        [
            $this->id,
            $this->kategori,
            $this->total,
        ] = $arr;
    }

    static public function cast($obj): VTopKategori {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'kategori', 'dt' => 0),
                array('db' =>'total', 'dt' => 1, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
            )
        );
    }
}

class DBVTopKategori extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_top_kategori
            AS 
            SELECT 
                kb.id,
                kb.nama AS kategori,
                SUM(bk.harga_satuan) AS total
            
            FROM brg_keluar AS bk
            INNER JOIN produk AS p
                ON p.id = bk.id_produk
            INNER JOIN kategori_brg AS kb
                ON kb.id = p.id_kategori_brg
            
            GROUP BY kb.id
            ORDER BY total DESC 
        ");
    }
}