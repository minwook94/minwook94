<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Total extends CI_Controller {

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
		$this->load->model('Total_m');
	}

    public function index($rs_id=null)
    {
        $this->session->unset_userdata('total_user_id');
		// $this->session->unset_userdata('c_id');
		// $this->session->unset_userdata('rs_id');

		$this->session->sess_destroy();

		$this->Total_m->db->select('qr.company_name, qr.test_date, qr.login_s_time, qr.login_e_time , total.total_user_id');
		$this->Total_m->db->from('qr_reservation as qr');
		$this->Total_m->db->join('qr_total_user as total' , 'qr.rs_id = total.rs_id' , 'LEFT');
		$this->Total_m->db->where('qr.rs_id', $rs_id);
		$query = $this->Total_m->db->get()->row();

		// echo '<pre>';
		// print_r($query);
		// echo '</pre>';

		if(!empty($query)){
			$s_time = str_replace('-', '', $query->test_date).$query->login_s_time;
			$e_time = str_replace('-', '', $query->test_date).$query->login_e_time;
			
			$login_acc = false;

			if(date('YmdHi') >= $s_time && date('YmdHi') < $e_time){
				$login_acc = true;
			}
			
			// $query->rs_id = $rs_id;
			$query->login_acc = $login_acc;

			$query->head_html = $this->load->view('total/head', array('rs_id'=>$rs_id , 'company_name'=>$query->company_name , 'base_url'=>$this->config->config['base_url']) , true);
			$this->load->view('total/index', $query);
		}else{
			$data['message'] = '잘못된 접근입니다.';
			
			$this->load->view('errors/custom_404', $data);
		}
    }

	public function loginChk()
	{
		$res_data = array(
			'code'=>700,
			'msg'=>'No Post method.'
		);
		
		if($this->input->method(true) === 'POST'){
			$res_data['code'] = 600;
			$res_data['msg'] = 'Not Total User.';
			
			$req_data = $this->inputSetting();
			
			$this->Total_m->db->select('total_user_id , total_user_pw');
			$this->Total_m->db->from($this->Total_m->db_name.'.qr_total_user');
			$this->Total_m->db->where('rs_id',$req_data['rs_id']);
			$total_db = $this->Total_m->db->get()->row();
			
			if(!empty($total_db)){
				if($total_db->total_user_id == $req_data['total_user_id']  && $total_db->total_user_pw == hash('sha256',$req_data['total_user_pw'])){
					$this->session->set_userdata('total_user_id',$req_data['total_user_id']);
					$this->session->set_userdata('rs_id',$req_data['rs_id']);
					$res_data['code'] = 200;
					$res_data['msg'] = 'Success.';
				}
			}
		}
		$this->responseForFront($res_data);
	}

	public function main()
	{
		if($this->adminSessChk()){
			// echo "test";
			// echo '<pre>';
			// print_r($this->session->userdata());
			// echo '</pre>';
			// $view_data['data'] = $this->Total_m->getMainData();

			$this->Total_m->db->select('company_name ,test_date');
			$this->Total_m->db->from($this->Total_m->db_name.'.qr_reservation');
			$this->Total_m->db->where('rs_id',$this->session->userdata('rs_id'));
			$view_data['data'] = $this->Total_m->db->get()->row();

			$view_data['head_html'] = $this->load->view('total/head', array('rs_id'=>$this->session->userdata('rs_id') , 'base_url'=>$this->config->config['base_url']) , true);
			$this->load->view('total/main', $view_data);


			
		}else{
			// $this->redirecting('total/');
			echo "세션기간이 만료되었습니다. 다시 로그인해주세요. <a href='#' onclick='moveLoginPage();'>로그인 페이지로 이동</a><script>function moveLoginPage(){ history.back(); }</script>";
		}
	}

	public function getMainData($rs_id){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout.'
		);
		if($this->adminSessChk()){
			$res_data['data'] = $this->Total_m->getMainData($rs_id);
			$res_data['code'] = 200;
			$res_data['msg'] = 'Success.';
		}

		// echo '<pre>';
		// print_r($res_data['data']['place_stat']);
		// echo '</pre>';
		// exit;
		$this->responseForFront($res_data);
	}



	private function adminSessChk(){
		$return = false;
		if($this->session->userdata('total_user_id') && $this->session->userdata('rs_id')){
			$return = true;
		}
		return $return;
	}

	private function redirecting($uri){
		$this->load->helper('url');
		redirect('https://'.$this->input->server('HTTP_HOST').$this->config->base_url().$uri);
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