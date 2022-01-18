<?php
/**
 * 
 */
class MUser extends Model
{
	
	function __construct(){
        parent::__construct();
    }
    
    function login($data){
        
    }
	function getInfor($username, $password){
		$query = $this->db->table('users')->getAll('`username` = \''.$username.'\' AND `password` = \''.$password.'\'');
		$info = $query->fetch_array(MYSQLI_ASSOC);
		return $info;
	}

	
	function checkInfor($username){
		$query = $this->db->table('users')->getAll('`username` = \''.$username.'\'');
		$info = $query->fetch_array(MYSQLI_ASSOC);
		return $info;
	}
    
    function register($data){
        $sql    = 'INSERT INTO users SET username =\''.$data['username'].'\', password =\''.$data['password'].'\', full_name =\''.$data['full_name'].'\', email =\''.$data['email'].'\'';
        $this->db->query($sql);
    }
    
    function updateInfo($data){
    	$data_update = array(
    	    'full_name' => $data['full_name'],
    	    'email' => $data['email']
    	);
    	if (!empty($data['password'])) {
    		$data_update['password'] = $data['password'];
    	}
	    
	    return $this->db->table('users')->updateRow($data['id'], $data_update)->affected_rows >= 1;
    }


}