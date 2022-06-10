<?
class Checker_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

    public function login($data)
    {

        $response_data = array();
        
        $code = 500;

        $this->load->library('seed_for_lib');

        $data['cf_name'] = @$this->seed_for_lib->kirbs_encrypt($data['cf_name']);
        $this->db->select('p_id');
        $this->db->from('qr_confirmer');
        $this->db->where('cf_name', $data['cf_name']);
        $this->db->where("cf_pw = sha2('".$data['cf_pw']."', 256)", null, false);
        $this->db->where('rs_id', $data['rs_id']);

        $query = $this->db->get()->row_array();

        if(count($query) > 0){

            $session_data = array(
                'cf_name' => $data['cf_name'],
                'rs_id' => $data['rs_id']
            );

            $this->db->select('p_name, addr');
            $this->db->from('qr_place');
            $this->db->where('p_id', $query['p_id']);

            $response_data['place'] = $this->db->get()->row_array();

            $code = 200;
            $this->session->set_userdata($session_data);

        }else{
            $code = 201;
        }

        $response_data['code'] = $code;

        return $response_data;
    }

    public function logout()
    {
        $response_data = array();

        $code = 500;

        if($this->session->userdata('cf_name') == null){
            $code = 201;
        }
        $this->session->unset_userdata('cf_name');
		$this->session->unset_userdata('rs_id');
        
        $this->session->sess_destroy();

        if($this->session->userdata('cf_name') == null){
            $code = 200;
        }

        $response_data['code'] = $code;

        return $response_data;
    }

    public function qrCheck($data)
    {
        $this->load->library('seed_for_lib');
        $response_data = array();

        $code = 501;

        if($data['c_id'] == ''){
            $code = 502;
            $response_data['code'] = $code;
            return $response_data;
        }

        $this->db->select('*');
        $this->db->from('qr_candidate_confirm');
        $this->db->where('c_id', $data['c_id']);
        $this->db->where('answer_id', $data['answer_id']);

        $confirm = $this->db->get()->row_array();

        $this->db->select('answer');
        $this->db->from('qr_candidate_answer');
        $this->db->where(array(
            'answer_id' => $data['answer_id'],
            'c_id' => $data['c_id']
        ));
        
        $query = $this->db->get()->row_array();

        if(count($confirm) > 0){
            $code = 203;

            $this->db->select('c_name');
            $this->db->from('qr_candidate');
            $this->db->where('c_id', $data['c_id']);

            $candidate_result = $this->db->get()->row_array();

            $name = $this->seed_for_lib->kirbs_decrypt($candidate_result['c_name']);

            $response_data['code'] = $code;
            $response_data['name'] = $name;
            return $response_data;
        }

        $j=0;
        $response_data['q_content'] = array();
        if(count($query) > 0){

            $code = 202;

            $answer = json_decode($query['answer']);
 
            $this->db->select('q_id');
            $this->db->from('qr_reservation');
            $this->db->where('rs_id', $data['rs_id']);
            
            $q_id = $this->db->get()->row_array();

            $this->db->select('q_content');
            $this->db->from('qr_question_items');
            $this->db->where('q_id', $q_id['q_id']);

            $questions = $this->db->get()->result_array();

            for($i=0; $i<count($questions); $i++){ 
                if($answer->question[$i] == 1){
                    $response_data['q_content'][$j] = $questions[$i]['q_content'];
                    $j++;
                }
            }
            
            if(count($response_data['q_content']) == 0){
                $code = 200;
            }

        }else {
            $code =  201;
        }
        $response_data['code'] = $code;

        return $response_data;
    }

    public function qrNegativeConfirm($data)
    {
        $insert_data = array(
            'answer_id' => $data['answer_id'],
            'c_id' => $data['c_id'],
            'c_confirm' => 0
        );
        $this->db->insert('qr_candidate_confirm', $insert_data);
        $code = 200;
        $response_data['code'] = $code;
        return $response_data;
    }

    public function qrPositiveConfirm($data)
    {

        $insert_data = array(
            'answer_id' => $data['answer_id'],
            'c_id' => $data['c_id'],
            'c_confirm' => $data['isolation_room']
        );
        $this->db->insert('qr_candidate_confirm', $insert_data);
        $code = 200;
        $response_data['code'] = $code;
        return $response_data;
    }
}
?>