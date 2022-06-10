<?
class Info_m extends CI_Model{

    public $db_name;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_name = $this->config->config['db_name'];
    }

	public function call_rs($rs_id){
		$query = "select * from qr_reservation where rs_id=".$rs_id;
		$rs_data = $this->db->query($query)->row_array();

		$query = "select c_class,p_id,concat(p_id,'_',c_class) as place_code ,count(*) as num from qr_candidate where rs_id=".$rs_id." group by p_id,c_class order by p_id,c_class";
		$tm = $this->db->query($query)->result_array();
		$class_data = array();
		foreach($tm as $key=>$val){
			$class_data[$val['p_id']][] = $val; 
		}
		


		$query = "
		select a.p_id,p_name from 
		(select p_id from qr_candidate where rs_id=".$rs_id." group by p_id order by p_id) g
		inner join
		qr_place a
		on a.p_id = g.p_id
		";
		$place_data = $this->db->query($query)->result_array();

		$result_class = $this->call_tester($rs_id);

		return array("place_data"=>$place_data,"rs_data"=>$rs_data,"class_data"=>$class_data,"result_class"=>$result_class);
	}

	public function call_tester($rs_id){
		$query = "select 
		t.c_class,concat(p_id,'_',c_class) as place_code,count(*) as num
		from 
		qr_candidate t
		inner join
		qr_candidate_confirm tf
		on t.c_id = tf.c_id
		where t.rs_id=".$rs_id."
		group by t.p_id,t.c_class";
		$result = $this->db->query($query)->result_array();
		$tm = array();
		foreach($result as $key=>$val){
			$tm[$val['place_code']] = $val['num'];
		}
		return $tm;
	}

}
?>