<?php

namespace ShortenerBundle\Service;

class Converter {
    private $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function base62_encode($num) {
        $b = 62;
        $r = $num  % $b ;
        $res = $this->base[$r];
        $q = floor($num/$b);
        while ($q) {
            $r = $q % $b;
            $q =floor($q/$b);
            $res = $this->base[$r].$res;
        }
        return $res;
    }

    public function base62_decode($num) {
        $b = 62;
        $limit = strlen($num);
        $res=strpos($this->base,$num[0]);
        for($i=1;$i<$limit;$i++) {
            $res = $b * $res + strpos($this->base,$num[$i]);
        }
        return $res;
    }
}