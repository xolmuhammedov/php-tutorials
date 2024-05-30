<?php
function getStatus($paymentStatus , $showMessage = true) : string {
  $message = match($paymentStatus) {
    1 => 'Success',
    2 => 'Denied',
    default => 'Unknown'
  };

  if($showMessage){
    var_dump($message);
  }

  return $message;
}
$statusMessage = getStatus(1);
var_dump($statusMessage);
?>
