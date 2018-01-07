<?php
$imgs  = scandir('../assets/backgrounds');

$rand = rand(3,count($imgs)- 1);

echo "<img src='/assets/backgrounds/{$imgs[$rand]}' border=0>"; 
