<?php 
/**
 * Profile
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		
		$this->load->view('profile', $this->template_data);
	}	

}
// end this file