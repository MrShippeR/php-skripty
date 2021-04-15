<?php 

    function get_string_between($string, $start, $end){
       $string = ' ' . $string;
       $ini = strpos($string, $start);
       if ($ini == 0) return '';
       $ini += strlen($start);
       $len = strpos($string, $end, $ini) - $ini;
       return substr($string, $ini, $len); 
    }

   $vypis_uctu = file_get_contents("https://ib.fio.cz/ib/transparent?a=2701804895");
   $vypis_uctu = get_string_between($vypis_uctu, '<table class="table">', '</table>');   
  
  // $vypis_uctu = str_replace(array("\r\n", "\r"), "\n", $vypis_uctu);
   $vypis_uctu = explode("CZK", $vypis_uctu);
   $zustatek = $vypis_uctu[5];
   $zustatek = str_replace(",00", "", $zustatek);
   $zustatek = str_replace(" ", "", $zustatek);
   
    echo ('<a href="https://ib.fio.cz/ib/transparent?a=2701804895">Zůstatek účtu je '.$zustatek.',-Kč.</a>');
?>
