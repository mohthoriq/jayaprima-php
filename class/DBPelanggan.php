<?php

class Pelanggan{
    public int $id;
    public string $nama;
    public ?string $alamat;
    public ?string $kota;
    public ?string $nohp;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->alamat,
            $this->kota,
            $this->nohp,
        ] = $arr;
    }

    static public function cast($obj): Pelanggan {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama', 'dt' => 0),
                array('db' =>'nohp', 'dt' => 1),
                array('db' =>'kota', 'dt' => 2),
                array('db' =>'alamat', 'dt' => 3),
                array('db' =>'id', 'dt' => 4, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnPelangganEdit' value='$id'>Edit</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 5),
            )
        );
    }
}

class DBPelanggan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pelanggan (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama VARCHAR(30),
                alamat VARCHAR(30),
                kota VARCHAR(30),
                nohp VARCHAR(30)
            );
        ");
    }

    public function get(int $id): ?Pelanggan {
        $stmt = $this->db->prepare('SELECT * FROM pelanggan WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new Pelanggan($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM pelanggan WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }

    public function insert (
        string $nama, string $alamat, string $kota, string $nohp
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO pelanggan (
                nama,
                alamat,
                kota,
                nohp
            ) VALUES (
                :nama,
                :alamat,
                :kota,
                :nohp
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':alamat', $alamat, SQLITE3_TEXT);
        $stmt->bindValue(':kota', $kota, SQLITE3_TEXT);
        $stmt->bindValue(':nohp', $nohp, SQLITE3_TEXT);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id, ?string $nama, ?string $alamat, ?string $kota, ?string $nohp
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('alamat', $alamat, SQLITE3_TEXT)
            ->add('kota', $kota, SQLITE3_TEXT)
            ->add('nohp', $nohp, SQLITE3_TEXT);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE pelanggan SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}