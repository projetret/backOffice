<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Agence_model extends CI_Model
{
    /**
     * This function is used to get the agence listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function agenceListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
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
        $this->db->from('agences as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.raison_sociale  LIKE '%".$searchText."%'
                            OR  BaseTbl.officeID  LIKE '%".$searchText."%'
                            OR  BaseTbl.created_at  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

}

  