<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index(){
		if (isset($this->session->userid)) {
		redirect('Admin/admindashboard');
		} else {
			$this->load->view('login');
		}	
	}
	public function checklogin(){
		$data = array();
		$max_attempts = 5;
		$lockout_minutes = 15;

		$attempts = (int) $this->session->userdata('login_attempts');
		$block_until = (int) $this->session->userdata('login_block_until');
		if ($attempts >= $max_attempts && $block_until > time()) {
			$mins = (int) ceil(($block_until - time()) / 60);
			$data['error_message'] = "Too many failed attempts. Please try again in {$mins} minute(s).";
			$this->load->view('login', $data);
			return;
		}
		if ($attempts >= $max_attempts && $block_until > 0 && $block_until <= time()) {
			$this->session->unset_userdata(array('login_attempts' => '', 'login_block_until' => ''));
		}

		$useremail = $this->input->post('user_email',TRUE);
		$userpassword = $this->input->post('user_password',TRUE);
		//$encryppass = password_hash($userpassword,PASSWORD_DEFAULT);
		$this->load->model('LoginModel');
		$user_details = $this->LoginModel->checkuserlogin($useremail);
		if ($user_details !== NULL && password_verify($userpassword,$user_details->user_password)){
			if ($user_details->user_status == 1) {
				$this->session->unset_userdata(array('login_attempts' => '', 'login_block_until' => ''));
				$this->session->sess_regenerate(TRUE);
				$session_data['userid'] 	= $user_details->user_id;
				$session_data['username']	= $user_details->username;
				$session_data['useremail']	= $user_details->user_email;
				$session_data['userrole'] 	= $user_details->user_role;
				$session_data['userstatus']	= $user_details->user_status;
				$this->session->set_userdata($session_data);
				redirect("Admin");
			} else {
				$data['error_message'] = "U Are Not An Active User...!";
				$this->load->view('login',$data);
			}
		}else{
			$this->session->set_userdata('login_attempts', $attempts + 1);
			if ($attempts + 1 >= $max_attempts) {
				$this->session->set_userdata('login_block_until', time() + ($lockout_minutes * 60));
			}
			redirect('Login/login_error');
		}
	}
	public function login_error(){
		$data['error_message'] = "Incorrect Username Or Password...!";
			$this->load->view('login',$data);
	}
	public function adminlogout(){
		$this->session->sess_destroy();
		$data['success_message'] = "Successfully Logout...!";
		$this->load->view('login',$data);	
	}
}
