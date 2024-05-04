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
class core
{
    public static $root = '../';
    public static $ip; 
    public static $ip_via_proxy = 0;
    public static $ip_count = array();
    public static $user_agent;
    public static $user_id = false;
    public static $user_rights = 0;
    public static $user_data = array();

    private $flood_chk = 0;
    private $flood_interval = '120';
    private $flood_limit = '70';
	
	public static $conn;
	
	function __construct()
    {
		$ip = ip2long($_SERVER['REMOTE_ADDR']) or die('Invalid IP');
        self::$ip = sprintf("%u", $ip);

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $vars)) {
            foreach ($vars[0] AS $var) {
                $ip_via_proxy = ip2long($var);
                if ($ip_via_proxy && $ip_via_proxy != $ip && !preg_match('#^(10|172\.16|192\.168)\.#', $var)) {
                    self::$ip_via_proxy = sprintf("%u", $ip_via_proxy);
                    break;
                }
            }
        }

        if (isset($_SERVER["HTTP_X_OPERAMINI_PHONE_UA"]) && strlen(trim($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) > 5) {
            self::$user_agent = 'Opera Mini: ' . htmlspecialchars(mb_substr(trim($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']), 0, 150));
        } elseif (isset($_SERVER['HTTP_USER_AGENT'])) {
            self::$user_agent = htmlspecialchars(mb_substr(trim($_SERVER['HTTP_USER_AGENT']), 0, 150));
        } else {
            self::$user_agent = 'Not Recognised';
        }

        $this->ip_flood();
		$this->session_start();
		$this->db_connect();
        $this->authorize();
	}
	
	/*
    -----------------------------------------------------------------
    IP manzilni Fludga tekshirish
    -----------------------------------------------------------------
    */
    private function ip_flood()
    {
        if ($this->flood_chk) {
            if ($this->ip_whitelist(self::$ip))
            return true;
            $file = ROOTPATH . 'vendor/cache/ip_flood.dat';
            $tmp = array();
            $requests = 1;
            if (!file_exists($file)) $in = fopen($file, "w+");
            else $in = fopen($file, "r+");
            flock($in, LOCK_EX) or die("Cannot flock ANTIFLOOD file.");
            $now = time();
            while ($block = fread($in, 8)) {
                $arr = unpack("Lip/Ltime", $block);
                if (($now - $arr['time']) > $this->flood_interval) continue;
                if ($arr['ip'] == self::$ip) $requests++;
                $tmp[] = $arr;
                self::$ip_count[] = $arr['ip'];
            }
            fseek($in, 0);
            ftruncate($in, 0);
            for ($i = 0; $i < count($tmp); $i++) fwrite($in, pack('LL', $tmp[$i]['ip'], $tmp[$i]['time']));
            fwrite($in, pack('LL', self::$ip, $now));
            fclose($in);
            if ($requests > $this->flood_limit) {
                die('FLOOD: Siz tizimga keragidan ortiq so`rov yubormoqdasiz!');
            }
        }
    }

    /*
    -----------------------------------------------------------------
    "Oq ro`yxat"ni tekshirish
    -----------------------------------------------------------------
    */
    private function ip_whitelist($ip)
    {
        $file = ROOTPATH . 'vendor/cache/ip_wlist.dat';
        if (file_exists($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $val) {
                $tmp = explode(':', $val);
                if (!$tmp[1]) $tmp[1] = $tmp[0];
                if ($ip >= $tmp[0] && $ip <= $tmp[1]) return true;
            }
        }

        return false;
    }
	
	/*
    -----------------------------------------------------------------
    Sessiyaga start beramiz
    -----------------------------------------------------------------
    */
    private function session_start()
    {
        session_name('SESID');
        session_start();
    }
	
	/*
    -----------------------------------------------------------------
    Ma'lumotlar omboriga ulanish
    -----------------------------------------------------------------
    */
	private function db_connect(){
		require(ROOTPATH . 'vendor/db.php');
		$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
		return self::$conn = $conn;
	}	
	
	/*
    -----------------------------------------------------------------
    Foydalanuvchini avtorizatsiyadan o'tkizish
    -----------------------------------------------------------------
    */
    private function authorize()
    {
        $user_id = false;
        $user_ps = false;
        if (isset($_SESSION['uid']) && isset($_SESSION['ups'])) {
            $user_id = abs(intval($_SESSION['uid']));
            $user_ps = $_SESSION['ups'];
        } elseif (isset($_COOKIE['cuid']) && isset($_COOKIE['cups'])) {
            $user_id = abs(intval(base64_decode(trim($_COOKIE['cuid']))));
            $_SESSION['uid'] = $user_id;
            $user_ps = md5(trim($_COOKIE['cups']));
            $_SESSION['ups'] = $user_ps;
        }
        if ($user_id && $user_ps) {
			$req = self::$conn->query("SELECT * FROM `users` WHERE `id` = '$user_id'");
            if ($req->num_rows > 0) {
                $user_data = $req->fetch_assoc();
                $permit = $user_data['failed_login'] < 3 || $user_data['failed_login'] > 2 && $user_data['ip'] == self::$ip && $user_data['browser'] == self::$user_agent ? true : false;
                if ($permit && $user_ps === $user_data['password']) {
                    self::$user_id = $user_data['id'];
                    self::$user_rights = $user_data['rights'];
                    self::$user_data = $user_data;
                } else {
                    self::$conn->query("UPDATE `users` SET `failed_login` = `failed_login`+'1' WHERE `id` = '" . $user_data['id'] . "'");
                    $this->user_unset();
                }
            } else {
                $this->user_unset();
            }
        }
    }


    /*
    -----------------------------------------------------------------
	Foydalanuvchi xaqidagi ma`lumotlarni tozalash
    -----------------------------------------------------------------
    */
    private function user_unset()
    {
        self::$user_id = false;
        self::$user_rights = 0;
        self::$user_data = array();
        unset($_SESSION['uid']);
        unset($_SESSION['ups']);
        setcookie('cuid', '');
        setcookie('cups', '');
    }
	
}
?>