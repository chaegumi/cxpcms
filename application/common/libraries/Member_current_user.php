<?php 
/**
 * Member_Current_User
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2013 chaegumi
 * @email		chaegumi@qq.com
 * @filesource
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_Current_User{

	private static $user;
	
	private static $ci;
	
	function __construct(){
		self::$ci = &get_instance();
		self::$ci->load->library('session');
	}
	
	public static function user(){
		self::$ci = &get_instance();
		if(self::$ci->session->userdata('member_userid')){
			self::$ci->db->where('id', self::$ci->session->userdata('member_userid'));
			$user1 = self::$ci->db->get('users')->row();
			if(isset($user1)){
				self::$ci->load->library('Member_acl');
				$my_acl=new Member_acl($user1->id);
				// 用户角色
				$userRoles = $my_acl->getUserRoles();
				$user1->userRoles = $userRoles;
				// 用户权限
				$userPerms = $my_acl->getPermArr('mini');
				$user1->userPerms = $userPerms;
				return $user1;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public static function login($username, $password){
		self::$ci = &get_instance();
		self::$ci->load->helper(array('security'));
		self::$ci->db->where('username', $username);
		self::$ci->db->or_where('email', $username);
		$u = self::$ci->db->get('users')->row();

		if(isset($u)){
			if($u->status){
				if(password_verify($password, $u->password)){	
					$ip = self::$ci->input->ip_address();
					$data = array(
						'cur_login_time' => date('Y-m-d H:i:s'),
						'cur_login_ip' => $ip,
						'cur_login_area' => '', // convert ip to area
						'last_login_ip' => $u->cur_login_ip,
						'last_login_area' => $u->cur_login_area,
						'last_login_time' => $u->cur_login_time,
						'login_times' => ($u->login_times + 1)
					);
					self::$ci->db->where('id', $u->id);
					self::$ci->db->update('users', $data);
					
					self::$ci->session->set_userdata('member_userid', $u->id);
					self::$ci->session->set_userdata('IsAuthorized', TRUE);
					self::$ci->session->set_userdata('member_companyid', $u->company_id);
					
					
					session_write_close();
					self::$user = $u;
					
					// return TRUE;
					return $u;
				}else{
					self::$ci->session->set_flashdata('perr', 'Error Password');
					session_write_close();
					return FALSE;
				}				
			}else{
				self::$ci->session->set_flashdata('perr', 'User Status Disable');
				session_write_close();
				return FALSE;
			}
		}else{
			self::$ci->session->set_flashdata('perr', 'User Do not Exist');
			session_write_close();
			return FALSE;
		}
	}
	
	public static function logout(){
		self::$ci = &get_instance();
		self::$ci->session->unset_userdata('member_userid');
		self::$ci->session->sess_destroy();
		session_unset();
		session_destroy();
		return TRUE;
	}
	
	public static function hasPermission($permKey){
		self::$ci = &get_instance();
		self::$ci->load->library('Member_acl');
		return self::$ci->member_acl->hasPermission($permKey);
	}
	
	public function __clone(){
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

}
// end file