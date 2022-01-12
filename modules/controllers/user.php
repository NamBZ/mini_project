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
				$_SESSION['permission'] = true;
				$_SESSION['id']    = $infoUser['id'];
				$_SESSION['password']   = $infoUser['password'];
				$_SESSION['username']       = $infoUser['username'];
				$_SESSION['full_name']  = $infoUser['full_name'];
				$_SESSION['level']  = $infoUser['level'];
				if (isset($_POST['saveLogin'])) {
					setcookie('permission', 1, time() + 86400, '/');
					setcookie('id', $_SESSION['id'], time() + 86400, '/');
					setcookie('username', $_SESSION['username'], time() + 86400, '/');
					setcookie('password', $infoUser['password'], time() + 86400, '/');
					setcookie('full_name', $_SESSION['full_name'], time() + 86400, '/');
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
			$data["full_name"]       = (isset($_POST['fullName'])) ? $_POST['fullName'] : '';
			$data["username"]        = (isset($_POST['username'])) ? $_POST['username'] : '';
			$data["password"]        = (isset($_POST['password'])) ? $_POST['password'] : '';
			$data["rePassword"]      = (isset($_POST['rePassword'])) ? $_POST['rePassword'] : '';
			$data["email"]           = (isset($_POST['email'])) ? $_POST['email'] : '';

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
        setcookie('permission',1,time()+86400, '/');
        setcookie('id','',time()-86400, '/');
        setcookie('username','',time()-86400, '/');
        setcookie('password','',time()-86400, '/');
        setcookie('full_name','',time()-86400, '/');
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
}
