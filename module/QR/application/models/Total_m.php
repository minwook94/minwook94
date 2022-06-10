<?
class Total_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

    public function getMainData($rs_id){
        $return_data = array();

        $this->db->select('can.c_id , can.c_num , can.c_name , can.c_class , can.c_part , can.p_id , place.p_name , confirm.c_confirm , confirm.confirm_date');
        $this->db->from($this->db_name.'.qr_candidate as can');
        $this->db->join($this->db_name.'.qr_place as place','can.p_id = place.p_id','INNER');
        $this->db->join($this->db_name.'.qr_candidate_confirm as confirm','can.c_id = confirm.c_id','LEFT');
        $this->db->where('can.rs_id', $rs_id);
        $candidates = $this->db->get()->result();

        // echo '<pre>';
        // print_r($candidates[0]);
        // echo '</pre>';

        if(count($candidates)){
            $this->load->library('seed_for_lib');

            // 장소별 통계
            $place_stat = array();
            // 분야별 통계
            $part_stat = array(); 
            //전체통계
            $total_stat = array('all'=>count($candidates) , 'active'=>0 , 'iso'=>0);          

            foreach($candidates as $k => $v){
                $candidate_info = array('name' => @$this->seed_for_lib->kirbs_decrypt($v->c_name),'c_num'=>$v->c_num);
                
                $place_stat[$v->p_id]['p_name'] = $v->p_name;
                $place_stat[$v->p_id]['class'][$v->c_class]['all'][]= $candidate_info;
                empty($place_stat[$v->p_id]['all']) ? $place_stat[$v->p_id]['all'] = 1 : $place_stat[$v->p_id]['all']++;
                // $place_stat[$v->p_id]['all'] += 1;
                if(isset($v->c_confirm)){
                    empty($place_stat[$v->p_id]['active']) ? $place_stat[$v->p_id]['active'] = 1 : $place_stat[$v->p_id]['active']++;
                    $place_stat[$v->p_id]['class'][$v->c_class]['active'][]= $candidate_info;
                    if($v->c_confirm > 0){ //격리실
                        empty($place_stat[$v->p_id]['iso']) ? $place_stat[$v->p_id]['iso'] = 1 : $place_stat[$v->p_id]['iso']++;
                        $place_stat[$v->p_id]['class'][$v->c_class]['iso'][]= $candidate_info;

                        // if(empty($place_stat[$v->p_id]['class']['격리실'.$v->c_confirm])){
                            $place_stat[$v->p_id]['class']['격리실'.$v->c_confirm]['all'][] = $candidate_info; 
                            $place_stat[$v->p_id]['class']['격리실'.$v->c_confirm]['active'][] = $candidate_info; 
                        // }else{

                        // }
                    }
                }

                $part_stat[$v->c_part]['all'][]= $candidate_info;
                if(isset($v->c_confirm)){
                    $total_stat['active']++;
                    $part_stat[$v->c_part]['active'][]= $candidate_info;
                    if($v->c_confirm > 0){ //격리실
                        $total_stat['iso']++;
                        $part_stat[$v->c_part]['iso'][]= $candidate_info;
                    }
                }
            }
        }

        $return_data['place_stat'] = $place_stat;
        $return_data['part_stat'] = $part_stat;
        $return_data['total_stat'] = $total_stat;
        // echo '<pre>';
        // print_r($return_data['place_stat']);
        // echo '</pre>';

        // exit;
        
        return $return_data;
    }
}
?>