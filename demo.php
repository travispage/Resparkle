<?php
file_put_contents("file.txt", "");
ini_set('max_execution_time', 300);
libxml_use_internal_errors(true);
$HTML = file_get_contents('http://203.155.220.231/scada/rainfall.aspx');
$dom = new DOMDocument;
$dom->loadHTML($HTML);
$allElements = $dom->getElementById('data-table')->getElementsByTagName('ul');
$count =  $allElements->length;

$arr = array();
for ($i=0; $i<$count;$i++) {
    foreach ($allElements->item($i)->getElementsByTagName('li') as $key =>$value) {
        if( $key == 1 || $key == 2 || $key == 3 || $key == 9 ){
            $arr[$i][] = $value->nodeValue; 
        }
    }
}

foreach ($arr as $key => $value) {

    if( $arr[$key][3] == "E19" || $arr[$key][3] == "E25" || $arr[$key][3] == "E26" ||  $arr[$key][3] == "E27" ){
        // unset($arr[$key][3]);
    }else{
        unset($arr[$key]);
    }

}
$file = 'file.txt';
foreach( $arr as $key => $details ) {

    $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($details));

    if($key === 0) {
        $keys = array();
        foreach($iterator as $k => $v) {
            $keys[] = $k;
        }
        echo implode(';', $keys);
    }

    $values = array();
    foreach($iterator as $val) {
        $values[] = $val;
    }

	$values = implode(";",$values);
    $values = $values.";";
    // echo "<pre>";
    // print_r($values);die;
    // echo PHP_EOL, implode(';', $values);die;

    file_put_contents($file, $values, FILE_APPEND | LOCK_EX);
}
?>