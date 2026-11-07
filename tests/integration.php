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
 $single = get_testimonial_rotator(array('id'=>$r,'template'=>$theme,'is_single'=>true));
 if(strpos($single,'Fixture testimonial content.')===false)throw new RuntimeException('Missing single body '.$theme);
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
// Previously stored values must render inertly without resaving.
wp_set_current_user(1);
$attack = 'fade" onpointerenter="document.documentElement.dataset.reviewMeta=1';
update_post_meta($r, '_fx', $attack);
update_post_meta($r, '_title_heading', 'img onerror=alert(1)');
update_post_meta($t, '_rating', 999999);
foreach(array_keys(testimonial_rotator_available_themes()) as $theme) {
 $out = get_testimonial_rotator(array('id'=>$r, 'template'=>$theme, 'extra_classes'=>$attack));
 if(preg_match('/\sonpointerenter=/', $out) || strpos($out, '<img onerror') !== false) throw new RuntimeException('Executable attribute rendered');
 if(substr_count($out, 'testimonial_rotator_star_') > 5) throw new RuntimeException('Unbounded stars');
}
if(thrg_tr_template('../../../uploads/evil', 'loop', array()) !== realpath(TESTIMONIAL_ROTATOR_DIR.'templates/default/loop-testimonial.php')) throw new RuntimeException('Traversal fallback failed');
if(do_shortcode('[testimonial_rotator id="-1"]') !== '') throw new RuntimeException('Invalid ID accepted');
echo "PASS stored XSS sink regressions, rating bounds and template fallback\n";
$widget = new TestimonialRotatorWidget();
$values = $widget->update(array('title'=>'<script>alert(1)</script>Title', 'link_text'=>array('bad'), 'rotator_id'=>array('bad'), 'limit'=>array('bad')), array());
if($values['rotator_id'] !== '' || $values['link_text'] !== '') throw new RuntimeException('Malformed widget values retained');
ob_start(); $widget->form($values); $form=ob_get_clean();
if(strpos($form,'<script>alert(1)</script>') !== false) throw new RuntimeException('Widget form injected');
$_POST=array('testimonial_rotator_nonce'=>wp_create_nonce('testimonial_rotator_save_'.$t), 'rating'=>array('bad'), 'cite'=>wp_slash("O'Reilly <b>safe</b><script>alert(1)</script>"));
testimonial_rotator_save_testimonial_meta($t,get_post($t));
if(get_post_meta($t,'_rating',true) != 0 || strpos(get_post_meta($t,'_cite',true), "O'Reilly") === false || strpos(get_post_meta($t,'_cite',true), '<script>') !== false) throw new RuntimeException('Metadata normalization failed');
if(thrg_tr_css('</style><script>bad</script>') !== '/stylescriptbad/script') throw new RuntimeException('CSS tag delimiters retained');
echo "PASS widget malformed input, form escaping and unslashed cite metadata\n";
// Aggregate calls must include a sole rating and leave the caller's post untouched.
update_post_meta($t, '_rating', 5);
update_post_meta($t, '_rotator_id', '|'.$r.'|');
$caller_post = $GLOBALS['post'];
if (5.0 != testimonial_rotator_rating($r, 'rating')) throw new RuntimeException('Single testimonial average lost');
if ($GLOBALS['post'] !== $caller_post) throw new RuntimeException('Rating changed caller post');
$bad_icon = function() { return 'fa-star" onpointerenter="alert(1)'; };
add_filter('testimonial_rotator_star', $bad_icon);
$stars = testimonial_rotator_rating($r);
remove_filter('testimonial_rotator_star', $bad_icon);
if (preg_match('/class="[^"]*"\s+onpointerenter=/', $stars)) throw new RuntimeException('Rating icon broke attribute');
update_post_meta($r, '_prevnext', 1);
$out = get_testimonial_rotator(array('id'=>$r, 'prev_next'=>'false'));
if (strpos($out, 'data-cycletwo-next=') !== false) throw new RuntimeException('Explicit false override lost');
echo "PASS aggregate rating, caller post preservation, icon escaping and false overrides\n";
// Root containment: even a registered selection cannot escape via a file symlink.
$outside = tempnam(sys_get_temp_dir(), 'reborn-template-');
file_put_contents($outside, '<?php echo "outside-marker";');
$test_dir = TESTIMONIAL_ROTATOR_DIR . 'templates/reviewcontained';
mkdir($test_dir);
$register_test_theme = function($themes) { $themes['reviewcontained'] = array('title'=>'Containment fixture'); return $themes; };
add_filter('testimonial_rotator_themes', $register_test_theme);
try {
 symlink($outside, $test_dir.'/loop-testimonial.php');
 $resolved = thrg_tr_template('reviewcontained','loop',array());
 if ($resolved === realpath($outside)) throw new RuntimeException('Symlink escaped template root');
 unlink($test_dir.'/loop-testimonial.php');
 file_put_contents($test_dir.'/loop-testimonial.php','<?php echo "inside-marker";');
 if (thrg_tr_template('reviewcontained','loop',array()) !== realpath($test_dir.'/loop-testimonial.php')) throw new RuntimeException('Registered contained template rejected');
} finally {
 remove_filter('testimonial_rotator_themes', $register_test_theme);
 unlink($test_dir.'/loop-testimonial.php'); rmdir($test_dir); unlink($outside);
}
echo "PASS registered template containment and escaping symlink rejection\n";

restore_error_handler();
wp_delete_post($t,true);wp_delete_post($r,true);
echo 'PASS PHP '.PHP_VERSION."\n";
