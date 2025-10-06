<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/BaseController.php';

/**
 * Class : Admin_users (Admin_usersController)
 * Admin Users Class to control all admin users related operations
 */
class Admin_users extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('user_model');
        $this->load->library('form_validation');
    }

    /**
     * This function is used to load the admin users listing page
     */
    public function index()
    {
        $data['title'] = 'Admin Users Listing';
        $data['userRecords'] = $this->user_model->userListing();
        $this->load->template('admin/listing', $data);
    }

    /**
     * This function is used to load the add new admin form
     */
    public function new_admin()
    {
        $data['title'] = 'Add New Admin';
        $this->load->view('admin/addEdit', $data);
    }

    /**
     * This function is used to add new admin to the system
     */
    public function save_new_admin()
    {
        $this->form_validation->set_rules('fullname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->new_admin();
        }
        else
        {
            $fullname = ucwords(strtolower($this->security->xss_clean($this->input->post('fullname'))));
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('password');
            
            $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'fullname'=>$fullname, 'createdDtm'=>date('Y-m-d H:i:s'));
            
            $this->user_model->addNewAdmin($userInfo);
            
            if($this->user_model->status == "ok")
            {
                $this->session->set_flashdata('success', $this->user_model->message);
            }
            else
            {
                $this->session->set_flashdata('error', $this->user_model->message);
            }
            
            redirect('admin_users');
        }
    }

    /**
     * This function is used to load the edit admin form
     */
    public function edit_admin($id = NULL)
    {
        if($id == null)
        {
            redirect('admin_users');
        }
        
        $data['title'] = 'Edit Admin';
        $data['userInfo'] = $this->user_model->getAdminInfo($id);
        
        $this->load->template('admin/addEdit', $data);
    }

    /**
     * This function is used to update the admin information
     */
    public function update_admin()
    {
        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('fullname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->edit_admin($id);
        }
        else
        {
            $fullname = ucwords(strtolower($this->security->xss_clean($this->input->post('fullname'))));
            $email = $this->security->xss_clean($this->input->post('email'));
            
            $userInfo = array();
            
            if(empty($this->input->post('password')))
            {
                $userInfo = array('email'=>$email, 'fullname'=>$fullname, 'updatedDtm'=>date('Y-m-d H:i:s'));
            }
            else
            {
                $password = $this->input->post('password');
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'fullname'=>$fullname, 'updatedDtm'=>date('Y-m-d H:i:s'));
            }
            
            $this->user_model->editAdmin($userInfo, $id);
            
            if($this->user_model->status == "ok")
            {
                $this->session->set_flashdata('success', $this->user_model->message);
            }
            else
            {
                $this->session->set_flashdata('error', $this->user_model->message);
            }
            
            redirect('admin_users/edit_admin/'.$id);
        }
    }

    /**
     * This function is used to delete the admin
     */
    public function delete_admin($id = NULL)
    {
        if($id == null)
        {
            redirect('admin_users');
        }
        
        $this->user_model->deleteAdmin($id);
        
        if($this->user_model->status == "ok")
        {
            $this->session->set_flashdata('success', $this->user_model->message);
        }
        else
        {
            $this->session->set_flashdata('error', $this->user_model->message);
        }
        
        redirect('admin_users');
    }
}

?>