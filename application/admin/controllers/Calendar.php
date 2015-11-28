<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2015 jeawin.com
 * @email		chaegumi@jeawin.com
 * @filesource
 */
class Calendar extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		check_permission('admin-events');
	}
	
	function index(){
		$this->load->view('calendar', $this->template_data);
	}
	
	function data(){
		
		$start = $this->input->get('start');
		$end = $this->input->get('end');
		if(!$start || !$end){
			json_response(array('success' => FALSE, 'msg' => '非法参数'));
		}
		
		$this->db->where('start>=', $this->db->escape($start), FALSE);
		$this->db->where('end<=', $this->db->escape($end), FALSE);
		$this->db->where('user_id', $this->user->id);
		$results = $this->db->get('events')->result();
		$events = array();
		foreach($results as $row){
			if($row->allDay){
				$row->allDay = TRUE;
			}else{
				$row->allDay = FALSE;
			}
			$events[] = $row;
		}
		
		json_response($events);
	}
	
	function add(){
		check_permission('admin-add-event');
		$start = $this->input->get('start');
		$this->template_data['start'] = $start;
		
		$end = $this->input->get('end');
		$this->template_data['end'] = $end;
		
		$this->template_data['main'] = 'events_edit';
		$this->load->view('dialog_layout', $this->template_data);
	}
	
	function edit(){
		check_permission('admin-edit-event');
		$id = intval($this->input->get('id'));
		
		$this->db->where('id', $id);
		$info = $this->db->get('events')->row();
		
		$this->template_data['info'] = $info;
		
		$this->template_data['main'] = 'events_edit';
		$this->load->view('dialog_layout', $this->template_data);
	}
	
	function save(){
		$this->form_validation->set_rules('title', '事件标题', 'trim|required');
		if($this->form_validation->run() === FALSE){
			json_response(array('success' => FALSE, 'msg' => validation_errors()));
		}else{
			$id = intval($this->input->post('id'));
			if($id === 0){
				check_permission('admin-add-event');
				$data = array(
					'user_id' => $this->user->id,
					'title' => trim($this->input->post('title')),
					'start' => $this->input->post('start'),
					'end' => $this->input->post('end'),
					// 'url' => trim($this->input->post('url')),
					'backgroundColor' => trim($this->input->post('backgroundColor')),
					'borderColor' => trim($this->input->post('backgroundColor')),
					'allDay' => $this->input->post('allDay'),
					'addtime' => $_SERVER['REQUEST_TIME']
				);
				$this->db->insert('events', $data);
				operation_log(array('user_id' => $this->user->id, 'content' => '添加事件：' . $data['title']));
				json_response(array('success' => TRUE, 'msg' => '添加事件成功'));
			}else{
				check_permission('admin-edit-event');
				$data = array(
					'title' => trim($this->input->post('title')),
					'start' => $this->input->post('start'),
					'end' => $this->input->post('end'),
					// 'url' => trim($this->input->post('url')),
					'backgroundColor' => trim($this->input->post('backgroundColor')),
					'borderColor' => trim($this->input->post('backgroundColor')),
					'allDay' => $this->input->post('allDay')
				);
				$this->db->where('id', $id);
				$this->db->where('user_id', $this->user->id);
				$this->db->update('events', $data);
				operation_log(array('user_id' => $this->user->id, 'content' => '修改事件：' . $data['title']));
				json_response(array('success' => TRUE, 'msg' => '修改事件成功'));
				
			}
		}
	}
	
	function delete(){
		check_permission('admin-del-event');
		$id = intval($this->input->get('id'));
		$this->db->where('id', $id);
		$this->db->where('user_id', $this->user->id);
		$this->db->delete('events');
		operation_log(array('user_id' => $this->user->id, 'content' => '删除事件：' . $id));
		json_response(array('success' => TRUE, 'msg' => '删除事件成功'));
	}
	
}