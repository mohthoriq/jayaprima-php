<?php

class HutangPembelian{
    public int $id;
    public int $id_supplier;
    public string $no_faktur;
    public int $nominal;
    public int $tanggal_beli;
    public int $status_lunas;
    public ?int $tanggal_lunas;
    public int $id_akun_pj;


    public function __construct(array $arr){
        [
            $this->id,
            $this->id_supplier,
            $this->no_faktur,
            $this->nominal,
            $this->tanggal_beli,
            $this->status_lunas,
            $this->tanggal_lunas,
            $this->id_akun_pj
        ] = $arr;
    }

    static public function cast($obj): HutangPembelian {
        return $obj;
    }
}

class DBHutangPembelian extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS hutang_pembelian (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                id_supplier INTEGER,
                no_faktur TEXT,
                nominal INTEGER,
                tanggal_beli INTEGER,
                status_lunas INTEGER,
                tanggal_lunas INTEGER,
                id_akun_pj INTEGER
            );
        ");
    }

    public function get(int $id): ?HutangPembelian {
        $stmt = $this->db->prepare('SELECT * FROM hutang_pembelian WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new HutangPembelian($result);
    }

    public function insert (
        int $id_supplier,
        string $no_faktur,
        int $nominal,
        int $tanggal_beli,
        int $status_lunas,
//        int $tanggal_lunas,
        int $id_akun_pj
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO hutang_pembelian (
                id_supplier,
                no_faktur,
                nominal,
                tanggal_beli,
                status_lunas,
                /*tanggal_lunas,*/
                id_akun_pj
            ) VALUES (
                :id_supplier,
                :no_faktur,
                :nominal,
                :tanggal_beli,
                :status_lunas,
                /*:tanggal_lunas,*/
                :id_akun_pj
            );
        ');
        $stmt->bindValue(':id_supplier', $id_supplier, SQLITE3_INTEGER);
        $stmt->bindValue(':no_faktur', $no_faktur, SQLITE3_TEXT);
        $stmt->bindValue(':nominal', $nominal, SQLITE3_INTEGER);
        $stmt->bindValue(':tanggal_beli', $tanggal_beli, SQLITE3_INTEGER);
        $stmt->bindValue(':status_lunas', $status_lunas, SQLITE3_INTEGER);
//        $stmt->bindValue(':tanggal_lunas', $tanggal_lunas, SQLITE3_INTEGER);
        $stmt->bindValue(':id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $id_supplier,
        ?string $no_faktur,
        ?int $nominal,
        ?int $tanggal_beli,
        ?int $status_lunas,
        ?int $tanggal_lunas,
        ?int $id_akun_pj
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('id_supplier', $id_supplier, SQLITE3_INTEGER)
            ->add('no_faktur', $no_faktur, SQLITE3_TEXT)
            ->add('nominal', $nominal, SQLITE3_INTEGER)
            ->add('tanggal_beli', $tanggal_beli, SQLITE3_INTEGER)
            ->add('status_lunas', $status_lunas, SQLITE3_INTEGER)
            ->add('id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        if($tanggal_lunas == 0)
            $builder->add('tanggal_lunas', null, SQLITE3_NULL);
        else
            $builder->add('tanggal_lunas', $tanggal_lunas, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE hutang_pembelian SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}