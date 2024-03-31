	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_model {

	
	public function check_login($user_name, $password)
	{
		if (!empty($user_name) && !empty($password)) {
			$this->db->select('U.*');
			$this->db->from('users AS U');
			$this->db->where('U.user_name', $user_name);
			$this->db->where('U.password', md5($password));
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$userdata = $query->result_array();
				// echo "<pre>";print_r($userdata);die();
				foreach ($userdata as $key => $value) {
					$arr_session = array(
						'userid' => $value['user_id'],
						'user_name' => $value['user_name'],
						'userrole' => $value['role'],
					);

					$this->session->set_userdata($arr_session);
				}
				return TRUE;

			}else{
				return FALSE;
			}
		}else{
			return FALSE;

		}
		// code...
	}

	public function get_all_user()
	{
		$this->db->select('U.*');
		$this->db->from('users AS U');

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else{
			return array();
		}
	}

	public function get_all_communication($user1_id, $user2_id) {
        $this->db->select('*');
        $this->db->from('communication');
        $this->db->where("(created_by = $user1_id AND user_id = $user2_id) OR (created_by = $user2_id AND user_id = $user1_id)");
        $this->db->order_by('date_time', 'ASC');
        $query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else{
			return array();
		}
    }

    public function insert_cumunication($data_insert) {
        $this->db->insert('communication',$data_insert);
        return $this->db->insert_id();
    }

	

}
