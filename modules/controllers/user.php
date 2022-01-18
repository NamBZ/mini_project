<?php

/**
 * 
 */
class User extends Controller
{

	private $_listError = array();

	function __construct()
	{
		parent::__construct();
		$this->load->model('muser');
		$this->Muser  = new Muser;
		$this->data['meta'] = array(
			'title'         => 'Hệ Thống Đăng Nhập',
			'description'   => 'Hệ Thống Đăng Nhập',
			'keyword'       => 'Hệ Thống Đăng Nhập',
		);
		$this->load->header($this->data['meta']);
	}
	function __destruct()
	{
		$this->load->footer($this->data['meta']);
	}

	function index()
	{
		if (isLogin() == true) {
			show_alert(4, array('Đã đăng nhập'));
		} else {
			show_alert(4, array('Nhập Thông Tin Đăng Nhập'));
			$this->load->view('user/form-login');
		}
	}
	function login()
	{
		if (count($_POST)) {
			$data["username"]        = (isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : '';
			$data["password"]        = (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '';
			$infoUser                = $this->Muser->checkInfor($data["username"]);

			if (!empty($infoUser) && password_verify($data['password'], $infoUser['password'])) {
				show_alert(1, array('Đăng nhập thành công'));
				$_SESSION['id'] = $infoUser['id'];
				$_SESSION['password'] = $infoUser['password'];
				$_SESSION['username'] = $infoUser['username'];
				$_SESSION['full_name'] = $infoUser['full_name'];
				$_SESSION['level'] = $infoUser['level'];
				if (isset($_POST['saveLogin'])) {
					setcookie('username', $_SESSION['username'], time() + 86400, '/');
					setcookie('password', md5($infoUser['password']), time() + 86400, '/');
				}
				redirect(base_url().'/user');
			} else {
				show_alert(3, array('Tên đăng nhập không đúng'));
				$this->showForm(1);
			}
		} else {
			show_alert(4, array('Nhập Thông Tin Đăng Nhập'));
			$this->showForm(1);
		}
		
	}
	function register()
	{
		global $configs;

		

		if (!empty($_POST)) {
			$data["full_name"]       = (isset($_POST['fullName'])) ? htmlspecialchars($_POST['fullName']) : '';
			$data["username"]        = (isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : '';
			$data["password"]        = (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '';
			$data["rePassword"]      = (isset($_POST['rePassword'])) ? htmlspecialchars($_POST['rePassword']) : '';
			$data["email"]           = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';

			if ($data["full_name"] == null || mb_strlen($data["full_name"]) < 4 || mb_strlen($data["full_name"]) > 255) {
				$this->_listError[] = 'Họ và tên không hợp lệ';
			}
			$checkUser = $this->Muser->checkInfor($data["username"]);
			if ($checkUser && count($checkUser)) {
				$this->_listError[] = 'Người dùng đã tồn tại';
			}
			if ($data["username"] == null || strlen($data["username"]) < 4 || strlen($data["username"]) > 50) {
				$this->_listError[] = 'Tên đăng nhập phải dài hơn 4 kí tự';
			}
			if ($data["password"] == null || strlen($data["password"]) < 6 || strlen($data["password"]) > 100) {
				$this->_listError[] = 'Mật khẩu phải dài hơn 6 kí tự';
			}
			if ($data["rePassword"] == null || $data["rePassword"] != $data["password"]) {
				$this->_listError[] = '2 mật khẩu không khớp';
			}
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false) {
				$this->_listError[] = 'Email không hợp lệ';
			}

			if (count($this->_listError)) {
				show_alert(2, $this->_listError);
				unset($this->_listError);
				$this->showForm(2);
			} else {
				$data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
				$this->Muser->register($data);
				$recheck = $this->Muser->checkInfor($data["username"]);
				if ($recheck && count($recheck)) {
					show_alert(1, array('Đăng kí thành công'));
				}
			}
		} else {
			show_alert(4, array('Nhập Thông Tin Đăng Ký'));
			$this->showForm(2);
		}
	}
	function logout(){
        session_destroy();
        setcookie('username','',time()-86400, '/');
        setcookie('password','',time()-86400, '/');
        show_alert(1,array('Đăng xuất thành công'));
        $this->showForm(1);
		redirect(base_url().'/user/login/');
    }
	function showForm($type)
	{
		if ($type == 1) {
			$this->load->view('user/form-login');
		} else {
			$this->load->view('user/form-register');
		}
	}

	function change_info()
    {
        $infoUser  = $this->Muser->getInfor($_SESSION['username'], $_SESSION['password']);
        if(!empty($_POST))
        {
            $data['id']              =  $infoUser['id'];
            $data["full_name"]       = (isset($_POST['fullName'])) ? htmlspecialchars(trim($_POST['fullName'])) : $_SESSION['full_name'];
            $data["password_old"]    = (isset($_POST['password_old'])) ? htmlspecialchars(trim($_POST['password_old'])) : '';
            $data["password_new"]    = (isset($_POST['password_new'])) ? htmlspecialchars(trim($_POST['password_new'])) : '';
            $data["rePassword_new"]  = (isset($_POST['rePassword_new'])) ? htmlspecialchars(trim($_POST['rePassword_new'])) : '';
            $data["email"]           = (isset($_POST['email'])) ? htmlspecialchars(trim($_POST['email'])) : $infoUser["email"];
            
            if($data["full_name"] == null || mb_strlen($data["full_name"]) < 4 || mb_strlen($data["full_name"]) > 255) {
                $this->_listError[] = 'Họ và tên không hợp lệ';
            }
            if(filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false) {
                $this->_listError[] = 'Email không hợp lệ';
            }
            // Nếu đổi mật khẩu
            $isResetPassword = false;
            if (!empty($data["password_old"]) || !empty($data["password_new"]) || !empty($data["rePassword_new"])) {

	            if(!password_verify($data['password_old'], $infoUser['password'])) {
	                $this->_listError[] = 'Mật khẩu cũ không hợp lệ';
	            }
	            if(strlen($data["password_new"]) < 6 || strlen($data["password_new"]) > 100) {
	                $this->_listError[] = 'Mật khẩu mới quá ngắn';
	            }
	            if($data["rePassword_new"] == null || $data["rePassword_new"] != $data["password_new"]) {
	                $this->_listError[] = 'Mật khẩu nhập lại không khớp';
	            }
	            $isResetPassword = true;
	        }            
            if(count($this->_listError)){
                show_alert(2,$this->_listError);
                unset($this->_listError);
            }else{
            	if ($isResetPassword) {
            		$data["password"] = password_hash($data["password_new"], PASSWORD_DEFAULT);
            		$_SESSION['password'] = $data['password'];
            	}
                
                $check = $this->Muser->updateInfo($data);
                if($check){
                    show_alert(1,array('Thay đổi thông tin thành công'));
                    $infoUser  = $this->Muser->getInfor($_SESSION['username'], $_SESSION["password"]);
                    $_SESSION['id'] = $infoUser['id'];
                    $_SESSION['password'] = $infoUser['password'];
                    $_SESSION['username'] = $infoUser['username'];
                    $_SESSION['full_name'] = $infoUser['full_name'];
                    $_SESSION['level'] = $infoUser['level'];
                }
            }
            
        }
        $this->load->view('user/change_info', $infoUser);
    }
}
