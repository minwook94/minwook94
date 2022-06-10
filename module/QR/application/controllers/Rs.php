<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rs extends CI_Controller {

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
	// private $rs_m;
	private $db_access_uri = array(
		'adminLogin',
		'rsList',
		'add',
		'detail',
		'addRs',
		'updateRs',
		'placeList',
		'addPlace',  
		'placeSearch',  
		'mgmt',  
		'getPaperItems',  
		'paperAction',  
		'paperAddItem',  
		'viewPaper',  
		'totalUserSetting',  
	);
	public function __construct(){
		parent::__construct();
		if(in_array($this->uri->rsegment(2), $this->db_access_uri)){
			$this->load->model('rs_m');
		}
	}

    public function index()
	{
		// $this->load->helper( array ( 'form' , 'url' ) ) ;
		$this->session->unset_userdata('admin_id');
		$this->session->unset_userdata('admin_name');
		$this->session->destroy;
		$view_data['head_html'] = $this->load->view('rs/head',array('title'=>'문진표 예약::Login'),true);
		$this->load->view('rs/login',$view_data);
	}
	
	public function adminLogin(){
		$res_data = array(
			'code'=>700,
			'msg'=>'No Post method.'
		);
		if($this->input->method(true) === 'POST'){
			$post_data = $this->input->post();
			$res_data['code'] = 600;
			$res_data['msg'] = 'No Post Data.';
			if(!empty($post_data['admin_email']) && !empty($post_data['admin_pw'])){
				$res_data['code'] = 601;
				$res_data['msg'] = 'fail login';
				$login_result = $this->rs_m->adminLogin(array('admin_email'=>$post_data['admin_email'],'admin_pw'=>hash("sha256", $post_data['admin_pw'])));
				if($login_result){
					$res_data['code'] = 200;
					$res_data['msg'] = 'Success';
				}
			}
		}
		$this->responseForFront($res_data);
	}

	public function main(){
		if($this->adminSessChk()){
			$view_data['head_html'] = $this->load->view('rs/head',array('title'=>'문진표 예약::Main'),true);
			$this->load->view('rs/main',$view_data);
		}else{
			$this->redirecting('rs');
		}
	}

	public function add(){
		if($this->adminSessChk()){
			$view_data['head_html'] = $this->load->view('rs/head',array('title'=>'문진표 예약::Add'),true);
			$view_data['items'] = $this->rs_m->getAddItems();
			$this->load->view('rs/add',$view_data); 
		}else{
			$this->redirecting('rs');
		}
	}
	
	public function detail($rs_id=null){
		if($this->adminSessChk()){
			// $this->load->library('seed_for_lib');
			$view_data['head_html'] = $this->load->view('rs/head',array('title'=>'문진표 예약::Detail'),true);
			$view_data['items'] = $this->rs_m->getAddItems();
			$view_data['data'] = $this->rs_m->getRsDetailData($rs_id);
			$view_data['rs_id'] = $rs_id;

			if(!$view_data['data']['rs_data']){
				// echo "test";
				$this->load->view('errors/custom_404',array('message'=>'존재하지않는 예약입니다. URL을 임의로 변경하지 마십시오.'));
			}else{
				$this->load->view('rs/detail',$view_data);
			}
		}else{
			$this->redirecting('rs');
		}
	}

	public function addRs(){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout.'
		);
		if($this->adminSessChk()){
			$res_data['code'] = 700;
			$res_data['msg'] = 'No Post method.';
			if($this->input->method(true) === 'POST'){
				$res_data['code'] = 500;
				$res_data['msg'] = 'Fail to add RS.';
				
				$rs_add_result = $this->rs_m->addRs($this->input->post());
				if($rs_add_result){
					$res_data['code'] = 200;
					$res_data['rs_id'] = $rs_add_result;
					$res_data['msg'] = 'Success.';
				}
			}
		}
		$this->responseForFront($res_data);
	}
	public function placeSearch($place_name){
		// echo urldecode($place_name);
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout.'
		);
		if($this->adminSessChk()){
			$res_data['code'] = 201;
			$res_data['msg'] = 'No data.';
			
			$db = $this->rs_m->db;
			$db->select('*');
			$db->from($this->rs_m->db_name.'.qr_place');
			$db->like('p_name',urldecode($place_name));
			$place_list = $db->get()->result();
			if(count($place_list) > 0){
				$res_data['code'] = 200;
				$res_data['msg'] = 'Success.';
				$res_data['data'] = $place_list;
			}
		}
		$this->responseForFront($res_data);
	}
	public function updateRs($rs_id){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout.'
		);
		if($this->adminSessChk()){
			$res_data['code'] = 700;
			$res_data['msg'] = 'No Post method.';

			// echo '<pre>';
			// print_r($this->input->post());
			// echo '</pre>';
			// exit;
			if($this->input->method(true) === 'POST'){
				$res_data['code'] = 500;
				$res_data['msg'] = 'Fail to add RS.';
				$rs_add_result = $this->rs_m->updateRs($this->input->post() , $rs_id);
				if($rs_add_result){
					$res_data['code'] = 200;
					// $res_data['rs_id'] = $rs_add_result;
					$res_data['msg'] = 'Success.';
				}
			}
		}
		$this->responseForFront($res_data);
	}

	public function rsList($page){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout'
		);

		if($this->adminSessChk()){
			$res_data['code'] = 600;
			$res_data['msg'] = 'No Data.';
			
			$per = 10;
			$limit = ($page-1)*$per;
			$rs_list = $this->rs_m->rsList($limit,$per);
			if(!empty($rs_list['rs_list'])){
				$res_data['code'] = 200;
				$res_data['msg'] = 'Success.';
				$res_data['rs_list'] = $rs_list['rs_list'];
				$res_data['per'] = $per;
			}
			$res_data['rs_all_cnt'] = $rs_list['rs_all_cnt'];
		}
		$this->responseForFront($res_data);
	}

	public function mgmt($page){
		if($this->adminSessChk()){
			$items = array(
				'place'=>array('title'=>'장소관리' , 'data'=>null),
				'paper'=>array('title'=>'문진표/동의서 관리' , 'data'=>$this->rs_m->getPaperList()),
			);
			if(!empty($items[$page])){
				$view_data['head_html'] = $this->load->view('rs/head',array('title'=>'문진표 관리::'.$items[$page]['title']),true);
				$view_data['data'] = $items[$page]['data'];
				
				$this->load->view('rs/mgmt_'.$page,$view_data);

			}else{
				$this->load->view('errors/custom_404',array('message'=>'존재하지않는 페이지입니다. URL을 임의로 변경하지 마십시오.'));
			}
		}else{
			$this->redirecting('rs');
		}
	}

	public function getPaperItems($table , $id){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout'
		);
		if($this->adminSessChk()){
			$res_data['code'] = 600;
			$res_data['msg'] = 'No Data.';

			$id_names =  array(
				'qr_question_items'=>array('pk'=>'q_id','div'=>'questionsItemBox','order_by'=>'q_order'),
				'qr_agree_items'=>array('pk'=>'a_id','div'=>'agreeItemBox','order_by'=>'a_order')
			);
			
			$this->rs_m->db->select('*');
			$this->rs_m->db->from($this->rs_m->db_name.'.'.$table);
			$this->rs_m->db->where($id_names[$table]['pk'] , $id);
			$this->rs_m->db->order_by($id_names[$table]['order_by'],'ASC');
			$data = $this->rs_m->db->get()->result();

			if(count($data)){
				$res_data['code'] = 200;
				$res_data['msg'] = 'Success.';
				$res_data['data'] = $data;
				// $res_data['box'] = $id_names[$table]['div'];
			}
		}

		$this->responseForFront($res_data);
	}

	public function paperAction(){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout'
		);
		if($this->adminSessChk()){

			$db_info = array(
				'question' => array('pk'=>'q_id','main_table'=>'qr_questions','item_table'=>'qr_question_items'),
				'agree' => array('pk'=>'a_id','main_table'=>'qr_agree','item_table'=>'qr_agree_items')
			);
			
			$req_data = $this->inputSetting();

			$res_data['code'] = 501;
			$res_data['msg'] = 'Fail.';

			$db = $this->rs_m->db;

			switch($req_data['action_prop']['action']){
				case 'delete' :
					$db->trans_start();
					$db->where($db_info[$req_data['action_prop']['div']]['pk'] , $req_data['action_prop']['index']);
					$item_del_result = $db->delete($this->rs_m->db_name.'.'.$db_info[$req_data['action_prop']['div']]['item_table']);
					if($item_del_result){
						$db->where($db_info[$req_data['action_prop']['div']]['pk'] , $req_data['action_prop']['index']);
						$main_del_result = $db->delete($this->rs_m->db_name.'.'.$db_info[$req_data['action_prop']['div']]['main_table']);
						if($main_del_result){
							$db->trans_complete();
							$res_data['code'] = 200;
							$res_data['msg'] = 'Success.';
						}
					}
					break;
					case 'update' :
						// echo '<pre>';
						// print_r($req_data);
						// echo '</pre>';
						// exit;
						$db->trans_start();
						$db->where($db_info[$req_data['action_prop']['div']]['pk'] , $req_data['action_prop']['index']);
						$item_del_result = $db->delete($this->rs_m->db_name.'.'.$db_info[$req_data['action_prop']['div']]['item_table']);
						if($item_del_result){
							$main_insert_result = $db->insert_batch($this->rs_m->db_name.'.'.$db_info[$req_data['action_prop']['div']]['item_table'],$req_data['items']);
							if($main_insert_result){
								$db->trans_complete();
								$res_data['code'] = 200;
								$res_data['msg'] = 'Success.';
							}
						}
				break;
			}
		}
		$this->responseForFront($res_data);
	}

	public function paperAddItem(){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout'
		);
		if($this->adminSessChk()){
			$req_data = $this->inputSetting();

			$res_data['code'] = 501;
			$res_data['msg'] = 'Fail.';

			$db = $this->rs_m->db;

			$db->trans_start();
			if($db->insert($this->rs_m->db_name.'.'.$req_data['group_table'],$req_data['group'])){
				$pk = $db->insert_id();
				
				foreach($req_data['items'] as $k => $v){
					$req_data['items'][$k][$req_data['id']] = $pk;
				}

				if($db->insert_batch($this->rs_m->db_name.'.'.$req_data['item_table'],$req_data['items'])){
					$res_data['code'] = 200;
					$res_data['msg'] = 'Success.';
				}
			}
		}
		$this->responseForFront($res_data);
	}

	public function placeList($page){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout'
		);

		if($this->adminSessChk()){
			$res_data['code'] = 600;
			$res_data['msg'] = 'No Data.';
			
			$per = 10;
			$limit = ($page-1)*$per;
			$rs_list = $this->rs_m->placeList($limit,$per);
			if(!empty($rs_list['place_list'])){
				$res_data['code'] = 200;
				$res_data['msg'] = 'Success.';
				$res_data['place_list'] = $rs_list['place_list'];
				$res_data['per'] = $per;
			}
			$res_data['place_all_cnt'] = $rs_list['place_all_cnt'];
		}
		$this->responseForFront($res_data);
	}
	
	public function addPlace(){
		$res_data = array(
			'code'=>601,
			'msg'=>'Session timeout.'
		);
		if($this->adminSessChk()){
			$res_data['code'] = 700;
			$res_data['msg'] = 'No Post method.';

			if($this->input->method(true) === 'POST'){
				$res_data['code'] = 500;
				$res_data['msg'] = 'Fail to add place.';
				
				$place_add_result = $this->rs_m->db->insert($this->rs_m->db_name.'.qr_place',$this->input->post());
				if($place_add_result){
					$res_data['code'] = 200;
					$res_data['msg'] = 'Success.';
				}
			}
		}
		$this->responseForFront($res_data);
	}


	public function viewPaper(){
		if($this->adminSessChk()){

			$get_data = $this->input->get();
			$rs_id = null;
			$p_id = null;
			$c_id = null;
			if(!empty($get_data['rs_id'])){ $rs_id = $get_data['rs_id']; }
			if(!empty($get_data['p_id'])){ $p_id = $get_data['p_id']; }
			if(!empty($get_data['c_id'])){ $c_id = $get_data['c_id']; }

			$view_data = $this->rs_m->getPaperData($rs_id , $p_id , $c_id);

			$this->load->view('rs/view_paper',$view_data);
			
		}else{
			$this->redirecting('rs');
		}
	}

	public function pdf_exam(){
		$this->load->view('rs/pdf_exam');
	}

	public function totalUserSetting(){
		$req_data = $this->inputSetting();
		// echo '<pre>';
		// print_r($req_data);
		// echo '</pre>';
		$code = $this->rs_m->totalUserSetting($req_data);
		$this->responseForFront(array('code'=>$code));

	}


	// ====== public module =======
	private function adminSessChk(){
		$return = false;
		// if(!empty($this->session->userdata('admin_id')) && !empty($this->session->userdata('admin_name'))){
		if($this->session->userdata('admin_id') && $this->session->userdata('admin_name')){
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


	// ======== work========

	// public function addCandidate(){
		// $tester_data = array(
		// 	array('79','1','1','11110001','우민욱'),
		// 	array('79','1','1','11110002','유동현'),
		// 	array('79','1','1','11110003','응시자1'),
		// 	array('79','1','2','11110004','응시자2'),
		// 	array('79','1','2','11110005','응시자3'),
		// 	array('79','1','2','11110006','응시자4'),
		// 	array('79','1','3','11110007','응시자5'),
		// 	array('79','1','3','11110008','응시자6'),
		// 	array('79','1','3','11110009','응시자7'),
		// 	array('79','1','3','11110010','응시자8'),
		// 	array('79','2','1','11110011','응시자9'),
		// 	array('79','2','1','11110012','응시자10'),
		// 	array('79','2','1','11110013','응시자11'),
		// 	array('79','2','2','11110014','응시자12'),
		// 	array('79','2','2','11110015','응시자13'),
		// 	array('79','2','2','11110016','응시자14'),
		// 	array('79','2','3','11110017','응시자15'),
		// 	array('79','2','3','11110018','응시자16'),
		// 	array('79','2','3','11110019','응시자17'),
		// 	array('79','2','3','11110020','응시자18'),
		// 	array('79','3','1','11110021','응시자19'),
		// 	array('79','3','1','11110022','응시자20'),
		// 	array('79','3','1','11110023','응시자21'),
		// 	array('79','3','2','11110024','응시자22'),
		// 	array('79','3','2','11110025','응시자23'),
		// 	array('79','3','2','11110026','응시자24'),
		// 	array('79','3','3','11110027','응시자25'),
		// 	array('79','3','3','11110028','응시자26'),
		// 	array('79','3','3','11110029','응시자27'),
		// 	array('79','3','3','11110030','응시자28'),
		// 	array('79','4','1','11110031','응시자29'),
		// 	array('79','4','1','11110032','응시자30'),
		// 	array('79','4','1','11110033','응시자31'),
		// 	array('79','4','2','11110034','응시자32'),
		// 	array('79','4','2','11110035','응시자33'),
		// 	array('79','4','2','11110036','응시자34'),
		// 	array('79','4','3','11110037','응시자35'),
		// 	array('79','4','3','11110038','응시자36'),
		// 	array('79','4','3','11110039','응시자37'),
		// 	array('79','4','3','11110040','응시자38'),
		// 	array('79','5','1','11110041','응시자39'),
		// 	array('79','5','1','11110042','응시자40'),
		// 	array('79','5','1','11110043','응시자41'),
		// 	array('79','5','2','11110044','응시자42'),
		// 	array('79','5','2','11110045','응시자43'),
		// 	array('79','5','2','11110046','응시자44'),
		// 	array('79','5','3','11110047','응시자45'),
		// 	array('79','5','3','11110048','응시자46'),
		// 	array('79','5','3','11110049','응시자47'),
		// 	array('79','5','3','11110050','응시자48'),
		// 	array('79','6','1','11110051','응시자49'),
		// 	array('79','6','1','11110052','응시자50'),
		// 	array('79','6','1','11110053','응시자51'),
		// 	array('79','6','2','11110054','응시자52'),
		// 	array('79','6','2','11110055','응시자53'),
		// 	array('79','6','2','11110056','응시자54'),
		// 	array('79','6','3','11110057','응시자55'),
		// 	array('79','6','3','11110058','응시자56'),
		// 	array('79','6','3','11110059','응시자57'),
		// 	array('79','6','3','11110060','응시자58'),
		// );

		// $this->load->library('seed_for_lib');
		// $insert_arr = array();
		// foreach($tester_data as $row){
		// 	$insert_arr[] = array(
		// 		'rs_id'=>$row[0],
		// 		'p_id'=>$row[1],
		// 		'c_class'=>$row[2],
		// 		'c_num'=>$row[3],
		// 		'c_name'=>@$this->seed_for_lib->kirbs_encrypt($row[4]),
		// 	);
		// }
		// $this->load->model('rs_m');
		// echo $this->rs_m->db->insert_batch('question.qr_candidate',$insert_arr);

	// 	$confirmer_data = array(
	// 		array('79','1','확인관1'),
	// 		array('79','2','확인관2'),
	// 		array('79','3','확인관3'),
	// 		array('79','4','확인관4'),
	// 		array('79','5','확인관5'),
	// 		array('79','6','확인관6'),
	// 	); 

	// 	$insert_arr = array();
	// 	foreach($confirmer_data as $k => $v){
	// 		$insert_arr[] = array(
	// 			'rs_id'=> $v[0],
	// 			'p_id'=> $v[1],
	// 			'cf_name'=> @$this->seed_for_lib->kirbs_encrypt($v[2]),
	// 			'cf_pw'=>hash('sha256','1'),
	// 		);
	// 	}
	// 	echo '<pre>';
	// 	print_r($insert_arr);
	// 	echo '</pre>';

	// 	echo $this->rs_m->db->insert_batch('question.qr_confirmer',$insert_arr);
	// }

}
