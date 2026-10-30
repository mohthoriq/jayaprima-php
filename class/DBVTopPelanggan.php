<?php

class VTopPelanggan{
    public int $id;
    public string $nama_pelanggan;
    public int $total_belanja;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama_pelanggan,
            $this->total_belanja,
        ] = $arr;
    }

    static public function cast($obj): VTopPelanggan {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama_pelanggan', 'dt' => 0),
                array('db' =>'total_belanja', 'dt' => 1, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
            )
        );
    }
}

class DBVTopPelanggan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_top_pelanggan
            AS 
            SELECT
                fp.id_pelanggan AS id,
                fp.nama_pelanggan AS nama_pelanggan,
                SUM(fp.subtotal) AS total_belanja
            FROM v_faktur_penjualan AS fp
            GROUP BY fp.id_pelanggan
            ORDER BY total_belanja DESC
        ");
    }
}