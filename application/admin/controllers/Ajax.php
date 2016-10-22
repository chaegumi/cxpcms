<?php 
/**
 * Ajax Request
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016 chaegumi
 * @email		chaegumi@qq.com
 * @filesource
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller{

	function __construct(){
		parent::__construct();
	}
	
	function index(){
		// sleep(1);
		// $this->output->set_output('true');
	}
	
	// 设置是否类型的值
	function setboolattribute(){
		$tblname=$this->input->post('tbname');
		$sfield=$this->input->post('tbfield');
		$sval=$this->input->post('tbfieldvalue');
		//if($sval==1){
		//	$sval=0;
		//}
		$id=intval($this->input->post('id'));
		$this->db->where('company_id', $this->user->company_id);
		$this->db->where('id',$id);
		//echo $sfield;
		$this->db->set($sfield,$sval);
		if ($this->db->table_exists($tblname)){
			$this->db->update($tblname);
		}else{
			$this->db->update($tblname);
		}
		//echo $this->db->last_query();
		$this->output->set_output('success');
	}
	
	function setfieldvalue(){
		$tblname = $this->input->post('tbname');
		$sfield = $this->input->post('tbfield');
		$sval = $this->input->post('tbfieldvalue');
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->where('site_id', $this->site_id);
		$this->db->set($sfield, $sval);
		$this->db->update($tblname);
		$this->output->set_output('success');
	}
	
	// 设置默认（唯一值）
	function setunique(){
		$tblname=$this->input->post('tbname');
		$sfield=$this->input->post('tbfield');
		$sval=$this->input->post('tbfieldvalue');
		$rowid = $this->input->post('rowid');
		$this->db->trans_begin();
		$data = array(
			$sfield => 0
		);
		$this->db->update($tblname, $data);
		$data = array(
			$sfield => 1
		);
		$this->db->where('id', $rowid);
		$this->db->where('company_id', $this->user->company_id);
		$this->db->update($tblname, $data);
		$this->db->trans_complete();
		$this->output->set_output('success');
	}
	
	function set_sortid(){
		$tbname = $this->input->post('tbname');
		$sfield = $this->input->post('tbfield');
		$sval = $this->input->post('tbfieldvalue');
		$rowid = $this->input->post('rowid');
		$this->db->where('id', $rowid);
		$this->db->where('company_id', $this->user->company_id);
		$data = array(
			$sfield => $sval
		);
		$this->db->update($tbname, $data);
		$this->output->set_output('success');
	}
	
	// 生成凭证
	function genkey(){
		//echo md5
	}
	
	// 检查用户名是否已经存在
	function check_username(){
		$username = $this->input->get('username');
		$this->db->where('username', $username);
		$userinfo = $this->db->get('users')->row();
		//var_dump($this->db->last_query());
		if($userinfo){
			$this->output->set_output('false');
		}else{
			$this->output->set_output('true');
		}
	}
	
	
	
	// 检查值是否存在
	function check_value(){
		$field = $this->input->get('field');
		$table = $this->input->get('table');
		if($table && $field){
			$field_value = $this->input->get($field);
			$this->db->where($field, $field_value);
			$info = $this->db->get($table)->row();
			if($info){
				$this->output->set_output('false');
			}else{
				$this->output->set_output('true');
			}
		}else{
			$this->output->set_output('true');
		}
	}	

}

// end file