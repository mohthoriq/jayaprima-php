<?php

class Supplier{
    public int $id;
    public string $nama;
    public ?string $alamat;
    public ?string $nohp;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->alamat,
            $this->nohp,
        ] = $arr;
    }

    static public function cast($obj): Supplier {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama', 'dt' => 0),
                array('db' =>'nohp', 'dt' => 1),
                array('db' =>'alamat', 'dt' => 2),
                array('db' =>'id', 'dt' => 3, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnSupplierEdit' value='$id'>Edit</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 4),
            )
        );
    }
}

class DBSupplier extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS supplier (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama VARCHAR(30),
                alamat VARCHAR(30),
                nohp VARCHAR(30)
            );
        ");
    }

    public function get(int $id): ?Supplier {
        $stmt = $this->db->prepare('SELECT * FROM supplier WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new Supplier($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM supplier WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    public function insert (
        string $nama, string $alamat, string $nohp
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO supplier (
                nama,
                alamat,
                nohp
            ) VALUES (
                :nama,
                :alamat,
                :nohp
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':alamat', $alamat, SQLITE3_TEXT);
        $stmt->bindValue(':nohp', $nohp, SQLITE3_TEXT);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id, ?string $nama, ?string $alamat, ?string $nohp
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('alamat', $alamat, SQLITE3_TEXT)
            ->add('nohp', $nohp, SQLITE3_TEXT);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE supplier SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}