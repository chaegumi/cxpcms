<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	protected $template_data = array();
	protected $user, $siteinfo, $site_id;
	protected $userSites = array();
	protected $node_contents_table_num = 10;
	function __construct(){
		parent::__construct();
		$this->template_data['cdn_server'] = base_url();
		$this->load->driver('cache', array('adapter' => 'file'));
		$this->load->library(array('Member_current_user'));
		$this->load->helper(array('server'));
		$this->user = Member_Current_User::user();
		if($this->user){
			// 登录用户信息
			// $this->template_data['user'] = $this->user;
			$this->load->vars(array('user' => $this->user));
			
		}else{
			// 跳转到登录页面
			redirect(base_url() . 'admin.php?c=login');
		}		
	}
	
	function index(){
		$this->load->view('login', $this->template_data);
	}

}