<?php

const WORDS = [
    "akce",
    "bambus",
    "blud",
    "dnes",
    "hmat",
    "lump",
    "mzda",
    "ocel",
    "revma",
    "sysel",
    "zmar",
    "zubr"
];

/**
 * Tries to find crypto wallet private key from public key
 *  
 * @param Array $words 
 * @param String $public
 * @param Array $before=[]
 * @return String?
 */
function bruteForceCryptoWallet($words, $public, $before=[]) 
{
    if (!preg_match("/^[A-Za-z0-9]{40}$/", $public))
        return "Not valid hash";

    if (sizeof($words) == 2)
    {
        $base_key = implode("", $before);
        if (sha1($key = ($base_key . $words[0] . $words[1])) == $public)
            return $key . "\n"; 

        if (sha1($key = ($base_key . $words[1] . $words[0])) == $public)
            return $key . "\n";
        
        return;
    }

    foreach($words as $index => $item)
    {
        $copy = $words;
        unset($copy[$index]);
        $copy = array_values($copy);
        array_push($before, $item);
        if($result = bruteForceCryptoWallet($copy, $public, $before))
            return $result; 
        array_pop($before);
    }
}

/**
 * Helping function that can generate bitcoin wallet token
 *
 * @param Array $target
 * @return String
 */
function generate(Array $target): String
{
    $shuffled = $target;
    shuffle($shuffled);
    return sha1(implode("", $shuffled));
}

$start = microtime(true);
echo bruteForceCryptoWallet(WORDS, "d99fa327540d376faf85ffb64bfe95c0191a428f");
$end = microtime(true);

echo ($end - $start) / 60;
