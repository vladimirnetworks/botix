<?php

namespace App\libs;

class photoTeleg
{

    protected $telegram;
    function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }

    static function toteleg($inp)
    {







        $uux = parse_url($inp);

        if (!isset($uux['scheme'])) {

            $cfile = new \CURLFile(realpath($inp), 'image/jpg', basename($inp)); //first parameter is YOUR IMAGE path

        } else {

            $cfile = $inp;
        }








        $contentx = array(
            'chat_id' => "@dxlxtmaer",

            'photo' =>     $cfile,

            'caption' => ""
        );

        $x =  $this->telegram->sendPhoto($contentx);

        #  print_r($x);exit;

        $teleg = json_encode($x);



        $ssz = 0;

        $ssz2 = 1073741824;


        if (isset($x['result']) && isset($x['result']['photo'])) {



            foreach ($x['result']['photo'] as $hit) {

                if ($hit['width'] > $ssz) {
                    $ssz = $hit['width'];
                    $filid = [

                        "fileid" => $hit['file_id'],
                        "width" => $hit['width'],
                        "height" => $hit['height']

                    ];
                }

                if ($hit['width'] < $ssz2) {
                    $ssz2 = $hit['width'];
                    $filid2 = [

                        "fileid" => $hit['file_id'],
                        "width" => $hit['width'],
                        "height" => $hit['height']

                    ];
                }
            }


            $filid2 = [

                "fileid" => $x['result']['photo'][0]['file_id'],
                "width" => $x['result']['photo'][0]['width'],
                "height" => $x['result']['photo'][0]['height']

            ];


            $las = count($x['result']['photo']) - 1;

            $filid = [

                "fileid" => $x['result']['photo'][$las]['file_id'],
                "width" => $x['result']['photo'][$las]['width'],
                "height" => $x['result']['photo'][$las]['height']

            ];
        }

        if (count($x['result']['photo']) > 2) {

            $filid2 = [

                "fileid" => $x['result']['photo'][1]['file_id'],
                "width" => $x['result']['photo'][1]['width'],
                "height" => $x['result']['photo'][1]['height']

            ];
        }





        if (isset($filid) && isset($filid2)) {

            $retxx['big'] = $filid;

            $retxx['small'] = $filid2;

            if (isset($filidmed)) {
                $retxx['medium'] = $filidmed;
            }
        }

        if (isset($filid) && isset($filid2)) {
            return $retxx;
        } else {
            return false;
        }
    }


    public function mtest()
    {
       return "works!";
    }
}
