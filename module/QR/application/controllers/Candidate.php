<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Candidate extends CI_Controller {

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
		$this->load->model('Candidate_m');
	}
	//로그인 화면
	public function index($rs_id=null)
	{

		$this->session->unset_userdata('name');
		$this->session->unset_userdata('c_id');
		$this->session->unset_userdata('rs_id');

		$this->session->sess_destroy();

		$this->Candidate_m->db->select('company_name, test_date, login_s_time, login_e_time');
		$this->Candidate_m->db->from('qr_reservation');
		$this->Candidate_m->db->where('rs_id', $rs_id);
		$query = $this->Candidate_m->db->get()->row();

		if(!empty($query)){
			$s_time = str_replace('-', '', $query->test_date).$query->login_s_time;
			$e_time = str_replace('-', '', $query->test_date).$query->login_e_time;
			
			$login_acc = false;

			if(date('YmdHi') >= $s_time && date('YmdHi') < $e_time){
				$login_acc = true;
			}
			
			$query->rs_id = $rs_id;
			$query->login_acc = $login_acc;

			$this->load->view('candidate/index', $query);
		}else{
			$data['message'] = '잘못된 접근입니다.';
			
			$this->load->view('errors/custom_404', $data);
		}
	}
	//문진표 화면
	public function question()
	{
		if($this->sessionCheck()){
			$this->load->model('Rs_m');
			$data = $this->Rs_m->getViewItems($this->session->userdata('rs_id'));

			$data['rs_id'] = $this->session->userdata('rs_id');

			$this->load->model('Candidate_m');
			$answer = $this->Candidate_m->checkAnswers($this->session->userdata('c_id'));
			if($answer){
				$this->result();
			}else{
				$this->load->view('candidate/question', $data);
			}
		}else{
			$this->redirectToLogin();
		}
	}

	public function result()
	{
		if($this->sessionCheck()){
			$this->load->model('Candidate_m');

			$request_data = array('rs_id' => $this->session->userdata('rs_id'),'c_id' => $this->session->userdata('c_id'));

			$data = $this->Candidate_m->getCandidateItems($request_data);
			$data['rs_id'] = $this->session->userdata('rs_id');
			$data['c_id'] = $this->session->userdata('c_id');
			
			$data['place'] = $this->Candidate_m->checkPlace($data);

			$this->load->view('candidate/result', $data);
		}else{
			$this->redirectToLogin();
		}
	}
	//로그인
	public function login()
	{
		$data = $this->inputSetting();

		$result = $this->Candidate_m->login($data);

		$this->responseForFront($result);
	}

	// 세션 체크
	private function sessionCheck()
	{
		$return = true;

		// if(empty($this->session->userdata('c_id'))){
		if(!$this->session->userdata('c_id')){
			$return = false;
		}

		return $return;
	}

	// 로그인 페이지로 redirect
	private function redirectToLogin()
	{
		$this->load->helper('url');
		redirect('https://kirbs.re.kr'.$this->config->config['base_url'].'candidate/'.$this->session->userdata('rs_id'));
	}
	
	// 문진표 필요 사항 로드
	public function load()
	{
		$result = $this->Candidate_m->load();
	}

	// 이미지 및 입력 값 입력
	public function save_img()
	{

		if($this->sessionCheck()){

			$data = $this->inputSetting();
			$data['c_id'] = $this->session->userdata('c_id');
			$result = $this->Candidate_m->save_img($data);
		}else{
			$result['code'] = 601;
		}
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

	// public function tests()
	// {
	// 	$this->Candidate_m->test();
	// }

}
