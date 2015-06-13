<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
        
        
        $this->load->helper("file");
        
		$tables = $this->db->list_tables();

        
        foreach($tables as $table){
            $fields = $this->db->field_data($table);
            
            $str = array();
            foreach($fields as $field){
                $str[] = "'{$field->name}' => '{$field->type}'";
            }

            $tempStr = implode(",\n",$str);

            $phpEntity = <<<EOT
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

\$entity = array(
{$tempStr}
);

EOT;


            write_file(APPPATH.'entity/'.$table.'.php', $phpEntity);
            
        }
	}
	
	
	

}
