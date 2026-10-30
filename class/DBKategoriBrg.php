<?php

class KategoriBrg{
    public int $id;
    public string $nama;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
        ] = $arr;
    }

    static public function cast($obj): KategoriBrg {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama', 'dt' => 0),
                array('db' =>'id', 'dt' => 1, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnKategoriBrgEdit' value='$id'>Edit</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 2),
            )
        );
    }
}

class DBKategoriBrg extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS kategori_brg (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT
            );
        ");
    }

    public function get(int $id): ?KategoriBrg {
        $stmt = $this->db->prepare('SELECT * FROM kategori_brg WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new KategoriBrg($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM kategori_brg WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    public function insert (string $nama): bool {
        $stmt = $this->db->prepare('
            INSERT INTO kategori_brg (
                nama
            ) VALUES (
                :nama
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);

        return (bool) $stmt->execute();
    }

    public function update (int $id, ?string $nama): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE kategori_brg SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}