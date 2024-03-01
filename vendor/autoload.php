<?
/**
 * @package     MilliyCore
 * @link        https://milliysoft.uz/mCore
 * @copyright   Copyright (C) 2022-2023 MilliySoft DTM
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      https://riack5h3n.uz
 */

defined('_MILLIY_CORE') or die('403');
// Error_Reporting(E_ALL & ~E_NOTICE);
ini_set('session.use_trans_sid', '0');
ini_set('arg_separator.output', '&amp;');
// ini_set('display_errors', 'Off');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');
/*
-----------------------------------------------------------------
ROOTPATH
-----------------------------------------------------------------
*/
define('ROOTPATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/*
-----------------------------------------------------------------
Klasslarni avtomatik tarzda ulash
-----------------------------------------------------------------
*/
spl_autoload_register(function ($class) {
    include ROOTPATH.'vendor/classes/' . $class . '.php';
});
/*
-----------------------------------------------------------------
Yadroni ulash
-----------------------------------------------------------------
*/
new core;
/*
-----------------------------------------------------------------
Foydalanuvchi xaqidagi o`zgaruvchilarni qabul qilib olamiz
-----------------------------------------------------------------
*/
$rootpath = ROOTPATH;
$ip = core::$ip;
$agn = core::$user_agent;
$user_id = core::$user_id;
$rights = core::$user_rights;
$datauser = core::$user_data;
$conn = core::$conn;
$login = isset($datauser['name']) ? $datauser['name'] : false;
$kmess = 10;

function validate_referer()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (@!empty($_SERVER['HTTP_REFERER'])) {
        $ref = parse_url(@$_SERVER['HTTP_REFERER']);
        if ($_SERVER['HTTP_HOST'] === $ref['host']) return;
    }
    die('Invalid request');
}

if ($rights) {
    validate_referer();
}

/*
-----------------------------------------------------------------
Global o`zgaruvchilarni qabul qilib olamiz
-----------------------------------------------------------------
*/
$id = isset($_REQUEST['id']) ? abs(intval($_REQUEST['id'])) : false;
$user = isset($_REQUEST['user']) ? abs(intval($_REQUEST['user'])) : false;
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$mod = isset($_REQUEST['mod']) ? trim($_REQUEST['mod']) : '';
$do = isset($_REQUEST['do']) ? trim($_REQUEST['do']) : false;
$page = isset($_REQUEST['page']) && $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
$start = isset($_REQUEST['page']) ? $page * $kmess - $kmess : (isset($_GET['start']) ? abs(intval($_GET['start'])) : 0);
$headmod = isset($headmod) ? $headmod : '';
$page_name = isset($page_name) ? trim($page_name) : 'home';

// if (!$user_id){
	// if ($headmod != 'login'){
		// header('location: /login.php');
		// exit;
	// }
// }
?>