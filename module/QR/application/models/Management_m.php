<?

class Management_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

    public function load($rs_id, $p_id)
    {

        $this->db->select('qr.company_name, qp.p_name');
        $this->db->from($this->db_name.'.qr_place_group as qpg');
        $this->db->join('qr_place as qp', 'qpg.p_id = qp.p_id');
        $this->db->join('qr_reservation as qr', 'qpg.rs_id = qr.rs_id');
        $this->db->where('qpg.rs_id', $rs_id);
        $this->db->where('qpg.p_id', $p_id);

        $query = $this->db->get()->row_array();

        return $query;
    }

    public function login($data)
    {

        $response_data = array();

        $code = 501;

        $this->db->select('qrg.rs_id, qrg.p_id, p.p_name');
        $this->db->from($this->db_name.'.qr_place_group as qrg');
        $this->db->join('qr_place as p', 'qrg.p_id = p.p_id');
        $this->db->where('qrg.rs_id', $data['rs_id']);
        $this->db->where('qrg.p_id', $data['p_id']);
        $this->db->where("qrg.login_code = sha2('".$data['code']."', 256)", null, false);

        $result = $this->db->get()->row_array();

        if($result){
            $code = 200;

            $session_data = array(
                'rs_id' => $result['rs_id'],
                'p_id' => $result['p_id'],
                'p_name' => $result['p_name']
            );
            
            $this->session->set_userdata($session_data);
        }else{
            $code = 201;
        }

        $response_data['code'] = $code;

        return $response_data;

    }

    public function getMainData($data)
    {
        $this->db->select('c_class');
        $this->db->distinct('c_class');
        $this->db->from($this->db_name.'.qr_candidate');
        $this->db->where('rs_id', $data['rs_id']);
        $this->db->where('p_id', $data['p_id']);
        $this->db->order_by('c_class*1', 'asc', false);

        $query = $this->db->get()->result_array();

        $c_class = array();

        for($i=0; $i<count($query); $i++){
            array_push($c_class, $query[$i]['c_class']);
        }

        return $c_class;

    }

    public function getCandidateCount($data)
    {

        $this->db->select('qc.c_class, qcc.c_confirm');
        $this->db->from('qr_candidate as qc');
        $this->db->join('qr_candidate_confirm as qcc', 'qc.c_id = qcc.c_id', 'left');
        $this->db->where('qc.rs_id', $data['rs_id']);
        $this->db->where('qc.p_id', $data['p_id']);
        $this->db->order_by('qc.c_class', 'asc');

        $query = $this->db->get()->result_array();

       $count = array(
            'c_class' => null,
            'c_confirm' => null,
            'isolation' => null,
            'total' => 0,
            'confirm_total' => 0
        );

        $first_isolation = 0;
        $second_isolation = 0;

        foreach($query as $k=>$v){

            $count['total'] += 1;

            if(isset($count['c_class'][$v['c_class']])){
                $count['c_class'][$v['c_class']] += 1;
            }else{
                $count['c_class'][$v['c_class']] = 0;
                $count['c_class'][$v['c_class']] += 1;
            }
            
            if(isset($v['c_confirm'])){

                if($v['c_confirm'] == 0){
                    if(isset($count['c_confirm'][$v['c_class']])){
                        $count['c_confirm'][$v['c_class']] += 1;
                    }else{
                        $count['c_confirm'][$v['c_class']] = 0;
                        $count['c_confirm'][$v['c_class']] += 1;
                    }
                }else {
                    if(isset($count['isolation'][$v['c_class']])){
                        $count['isolation'][$v['c_class']] += 1;
                    }else{
                        $count['isolation'][$v['c_class']] = 0;
                        $count['isolation'][$v['c_class']] += 1;
                    }
                }

                if($v['c_confirm'] == 1){
                    $first_isolation += 1;
                }else if($v['c_confirm'] == 2){
                    $second_isolation += 1;
                }

                $count['confirm_total'] += 1;
            }
        }

        foreach($count['c_class'] as $k=>$v){
            if(!isset($count['c_confirm'][$k])){
                $count['c_confirm'][$k] = 0;
            }
            if(!isset($count['isolation'][$k])){
                $count['isolation'][$k] = 0;
            }
        }

        $count['percentage'] = round(($count['confirm_total'] / $count['total']) * 100, 1);

        $count['first_isolation'] = $first_isolation;
        $count['second_isolation'] = $second_isolation;

        // print_r($count);
        return $count;
    }

    public function getDetailData($data)
    {

        $code = 501;

        $response_data = array();

        if(empty($data['c_class'])){
            $code = 502;

            $response_data['code'] = $code;
            return $response_data;
        };

        $this->load->library('seed_for_lib');

        $this->db->select('qc.c_id, qc.c_num, qc.c_name, qc.c_part, qcc.c_confirm');
        $this->db->from($this->db_name.'.qr_candidate as qc');
        $this->db->join('qr_candidate_confirm as qcc', 'qcc.c_id = qc.c_id', 'LEFT');
        $this->db->where('rs_id', $data['rs_id']);
        $this->db->where('p_id', $data['p_id']);
        $this->db->where('c_class', $data['c_class']);
        $this->db->order_by('qc.c_num', 'asc');

        $query = $this->db->get()->result_array();

        if(count($query) > 0){
            
            $code = 200;
            foreach($query as $k=>$v){
                $query[$k]['c_name'] = $this->seed_for_lib->kirbs_decrypt($v['c_name']);
            }
        }else {
            $code = 201;
        }

        $response_data = array('code'=>$code, 'data'=>$query, 'c_class'=>$data['c_class']);

        return $response_data;

    }

    public function getIsolationDetailData($data)
    {

        $code = 501;

        $response_data = array();

        $this->load->library('seed_for_lib');

        $this->db->select('qc.c_num, qc.c_name, qc.c_class, qc.c_part, qcc.c_confirm');
        $this->db->from('qr_candidate as qc');
        $this->db->join('qr_candidate_confirm as qcc', 'qc.c_id = qcc.c_id', 'LEFT');
        $this->db->where('qc.rs_id', $data['rs_id']);
        $this->db->where('qc.p_id', $data['p_id']);
        $this->db->where('qcc.c_confirm', $data['c_confirm']);
        $this->db->order_by('qc.c_class', 'asc');

        $query = $this->db->get()->result_array();

        if(count($query) > 0){
            
            $code = 200;
            foreach($query as $k=>$v){
                $query[$k]['c_name'] = $this->seed_for_lib->kirbs_decrypt($v['c_name']);
            }

        }else {
            $code = 201;
        }
        $response_data = array(
            'code' => $code,
            'data' => $query,
        );

        return $response_data;
    }

    public function updateIsolation($data)
    {

        $code = 501;
        $this->db->trans_start();
        
        $this->db->select('c_id');
        $this->db->where('c_num', $data['c_num']);
        $this->db->where('rs_id', $data['rs_id']);
        $this->db->where('p_id', $data['p_id']);
        $query = $this->db->get($this->db_name.'.qr_candidate')->row_array();

        $this->db->where('c_id', $query['c_id']);
        $this->db->update('qr_candidate_confirm', array('c_confirm' => $data['c_confirm']));

        if($this->db->affected_rows() > 0){

            $this->db->trans_commit();
            $code = 200;
        }else{
            $this->db->trans_rollback();
            $code = 201;
        }
        $response_data = array(
            'code'=>$code,
        );

        return $response_data;
    }
}

?>