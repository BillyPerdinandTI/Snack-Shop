<?php

$fp = fsockopen("smtp.gmail.com", 587, $errno, $errstr, 10);

if (!$fp) {
    echo "ERROR: $errno - $errstr";
} else {
    echo "Berhasil terhubung";
    fclose($fp);
}