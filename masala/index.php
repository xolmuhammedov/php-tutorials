<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
    $gift = isset(($_POST['gift'])) ? $_POST['gift'] : 0;
    $a = isset(($_POST['a'])) ? $_POST['a'] : 0;
    $b = isset(($_POST['b'])) ? $_POST['b'] : 0;
    $c = isset(($_POST['c'])) ? $_POST['c'] : 0;

    $f = $a+$b+$c;
    if ($gift < $f) {
        echo "Yetadi pullari";
    } else {
        echo "Yetmaydikan";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hi</title>
</head>
<body>
    <form action="/masala/" method="post">
        <label for="gift">Sovg'ani narxi</label>
        <input type="number" name="gift">
        <br>
        <label for="a">a sister</label>
        <input name="a" type="number">
        <br>
        <label for="b">b sister</label>
        <input name="b" type="number">
        <br>
        <label for="c">c sister</label>
        <input name="c" type="number">
        <input type="submit" value="TUGATISH">
    </form>
</body>
</html>