<?php  
/**
 * Member Acl 
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2013 chaegumi
 * @email		chaegumi@qq.com
 * @filesource
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Member_acl
	{
		var $perms = array();		//Array : Stores the permissions for the user
		var $userID = 0;			//Integer : Stores the ID of the current user
		var $userRoles = array();	//Array : Stores the roles of the current user
		private $allPerms = array();
		
		private $CI;
		
		function __construct($userID = '')
		{
			$this->CI = &get_instance();
			$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
			
			if ($userID != '')
			{
				$this->userID = floatval($userID);
			} else {
				$this->userID = floatval($this->CI->session->userdata('member_userid'));
			}
			$this->userRoles = $this->getUserRoles('ids');
			$this->allPerms = $this->getAllPerms('full');
			$this->buildACL();
		}
		
/* 		function ACL($userID = '')
		{
			$this->__constructor($userID);
			//crutch for PHP4 setups
		}*/
		
		function buildACL()
		{
			if(!$perms = $this->CI->cache->get('uperms_' . $this->userID)){
				//first, get the rules for the user's role
				if (count($this->userRoles) > 0)
				{
					$this->perms = array_merge($this->perms, $this->getRolePerms($this->userRoles));
				}
				//then, get the individual user permissions
				$this->perms = array_merge($this->perms, $this->getUserPerms($this->userID));
				
				$this->CI->cache->save('uperms_' . $this->userID, $this->perms, 3600);
			}else{
				$this->perms = $perms;
			}
		} 
		
		function getPermKeyFromID($permID)
		{
			$allperms = $this->allPerms;
			if(array_key_exists($permID, $allperms)){
				return $allperms[$permID]['Key'];
			}else{
				return FALSE;
			}
		}
		
		function getPermNameFromID($permID)
		{
			$allperms = $this->allPerms;
			if(array_key_exists($permID, $allperms)){
				return $allperms[$permID]['Name'];
			}else{
				return FALSE;
			}
		}
		
		function getPermFromID($permID)
		{
			$allperms = $this->allPerms;
			if(array_key_exists($permID, $allperms)){
				$row = $allperms[$permID];
			}else{
				$row = FALSE;
			}
			return $row;
		}
		
		function getRoleNameFromID($roleID)
		{
			$allroles = $this->getAllRoles('full');
			if(array_key_exists($roleID, $allroles)){
				return $allroles[$roleID]['roleName'];
			}else{
				return FALSE;
			}
		}
		
		function getUserRoles()
		{
			$userid = floatval($this->userID);
			if($userid){
				if(!$resp = $this->CI->cache->get('user_roles_' . $userid)){
					$strSQL = "SELECT * FROM `user_roles` WHERE `userID` = " . floatval($this->userID) . " ORDER BY `addDate` ASC";
					$data = $this->CI->db->query($strSQL)->result_array();
					$resp = array();
					foreach($data as $row){
						$resp[] = $row['roleID'];
					}
					$this->CI->cache->save('user_roles_' . $userid, $resp, 3600);
				}
				return $resp;
			}else{
				return array();
			}
		}
		
		function getAllRoles($format='ids')
		{
			if(!$resp = $this->CI->cache->get('allroles')){
				$format = strtolower($format);
				$strSQL = "SELECT * FROM `roles` ORDER BY `roleName` ASC";
				$data = $this->CI->db->query($strSQL)->result_array();
				$resp = array();
				foreach($data as $row)
				{
					if ($format == 'full')
					{
						$resp[$row['id']] = array("id" => $row['id'],"Name" => $row['roleName']);
					} else {
						$resp[$row['id']] = $row['id'];
					}
				}
				$this->CI->cache->save('allroles', $resp, 3600);
			}
			return $resp;
		}
		
		function getAllPerms($format='ids')
		{
			if(!$resp = $this->CI->cache->get('allperms')){
				$format = strtolower($format);
				$strSQL = "SELECT * FROM `permissions` ORDER BY `permName` ASC";
				$data = $this->CI->db->query($strSQL)->result_array();
				$resp = array();
				foreach($data as $row)
				{
					if ($format == 'full')
					{
						$resp[$row['id']] = array('id' => $row['id'], 'Name' => $row['permName'], 'Key' => $row['permKey']);
					} else {
						$resp[] = $row['id'];
					}
				}
				$this->CI->cache->save('allperms', $resp, 3600);
			}
			return $resp;
		}

		function getRolePerms($role)
		{	
			if (is_array($role))
			{
				$roleSQL = "SELECT * FROM `role_perms` WHERE `roleID` IN (" . implode(",",$role) . ") ORDER BY `ID` ASC";
			} else {
				$roleSQL = "SELECT * FROM `role_perms` WHERE `roleID` = " . floatval($role) . " ORDER BY `ID` ASC";
			}
			$data = $this->CI->db->query($roleSQL)->result_array();
			$perms = array();
			foreach($data as $row)
			{
				$perminfo = $this->getPermFromID($row['permID']);
				$pK = strtolower($perminfo['Key']);
				if ($pK == '') { continue; }
				if ($row['value'] === '1') {
					$hP = true;
				} else {
					$hP = false;
				}
				$perms[$pK] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'Name' => $perminfo['Name'],'id' => $row['permID']);
			}
			return $perms;
		}
		
		function getUserPerms($userID)
		{
			if($userID){
				if(!$perms = $this->CI->cache->get('userperms_' . $userID)){
					$strSQL = "SELECT * FROM `user_perms` WHERE `userID` = " . floatval($userID) . " ORDER BY `addDate` ASC";
					$data = $this->CI->db->query($strSQL)->result_array();
					$perms = array();
					foreach($data as $row)
					{
						$perminfo = $this->getPermFromID($row['permID']);
						$pK = strtolower($perminfo['Key']);
						if ($pK == '') { continue; }
						if ($row['value'] == '1') {
							$hP = true;
						} else {
							$hP = false;
						}
						$perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'Name' => $perminfo['Name'],'id' => $row['permID']);
					}
					$this->CI->cache->save('userperms_' . $userID, $perms, 3600);
				}
				return $perms;
			}else{
				return array();
			}
		}
		
		function userHasRole($roleID)
		{
			foreach($this->userRoles as $k => $v)
			{
				if (floatval($v) === floatval($roleID))
				{
					return true;
				}
			}
			return false;
		}
		
		function hasPermission($permKey)
		{
			$permKey = strtolower($permKey);
			
			if (array_key_exists($permKey,$this->perms))
			{
				if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
				{
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function getPermArr($type = ''){
			if($type == ''){
				return $this->perms;
			}else{
				$perms = $this->perms;
				$newperm = array();
				foreach($perms as $k=>$perm){
					$newperm[$k] = ($perm['value'] === '1' || $perm['value'] === true ? true : false);
				}
				return $newperm;
			}
			
		}
		
		function getUsername($userID)
		{
			$strSQL = "SELECT `username` FROM `users` WHERE `ID` = " . floatval($userID) . " LIMIT 1";
			$row = $this->CI->db->query($strSQL)->row_array();
			return $row[0];
		}
	}

// end file