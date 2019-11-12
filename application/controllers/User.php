<?php 
use Restserver \Libraries\REST_Controller ; 

Class sparepart  extends REST_Controller{
    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding"); 
        parent::__construct(); 
        $this->load->model('spareparts Model'); 
        $this->load->library('form_validation'); 
    }
    
    public function index_get(){ 
        return $this->returnData($this->db->get('sparepart')->result(), false); 
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->sparepartModel->rules();
        
        if($id == null){
            array_push($rule,[
                'field' => 'created_at', 
                'label' => 'created_at', 
                'rules' => 'required'
                ],
                [
                    'field' => 'amount', 
                    'label' => 'amount', 
                    'rules' => 'required|valid_amount|is_unique[sparepart.amount]'
                ]
            );
        }
        else{
            array_push($rule,
                [
                    'field' => 'amount', 
                    'label' => 'amount', 
                    'rules' => 'required|valid_amount'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $sparepart  = new sparepartData();
        $sparepart->name = $this->post('name');
        $sparepart->amount = $this->post('amount');
        if($id == null){
            $response = $this->sparepartModel->store($sparepart);

        } else{
            $response = $this->sparepartModel->update($sparepart,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->sparepartModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

    Class sparepartData {
        public $name; 
        public $created_at; 
        public $amount;
    }