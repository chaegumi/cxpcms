<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Users extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-users');
	}

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->view('users', $this->template_data);
	}
	
	function data(){
		$response = new stdClass;
		$response->draw = $this->input->post('draw');
		
		$search = $this->input->post('search');
		$keyword = '';
		if($search) $keyword = $search['value'];
		// $this->session->set_userdata('search', $search);
		// $perpage = 10;
		$this->db->select('count(A.id) as ccount', FALSE);
		$this->db->from('users A');
		if($keyword){
			$this->db->where('(A.username=' . $this->db->escape($keyword) . ' or A.email=' . $this->db->escape($keyword) . ')');
		}
		$q = $this->db->get()->row();
		$response->recordsTotal = $q->ccount;
		
		// $offset = $response->draw * $perpage;
		
		$this->db->select('A.*');
		$this->db->from('users A');
		if($keyword){
			$this->db->where('(A.username=' . $this->db->escape($keyword) . ' or A.email=' . $this->db->escape($keyword) . ')');
		}		
		$this->db->order_by('A.id', 'desc');
		$this->db->limit($this->input->post('length'), $this->input->post('start'));
		$results = $this->db->get()->result();
		
		$response->recordsFiltered = $response->recordsTotal;
		
		$response->data = array();
		foreach($results as $row){
			$data = array();
			$data['id'] = $row->id;
			$data['username'] = $row->username;
			$data['status'] = $row->status;
			$data['email'] = $row->email;
			$data['reg_time'] = $row->reg_time;
			$data['login_times'] = $row->login_times;
			$data['last_login_time'] = $row->last_login_time;
			$data['issys'] = $row->issys;
			$response->data[] = $data;
		}
		
		$this->output->set_output(json_encode($response));			
	}
	
	function add(){
		check_permission('admin-add-user');
		$userRoles = array();
		$this->template_data['userRoles'] = $userRoles;
		
		$this->load->view('users_edit', $this->template_data);
	}
	
	function edit(){
		check_permission('admin-edit-user');
		$id = intval($this->input->get('id'));
		
		$this->db->where('id', $id);
		$info = $this->db->get('users')->row();
		$this->template_data['info'] = $info;
		$this->load->library('Member_acl');
		$member_acl1 = new Member_acl($id);
		$userRoles = $member_acl1->getUserRoles();
		$this->template_data['userRoles'] = $userRoles;
		$this->load->view('users_edit', $this->template_data);
	}
	

	
	function save(){
		$id = intval($this->input->post('id'));
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		if($this->form_validation->run() === FALSE){
			json_response(array('success' => FALSE, 'msg' => validation_errors()));
		}else{
			if($id === 0){
				check_permission('admin-add-user');
				$this->db->trans_begin();
				$data = array(
					'username' => trim($this->input->post('username')),
					'email' => trim($this->input->post('email')),
					'status' => intval($this->input->post('status')),
					'password' => password_hash(trim($this->input->post('password')), PASSWORD_BCRYPT)
				);
				$this->db->insert('users', $data);
				$new_user_id = $this->db->insert_id();
				
				// set user roles
				$rolesarr = $this->input->post('roles');
				if($rolesarr){
					$sql = 'insert into user_roles(userID, roleID) values';
					$tstr = '';
					foreach($rolesarr as $v){
						$tstr .= '(' . $new_user_id . ', ' . $v . '),';
					}
					if($tstr != ''){
						$sql .= rtrim($tstr, ',');
						$this->db->query($sql);
					}
				}
				$this->db->trans_complete();
				json_response(array('success' => TRUE, 'msg' => 'Add User Success'));
			}else{
				check_permission('admin-edit-user');
				$this->db->trans_begin();
				$data = array(
					'username' => trim($this->input->post('username')),
					'email' => trim($this->input->post('email')),
					'status' => intval($this->input->post('status'))
				);
				$this->db->where('id', $id);
				$this->db->update('users', $data);
				// User Roles
				$rolesarr = $this->input->post('roles');
				if($rolesarr){
					// Delete Old User Roles
					$this->db->where('userID', $id);
					$this->db->delete('user_roles');
					$sql = 'insert into user_roles(userID, roleID) values';
					$tstr = '';
					foreach($rolesarr as $v){
						$tstr .= '(' . $id . ', ' . $v . '),';
					}
					if($tstr != ''){
						$sql .= rtrim($tstr, ',');
						$this->db->query($sql);
					}
				}
				$this->db->trans_complete();
				json_response(array('success' => TRUE, 'msg' => 'Edit User Success'));
			}
			
		}		
	}
	
	function delete(){
		check_permission('admin-del-user');
		$id = intval($this->input->get('id'));
		
		$this->db->trans_begin();
		
		// delete user perms
		$this->db->where('userID', $id);
		$this->db->delete('user_perms');
		
		// delete user roles
		$this->db->where('userID', $id);
		$this->db->delete('user_roles');

		
		// delete user detail
		// $this->db->where('user_id', $id);
		// $this->db->delete('user_profile');
		
		// delete user
		$this->db->where('id', $id);
		$this->db->delete('users');
		
		$this->db->trans_complete();
		json_response(array('success' => TRUE, 'msg' => 'Delete User Success'));
	}
	
	function edit_password(){
		if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
			$this->form_validation->set_rules('password', 'New Password', 'trim|required');
			if($this->form_validation->run() === FALSE){
				json_response(array('success' => FALSE, 'msg' => validation_errors()));
			}else{
				$user_id = intval($this->input->post('user_id'));
				$this->db->where('id', $user_id);
				$data = array(
					'password' => password_hash(trim($this->input->post('password')), PASSWORD_BCRYPT)
				);
				$this->db->update('users', $data);
				json_response(array('success' => TRUE, 'msg' => 'Change Password Success'));
			}	
		}else{
			$user_id = intval($this->input->get('user_id'));
			$this->template_data['user_id'] = $user_id;
			$this->load->view('edit_password', $this->template_data);			
		}
	}
	
	function set_perms(){
		if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
			foreach ($_POST as $k => $v)
			{
				if (substr($k,0,5) == "perm_")
				{
					$permID = str_replace("perm_","",$k);
					if ($v == 'x')
					{
						$strSQL = "DELETE FROM `user_perms` WHERE `userID` = ? AND `permID` = ?";
						$this->db->query($strSQL,array($_POST['user_id'],floatval($permID)));
					} else {
						$strSQL = "REPLACE INTO `user_perms` SET `userID` = ?, `permID` = ?, `value` = ?";
						$this->db->query($strSQL,array($_POST['user_id'],floatval($permID),$v));
						
					}
				}
			}
			cxp_update_cache();
			json_response(array('success' => TRUE, 'msg' => 'change user permission success'));
		}else{
			$user_id = intval($this->input->get('user_id'));
			$this->db->where('id', $user_id);
			$info = $this->db->get('users')->row();
			$this->template_data['info'] = $info;
			$this->template_data['user_id'] = $user_id;
			
			$this->load->view('set_perms', $this->template_data);			
		}

	}
	
	function perm_data(){
		$permissions = permissions_list();
	  $perm_parr = array();
	  foreach($permissions as $row){
		$perm_parr[$row->parent_id][] = $row;
	  }
	  
	  $user_id = intval($this->input->post('user_id'));
	  $this->load->library('Member_acl');
			$my_acl=new Member_acl($user_id);
			$this->template_data['my_acl'] = $my_acl;
			$rPerms = $my_acl->getPermArr();
			$this->template_data['rPerms'] = $rPerms;
	  $this->output->set_output('[' . $this->loop_parent($perm_parr, 0, 0, 0, '', $rPerms) . ']');
	}
	
	function loop_parent($perm_parr, $parent_id, $curloop, $curid, $html, $rPerms){
		if(isset($perm_parr[$parent_id]) && count($perm_parr[$parent_id])>0){
		  
			  foreach($perm_parr[$parent_id] as $row){
				$permKey = $row->permKey;
				$selhtml = '';
				$selhtml .= "<select name=\"perm_" . $row->id . "\">";
				$selhtml .= "<option value=\"1\"";
				if (isset($rPerms[$permKey]) && ($rPerms[$permKey]['value'] === '1' || $rPerms[$permKey]['value'] === true) && $rPerms[$permKey]['inheritted'] != true) { $selhtml .= " selected=\"selected\""; }
				$selhtml .= ">Allow</option>";
				$selhtml .= "<option value=\"0\"";
				if(isset($rPerms[$permKey])){if ($rPerms[$permKey]['value'] === false && $rPerms[$permKey]['inheritted'] != true) { $selhtml .= " selected=\"selected\""; }}
				$selhtml .= ">Deny</option>";
				$selhtml .= "<option value=\"x\"";
				$iVal = '';
				if(isset($rPerms[$permKey])){
					if ($rPerms[$permKey]['inheritted'] == true || !array_key_exists($permKey,$rPerms))
					{
						$selhtml .= " selected=\"selected\"";
						if ($rPerms[$permKey]['value'] === true )
						{
							$iVal = '(Allow)';
						} else {
							$iVal = '(Deny)';
						}
					}
				}else{
					$selhtml .= " selected=\"selected\"";
					$iVal = '(Deny)';
				}
				$selhtml .= ">Inherit $iVal</option>";
                $selhtml .= "</select>";
				  
				  if(isset($perm_parr[$row->id]) && count($perm_parr[$row->id])>0){
					$html .= "{id:" . $row->id . ",name:'" . $row->permName . "', select:'" . $selhtml . "', children:[";
					$html = $this->loop_parent($perm_parr, $row->id, $curloop + 1, $curid, $html, $rPerms) . ']},';
					
				  }else{
					  $html .= "{id:" . $row->id . ",name:'" . $row->permName . "', select:'" . $selhtml . "'},";
				  }
			  }								  
		}else{
			// $html .= ']},';
		}
		return $html;
	}		
}
