<?php 
/**
 * Logout
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
	
	function index(){
		
		$this->load->library(array('Member_current_user'));
		$this->load->helper(array('server'));
		$user = Member_Current_user::user();
		Member_Current_user::logout();
		// other 
		
		redirect('c=login');
	}	

}
// end this file