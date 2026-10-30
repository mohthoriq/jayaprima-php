<?php

class VBeranda{
    public int $hutang_pembelian;
    public int $hutang_pelanggan;
    public int $supplier;
    public int $pelanggan;
    public int $produk;
    public int $admin_aktif;

    public function __construct(array $arr){
        [
            $this->hutang_pembelian,
            $this->hutang_pelanggan,
            $this->supplier,
            $this->pelanggan,
            $this->produk,
            $this->admin_aktif,
        ] = $arr;
    }

    static public function cast($obj): VBeranda {
        return $obj;
    }
}

class DBVBeranda extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_beranda
            AS 
            SELECT
            (
                SELECT COUNT(*) FROM hutang_pembelian WHERE status_lunas = 0
            ) AS hutang_pembelian,
            (
                SELECT COUNT(*) FROM faktur_penjualan WHERE sisa < 0
            ) AS hutang_pelanggan,
            (
                SELECT COUNT(*) FROM supplier
            ) AS supplier,
            (
                SELECT COUNT(*) FROM pelanggan
            ) AS pelanggan,
            (
                SELECT COUNT(*) FROM produk
            ) AS produk,
            (
                SELECT COUNT(*) FROM akun WHERE isActive = 1
            ) AS admin_aktif
        ");
    }

    public function get(): ?VBeranda {
        $stmt = $this->db->prepare('SELECT * FROM v_beranda');

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VBeranda($result);
    }
}