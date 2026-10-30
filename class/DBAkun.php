<?php

class Akun{
    public int $id;
    public string $nama;
    public ?string $alamat;
    public ?string $nohp;
    public string $username;
    public string $password;
    public ?string $foto;
    public string $level_akses;
    public int $isActive;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->alamat,
            $this->nohp,
            $this->username,
            $this->password,
            $this->foto,
            $this->level_akses,
            $this->isActive,
        ] = $arr;
    }

    static public function cast($obj): Akun{
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'foto', 'dt' => 0, 'formatter' => function ($d, $row) {
                    $fotoProfilUrl = HOST . 'api.php?akun=' . urlencode(json_encode(array('foto' => $row['id'])));

                    return "
                        <a href=\"javascript:;\" class=\"user-profile\" aria-expanded=\"false\">
                            <img src=\"$fotoProfilUrl\" alt=\"\">
                        </a>
                    ";
                }),
                array('db' =>'nama', 'dt' => 1),
                array('db' =>'isActive', 'dt' => 2, 'formatter' => function ($d, $row) {
                    return ($d == 1)
                        ? '<p class="txt_green">Aktif</p>'
                        : '<p class="txt_gray">NonAktif</p>';
                }),
                array('db' =>'level_akses', 'dt' => 3, 'formatter' => function ($d, $row) {
                    return ($d == 0)? 'Admin Super' : 'Admin';
                }),
                array('db' =>'nohp', 'dt' => 4),
                array('db' =>'alamat', 'dt' => 5),
                array('db' =>'username', 'dt' => 6),
                array('db' =>'password', 'dt' => 7),
                array('db' =>'id', 'dt' => 8, 'formatter' => function($d, $row){
                    if((Akun::cast($_SESSION['akun']))->level_akses > 0) return '';

                    $id = $d;
                    $btnAktifCtrl = '';
                    if((Akun::cast($_SESSION['akun']))->id != $d){
                        $btnAktifCtrl = ($row['isActive'] == 1)
                            ? "<button class='btn btn-dark btnAkunActiveCtrl' value='$id'>Nonaktifkan</button>"
                            : "<button class='btn btn-primary btnAkunActiveCtrl' value='$id'>Aktifkan</button>";
                    }

                    return "
                        <button class='btn btn-warning btnAkunEdit' value='$id'>Edit</button>
                        $btnAktifCtrl
                    ";
                }),
                array('db' =>'id', 'dt' => 9),
                array('db' =>'isActive', 'dt' => 10),
            )
        );
    }
}

class DBAkun extends Database {
    public function __construct(bool $isLoadCurrDBYear = false){
        $DBYEAR = null;
        if($isLoadCurrDBYear){
            //login dengan load db terbaru
            if(is_array($GLOBALS['dbList']) && sizeof($GLOBALS['dbList']) > 0){
                $DBYEAR = $GLOBALS['dbList'][0];
            }
        }

        parent::__construct($DBYEAR);

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS akun (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT,
                alamat TEXT,
                nohp TEXT,
                username TEXT UNIQUE,
                password TEXT,
                foto BLOB,
                level_akses INTEGER,
                isActive INTEGER DEFAULT 1
            );
        ");
    }

    public function getWhereUsernamePass(string $username, string $password): ?Akun {
        $stmt = $this->db->prepare('SELECT * FROM akun WHERE username=:username AND password=:password');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $password, SQLITE3_TEXT);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new Akun($result);
    }

    public function get(int $id): ?Akun {
        $stmt = $this->db->prepare('SELECT * FROM akun WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new Akun($result);
//        while ($res= $result->fetchArray(SQLITE3_NUM)){
//            array_push($data, $res);
//        }
    }

    public function isUsernameExists(string $username): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM akun WHERE username=:username');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);

        $result = $stmt->execute();
        $result = $result->fetchArray(SQLITE3_NUM);

        return (is_array($result) && $result[0] > 0);
    }

    public function getFoto(int $id): ?string {
        $result = $this->get($id);

        return ($result == false)? null : $result->foto;
    }

    public function insert (
        string $nama, string $alamat, string $nohp, string $username, string $password, ?string $foto, int $level_akses
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO akun (
                nama,
                alamat,
                nohp,
                username,
                password,
                foto,
                level_akses
            ) VALUES (
                :nama,
                :alamat,
                :nohp,
                :username,
                :password,
                :foto,
                :level_akses 
            );
        ');
        $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':alamat', $alamat, SQLITE3_TEXT);
        $stmt->bindValue(':nohp', $nohp, SQLITE3_TEXT);
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', md5($password), SQLITE3_TEXT);
        $stmt->bindValue(':level_akses', $level_akses, SQLITE3_INTEGER);
        $stmt->bindValue(':foto', $foto, ($foto == null)? SQLITE3_NULL : SQLITE3_BLOB);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id, ?string $nama, ?string $alamat, ?string $nohp, ?string $username, ?string $password, ?string $foto,
        ?int $level_akses, ?int $isActive
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('alamat', $alamat, SQLITE3_TEXT)
            ->add('nohp', $nohp, SQLITE3_TEXT)
            ->add('username', $username, SQLITE3_TEXT)
            ->add('password', ($password === null)? null : md5($password), SQLITE3_TEXT)
            ->add('level_akses', $level_akses, SQLITE3_INTEGER)
            ->add('isActive', $isActive, SQLITE3_INTEGER)
            ->add('foto', $foto, SQLITE3_BLOB);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE akun SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}