<?if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/KISA_SEED_ECB.php"; 

class Seed_for_lib extends KISA_SEED_ECB{
    
    public function __construct(){} 
    
    //암호화 key  'kirbskirbs196809' hex로 변환 (16bytes)
    private $_CIPHER_KEY = "6B,69,72,62,73,6B,69,72,62,73,31,39,36,38,30,39";
   
    
    //암호화 function
    //$str은 평문(해석된)으로 들어온다
    public function kirbs_encrypt($str){
        $planBytes = $this->strToHex($str);
        $keyBytes = explode(",",$this->_CIPHER_KEY);
        
        for($i = 0; $i < 16; $i++)
        {
            $keyBytes[$i] = hexdec($keyBytes[$i]);
        }
        for ($i = 0; $i < count($planBytes); $i++) {
            $planBytes[$i] = hexdec($planBytes[$i]);
        }
        
        if (count($planBytes) == 0) {
            return $str;
        }
        $ret = null;
        $bszChiperText = null;
        $pdwRoundKey = array_pad(array(),32,0);
        
        //방법 1
        $bszChiperText = $this->SEED_ECB_Encrypt($keyBytes, $planBytes, 0, count($planBytes));
        
        for($i=0;$i< sizeof($bszChiperText);$i++) {
            $ret .=  sprintf("%02X", $bszChiperText[$i]).",";
        }
        
        return substr($ret,0,strlen($ret)-1);
    }
    
    //복호화 function
    //$str : 암호화된 문자열
    public function kirbs_decrypt($str){
        $planBytes = explode(",", $str);
        $keyBytes = explode(",",$this->_CIPHER_KEY);
        
        for($i = 0; $i < 16; $i++)
        {
            $keyBytes[$i] = hexdec($keyBytes[$i]);
        }
        
        for ($i = 0; $i < count($planBytes); $i++) {
            $planBytes[$i] = hexdec($planBytes[$i]);
        }
        
        if (count($planBytes) == 0) {
            return $str;
        }
        
        $pdwRoundKey = array_pad(array(),32,0);
        
        $bszPlainText = null;
        $planBytresMessage = null;
        
        // 방법 1
        $bszPlainText = $this->SEED_ECB_Decrypt($keyBytes, $planBytes, 0, count($planBytes));
        for($i=0;$i< sizeof($bszPlainText);$i++) {
            $planBytresMessage .=  sprintf("%02X", $bszPlainText[$i]).",";
        }
        
        $hex_arr = explode(",",substr($planBytresMessage,0,strlen($planBytresMessage)-1));
        
        return $this->hexToStr($hex_arr);
    }
    
    
    //문자열을 hex 배열로 변환
    private function strToHex($str){
        $hex = array();
        for($i=0; $i<strlen($str); $i++){
            $ord = ord($str[$i]);
            $hexCode = dechex($ord);
            $hex[$i] = strToUpper(substr('0'.$hexCode, -2));
        }
        return $hex;
    }
    
    //hex 배열을 문자열로 변환
    private function hexToStr($hex_arr){
        
        $str ='';
        foreach($hex_arr as $key=>$val){
            $low_hex = strtolower($val);
            $str_c= hexdec($low_hex);
            
            $str.=chr($str_c);
        }
        return $str;
    }
    
}
?>