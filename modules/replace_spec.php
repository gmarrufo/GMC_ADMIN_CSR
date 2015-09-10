<?php

function just_clean($string)
{
// Replace other special chars

/*
$specialCharacters = array(
"!" => "&#033",
"#" => "&#035",
"$" => "&#036",
"%" => "&#037",
"&" => "&#038",
"'" => "&#039",
"(" => "&#040",
")" => "&#041",
"*" => "&#042",
"?" => "&#063",
"+" => "&#043",
"=" => "&#061",
"," => "&#044",
"-" => "&#045",
"." => "&#046",
"/" => "&#047",
"@" => "&#064");
*/

/*
$specialCharacters = array(
"&" => "&#038",
"%" => "&#037",
"'" => "&#039",
"*" => "&#042",
"@" => "&#064");
*/

// Character below gives error
//"\" => "",

/*
while (list($character, $replacement) = each($specialCharacters))
{
    // $string = str_replace($character, '-' . $replacement . '-', $string);
    $string = str_replace($character,  $replacement , $string);
}
*/

// GMC - 06/20/14 - Selective replacement of symbols
// & replacement
$string = str_replace("&", "&#038;", $string);

// % replacement
$string = str_replace("%", "&#037;", $string);

// ' replacement
$string = str_replace("'", "&#039;", $string);

// * replacement
$string = str_replace("*", "&#042;", $string);

// @ replacement
$string = str_replace("@", "&#064;", $string);

// $string = addslashes($string);

/*
$string = strtr($string,
"??????? ??????????????????????????????????????????????",
"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
);
*/

// Remove all remaining other unknown characters
// $string = preg_replace("/[^a-zA-Z0-9-]/", " ", $string);
// $string = preg_replace("/^[-]+/", "", $string);
// $string = preg_replace("/[-]+$/", "", $string);
// $string = preg_replace("/[-]{2,}/", " ", $string);

return $string;
}

?>
