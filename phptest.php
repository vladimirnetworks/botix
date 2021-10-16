<?php
function fixencode($inp) {
return  preg_replace_callback("![^[:ascii:]]!",function($i) {
    return urlencode($i[0]);
},$inp);


}
