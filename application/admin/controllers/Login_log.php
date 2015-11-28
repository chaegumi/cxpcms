<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Login_log extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-login-log1');
	}
	
	function index(){
		// $this->load->view('login_log', $this->template_data);
	}
	
	

}