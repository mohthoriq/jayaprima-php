<?php

//include_once ROOTDIR . 'class/DBHutangPembelian.php';

class VHutangPembelian{
    public int $id;
    public int $id_supplier;
    public string $nama_supplier;
    public string $no_faktur;
    public int $nominal;
    public int $tanggal_beli;
    public int $status_lunas;
    public string $txt_status_lunas;
    public int $tanggal_lunas;
    public int $id_akun_pj;
    public string $nama_akun;

    public function __construct(array $arr){
        [
            $this->id,
            $this->id_supplier,
            $this->nama_supplier,
            $this->no_faktur,
            $this->nominal,
            $this->tanggal_beli,
            $this->status_lunas,
            $this->txt_status_lunas,
            $this->tanggal_lunas,
            $this->id_akun_pj,
            $this->nama_akun,
        ] = $arr;
    }

    static public function cast($obj): VHutangPembelian {
        return $obj;
    }

    static public function dataTables() : array{
        $deadLineDays = $GLOBALS['dbUserInfo']->deadLineHutangPbDays;

        return array(
            'id',
            array(
                array('db' =>'tanggal_beli', 'dt' => 0, 'formatter' => function($d, $row){
                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'nominal', 'dt' => 1, 'formatter' => function($d, $row){
                    return number_format($d,0,'','.');
                }),
                array('db' =>'txt_status_lunas', 'dt' => 2, 'formatter' => function($d, $row){
                    return ($row['status_lunas'] == 1)
                        ? "<p class='txt_green'>$d</p>"
                        : "<p class='txt_red'>$d</p>";
                }),
                array('db' =>'no_faktur', 'dt' => 3),
                array('db' =>'nama_supplier', 'dt' => 4),
                array('db' =>'tanggal_lunas', 'dt' => 5, 'formatter' => function($d, $row) use($deadLineDays) {
                    if($d == null) {
                        $nowD = time();
                        $deadD = strtotime("+$deadLineDays day", ((int) $row['tanggal_beli'])/1000);
                        $diffD = $deadD - $nowD;
                        $remainDays = round($diffD / (60 * 60 * 24));

                        $class = ($remainDays > 10)? 'txt_green' : 'txt_red';

                        return "<p class='$class'>(Jatuh tempo dalam $remainDays hari)</p>";
                    }

                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'nama_akun', 'dt' => 6, 'formatter' => function($d, $row){
                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 7, 'formatter' => function($d, $row){
                    $id = $d;
                    $btnDebtCtrl = ($row['status_lunas'] == 1)
                        ? "<button class='btn btn-dark btnVHutangPembelianDebtCtrl' value='$id'>Hutang</button>"
                        : "<button class='btn btn-primary btnVHutangPembelianDebtCtrl' value='$id'>Bayar</button>";

                    return "
                        <button class='btn btn-warning btnVHutangPembelianEdit' value='$id'>Edit</button>
                        $btnDebtCtrl
                    ";
                }),
                array('db' =>'id', 'dt' => 8),
                array('db' =>'id_supplier', 'dt' => 9),
                array('db' =>'nominal', 'dt' => 10),
                array('db' =>'tanggal_beli', 'dt' => 11),
                array('db' =>'status_lunas', 'dt' => 12),
                array('db' =>'id_akun_pj', 'dt' => 13),
            )
        );
    }
}

class DBVHutangPembelian extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_hutang_pembelian
            AS 
                SELECT 
                    h.id AS id, 
                    h.id_supplier AS id_supplier,
                    s.nama AS nama_supplier,
                    h.no_faktur AS no_faktur,
                    h.nominal AS nominal,
                    h.tanggal_beli AS tanggal_beli,
                    h.status_lunas AS status_lunas,
                    CASE h.status_lunas
                        WHEN 1 THEN 'Lunas'
                        ELSE 'Hutang'   
                    END AS txt_status_lunas,
                    h.tanggal_lunas AS tanggal_lunas,
                    h.id_akun_pj AS id_akun_pj,
                    a.nama AS nama_akun
                FROM hutang_pembelian AS h
                INNER JOIN supplier AS s 
                    ON h.id_supplier = s.id
                INNER JOIN akun AS a
                    ON h.id_akun_pj = a.id
                ORDER BY
                    h.status_lunas ASC, 
                    h.tanggal_beli ASC;
        ");
    }

    public function get(int $id): ?VHutangPembelian {
        $stmt = $this->db->prepare('SELECT * FROM v_hutang_pembelian WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VHutangPembelian($result);
    }
}