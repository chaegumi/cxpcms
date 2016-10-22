<?php 
/**
 * Login Log
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_log extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-login-log1');
	}
	
	function index(){
		// $this->load->view('login_log', $this->template_data);
	}
	
	

}
// end this file