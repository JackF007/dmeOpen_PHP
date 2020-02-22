<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Home_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	// public function get_news_list($data){
	// 	$result=$this->db->get('news')->result_array();
	// 	return $result;
	// }
	public function get_category_list($data){
		$result=array();
		$result=$this->db->query("SELECT * from category where status=0 order by id asc")->result_array(); 
		return $result;
	}

	public function get_event($data){
		$result=array();
		$event_id=$data['event_id'];
		$result=$this->db->query("SELECT * from store_events where status=0 and id=$event_id ")->row_array(); 
		return $result;
	}

	public function get_sub_category_list($data){
		$result=array();
		$category_id=$data['category_id'];
		$result=$this->db->query("SELECT * from sub_category where status=0 and main_cat_id=$category_id order by id asc")->result_array(); 
		return $result;
	}

	public function get_advertisement_banner($data){
		$result=array();
		$result=$this->db->query("SELECT id,title,image from advertisement where status=0 order by id desc limit 0,10")->result_array(); 
		return $result;
	}


	public function get_offer_list_by_category($data){
		$category=$data['category_id'];
		//$type=$data['type'];
		$result=array();
		$result=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,sm.thumbnail,sm.rating  
											as rating,sm.reviews,'offer' as type,
											(so.price*(100-so.discount)/100) as discount_price 
											from store_offers as so 
											join store_master as sm on so.store_id=sm.id 
											where so.category=$category and so.status=0 
											group by so.store_id
											order by id asc")->result_array();
		// if($type=='event'){
		// $result['events']=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,
		// 									sm.thumbnail,sm.rating as rating,sm.reviews,
		// 									'event' as type 
		// 									from store_events as so 
		// 									join store_master as sm on so.store_id=sm.id 
		// 									where so.category=$category and so.status=0
		// 									group by so.store_id 
		// 									order by id asc")->result_array(); 
		// }
		// $stores=$this->db->query("SELECT * from store_master where category=$category and status=0 order by id desc limit 0,10")->result_array(); 
		// if($stores){
		// 	foreach ($stores as $store) {
		// 		$store_id=$store['id'];
		// 		$offers=$this->db->query("SELECT * from store_offers where store_id=$store_id and status=0 order by id desc limit 0,5")->result_array(); 	
		// 		$store['offers'] = $offers;
		// 		$final_stores[]=$store;
		// 	}
		// }
		return $result;
	}
	
	public function get_stores($data){
		$category=$data['category_id'];
		//$type=$data['type'];
		
		
		$this->db->select("id as store_id,name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,thumbnail,rating as rating,reviews");
		$this->db->where('category',$category);
		$this->db->order_by('id','desc');
		$result = $this->db->get('store_master')->result_array();
		
		
		//$result=$this->db->query()->result_array();
		return $result;
	}

	public function get_offer_or_event_list_by_store_id($data){
		$type=$data['type'];
		$store_id=$data['store_id'];
		$category=$data['category'];
		$sub_category=$data['sub_category'];
		$where_sub_cat='';
		$where_cat='';
		if($category){
			$where_cat =" and so.category = $category ";
		}
		if($sub_category){
			$where_sub_cat =" and so.sub_category = $sub_category ";
		}
		if($type=='offer'){
			$result['offers']=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,sm.thumbnail,sm.rating 
				as rating,sm.reviews,'offer' as type 
				from store_offers as so 
				join store_master as sm on so.store_id=sm.id 
				where 1=1 $where_cat $where_sub_cat and so.status=0 
				and so.store_id = $store_id
				order by id asc")->result_array();
		}
		if($type=='event'){
			$result['offers']=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,sm.thumbnail,sm.rating 
				as rating,sm.reviews,'offer' as type 
				from store_events as so 
				join store_master as sm on so.store_id=sm.id 
				where 1=1 $where_cat $where_sub_cat and so.status=0 
				and so.store_id = $store_id
				order by id asc")->result_array();
		}
		return $result;

	}

	public function offer_and_event_filter($data){
		$user_id = $data['user_id'];
		$sub_category='';
		if(isset($data['sub_category'])){
			$sub_category = $data['sub_category'];
		}
		$category = $data['category'];
		$sort_by = $data['sort_by'];
		$type = $data['type'];
		$where='';
	    /* order condition */
	    if($sort_by=='date'){
	    	$sort_by = 'end_date';
	    }
	    
	    if($sort_by != ""){
	        $sort_by =  " order by $sort_by desc ";
	    }
	    /* lets make a where for all result or conditional result  */
	    if($category != "" and $sub_category != "" ){
	        $where =  " where so.sub_category = $sub_category and so.category = $category  ";
	    }
	    if($category != "" and $sub_category == "" ){
	        $where =  " where so.category = $category  ";
	    }
	    //$stores =  $this->db->query("SELECT store_offers.*,store_master.name as store_name,store_master.rating as rating from store_offers join store_master on store_offers.store_id=store_master.id $where $sort_by ")->result_array();
	    if($type=='offer' or $type==''){
		    $result['offers']=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,sm.thumbnail,sm.rating 
												as rating,sm.reviews,'offer' as type 
												from store_offers as so 
												join store_master as sm on so.store_id=sm.id 
												$where and so.status=0 
												group by so.store_id
												$sort_by")->result_array();
		}
		if($type=='event')
		{
		$result['events']=$this->db->query("SELECT so.*,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,
											sm.thumbnail,sm.rating as rating,sm.reviews,
											'event' as type 
											from store_events as so 
											join store_master as sm on so.store_id=sm.id 
											$where and so.status=0
											group by so.store_id 
											$sort_by")->result_array();
		}
		if($result){
			return $result;
		}
	
    }  

    public function get_events_list($data){
		$user_id=$data['user_id'];
		$result=array();
		$result=$this->db->query("SELECT so.*,sm.id as store_id,sm.name as store_name,substring_index(location,',',1) as lattitude, substring_index(location,',',-1) as longitude,
											sm.thumbnail,sm.rating as rating,sm.reviews,
											'event' as type 
											from store_events as so 
											join store_master as sm on so.store_id=sm.id 
											where so.status=0
											group by so.store_id 
											order by id asc")->result_array(); 
		return $result;
	}

	public function get_search_stores($data){
		$where='';
		$user_id=$data['user_id'];
		$category=$data['category'];
		$lat=$data['lat'];
		$long=$data['long'];
		//$location=explode(",",$location);
		if($category){
			$where= " where FIND_IN_SET('$category',so.tags) > 0 ";
		}
		$result=array();
		if($lat!='' and $long!=''){
			$result=$this->db->query("SELECT *,ROUND(111.111 *
								    DEGREES(ACOS(COS(RADIANS($lat))
								         * COS(RADIANS(substring_index(location,',', 1)))
								         * COS(RADIANS($long-substring_index(location,',', -1)))
								         + SIN(RADIANS($lat))
								         * SIN(RADIANS(substring_index(location,',', 1))))),1) AS distance_in_km
								  FROM store_master AS a
								  join store_offers as so on so.store_id=a.id 
								   $where having distance_in_km < '20'")->result_array(); 
		}else{
			$result=$this->db->query("SELECT * FROM store_master AS a join store_offers as so on so.store_id=a.id $where ")->result_array(); 
		}
		if($result){
			foreach($result as $store){
				$bookmarked = $this->is_already_bookmarked(["user_id"=>$user_id,"store_id"=>$store['id']]);
				$store['is_bookmark']= '0';
				if($bookmarked){
					$store['is_bookmark']='1';
				}	
				$with_is_bookmark[]=$store;		
			}
			$result=$with_is_bookmark;
		}
		return $result;
	}

	public function is_already_bookmarked($data){
		$this->db->where('user_id',$data['user_id']);
		$this->db->where('store_id',$data['store_id']);
		$result = $this->db->get("bookmark")->row_array();
		if($result){
			return TRUE;
		}
		return FALSE;
	}


}