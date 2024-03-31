	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashbord extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if (!is_user_login()) {
			redirect('');
		}

		$this->load->model('user_model');
	}

	public function index()
	{
		$user_data = $this->user_model->get_all_user();
		$data['user_data'] = $user_data;

		$this->load->view('users/user_listing', $data);
	}

    public function fetch_messages() {

        $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $response = ['status' => 'error', 'message' => validation_errors() ];
        }else{

	        $user_id = $this->input->post('user_id');
			$messages = $this->user_model->get_all_communication($user_id, $this->session->userdata('userid'));

	        $message_html = '';
	        foreach ($messages as $message) {
	        	if ($this->session->userdata('userid') == $message['user_id']) {
	        		$message_html .= '<div class="message_01">
	            					' . $message['text'] . ' <sub>' . date('H:i:s', strtotime($message['date_time'])) . '</sub>
	            				</div>';
	        	}else{
	        		$message_html .= '<div class="message_02" >
	            					'.$message['text'] . ' <sub>' . date('H:i:s', strtotime($message['date_time'])) . '</sub>
	            				</div>';
	        	}
	            
	        }

            $response = ['status' => 'success', 'message' => $message_html ];
	    }
	    echo json_encode($response);
    }

    public function send_message() {

        $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
        $this->form_validation->set_rules('message', 'message', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $response = ['status' => 'error', 'message' => validation_errors() ];
        }else{
        	$data_insert = array();
	        $data_insert['user_id'] = $this->input->post('user_id');
	        $data_insert['text'] = $this->input->post('message');
	        $data_insert['date_time'] = date('Y-m-d H:i:s');
	        $data_insert['status'] = 'Active';
	        $data_insert['created_by'] = $this->session->userdata('userid');
	        $data_insert['created_on'] = date('Y-m-d H:i:s');
	        $data_insert['updated_by'] = $this->session->userdata('userid');
	        $data_insert['updated_on'] = date('Y-m-d H:i:s');


			$id = $this->user_model->insert_cumunication($data_insert);

            $response = ['status' => 'success', 'message' => 'inserted' ];
	    }
	    echo json_encode($response);
    }


}