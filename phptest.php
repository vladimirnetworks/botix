<?php
function fixencode($inp)
{
    return  preg_replace_callback("![^[:ascii:]]!", function ($i) {
        return urlencode($i[0]);
    }, $inp);
}

$xx = " return 1;";

#echo eval($xx);
//test cpanel

// remove !!i

$subj =  " https://www.2nafare.com/asdasd/";

preg_match('!^https:\/\/www\.2nafare\.com\/[a-zA-Z0-9%-]+\/$!',$subj,$m);

print_r($m);

