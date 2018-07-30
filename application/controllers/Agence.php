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
            $this->global['typeAgence'] = TYPE_AGENCE;

            $this->global['pageTitle'] = 'Projet Ret : Add New User';

            $this->loadViews("agences/add", $this->global, NULL, NULL);
        }
    }

    function edit(){
        echo 'edit ';
    }
   
}
?>