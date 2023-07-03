<?php

namespace php_font_changer;

//require_once "./pia_init.php";
require_once 'init.php';

class PFCState
{
    public $is_phone;
    public $font_family; // 0 = Gothic, 1 = Mincho
    public $font_size; // 1-9, 5 == default medium
    public $color; // 0 = black, 1 = white, 2 = beige, 3 = light mode, 4 = dark mode
    public $has_other_state = false; // Personal Novel Server 以外で使っている URL パラメーターがあるか
    public $other_states = [];
    public $other_states_str = "";

    function __construct(){
        $this->is_phone = $this->check_is_phone();
        $this->font_family = isset($_GET["family"]) ? (int)$_GET["family"] : 0;
        $this->font_size = isset($_GET["size"]) ? (int)$_GET["size"] : 1;
        $this->color = $this->get_color();
        $this->get_other_states();
    }

    // function is_daytime(){
    //     date_default_timezone_set('Asia/Tokyo');
    //     $m = date("m");
    //     $h_temp = date("H");
    //     $i = date("i");
    //     $i = (float)$i;
    //     $h = $i / 60 + (float)$h_temp; // 18:30 -> 18.5

    //     // 各月21日の日の出と日没時刻から、昼か夜かを判断（昼なら true）
    //     switch ((int)$m){
    //         case 1:  if($h >= 6.8 && $h <= 16.9) { return true; } else { return false; }
    //         case 2:  if($h >= 6.3 && $h <= 17.5) { return true; } else { return false; }
    //         case 3:  if($h >= 5.7 && $h <= 17.9) { return true; } else { return false; }
    //         case 4:  if($h >= 5.1 && $h <= 18.3) { return true; } else { return false; }
    //         case 5:  if($h >= 4.6 && $h <= 18.7) { return true; } else { return false; }
    //         case 6:  if($h >= 4.4 && $h <= 19.0) { return true; } else { return false; }
    //         case 7:  if($h >= 4.7 && $h <= 18.9) { return true; } else { return false; }
    //         case 8:  if($h >= 5.1 && $h <= 18.4) { return true; } else { return false; }
    //         case 9:  if($h >= 5.5 && $h <= 17.7) { return true; } else { return false; }
    //         case 10: if($h >= 5.9 && $h <= 17.0) { return true; } else { return false; }
    //         case 11: if($h >= 6.3 && $h <= 16.5) { return true; } else { return false; }
    //         case 12: if($h >= 6.8 && $h <= 16.5) { return true; } else { return false; }
    //         default: return false;
    //     }
    // }

    function get_color(){
        // 個人サイト用（ライトモードとダークモードを適用）
//        var_dump(PNS_LIGHT_AND_DARK);
//        var_dump($this->is_daytime());
        if(PNS_LIGHT_AND_DARK){
            $mode = isset($_GET["mode"]) ? (int)$_GET["mode"] : 0;
            if($mode > 0){
                return $mode === 1 ? 3 : 4;
            } else {
                return $this->is_daytime() ? 3 : 4;
            }
        } else {
            return isset($_GET["color"]) ? (int)$_GET["color"] : 0;
        }
    }

    function check_is_phone(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/iphone|ipod|ipad|android/ui', $user_agent) != 0;
    }

    function get_other_states(){
        $keys = array_keys($_GET);
        foreach ($keys as $key){
            if($key !== "pns"
            && $key !== "novel"
            && $key !== "chap"
            && $key !== "ep"
            && $key !== "family"
            && $key !== "size"
            && $key !== "color"
            && $key !== "x")
            {
                if($this->has_other_state === false){
                    $this->other_states_str .= "?" . $key . "=" . $_GET[$key];
                    $this->has_other_state = true;
                } else {
                    $this->other_states_str .= "&" . $key . "=" . $_GET[$key];
                }
                array_push($this->other_states, $key);
            }
        }
    }
}