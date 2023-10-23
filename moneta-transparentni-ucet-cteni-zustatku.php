<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function kamprnavMonetaBankingParseTable() {
    $url = 'https://transparentniucty.moneta.cz/245355790';
    $html = gzdecode( file_get_contents( $url ));

    
    if (str_contains($html, 'Pro tento transparentní účet nejsou evidovány žádné transakce.'))
        return 'Na stránce s bankovnictvím nejsou žádné vypsané platby, <a href="'.$url.'">Moneta banking</a>';
    
    // https://stackoverflow.com/questions/7130867/remove-script-tag-from-html-content
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    
    require_once(plugin_dir_path(__FILE__) . 'includes/simple_html_dom.php');
    // DOCS https://enb.iisd.org/_inc/simple_html_dom/manual/manual.htm#section_callback
    
    $html_dom = new simple_html_dom();
    $html_dom->load($html);
    
    $table = $html_dom->find('table', 0);
    
    if ($table) {
        $tableRows = array();

        foreach ($table->find('tr') as $row) {
            $rowCells = array();

            foreach ($row->find('td') as $cell) {
                $rowCells[] = $cell->plaintext;
            }
        $tableRows[] = $rowCells;
        }
        
    // return $tableRows;
    }
    else
        return "Tabulka nebyla nalezena.";
    
    // Uvolnění paměti
    $html_dom->clear();
    
    // remove empty arrays
    $tableRows = array_filter($tableRows);
 
    // reindex array
    $tableRows = array_values($tableRows);
    
    // reformate array to suit old code
    $r_tableRows = array();
    
    foreach ($tableRows as $key => $row) {
        $r_tableRows[$key][0] = $tableRows[$key][1];
        $r_tableRows[$key][1] = $tableRows[$key][3];
        $r_tableRows[$key][2] = $tableRows[$key][1];
        $r_tableRows[$key][3] = $tableRows[$key][5];
        $r_tableRows[$key][4] = $tableRows[$key][7];
    }
    
    return $r_tableRows;
    // example output:
    // Array (  [0] => Array ( [0] => 11.9.2022 [1] => Marek Vach [2] => 12.9.2022  [3] => --------------   [4] => 1 CZK )
    //          [1] => Array ( [0] => 9.9.2022  [1] => Marek Vach [2] => 9.9.2022   [3] => 950906           [4] => 10 CZK ) )

}

function kamprnavGetStringBetween($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

// https://stackoverflow.com/questions/35367907/file-get-contents-returns-unreadable-text-for-a-specific-url
if( !function_exists('gzdecode') ){
    function gzdecode( $data ){ 
        $g=tempnam('/tmp','ff'); 
        @file_put_contents( $g, $data );
        ob_start();
        readgzfile($g);
        $d=ob_get_clean();
        unlink($g);
        return $d;
    }   
}























