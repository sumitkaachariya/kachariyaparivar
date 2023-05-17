<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends CI_Controller {
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
        $current_user = $this->session->userdata('id');
        $Common =  new Commn();
        $data['subscriptions'] = $Common->where_all_records('user_membership_plan', array('user_id' => $current_user),'*');

        $this->load->view('member/header');
        $this->load->view('member/subscription',$data);
        $this->load->view('member/footer');
    }

    public function newsubscription(){
        $data = array();
        $Common =  new Commn();
        $mobileno = $this->input->get('mobileno');
        $user =  $Common->select_get_row_data('user_membership_plan',array('mobileno' => $mobileno),'*');
        $data['type_pay_list'] =  $Common->all_records('type_pay_list','*');
        
        if($user){
            $user_id = $user->id;
            $data['user'] = $user;
            $data['subscriptions'] = $Common->where_all_records('plan_member', array('member_user' => $user_id),'*');
        }
        $this->load->view('member/header');
        $this->load->view('member/newsubscription',$data);
        $this->load->view('member/footer');
    }

    public function submit_subscription(){
        
        $Common =  new Commn();
        $current_user = $this->session->userdata('id'); 
        $member_user=  get_field('user_membership_plan',array('mobileno' => $this->input->post('mobileno')),'*');

        
        if(empty($member_user)){
            $membership_plan = array(
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'mobileno' => $this->input->post('mobile_no'),
                'gam' => $this->input->post('gam'),
                'edu_no_of_child' => $this->input->post('no_of_child_std'),
                'no_of_result' => $this->input->post('submit_result'),
                'pay_of_notebook' => $this->input->post('notebook'),
                'no_of_home_person' => $this->input->post('total_member'),
                'year' => date('Y'),
                'user_id' => $current_user,
            );

            $user_membership_plan_data = $Common->insert_data('user_membership_plan', $membership_plan);
            $last_member_user_id =  $this->db->insert_id();
            $pay_list = $this->input->post('pay_list[]');
            $sabhylist = $this->input->post('sabhy[]');

            $status = 1;
            if(isset($pay_list)){
                foreach ($pay_list as $key => $list) {
                    if($list != ''){
                        if($key == 'varshik_lavajam'){
                            $key = 'વાર્ષિક લવાજમ';
                        }
                        if($key == 'danbhet'){
                            $key = 'દાનભેટ';
                        }
                        if($key == 'notebook'){
                            $key = 'નોટબુક';
                        }
                        if($key == 'any'){
                            $key = 'અન્ય';
                        }

                        $pay_id = get_field('type_pay_list',array('name' => $key),'id');
                        $table = 'plan_member';
                        $data = array(
                            'type_pay' => $pay_id->id,
                            'total_amount' => $list,
                            'member_user' => $last_member_user_id,
                            'user_id ' => $current_user,
                            'no_of_entry' => 1,
                            'year' => date('Y'),
                        );
                        $plan_member = $Common->insert_data('plan_member', $data);
                        $status = 1;
                    }
                }
            }else{
                $status = 1;
            }
            if(isset($sabhylist)){
                foreach ($sabhylist as $key => $sabhy) {
                    if($sabhy != ''){
                       
                        $data = array(
                            'member_name' => $sabhy['name'],
                            'member_edu' => $sabhy['edu'],
                            'member_user' => $last_member_user_id,
                            'user_id' => $current_user
                        );
                        $plan_member = $Common->insert_data('member_of_user_home', $data);
                        $status = 1;
                    }
                }
            }else{
                $status = 1;
            }
            if($status == 1){
                echo json_encode(array('status' => 200,'response'=>'SuccessFully Add Memebrship'));
            }
        }else{
            echo json_encode(array('status' => 400,'response'=>'Already added member'));
        }
    }

    public function update_subscription(){
        $Common =  new Commn();
        $current_user = $this->session->userdata('id'); 
        $member_user=  get_field('user_membership_plan',array('mobileno' => $this->input->post('mobileno')),'*');
        $pay_list = $this->input->post('pay_list[]');
        $status = 0;
            if(isset($pay_list)){
                foreach ($pay_list as $key => $list) {
                    if($list != ''){
                        $pay_id = get_field('type_pay_list',array('name' => $key),'id');
                        $table = 'plan_member';
                        $data = array(
                            'type_pay' => $pay_id->id,
                            'total_amount' => $list,
                            'member_user' => $member_user->id,
                            'user_id ' => $current_user,
                            'no_of_entry' => 2,
                            'year' => date('Y'),
                        );
                        $plan_member = $Common->insert_data('plan_member', $data);
                        $status = 1;
                    }
                }
            }
            if($status == 1){
                echo json_encode(array('status' => 200,'response'=>'SuccessFully Add Memebrship'));
            }

    }

    public function printview(){
        $Common =  new Commn();

        $mobile_no = $this->input->get('mobileno');

        $data['member_user']=  get_field('user_membership_plan',array('mobileno' => $mobile_no),'*');

        $data['list_membership'] = $Common->where_all_records('plan_member', array('member_user' => $data['member_user']->id),'*');
        
        $data['provided_user']=  get_field('users',array('id' =>  $data['member_user']->user_id),'*');

        $amount = array(
            'varshik_lavajam' => 0,
            'danbhet' => 0,
            'notebook' => 0,
            'other' => 0,
        );
        if(isset($data['list_membership'])){
            foreach ($data['list_membership'] as $key => $list_membership) {
                if($list_membership->type_pay == 1){
                    $amount['varshik_lavajam'] = ($amount['varshik_lavajam'] + $list_membership->total_amount);
                }
                if($list_membership->type_pay == 2){
                    $amount['danbhet'] = ($amount['danbhet'] + $list_membership->total_amount);
                }
                if($list_membership->type_pay == 3){
                    $amount['notebook'] = ($amount['notebook'] + $list_membership->total_amount);
                }
                if($list_membership->type_pay == 4){
                    $amount['other'] = ($amount['other'] + $list_membership->total_amount);
                }
            }
        }
        $data['amount'] = $amount;
        $this->load->view('member/printview',$data);
        // Get output html
        $html = $this->output->get_output();

        // Load pdf library
        $this->load->library('pdf');
        
        $dompdf = new Pdf('UTF-8');

        $this->dompdf->set_option('isHtml5ParserEnabled', true);
        $this->dompdf->set_option('isRemoteEnabled', true);   

        // Load HTML content
        $this->dompdf->loadHtml($html, 'UTF-8');
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        $file_name = $data['member_user']->name.'.pdf';
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($file_name, array("Attachment"=>1));

    }
}
?>