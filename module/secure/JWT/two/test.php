<?
require_once './Jwt_lib.php';

class cert {

    function certifiedData(){

        $jwt = new Jwt_lib();

        $rsa_data = $jwt->getKey();

        $private_key = $rsa_data['private_key'];
        $public_key = $rsa_data['public_key'];
        $servername = 'mysql56_public';
        $username = 'root';
        $password = 'rootpassword';

        $db = mysqli_connect($servername, $username, $password);

        $query = 'INSERT INTO hmat_api.user (`private_key`, `date`) VALUES ("'.$private_key.'", NOW())';

        $result = mysqli_query($db, $query);

        if($result){
            print_r('공개 키 : '.$public_key);
            echo "<br>";
            print_r('ID : '.$db->insert_id);
        }
    }

    function encryptedData($public_key, $id){
        $public_key = $_GET['public_key'];
        $id = $_GET['id'];

        $jwt = new Jwt_lib();

        $testers = array(
            'tester1' => 1000,
            'tester2' => 1001,
        );

        $data = $jwt->hashing($testers, $public_key);

        print_r($data);
    }

    function decryptedData($token, $id){

        $servername = 'mysql56_public';
        $username = 'root';
        $password = 'rootpassword';

        $db = mysqli_connect($servername, $username, $password);

        $query = 'SELECT `private_key`, `id` FROM hmat_api.user WHERE id = '.$id;

        $result = mysqli_query($db, $query);

        $row = mysqli_fetch_array($result);

        $jwt = new Jwt_lib();

        $jwt->dehashing($token, $row['private_key']);
    }
}

    if(empty($_GET['type'])){
        echo "type을 입력해주세요.";
    }else{
        $cert = new cert();

        if($_GET['type'] == 'certifiedData'){

            $cert->certifiedData();

        }else if($_GET['type'] == 'encryptedData'){

            if(empty($_GET['public_key']) || empty($_GET['id'])){
                echo "public_key와 id를 입력해주세요.";
            }else{
                $cert->encryptedData($_GET['public_key'], $_GET['id']);
            }
            
        }else if($_GET['type'] == 'decryptedData'){
            if(empty($_GET['token']) || empty($_GET['id'])){
                echo "token을 입력해주세요.";
            }else{
                $token = $_GET['token'];
                $id = $_GET['id'];

                $cert->decryptedData($token, $id);
            }
        }
    }
?>