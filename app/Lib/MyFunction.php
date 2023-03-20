<?php

namespace App\Lib;

class MyFunction
{

    public static function yearSelect(){
        $n = date("Y"); // 現在の年（2023）
        $y = $n - 125; // 125年前からスタート（1898）
        for($n; $y<=$n; $y++){
            echo '<option value="'.$y.'">'.$y.'</option>';
        }
    }

    public static function monthSelect(){
        for($m=1; $m<=12; $m++){
            $pad = sprintf('%02d', $m);
            // 1~9までは前を0埋め,str_pad($m, 2 ,0, STR_PAD_LEFT)から変更
            echo '<option value="'.$pad.'">'.$m.'</option>';
        }
    }

    public static function daySelect(){
        for($d=1; $d<=31; $d++){
            $pad = sprintf('%02d', $d);
            // 1~9までは前を0埋め,str_pad($d, 2 ,0, STR_PAD_LEFT)から変更
            echo '<option value="'.$pad.'">'.$d.'</option>';
        }
    }

}
