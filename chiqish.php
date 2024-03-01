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
$page_name = 'Tizimdan chiqish';
require('vendor/autoload.php');
if (isset($_POST['chiq'])){
	setcookie('cuid', '');
	setcookie('cups', '');
	session_destroy();
	header('location: /login.php');
	exit;
}else{
require('vendor/head.php');
?>
						<div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
										<form action="chiqish.php" method="POST">
											<h3 class="card-title">Tizimdan chiqish</h4>
											<p class="card-title-desc">Siz rostdan xam tizimdan chiqishni xoxlaysizmi?</p>
											<div class="d-flex flex-wrap gap-2">
												<button type="submit" name="chiq" class="btn btn-primary waves-effect waves-light me-1">
													Chiqish
												</button>
												<a href="/index.php" class="btn btn-secondary waves-effect">
													Yo`q
												</a>
											</div>
										</form>
                                    </div>
                                </div>
                            </div>
                        </div>
<?
require('vendor/end.php');	
}