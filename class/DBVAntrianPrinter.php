<?php

class VAntrianPrinter{
    public int $id;
    public int $tanggal;
    public int $status;
    public string $txt_status;
    public int $template;
    public string $txt_template;
    public string $kode_faktur;
    public string $nama_pelanggan;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->status,
            $this->txt_status,
            $this->template,
            $this->txt_template,
            $this->kode_faktur,
            $this->nama_pelanggan,
        ] = $arr;
    }

    static public function cast($obj): VAntrianPrinter {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'tanggal', 'dt' => 0, 'formatter' => function($d, $row){
                    $seconds = $d / 1000;
                    return date("j M Y", $seconds);
                }),
                array('db' =>'txt_status', 'dt' => 1, 'formatter' => function($d, $row){
                    return ($row['status'] == 1)
                        ? "<p class='txt_green'>$d</p>"
                        : "<p class='txt_yellow'>$d</p>";
                }),
                array('db' =>'txt_template', 'dt' => 2),
                array('db' =>'kode_faktur', 'dt' => 3),
                array('db' =>'nama_pelanggan', 'dt' => 4),
                array('db' =>'id', 'dt' => 5, 'formatter' => function($d, $row){
                    if($row['status'] == 1) return '';

                    $id = $d;
                    return "
                        <button class='btn btn-warning btnVAntrianPrinterHapus' value='$id'>Batal Cetak</button>
                    ";
                }),

                array('db' =>'id', 'dt' => 6),
                array('db' =>'status', 'dt' => 7),
            )
        );
    }
}

class DBVAntrianPrinter extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_antrian_printer
            AS 
            SELECT
                ap.id AS id,
                ap.tanggal AS tanggal,
                ap.status AS status,

                CASE ap.status
                    WHEN 1 THEN 'Sedang Mencetak'
                    ELSE 'Menunggu'
                END AS txt_status,  

                ap.template AS template,

                CASE ap.template
                    WHEN 1 THEN 'Dengan Kop Faktur'
                    ELSE 'Tanpa Kop Faktur'
                END AS txt_template,

                fp.kode AS kode_faktur,
                fp.nama_pelanggan AS nama_pelanggan

            FROM antrian_printer AS ap
            INNER JOIN v_faktur_penjualan AS fp
                ON ap.id_faktur_penjualan = fp.id
            ORDER BY ap.tanggal ASC;
        ");
    }

    public function get(int $id): ?VAntrianPrinter {
        $stmt = $this->db->prepare('SELECT * FROM v_antrian_printer WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VAntrianPrinter($result);
    }
}