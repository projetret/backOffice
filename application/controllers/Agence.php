<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Agence (AgenceController)
 * Agence Class to control all Agence related operations.
 * @author : Dali SOUISSI
 * @version : 0.1
 * @since : Juil 2018
 */
class Agence extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('agence_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Projet Ret : Agence';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function liste()
    {
        if($this->isAdmin() == TRUE)  return $this->loadThis();
        
        //$searchText = $this->security->xss_clean($this->input->post('searchText'));
        $searchText = "";
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        $count = $this->agence_model->agenceListingCount($searchText);
        $returns = $this->paginationCompress ( "agencesListing/", $count, $this->nbPerPage );

        $data['AgecnesRecords'] = $this->agence_model->agencesListing($searchText, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Projet Ret : Agence Listing';
        
        $this->loadViews("agences/liste", $this->global, $data, NULL); 

    }

    /**
     * This function is used to load the add new form
    */
    function add()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if(isset($_POST) && ($_POST)){
                $this->load->library('form_validation');
            
                $this->form_validation->set_rules('raison_sociale','Raison sociale','trim|required|max_length[128]|is_unique[agences.raison_sociale]');
                $this->form_validation->set_rules('officeID','Office ID','trim|required|max_length[128]');
                $this->form_validation->set_rules('typeAgence','Type Agence','trim|required');
                $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|is_unique[agences.email]');
                $this->form_validation->set_rules('username','Full Name','trim|required|max_length[128]|is_unique[agences.username]');
                $this->form_validation->set_rules('password','Password','required|max_length[20]');
                $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');

               
                if($this->form_validation->run())
                {
                    $data_storage = array(
                        'raison_sociale' => ucwords(strtolower($this->security->xss_clean($this->input->post('raison_sociale')))),
                        'officeID' => strtoupper($this->security->xss_clean($this->input->post('officeID'))),
                        'typeAgence' => $this->security->xss_clean($this->input->post('typeAgence')),
                        'email' => ucwords(strtolower($this->security->xss_clean($this->input->post('email')))),
                        'username' => ucwords(strtolower($this->security->xss_clean($this->input->post('username')))),
                        'createdBy' => $this->vendorId, 
                        'createdDtm'=>date('Y-m-d H:i:s')
                     );
                    
                    // send to module : addAgence
                    $result = $this->agence_model->addAgence($data_storage);
                    
                    if($result > 0) {
                        // une fois les données de l'agence enregistré nous allons créé le compte Admin agence
                        $data_storage = array(
                            'email' => ucwords(strtolower($this->security->xss_clean($this->input->post('email')))),
                            'name' => ucwords(strtolower($this->security->xss_clean($this->input->post('username')))),
                            'password' => getHashedPassword($this->security->xss_clean($this->input->post('password'))),
                            'roleId' => $this->agence_model->getRoleId('AdminAgence')[0]->roleId,
                            'agenceId' => $result,
                            'passwordToBeChanged' => 1,
                            'createdBy' => $this->vendorId,
                            'createdDtm'=>date('Y-m-d H:i:s')
                        );
                        $this->load->model('user_model');
                        $idUser = $this->user_model->addNewUser($data_storage);
                        if($idUser>1) { $this->session->set_flashdata('success', 'New agancy created successfully'); }
                        else { $this->session->set_flashdata('error', 'Admin agancy creation failed');}

                        redirect('agence/liste');
                    }
                    else { $this->session->set_flashdata('error', 'Agancy creation failed');}

                    
                }
            }
                        
            $this->global['typeAgence'] = TYPE_AGENCE;

            $this->global['pageTitle'] = 'Projet Ret : Add New User';

            $this->loadViews("agences/add", $this->global, NULL, NULL);
        }
    }

    function edit(){
        echo 'edit ';
    }



     /**
     * This function is used to delete the agency using id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($id = "")
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $data_storage = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->agence_model->delete($id, $data_storage);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
        return true;
    }
    


   
}
?>