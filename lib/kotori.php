<?php
/**
* Kotori.php - MoeFQ SSPanel ���ο���������װ��
* @copyright 2015(c) Minami-Kotori
*/

/** 2015/8/10   ��ԭ����MySQLȫ��ʹ�� Medoo ��д����ֹSQLע�� */

class Kotori{

	var $dbhost = DB_HOST;
	var $dbuser = DB_USER;
	var $dbpass = DB_PWD;
	var $dbname = DB_DBNAME ;
	private $db;
	private $dbcontent;
	
	function __construct(){
		global $db;
		$this->db  = $db;
	}



	public function db(){
		$this->dbcontent = new mysqli($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname,3306);
		$this->dbcontent->query('SET NAMES UTF8');
		return $this->dbcontent;
	}
	

	/**
	* ��ȡ moefq_option ��ѡ��
	* @param $optionName ѡ������
	* @param $return ��������
	*/
	public function getOption($optionName,$return = 'value'){
		$query = $this->db->select("moefq_option","*",[
			"name"=>$optionName
			]);
		return $query['0'][$return];
		//$query = $this->db()->query("SELECT * FROM moefq_option WHERE name='{$optionName}' ");
		//$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
		//return $result[$return];
	}
	
	/**
	 * �� moefq_option ��д������
	 * @param $name ѡ������
	 * @param $value ѡ����ֵ
	 * @param $subvalue ѡ���ֵ
	*/
	public function newOption($name,$value,$subvalue = ''){
		$query = $this->db->insert("moefq_option",[
			"name"=>$name,
			"value"=>$value,
			"subvalue"=>$subvalue
			]);
		//$query = $this->db()->query("INSERT INTO moefq_option (`name`,`value`,`subvalue`) VALUES ('{$name}','{$value}','{$subvalue}')");
		if($query) return true; else return false;
	}
	
	/**
	 * ���� moefq_option ������
	 * @param $name ѡ������
	 * @param $value ѡ����ֵ
	 * @param $subvalue ѡ���ֵ
	 */
	public function update($name,$value,$subvalue = ''){
		//$query = $this->db()->query("UPDATE moefq_option SET `value`='{$value}',`subvalue`='{$subvalue}' WHERE `name`='{$name}'") or die(mysqli_error($this->db()));
		$query = $this->db->update("moefq_option",[
			"value"=>$value,
			"subvalue"=>$subvalue
			],[
			"name"=>$name
			]);
		if($query) return true; else return false;
	}

	 /**
	  * ���� user ������
	  * @param $name Ҫ�޸ĵ��ֶ�����
	  * @param $value �޸ĵ��ֶε�ֵ
	  * @param $uid �û���UID
	  */
	 public function updateUserInfo($name,$value,$uid){
	 	//$query = $this->db()->query("UPDATE user SET `{$name}` = '{$value}' WHERE `uid` = {$uid}");
	 	$query = $this->db->update("user",[
	 		$name=>$value
	 		],[
	 		"uid"=>$uid
	 		]);
	 	if($query) return true; else; return false;
	 }

	 /**
	  * ��ȡ user ������
	  * @param $name Ҫ��ȡ���ֶ�����
	  * @param $uid �û���UID
	  * -----------------------------
	  * ͨ��������ʽ��ȡ user ������
	  * @param $name Ҫ��ȡ���ֶ�����
	  * @param $selectName Ҫѡȡ���ֶ�����
	  * @param @selectValue Ҫѡȡ���ֶε�ֵ
	  */
	 public function kotoriNeedInfo($name,$uid){

	 	//$query = $this->db()->query("SELECT * FROM user WHERE `uid` = {$uid}");
	 	//$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
	 	$data = $this->db->select("user","*",[
	 		"uid"=>$uid,
	 		"LIMIT"=>1
	 		]);
	 	//return $result[$name];
	 	return $data['0'][$name];
	    }

	    public function kotoriFindKotori($name,$selectName,$selectValue){
	    	//$query = $this->db()->query("SELECT * FROM user WHERE `{$selectName}` = '{$selectValue}'");
	    	//$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
	    	$data = $this->db->select("user","*",[
	    		$selectName=>$selectValue,
	    		"LIMIT"=>1
	    		]);
	    	//return $result[$name];	      
	    	return $data['0'][$name];
	    }

	  /**
	   * Count ��������ظ�
	   * @param $name WHERE��������
	   * @param $value �ֶε�ֵ
	   * @param $table �ֱ�
	   */
	  public function checkRepeat($name,$value,$table = 'user'){
	  	$query = $this->db()->query("SELECT COUNT(*) AS total FROM {$table} WHERE `{$name}` = '{$value}'");
	  	$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
	  	return $result['total'];
	  }

	   /**
	    * �ڲ����� reg �ӿڵ�����»�ȡ���һ���˿�
	    * @param $role �û���ɫ
	    */
	   public function getLastPort($role){
	   	//$query = $this->db()->query("SELECT * FROM user WHERE `role` = '{$role}' ORDER BY `port` DESC LIMIT 1");
	   	//$datas = mysqli_fetch_array($query);
	   	$datas = $this->db->select("user","*",[
	   		"role"=>$role,
	   		"ORDER"=>"port DESC",
	   		"LIMIT"=>1
	   		]);
	   	return $datas['0']['port'];
	   }

        /**
         * ����һ���ظ��Ľӿڣ���SS\User\UserInfo�������ƽӿڣ�����Ϊ�˷��㣬������������
         * @param $uis �û�uid
         */
        public function getUserPermission($role){
        	switch($role){
        		case 'admin':
        		$permission = 666;
        		break;
        		case 'vip1':
        		$permission = 1;
        		break;
        		case 'vip2':
        		$permission = 2;
        		break;
        		case 'user':
        		$permission = 0;
        		break;
        		default:
        		$permission = 0;
        		break;
        	}
        	return $permission;
        }
        /**
         * payCode ���ɸ����뺯��
         * @param  int $number ���ɸ�����ĸ���
         * @param  int $size   ���ɸ��������ֵ���ȱң�
         * @param  [type] $salt  ���ɸ������õ���
         * @return boolean
         */
        public function payCode($number,$size,$salt){
        	for($time = 0; $time<$number ; $time++){
        		$rander = array();
        		$moe = '';
        		for($i=1;$i<20;$i++){
        			$rander[$i] = chr(rand(97,122));
        			$moe = $moe.$rander[$i-1];
        		}
        		$moe = $moe.$salt.$size;
        		$payCode = md5(md5($moe));
        		//$this->db()->query("INSERT INTO moefq_code (`code`,`size`) VALUES ('{$payCode}','{$size}')");
        		$this->db->insert("moefq_code",[
        			"code"=>$payCode,
        			"size"=>$size
        			]);
        	}
        	return true;
        }

      }