<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
class Employee_Model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();       	
	}

    public function getAllEmployees()
    {		
		$query = $this->db->get('employees');
		return $query->result();       
    }

    public function getEmployee($id)
    {
    	$this->db->where('id',$id);
        $query = $this->db->get('employees');
		return $query->row();  
    }

    public function deleteEmployee($id)
    {
    	$this->db->where('id',$id);
        return $this->db->delete('employees');		
    }

    public function addEmployee($data)
    {
        return $this->db->insert('employees',$data);
	}

	public function updateEmployee($id,$data)
    {
    	$this->db->where('id',$id);
		return $this->db->update('employees',$data);
	}
}
