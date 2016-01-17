<?php
	Class MongoigniterBase{
		protected $CI;
		
		protected $connection;
		protected $db;
		private $connection_string;

		private $host;
		private $port;
		private $user;
		private $pass;
		private $dbname;
		protected $query_safety;

		protected $selects = array();
		protected  $wheres = array();
		protected  $updates = array();

		
		public function __construct(){
			if (!class_exists('Mongo')){
			show_error("Ekstensi PECL Belum Diaktifkan", 500);
			}
			$this->CI =& get_instance();
			$this->connection_string();
			$this->connect();
		}
		
		private function connection_string(){
				$this->CI->config->load("mongoigniter");
				$this->host	= trim($this->CI->config->item('host'));
				$this->port = trim($this->CI->config->item('port'));
				$this->user = trim($this->CI->config->item('username'));
				$this->pass = trim($this->CI->config->item('password'));
				$this->dbname = trim($this->CI->config->item('db'));
				$this->query_safety = $this->CI->config->item('query_safety');
				$this->time_out=$this->CI->config->item('time_out');

				$connection_string = "mongodb://";

				if (empty($this->host)){
					show_error("Host Belum Terkoneksi Dengan Mongo DB", 500);
				}

				if (empty($this->dbname)){
					show_error("Nama Database Tidak Ditemukan", 500);
				}

				if ( ! empty($this->user) && ! empty($this->pass)){
					$connection_string .= "{$this->user}:{$this->pass}@";
				}

				if (isset($this->port) && ! empty($this->port)){
					$connection_string .= "{$this->host}:{$this->port}";
				}else{
					$connection_string .= "{$this->host}";
				}
			}
		
		//function connect untuk koneksi ke mongodb	
		public function connect(){
					$options = array();
				try{
					$this->connection = new Mongo($this->connection_string, $options);
					$this->db = $this->connection->{$this->dbname};
					return TRUE;
				}catch (MongoConnectionException $e){
					show_error("Gagal Koneksi Dikarenakan: {$e->getMessage()}", 500);
					}
		}
		
		//function untuk memilih dan atau membuatdb
		public function select_db($dbase=""){
			if(empty($dbase)){
					show_error("Database Belum Ditentukan");
				}
				$this->dbname=$dbase;
				try{
					$this->db = $this->connection->{$this->dbname};
						return TRUE;
					}catch (Exception $e){
							show_error("Tidak Dapat Mengakses: {$e->getMessage()}", 500);
						}
			}
		
		//function menghapus db	
		public function drop_db($dbase=""){
			if(empty($dbase)){
				show_error("Database Belum Ditentukan");
				}
				else{
			try{
				$this->connection->{$dbase}->drop();
				return TRUE;
			}catch (Exception $e){
					show_error("Gagal Menghapus Database `{$dbase}`: {$e->getMessage()}", 500);
					}

				}
			}
		
		//function membuat collection
		public function create_coll($collName=""){
			if(empty($collName)){
					show_error('Nama Collection Kosong',500);
				}
				else{
					$this->db = $this->connection->{$this->dbname};
					try{
						$this->db->createCollection($collName);
						return TRUE;
						}
						catch(Exception $e){
							show_error("Gagal Membuat Collection '{$collName}':{$e->getMessage()}",500 );
							}
					}
				
			}
			
			
		//function menghapus collection
		public function drop_collection($coll=""){
			if (empty($coll)){
					show_error('Gagal Hapus,Nama Collection Kosong', 500);
				}else{
			
			try{
				$this->connection->{$this->dbname}->{$coll}->drop();
				return TRUE;
			}catch (Exception $e)
			{
					show_error("Gagal Menghapus '$coll': {$e->getMessage()}", 500);
				}
			}

			return $this;
			}
		
		protected function _exception($message,$obj){
				if($obj){
						$ressult =  new stdClass();
						$ressult->has_error=TRUE;
						$ressult->error_message=$message;
					}else{
						$ressult =  array(
							"has_error"=>TRUE,
							"error_message"=>$message
						);
				}
				return $res;
			}
		
		protected function _clear(){
				$this->selects	= array();
				$this->updates	= array();
				$this->wheres	= array();
				$this->limit	= FALSE;
			}
	}
?>
