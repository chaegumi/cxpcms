<?php 
/**
 * Roles Manage
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2016~2099 cxpcms.com
 * @email		chaegumi@qq.com
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-roles');
	}
	
	function index(){
		$this->load->view('roles', $this->template_data);
	}
	
	function data(){
		$response = new stdClass;
		$response->draw = $this->input->post('draw');
		
		// $search = $this->input->post('search');
		// $this->session->set_userdata('search', $search);
		// $perpage = 10;
		$this->db->select('count(A.id) as ccount', FALSE);
		$this->db->from('roles A');
		$q = $this->db->get()->row();
		$response->recordsTotal = $q->ccount;
		
		// $offset = $response->draw * $perpage;
		
		$this->db->select('A.*');
		$this->db->from('roles A');
		// if($search['value']){
		// }		
		$this->db->order_by('A.id', 'desc');
		$this->db->limit($this->input->post('length'), $this->input->post('start'));
		$results = $this->db->get()->result();
		
		$response->recordsFiltered = $response->recordsTotal;
		
		$response->data = array();
		foreach($results as $row){
			$data = array();
			$data['id'] = $row->id;
			$data['roleName'] = $row->roleName;
			$data['issys'] = $row->issys;
			
			$response->data[] = $data;
		}
		
		$this->output->set_output(json_encode($response));		
	}
	
	function add(){
		check_permission('admin-add-role');
		$this->load->view('roles_edit', $this->template_data);
	}
	
	function perm_data(){
		$permissions = permissions_list();
	  $perm_parr = array();
	  foreach($permissions as $row){
		$perm_parr[$row->parent_id][] = $row;
	  }
	  
	  $roleid = intval($this->input->post('roleid'));
	  $this->load->library('Member_acl');
		$my_acl=new Member_acl();
		// $my_acl->getAllPerms();
		$rPerms = $my_acl->getRolePerms($roleid);
	  // var_dump($rPerms);
	  $this->output->set_output('[' . $this->loop_parent($perm_parr, 0, 0, 0, '', $rPerms) . ']');
	}
	
	function loop_parent($perm_parr, $parent_id, $curloop, $curid, $html, $rPerms){
		if(isset($perm_parr[$parent_id]) && count($perm_parr[$parent_id])>0){
		  
			  foreach($perm_parr[$parent_id] as $row){
				  
				if(isset($rPerms[$row->permKey]['value'])){if ($rPerms[$row->permKey]['value'] === true) { $chk = '1'; }}
				if(isset($rPerms[$row->permKey]['value'])){if ($rPerms[$row->permKey]['value'] != true) { $chk = '0'; }}
				if (!array_key_exists($row->permKey,$rPerms)) { $chk = 'x'; }
				  
				  if(isset($perm_parr[$row->id]) && count($perm_parr[$row->id])>0){
					$html .= "{id:" . $row->id . ",name:'" . $row->permName . "', chk:'" . $chk . "', children:[";
					$html = $this->loop_parent($perm_parr, $row->id, $curloop + 1, $curid, $html, $rPerms) . ']},';
					
				  }else{
					  $html .= "{id:" . $row->id . ",name:'" . $row->permName . "', chk:'" . $chk . "'},";
				  }
			  }								  
		}else{
			// $html .= ']},';
		}
		return $html;
	}		
	
	function edit(){
		check_permission('admin-edit-role');
		$id = intval($this->input->get('id'));
		$this->db->where('id', $id);
		$info = $this->db->get('roles')->row();
		$this->template_data['info'] = $info;
		// $this->load->library('Member_acl');
		// $my_acl=new Member_acl();
		// $my_acl->getAllPerms();
		// $rPerms = $my_acl->getRolePerms($id);
		
		// $this->template_data['rPerms'] = $rPerms;
		$this->load->view('roles_edit', $this->template_data);
	}
	
	function save(){
		$id = intval($this->input->post('id'));
		$this->form_validation->set_rules('roleName', 'Role Name', 'trim|required');
		if($this->form_validation->run() === FALSE){
			json_response(array('success' => FALSE, 'msg' => validation_errors()));
		}else{
			if($id === 0){
				check_permission('admin-add-role');
				$this->db->trans_begin();
				$data = array(
					'roleName' => trim($this->input->post('roleName')),
					'issys' => 1
				);
				$this->db->insert('roles', $data);
				$new_role_id = $this->db->insert_id();
				// 添加角色权限
				$this->save_role_perm($new_role_id);
				$this->db->trans_complete();
				json_response(array('success' => TRUE, 'msg' => 'Add Role Success'));
			}else{
				check_permission('admin-edit-role');
				$this->db->trans_begin();
				$data = array(
					'roleName' => trim($this->input->post('roleName')),
					'issys' => 1
				);
				$this->db->where('id', $id);
				$this->db->update('roles', $data);
				
				// 更新角色权限
				$this->save_role_perm($id);
				
				$this->db->trans_complete();
				cxp_update_cache();
				json_response(array('success' => TRUE, 'msg' => 'Edit Role Success'));
			}
			
		}
	}
	
	function save_role_perm($id){
		$roleID = $id;
		foreach ($_POST as $k => $v)
		{
			if (substr($k,0,5) == "perm_")
			{
				$permID = str_replace("perm_","",$k);
				if ($v == 'x')
				{
					$strSQL ="DELETE FROM `role_perms` WHERE `roleId` = ? AND `permId` = ?";
					$this->db->query($strSQL,array($roleID,$permID));
					continue;
				}
				$strSQL = "REPLACE INTO `role_perms` SET `roleId` = ?, `permId` = ?, `value` = ?";
				$this->db->query($strSQL,array($roleID,$permID,$v));
			}
		}	
	}	
	
	function delete(){
		check_permission('admin-del-role');
		$id = intval($this->input->get('id'));
		$this->db->trans_begin();
		
		$this->db->where('roleID', $id);
		$this->db->delete('role_perms');
		
		$this->db->where('roleID', $id);
		$this->db->delete('user_roles');
		
		// 
		$this->db->where('id', $id);
		$this->db->delete('roles');
		
		$this->db->trans_complete();
		cxp_update_cache();
		json_response(array('success' => TRUE, 'msg' => 'Delete Role Success'));
	}

}
// end this file