<?php

function csprng()
{
    try {
        $bytes = random_bytes(32);
        echo bin2hex($bytes);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function tba()
{
    $random = random_bytes(32);
    //$unique_id = hash('sha256', uniqid(mt_rand(), true));
    $unique_id = hash('sha256', $random);
    echo $unique_id;
}

function openssl()
{
    $bytes = openssl_random_pseudo_bytes(20);
    echo bin2hex($bytes);
}

$id = tba();