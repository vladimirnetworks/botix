<?php
echo preg_replace_callback("![^[:ascii:]]!",function($i) {
    return urlencode($i[0]);
},"abaببa  a=");
