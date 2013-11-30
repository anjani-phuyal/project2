<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nepalinn extends CI_Controller {

	//Controller for Home page	
	public function index()
	{
		$data['title'] = 'Nepalinn | Home';
		$data['today'] = date('Y-m-d');
		$data['tomorrow'] = date('Y-m-d', time()+86400);
		$this->load->view('header', $data);
		$this->load->view('home',$data);
		$this->load->view('footer');
	}

	public function result()
	{
		$data['title'] = 'Nepalinn | Search Result';
		$this->load->helper('text');
		if($this->input->post()){
			$this->_update_session($this->input->post());
		}
		else if(!($this->session->userdata('searchInfo'))){
			redirect('index');
		}
		$searchInfo=$this->session->userdata('searchInfo');
		$result=$this->rooms->get_available_hotels($searchInfo['city'],$searchInfo['checkInDate'],$searchInfo['checkOutDate']);
		$result_details = array();
		foreach ($result as $aResult) {
			$id=$aResult['id'];
			$desc=$this->booking->get_hotel_details($id);
			$desc[0] = get_object_vars($desc[0]);
			$desc[0]['description']=word_limiter($desc[0]['description'],28);
			array_push($result_details, $desc[0]);
		}
		$data['searchInfo']=$searchInfo;
		$data['result']=$result_details;
		$this->load->view('header', $data);
		$this->load->view('result', $data);
		$this->load->view('footer');
	}

	private function _update_session($search_info){
		if($this->session->userdata['searchInfo']){
			$this->session->unset_userdata('searchInfo');
		}
		$this->session->set_userdata('searchInfo',$search_info);
	}

	public function details()
	{
		$data['title'] = 'Nepalinn | Search Result';
		$this->load->view('header', $data);
		$this->load->view('details');
		$this->load->view('footer');
	}

	public function test(){
		$available=$this->booking->get_hotel_details(1);
		echo $this->rooms->get_number_of_available_rooms(1,"2013-11-30","2013-12-5");
		echo "<pre>";
		print_r($available);
	}
}
