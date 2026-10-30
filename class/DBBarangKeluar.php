<?php

class BarangKeluar{
    public int $id;
    public int $id_faktur_penjualan;
    public int $id_produk;
    public ?string $disc_dari_supplier;
    public int $qty;
    public int $id_satuan_brg_qty;
    public int $modal;
    public int $harga_satuan;
    public string $persen_disc;
    public ?int $bonus_qty;
    public ?int $id_satuan_brg_bonus_qty;
    public int $harga_total;
    public ?int $untung_bersih;

    public function __construct(array $arr){
        [
            $this->id,
            $this->id_faktur_penjualan,
            $this->id_produk,
            $this->disc_dari_supplier,
            $this->qty,
            $this->id_satuan_brg_qty,
            $this->modal,
            $this->harga_satuan,
            $this->persen_disc,
            $this->bonus_qty,
            $this->id_satuan_brg_bonus_qty,
            $this->harga_total,
            $this->untung_bersih
        ] = $arr;
    }

    static public function cast($obj): BarangKeluar {
        return $obj;
    }
}

class DBBarangKeluar extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS brg_keluar (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                id_faktur_penjualan INTEGER,
                id_produk INTEGER,
                disc_dari_supplier TEXT,   --discount dari supplier/distributor
                qty INTEGER,
                id_satuan_brg_qty INTEGER,
                modal INTEGER,
                harga_satuan INTEGER,
                persen_disc TEXT,   --discount utk konsumen
                bonus_qty INTEGER,
                id_satuan_brg_bonus_qty INTEGER,
                harga_total INTEGER,
                untung_bersih INTEGER
            );
        ");
    }

    public function get(int $id): ?BarangKeluar {
        $stmt = $this->db->prepare('SELECT * FROM brg_keluar WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new BarangKeluar($result);
    }

    public function insert (
        int $id_faktur_penjualan,
        int $id_produk,
        string $disc_dari_supplier,
        int $qty,
        int $id_satuan_brg_qty,
        int $modal,
        int $harga_satuan,
        string $persen_disc,
        ?int $bonus_qty,
        ?int $id_satuan_brg_bonus_qty,
        int $harga_total,
        int $untung_bersih
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO brg_keluar (
                id_faktur_penjualan,
                id_produk,
                disc_dari_supplier,
                qty,
                id_satuan_brg_qty,
                modal,
                harga_satuan,
                persen_disc,
                bonus_qty,
                id_satuan_brg_bonus_qty,
                harga_total,
                untung_bersih
            ) VALUES (
                :id_faktur_penjualan,
                :id_produk,
                :disc_dari_supplier,
                :qty,
                :id_satuan_brg_qty,
                :modal,
                :harga_satuan,
                :persen_disc,
                :bonus_qty,
                :id_satuan_brg_bonus_qty,
                :harga_total,
                :untung_bersih
            );
        ');
        $stmt->bindValue(':id_faktur_penjualan', $id_faktur_penjualan, SQLITE3_INTEGER);
        $stmt->bindValue(':id_produk', $id_produk, SQLITE3_INTEGER);
        $stmt->bindValue(':disc_dari_supplier', $disc_dari_supplier, SQLITE3_TEXT);
        $stmt->bindValue(':qty', $qty, SQLITE3_INTEGER);
        $stmt->bindValue(':id_satuan_brg_qty', $id_satuan_brg_qty, SQLITE3_INTEGER);
        $stmt->bindValue(':modal', $modal, SQLITE3_INTEGER);
        $stmt->bindValue(':harga_satuan', $harga_satuan, SQLITE3_INTEGER);
        $stmt->bindValue(':persen_disc', $persen_disc, SQLITE3_TEXT);

        $stmt->bindValue(':bonus_qty', $bonus_qty, ($bonus_qty == null)? SQLITE3_NULL : SQLITE3_INTEGER);
        $stmt->bindValue(':id_satuan_brg_bonus_qty', $id_satuan_brg_bonus_qty, ($id_satuan_brg_bonus_qty == null)? SQLITE3_NULL : SQLITE3_INTEGER);

        $stmt->bindValue(':harga_total', $harga_total, SQLITE3_INTEGER);

        $stmt->bindValue(':untung_bersih', $untung_bersih, SQLITE3_INTEGER);


        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $id_faktur_penjualan,
        ?int $id_produk,
        ?string $disc_dari_supplier,
        ?int $qty,
        ?int $id_satuan_brg_qty,
        ?int $modal,
        ?int $harga_satuan,
        ?string $persen_disc,
        ?int $bonus_qty,
        ?int $id_satuan_brg_bonus_qty,
        ?int $harga_total,
        ?int $untung_bersih
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('id_faktur_penjualan', $id_faktur_penjualan, SQLITE3_INTEGER)
            ->add('id_produk', $id_produk, SQLITE3_INTEGER)
            ->add('disc_dari_supplier', $disc_dari_supplier, SQLITE3_TEXT)
            ->add('qty', $qty, SQLITE3_INTEGER)
            ->add('id_satuan_brg_qty', $id_satuan_brg_qty, SQLITE3_INTEGER)
            ->add('modal', $modal, SQLITE3_INTEGER)
            ->add('harga_satuan', $harga_satuan, SQLITE3_INTEGER)
            ->add('persen_disc', $persen_disc, SQLITE3_TEXT)
            ->add('bonus_qty', $bonus_qty, ($bonus_qty == null)? SQLITE3_NULL : SQLITE3_INTEGER)
            ->add('id_satuan_brg_bonus_qty', $id_satuan_brg_bonus_qty, ($id_satuan_brg_bonus_qty == null)? SQLITE3_NULL : SQLITE3_INTEGER)
            ->add('harga_total', $harga_total, SQLITE3_INTEGER)
            ->add('untung_bersih', $untung_bersih, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE brg_keluar SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function delete (
        int $id
    ): bool {
        $stmt = $this->db->prepare("
            DELETE FROM brg_keluar WHERE id=:id;
        ");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}