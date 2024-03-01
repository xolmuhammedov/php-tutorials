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

if (isset($_GET['err404'])){
	$act = 404;
}elseif(isset($_GET['err500'])){
	$act = 500;
}

switch ($act) {
    case '404':
	$page_name = 'Xatolik';
	require('vendor/head.php');
    ?>
	
	<?
    break;     
	case '500':
	$page_name = 'Xatolik';
	require('vendor/head.php');
	?>
	
	<?
	break;
    default:
	$page_name = 'Boshqaruv paneli';
	require('vendor/head.php');
}
?>

<?php
require('vendor/end.php');
?>