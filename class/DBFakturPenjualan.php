<?php

class FakturPenjualan{
    public int $id;
    public int $tanggal;
    public string $kode;
    public int $id_pelanggan;
    public int $subtotal;
    public int $persen_ppn;
    public int $total;
    public int $bayar;
    public int $sisa;
    public int $id_akun_pj;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->kode,
            $this->id_pelanggan,
            $this->subtotal,
            $this->persen_ppn,
            $this->total,
            $this->bayar,
            $this->sisa,
            $this->id_akun_pj,
        ] = $arr;
    }

    static public function cast($obj): FakturPenjualan {
        return $obj;
    }
}

class DBFakturPenjualan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS faktur_penjualan (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tanggal INTEGER,
                kode TEXT,
                id_pelanggan INTEGER,
                subtotal INTEGER,
                persen_ppn INTEGER,
                total INTEGER,
                bayar INTEGER,
                sisa INTEGER,
                id_akun_pj INTEGER
            );
        ");
    }

    public function get(int $id): ?FakturPenjualan {
        $stmt = $this->db->prepare('SELECT * FROM faktur_penjualan WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new FakturPenjualan($result);
    }

    public function insert (
        int $tanggal,
        string $kode,
        int $id_pelanggan,
        int $subtotal,
        int $persen_ppn,
        int $total,
        int $bayar,
        int $sisa,
        int $id_akun_pj
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO faktur_penjualan (
                tanggal,
                kode,
                id_pelanggan,
                subtotal,
                persen_ppn,
                total,
                bayar,
                sisa,
                id_akun_pj
            ) VALUES (
                :tanggal,
                :kode,
                :id_pelanggan,
                :subtotal,
                :persen_ppn,
                :total,
                :bayar,
                :sisa,
                :id_akun_pj
            );
        ');

        $stmt->bindValue(':tanggal', $tanggal, SQLITE3_INTEGER);
        $stmt->bindValue(':kode', $kode, SQLITE3_TEXT);
        $stmt->bindValue(':id_pelanggan', $id_pelanggan, SQLITE3_INTEGER);
        $stmt->bindValue(':subtotal', $subtotal, SQLITE3_INTEGER);
        $stmt->bindValue(':persen_ppn', $persen_ppn, SQLITE3_INTEGER);
        $stmt->bindValue(':total', $total, SQLITE3_INTEGER);
        $stmt->bindValue(':bayar', $bayar, SQLITE3_INTEGER);
        $stmt->bindValue(':sisa', $sisa, SQLITE3_INTEGER);
        $stmt->bindValue(':id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $tanggal,
        ?string $kode,
        ?int $id_pelanggan,
        ?int $subtotal,
        ?int $persen_ppn,
        ?int $total,
        ?int $bayar,
        ?int $sisa,
        ?int $id_akun_pj
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('tanggal', $tanggal, SQLITE3_INTEGER)
            ->add('kode', $kode, SQLITE3_TEXT)
            ->add('id_pelanggan', $id_pelanggan, SQLITE3_INTEGER)
            ->add('subtotal', $subtotal, SQLITE3_INTEGER)
            ->add('persen_ppn', $persen_ppn, SQLITE3_INTEGER)
            ->add('total', $total, SQLITE3_INTEGER)
            ->add('bayar', $bayar, SQLITE3_INTEGER)
            ->add('sisa', $sisa, SQLITE3_INTEGER)
            ->add('id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE faktur_penjualan SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}