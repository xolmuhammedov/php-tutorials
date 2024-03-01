<?php
/**
 * @package     MilliyCore
 * @link        https://milliysoft.uz/mCore
 * @copyright   Copyright (C) 2022-2023 MilliySoft DTM
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      https://riack5h3n.uz
 */
define('_MILLIY_CORE', 1);
require('vendor/autoload.php');
require('vendor/head.php');
if(isset($_POST['submit'])){
    $fio = isset($_POST['fio']) ? $_POST['fio'] : false;
    $telefon = isset($_POST['telefon']) ? $_POST['telefon'] : false;
    $manzil = isset($_POST['manzil']) ? $_POST['manzil'] : false;
    if (empty($fio)){
        $xato = 'FIO maydoni bo`sh';
        $xato_fio = 'FIO maydoni bo`sh';
        $xato_fio_style = "style='border: 1px solid red;'";
    }
    if (empty($telefon)){
        $xato = 'Telefon maydoni bo`sh';
        $xato_telefon = 'Telefon maydoni bo`sh';
        $xato_telefon_style = "style='border: 1px solid red;'";
    }
    if (empty($manzil)){
        $xato = 'Manzil maydoni bo`sh';
        $xato_manzil = 'Manzil maydoni bo`sh';
        $xato_manzil_style = "style='border: 1px solid red;'";
    }
    if(empty($xato)){
        // functions::check_in() bu filtratsiya
        $conn->query("INSERT INTO `mijozlar` SET
            `fio` = '".functions::check_in($fio)."',
            `telefon` = '".functions::check_in($telefon)."',
            `manzil` = '".functions::check_in($manzil)."',
            `status` = '1',
            `reg_sana` = '".date('d.m.Y')."',
            `time` = '".time()."'
        ");

        
    }
}
?>
<form action="/mijozlar.php" method="POST">
    FIO: <br>
    <input type="text" name="fio" placeholder="FIO" value=""/><br>
    Telefon:<br>
    <input type="text" name="telefon" placeholder="Telefon" value=""/><br>
    Manzil:<br>
    <input type="text" name="manzil" placeholder="Manzil" value=""/><br>
    <input type="submit" name="submit" value="Qo'shish" />
</form>

<table border="1">
    <tr>
        <th>FIO</th>
        <th>Telefon</th>
        <th>Manzil</th>
        <th>Qo'shilgan sana</th>
        <th>Holati</th>
    </tr>
    <?
    $mijoz_s = $conn->query("SELECT * FROM `mijozlar` WHERE `status` != '3'");
    if($mijoz_s->num_rows == 0){
    ?>
        <tr>
            <td colspan="5"><center>Hozicha mijozlar mavjud emas!</center></td>
        </tr>
    <?
    }else{
        while($mijoz = $mijoz_s->fetch_assoc()){
        if($mijoz['status'] == 1){
            $holati = 'Aktiv';    
        }elseif($mijoz['status'] == 0){
            $holati = 'Faol emas';    
        }elseif($mijoz['status'] == 2){
            $holati = 'Bloklangan'; 
        }
        ?>
        <tr>
            <td><?=$mijoz['fio']?></td>
            <td><?=$mijoz['telefon']?></td>
            <td><?=$mijoz['manzil']?></td>
            <td><?=$mijoz['reg_sana']?></td>
            <td><?=$holati?></td>
        </tr>
        <?
        }
    }
    ?>
</table>
<?
require('vendor/end.php');