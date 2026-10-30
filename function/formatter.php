<?php

class Nominal{
    private static function sebutanSatuan(int $nominals, string &$terbilang) {
        switch ($nominals) {
            case 0 : $terbilang .= "nol ";break;
            case 1 : $terbilang .= "satu ";break;
            case 2 : $terbilang .= "dua ";break;
            case 3 : $terbilang .= "tiga ";break;
            case 4 : $terbilang .= "empat ";break;
            case 5 : $terbilang .= "lima ";break;
            case 6 : $terbilang .= "enam ";break;
            case 7 : $terbilang .= "tujuh ";break;
            case 8 : $terbilang .= "delapan ";break;
            case 9 : $terbilang .= "sembilan ";break;
        }
    }
    private static function sebutanBelasan(int $nominals, string &$terbilang){
        switch($nominals){
            case 10 : $terbilang .= "sepuluh ";break;
            case 11 : $terbilang .= "sebelas ";break;
            default : {
                Nominal::terbilang($nominals % 10, $terbilang);
                $terbilang .= "belas ";
                break;
            }
        }
    }
    private static function sebutanPuluhan(int $nominals, string &$terbilang){
        Nominal::terbilang($nominals / 10, $terbilang);
        $terbilang .= "puluh ";

        $satuan = $nominals % 10;
        if($satuan > 0) {
            Nominal::terbilang($satuan, $terbilang);
        }
    }
    private static function sebutanRatusan(int $nominals, string &$terbilang){
        if(((int) ($nominals/100)) == 1){
            $terbilang .= "seratus ";
        }
        else {
            Nominal::terbilang($nominals / 100, $terbilang);
            $terbilang .= "ratus ";
        }
    }
    private static function sebutanRibuan(int $nominals, string &$terbilang){
        if(((int) ($nominals/1000)) == 1){
            $terbilang .= "seribu ";
        }
        else {
            Nominal::terbilang($nominals / 1000, $terbilang);
            $terbilang .= "ribu ";
        }
    }
    private static function sebutanJutaan(int $nominals, string &$terbilang){
        Nominal::terbilang($nominals / 1000000, $terbilang);
        $terbilang .= "juta ";
    }
    private static function sebutanMiliaran(int $nominals, string &$terbilang){
        Nominal::terbilang($nominals / 1000000000, $terbilang);
        $terbilang .= "miliar ";
    }
    private static function sebutanTriliunan(int $nominals, string &$terbilang){
        Nominal::terbilang($nominals / 1000000000000, $terbilang);
        $terbilang .= "triliun ";
    }
    private static function sebutanKuadriliunan(int $nominals, string &$terbilang){
        Nominal::terbilang($nominals / 1000000000000000, $terbilang);
        $terbilang .= "kuadriliun ";
    }

    public static function terbilang(int $nominalp, string &$terbilangParam): string {
        $nominal = $nominalp;
        if($nominal == 0) self::sebutanSatuan($nominal, $terbilangParam);
        else while(((int)($nominal)) > 0) {
            if($nominal < 10) {
                //satuan
                Nominal::sebutanSatuan($nominal, $terbilangParam);
                $nominal /= 10;
            }
            else if($nominal < 20) {
                //belasan
                Nominal::sebutanBelasan($nominal, $terbilangParam);
                $nominal /= 20;
            }
            else if($nominal < 100) {
                //puluhan
                Nominal::sebutanPuluhan($nominal, $terbilangParam);
                $nominal /= 100;
            }
            else if($nominal < 1000) {
                //ratusan
                Nominal::sebutanRatusan($nominal, $terbilangParam);
                $nominal %= 100;
            }
            else if($nominal < 1000000) {
                //ribuan
                Nominal::sebutanRibuan($nominal, $terbilangParam);
                $nominal %= 1000;
            }
            else if($nominal < 1000000000) {
                //juta
                Nominal::sebutanJutaan($nominal, $terbilangParam);
                $nominal %= 1000000;
            }
            else if($nominal < 1000000000000) {
                //miliar
                Nominal::sebutanMiliaran($nominal, $terbilangParam);
                $nominal %= 1000000000;
            }
            else if($nominal < 1000000000000000) {
                //triliun
                Nominal::sebutanTriliunan($nominal, $terbilangParam);
                $nominal %= 1000000000000;
            }
            else if($nominal < 1000000000000000000) {
                //kuadriliun
                Nominal::sebutanKuadriliunan($nominal, $terbilangParam);
                $nominal %= 1000000000000000;
            }
        }

        return $terbilangParam;
    }
}
function txtSpliter(string $word, int $maxCharLen, int $countResult): array {
    $ret = array();
    $words = explode(' ', $word);

    $i=0;
    $strLen=0;
    $str='';
    $retLen = 0;
    foreach ($words as $w){
        $wLen = strlen($w);
        if(($strLen + $wLen + 1) > $maxCharLen){
            array_push($ret, $str);
            $strLen=0;
            $str='';
            $i++;
        }

        $strLen = $strLen + $wLen + 1;
        $str .= $w;
        $str .= ' ';
        $retLen = $retLen + $wLen + 1;

        if($i >= $countResult) break;
    }
    if($i < $countResult && $str != ''){
        array_push($ret, $str);
    }

    if(((strlen($word)+1) - $retLen) > 0){
        //overload
        $ret[sizeof($ret)-1] = substr($ret[sizeof($ret)-1], 0, $maxCharLen-3) . '...';
    }
    else{
        $rest = ($countResult-sizeof($ret));
        for($i=0; $i<$rest; $i++) {
            array_push($ret, '');
        }
    }

    return $ret;
}
