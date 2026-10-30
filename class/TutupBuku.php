<?php

class TutupBuku{
    public static $TIMER_S = 10;

    public string $oldDBPath;
    public string $newDBPath;

    public function start(string $oldDBName, string $newDBName, callable $progressCallback){
        $this->oldDBPath = DBDIR . $oldDBName;
        $this->newDBPath = DBDIR . $newDBName;

        $title = 'Sistem dalam proses Tutup Buku';

        $this->copyDB(function ($progress) use ($title, $progressCallback){
            ($progressCallback)($title, 'Tahap 1/2 - Sedang mengcopy data', $progress . '%');

            //sleep(1);
        });

        $this->resetDB(function ($progress, $errMsg) use ($title, $progressCallback){
            if($errMsg != null) unlink($this->newDBPath);

            ($progressCallback)(
                $title,
                'Tahap 2/2 - ' . (($errMsg != null)? $errMsg : 'Sedang menyiapkan data'),
                $progress . '%'
            );

            //sleep(1);
        });
    }

    private function copyDB($progressCallback){
        $lastTime = time();

        $filesize = filesize($this->oldDBPath);

        $inFile = fopen($this->oldDBPath, 'r');
        $outFile = fopen($this->newDBPath, 'w');

        $read_bytes = 0;
        while(!feof($inFile)) {
            $buffer = fread($inFile, 2048);
            fwrite($outFile, $buffer);

            $read_bytes += 2048;

            if((time() - $lastTime) >= self::$TIMER_S){
                //Use $filesize as calculated earlier to get the progress percentage
                $progress = min(100, 100 * $read_bytes / $filesize);
                ($progressCallback)($progress);
            }
        }
        fclose($inFile);
        fclose($outFile);
    }

    private function resetDB($progressCallback): bool {
        $db = new SQLite3($this->newDBPath);

        $idAkunPj = (Akun::cast($_SESSION['akun']))->id;
        $tanggal = intval(microtime(true)*1000);

        /** @noinspection SqlWithoutWhere */
        $queryList = array(
            //hapus semua antrian printer
            'DELETE FROM antrian_printer',

            //hapus hutang_pembelian yg lunas
            'DELETE FROM hutang_pembelian WHERE status_lunas = 1',

            //Hapus semua Akun nonaktif yang tidak terikat dalam faktur penjualan belum lunas
            'DELETE FROM akun WHERE isActive = 0 AND id NOT IN
            (
                SELECT 
                    fp.id_akun_pj AS id_akun
                FROM faktur_penjualan AS fp
                WHERE fp.sisa < 0
                GROUP BY id_akun
            )
            ',

            //SUM semua brg_masuk berdasarkan id_produk dan qty terkecil.
            //final_qty adalah qty terkecil, id_satuan_brg_produk adalah satuan produk terkecil
            "CREATE TEMPORARY TABLE temp_brg_masuk AS
            SELECT
                $tanggal AS tanggal,
                id_produk AS id_produk,
                SUM(final_qty) AS qty,
                id_satuan_brg_produk AS id_satuan_brg,
                $idAkunPj AS id_akun_pj
            FROM v_brg_masuk
            GROUP BY id_produk;

            --Truncate brg_masuk, kemudian insert hasil SUM
            DELETE FROM brg_masuk;
            INSERT INTO brg_masuk (tanggal, id_produk, qty, id_satuan_brg, id_akun_pj) 
            SELECT tbm.tanggal, tbm.id_produk, tbm.qty, tbm.id_satuan_brg, tbm.id_akun_pj FROM temp_brg_masuk AS tbm;
            ",

            //SUM semua brg_keluar faktur yg lunas,
            //termasuk id_faktur_penjualan yg null, karena hasil tutup buku sebelumnya.
            'CREATE TEMPORARY TABLE temp_brg_keluar AS
            SELECT 
                null AS id_faktur_penjualan,
                bk.id_produk AS id_produk,
                SUM(bk.final_qty) AS qty,
                bk.id_satuan_brg_produk AS id_satuan_brg_qty,
                0 AS modal,
                0 AS harga_satuan,
                \'0\' AS persen_disc,
                null AS bonus_qty,
                null AS id_satuan_brg_bonus_qty,
                0 AS harga_total
            FROM v_brg_keluar AS bk
            LEFT JOIN faktur_penjualan AS fp
                ON fp.id = bk.id_faktur_penjualan
            WHERE bk.id_faktur_penjualan IS NULL OR fp.sisa >= 0
            GROUP BY id_produk;

            -- Hapus semua brg_keluar faktur yg lunas, kemudian INSERT hasil SUM ke brg_keluar
            DELETE FROM brg_keluar WHERE id IN 
            (
                SELECT 
                    bk.id AS id
                FROM brg_keluar AS bk
                LEFT JOIN faktur_penjualan AS fp
                    ON fp.id = bk.id_faktur_penjualan
                WHERE bk.id_faktur_penjualan IS NULL OR fp.sisa >= 0
            );
            INSERT INTO brg_keluar (id_faktur_penjualan, id_produk, qty, id_satuan_brg_qty, modal, harga_satuan, persen_disc, bonus_qty, id_satuan_brg_bonus_qty, harga_total)
            SELECT tbk.id_faktur_penjualan, tbk.id_produk, tbk.qty, tbk.id_satuan_brg_qty, tbk.modal, tbk.harga_satuan, tbk.persen_disc, tbk.bonus_qty, tbk.id_satuan_brg_bonus_qty, tbk.harga_total FROM temp_brg_keluar AS tbk;
            ',

            //hapus semua faktur_penjualan yg lunas
            'DELETE FROM faktur_penjualan WHERE sisa >= 0'
        );

        $ret = $db->exec('BEGIN TRANSACTION;');
        if($ret){
            $len = sizeof($queryList);
            for ($i = 0; $i < $len; $i++) {
                $ret = $db->exec($queryList[$i]);

                $progress = min(100, 100 * $i / ($len-1));
                if(!$ret) {
                    ($progressCallback)($progress, "Error tahap $i");
                    break;
                }
                ($progressCallback)($progress, null);
            }

            if($ret) $db->exec('COMMIT;');
            else $db->exec('ROLLBACK;');

            if($ret) {
                $ret = $db->exec('VACUUM;');
                if(!$ret) ($progressCallback)(100, "Error meringkas data");
            }
        }

        return $ret;
    }
}
