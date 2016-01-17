<?php
	if (!defined('BASEPATH'))
	exit('No direct script access allowed');
        require_once('mongoigniterbase.php');
	
    Class Mongocrud extends Mongoigniterbase{
		private $_inserted_id = FALSE;
                public $debug = FALSE;

        public function __construct() {
                parent::__construct();
        }
        
        
        
	public function insert($collection = "", $insert = array()) {
                if (empty($collection)) {
                        show_error("Tidak Ada Collection Yang Dipilih", 500);
                }

                if (count($insert) == 0) {
                        show_error("Gagal Isi Data Atau Data inputan Bukan Merupakan Array", 500);
                }
                $this->_inserted_id = FALSE;
                try {
                        $query = $this->db->selectCollection($collection)->insert($insert, array("w" => $this->query_safety));
                        if (isset($insert['_id'])) {
                                $this->_inserted_id = $insert['_id'];
                                return TRUE;
                        } else {
                                return FALSE;
                        }
                } catch (MongoException $e) {
                        show_error("Gagal Input Data: {$e->getMessage()}", 500);
                } catch (MongoCursorException $e) {
                        show_error("Gagal Input Data: {$e->getMessage()}", 500);
                }
        }
        
        private function update_init($method='')
        {
            # code...
            if ( ! isset($this->updates[$method])){
            $this->updates[$method] = array();
        }
        }
        public function update($collection="",$data=array(),$options=array())
                {
                        # code...
                        if (empty($collection)) {
                                # code...
                                show_error('Tidak Ada Collection Dipilih',500);
                        }
                        if (count($data)==0) {
                                # code...
                                show_error('Gagal Update Data,Inputan Bukan Merupakan Array',500);
                        }try{   
                                $options = array_merge(array("w"=>$this->query_safety,'multiple' => FALSE), $options);
                                $this->update_init('$set');
                                $this->updates['$set'] += $data;
                                foreach ($options as $key => $value) {
                                      # code...
                              }
                                $this->db->selectCollection($collection)->update(array($key=>$value),$this->updates,$options);
                                $this->_clear();
                                return TRUE;
                        }catch (MongoCursorException $e) {
                                show_error('Gagal Update Data:{$e->getMessage()}',500);
                        }catch(MongoCursorTimeoutException $e){
                                show_error('Gagal Update Data:{$e->getMessage()}',500);
                        }

                }
	   public function delete($collection='',$options=array())
                {
                        if (empty($collection)) {
                                show_error("Tidak Ada Collection Yang Dipilih",500);
                        }
                        try {
                              foreach ($options as $key => $value) {
                                      # code...
                              }
                              $this->db->selectCollection($collection)->remove(array($key=>$value),array($this->query_safety,$this->time_out));
                              return TRUE;
                        } catch (MongoCursorException $e) {
                                show_error('Gagal Hapus Data:{$e->getMessage()}',500);
                        }catch(MongoCursorTimeoutException $e){
                                show_error('Gagal Hapus Data:{$e->getMessage()}',500);
                        }

                }	
	public function truncate($collection = ""){
			if (empty($collection)) {
                        show_error("Tidak Ada Collection Yang Dipilih", 500);
                }
                try {
                        $this->db->selectCollection($collection)->remove();
                        return TRUE;
                } catch (MongoCursorException $e) {
                        show_error("Gagal Hapus Data: {$e->getMessage()}", 500);
                } catch (MongoCursorTimeoutException $e) {
                        show_error("Gagal Hapus Data: {$e->getMessage()}", 500);
                }
			
	       }
	}
?>
