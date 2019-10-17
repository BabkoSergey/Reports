<?php

function randomKey($length, $type = '') {
    $key = '';
    
    switch ($type) {
        case 'd'    : $pool = array_merge(range(0, 9));                                     break;
        case 'a'    : $pool = array_merge(range('a', 'z'));                                 break;
        case 'A'    : $pool = array_merge(range('A', 'Z'));                                 break;
        case 'A,a'  : $pool = array_merge(range('a', 'z'), range('A', 'Z'));                break;
        case 'A,d'  : $pool = array_merge(range(0, 9), range('A', 'Z'));                    break;
        case 'a,d'  : $pool = array_merge(range(0, 9), range('a', 'z'));                    break;
        default     : $pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));   break;
    }

    for ($i = 0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }

    return $key;
}
