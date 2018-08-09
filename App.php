<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
	  public function __construct()
    {

        parent::__construct();

        if (!$this->session->userdata('username'))
        {
            
             //redirect('App/login'); //if session is not there, redirect to login page
        }   

    }
public function signup()
  {
          $data['title'] = 'Signup';
          
          $this->form_validation->set_rules('fullname',  'Full Name',   'trim|required');
          $this->form_validation->set_rules('email',      'Email',      'trim|required|is_unique[users.user_email]');
          $this->form_validation->set_rules('password',   'Password',   'trim|required');
         
   
      if ($this->form_validation->run() === FALSE ) {
         $this->session->set_flashdata("error","please Enter all fields");
      }
      else {
        $status = 'admin';
        $data = array(
        'user_Fname'   => $this->input->post('fullname'),
        'user_email' => $this->input->post('email'),
        'user_physical_address' =>  $this->input->post('city'),
        'user_country'    =>  $this->input->post('country'),
        'username'    =>  $this->input->post('username'),
        'user_gender'    =>  $this->input->post('gender'),
        'user_password'=> md5($this->input->post('password'))
        
        
      );
        $this->App_model->createAccount($data);

        redirect('App/', 'refresh');
        $this->session->set_flashdata("success","Sucessful registered you can now log in");
      }
  }
  // the login page is used to login other users
  public function login()
  {
       $data['title'] = 'Login';
      $this->form_validation->set_rules('password',  'password', 'trim|required');
    if($this->form_validation->run() === FALSE) {
      $this->session->set_flashdata("error","please Enter all fields");
       $this->load->view('app/index', $data);
     
    }
    else {
     $check_database = $this->check_database();

      //Go to private area
     if ($check_database == TRUE){
      redirect('App/newsfeed', 'refresh');
     }else{
      
        $this->load->view('app/index', $data);
     }
      
    }
  }
  // this function is called  by the login function and it checks the database to see whether the login details exist
    public function check_database() {
     
        $this->load->library('session');

        $username      = $this->input->post('username');
        $password   = $this->input->post('password');
        //query the database
        $result  = $this->App_model->details_verification($username, $password);

      if($result) {
     
          $sess_array = array(
            'id' => $result->user_id, 
            'email' => $result->user_email,
            'username' => $result->username,
            'picture' => $result->user_profile_pic,
            'password' => $result->user_password,
            'Fname' => $result->user_Fname,
            'Mname' => $result->user_Mname,
            'Lname' => $result->user_Lname

           );
        $this->session->set_userdata( $sess_array);

          return TRUE;
      } else {
         $this->session->set_flashdata("error","Invalid email or  password");
     $this->form_validation->set_message('check_database', 'Invalid email or password');
     return false;
      }
    }
    //the check_password funtion is used to check whether the password is correct 
 public function check_password() {
     
        $this->load->library('session');

        $email      = $this->session->userdata['logged_in']['email'];
        $password   = $this->input->post('password');
        //query the database
        $result  = $this->App_model->details_verification($email, $password);

      if($result) {
    

          
          return TRUE;
      } else {
     $this->form_validation->set_message('check_database', 'The current password is wrong');
     return false;
      }
    }
	
	public function index()
	{
		 $data['title'] = 'Login';
		$this->load->view('app/login_soft', $data);
	}
	
	public function dashboard()
	{
		 $data['title'] = 'Dashboard';
	   $data['picture'] = $this->session->userdata('picture');
       $data['Fname'] = $this->session->userdata('Lname');
       $data['Lname'] = $this->session->userdata('Lname');
       $data['Mname'] = $this->session->userdata('Mname');
       $data['email'] = $this->session->userdata('email');
       $data['username'] = $this->session->userdata('username');    
       $this->load->view('app/include/main_header_view', $data);  
       $this->load->view('app/include/menu_view', $data);
	  	$this->load->view('app/index', $data);
      $this->load->view('app/include/sidebar_view', $data);
	}
	public function comingsoon()
	{
		 $data['title'] = 'coming';
		 $data['picture'] = $this->session->userdata('picture');
     $data['Fname'] = $this->session->userdata('Lname');
     $data['Lname'] = $this->session->userdata('Lname');
     $data['Mname'] = $this->session->userdata('Mname');
     $data['email'] = $this->session->userdata('email');
     $data['username'] = $this->session->userdata('username');
     $this->load->view('app/include/main_header_view', $data);
     $this->load->view('app/include/menu_view', $data);
		 $this->load->view('app/page_coming_soon', $data);
     $this->load->view('app/include/sidebar_view', $data);
	}
	public function Faq()
	{
		     $data['title'] = 'Faq';
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $data['username'] = $this->session->userdata('username');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/extra_faq', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function newsfeed()
	{
		     $data['title'] = 'newsfeed';
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname') ;
         $data['Lname'] = $this->session->userdata('Lname') ;
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $data['username'] = $this->session->userdata('username');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/newsfeed_view', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function blog_page()
	{
		     $data['title'] = 'blog_page';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/page_blog_item', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function timeline()
	{
		    $data['title'] = 'Timeline';
		    $data['username'] = $this->session->userdata('username');
		    $data['picture'] = $this->session->userdata('picture');
        $data['Fname'] = $this->session->userdata('Fname');
        $data['Lname'] = $this->session->userdata('Lname') ;
        $data['Mname'] = $this->session->userdata('Mname');
        $data['email'] = $this->session->userdata('email');
        $this->load->view('app/include/main_header_view', $data);
        $this->load->view('app/include/menu_view', $data);
		    $this->load->view('app/timeline_view', $data);
        $this->load->view('app/include/sidebar_view', $data);
	}
	public function calendar()
	{
		     $data['title'] = 'Calender';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Lname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/main_menu_view', $data);
		     $this->load->view('app/calendar_view', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function notifications()
	{
		     $data['title'] = 'notifications';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/notifications_view.php', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}

	public function inbox()
	{
		     $data['title'] = 'Messages';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/inbox', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function user_profile()
	{
		     $data['title'] = 'Profile';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname') ;
         $data['email'] = $this->session->userdata('email');
         $id = $this->session->userdata('id');
         $data['user'] = $this->App_model->get_user($id );
         $this->load->view('app/include/header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/profile_view', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
	public function user_profile_settings()
	{
		     $data['title'] = 'Profile';
		     $data['username'] = $this->session->userdata('username');
		     $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $id = $this->session->userdata('id');
         $data['user'] = $this->App_model->get_user($id );
         $this->load->view('app/include/header_view', $data);
         $this->load->view('app/include/menu_view', $data);
		     $this->load->view('app/userdetails_view', $data);
         $this->load->view('app/include/sidebar_view', $data);
	}
  public function business_profile()
  {
         $data['title'] = 'business';
         $data['username'] = $this->session->userdata('username');
         $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
         $this->load->view('app/business_profile_view', $data);
         $this->load->view('app/include/sidebar_view', $data);
  }
  public function entire_profile()
  {
         $data['title'] = 'Client profile';
         $data['username'] = $this->session->userdata('username');
         $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
         $this->load->view('app/form_editable', $data);
         $this->load->view('app/include/sidebar_view', $data);
  }
  public function billing_information()
  {
         $data['title'] = 'billing information';
         $data['username'] = $this->session->userdata('username');
         $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Fname');
         $data['Lname'] = $this->session->userdata('Lname');
         $data['Mname'] = $this->session->userdata('Mname');
         $data['email'] = $this->session->userdata('email');
         $this->load->view('app/include/main_header_view', $data);
         $this->load->view('app/include/menu_view', $data);
     $this->load->view('app/form_wizard', $data);
     $this->load->view('app/include/sidebar_view', $data);
  }
  public function business_information()
  {
      $data['title'] = 'business information';
      $data['username'] = $this->session->userdata('username');
      $data['picture'] = $this->session->userdata('picture');
      $data['Fname'] = $this->session->userdata('Fname');
      $data['Lname'] = $this->session->userdata('Lname');
      $data['Mname'] = $this->session->userdata('Mname');
      $data['email'] = $this->session->userdata('email');
      $id = $this->session->userdata('id');
      $data['businesses'] = $this->App_model->list_businesses($id);
      $this->load->view('app/include/main_header_view', $data);
      $this->load->view('app/include/menu_view', $data);
      $this->load->view('app/business_information_view', $data);
      $this->load->view('app/include/sidebar_view', $data);
  }
	public function user_profile_help()
	{
		 $data['title'] = 'Help';
		 $data['username'] = $this->session->userdata('username');
		 $data['picture'] = $this->session->userdata('picture');
         $data['Fname'] = $this->session->userdata('Lname');
         $data['Lname'] = $this->session->userdata('Lname');
        $data['Mname'] = $this->session->userdata('Mname');
        $data['email'] = $this->session->userdata('email');
        $this->load->view('app/include/header_view', $data);
        $this->load->view('app/include/menu_view', $data);
		$this->load->view('app/help_view', $data);
    $this->load->view('app/include/sidebar_view', $data);
	} 
	public function tasks()
	{
		 $data['title'] = 'Tasks';
		 $data['username'] = $this->session->userdata('username');
		 $data['picture'] = $this->session->userdata('picture');
     $data['Fname'] = $this->session->userdata('Fname');
     $data['Lname'] = $this->session->userdata('Lname');
     $data['Mname'] = $this->session->userdata('Mname');
     $data['email'] = $this->session->userdata('email') ;
     $id = $this->session->userdata('id') ;
     $project_id = $this->uri->segment(3);
     $data['tasks'] = $this->App_model->list_tasks($project_id);
     $data['projects'] = $this->App_model->list_lists($id);
     $this->load->view('app/include/header_view', $data);
     $this->load->view('app/include/menu_view', $data);
		 $this->load->view('app/taskadd_view', $data);
     $this->load->view('app/include/sidebar_view', $data);
	}
  public function lists()
  {
     $data['title'] = 'Lists';
     $data['username'] = $this->session->userdata('username');
     $data['picture'] = $this->session->userdata('picture');
     $data['Fname'] = $this->session->userdata('Fname');
     $data['Lname'] = $this->session->userdata('Lname');
     $data['Mname'] = $this->session->userdata('Mname');
     $data['email'] = $this->session->userdata('email') ;
     $id = $this->session->userdata('id') ;
     $list_id = $this->uri->segment(3);
     $data['tasks'] = $this->App_model->list_tasks($list_id);
     $data['projects'] = $this->App_model->list_lists($id);
     $this->load->view('app/include/header_view', $data);
     $this->load->view('app/include/menu_view', $data);
     $this->load->view('app/listsadd_view', $data);
     $this->load->view('app/include/sidebar_view', $data);
  }
	 //this is the logout function    
public function logout()
    {
        $this->load->library('session');
        $this->session->sess_destroy();
        redirect('App');
    }
	
}
