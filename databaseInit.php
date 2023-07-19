<?php
	class databaseInit{
		function __construct(){
			/*
			$this->name = 'xs821075_hati';
			$this->pass = 'mmoott435';
			$this->dbname = 'xs821075_notnormal';
			*/
			$this->name = 'pma';
			$this->pass = '';
			$this->dbname = 'hattatu';
			
			$this->host = 'localhost';
			$this->host = "mysql:host={$this->host};dbname={$this->dbname}";
			try {
			    $db = new PDO($this->host, $this->name, $this->pass);
			 	$this->db = $db;
			    // echo "接続成功\n";
			} catch (PDOException $e) {
			    echo "接続失敗: " . $e->getMessage() . "\n";
			    //exit();
			}
		}
	}
?>
