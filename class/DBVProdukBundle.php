<?php

class VProdukBundle{
    public int $id;
    public int $id_produk;
    public int $id_satuan_brg;
    public string $nama_satuan_brg;
    public int $qty;

    public function __construct(array $arr){
        [
            $this->id,
            $this->id_produk,
            $this->id_satuan_brg,
            $this->nama_satuan_brg,
            $this->qty,
        ] = $arr;
    }

    static public function cast($obj): VProdukBundle {
        return $obj;
    }
}

class DBVProdukBundle extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_produk_bundle
            AS
            SELECT
                pb.id AS id,
                pb.id_produk AS id_produk,
                pb.id_satuan_brg AS id_satuan_brg,
                sb.nama AS nama_satuan_brg,
                pb.qty AS qty
            FROM produk_bundle AS pb
            INNER JOIN satuan_brg AS sb
                ON pb.id_satuan_brg = sb.id;
        ");
    }

    public function get(int $id): ?VProdukBundle {
        $stmt = $this->db->prepare('SELECT * FROM v_produk_bundle WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VProdukBundle($result);
    }

    public function autoComplete($query, ?int $id_produk): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        if($id_produk == null) return $ret;

        $sql = "SELECT id_satuan_brg, nama_satuan_brg FROM v_produk_bundle WHERE nama_satuan_brg LIKE :query AND id_produk = :id_produk";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);
        $stmt->bindValue(":id_produk", $id_produk, SQLITE3_INTEGER);

        include_once ROOTDIR . 'class/DBVProduk.php';
        $p = (new DBVProduk())->get($id_produk);
        if($p != null){
            array_push($ret->suggestions, new AutoCompleteSuggestions($p->id_satuan_brg, $p->nama_satuan_brg));
        }

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    /**
     * @param int $id_produk
     * @return array{VProdukBundle}
     */
    public function getWhereIdProduk(int $id_produk): array {
        $stmt = $this->db->prepare('SELECT * FROM v_produk_bundle WHERE id_produk=:id_produk');
        $stmt->bindValue(':id_produk', $id_produk, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $ret = array();
        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret, new VProdukBundle($res));
        }

        return $ret;
    }
}