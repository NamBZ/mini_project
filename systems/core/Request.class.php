<?php


/**
 * 
 */
class Request extends Controller
{
    //thuộc tính lưu trữ url truy vấn
    public $_strQuery;
    
    //thuộc tính lưu trữ controller
    private $_controller = 'post';
    
    //thuộc tính lưu trữ action
    private $_action = 'index';
    
    //thuộc tính lưu trữ param
    private $_param;
    
    //thuộc tính lưu trữ param2
    private $_param2;

    private $_Temp;

    public function __construct()
    {
        
        parent::__construct();
        
        $this->_strQuery = $_SERVER['QUERY_STRING'];
        
        $this->getModule();
        
        $this->proccessRequest();
        
    }

    //phương thức tách url thành controller, action, param1, param2
    private function getModule()
    {
        if($this->_strQuery == null)
        {
            $this->_controller = DEFAULT_MODULE; 
        }

        $this->_controller = (isset($_GET['controller'])) ? htmlspecialchars($_GET['controller']) : '';

        $this->_action     = (isset($_GET['action'])) ? htmlspecialchars($_GET['action']) : '';

        $this->_param      = (isset($_GET['param'])) ? htmlspecialchars($_GET['param']) : '';

        $this->_param2     = (isset($_GET['param2'])) ? htmlspecialchars($_GET['param2']) : '';
    } 

    //phương thức tiến hành xử lý request
    private function proccessRequest()
    {
        
        //không tồn tại controller
        if($this->_controller == null)
        {
            $this->_controller = DEFAULT_MODULE;

            $this->load->controller($this->_controller);

            $this->_Temp  = new $this->_controller;

            if(method_exists($this->_Temp, 'index'))
            {
                $this->_Temp->index();
            }
            else
            {
                show_alert(3,array('chưa tồn tại phương thức index'));
            }
         //tồn tại controller   
        }
        else
        { 
            //không tồn tại action
            if($this->_action == null)
            {
                $this->load->controller($this->_controller);
                
                if(class_exists($this->_controller))
                {
                    $this->_Temp     = new $this->_controller;

                    if(method_exists($this->_Temp, 'index'))
                    {
                        $this->_Temp->index();
                    }
                    else
                    {
                        show_alert(3,array('chưa tồn tại phương thức index'));
                    }
                }
             //tồn tại action   
            }
            else
            {
                $this->load->controller($this->_controller);

                if(class_exists($this->_controller))
                {
                    $this->_Temp     = new $this->_controller;

                    //kiểm tra sự tồn tại của action trong class
                    if(!method_exists($this->_Temp, $this->_action))
                    {
                        if(method_exists($this->_Temp, 'index'))
                        {
                            $this->_Temp->index();
                        }
                        else
                        {
                            $this->load->header();

                            show_alert(3,array('chưa tồn tại phương thức index'));

                            $this->load->footer();
                        }
                    }
                    else
                    {
                        if($this->_param2 != null)
                        {
                            $this->_Temp->{$this->_action}($this->_param, $this->_param2);
                        }
                        else
                        {
                            $this->_Temp->{$this->_action}($this->_param);
                        }

                        
                        $this->_Temp->{$this->_action}();
                        
                    }
                    
                }
                else
                {
                    $this->_controller = DEFAULT_MODULE;

                    $this->load->controller($this->_controller);

                    new $this->_controller;
                }
            }
            
        }
    }
}

new Request;