<?php

namespace App\Http\Controllers;

use App\Models\maker_history;
use App\Models\Target;
use App\Models\urlpattern;
use App\Models\page;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Sunra\PhpSimple\HtmlDomParser;

use Illuminate\Http\Request;
use DOMDocument;





class crawlController extends Controller
{


    static function regexformysql($i)
    {
        preg_match('/!(.+)!.*/', $i, $m);
        return $m[1];
    }



    static function toteleg($inp)
    {




        $telegram = new Telegram("623179675:AAEdSd9UyeW65VgisyrMaHqgSG255YdDtIM", false/*,["type"=>CURLPROXY_SOCKS5,
        "url"=>"127.0.0.1",
        "port"=>"9090"]*/);



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

        $x =  $telegram->sendPhoto($contentx);

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
        $targ = Target::has('urlpatterns')->where('active', '=', 1)->orderBy('lastseen', 'asc')->first();
        $this->runcrawl($targ);
    }


    public function crawlsave()
    {


        $targ = Target::has('urlpatterns')->where('active', '=', 1)->orderBy('lastseen', 'asc')->first();



        $patts = $targ->urlpatterns;


        $saveisok = false;
        $m = null;
        foreach ($patts as $pat) {

            if ($pat->type == 1) {
                $pages_get = DB::table('pages')->whereraw('target_id = ' . $targ->id . " and status = 0 and url REGEXP '" . crawlController::regexformysql($pat->pattern) . "' ")->first();
                if (isset($pages_get->id)) {
                    $pages = page::find($pages_get->id);
                    break;
                }
            }
        }



        if (isset($pages) && isset($targ)) {
            $this->runcrawl($targ, $pages);
        }
    }




    public function runcrawl($targ, $pages = null)
    {


        date_default_timezone_set("Asia/Tehran");


        //$targ = Target::has('urlpatterns')->where('active', '=', 1)->orderBy('lastseen', 'asc')->first();
        $targ->lastseen = date("Y-m-d H:i:s");
        $targ->save();


        if ($pages == null) {
            $pages = $targ->pages->where('status', '=', 0)->first();
        }


        $url = null;

        if (isset($pages)) {
            $url = $pages->url;
        }

        if (!$url) {


            $now = Carbon::now();


            DB::table('pages')->insertOrIgnore(

                [
                    'url' => $targ->url, 'target_id' => $targ->id,

                    "created_at" => $now,
                    "updated_at" => $now,

                ]

            );

            $targ = Target::where('active', '=', 1)->orderBy('lastseen', 'desc')->first();
            $pages = $targ->pages->where('status', '=', 0)->first();
            $url = $pages->url;
        }



        $url = trim($url);

        $parent = $pages->id;

        $parent_url = $url;
        $url_parsed = parse_url($url);



        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $getpage = crawlController::gett($url);

        if (!$getpage) {

            $pages->status = -1;
            $pages->save();

            exit;
        }

        $patts = $targ->urlpatterns;


        $saveisok = false;
        $m = null;
        foreach ($patts as $pat) {

            if ($pat->type == 1 && preg_match($pat->pattern, $url)) {

                $saveisok = true;

                if ($pat->savehtmlpipe) {

                    $html = $getpage;
                    $getpage = eval($pat->savehtmlpipe);
                }

                if ($pat->savepattern) {
                    /*
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
                    */
                }

                break;
            }
        }

        if ($saveisok) {

            /*if (isset($retmatch)) {
                $pages->html = implode(" ", $retmatch);
            } else {
                $pages->html = $getpage;
            }*/

            $pages->html = $getpage;
        }


        $htmlpipe = null;

        foreach ($patts as $pat) {

            if (preg_match($pat->pattern, $url) && $pat->htmlpipe) {
                $htmlpipe = $pat->htmlpipe;
                break;
            }
        }


        if ($htmlpipe) {
            $html = $getpage;
            $html = eval($htmlpipe);
            $dom->loadHTML($html);
        } else {

            $dom->loadHTML($getpage);
        }

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

            $hit_url = trim($hit_url);

            $isok = false;

            foreach ($patts as $pat) {

                if ($pat->pattern && $pat->whereParentpattern && preg_match($pat->pattern, $hit_url) && preg_match($pat->whereParentpattern, $parent_url)) {
                    $isok = true;
                }
            }

            if ($isok) {

                $ret[] = $hit_url;
                // page::create(['url' => $hit_url,'target_id'=>$targ->id]);
                $now = Carbon::now();
                DB::table('pages')->insertOrIgnore(

                    [
                        'url' => $hit_url,
                        'target_id' => $targ->id,

                        "created_at" => $now,
                        "updated_at" => $now,


                        'parent' => $parent,


                    ]

                );
            }
        }


        if (isset($ret)) {
            //dd($ret);
        } else {
            //dd("done");
        }

        exit;

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


                $regexurl = crawlController::regexformysql($hitmaker->urlpattern);
            }

            $regexhtml = '.*';
            if ($hitmaker->htmlpattern) {


                $regexhtml = crawlController::regexformysql($hitmaker->htmlpattern);
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
                'url' => $pgg->url,
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


class Telegram
{
    const INLINE_QUERY = 'inline_query';
    const CALLBACK_QUERY = 'callback_query';
    const EDITED_MESSAGE = 'edited_message';
    const REPLY = 'reply';
    const MESSAGE = 'message';
    const PHOTO = 'photo';
    const VIDEO = 'video';
    const AUDIO = 'audio';
    const VOICE = 'voice';
    const ANIMATION = 'animation';
    const STICKER = 'sticker';
    const DOCUMENT = 'document';
    const LOCATION = 'location';
    const CONTACT = 'contact';
    const CHANNEL_POST = 'channel_post';
    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
    private $proxy;
    public function __construct($bot_token, $log_errors = true, array $proxy = [])
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
        $this->proxy = $proxy;
    }
    public function endpoint($api, array $content, $post = true)
    {
        $url = 'https://api.telegram.org/bot' . $this->bot_token . '/' . $api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }
        return json_decode($reply, true);
    }
    public function getMe()
    {
        return $this->endpoint('getMe', [], false);
    }
    public function respondSuccess()
    {
        http_response_code(200);
        return json_encode(['status' => 'success']);
    }
    public function sendMessage(array $content)
    {
        return $this->endpoint('sendMessage', $content);
    }
    public function forwardMessage(array $content)
    {
        return $this->endpoint('forwardMessage', $content);
    }
    public function sendPhoto(array $content)
    {
        return $this->endpoint('sendPhoto', $content);
    }
    public function sendAudio(array $content)
    {
        return $this->endpoint('sendAudio', $content);
    }
    public function sendDocument(array $content)
    {
        return $this->endpoint('sendDocument', $content);
    }
    public function sendAnimation(array $content)
    {
        return $this->endpoint('sendAnimation', $content);
    }
    public function sendSticker(array $content)
    {
        return $this->endpoint('sendSticker', $content);
    }
    public function sendVideo(array $content)
    {
        return $this->endpoint('sendVideo', $content);
    }
    public function sendVoice(array $content)
    {
        return $this->endpoint('sendVoice', $content);
    }
    public function sendLocation(array $content)
    {
        return $this->endpoint('sendLocation', $content);
    }
    public function editMessageLiveLocation(array $content)
    {
        return $this->endpoint('editMessageLiveLocation', $content);
    }
    public function stopMessageLiveLocation(array $content)
    {
        return $this->endpoint('stopMessageLiveLocation', $content);
    }
    public function setChatStickerSet(array $content)
    {
        return $this->endpoint('setChatStickerSet', $content);
    }
    public function deleteChatStickerSet(array $content)
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }
    public function sendMediaGroup(array $content)
    {
        return $this->endpoint('sendMediaGroup', $content);
    }
    public function sendVenue(array $content)
    {
        return $this->endpoint('sendVenue', $content);
    }
    public function sendContact(array $content)
    {
        return $this->endpoint('sendContact', $content);
    }
    public function sendChatAction(array $content)
    {
        return $this->endpoint('sendChatAction', $content);
    }
    public function getUserProfilePhotos(array $content)
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }
    public function getFile($file_id)
    {
        $content = ['file_id' => $file_id];
        return $this->endpoint('getFile', $content);
    }
    public function kickChatMember(array $content)
    {
        return $this->endpoint('kickChatMember', $content);
    }
    public function leaveChat(array $content)
    {
        return $this->endpoint('leaveChat', $content);
    }
    public function unbanChatMember(array $content)
    {
        return $this->endpoint('unbanChatMember', $content);
    }
    public function getChat(array $content)
    {
        return $this->endpoint('getChat', $content);
    }
    public function getChatAdministrators(array $content)
    {
        return $this->endpoint('getChatAdministrators', $content);
    }
    public function getChatMembersCount(array $content)
    {
        return $this->endpoint('getChatMembersCount', $content);
    }
    public function getChatMember(array $content)
    {
        return $this->endpoint('getChatMember', $content);
    }
    public function answerInlineQuery(array $content)
    {
        return $this->endpoint('answerInlineQuery', $content);
    }
    public function setGameScore(array $content)
    {
        return $this->endpoint('setGameScore', $content);
    }
    public function answerCallbackQuery(array $content)
    {
        return $this->endpoint('answerCallbackQuery', $content);
    }
    public function editMessageText(array $content)
    {
        return $this->endpoint('editMessageText', $content);
    }
    public function editMessageCaption(array $content)
    {
        return $this->endpoint('editMessageCaption', $content);
    }
    public function editMessageReplyMarkup(array $content)
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }
    public function downloadFile($telegram_file_path, $local_file_path)
    {
        $file_url = 'https://api.telegram.org/file/bot' . $this->bot_token . '/' . $telegram_file_path;
        $in = fopen($file_url, 'rb');
        $out = fopen($local_file_path, 'wb');
        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }
    public function setWebhook($url, $certificate = '')
    {
        if ($certificate == '') {
            $requestBody = ['url' => $url];
        } else {
            $requestBody = ['url' => $url, 'certificate' => "@$certificate"];
        }
        return $this->endpoint('setWebhook', $requestBody, true);
    }
    public function deleteWebhook()
    {
        return $this->endpoint('deleteWebhook', [], false);
    }
    public function getData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');
            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }
    public function setData(array $data)
    {
        $this->data = $data;
    }
    public function Text()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['data'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['text'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['text'];
        }
        return @$this->data['message']['text'];
    }
    public function Caption()
    {
        $type = $this->getUpdateType();
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['caption'];
        }
        return @$this->data['message']['caption'];
    }
    public function ChatID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['chat']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['chat']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['chat']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }
        return $this->data['message']['chat']['id'];
    }
    public function MessageID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['message_id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['message_id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['message_id'];
        }
        return $this->data['message']['message_id'];
    }
    public function ReplyToMessageID()
    {
        return $this->data['message']['reply_to_message']['message_id'];
    }
    public function ReplyToMessageFromUserID()
    {
        return $this->data['message']['reply_to_message']['forward_from']['id'];
    }
    public function Inline_Query()
    {
        return $this->data['inline_query'];
    }
    public function Callback_Query()
    {
        return $this->data['callback_query'];
    }
    public function Callback_ID()
    {
        return $this->data['callback_query']['id'];
    }
    public function Callback_Data()
    {
        return $this->data['callback_query']['data'];
    }
    public function Callback_Message()
    {
        return $this->data['callback_query']['message'];
    }
    public function Callback_ChatID()
    {
        return $this->data['callback_query']['message']['chat']['id'];
    }
    public function Date()
    {
        return $this->data['message']['date'];
    }
    public function FirstName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['first_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['first_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['first_name'];
        }
        return @$this->data['message']['from']['first_name'];
    }
    public function LastName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['last_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['last_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['last_name'];
        }
        if ($type == self::MESSAGE) {
            return @$this->data['message']['from']['last_name'];
        }
        return '';
    }
    public function Username()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['username'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['username'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['username'];
        }
        return @$this->data['message']['from']['username'];
    }
    public function Location()
    {
        return $this->data['message']['location'];
    }
    public function UpdateID()
    {
        return $this->data['update_id'];
    }
    public function UpdateCount()
    {
        return count($this->updates['result']);
    }
    public function UserID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return $this->data['callback_query']['from']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return $this->data['channel_post']['from']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['id'];
        }
        return $this->data['message']['from']['id'];
    }
    public function FromID()
    {
        return $this->data['message']['forward_from']['id'];
    }
    public function FromChatID()
    {
        return $this->data['message']['forward_from_chat']['id'];
    }
    public function messageFromGroup()
    {
        if ($this->data['message']['chat']['type'] == 'private') {
            return false;
        }
        return true;
    }
    public function messageFromGroupTitle()
    {
        if ($this->data['message']['chat']['type'] != 'private') {
            return $this->data['message']['chat']['title'];
        }
        return '';
    }
    public function buildKeyBoard(array $options, $onetime = false, $resize = false, $selective = true)
    {
        $replyMarkup = ['keyboard' => $options, 'one_time_keyboard' => $onetime, 'resize_keyboard' => $resize, 'selective' => $selective,];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }
    public function buildInlineKeyBoard(array $options)
    {
        $replyMarkup = ['inline_keyboard' => $options,];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }
    public function buildInlineKeyboardButton($text, $url = '', $callback_data = '', $switch_inline_query = null, $switch_inline_query_current_chat = null, $callback_game = '', $pay = '')
    {
        $replyMarkup = ['text' => $text,];
        if ($url != '') {
            $replyMarkup['url'] = $url;
        } elseif ($callback_data != '') {
            $replyMarkup['callback_data'] = $callback_data;
        } elseif (!is_null($switch_inline_query)) {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        } elseif (!is_null($switch_inline_query_current_chat)) {
            $replyMarkup['switch_inline_query_current_chat'] = $switch_inline_query_current_chat;
        } elseif ($callback_game != '') {
            $replyMarkup['callback_game'] = $callback_game;
        } elseif ($pay != '') {
            $replyMarkup['pay'] = $pay;
        }
        return $replyMarkup;
    }
    public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
    {
        $replyMarkup = ['text' => $text, 'request_contact' => $request_contact, 'request_location' => $request_location,];
        return $replyMarkup;
    }
    public function buildKeyBoardHide($selective = true)
    {
        $replyMarkup = ['remove_keyboard' => true, 'selective' => $selective,];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }
    public function buildForceReply($selective = true)
    {
        $replyMarkup = ['force_reply' => true, 'selective' => $selective,];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }
    public function sendInvoice(array $content)
    {
        return $this->endpoint('sendInvoice', $content);
    }
    public function answerShippingQuery(array $content)
    {
        return $this->endpoint('answerShippingQuery', $content);
    }
    public function answerPreCheckoutQuery(array $content)
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }
    public function sendVideoNote(array $content)
    {
        return $this->endpoint('sendVideoNote', $content);
    }
    public function restrictChatMember(array $content)
    {
        return $this->endpoint('restrictChatMember', $content);
    }
    public function promoteChatMember(array $content)
    {
        return $this->endpoint('promoteChatMember', $content);
    }
    public function exportChatInviteLink(array $content)
    {
        return $this->endpoint('exportChatInviteLink', $content);
    }
    public function setChatPhoto(array $content)
    {
        return $this->endpoint('setChatPhoto', $content);
    }
    public function deleteChatPhoto(array $content)
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }
    public function setChatTitle(array $content)
    {
        return $this->endpoint('setChatTitle', $content);
    }
    public function setChatDescription(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }
    public function pinChatMessage(array $content)
    {
        return $this->endpoint('pinChatMessage', $content);
    }
    public function unpinChatMessage(array $content)
    {
        return $this->endpoint('unpinChatMessage', $content);
    }
    public function getStickerSet(array $content)
    {
        return $this->endpoint('getStickerSet', $content);
    }
    public function uploadStickerFile(array $content)
    {
        return $this->endpoint('uploadStickerFile', $content);
    }
    public function createNewStickerSet(array $content)
    {
        return $this->endpoint('createNewStickerSet', $content);
    }
    public function addStickerToSet(array $content)
    {
        return $this->endpoint('addStickerToSet', $content);
    }
    public function setStickerPositionInSet(array $content)
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }
    public function deleteStickerFromSet(array $content)
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }
    public function deleteMessage(array $content)
    {
        return $this->endpoint('deleteMessage', $content);
    }
    public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true)
    {
        $content = ['offset' => $offset, 'limit' => $limit, 'timeout' => $timeout];
        $this->updates = $this->endpoint('getUpdates', $content);
        if ($update) {
            if (array_key_exists('result', $this->updates) && is_array($this->updates['result']) && count($this->updates['result']) >= 1) {
                $last_element_id = $this->updates['result'][count($this->updates['result']) - 1]['update_id'] + 1;
                $content = ['offset' => $last_element_id, 'limit' => '1', 'timeout' => $timeout];
                $this->endpoint('getUpdates', $content);
            }
        }
        return $this->updates;
    }
    public function serveUpdate($update)
    {
        $this->data = $this->updates['result'][$update];
    }
    public function getUpdateType()
    {
        $update = $this->data;
        if (isset($update['inline_query'])) {
            return self::INLINE_QUERY;
        }
        if (isset($update['callback_query'])) {
            return self::CALLBACK_QUERY;
        }
        if (isset($update['edited_message'])) {
            return self::EDITED_MESSAGE;
        }
        if (isset($update['message']['text'])) {
            return self::MESSAGE;
        }
        if (isset($update['message']['photo'])) {
            return self::PHOTO;
        }
        if (isset($update['message']['video'])) {
            return self::VIDEO;
        }
        if (isset($update['message']['audio'])) {
            return self::AUDIO;
        }
        if (isset($update['message']['voice'])) {
            return self::VOICE;
        }
        if (isset($update['message']['contact'])) {
            return self::CONTACT;
        }
        if (isset($update['message']['location'])) {
            return self::LOCATION;
        }
        if (isset($update['message']['reply_to_message'])) {
            return self::REPLY;
        }
        if (isset($update['message']['animation'])) {
            return self::ANIMATION;
        }
        if (isset($update['message']['sticker'])) {
            return self::STICKER;
        }
        if (isset($update['message']['document'])) {
            return self::DOCUMENT;
        }
        if (isset($update['channel_post'])) {
            return self::CHANNEL_POST;
        }
        return false;
    }
    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url . '?chat_id=' . $content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        if (!empty($this->proxy)) {
            if (array_key_exists('type', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy['type']);
            }
            if (array_key_exists('auth', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy['auth']);
            }
            if (array_key_exists('url', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['url']);
            }
            if (array_key_exists('port', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok' => false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }
        return $result;
    }
}
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename=" . ($postname ?: basename($filename)) . ($mimetype ? ";type=$mimetype" : '');
    }
}
