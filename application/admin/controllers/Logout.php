<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
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