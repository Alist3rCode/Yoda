<?php
$handle = fopen("pwd", "r");
// $handle = popen("cd", 'r');
echo $handle;
while(!feof($handle)) {
    $buffer = fgets($handle);
    echo $buffer;
    ob_flush();
    flush();
}
pclose($handle);
?>