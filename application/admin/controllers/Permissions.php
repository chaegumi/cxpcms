<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Permissions extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-permissions');
	}
	
	function index(){
		$this->load->view('permissions', $this->template_data);
	}
	
	function data(){
		$permissions = permissions_list();
	  $perm_parr = array();
	  foreach($permissions as $row){
		$perm_parr[$row->parent_id][] = $row;
	  }
	  $this->output->set_output('[' . $this->loop_parent($perm_parr, 0, 0, 0, '') . ']');
	}
	
  function loop_parent($perm_parr, $parent_id, $curloop, $curid, $html){
	if(isset($perm_parr[$parent_id]) && count($perm_parr[$parent_id])>0){
	  
		  foreach($perm_parr[$parent_id] as $row){
			  if(isset($perm_parr[$row->id]) && count($perm_parr[$row->id])>0){
				$html .= "{id:" . $row->id . ",name:'" . $row->permName . "', permKey:'" . $row->permKey . "', children:[";
				$html = $this->loop_parent($perm_parr, $row->id, $curloop + 1, $curid, $html) . ']},';
				
			  }else{
				  $html .= "{id:" . $row->id . ",name:'" . $row->permName . "', permKey:'" . $row->permKey . "'},";
			  }
		  }								  
	}else{
		// $html .= ']},';
	}
	return $html;
  }		
  
	function add(){
		check_permission('admin-add-permission');
		$parent_id = intval($this->input->get('parent_id'));
		$this->template_data['parent_id'] = $parent_id;
		
		$this->load->view('permissions_edit', $this->template_data);
	}
	

	
	function edit(){
		check_permission('admin-edit-permission');
		$id = intval($this->input->get('id'));
		
		$this->db->where('id', $id);
		$info = $this->db->get('permissions')->row();
		$this->template_data['info'] = $info;
		
		$this->load->view('permissions_edit', $this->template_data);
	}
	
	function save(){
		$id = intval($this->input->post('id'));
		$this->form_validation->set_rules('permName', '权限名称', 'trim|required');
		$this->form_validation->set_rules('permKey', '权限KEY', 'trim|required');
		if($this->form_validation->run() === FALSE){
			json_response(array('success' => FALSE, 'msg' => validation_errors()));
		}else{
			if($id === 0){
				check_permission('admin-add-permission');
				$this->db->trans_begin();
				$data = array(
					'parent_id' => intval($this->input->post('parent_id')),
					'permName' => trim($this->input->post('permName')),
					'permKey' => trim($this->input->post('permKey'))
				);
				$this->db->insert('permissions', $data);
				
				$new_perm_id = $this->db->insert_id();
				// 添加新权限总后台帐号自动授权
				$data1 = array(
					'roleID' => 5,
					'permID' => $new_perm_id,
					'value' => 1
				);
				$this->db->insert('role_perms', $data1);
				// 更新权限缓存
				cxp_update_cache();
				$this->db->trans_complete();
				
				json_response(array('success' => TRUE, 'msg' => 'Add Permission Success'));
			}else{
				check_permission('admin-edit-permission');
				$this->db->trans_begin();
				$data = array(
					'parent_id' => intval($this->input->post('parent_id')),
					'permName' => trim($this->input->post('permName')),
					'permKey' => trim($this->input->post('permKey'))
				);
				$this->db->where('id', $id);
				$this->db->update('permissions', $data);
				
				cxp_update_cache();
				$this->db->trans_complete();
				
				json_response(array('success' => TRUE, 'msg' => 'Edit Permission SUccess'));
			}
			
		}		
	}
	
	function delete(){
		check_permission('admin-del-permission');
		$id = intval($this->input->get('id'));
		$this->db->trans_begin();
		$this->db->where('permID', $id);
		$this->db->delete('user_perms');
		
		$this->db->where('permID', $id);
		$this->db->delete('role_perms');
		
		$this->db->where('id', $id);
		$this->db->delete('permissions');
		$this->db->trans_complete();
		
		cxp_update_cache();
		json_response(array('success' => TRUE, 'msg' => 'Delete Permission Success'));
	}

}