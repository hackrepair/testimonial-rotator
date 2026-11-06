<?php
error_reporting(E_ALL);
set_error_handler(function($level,$message,$file,$line){ throw new ErrorException($message,0,$level,$file,$line); });
require_once TESTIMONIAL_ROTATOR_DIR . 'admin/metaboxes-rotator.php';
require_once TESTIMONIAL_ROTATOR_DIR . 'admin/metaboxes-testimonial.php';
wp_set_current_user(1);
$_POST = array();
$r = wp_insert_post(array('post_type'=>'testimonial_rotator','post_status'=>'publish','post_title'=>'Reborn fixture'));
$t = wp_insert_post(array('post_type'=>'testimonial','post_status'=>'publish','post_title'=>'Reborn testimonial','post_content'=>'Fixture testimonial content.'));
update_post_meta($t,'_rotator_id','|'.$r.'|');
update_post_meta($t,'_rating',5);
foreach(array_keys(testimonial_rotator_available_themes()) as $theme){
 $out = do_shortcode('[testimonial_rotator id="'.$r.'" template="'.$theme.'"]');
 if(strpos($out,'Fixture testimonial content.')===false)throw new RuntimeException('Missing body '.$theme);
 echo 'PASS template '.$theme."\n";
}
get_testimonial_rotator(array('id'=>999999999));
$_POST=array('timeout'=>'17');
testimonial_rotator_save_rotator_meta($r,get_post($r));
if(get_post_meta($r,'_timeout',true)!=='')throw new RuntimeException('Save without nonce accepted');
$_POST['testimonial_rotator_nonce']=wp_create_nonce('testimonial_rotator_save_'.$r);
testimonial_rotator_save_rotator_meta($r,get_post($r));
if(get_post_meta($r,'_timeout',true)!=='17')throw new RuntimeException('Authorized save failed');
wp_set_current_user(0);
$_POST['timeout']='23';
testimonial_rotator_save_rotator_meta($r,get_post($r));
if(get_post_meta($r,'_timeout',true)!=='17')throw new RuntimeException('Unauthorized save accepted');
echo "PASS metadata request guards\n";
restore_error_handler();
wp_delete_post($t,true);wp_delete_post($r,true);
echo 'PASS PHP '.PHP_VERSION."\n";
