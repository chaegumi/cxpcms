<?php 
/**
 * Admin Welcome
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('server');
	}

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		
		// var_dump(FCPATH . 'server');

		$this->load->view('welcome_message', $this->template_data);
	}
	
	function dashboard(){	
		
		$this->load->view('dashboard', $this->template_data);
	}
}
// end this file