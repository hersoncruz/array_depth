<?php
namespace HersonCruz\ArrayDepth;

class ArrayDepth
{
        /*return array depth (level of interleaving) */
        public static function epth($array,$reset=true){
          if(!function_exists('array_in')){
            function  array_in($array){
              if(is_array($array)){
                foreach($array as $val){
                  if(is_array($val)) return true;
                }
              }
              return false;
            }
          }
          static $i=1;
          if($reset) {
            $i=1;
          }
          if(is_array($array)){
            if(array_in($array)){
              $i++;
              foreach($array as $v){
                if(array_in($v)) {
                  self::epth($v,false);
                }
              }
            }
          }else{
            return 0;
          }
          return $i;
        }

        /* return all data types used in the given array */
        public static function typesof($array,$reset=true){
          static $types=[];
          if($reset) $types=[];
          if(is_array($array)){
            foreach($array as $v){
              if(is_array($v)){
                $types[]=gettype($v);
                self::typesof($v,false);
              }
              else{
                $types[]=gettype($v);
              }
            }
            return $types;
          }else{
            return [];
          }
        }

        /* return the data type(s) used in the first element of the array*/
        public static function signature($array){
          if(is_array($array)){
            reset($array);
            $current=current($array);
            if(is_array($current)&&!empty($current)){ 
            $types=typesof(current($array));
            array_unshift($types,'array');
            return $types;
            }
            else return array(gettype($current));
          }else{
            return array(gettype($array));
          }
        }

        /* return true if all the elements of the array have the same signature
          as the first element and the data type used in the array  */
        public static function homogeneous($array,&$type=null){
          if(is_array($array)){
            $types=self::typesof($array);
            $signature=self::signature($array);
            $chunks=array_chunk($types,count($signature));
            foreach($chunks as $vals){
              if($vals!==$signature){
                if($type) $type=null;
                return false;
              }
            }
            if($type){
              $type=current($signature)==='array'?'array':current($signature);
            }
            return true;
          }else{
            if($type) $type=gettype($array);
            return false;
          }
        }
        /* return true if key signature of the first element of the array is the 
        same for all the elements in the array*/

        public static function rowsandcol($array,&$ksignature){
          if(is_array($array)){
            if(is_array($vc=current($array)))
            $ksignature=array_keys($vc);
            else return false;
            foreach($array as $k=>$v){
              if(!is_array($v)) return false; 
              else{
                if(array_keys($v)!==$ksignature) return false;
              }
            }
            return  true;
          }
          return false;
        }
}


  /*return array depth (level of interleaving) */

  function array_depth($array){
    return ArrayDepth::epth($array);
  }
  /* return all data types used in the given array */
  function typesof($array){
    return ArrayDepth::typesof($array);
  }
  /* return true if all the elements of the array have the same signature
    as the first element and the data type used in the array  */
  function homogeneous($array,&$type=null){
    return ArrayDepth::homogeneous($array,$type);
  }
  /* return the type(s) used in the first element of the array*/
  function signature($array){
    return ArrayDepth::signature($array);
  }
  /* return true if key signature of the first element of the array is the 
    same for all the elements in the array */
  function rowsandcol($array,&$ksignature=null){
    return ArrayDepth::rowsandcol($array,$ksignature);
  }

?>
