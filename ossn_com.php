<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright 2014-2018 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/
 */
define('Mentions', ossn_route()->com . 'Mentions/');
/**
 * Mentions  Init
 *
 * @return void
 */
function mentions_init() {
		ossn_extend_view('js/opensource.socialnetwork', 'mentions/js');
		ossn_extend_view('css/ossn.default', 'mentions/css');

		if(ossn_isLoggedin()) {
				ossn_load_external_js('tribute.js');

				ossn_register_page('mentions_picker', 'mentions_picker');

				ossn_add_hook('comment:view', 'template:params', 'mentions_tag_user', 150);

				ossn_new_external_js('tribute.js', ossn_add_cache_to_url('components/Mentions/vendors/tribute.js'));
				ossn_extend_view('forms/OssnComments/comment/edit', 'mentions/comment_edit');
		}
}
/**
 * Replace in the comments
 *
 * @param string $hook comment:view
 * @param string $callback template:params
 * @param array  $params Comment Data
 *
 * @return array
 */
function mentions_tag_user($hook, $type, $return, $params) {
		if(isset($return['comment']['comments:entity'])) {
				$return['comment']['comments:entity'] = mentions_replace_usernames($return['comment']['comments:entity']);
		}
		if(isset($return['comment']['comments:post'])) {
				$return['comment']['comments:post'] = mentions_replace_usernames($return['comment']['comments:post']);
		}
		return $return;
}
/**
 * Replace @mention with profile URL
 *
 * @return string|string
 */
function mentions_replace_usernames($text) {
		$url = ossn_site_url('u/');
		return preg_replace_callback('/@(\w+)/', 'replace_usernames_mentions_links', $text);
}
/**
 * Mentions Picker
 * Copied from OssnWall friends picker added username
 *
 * return void
 */
function mentions_picker() {
		header('Content-Type: application/json');
		if(!ossn_isLoggedin()) {
				exit();
		}
		$user    = new OssnUser();
		$friends = $user->getFriends(ossn_loggedin_user()->guid);
		if(!$friends) {
				return false;
		}
		$search_for = input('q');
		// allow case insensitivity with first typed in char
		$fc         = mb_strtoupper(mb_substr($search_for, 0, 1, 'UTF-8'), 'UTF-8');
		$search_For = $fc . mb_substr($search_for, 1, null, 'UTF-8');
		// show all friends with wildcard '*' in first place
		if($search_for == '*') {
				$search_for = '';
				$search_For = '';
		}
		$search_len = mb_strlen($search_for, 'UTF-8');
		foreach ($friends as $users) {
				$first_name_start = mb_substr($users->first_name, 0, $search_len, 'UTF-8');
				if($first_name_start == $search_for || $first_name_start == $search_For) {
						$p['key'] 		 = $users->fullname;
						$p['imageurl']   = $users->iconURL()->smaller;
						$p['value']   = $users->username;
						$usera[]         = $p;
				}
		}
		echo json_encode($usera);
}
/** 
 * Replace preg_match_callback
 *
 * @access private
 * @return string
 */
function replace_usernames_mentions_links($matches){
				if($user = ossn_user_by_username($matches[1])){
						return ossn_plugin_view('output/url', array(
								'href' => $user->profileURL(),
								'_target' => 'blank',
								'text' => $user->fullname,
								'class' => 'mentions-user',
						));
				} 
				return '@'.$matches[1];
}
ossn_register_callback('ossn', 'init', 'mentions_init');