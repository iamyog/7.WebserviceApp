<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
require APPPATH . '/libraries/REST_Controller.php';

class Employee extends REST_Controller 
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('employee_model');		
	}
    public function employees_get()
    {
		/*
			We can get id from webservice using two ways..
			1.Query Builder - I think this is not secure than segemtns cause every one can see what filed are needed..
			2.Segments = segment never shows filed name..just need actual id or name..
		*/

		//localhost/webserviceapp/index.php/employee/employees/?id=2  
        $id = $this->get('id');       
        
        //localhost/webserviceapp/index.php/employee/employees/2		 
        //$id = $this->uri->segment(3); 

        if ($id === NULL) //for both query builder and segments
        {       
        	$employees = $this->employee_model->getAllEmployees(); 	

			//if there is user in table then perform model operation here.
            if ($employees) 
            {                
                $this->response([
                	'status' =>'success',
                	'message' => $employees,                 	
                ],REST_Controller::HTTP_OK); //HTTP_OK(200) 
            }
            else
            {
                $this->response([
                    'status' => 'failed',
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) 
            }
        }
        else
        {		
        	$id = (int) $id;
	        if ($id <= 0)
	        {
	        	$this->response([
                    'status' => 'failed',
                    'message' => 'Invalid input entered.'
                ], REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
	        	$employee = $this->employee_model->getEmployee($id); 	
	        	//get the id and perform logical action and return some value.. if return is some value then check if statment
	        	if ($employee)
	        	{
		        	$this->response([
		                'status' => 'success',
		                'message' => $employee
			        ], REST_Controller::HTTP_OK);	
	        	}
	        	else 
	        	{
					$this->response([
		                'status' => 'failed',
		                'message' => 'Employee data not found'
		            ], REST_Controller::HTTP_NOT_FOUND);
	        	}				
	        }
        }
    }

    public function employees_post()
    {
        $employee = [            
            'name' => $this->post('name'),
            'des' => $this->post('des'),
            'salary' => $this->post('salary')
        ];

		$bool = $this->employee_model->addEmployee($employee); 

		if($bool)
		{
			$this->response([
        		'status' => 'success',
        		'message' => 'Record Inserted Successfuly'         		
        	],REST_Controller::HTTP_CREATED); // CREATED (201) 
		}
		else
		{
			 $this->response([
        		'status' => 'failed',
        		'message' => 'Record can not inserted'         		
        	],REST_Controller::HTTP_BAD_REQUEST);  
		}
       
    }

    public function employees_delete()
    { 
    	//$id = (int) $id; //segment

    	/*
			in postman dont sent via url or segment just sent body with x-www-form-urlendcode
        */			

    	$id = (int) $this->delete('id');

        // Validate the id.
        if ($id <= 0)
        {
        	$this->response([
                    'status' => 'failed',
                    'message' => 'Invalid input entered.'
              ], REST_Controller::HTTP_BAD_REQUEST); //BAD_REQUEST (400)
        }
        else
        {
        	$bool = $this->employee_model->deleteEmployee($id);       	

	        if($bool)
			{
				$this->response([
	        		'status' => 'success',
	        		'message' => 'Record Deleted Successfuly'         		
	        	],REST_Controller::HTTP_OK);  
			}
			else
			{
				 $this->response([
	        		'status' => 'failed',
	        		'message' => 'Record can not deleted'         		
	        	],REST_Controller::HTTP_BAD_REQUEST);  
			}	
        }        	        
    } 
    public function employees_put()
    {
        /*
			in postman dont sent via url or segment just sent body with x-www-form-urlendcode
        */

    	$id = (int) $this->put('id');

        // Validate the id.
        if ($id <= 0)
        {
        	$this->response([
                    'status' => 'failed',
                    'message' => 'Invalid input entered.'
              ], REST_Controller::HTTP_BAD_REQUEST); //BAD_REQUEST (400)
        }
        else
        {
        	$employee = [        	       
            	'name' => $this->put('name'),
            	'des' => $this->put('des'),
            	'salary' => $this->put('salary')
        	];

        	$bool = $this->employee_model->updateEmployee($id,$employee);

	        if($bool)
			{
				$this->response([
	        		'status' => 'success',
	        		'message' => 'Record Updated Successfuly'         		
	        	],REST_Controller::HTTP_OK);  
			}
			else
			{
				 $this->response([
	        		'status' => 'failed',
	        		'message' => 'Record can not updated'         		
	        	],REST_Controller::HTTP_BAD_REQUEST);  
			}	
        }        	        
    } 
}
