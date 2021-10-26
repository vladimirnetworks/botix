<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use libs\testlib;
use Campo\UserAgent;
use App\libs\photoTeleg;
use App\libs\Telegram;
use App\libs\shd;
class testcontroller extends Controller
{
    //

    public static function shd($i) {
        return shd::str_get_html($i);
    }

    public function testit()
    {

        $xx = new photoTeleg(new Telegram(
            "623179675:AAEdSd9UyeW65VgisyrMaHqgSG255YdDtIM",
            false
            /*,["type"=>CURLPROXY_SOCKS5,
              "url"=>"127.0.0.1",
              "port"=>"9090"]*/
        ));



        $lax = eval('return $this::shd("<a>xxx</a>")->find("a",0)->innertext;');


     

        return $lax;
    }
}
