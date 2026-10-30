<?php

class Produk{
    public int $id;
    public string $nama;
    public int $id_kategori_brg;
    public int $id_satuan_brg;
    public int $modal;
    public string $disc_dari_supplier;
    public int $id_akun_pj;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->id_kategori_brg,
            $this->id_satuan_brg,
            $this->modal,
            $this->disc_dari_supplier,
            $this->id_akun_pj,
        ] = $arr;
    }

    static public function cast($obj): Produk {
        return $obj;
    }
}

class DBProduk extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS produk (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT,
                id_kategori_brg INTEGER,
                id_satuan_brg INTEGER, --(brg terkecil)
                modal INTEGER,
                disc_dari_supplier TEXT,
                id_akun_pj INTEGER --(penanggung jawab)
            );
        ");
    }

    public function get(int $id): ?Produk {
        $stmt = $this->db->prepare('SELECT * FROM produk WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new Produk($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM produk WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    public function insert (
        string $nama,
        int $id_kategori_brg,
        int $id_satuan_brg,
        int $modal,
        string $disc_dari_supplier,
        int $id_akun_pj
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO produk (
                nama,
                id_kategori_brg,
                id_satuan_brg,
                modal,
                disc_dari_supplier,
                id_akun_pj
            ) VALUES (
                :nama,
                :id_kategori_brg,
                :id_satuan_brg,
                :modal,
                :disc_dari_supplier,
                :id_akun_pj
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':id_kategori_brg', $id_kategori_brg, SQLITE3_INTEGER);
        $stmt->bindValue(':id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER);
        $stmt->bindValue(':modal', $modal, SQLITE3_INTEGER);
        $stmt->bindValue(':disc_dari_supplier', $disc_dari_supplier, SQLITE3_TEXT);
        $stmt->bindValue(':id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?string $nama,
        ?int $id_kategori_brg,
        ?int $id_satuan_brg,
        ?int $modal,
        ?string $disc_dari_supplier,
        ?int $id_akun_pj
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('id_kategori_brg', $id_kategori_brg, SQLITE3_INTEGER)
            ->add('id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER)
            ->add('modal', $modal, SQLITE3_INTEGER)
            ->add('disc_dari_supplier', $disc_dari_supplier, SQLITE3_TEXT)
            ->add('id_akun_pj', $id_akun_pj, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE produk SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}