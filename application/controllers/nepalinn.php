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

	//reviews page for particular hotel
	public function reviews()
	{
		$hotel_id = intval($this->uri->segment(2));
		if(empty($hotel_id) || !is_int($hotel_id)){
			redirect('home');		
		}else{
			$data['title'] = 'Nepalinn | Reviews';
			
			$data['rating_names'] = array('Hospitality', 'Value', 'Services', 'Cleanliness', 'Dining');
			$desc=$this->booking->get_hotel_details($hotel_id);
			$data['hotelInfo']=$desc[0];
			$data['ratings'] = $this->rooms->get_all_ratings($hotel_id);
			$data['reviews_no'] = $this->rooms->get_num_of_reviews($hotel_id);
			$data['reviews'] = $this->dbase->get_Reviews($hotel_id);

			$hotel_IDs = $this->session->userdata('rated_hotel');
			if($hotel_IDs==false){
				$hotel_IDs = array();
			}
			$data['rate_style'] = '';
			if(in_array($hotel_id, $hotel_IDs)){
				$data['rate_style'] = "display:none;";
			}
			$reviewed_hotel_IDs = $this->session->userdata('reviewed_hotel');
			if($reviewed_hotel_IDs == false){
				$reviewed_hotel_IDs = array();
			}
			$data['review_style'] = '';
			if(in_array($hotel_id, $reviewed_hotel_IDs)){
				$data['review_style'] = "display:none;";
			}

			$this->load->view('header', $data);
			$this->load->view('reviews',$data);
			$this->load->view('rate_review');
			$this->load->view('footer');
		}
	}

	public function about()
	{
		$data['title'] = 'Nepalinn | About';
		$this->load->view('header', $data);
		$this->load->view('about',$data);
		$this->load->view('footer');
	}

	public function faq()
	{
		$data['title'] = 'Nepalinn | FAQ';
		$this->load->view('header', $data);
		$this->load->view('faq',$data);
		$this->load->view('footer');
	}

	public function contact()
	{
		$data['title'] = 'Nepalinn | Contact us';
		$this->load->view('header', $data);
		$this->load->view('contact',$data);
		$this->load->view('footer');
	}

	public function home(){
		$this->index();
	}

	public function thank_you()
	{
		
		$data['title'] = 'Nepalinn | Thank You';
		$this->load->view('header', $data);
		if(!(isset($_POST['StatusCode']))){
			$data['message']='Invalid Navigation';
			$this->load->view('error',$data);
		}
		else if($this->input->post('StatusCode') != 0){
			$data['message']=$this->input->post('Message');
			$this->load->view('error',$data);
		}
		else{
			$guest_det=$this->session->userdata('guest');
			$booking_det=$this->session->userdata('booking');
			$rooms=$this->session->userdata('rooms');
			$guest_id=$this->guest->add_guest($guest_det);
			$booking_det['guest_id']=$guest_id;
			$booking_id=$this->booking->add_booking($booking_det,$rooms);
			$this->load->view('thank_you',$data);
		}
		$this->load->view('footer');
	}

	public function error()
	{
		$data['title'] = 'Nepalinn | Error In Payment';
		if($this->input->post('Message'))
			$data['message'] = $this->input->post('Message');
		else
			$data['message']=5;
		$this->load->view('header', $data);
		$this->load->view('error',$data);
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
			redirect('home');
		}
		$searchInfo=$this->session->userdata('searchInfo');
		$result=$this->rooms->get_available_hotels($searchInfo['city'],$searchInfo['checkInDate'],$searchInfo['checkOutDate']);
		$result_details = array();
		foreach ($result as $aResult) {
			$id=$aResult['id'];
			$desc=$this->booking->get_hotel_details($id);
			$desc[0] = get_object_vars($desc[0]);
			$desc[0]['description']=word_limiter($desc[0]['description'],28);
			$templates=$this->rooms->get_available_templates($id,$searchInfo['checkInDate'],$searchInfo['checkOutDate']);
			$desc[0]['rate']=$this->rooms->get_start_price($templates);
			if($desc[0]['default_imgid'] != 0){
				$image_det=$this->dbase->get_Image_Details($desc[0]['default_imgid']);
				$image_det=get_object_vars($image_det[0]);
			}
			else{
				$image_det = array('path' => '', 'alt' => 'No Image');
			}
			$desc[0]['image']=$image_det;
			$desc[0]['rating']=$this->rooms->get_average_rating($id);
			$desc[0]['reviews']=$this->rooms->get_num_of_reviews($id);
			array_push($result_details, $desc[0]);
		}
		$data['searchInfo']=$searchInfo;
		$data['result']=$result_details;
		$this->load->view('header', $data);
		$this->load->view('result', $data);
		$this->load->view('footer');
	}

	private function _update_session($search_info){
		if(!(isset($search_info['city']))){
			$current_info=$this->session->userdata('searchInfo');
			$current_info['checkInDate']=$search_info['checkInDate'];
			$current_info['checkOutDate']=$search_info['checkOutDate'];
			$search_info=$current_info;
		}
		else if($this->session->userdata('searchInfo')){
			$this->session->unset_userdata('searchInfo');
		}
		$this->session->set_userdata('searchInfo',$search_info);
	}

	public function details()
	{
		if(!($this->uri->segment(2)))
			redirect ('home');
		if($this->input->post('changeDate')){
			$searchInfo=$this->input->post();
			$this->_update_session($searchInfo);
		}
		$hotel_id = $this->uri->segment(2);
		$searchInfo=$this->session->userdata('searchInfo');

		$desc=$this->booking->get_hotel_details($hotel_id);
		$desc[0] = get_object_vars($desc[0]);
		$desc[0]['rate']=$this->rooms->get_start_price($hotel_id);
		if($desc[0]['default_imgid'] != 0){
			$image_det=$this->dbase->get_Image_Details($desc[0]['default_imgid']);
			$image_det=get_object_vars($image_det[0]);
		}
		else{
			$image_det = array('path' => '', 'alt' => 'No Image');
		}
		$desc[0]['image']=$image_det;
		$data['hotelInfo']=$desc[0];
		$data['checkInDate'] = $searchInfo['checkInDate'];
		$data['checkOutDate'] = $searchInfo['checkOutDate'];
		$data['ratings'] = $this->rooms->get_all_ratings($hotel_id);
		$data['reviews'] = $this->rooms->get_num_of_reviews($hotel_id);
		$data['hotel_facilities'] = $this->booking->get_hotel_facilities($hotel_id);
		$data['available_rooms'] = $this->rooms->get_available_rooms($hotel_id, $searchInfo['checkInDate'], $searchInfo['checkOutDate']);
		$data['hotel_id'] = $hotel_id;
		$data['title'] = 'Nepalinn | Search Result';

		$data['rating_names'] = array('Hospitality', 'Value', 'Services', 'Cleanliness', 'Dining');
		$this->load->view('header', $data);
		$this->load->view('details');
		$this->load->view('rate_review');
		$this->load->view('footer');
	}

	public function test(){
		$this->load->library('email');
		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('bidur@genesesofts.com'); 
		$this->email->subject('Email Test');
		$data['title'] = 'Nepalinn | About';
		$msg=$this->load->view('header', $data, true);
		$this->email->message($msg);	
		$this->email->send();
	}

	//function to get the hash digest and return to ajax call
	public function calculate_hash(){
		if(!isset($_POST['amount'])){
			redirect('home');
		}else{
			$amount = $_POST['amount'];
			$orderDesc = $_POST['orderDesc'];
			$custName = $_POST['customerName'];
			$searchInfo=$this->session->userdata('searchInfo');
			$guest = array(
				'name' => $custName,
				'email' => $_POST['email'],
				'country' => $_POST['country'],
				'address' => $_POST['address'],
				'phone' => $_POST['phone'],
				'passport_no' => $_POST['passport']
			);

			$booking = array(
				'hotel_id' => $_POST['hotel_id'],
				'checkin_date' => $searchInfo['checkInDate'],
				'checkout_date' => $searchInfo['checkOutDate'],
				'pickup_req' => $_POST['pickup_req'],
				'pickup_place' => $_POST['pickup_place'],
				'pickup_time' => $_POST['pickup_time'],
				'status' => '1',
				'remarks' => $_POST['remarks'],
				'booking_source' => '1'
			);
			$this->session->set_userdata('guest',$guest);
			$this->session->set_userdata('booking',$booking);
			$dateTime = date('Y-m-d H:i:s P');
			$hash_digest = $this->_hash_calculator($amount, $orderDesc, $custName, $dateTime);
			echo $hash_digest.'/'.$dateTime;exit();
		}
	}



	public function _hash_calculator($amount,$orderDesc,$custName,$dateTime){
		require_once('hash_calculator.php');
		return $szHashDigest;
	}

	public function checkout()
	{
		if($this->input->post('submit')==false){
			redirect('home');
		}else{
			$data['title'] = 'Nepalinn | Checkout';
			$searchInfo=$this->session->userdata('searchInfo');
			$data['checkInDate'] = $searchInfo['checkInDate'];
			$data['checkOutDate'] = $searchInfo['checkOutDate'];
			$data['noOfDays']=(strtotime($data['checkOutDate'])-strtotime($data['checkInDate']))/86400;
			$hotel_id = $this->input->post('hotel_id');
			$room_ids = $this->input->post('room_id');
			$room_id=$room_ids;
			$room_id=explode( ",",$room_ids);
			$this->session->set_userdata('rooms',$room_id);
			$desc=$this->booking->get_hotel_details($hotel_id);
			$desc[0] = get_object_vars($desc[0]);
			$data['hotel_details']=$desc[0];
			$data['checkout_details'] = $this->dbase->get_Template_Room_Details($room_ids);
			$total=0;
			foreach ($data['checkout_details'] as $details)
				$total += $details['total'];

			$data['total']=$total;
			$data['hotel_id']=$hotel_id;
			$data['grand_tot']=$total * $data['noOfDays'];
			$data['deposit'] = 0.2 * $data['grand_tot'];
			$data['deposit_pound'] = $data['deposit'];
			$this->load->view('header', $data);
			$this->load->view('checkout');
			$this->load->view('footer');
		}
	}

	public function rating_add(){
		if(!isset($_POST['hotel_id'])){
			redirect('home');
		}else{
			$hospitality = $_POST['hospitality'];
			$value = $_POST['value'];
			$dining = $_POST['dining'];
			$cleanliness = $_POST['cleanliness'];
			$services = $_POST['services'];
			$hotel_id = $_POST['hotel_id'];
			
			$avg = ($hospitality + $value + $dining + $cleanliness + $services)/5;
			

			$data = array(
				'hotel_id' => $hotel_id,
				'friendliness' => $hospitality, 
				'services' => $services, 
				'value' => $value, 
				'cleanliness' => $cleanliness, 
				'dining' => $dining,
				'average' => $avg
			);

			$this->dbase->add_rating($data);


			$hotels = array();
			if($this->session->userdata('rated_hotel')){
				$hotels = $this->session->userdata('rated_hotel');
			}
			if($hotels == array()){
				$ids = array($hotel_id);
				$this->session->set_userdata('rated_hotel',$ids);	
			}else{
				array_push($hotels, $hotel_id);
				$this->session->unset_userdata('rated_hotel');
				$this->session->set_userdata('rated_hotel',$hotels);
			}

			echo 'Thank you for rating.';

		}
	}

	public function rating_reviews(){
		if(!isset($_POST['hotel_id'])){
			redirect('home');
		}else{
			$hotel_id = $_POST['hotel_id'];
			$ratings = $this->rooms->get_all_ratings($hotel_id);
			$num['reviews_no'] = $this->rooms->get_num_of_reviews($hotel_id);
			$last_review = $this->rooms->get_last_review($hotel_id);
			
			$review = $last_review['description'];
			
			$hotel_IDs = $this->session->userdata('rated_hotel');
			$reviewed_hotel_IDs = $this->session->userdata('reviewed_hotel');

			$details = array($ratings, $num, $last_review, $hotel_IDs, $reviewed_hotel_IDs);

			print_r(json_encode($details));exit();
		}
	}

	public function review_add(){
		if(!isset($_POST['hotel_id'])){
			redirect('home');
		}else{
			
			$dateToday = date('Y-m-d');
			$details = array(
				'review_id' => '',
				'hotel_id' => $_POST['hotel_id'],
				'name' => $_POST['name'],
				'review_txt' => $_POST['title'],
				'description' => $_POST['desc'],
				'date' => $dateToday
			);

			$this->dbase->add_review($details);

			$hotels = array();
			if($this->session->userdata('reviewed_hotel')){
				$hotels = $this->session->userdata('reviewed_hotel');
			}
			if($hotels == array()){
				$ids = array($details['hotel_id']);
				$this->session->set_userdata('reviewed_hotel',$ids);	
			}else{
				array_push($hotels, $details['hotel_id']);
				$this->session->unset_userdata('reviewed_hotel');
				$this->session->set_userdata('reviewed_hotel',$hotels);
			}

			echo 'Thank you for reviewing.';

		}
	}
}
