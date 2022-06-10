<?

class Candidate_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

    public function login($data){

        $this->load->library('seed_for_lib');

        $response_data = array();
        $code = 500;

        $name = @$this->seed_for_lib->kirbs_encrypt($data['name']);

        $this->db->select('qc.c_id, qc.c_name, qc.c_class, qc.rs_id, qp.p_name, qp.addr, qcc.c_confirm');
        $this->db->from($this->db_name.'.qr_candidate as qc');
        $this->db->join($this->db_name.'.qr_place as qp', 'qc.p_id = qp.p_id', 'left');
        $this->db->join($this->db_name.'.qr_candidate_confirm as qcc', 'qc.c_id = qcc.c_id', 'left');
        $this->db->where('c_num', $data['c_num']);
        $this->db->where('c_name', $name);
        $this->db->where('rs_id', $data['rs_id']);

        $query = $this->db->get()->row_array();

        if(count($query) > 0){

            if(!isset($query['c_confirm'])){
                $response_data = array(
                    'c_class' => $query['c_class'],
                    'p_name' => $query['p_name'],
                    'addr' => $query['addr']
                );

                $session_data = array(
                    'name' => $this->seed_for_lib->kirbs_decrypt($query['c_name']),
                    'c_id' => $query['c_id'],
                    'rs_id' => $data['rs_id']
                );

                $code = 200;
                $this->session->set_userdata($session_data);

            }else{
                $code = 202;
            }
        }else{
            $code = 201;
        }
        $response_data['code'] = $code;

        return $response_data;
    }
    
    public function save_img($data)
    {
        $code = 501;

        $this->db->trans_start();

        $question = json_encode($data['user_data']);

        $insert_data = array(
            'c_id' => $data['c_id'],
            'answer' => $question,
            'sign_img' => $data['sign_img'],
        );

        $this->db->insert($this->db_name.'.qr_candidate_answer', $insert_data);
        
        $db_error = $this->db->error();

        if($db_error['code'] == 0){
            $code = 200;

            // 내방 검사 한정
            $contents = array('title'=>'응시자 문진표 작성 안내','message'=>'응시자가 문진표 작성을 완료하였습니다.','link'=>'https://kirbs.re.kr/QRtest_v2/checker/'.$data['rs_id'],'image_url'=>'');
            $user_id = 'minwook.woo';
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.hiworks.com/office/v2/notify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\n   \"user_list\":[\n      \"$user_id \"\n   ],\n   \"message\":\"".$contents['message']."\",\n   \"link\":\"".$contents['link']."\",\n   \"mlink\":\"https://m.hiworks.com\",\n   \"solution_name\":\"".$contents['title']."\",\n   \"solution_image_url\":\"".$contents['image_url']."\",\n   \"solution_default_url\":\"https://www.hiworks.com\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ee08dbe89cc5e9de49f005e98e6b870e",
                "Content-Type: application/json"
            ),
            ));

            $response = curl_exec($curl);
        }

        $response_data = array(
            'code' => $code,
        );
        return $response_data;
    }

    public function getCandidateItems($data)
    {

        $this->load->library('seed_for_lib');

        $this->db->select('company_name');
        $this->db->from($this->db_name.'.qr_reservation');
        $this->db->where('rs_id', $data['rs_id']);
        $result = $this->db->get()->row_array();
        $query['company_name'] = $result['company_name'];

        $this->db->select('c_name');
        $this->db->from($this->db_name.'.qr_candidate');
        $this->db->where('c_id', $data['c_id']);
        $result = $this->db->get()->row_array();
        $c_name = $result['c_name'];

        $this->db->select('answer_id');
        $this->db->from($this->db_name.'.qr_candidate_answer');
        $this->db->where('c_id', $data['c_id']);
        $result = $this->db->get()->row_array();
        $query['answer_id'] = $result['answer_id'];

        $query['c_name'] = $this->seed_for_lib->kirbs_decrypt($c_name);

        return $query;
    }
    public function checkAnswers($data)
    {
        $this->db->select('c_id');
        $this->db->from($this->db_name.'.qr_candidate_answer');
        $this->db->where('c_id', $data);

        $query = $this->db->get()->row_array();

        if(count($query) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function checkPlace($data)
    {

        $this->db->select('p_id');
        $this->db->from($this->db_name.'.qr_candidate');
        $this->db->where('c_id', $data['c_id']);
        $this->db->where('rs_id', $data['rs_id']);
        
        $result = $this->db->get()->row_array();

        $p_id = $result['p_id'];

        $this->db->select('p_name, addr');
        $this->db->from($this->db_name.'.qr_place');
        $this->db->where('p_id', $p_id);

        $place = $this->db->get()->row_array();

        return $place;
    }
}
?>