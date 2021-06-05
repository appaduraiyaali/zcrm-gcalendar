<?php
//testStartDateFormatting();
$length_of_string=20;
//random_strings();
function random_strings() {
      global $length_of_string;
    // md5 the timestamps and returns substring
    // of specified length
    $randomstr= substr(md5(time()), 0, $length_of_string);
	echo $randomstr;
	return $randomstr;
}

