<?php

class AntrianPrinter{
    public int $id;
    public int $tanggal;
    public int $status;
    public int $template;
    public int $id_faktur_penjualan;

    public function __construct(array $arr){
        [
            $this->id,
            $this->tanggal,
            $this->status,
            $this->template,
            $this->id_faktur_penjualan,
        ] = $arr;
    }

    static public function cast($obj): AntrianPrinter {
        return $obj;
    }
}

class DBAntrianPrinter extends Database {
    public function __construct(){
        parent::__construct();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS antrian_printer (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tanggal INTEGER,
                status INTEGER,
                template INTEGER,
                id_faktur_penjualan INTEGER
            );
        ");
    }

    public function get(int $id): ?AntrianPrinter {
        $stmt = $this->db->prepare('SELECT * FROM antrian_printer WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        return ($result == false)? null : new AntrianPrinter($result);
    }

    public function consume(): array {
        $stmt = $this->db->prepare('SELECT * FROM antrian_printer ORDER BY tanggal LIMIT 1');

        $result = $stmt->execute();

        $result = $result->fetchArray(SQLITE3_NUM);

        $p = ($result == false)? null : new AntrianPrinter($result);

        $ret = array();
        if($p != null){
            function digitSeparator(int $d): string {
                return number_format($d,0,'','.');
            }

            include_once ROOTDIR . 'class/DBVFakturPenjualan.php';
            $fp = (new DBVFakturPenjualan())->get($p->id_faktur_penjualan);

            include_once ROOTDIR . 'class/DBVBarangKeluar.php';
            $rawListBrg = (new DBVBarangKeluar())->getByIdFaktur($p->id_faktur_penjualan);
            $listBrg = array();
            /** @type $brg VBarangKeluar*/
            foreach ($rawListBrg as $brg){
                array_push($listBrg, array(
                    'nama' => $brg->nama_produk,
                    'qty' => $brg->raw_qty . $brg->singkatan_satuan_brg_raw_qty,
                    'bonus' => $brg->bonus_qty . $brg->singkatan_satuan_brg_bonus_qty,
                    'harga' => digitSeparator((($brg->final_qty/$brg->raw_qty) * $brg->harga_satuan)),
                    'disc' => $brg->persen_disc . '%',
                    'jumlah' => digitSeparator($brg->harga_total),
                ));
            }

            include_once ROOTDIR . 'class/DBUserInfo.php';
            $pengirim = (new DBUserInfo())->get(1);

            include_once ROOTDIR . 'class/DBPelanggan.php';
            $penerima = (new DBPelanggan())->get($fp->id_pelanggan);

            $tglTerbit = date('d-M-Y', $fp->tanggal/1000);//11-Okt-2022

            include_once ROOTDIR . 'function/formatter.php';
            $terbilang = '';
            Nominal::terbilang($fp->total, $terbilang);
            $terbilang .= 'Rupiah';
            [$tr1, $tr2, $tr3] = txtSpliter($terbilang, 42, 3);


            $ret = array(
                'id' => $p->id,
                'pengirim' => array(
                    'nama' => ($p->template == 0)? '' : $pengirim->nama,
                    'alamat' => ($p->template == 0)? '' : $pengirim->alamat,
                    'info1' => ($p->template == 0)? '' : $pengirim->info1,
                    'info2' => ($p->template == 0)? '' : $pengirim->info2,
                ),
                'penerima'=> array(
                    'nama' => $penerima->nama,
                    'alamat' => $penerima->alamat,
                    'kota' => $penerima->kota,
                ),
                'faktur' => array(
                    'tanggalTerbit' => $tglTerbit,
                    'noFaktur' => $fp->kode,
                    'subTotal' => digitSeparator($fp->subtotal),
                    'ppn' => $fp->persen_ppn . '%',
                    'totalTerbilangRow1' => strtoupper($tr1),
                    'totalTerbilangRow2' => strtoupper($tr2),
                    'totalTerbilangRow3' => strtoupper($tr3),
                    'total' => digitSeparator($fp->total),
                    'bayar' => digitSeparator($fp->bayar),
                    'sisa' => digitSeparator($fp->sisa),

                    'listBrg' => $listBrg,
                ),
            );


            //update status sedang cetak
            (new DBAntrianPrinter())->update($p->id, null, 1, null, null);
        }

        return $ret;
    }


    public function insert (
        int $tanggal,
        int $status,
        int $template,
        int $id_faktur_penjualan
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO antrian_printer (
                tanggal,
                status,
                template,
                id_faktur_penjualan
            ) VALUES (
                :tanggal,
                :status,
                :template,
                :id_faktur_penjualan
            );
        ');
        $stmt->bindValue(':tanggal', $tanggal, SQLITE3_INTEGER);
        $stmt->bindValue(':status', $status, SQLITE3_INTEGER);
        $stmt->bindValue(':template', $template, SQLITE3_INTEGER);
        $stmt->bindValue(':id_faktur_penjualan', $id_faktur_penjualan, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function update (
        int $id,
        ?int $tanggal,
        ?int $status,
        ?int $template,
        ?int $id_faktur_penjualan
    ): bool {
        $builder = new SQLUpdateValue();
        $builder
            ->add('tanggal', $tanggal, SQLITE3_INTEGER)
            ->add('status', $status, SQLITE3_INTEGER)
            ->add('template', $template, SQLITE3_INTEGER)
            ->add('id_faktur_penjualan', $id_faktur_penjualan, SQLITE3_INTEGER);

        $sqlVal = $builder->build();

        $stmt = $this->db->prepare("
            UPDATE antrian_printer SET $sqlVal WHERE id=:id;
        ");

        $builder->bindParam($stmt);

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }

    public function delete (int $id): bool {
        $stmt = $this->db->prepare("
            DELETE FROM antrian_printer WHERE id=:id;
        ");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return (bool) $stmt->execute();
    }
}
