<? 
class ControllerToolNoRest extends Controller {
	public function index() {
		
$json=array('data'=>'DISABLED');

if($this->config->get('norest_status')){
	$json['data']='AUTH REQUIRED';
	
	if(!empty($this->request->server['HTTP_X_AUTH']) && ($this->request->server['HTTP_X_AUTH']==$this->config->get('norest_key') ) ) {
	
	$json['data']=[]; 
	$allowed=explode("\n",$this->config->get('norest_allowed'));

	$json_post=json_decode(file_get_contents('php://input'), true);
	
	$api_method=false;
	$api_method=(!empty($this->request->get['method']))?$this->request->get['method']:$api_method;
	$api_method=(!empty($this->request->post['method']))?$this->request->post['method']:$api_method;
	$api_method=(!empty($json_post['method']))?$json_post['method']:$api_method;
	
	$api_data=false;
	$api_data=(!empty($this->request->get['data']))?$this->request->get['data']:$api_data;
	$api_data=(!empty($this->request->post['data']))?$this->request->post['data']:$api_data;
	$api_data=(!empty($json_post['data']))?$json_post['data']:$api_data;

		if(!empty($api_method) && in_array($api_method, $allowed)){
		
		$service_key=explode("-",$api_method);
		$service_name=$service_key[0];
		
		$service_name_keys=explode("_",$service_name);
		$service_type=array_shift ($service_name_keys);
		$service_method=$service_key[1];
		
		
		//$json['service_key']=$service_name;
		//$json['service_method']=$service_method;
	

		$service_path=implode("/",$service_name_keys);
		
		if($service_type=="model"){
			$this->load->model($service_path);
		}		
		
		if($service_type=="library"){
	    	$service_name=$service_path;
	    }
			
			
		if(!empty($api_data)){
			
			if(is_array($api_data)){
				$method_data =call_user_func_array(array($this->{$service_name},$service_method), $api_data);
			} else {
				$method_data = $this->{$service_name}->{$service_method}($api_data);
            }
             
         } else {
            $method_data= $this->{$service_name}->{$service_method}();
         }
     
     
         
          if(is_array($method_data)&&is_array(reset( $method_data))){
		  	$json['data'] = array_values((array) $method_data);
		  } else {
		  	$json['data']= $method_data; 
		  }
		

	
		} else {
			$json['data']="Method not allowed";
		}
	}
}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}