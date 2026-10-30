<?php

class VPendapatanHarian{
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

    static public function cast($obj): VPendapatanHarian {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'tanggal',
            array(
                array('db' =>'tanggal', 'dt' => 0, 'formatter' => function($d, $row){
                    return date("j M Y", strtotime($d));
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
            )
        );
    }
}

class DBVPendapatanHarian extends Database {
    public function __construct($timezoneOffset = "'localtime'"){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_pendapatan_harian
            AS 
            SELECT
                rbk.dmy AS tanggal,
                ROUND(rbk.kotor) AS kas_kotor,
                ROUND(rbk.phutang_kotor) AS phutang_kotor,

                ROUND(rbk.kotor - rbk.bersih) AS kas_modal,
                ROUND(rbk.phutang_kotor - rbk.phutang_bersih) AS phutang_modal,

                ROUND(rbk.bersih) AS kas_bersih,
                ROUND(rbk.phutang_bersih) AS phutang_bersih
            FROM
            (    
                SELECT
                    tbk.dmy AS dmy,
                    SUM(tbk.kotor * tbk.percent) AS kotor,
                    SUM(tbk.bersih * tbk.percent) AS bersih,
                    SUM(tbk.kotor * (1.0-tbk.percent)) AS phutang_kotor,
                    SUM(tbk.bersih * (1.0-tbk.percent)) AS phutang_bersih
                FROM (
                    SELECT
                        strftime('%Y-%m-%d', fp.tanggal/1000,  'unixepoch',  $timezoneOffset) AS dmy,
                        bk.harga_total AS kotor,
                        IFNULL(bk.untung_bersih, 0) AS bersih,
                        CASE WHEN (fp.bayar - fp.subtotal) < 0 THEN 1.0 - (ABS(fp.bayar - fp.subtotal)/CAST(fp.subtotal AS FLOAT)) ELSE 1.0 END AS percent
                        
                    FROM v_brg_keluar AS bk
                    INNER JOIN faktur_penjualan AS fp
                        ON bk.id_faktur_penjualan = fp.id    
                ) AS tbk          
                GROUP BY dmy                                 
            ) AS rbk
            ORDER BY rbk.dmy DESC
        ");
    }
}