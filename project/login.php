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

$headmod = 'login';
require('vendor/autoload.php');
if(core::$user_id){
    header('Location: index.php');
	exit;
}else{
?>


<?
$xato = '';
if (isset($_POST['submit'])){
	$captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : '5';
	$user_login = isset($_POST['user_login']) ? trim($_POST['user_login']) : NULL;
	$user_pass = isset($_POST['user_pass']) ? trim($_POST['user_pass']) : NULL;
	$user_mem = isset($_POST['mem']) ? 1 : 0;
	$user_code = isset($_POST['code']) ? trim($_POST['code']) : NULL;
	$xato = isset($xato) ? trim($xato) : NULL;
	if (empty($xato)){
		$req = $conn->query("SELECT * FROM `users` WHERE `login`='".functions::check_in($user_login)."' LIMIT 1");
		if (mysqli_num_rows($req)) {
			$user = $req->fetch_assoc();
			if (md5(md5($user_pass)) == $user['password']) {
				if (isset($_POST['mem'])) {
                   $cuid = base64_encode($user['id']);
                   $cups = md5($user_pass);
				   setcookie("cuid", $cuid, time() + 3600 * 24 * 30);
				   setcookie("cups", $cups, time() + 3600 * 24 * 30);
                   }
                   $_SESSION['uid'] = $user['id'];
                   $_SESSION['ups'] = md5(md5($user_pass));
				   $conn->query("UPDATE `users` SET `failed_login` = '0' WHERE `id` = '".functions::check_in($user['id'])."'");
				header('location: /index.php');
				exit;
			}else{
                if ($user['failed_login'] < 3) {
                   $conn->query("UPDATE `users` SET `failed_login` = `failed_login`+'1' WHERE `id` = '".functions::check_in($user['id'])."'");
                }
                $xato = "Tizimga kirishda xatolik!";
            }
		} else {
            $xato = "Tizimga kirishda xatolik!";
        }
	}
}
?>
             <form action="/login.php" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label" for="username">Login</label>
                                            <input type="text" name="user_login" class="form-control" id="username" placeholder="Loginni kiriting">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="userpassword">Parol</label>
                                            <input type="password" name="user_pass" class="form-control" id="userpassword" placeholder="Parolni kiriting">
                                        </div>
                
                                        <div class="form-check">
                                            <input type="checkbox" name="mem" class="form-check-input" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Eslab qolish</label>
                                        </div>
                                        
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" name="submit" type="submit">Tizimga kirish</button>
                                        </div>
                                    </form>

<?
}
