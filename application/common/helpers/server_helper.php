<?php 
/**
 * Common Function
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @param bool $strict Optional. Whether to be strict about the end of the string. Defaults true.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data, $strict = true ) {
	// if it isn't a string, it isn't serialized
	if ( ! is_string( $data ) )
		return false;
	$data = trim( $data );
 	if ( 'N;' == $data )
		return true;
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	if ( ':' !== $data[1] )
		return false;
	if ( $strict ) {
		$lastc = $data[ $length - 1 ];
		if ( ';' !== $lastc && '}' !== $lastc )
			return false;
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace )
			return false;
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== $data[ $length - 2 ] )
					return false;
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
			// or else fall through
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}

/**
 * Check whether serialized data is of string type.
 *
 * @since 2.0.5
 *
 * @param mixed $data Serialized data
 * @return bool False if not a serialized string, true if it is.
 */
function is_serialized_string( $data ) {
	// if it isn't a string, it isn't a serialized string
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	elseif ( ':' !== $data[1] )
		return false;
	elseif ( ';' !== $data[$length-1] )
		return false;
	elseif ( $data[0] !== 's' )
		return false;
	elseif ( '"' !== $data[$length-2] )
		return false;
	else
		return true;
}

/**
 * Serialize data, if needed.
 *
 * @since 2.0.5
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	// Double serialization is required for backward compatibility.
	// See http://core.trac.wordpress.org/ticket/12930
	if ( is_serialized( $data, false ) )
		return serialize( $data );

	return $data;
}


// 输出json数据
if(!function_exists('json_response')){
	function json_response($obj, $callback = ''){
		$CI = &get_instance();
		if($callback == ''){
			$CI->output->set_output(json_encode($obj));
		}else{
			$CI->output->set_output($CI->input->get($callback) . '(' . json_encode($obj) . ')');
		}
		$CI->output->_display();
		exit();
	}
}

// 角色列表
function roles_list(){
	$CI = &get_instance();
	$results = $CI->db->get('roles')->result();
	return $results;
}

// 检查权限
if(!function_exists('check_permission')){
	function check_permission($permKey, $json = TRUE){
		$CI = &get_instance();
		$user = $CI->load->get_var('user');
		$perms = $user->userPerms;
		if(isset($perms[$permKey]) && $perms[$permKey]){
			
		}else{
			if($json){
				json_response(array('success' => FALSE, 'msg' => 'You do not have permission to operate:' . $permKey));
			}else{
				// set_status_header(500);
				$data['errorString'] = '500';
				$content = $CI->load->view($CI->admin_theme . 'member/500', $data, TRUE);
				$CI->output->set_output($content);
				$CI->output->_display();
				exit;
			}
		}
	}
}

if(!function_exists('hasPermission')){
	function hasPermission($permKey){
		$CI = &get_instance();
		$user = $CI->load->get_var('user');
		$perms = $user->userPerms;
		if(isset($perms[$permKey]) && $perms[$permKey]){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

// add permission
function addPermission($permName, $permKey, $parent_id, $permType = 0, $rel_id = 0){
	$CI = &get_instance();
	$data = array(
		'permName' => $permName,
		'permKey' => $permKey,
		'parent_id' => $parent_id,
		'permType' => $permType,
		'rel_id' => $rel_id
	);
	$CI->db->insert('permissions', $data);
	$new_id = $CI->db->insert_id();
	return $new_id;
}

// 截取一段文字
if(!function_exists('str_cut')){
	function str_cut($str, $sublen, $etc = '...')
	{
			if(strlen($str)<=$sublen) {
					$rStr = $str;
			} else {
					$I = 0;
					while ($I<$sublen) {
							$StringTMP = substr($str,$I,1);
	 
							if (ord($StringTMP)>=224) {
									$StringTMP = substr($str,$I,3);
									$I = $I + 3;
							} elseif (ord($StringTMP)>=192) {
									$StringTMP = substr($str,$I,2);
									$I = $I + 2;
							} else {
									$I = $I + 1;
							}
							$StringLast[] = $StringTMP;
					}
	 
					$rStr = implode('',$StringLast).$etc;
			}
	 
			return $rStr;
	}
}

// permission list
function permissions_list(){
	$CI = &get_instance();
	$results = $CI->db->get('permissions')->result();
	return $results;
}

// 清除缓存
if(!function_exists('cxp_update_cache')){
	function cxp_update_cache($site_id = 0, $cachekey = ''){
		$CI = &get_instance();
		delete_files(FCPATH . 'application/common/cache/', FALSE, TRUE);
	}
}