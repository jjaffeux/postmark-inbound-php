<?php


$file = file_get_contents('/Users/joffreyjaffeux/Desktop/landscape-z2n7.png');

echo "<textarea>";
echo base64_encode($file);
echo "</textarea>";