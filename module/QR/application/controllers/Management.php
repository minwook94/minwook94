<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management extends CI_Controller {

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
		$this->load->model('Management_m');
	}

	public function index($rs_id=null, $p_id=null)
	{

		if(empty($rs_id) || empty($p_id)){
			$data['message'] = '잘못된 접근입니다. URL을 다시 확인해주세요.';
			$this->load->view('errors/custom_404', $data);

			return;
		}
		$this->session->unset_userdata('rs_id');
		$this->session->unset_userdata('p_id');

		$this->session->sess_destroy();

		$data['rs_id'] = $rs_id;
		$data['p_id'] = $p_id;

		$data['management'] = $this->Management_m->load($rs_id, $p_id);

		if(count($data['management']) != 0){
			
			$this->Management_m->db->select('company_name, date_format(`test_date`,"%Y%m%d") as test_date, login_s_time, login_e_time');
			$this->Management_m->db->from('qr_reservation');
			$this->Management_m->db->where('rs_id', $rs_id);
			$query = $this->Management_m->db->get()->row();

			if(!empty($query)){

				$data['login_acc'] = true;

				$this->load->view('management/index', $data);
			}else{
				$data['message'] = '잘못된 접근입니다.';
				$this->load->view('errors/custom_404', $data);
			}
		}else{
			$data['message'] = '실제로 존재하지 않는 검사건입니다. URL을 다시 확인해주세요.';
			$this->load->view('errors/custom_404', $data);
		}
	}

	public function login(){

		$data = $this->inputSetting();

		$result = $this->Management_m->login($data);

		$this->responseForFront($result);
	}

	public function main(){

		if($this->sessionCheck()){

			$data['rs_id'] = $this->session->userdata('rs_id');
			$data['p_id'] = $this->session->userdata('p_id');
			$data['p_name'] = $this->session->userdata('p_name');

			$data['c_class'] = $this->Management_m->getMainData($data);

			$this->load->view('management/main', $data);
		}else{
			$this->load->view('management/session_error');
		}
	}

	public function getCandidateCount(){

		if($this->sessionCheck()){
			
			$data = $this->inputSetting();
			$response_data = $this->Management_m->getCandidateCount($data);
		}else {
			$response_data['code'] = 601;
		}
		$this->responseForFront($response_data);
	}

	public function detail(){
		if($this->sessionCheck()){
			
			$data = $this->inputSetting();
			$response_data = $this->Management_m->getDetailData($data);
		}else {
			$response_data['code'] = 601;
		}
		$this->responseForFront($response_data);
	}

	public function isolationDetail(){
		if($this->sessionCheck()){
			
			$data = $this->inputSetting();
			$response_data = $this->Management_m->getIsolationDetailData($data);
		}else {
			$response_data['code'] = 601;
		}
		$this->responseForFront($response_data);
	}

	public function updateIsolation(){
		if($this->sessionCheck()){
			
			$data = $this->inputSetting();
			$response_data = $this->Management_m->updateIsolation($data);
		}else {
			$response_data['code'] = 601;
		}
		$this->responseForFront($response_data);
	}

	private function sessionCheck()
	{
		$return = true;

		if(!$this->session->userdata('rs_id') || !$this->session->userdata('p_id')){
			$return = false;
		}

		return $return;
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
