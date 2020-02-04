<?php

function clean($input)
{
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $output[$key] = clean($value);
        }
    } else {
        $output = (string)$input;
        if (get_magic_quotes_gpc()) {
            $output = stripslashes($output);
        }
        $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
    }
    return $output;
}

$text = "<script>alert(1)</script>";
$text = clean($text);
echo $text;