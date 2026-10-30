<?php

class SQLValuePair{
    public string $fieldName;
    /* @type mixed|null*/
    public $value;
    public int $type;

    function __construct(string $fieldName, $value, int $type){
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->type = $type;
    }
}

class SQLUpdateValue{
    /* @type SQLValuePair[]*/
    private $list = array();

    function add(string $fieldName, $value, int $type) : SQLUpdateValue{
        if($value !== null || $type == SQLITE3_NULL){
            array_push($this->list, new SQLValuePair(
                $fieldName, $value, $type
            ));
        }
        return $this;
    }

    function build(): string {
        $sqlVal = '';
        $len = sizeof($this->list);

        for($i=0; $i<$len; $i++){
            $p = $this->list[$i];

            $sqlVal = $sqlVal . $p->fieldName . '=:' . $p->fieldName;

            if($i+1 < $len) $sqlVal = $sqlVal . ', ';
        }

        return $sqlVal;
    }

    function bindParam(SQLite3Stmt &$stmt){
        foreach ($this->list as $p){
            $stmt->bindValue(':' . $p->fieldName, $p->value, $p->type);
        }
    }
}

abstract class Database {
    public SQLite3 $db;

    public function __construct(?int $DBYEAR = null){
        if($GLOBALS['DBCONN'] == null || !isDBConnAlive()) {
            $GLOBALS['DBCONN'] = new SQLite3(DBDIR . ($DBYEAR ?? $_SESSION['DBYEAR']));
            $GLOBALS['DBCONN']->busyTimeout(5000);
        }
        $this->db = $GLOBALS['DBCONN'];

//        if(!$isDbExists){
//            $this->db->exec("
//                CREATE TABLE IF NOT EXISTS supplier (
//                    id INTEGER PRIMARY KEY,
//                    nama TEXT,
//                    alamat TEXT,
//                    nohp TEXT
//                );
//            ");
//            $this->db->exec("
//                CREATE TABLE IF NOT EXISTS pelanggan (
//                    id INTEGER PRIMARY KEY,
//                    nama TEXT,
//                    alamat TEXT,
//                    kota TEXT,
//                    nohp TEXT
//                );
//            ");
//            $this->db->exec("
//                CREATE TABLE IF NOT EXISTS antrian_printer (
//                    tanggal INTEGER PRIMARY KEY,
//                    id_faktur_penjualan INTEGER
//                );
//            ");
//            $this->db->exec("
//                CREATE TABLE IF NOT EXISTS kategori_produk (
//                    id INTEGER PRIMARY KEY,
//                    nama TEXT
//                );
//            ");
//
//        }
    }

    public static function getTimeZoneOffset(): array {
        $off = explode(':', date('P'));
        $isHourMinus = strpos('-', $off[0]) !== false;

        if($isHourMinus) $off[1] = '-' . $off[1];
        else $off[1] = '+' . $off[1];

        return $off;
    }
}