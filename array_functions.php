<?php
// array functions - 20/07/2006 12.28


if(!defined("ARRAY_FUNCS")) {   
    // Swap 2 elements in array preserving keys.
    function array_swap(&$array,$key1,$key2) {
        $v1=$array[$key1];
        $v2=$array[$key2];
        $out=array();
        foreach($array as $i=>$v) {
            if($i===$key1) {
                $i=$key2;
                $v=$v2;
            } else if($i===$key2) {
                $i=$key1;
                $v=$v1;
            }
            $out[$i]=$v;
        }
        return $out;
    }
   
    //  Get a key position in array
    function array_kpos(&$array,$key) {
        $x=0;
        foreach($array as $i=>$v) {
            if($key===$i) return $x;
            $x++;
        }
        return false;
    }
   
    // Return key by position
    function array_kbypos(&$array,$pos) {
        $x=0;
        foreach($array as $i=>$v) {
            if($pos==$x++) return $i;
        }
        return false;
    }

    // Move an element inside an array preserving keys
    // $relpos should be like -1, +2...
    function array_move(&$array,$key,$relpos) {
        if(!$relpos) return false;
        $from=array_kpos($array,$key);
        if($from===false) return false;
        $to=$from+$relpos+($relpos>0?1:0);
        $len=count($array);
        if($to>=$len) {
            $val=$array[$key];
            unset($array[$key]);
            $array[$key]=$val;
        } else {
            if($to<0) $to=0;
            $new=array();       
            $x=0;
            foreach($array as  $i=>$v) {
                if($x++==$to) $new[$key]=$array[$key];
                if($i!==$key) $new[$i]=$v;
            }
            $array=$new;
        }
        return $array;
    }

    define("ARRAY_FUNCS",true);
}
/*
$a=array("04"=>"alpha",4=>"bravo","c"=>"charlie","d"=>"delta");
$b=array_move($a,4,2);

echo "here is \$a<pre>";
print_r($a);

$c=array( 1, 2 ,3);
$d=array(4,5,6);
$c = array_merge($c,$d);
print_r($c);
*/
?>