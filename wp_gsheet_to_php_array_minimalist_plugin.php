<?php
/*
Plugin Name: Kamzíci - bodovací google tabulka
Description: Umožňuje shortcodem [kamzici-gtabulka url="http://example" skryte_sloupce="B,C" plovouci_sloupce="A,D"] vkládat tabulku gSheet do webu s upraveným formátováním.
Version: 1.0
Author: Marek "Moris" Vach
*/

function kambodgt_display_shortcode( $atts ){
	$a = shortcode_atts( array(
		'url' => '',
		'skryte_sloupce' => '',
      'plovouci_sloupce' => '',
	), $atts );
   // return "url = {$a['plovouci_sloupce']}";
   
   $table_as_array = kambodgt_return_html_table_as_array($a['url']);
   
   $html_table = kambodgt_return_new_table($table_as_array);
   
   //return print_r($table_as_array[28]);
   return $html_table;
}
add_shortcode( 'kamzici-gtabulka', 'kambodgt_display_shortcode' );


function kambodgt_return_html_table_as_array($url) {    
    include plugin_dir_path( __FILE__ ) . 'includes/simple_html_dom.php'; // library for parsing html to php_array
    // DOCS https://enb.iisd.org/_inc/simple_html_dom/manual/manual.htm#section_callback
    
    $html_dom = file_get_html($url);
    $table_rows = array();
    foreach($html_dom->find('tr') as $row) {  
      $cells = array();  
      foreach($row->find('td') as $cell) {
         $cells[] = $cell->plaintext;
      }
      if (!empty($cells))
      $table_rows[] = $cells;
    }
    
    return $table_rows;
}

function kambodgt_return_new_table($table_as_array) {
   $table = '<table class="kamzici-gtabulka">';
   
   $table .= '<tr>';
   foreach ($table_as_array[0] as $cell) {
      $table .= '<th>';
      $table .= $cell;
      $table .= '</th>';      
   }
   $table .= '</tr>';
   
   for ($i = 1; $i < count($table_as_array); $i++) {
      $row = $table_as_array[$i];
      $table .= '<tr>';
      
      foreach ($row as $cell) {
         $table .= '<td>';
         $table .= $cell;
         $table .= '</td>';
      }
      $table .= '</tr>';      
      
   }
   $table .= '</table>';
   return $table;
}

























