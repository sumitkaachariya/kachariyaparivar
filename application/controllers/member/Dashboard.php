<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commn');
        $id = $this->session->userdata('id');
        if(empty($id)){
            redirect('user_login');
        }
    }

    public function index(){
        $this->load->view('member/header');
        $this->load->view('member/dashboard');
        $this->load->view('member/footer');
    }
    public function logout(){
        $this->session->unset_userdata('id');
        redirect('user_login');
    }
}
?>