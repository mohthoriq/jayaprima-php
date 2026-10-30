<?php

class ProdukBundle{
    public int $id;
    public int $id_produk;
    public int $id_satuan_brg;
    public int $qty;

    public function __construct(array $arr){
        [
            $this->id,
            $this->id_produk,
            $this->id_satuan_brg,
            $this->qty,
        ] = $arr;
    }

    static public function cast($obj): ProdukBundle {
        return $obj;
    }
}

class DBProdukBundle extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS produk_bundle (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                id_produk INTEGER,
                id_satuan_brg INTEGER,
                qty INTEGER
            );
        ");
    }

    public function get(int $id): ?ProdukBundle {
        $stmt = $this->db->prepare('SELECT * FROM produk_bundle WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new ProdukBundle($result);
    }

    public function insert (
        int $id_produk,
        int $id_satuan_brg,
        int $qty
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO produk_bundle (
                id_produk,
                id_satuan_brg,
                qty
            ) VALUES (
                :id_produk,
                :id_satuan_brg,
                :qty
            );
        ');
        $stmt->bindValue(':id_produk', $id_produk, SQLITE3_INTEGER);
        $stmt->bindValue(':id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER);
        $stmt->bindValue(':qty', $qty, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $id_produk,
        ?int $id_satuan_brg,
        ?int $qty
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('id_produk', $id_produk, SQLITE3_INTEGER)
            ->add('id_satuan_brg', $id_satuan_brg, SQLITE3_INTEGER)
            ->add('qty', $qty, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE produk_bundle SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function delete (
        int $id
    ): bool {
        $stmt = $this->db->prepare("
            DELETE FROM produk_bundle WHERE id=:id;
        ");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}