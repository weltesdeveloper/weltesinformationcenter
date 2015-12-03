<?php
/**
INI ADALAH FRAMEWORK MANIPULASI DATABASE YANG DIBIKIN SENDIRI
UNTUK SEMENTARA BARU MELIPUTI 
- KONEKSI KE DATABASE
- DISCONNECT DATABASE
- INSERT RECORD KE DATABASE
- UPDATE RECORD KE DATABASE
- DELETE RECORD DARI DATABASE
- MENGEKSEKUSI ALL QUERY (INSERT,UPDATE,DELETE)
- MENAMPILKAN LOG QUERY
- MENAMPILKAN STATUS KONEKSI
- MENAMPILKAN STATUS COMMIT QUERY
*/

class dbConfig
{
	static $host = null;
	static $dbName = null;
	static $user = null;
	static $password = null;
	
	private $dbh = null;
	private $arrayQuery = array();
	private $fileLogError = 'log_error_dbConfig.txt';
	private $fileLogSuccess = 'log_success_dbConfig.txt';
	private $fileLogConn = 'log_conn_dbConfig.txt';
	
	public $statusConn = null;
	public $statusCommit = null;
	public $statusQuery = null;
	
	protected function setHost($Host){
		$this->host = $Host;
	}
	protected function setDbName($DbName){
		$this->dbName = $DbName;
	}
	protected function setUser($User){
		$this->user = $User;
	}
	protected function setPassword($Pass){
		$this->password = $Pass;
	}

	public function connecting(){
		try{
			//UNTUK MYSQL
			$dns = "mysql:host=$this->host;dbname=$this->dbName";
			$this->dbh = new PDO($dns, $this->user, $this->password);
			
			//UNTUK SQLSERVER
			/*$dns = "sqlsrv:Server=$this->host;Database=$this->dbName";
			$this->dbh = new PDO($dns, $this->user, $this->password);*/
			
			$this->statusConn = $this->getDateNow();
			$this->statusConn .= "/ Koneksi Berhasil: Use Database $this->dbName \n";
			
			$this->logSuccess($this->statusConn);
			$this->logConnection($this->statusConn);
		} 
		catch(PDOException $e){
			$this->dbh = null;
			
			$this->statusConn = $this->getDateNow();
			$this->statusConn .= "/ Koneksi Gagal: ".$e->getMessage()."\n";
			
			$this->logError($this->statusConn);
			$this->logConnection($this->statusConn);
		}
	}
	
	public function disconnect(){
		$this->dbh = null;
		
		$this->statusConn = $this->getDateNow();
		$this->statusConn .= "/ Koneksi Diputus \n";
		
		$this->logConnection($this->statusConn);
		$this->logSuccess($this->statusConn);
	}
	
	public function selectFrom($tableName,$arrayField = 'ALL',$idAndValue = null,$orderBy = null){
		$arrayField = strtoupper($arrayField);
		$arrayField = ($arrayField == '*' ? 'ALL' : $arrayField);
		
		$queryString = null;
		
		$this->connecting();
		try {  
			if($arrayField == 'ALL' && $idAndValue == null){
				$queryString = "SELECT * FROM $tableNamen ";
			}
			else if($arrayField != 'ALL'  && $idAndValue == null){
				$field = "";
				
				if(is_array($arrayField)){
					foreach($arrayField as $data){
						$field .= $data .",";
					}
					$field = substr($field, 0, -1);  
				}
				else{
					$field = $arrayField;
				}
				
				$queryString = "SELECT $field FROM $tableName";
			}
			else if($arrayField == 'ALL'  && $idAndValue != null){
				$id = "";
				foreach($idAndValue as $key => $itemValue){
					$id .= $key ."='".$itemValue."' AND";
				}
				$id = substr($id, 0, -3);  
				
				$queryString = "SELECT * FROM $tableName WHERE $id";
			}
			else if($arrayField != 'ALL'  && $idAndValue != null){
				$field = "";
				
				if(is_array($arrayField)){
					foreach($arrayField as $data){
						$field .= $data .",";
					}
					$field = substr($field, 0, -1);  
				}
				else{
					$field = $arrayField;
				}
				
				$id = "";
				foreach($idAndValue as $key => $itemValue){
					$id .= $key ."='".$itemValue."' AND";
				}
				$id = substr($id, 0, -3);
				
				$queryString = "SELECT $field FROM $tableName WHERE $id";
			}
			
			if($orderBy != null){
				$dataOrder = ' ORDER BY ';
				foreach($orderBy as $data){
					$dataOrder .= $data .",";
				}
				$dataOrder = substr($dataOrder, 0, -1);  
				
				$queryString .= $dataOrder;
			}
			
			
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sth = $this->dbh->prepare($queryString);
			$sth->execute();
			
			$result = $sth->fetchAll();
		
			$this->statusQuery = $this->getDateNow();
			$this->statusQuery .= "/ Query Berhasil: $queryString \n";
			
			$this->logSuccess($this->statusQuery);
			
			$this->disconnect();
			return $result;
		} 
		catch (Exception $e) {
			$this->statusQuery = $this->getDateNow();
			$this->statusQuery .= "/ Query Gagal: ".$e->getMessage()." | QuerySyntax ($queryString)\n";
			
			$this->logError($this->statusQuery);
			
			$this->disconnect();
			return $this->statusQuery;
		}
	}	
		
	public function insert($tableName,$fieldAndValue){
		$field = "";
		$value = "";
		foreach($fieldAndValue as $key => $itemValue){
			if($itemValue == ''){
				continue;
			}
			else{
				$field .= $key.",";
				$value .= "'". $itemValue ."',";
			}
		}
		$field = substr($field, 0, -1);  
		$value = substr($value, 0, -1);  
		
		$stringQuery = "INSERT INTO $tableName ($field) VALUES ($value)";
		array_push($this->arrayQuery,$stringQuery);
	}
	
	public function update($tableName,$fieldAndValue,$idAndValue){
		$value = "";
		foreach($fieldAndValue as $key => $itemValue){
			if($itemValue == ''){
				continue;
			}
			else{
				$value .= $key ."='".$itemValue."',";
			}	
		}
		$value = substr($value, 0, -1);  
		
		$id = "";
		foreach($idAndValue as $key => $itemValue){
			$id .= $key ."='".$itemValue."' AND";
		}
		$id = substr($id, 0, -3);  
			
		$stringQuery = "UPDATE $tableName SET $value WHERE $id";
		array_push($this->arrayQuery,$stringQuery);
	}

	public function delete($tableName,$idAndValue){
		$id = "";
		foreach($idAndValue as $key => $itemValue){
			$id .= $key ."='".$itemValue."' AND";
		}
		$id = substr($id, 0, -3);  
		
		$stringQuery = "DELETE FROM $tableName WHERE $id";
		array_push($this->arrayQuery,$stringQuery);
	}
	
	public function execQuery($queryString){
		$this->connecting();
		try {  
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sth = $this->dbh->prepare($queryString);
			$sth->execute();
			
			$result = $sth->fetchAll();
			
			$this->statusQuery = $this->getDateNow();
			$this->statusQuery .= "/ Query Berhasil: $queryString \n";
			
			$this->logSuccess($this->statusQuery);
			
			$this->disconnect();
			return $result;
		} 
		catch (Exception $e) {
			$this->statusQuery = $this->getDateNow();
			$this->statusQuery .= "/ Query Gagal: ".$e->getMessage()." | QuerySyntax ($queryString)\n";
			
			$this->logError($this->statusQuery);
			
			$this->disconnect();
			return $this->statusQuery;
		}
		
	}
		
	public function commitQuery(){
		$this->connecting();
		
		try {  
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbh->beginTransaction();
			
			foreach ($this->arrayQuery as $query){
				$this->dbh->exec($query);
			}
			
			$this->dbh->commit();  
			
			$this->statusCommit = $this->getDateNow();
			$this->statusCommit .= "/ CommitQuery Berhasil: ".print_r($this->arrayQuery,true)." \n";
			
			$this->logSuccess($this->statusCommit);
			
			$this->arrayQuery = array();
			
			$this->disconnect();
			return true;
		} 
		catch (Exception $e) {
			$this->dbh->rollBack();
			
			$this->statusCommit = $this->getDateNow();
			$this->statusCommit .= "/ CommitQuery Gagal: ".$e->getMessage()." | QuerySyntax (".print_r($this->arrayQuery,true).")\n";
			$this->logError($this->statusCommit);
			
			$this->disconnect();
			return false;
		}

	}
	 
	public function logQuery(){
		$string = print_r($this->arrayQuery,true);
		return $string;
	}
	
	private function getDateNow(){
		date_default_timezone_set("Asia/Jakarta"); 
		$date = date("D / Y-m-d / H:i:s T"); 
		
		return $date;
	}
	
	private function logError($msgError){
		$file = $this->fileLogError;
		file_put_contents($file, $msgError, FILE_APPEND | LOCK_EX);
	}
	
	private function logSuccess($msgSuccess){
		$file = $this->fileLogSuccess;
		file_put_contents($file, $msgSuccess, FILE_APPEND | LOCK_EX);
	}
	
	private function logConnection($msg){
		$file = $this->fileLogConn;
		file_put_contents($file, $msg, FILE_APPEND | LOCK_EX);
	}
}
?>