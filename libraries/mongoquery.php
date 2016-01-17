<?php
	if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    require_once('mongoigniterbase.php');
	
	/**
	* 
	*/
	class Mongoquery extends Mongoigniterbase
	{
		
		function __construct()
		{
			# code...
			 parent::__construct();
		}

		public function get($collection='',$option=array())
		{
			# code...
			if(empty($collection)){
				show_error('Nama Collection Harus Diisi');
			}
				$value=$this->db->selectCollection($collection)->find($option);
			return $value;
		}

		public function get_where($collection='',$value=array())
		{
			# code...
			
			$value=$this->db->selectCollection($collection)->findOne($value);
			return $value;
		}

		public function select($collection='',$limit=FALSE)
		{
			# code...
			if(empty($collection)){
				show_error('Nama Collection Harus Diisi');
			}
			if($limit==TRUE||is_numeric($limit)){
				$value=$this->db->selectCollection($collection)->find()->limit($limit);
			}else{
				$value=$this->db->selectCollection($collection)->find();
			}
			return $value;
		}



        public function order_by($collection='',$option=array(),$limit=FALSE)
        {
        	# code...
        	$cursor=$this->db->selectCollection($collection)->find()->limit($limit);
        	foreach ($option as $key => $value) {
        		# code...
        	}
        	if(strtolower($value)=='asc'){
        		$order=$cursor->sort(array($key=>1));
        	}elseif (strtolower($value)=='desc') {
        		# code...
        		$order=$cursor->sort(array($key=>-1));
        	}
           	return $order;
        }

        public function count($collection='',$option=array())
        {
        	# code...
        	if(empty($collection)){
        		show_error('Nama Collection Harus Diisi');
        	}
        	if(!empty($option)||is_array($option)){

        		foreach ($option as $key => $value) {
        			# code...

               		$result=$this->db->selectCollection($collection)->find(array($key=>$value))->count();
               	}
               	return $result;
               }
        }

        public function count_all($collection=''){
        	$result=$this->db->selectCollection($collection)->find()->count();
        	return $result;
        }
	}
?>