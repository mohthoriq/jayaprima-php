<?php

class VProduk{
    public int $id;
    public string $nama;
    public int $id_kategori_brg;
    public string $nama_kategori_brg;
    public int $id_satuan_brg;
    public string $nama_satuan_brg;
    public string $singkatan_satuan_brg;
    public int $modal;
    public ?string $disc_dari_supplier;
    public int $id_akun_pj;
    public string $nama_akun;
    public string $produk_bundle;

    public function __construct(array $arr){
        [
            $this->id,
            $this->nama,
            $this->id_kategori_brg,
            $this->nama_kategori_brg,
            $this->id_satuan_brg,
            $this->nama_satuan_brg,
            $this->singkatan_satuan_brg,
            $this->modal,
            $this->disc_dari_supplier,
            $this->id_akun_pj,
            $this->nama_akun,
            $this->produk_bundle,
        ] = $arr;
    }

    static public function cast($obj): VProduk {
        return $obj;
    }

    static public function dataTables() : array{
        return array(
            'id',
            array(
                array('db' =>'nama', 'dt' => 0),
                array('db' =>'modal', 'dt' => 1, 'formatter' => function($d, $row){
                    $fd = number_format($d,0,'','.');
                    return "$fd <b>/</b> ${row['nama_satuan_brg']}";
                }),
                array('db' =>'nama_satuan_brg', 'dt' => 2),
                array('db' =>'nama_kategori_brg', 'dt' => 3),
                array('db' =>'produk_bundle', 'dt' => 4, 'formatter' => function($d, $row){
                    $json = json_decode($d);
                    $html = '<table>';

                    foreach ($json as $j){
                        $namaSatuanBrg = $j->nama_satuan_brg;
                        $qty = $j->qty;
                        $html .= "<tr><td>1&nbsp;$namaSatuanBrg</td><td>&nbsp;=&nbsp;</td><td>$qty&nbsp;</td><td>${row['nama_satuan_brg']}</td></tr>";
                    }

                    $html .= '</table>';
                    return $html;
                }),
                array('db' =>'disc_dari_supplier', 'dt' => 5, 'formatter' => function($d, $row){
                    $dMsg = '';
                    if($d != null && strpos($d, '+') !== false){
                        $calc = function ($d1, $d2) { return $d1 + $d2 - ($d1 * $d2 / 100); };
                        $dList = explode('+', $d);
                        $aDisc = ($calc)((float)(str_replace(',','.', $dList[0])), (float)(str_replace(',','.', $dList[1])));
                        for($i=2; $i<sizeof($dList); $i++){
                            $aDisc = ($calc)($aDisc, (float)(str_replace(',','.', $dList[$i])));
                        }

                        $aDisc = (float)sprintf("%.8f", $aDisc);
                        $decimals = strlen(substr(strrchr((string)$aDisc, "."), 1));

                        $p = number_format($aDisc, $decimals, ',','.');
                        $dMsg = "<br/><div style='font-size: 12px'>Setara dengan $p%</div>";
                    }

                    return ($d == null)? '' : "$d%$dMsg";
                }),
                array('db' =>'nama_akun', 'dt' => 6, 'formatter' => function($d, $row){
                    $urlProfil = HOST . 'accounts.php?id=' . $row['id_akun_pj'];
                    return "
                        <button type='button' class='btn btn-default' onclick=\"window.open('$urlProfil', '_blank').focus()\">$d</button>
                    ";
                }),
                array('db' =>'id', 'dt' => 7, 'formatter' => function($d, $row){
                    $id = $d;

                    return "
                        <button class='btn btn-warning btnVProdukEdit' value='$id'>Edit</button>
                    ";
                }),

                array('db' =>'id', 'dt' => 8),
                array('db' =>'id_kategori_brg', 'dt' => 9),
                array('db' =>'id_satuan_brg', 'dt' => 10),
                array('db' =>'singkatan_satuan_brg', 'dt' => 11),
                array('db' =>'id_akun_pj', 'dt' => 12),
                array('db' =>'modal', 'dt' => 13),
                array('db' =>'produk_bundle', 'dt' => 14),
                array('db' =>'disc_dari_supplier', 'dt' => 15, ),
            )
        );
    }
}

class DBVProduk extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE VIEW IF NOT EXISTS v_produk
            AS 
            SELECT 
                p.id AS id,
                p.nama AS nama,
                kb.id AS id_kategori_brg,
                kb.nama AS nama_kategori_brg,
                sb.id AS id_satuan_brg,
                sb.nama AS nama_satuan_brg,
                sb.singkatan AS singkatan_satuan_brg,
                p.modal AS modal,
                p.disc_dari_supplier AS disc_dari_supplier,
                a.id AS id_akun_pj,
                a.nama AS nama_akun,
                (
                    SELECT 
                        json_group_array( 
                            json_object(
                                'id', vpb.id, 
                                'id_produk', vpb.id_produk,
                                'id_satuan_brg', vpb.id_satuan_brg,
                                'nama_satuan_brg', vpb.nama_satuan_brg,
                                'qty', vpb.qty 
                            )
                        )
                    FROM v_produk_bundle AS vpb 
                    WHERE vpb.id_produk = p.id
                ) AS produk_bundle
            FROM produk AS p
            INNER JOIN kategori_brg AS kb
                ON p.id_kategori_brg = kb.id
            INNER JOIN satuan_brg AS sb
                ON p.id_satuan_brg = sb.id
            INNER JOIN akun AS a
                ON p.id_akun_pj = a.id;
        ");
    }

    public function get(int $id): ?VProduk {
        $stmt = $this->db->prepare('SELECT * FROM v_produk WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new VProduk($result);
    }

    public function autoComplete($query): object {
        include_once ROOTDIR . 'class/AutoCompleteResponse.php';
        $ret = new AutoCompleteResponse();

        $stmt = $this->db->prepare("SELECT id, nama FROM v_produk WHERE nama LIKE :query");
        $stmt->bindValue(':query', "%$query%", SQLITE3_TEXT);

        $result = $stmt->execute();

        while ($res= $result->fetchArray(SQLITE3_NUM)){
            array_push($ret->suggestions, new AutoCompleteSuggestions($res[0], $res[1]));
        }

        return $ret;
    }
}