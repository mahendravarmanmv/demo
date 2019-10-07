<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardcontroller extends CI_Controller {
	
	public function __construct()
        {
                parent::__construct();
                $this->load->model('Usermodel');
        }

	public function index()
	{
		$data['getproducts'] = $this->Usermodel->get_products("products");
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/indexpage',$data);
		$this->load->view('commonfiles/footer');
	}
	
	public function createproducts($id="")
	{
		if($this->input->post('submitproducts'))
		{
		$this->creatingitem();	
		}
		else
		{
		$data['specificpro'] = array();
		if($id!="")
		{
		$data['specificpro'] = $this->Usermodel->get_specificdata("products",array('id' => $id));	
		}
		$data['categories'] = $this->Usermodel->get_entries("categories",array('status' => 0));
		$data['units'] = $this->Usermodel->get_entries("units",array());
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_products',$data);
		$this->load->view('commonfiles/footer');
		}
	}
	
	public function createcategories($id="")
	{
		if($this->input->post('submitcategories'))
		{
		$this->creatingcategory();	
		}
		else
		{
		$data['specificcat'] = array();
		if($id!="")
		{
		$data['specificcat'] = $this->Usermodel->get_specificdata("categories",array('id' => $id));	
		}
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_categories',$data);
		$this->load->view('commonfiles/footer');
		}
	}
	
	public function createvendors($id="")
	{
		if($this->input->post('submitvendors'))
		{
		$this->creatingvendor();	
		}
		else
		{
		$data['specificvendor'] = array();
		if($id!="")
		{
		$data['specificvendor'] = $this->Usermodel->get_specificdata("vendors",array('id' => $id));	
		}
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_vendors',$data);
		$this->load->view('commonfiles/footer');
		}
	}
	
	public function creategodowns($id="")
	{
		if($this->input->post('submitgodowns'))
		{
		$this->creatinggodown();	
		}
		else
		{
		$data['specificgodown'] = array();
		if($id!="")
		{
		$data['specificgodown'] = $this->Usermodel->get_specificdata("godowns",array('id' => $id));	
		}
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_godowns',$data);
		$this->load->view('commonfiles/footer');
		}
	}
	
	public function creatinggodown()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('godownname', 'Godown', 'trim|required|xss_clean');
		$this->form_validation->set_rules('area', 'Area', 'trim|required|xss_clean');
		$this->form_validation->set_rules('contactperson', 'Contact person', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|required|numeric|min_length[10]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
                {
        $this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_godowns');
		$this->load->view('commonfiles/footer');
                }
                else
                {
				
				$data=array(
					'name'=>$this->input->post('godownname'),
					'area'=>$this->input->post('area'),
					'contact_person'=>$this->input->post('contactperson'),
					'phonenumber'=>$this->input->post('phonenumber'),
                    );
					
				$query=$this->Usermodel->insert_godowns($data);
                	
				if($query)
				{
					redirect(base_url()."tb/godowns","refresh");
				}
                
                }
	}
	
	public function creatingvendor()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('vendorname', 'Vendor', 'trim|required|xss_clean');
		$this->form_validation->set_rules('contactno', 'Contact number', 'trim|required|numeric|min_length[10]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
                {
        $this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_vendors');
		$this->load->view('commonfiles/footer');
                }
                else
                {
				
				$data=array(
					'name'=>$this->input->post('vendorname'),
					'contactnumber'=>$this->input->post('contactno'),
					'address'=>$this->input->post('address'),
                    );
					
				$query=$this->Usermodel->insert_vendors($data);
                	
				if($query)
				{
					redirect(base_url()."tb/vendors","refresh");
				}
                
                }
	}
	
	public function creatingcategory()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categoryname', 'Category', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
                {
        $this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_categories');
		$this->load->view('commonfiles/footer');
                }
                else
                {
				
				$data=array(
					'name'=>$this->input->post('categoryname'),
                    );
					
				$query=$this->Usermodel->insert_categories($data);
                	
				if($query)
				{
					redirect(base_url()."tb/categories","refresh");
				}
                
                }
	}
	
	public function enablecat($id)
	{
	if($id!="")
	{
	$data=array(
		'status'=> "0",
               );
	    $this->catdisable($data,$id);
		
	}
	}
	
	public function discat($id)
	{
	if($id!="")
	{
	$data=array(
		'status'=> "1",
               );
	$data1=array(
		'category'=> "1",
               );
	$query=$this->Usermodel->uncategorize($data1,$id);
	if($query==1)
	{
	$this->catdisable($data,$id);
	}
		
	}
	}
	
	public function catdisable($data,$id)
	{
	
	
		$query=$this->Usermodel->disablecat($data,$id);
		if($query)
		{
		redirect(base_url()."tb/categories","refresh");
		}
		
	}
	
	public function delcat($id)
	{
		if($id!="")
		{
		$query=$this->Usermodel->deldata($id,"categories");
		if($query==1)
		{
		redirect(base_url()."tb/categories","refresh");
		}
		}
		
	}
	
	public function delproduct($id)
	{
		if($id!="")
		{
		$query=$this->Usermodel->deldata($id,"products");
		if($query==1)
		{
		redirect(base_url()."tb/products","refresh");
		}
		}
		
	}
	public function delvendor($id)
	{
		if($id!="")
		{
		$query=$this->Usermodel->deldata($id,"vendors");
		if($query==1)
		{
		redirect(base_url()."tb/vendors","refresh");
		}
		}
		
	}
	public function delgodown($id)
	{
		if($id!="")
		{
		$query=$this->Usermodel->deldata($id,"godowns");
		if($query==1)
		{
		redirect(base_url()."tb/godowns","refresh");
		}
		}
		
	}
	

	
	public function creatingitem()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('itemname', 'itemname', 'trim|required|xss_clean');
        $this->form_validation->set_rules('category', 'category', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pprice', 'Purchase price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dateofpurchase', 'dateofpurchase', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
                {
        $this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/create_products');
		$this->load->view('commonfiles/footer');
                }
                else
                {
				$expd = explode("/",$this->input->post('dateofpurchase'));
				$data=array(
					'itemname'=>$this->input->post('itemname'),
					//'image' =>$this->input->post('instrument_used'),
					'unit' =>$this->input->post('units'),
					'category' =>$this->input->post('category'),
					'purchaseprice' =>$this->input->post('pprice'),
					'dateofpurchase' =>$expd[2].'-'.$expd[1].'-'.$expd[0],
                    );
					
				$query=$this->Usermodel->insert_products($data);
                	
				if($query)
				{
					redirect(base_url()."tb/products","refresh");
				}
                
                }
	}
	
	public function categorieslist()
	{
		$data['getcategories'] = $this->Usermodel->get_entries("categories",array());
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/categories_list',$data);
		$this->load->view('commonfiles/footer');
	}
	
	public function vendorslist()
	{
		$data['getvendors'] = $this->Usermodel->get_entries("vendors",array());
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/vendors_list',$data);
		$this->load->view('commonfiles/footer');
	}
	
	public function godownslist()
	{
		$data['getgodowns'] = $this->Usermodel->get_entries("godowns",array());
		$this->load->view('commonfiles/header');
		$this->load->view('dashboardpages/godowns_list',$data);
		$this->load->view('commonfiles/footer');
	}
	
	
	public function logout()
	{
		redirect(base_url()."login",'refresh');
	}
	
}
