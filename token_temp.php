<?php

$email = "noreply@yourwebsite.com";
$key = hash('sha256', $email);
$addKey = substr(hash('sha256',(uniqid(rand(),1))),3,10);
$key = $key . $addKey;
echo $key;