<?php
function addto($x) {
  return function($y) use ($x) {
    return $x + $y;
  };
}

$fourplus = addto(4);
echo $fourplus(3);
?>
