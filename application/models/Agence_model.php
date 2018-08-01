<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Agence_model extends CI_Model
{
    private $table = 'agences';
    
    /**
     * This function is used to get the agence listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function agenceListingCount($searchText = '')
    {
        $this->db->from('agences as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.username  LIKE '%".$searchText."%'
                            OR  BaseTbl.officeID  LIKE '%".$searchText."%'
                            OR  BaseTbl.adresse  LIKE '%".$searchText."%'
                            OR  BaseTbl.registreDeCommerce  LIKE '%".$searchText."%'
                            OR  BaseTbl.raison_sociale  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    
    /**
     * This function is used to get the agence listing 
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function agencesListing($searchText = '', $page, $segment)
    {
        $this->db->from( $this->table.' as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
            OR  BaseTbl.username  LIKE '%".$searchText."%'
            OR  BaseTbl.officeID  LIKE '%".$searchText."%'
            OR  BaseTbl.adresse  LIKE '%".$searchText."%'
            OR  BaseTbl.registreDeCommerce  LIKE '%".$searchText."%'
            OR  BaseTbl.raison_sociale  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }


    /**
     * This function is used to add new agence to system
     * @return number $insert_id : This is last inserted id
     */
    function addAgence($data)
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $data);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }




     /**
     * This function is used to delete the agency information
     * @param number $userId : This is agency id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        
        return $this->db->affected_rows();
    }




}

  