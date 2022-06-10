<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checker extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
		parent::__construct();
		// parent::__construct();
		$this->load->model('Checker_m');
	}

    public function index($rs_id=null)
	{
		// $this->session->unset_userdata('cf_name');
		// $this->session->unset_userdata('rs_id');

		// $this->session->sess_destroy();

		if($this->session->userdata('cf_name') && $this->session->userdata('rs_id')){
			$this->Checker_m->db->select('company_name');
			$this->Checker_m->db->from('qr_reservation');
			$this->Checker_m->db->where('rs_id', $rs_id);
			$query = $this->Checker_m->db->get()->row();

			$query->rs_id = $rs_id;

			$this->load->view('checker/check', $query);

		}else{

			$this->Checker_m->db->select('company_name, test_date, login_s_time, login_e_time');
			$this->Checker_m->db->from('qr_reservation');
			$this->Checker_m->db->where('rs_id', $rs_id);
			$query = $this->Checker_m->db->get()->row();

			// if(!empty($query)){
			if(count($query) > 0){
				$s_time = str_replace('-', '', $query->test_date).$query->login_s_time;
				$e_time = str_replace('-', '', $query->test_date).$query->login_e_time;
				
				$login_acc = false;

				if(date('YmdHi') >= $s_time && date('YmdHi') < $e_time){
					$login_acc = true;
				}
				
				$query->rs_id = $rs_id;
				$query->login_acc = $login_acc;

				$this->load->view('checker/index', $query);
			}else{
				$data['message'] = '잘못된 접근입니다.';
				
				$this->load->view('errors/custom_404', $data);
			}

		}

		
	}

	public function check($rs_id=null)
	{
		
		if($this->sessionCheck()){
			$this->Checker_m->db->select('company_name');
			$this->Checker_m->db->from('qr_reservation');
			$this->Checker_m->db->where('rs_id', $rs_id);
			$query = $this->Checker_m->db->get()->row();

			$query->rs_id = $rs_id;

			$this->load->view('checker/check', $query);
		}else{
			$this->load->view('checker/session_error');
		}
	}

	public function login()
	{
		$data = $this->inputSetting();

		$result = $this->Checker_m->login($data);

		$this->responseForFront($result);
	}

	public function logout()
	{
		$result = $this->Checker_m->logout();

		$this->responseForFront($result);
	}

	private function sessionCheck()
	{
		$return = true;

		// if(empty($this->session->userdata('c_id'))){
		if(!$this->session->userdata('cf_name')){
			$return = false;
		}

		return $return;
	}

	
	public function qrCheck()
	{
		if($this->sessionCheck()){
			$data = $this->inputSetting();
			$result = $this->Checker_m->qrCheck($data);
		}else{
			$result['code'] = 601;
		}
		$this->responseForFront($result);
	}

	public function positiveConfirm()
	{
		$data = $this->inputSetting();
		
		$result = $this->Checker_m->qrPositiveConfirm($data);

		$this->responseForFront($result);
	}

	public function negativeConfirm()
	{
		$data = $this->inputSetting();
		
		$result = $this->Checker_m->qrNegativeConfirm($data);

		$this->responseForFront($result);
	}

	private function inputSetting()
	{
		$this->input->raw_input_stream;
		return json_decode($this->input->raw_input_stream, true);
	}

	private function responseForFront($data)
	{
		$this->output->set_content_type('text/json');
		$this->output->set_output(json_encode($data));
	}

}
