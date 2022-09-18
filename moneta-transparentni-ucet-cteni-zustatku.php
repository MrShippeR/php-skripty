<?php
    $url  = 'https://transparentniucty.moneta.cz/homepage?accountNumber=245355790';
    $html = file_get_contents($url);
    
    $html = get_string_between($html, '<table class="normal compact" id="transparentAccountTable">', '<tr class="pagination">');
    $html = preg_replace('/^\h*\v+/m', '', $html); // remove empty lines from string
    
    $table_rows = array(); // [0] => datum_zauctovani; [1] => nazev_uctu; [2] => datum_transakce; [3] => variabilni_symbol; [4] => castka
    
    require('simple_html_dom.php'); // library for parsing html to php_array
    // DOCS https://enb.iisd.org/_inc/simple_html_dom/manual/manual.htm#section_callback
    $html_dom = str_get_html($html);
    
    foreach($html_dom->find('td') as $row) {
       $row = strip_tags($row);
       $row = str_replace('&nbsp;', '', $row); // remove from nazev_uctu html chart
       $table_rows[] = $row;
    }
    
    if(empty($table_rows)) {
        echo ('Na stránce s bankovnictvím nejsou žádné vypsané platby, nebo se stránka nepodařila načíst: <a href="'.$url.'">Moneta banking</a>');
        exit;
    }
    $payment_per_row = array_chunk($table_rows,5); // each payment is new array in multidimensional array
    
    // print_r($payment_per_row);
    // example output Array ( [0] => Array ( [0] => 11.9.2022 [1] => Marek Vach [2] => 12.9.2022 [3] => -------------- [4] => 1 CZK ) [1] => Array ( [0] => 9.9.2022 [1] => Marek Vach [2] => 9.9.2022 [3] => 950906 [4] => 10 CZK ) )

    
    
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
?>
