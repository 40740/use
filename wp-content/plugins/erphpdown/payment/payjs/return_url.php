<?php
require_once('../../../../../wp-load.php');

$re = get_option('erphp_url_front_success');
if($re)
	wp_redirect($re);
else{
	echo 'success';
	exit;
}

       
