<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Profile extends MY_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		
		$this->load->view('profile', $this->template_data);
	}	

}