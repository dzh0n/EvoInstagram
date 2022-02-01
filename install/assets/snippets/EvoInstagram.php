/**
 * EvoInstagram
 * 
 * EvoInstagram
 * 
 * @category    snippet
 * @version     0.0.1
 * @internal    @modx_category Content

*/
//<?php
require_once MODX_BASE_PATH . 'assets/snippets/EvoInstagram/Instagram.class.php';
	
if (empty($params['token'])) {
    return 'Token required';
}

$result = '';
$items = [];
$imagesDir = 'assets/images/instagram';

if (!is_dir(MODX_BASE_PATH . $imagesDir)) {
            mkdir(MODX_BASE_PATH . $imagesDir, 0744, true);
        }

$token = $token;
$inst = new Instagram($token);
$inst->count_post = $display;
$instPosts = $inst->getInstagramPosts();

if(isset($instPosts)) {
	foreach($instPosts['data'] as $post) {
		
		$thumb = $imagesDir.'/'.$post['id'].'.jpg';
		$imageLocal = MODX_BASE_PATH . $thumb;
		
		if (!is_readable($imageLocal)) {
            if ($post['media_type'] == 'VIDEO') {
                $imageUrl = $post['thumbnail_url'];
            } else {
                $imageUrl = $post['media_url'];
            }

            $raw = file_get_contents($imageUrl);
            file_put_contents($imageLocal, $raw);
        }
		
		
		$items[] = $modx->parseChunk($tpl, [
		 'images.standard_resolution.url' => $thumb,
			'link' => $post['permalink'],
			'caption.text' => $post['caption'],
			'created_time' => strtotime($post['timestamp'])
		], '[+', '+]');
	}
	
}

return $modx->parseChunk($ownerTPL,['dl.wrap' => implode(' ', $items)], '[+', '+]');
