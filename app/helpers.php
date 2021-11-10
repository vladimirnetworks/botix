<?php

use Stichoza\GoogleTranslate\GoogleTranslate;
use vladimirnetworks\htmlparser\shd;

function shd($input)
{
    return shd::str_get_html($input);
}


function faToEn($input)
{
    $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
    $tr->setSource('fa');
    $tr->setTarget('en');
    return $tr->translate($input);
}

function onespace($input)
{
    return trim(preg_replace('!\s+!', ' ', $input));
}

function spaceToDash($input) {
	return str_replace(" ","-",onespace($input));
}

function alefba()
{
    $alefba[] = 'ا';
    $alefba[] = 'ب';
    $alefba[] = 'پ';
    $alefba[] = 'ت';
    $alefba[] = 'ث';
    $alefba[] = 'ج';
    $alefba[] = 'چ';
    $alefba[] = 'ح';
    $alefba[] = 'خ';
    $alefba[] = 'د';
    $alefba[] = 'ذ';
    $alefba[] = 'ر';
    $alefba[] = 'ز';
    $alefba[] = 'ژ';
    $alefba[] = 'س';
    $alefba[] = 'ش';
    $alefba[] = 'ص';
    $alefba[] = 'ض';
    $alefba[] = 'ط';
    $alefba[] = 'ظ';
    $alefba[] = 'ع';
    $alefba[] = 'غ';
    $alefba[] = 'ف';
    $alefba[] = 'ق';
    $alefba[] = 'ک';
    $alefba[] = 'گ';
    $alefba[] = 'ل';
    $alefba[] = 'م';
    $alefba[] = 'ن';
    $alefba[] = 'و';
    $alefba[] = 'ه';
    $alefba[] = 'ی';
    $alefba[] = 'ء';
    $alefba[] = 'آ';
    $alefba[] = 'اً';
    $alefba[] = 'هٔ';
    $alefba[] = 'ة';
    return implode("", $alefba);
}

function adaad()
{
    $adaad = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"];
    return implode("", $adaad);
}

function arabicToFarsi($inp)
{

    $f[] = 'ي';
    $r[] = 'ی';

    $f[] = 'ك';
    $r[] = 'ک';


    $f[] = '٤';
    $r[] = '۴';

    $f[] = '٥';
    $r[] = '۵';

    $f[] = '٦';
    $r[] = '۶';


    return str_replace($f, $r, $inp);
}


function toPersianNumbers($i)
{

    $f = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    $r = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"];
    return str_replace($f, $r, $i);
}


function ToEnglishNumbers($i)
{

    $f = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"];
    $r = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    return str_replace($f, $r, arabicToFarsi($i));
}

function tosapce($regxs, $input)
{
    return onespace(preg_replace('![^' . implode("", $regxs) . ']+!u', ' ', $input));
}

function onlyFarsiLetters($input)
{
    return tosapce([alefba()], arabicToFarsi($input));
}

function onlyFarsiLettersAndNumbers($input)
{
    return tosapce([alefba(), adaad()], arabicToFarsi($input));
}

function onlyEnglishLetters($input)
{
    return tosapce(['a-zA-z'], $input);
}

function onlyEnglishLettersAndnumbers($input)
{
    return tosapce(['a-zA-z0-9'], $input);
}

function onlyFarsAndEnglishiLettersAndNumbers($input)
{
    return tosapce([alefba(), adaad(), 'a-zA-z0-9'], arabicToFarsi($input));
}
