<?php

namespace App\Http\Controllers;

use App\Models\maker_history;
use App\Models\Target;
use App\Models\urlpattern;
use App\Models\page;
use App\Models\post;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use DOMDocument;



class crawlController extends Controller
{


    static function fixencode($inp)
    {
        return  preg_replace_callback("![^[:ascii:]]!", function ($i) {
            return urlencode($i[0]);
        }, $inp);
    }

    static function gett($u, $headers = null, $ua = null)
    {

        $u = crawlController::fixencode($u);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $u);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 7);




        $userAgent =  \Campo\UserAgent::random(['os_type' => "Windows", 'device_type' => "Desktop"]);




        if ($headers) {



            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        #echo "req : ".$u;
        #echo "\n";
        #echo "ua : ".$userAgent;
        #echo "\n";
        if ($ua != null) {
            $userAgent = $ua;
        }

        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        return curl_exec($ch);
    }




    public function crawl()
    {

        date_default_timezone_set("Asia/Tehran");
        $targ = Target::has('urlpatterns')->where('active', '=', 1)->orderBy('lastseen', 'asc')->first();

        $targ->lastseen = date("Y-m-d H:i:s");
        $targ->save();


        $pages = $targ->pages->where('status', '=', 0)->first();
        $url = null;

        if (isset($pages)) {
            $url = $pages->url;
        }

        if (!$url) {
            // $url = $targ->url;

            DB::table('pages')->insertOrIgnore(

                ['url' => $targ->url, 'target_id' => $targ->id]

            );

            $targ = Target::where('active', '=', 1)->orderBy('lastseen', 'desc')->first();
            $pages = $targ->pages->where('status', '=', 0)->first();
            $url = $pages->url;
        }



        $url_parsed = parse_url($url);



        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $getpage = crawlController::gett($url);

        $patts = $targ->urlpatterns;


        $saveisok = false;
        $m = null;
        foreach ($patts as $pat) {

            if ($pat->type == 1 && preg_match($pat->pattern, $url)) {

                $saveisok = true;

                if ($pat->savepattern) {

                    $allsavepat = explode("\n", trim($pat->savepattern));


                    $retmatch = null;
                    foreach ($allsavepat as $hspat) {

                        preg_match('!(\!.+\!.+)->(.*)!', trim($hspat), $pm);
                        $pm[1] = trim($pm[1]);
                        $pm[2] = preg_replace("![^0-9,]!", "", $pm[2]);


                        preg_match($pm[1], $getpage, $m);

                        $thismatchs = explode(",", $pm[2]);

                        foreach ($thismatchs as $hit_thismatch) {
                            if (isset($m[$hit_thismatch])) {
                                $retmatch[] = $m[$hit_thismatch];
                            }
                        }
                    }
                }

                break;
            }
        }

        if ($saveisok) {

            if (isset($retmatch)) {
                $pages->html = implode(" ", $retmatch);
            } else {
                $pages->html = $getpage;
            }
        }



        $dom->loadHTML($getpage);

        if (isset($pages)) {
            $pages->status = strlen($getpage);
            $pages->save();
        }

        $htmlNodes = $dom->getElementsByTagName('a');


        foreach ($htmlNodes as $node) {

            $href = $node->getAttribute('href');

            $href_parsed = parse_url($href);

            $scheme = null;
            if (isset($href_parsed['scheme'])) {
                $scheme = strtolower($href_parsed['scheme']);
            }


            if ($scheme == 'https' || $scheme == 'http') {



                $getedurl = $href;
            } else {

                $getedurl = $url_parsed['scheme'] . "://" . $url_parsed['host'] . '/' . ltrim($href, "/");
            }

            $getedur = rtrim($getedurl, "#") . "#" . rand(0, 9999);

            $getedur = preg_replace("!#.*!", "", $getedur);

            $mpd[] = $getedur;
        }

        $mpd = array_unique($mpd);


        foreach ($mpd as $hit_url) {

            $isok = false;

            foreach ($patts as $pat) {

                if (preg_match($pat->pattern, $hit_url)) {
                    $isok = true;
                }
            }

            if ($isok) {

                $ret[] = $hit_url;
                // page::create(['url' => $hit_url,'target_id'=>$targ->id]);

                DB::table('pages')->insertOrIgnore(

                    ['url' => $hit_url, 'target_id' => $targ->id]

                );
            }
        }


        dd($ret);


        return  $mpd;
    }


    public function make()
    {

        date_default_timezone_set("Asia/Tehran");
        $targ = Target::has('makers')->where('makeractive', '=', 1)->orderBy('makerlastseen', 'asc')->first();

        $targ->makerlastseen = date("Y-m-d H:i:s");
        $targ->save();




        $makers = $targ->makers->where('active', '=', 1);








        // dd($pgg->url);

        foreach ($makers as $hitmaker) {

            $regexurl = '.*';
            if ($hitmaker->urlpattern) {
                $regexurl = $hitmaker->urlpattern;
            }

            $regexhtml = '.*';
            if ($hitmaker->htmlpattern) {
                $regexhtml = $hitmaker->htmlpattern;
            }


            $q = "SELECT  *
            FROM    pages p
            WHERE  `url` REGEXP '$regexurl' and `html` REGEXP '$regexhtml' and  p.`html` is not null and   NOT EXISTS
                    (
                    SELECT  null 
                    FROM    maker_history h
                    WHERE   h.url = p.url and h.target_id = p.target_id and maker_id=" . $hitmaker->id . "
                    )";


            $pgg = DB::select(DB::raw($q))[0];


            //
            $html = $pgg->html;
            $res = eval($hitmaker->maker . ";");

            post::create([
                'target_id' => $pgg->target_id,
                'maker_id' => $hitmaker->id,
                'data' => json_encode($res),
            ]);
            //


            maker_history::create([
                'target_id' => $pgg->target_id,
                'maker_id' => $hitmaker->id,
                'url' => $pgg->url,
            ]);


            dd($pgg);
        }



        return null;
    }
}
