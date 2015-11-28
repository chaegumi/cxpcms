<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Clear_cache extends MY_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		//
		cxp_update_cache($this->site_id);
		// $this->output->set_output('success');
		// $this->load->view('alert', $this->template_data);
		json_response(array('success' => FALSE, 'msg' => 'Success'));
	}
	
}