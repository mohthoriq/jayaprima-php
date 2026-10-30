<?php

class SatuanBrg{
    public int $id;
    public string $nama;
    public string $singkatan;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->singkatan,
        ] = $arr;
    }

    static public function cast($obj): SatuanBrg {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama', 'dt' => 0),
                array('db' =>'singkatan', 'dt' => 1),
                array('db' =>'id', 'dt' => 2, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnSatuanBrgEdit' value='$id'>Edit</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 3),
            )
        );
    }
}

class DBSatuanBrg extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS satuan_brg (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT,
                singkatan VARCHAR(3)
            );
        ");
    }

    public function get(int $id): ?SatuanBrg {
        $stmt = $this->db->prepare('SELECT * FROM satuan_brg WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new SatuanBrg($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM satuan_brg WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    public function insert (
        string $nama,
        string $singkatan
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO satuan_brg (
                nama,
                singkatan
            ) VALUES (
                :nama,
                :singkatan
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':singkatan', $singkatan, SQLITE3_TEXT);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id, ?string $nama, ?string $singkatan
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('singkatan', $singkatan, SQLITE3_TEXT);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE satuan_brg SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}