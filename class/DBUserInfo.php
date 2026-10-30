<?php

class UserInfo{
    public int $id;
    public ?string $nama;
    public ?string $alamat;
    public ?string $info1;
    public ?string $info2;
    public ?string $logo;
    public int $deadLineHutangPbDays;
    public int $deadLineHutangPjDays;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->alamat,
            $this->info1,
            $this->info2,
            $this->logo,
            $this->deadLineHutangPbDays,
            $this->deadLineHutangPjDays,
        ] = $arr;
    }
}

class DBUserInfo extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS user_info (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama VARCHAR(30),
                alamat VARCHAR(30),
                info1 VARCHAR(30),
                info2 VARCHAR(30),
                logo BLOB,
                deadLineHutangPbDays INTEGER DEFAULT 90,
                deadLineHutangPjDays INTEGER DEFAULT 90
            );
        ");
    }

    public function get(int $id): ?UserInfo {
        $stmt = $this->db->prepare('SELECT * FROM user_info WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new UserInfo($result);
    }

    public function getLogo(int $id): ?string {
        $result = $this->get($id);

        return ($result == false)? null : $result->logo;
    }

    public function update (
        int $id,
        ?string $nama,
        ?string $alamat,
        ?string $info1,
        ?string $info2,
        ?string $logo,
        ?int $deadLineHutangPbDays,
        ?int $deadLineHutangPjDays
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('nama', $nama, SQLITE3_TEXT)
            ->add('alamat', $alamat, SQLITE3_TEXT)
            ->add('info1', $info1, SQLITE3_TEXT)
            ->add('info2', $info2, SQLITE3_TEXT)
            ->add('logo', $logo, SQLITE3_BLOB)
            ->add('deadLineHutangPbDays', $deadLineHutangPbDays, SQLITE3_INTEGER)
            ->add('deadLineHutangPjDays', $deadLineHutangPjDays, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE user_info SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}