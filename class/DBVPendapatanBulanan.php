<?php

class VPendapatanBulanan{
    public string $tanggal;
    public int $kas_kotor;
    public int $phutang_kotor;

    public int $kas_modal;
    public int $phutang_modal;

    public int $kas_bersih;
    public int $phutang_bersih;

    public function __construct(array $arr){
        [
            $this->tanggal,
            $this->kas_kotor,
            $this->phutang_kotor,

            $this->kas_modal,
            $this->phutang_modal,

            $this->kas_bersih,
            $this->phutang_bersih,
        ] = $arr;
    }

    static public function cast($obj): VPendapatanBulanan {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'Ym',
            array(
                array('db' =>'Ym', 'dt' => 0, 'formatter' => function($d, $row){
                    return date("M Y", strtotime($d));
                }),
                array('db' =>'kas_kotor', 'dt' => 1, 'formatter' => function($d, $row){
                    $kas = number_format($d,0,'','.');
                    $pHutang = number_format($row['phutang_kotor'],0,'','.');
                    $total = number_format($d + $row['phutang_kotor'],0,'','.');

                    return "<div class='red'>Hutang Pelanggan: $pHutang</div><div class='green'>Kas: $kas</div><div class='blue'>Total: $total</div>";
                }),
                array('db' =>'kas_modal', 'dt' => 2, 'formatter' => function($d, $row){
                    $kas = number_format($d,0,'','.');
                    $pHutang = number_format($row['phutang_modal'],0,'','.');
                    $total = number_format($d + $row['phutang_modal'],0,'','.');

                    return "<div class='red'>Hutang Pelanggan: $pHutang</div><div class='green'>Kas: $kas</div><div class='blue'>Total: $total</div>";
                }),
                array('db' =>'kas_bersih', 'dt' => 3, 'formatter' => function($d, $row){
                    $kas = number_format($d,0,'','.');
                    $pHutang = number_format($row['phutang_bersih'],0,'','.');
                    $total = number_format($d + $row['phutang_bersih'],0,'','.');

                    return "<div class='red'>Hutang Pelanggan: $pHutang</div><div class='green'>Kas: $kas</div><div class='blue'>Total: $total</div>";
                }),

                array('db' =>'phutang_kotor', 'dt' => 4 ),
                array('db' =>'phutang_modal', 'dt' => 5 ),
                array('db' =>'phutang_bersih', 'dt' => 6 ),

                array('db' =>'kas_kotor', 'dt' => 7 ),
                array('db' =>'kas_modal', 'dt' => 8 ),
                array('db' =>'kas_bersih', 'dt' => 9 ),
            )
        );
    }
}

class DBVPendapatanBulanan extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_pendapatan_bulanan
            AS 
            SELECT 
                strftime('%Y-%m', ph.tanggal) AS Ym,

                SUM(ph.kas_kotor) AS kas_kotor,
                SUM(ph.phutang_kotor) AS phutang_kotor,

                SUM(ph.kas_modal) AS kas_modal,
                SUM(ph.phutang_modal) AS phutang_modal,

                SUM(ph.kas_bersih) AS kas_bersih,
                SUM(ph.phutang_bersih) AS phutang_bersih

            FROM v_pendapatan_harian AS ph
            GROUP BY Ym
            ORDER BY Ym
        ");
    }
}