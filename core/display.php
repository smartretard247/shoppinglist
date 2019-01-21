<?php 

function StartTable($params = '') {
    echo '<table '. $params . '><tr>';
}

function EndTable() {
    echo "</tr></table>";
}

function TH($header, $span = '1') {
    echo '<th colspan="' . $span . '">' . $header . '</th>';
}

function TH2($header, $params = '') {
    echo '<th ' . $params . '>' . $header . '</th>';
}

function TR($data, $span = '1') {
    echo '<tr colspan="' . $span . '">"' . $data . '</tr>';
} 

function NoDataRow($array, $colspan) {
    if($array[0] == 0) {
	echo '<tr><td colspan="' . $colspan . '"><b>No data exists in the table.</b></td></tr>';
	}
}

function DisplayFileName() {
  $serverPhpSelf = filter_input(INPUT_SERVER, "PHP_SELF");
  echo $serverPhpSelf . '<br/>';
}

function AlphabetLinks() {
  $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
  $first = true;
  foreach ($alphabet as $letter) {
    if($first) {
      $first = false;
      echo "<a href='#$letter' style='font-size: 11pt;'>$letter</a>";
    } else {
      echo " | <a href='#$letter' style='font-size: 11pt;'>$letter</a>";
    }
  }
  
  echo "<br/><br/>";
}