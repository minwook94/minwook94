<?

class Rs_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

    public function getViewItems($rs_id){
        $return_arr = array();
        $this->db->select('q_id , a_id');
        $this->db->from($this->db_name.'.qr_reservation');
        $this->db->where('rs_id', $rs_id);
        $query = $this->db->get()->row();
        
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_question_items');
        $this->db->where('q_id', $query->q_id);
        $this->db->order_by('q_order','ASC');
        $return_arr['questions'] = $this->db->get()->result();
        
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_agree_items');
        $this->db->where('a_id', $query->a_id);
        $this->db->order_by('a_order','ASC');
        $return_arr['agrees'] = $this->db->get()->result();
        return $return_arr;
    }
    
    public function adminLogin($where){
        $return = false;
        $this->db->select('admin_id , admin_name');
        $this->db->from($this->db_name.'.qr_admin');
        $this->db->where($where);
        $query = $this->db->get()->row();

        // echo $this->db->last_query();
        if(!empty($query)){
            $this->session->set_userdata('admin_id', $query->admin_id);
            $this->session->set_userdata('admin_name', $query->admin_name);
            $return = true;
        }
        return $return;
    }

    public function rsList($limit , $per){
        $return = array();
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_reservation');
        $this->db->where('rs_id >','103');
        $this->db->order_by('rs_id','DESC');
        $this->db->limit($per, $limit);
        $return['rs_list'] = $this->db->get()->result();
        
        $this->db->select('count(*) as rs_all_cnt');
        $this->db->from($this->db_name.'.qr_reservation');
        $this->db->where('rs_id >','103');
        $return['rs_all_cnt'] = $this->db->get()->row()->rs_all_cnt;
        
        return $return;
    }

    public function placeList($limit , $per){
        $return = array();
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_place');
        $this->db->order_by('p_name','ASC');
        $this->db->limit($per, $limit);
        $return['place_list'] = $this->db->get()->result();
        
        $this->db->select('count(*) as place_all_cnt');
        $this->db->from($this->db_name.'.qr_place');
        $return['place_all_cnt'] = $this->db->get()->row()->place_all_cnt;
        
        return $return;
    }

    public function getAddItems(){
        $return = array();
        $this->db->select('q_id , q_name');
        $this->db->from($this->db_name.'.qr_questions');
        $return['questions'] = $this->db->get()->result();

        $this->db->select('a_id , a_name');
        $this->db->from($this->db_name.'.qr_agree');
        $return['agree'] = $this->db->get()->result();
        
        // $this->db->select('*');
        // $this->db->from($this->db_name.'.qr_place');
        // $return['place'] = $this->db->get()->result();
        return $return;
    }

    public function addRs($data){
        $return = false;
        
        $this->load->library('seed_for_lib');
        
        $candidate = json_decode($data['candidate']);
        $confirmer = json_decode($data['confirmer']);
        $place_group = json_decode($data['place_group']);
        
        unset($data['candidate']);
        unset($data['confirmer']);
        unset($data['place_group']);
        
        $data['admin_id'] = $this->session->userdata('admin_id');
        
        $this->db->trans_start();
        if($this->db->insert($this->db_name.'.qr_reservation', $data)){

            $rs_id = $this->db->insert_id();

            foreach($place_group as $k => $v){
                $code = $v->login_code;
                $place_group[$k]->rs_id = $rs_id;
                $place_group[$k]->login_code = hash('sha256', $code);
                $place_group[$k]->login_code_seed = @$this->seed_for_lib->kirbs_encrypt($code);
            }

            foreach($candidate as $k => $v){
                $candidate[$k]->rs_id = $rs_id;
                $candidate[$k]->c_name = @$this->seed_for_lib->kirbs_encrypt($v->c_name);
            }

            if($this->db->insert_batch($this->db_name.'.qr_place_group',$place_group)){
                if($this->db->insert_batch($this->db_name.'.qr_candidate',$candidate)){
                    foreach($confirmer as $k => $v){
                        $pw = $v->cf_pw;
                        $confirmer[$k]->rs_id = $rs_id;
                        $confirmer[$k]->cf_pw = hash('sha256', $pw); 
                        $confirmer[$k]->cf_pw_seed = @$this->seed_for_lib->kirbs_encrypt($pw); 
                        $confirmer[$k]->cf_name = @$this->seed_for_lib->kirbs_encrypt($v->cf_name);

                    }
                    if($this->db->insert_batch($this->db_name.'.qr_confirmer',$confirmer)){
                        $this->db->trans_complete();
                        $return = $rs_id;
                    }
                }
            }
        }
        return $return;
    }

    public function updateRs($data , $rs_id){
        $return = false;

        
        $candidate = json_decode($data['candidate']);
        unset($data['candidate']);
        
        $confirmer = null;
        if(!empty($data['confirmer'])){
            $confirmer = json_decode($data['confirmer']);
            unset($data['confirmer']);
        }

        $place_group = null;
        if(!empty($data['place_group'])){
            $place_group = json_decode($data['place_group']);
            unset($data['place_group']);
        }
        $this->load->library('seed_for_lib');
        $this->db->trans_start();
        $this->db->where('rs_id',$rs_id);
        $rs_update_result = $this->db->update($this->db_name.'.qr_reservation',$data);
        if($rs_update_result){

            $place_group_insert_chk = true;
            if(!empty($place_group)){
                $place_group_insert_chk = false;
                foreach($place_group as $k => $v){
                    $code = $v->login_code;
                    $place_group[$k]->rs_id = $rs_id;
                    $place_group[$k]->login_code = hash('sha256', $code);
                    $place_group[$k]->login_code_seed = @$this->seed_for_lib->kirbs_encrypt($code);
                }

                if($this->db->insert_batch($this->db_name.'.qr_place_group',$place_group)){
                    $place_group_insert_chk = true;
                }
            }

            if($place_group_insert_chk){ 

                $this->db->where('rs_id',$rs_id);
                if($this->db->delete($this->db_name.'.qr_candidate')){
    
                    
    
                    foreach($candidate as $k => $v){
                        $candidate[$k]->rs_id = $rs_id;
                        $candidate[$k]->c_name = @$this->seed_for_lib->kirbs_encrypt($v->c_name);
                    }
                    
                    if($this->db->insert_batch($this->db_name.'.qr_candidate',$candidate)){
                        if(!empty($confirmer)){
    
                            foreach($confirmer as $k => $v){
                                $pw = $v->cf_pw;
                                $confirmer[$k]->rs_id = $rs_id;
                                $confirmer[$k]->cf_pw = hash('sha256', $pw); 
                                $confirmer[$k]->cf_pw_seed = @$this->seed_for_lib->kirbs_encrypt($pw); 
                                $confirmer[$k]->cf_name = @$this->seed_for_lib->kirbs_encrypt($v->cf_name);
                            }
                            if($this->db->insert_batch($this->db_name.'.qr_confirmer',$confirmer)){
                                $this->db->trans_complete();
                                $return = true;
                            }
                        }else{
                            $this->db->trans_complete();
                            $return = true;
                        }
                    }
                }
            }
            
        }
        return $return;
    }

    public function getRsDetailData($rs_id){
        $return_arr = array();
        $this->load->library('seed_for_lib');

        $this->db->select('rs.* , t_user.total_user_id');
        $this->db->from($this->db_name.'.qr_reservation rs');
        $this->db->join($this->db_name.'.qr_total_user t_user','rs.rs_id = t_user.rs_id','LEFT');
        $this->db->where('rs.rs_id', $rs_id);
        $return_arr['rs_data'] = $this->db->get()->row();

        // $this->db->select('con.cf_id , con.cf_name , pl.p_name , pl.p_id');
        $this->db->select('con.cf_id , con.cf_name , con.p_id , con.cf_pw_seed');
        $this->db->from($this->db_name.'.qr_confirmer con');
        // $this->db->join($this->db_name.'.qr_place pl','con.p_id = pl.p_id','INNER');
        $this->db->where('rs_id', $rs_id);
        $this->db->order_by('cf_id', 'ASC');
        $return_arr['confirmer'] = $this->db->get()->result();
        
        foreach($return_arr['confirmer'] as $k => $v){
            $return_arr['confirmer'][$k]->cf_name = @$this->seed_for_lib->kirbs_decrypt($v->cf_name);
        }
        
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_candidate can');
        $this->db->join($this->db_name.'.qr_candidate_confirm conf','can.c_id = conf.c_id','LEFT');
        $this->db->where('rs_id', $rs_id);
        $this->db->order_by('can.c_id','ASC');
        $return_arr['candidate'] = $this->db->get()->result();
        
        foreach($return_arr['candidate'] as $k => $v){
            $return_arr['candidate'][$k]->c_name = @$this->seed_for_lib->kirbs_decrypt($v->c_name);
        }
        
        $this->db->select('pg.p_id , pg.login_code_seed , p.p_name');
        $this->db->from($this->db_name.'.qr_place_group pg');
        $this->db->join($this->db_name.'.qr_place p','pg.p_id = p.p_id','INNER');
        $this->db->where('pg.rs_id', $rs_id);
        $return_arr['place_group'] = $this->db->get()->result();

        return $return_arr;
    }

    public function getPaperList(){
        $return_data = array();
        
        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_questions');
        $return_data['questions'] = $this->db->get()->result();

        $this->db->select('*');
        $this->db->from($this->db_name.'.qr_agree');
        $return_data['agree'] = $this->db->get()->result();

        return $return_data;
    }

    public function getPaperData($rs_id=null , $p_id=null , $c_id=null){
        $return_data = array();
        //문진표, 동의서 폼 data
        $paper_keys = array('q_id'=>null , 'a_id'=>null); 
        $c_ids = array();
        if(!empty($c_id)){
            $this->db->select('rs_id')->from($this->db_name.'.qr_candidate')->where('c_id',$c_id);
            $rs_id_sub_q = $this->db->get_compiled_select();

            $this->db->select('q_id , a_id')->from($this->db_name.'.qr_reservation')->where('rs_id = ('.$rs_id_sub_q.')',null,false);
            $paper_keys = $this->db->get()->row_array();
            $c_ids[0] = array('c_id'=>$c_id);
        }else if(!empty($rs_id) && !empty($p_id)){
            
            $this->db->select('q_id , a_id')->from($this->db_name.'.qr_reservation')->where('rs_id',$rs_id);
            $paper_keys = $this->db->get()->row_array();
            
            $this->db->select('c_id')->from($this->db_name.'.qr_candidate')->where('rs_id',$rs_id)->where('p_id',$p_id);
            $c_ids = $this->db->get()->result_array();

        }


        $return_data['question_title'] = $this->db->select('q_name')->from($this->db_name.'.qr_questions')->where('q_id',$paper_keys['q_id'])->get()->row();
        $return_data['agree_title'] = $this->db->select('a_name')->from($this->db_name.'.qr_agree')->where('a_id',$paper_keys['a_id'])->get()->row();

        $return_data['question_itmes'] = $this->db->select('q_order , q_content')->from($this->db_name.'.qr_question_items')->where('q_id',$paper_keys['q_id'])->get()->result();
        $return_data['agree_itmes'] = $this->db->select('a_order , a_title , a_content , a_agree_type')->from($this->db_name.'.qr_agree_items')->where('a_id',$paper_keys['a_id'])->order_by('a_order','ASC')->get()->result();

        $ids = array();
        foreach($c_ids as $k => $v){
            $ids[] = $v['c_id'];
        }
        
        $this->db->select('ans.answer , ans.sign_img , can.c_name , can.c_num , rs.company_name , rs.test_date');
        $this->db->from($this->db_name.'.qr_candidate_answer ans');
        $this->db->join($this->db_name.'.qr_candidate can','ans.c_id = can.c_id','INNER');
        $this->db->join($this->db_name.'.qr_reservation rs','can.rs_id = rs.rs_id','INNER');
        $return_data['answer_data'] = $this->db->where_in('ans.c_id',$ids)->get()->result_array();

        $this->load->library('seed_for_lib'); 
        foreach($return_data['answer_data'] as $k => $v){
            $return_data['answer_data'][$k]['c_name'] = @$this->seed_for_lib->kirbs_decrypt($v['c_name']);
            $return_data['answer_data'][$k]['answer'] = json_decode($v['answer']);
        }
        return $return_data;
    }

    public function totalUserSetting($data){
        $code = 500;
        switch($data['action']){
            case 'insert':
                $insert_data = array(
                    'rs_id' => $data['rs_id'],
                    'total_user_id' => $data['total_user_id'],
                    'total_user_pw' => hash('sha256',$data['total_user_pw'])
                );
                if($this->db->insert($this->db_name.'.qr_total_user',$insert_data)){
                    $code = 200;
                }
                break;
            break;
            case 'update':
                $code = 501;

                $this->db->select('total_user_pw');
                $this->db->from($this->db_name.'.qr_total_user');
                $this->db->where('rs_id',$data['rs_id']);
                $old_pw = $this->db->get()->row();

                // echo $old_pw->total_user_pw;
                // echo "<br>";
                // echo hash('sha256',$data['total_user_old_pw']);
                // exit;

                if($old_pw->total_user_pw == hash('sha256',$data['total_user_old_pw'])){
                    $update_data = array(
                        'total_user_id' => $data['total_user_id'],
                        'total_user_pw' => hash('sha256',$data['total_user_pw'])
                    );
                    $this->db->where('rs_id',$data['rs_id']);
                    if($this->db->update($this->db_name.'.qr_total_user',$update_data)){
                        $code = 200;
                    }
                }

                // echo '<pre>';
                // print_r($old_pw);
                // echo '</pre>';

                // exit;

                // $this->db->where('rs_id',$data['rs_id']);
                // $this->db->where('total_user_pw',hash('sha256',$data['total_user_old_pw']));
                // $update_result = $this->db->update($this->db_name.'.qr_total_user',array('total_user_id'=>$data['total_user_id'],'total_user_pw'=>hash('sha256',$data['total_user_pw'])));
                // echo '<pre>';
                // print_r($this->db->last_query());
                // echo '</pre>';
                // exit;
                // if($update_result){
                //     $code = 200;
                // }
            break;
        }
        return $code;
    }
 }
?>