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

$subj =  '<h2 class="title"><a>aa</a></h2>        <h2 class="title"><a>bb</a></h2>';

preg_match_all('!<h2 class="title">(.*?)</h2>!',$subj,$m);

print_r($m);

