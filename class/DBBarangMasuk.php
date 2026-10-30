<?php

class BarangMasuk{
    public int $id;
    public int $tanggal;
    public int $id_produk;
    public int $qty;
    public int $id_satuan_brg;
    public int $id_akun_pj;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->id_produk,
            $this->qty,
            $this->id_satuan_brg,
            $this->id_akun_pj,
        ] = $arr;
    }

    static public function cast($obj): BarangMasuk {
        return $obj;
    }
}

class DBBarangMasuk extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS brg_masuk (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tanggal INTEGER,
                id_produk INTEGER,
                qty INTEGER,
                id_satuan_brg INTEGER,
                id_akun_pj INTEGER
            );
        ");
    }

    public function get(int $id): ?BarangMasuk {
        $stmt = $this->db->prepare('SELECT * FROM brg_masuk WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new BarangMasuk($result);
    }

    public function insert (
        int $tanggal,
        int $id_produk,
        int $qty,
        int $id_satuan_brg,
        int $id_akun_pj
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO brg_masuk (
                tanggal,
                id_produk,
                qty,
                id_satuan_brg,
                id_akun_pj
            ) VALUES (
                :tanggal,
                :id_produk,
                :qty,
                :id_satuan_brg,
                :id_akun_pj
            );
        ');
        $stmt->bindValue(':tanggal', $tanggal, SQLITE3_INTEGER);
        $stmt->bindValue(':id_produk', $id_produk, SQLITE3_INTEGER);
        $stmt->bindValue(':qty', $qty, SQLITE3_INTEGER);
        $stmt->bindValue(':id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER);
        $stmt->bindValue(':id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $tanggal,
        ?int $id_produk,
        ?int $qty,
        ?int $id_satuan_brg,
        ?int $id_akun_pj
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('tanggal', $tanggal, SQLITE3_INTEGER)
            ->add('id_produk', $id_produk, SQLITE3_INTEGER)
            ->add('qty', $qty, SQLITE3_INTEGER)
            ->add('id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER)
            ->add('id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE brg_masuk SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}