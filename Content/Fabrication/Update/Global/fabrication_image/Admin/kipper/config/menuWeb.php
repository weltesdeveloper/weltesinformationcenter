<?php
require_once('dbWeb.php');

$con = new dbWeb();


class menuWeb extends dbWeb
{
	function __construct() {
		parent::__construct();
	}
	
	private function arrayToObject($array){
		foreach( $array as $key => $value ){
			if( is_array( $value ) ) $array[ $key ] = $this->arrayToObject( $value );
		}
		return (object) $array;
	}
	
	private function orderMenuKiri($dataMenu,$parent = 0,$depthLevel = 1){
		$html = null;
		
		if(isset($dataMenu[$parent])){
			$html .= "<ul>";
			
			foreach($dataMenu[$parent] as $data){
				$html .= "<li>";
				
				
				if($depthLevel == 1){
					$html .= "<a href='$data->url'><i class='$data->icon fa-lg fa-fw'></i> $data->nama_menu </a>";
				}
				else if($depthLevel == 2){
					$html .= "<a href='$data->url'> $data->nama_menu </a>";
				}
				else{
					$html .= "<a href='$data->url'><i class='$data->icon fa-fw'></i> $data->nama_menu </a>";
				}
				
				
				
				$htmlChild = $this->orderMenuKiri($dataMenu,$data->id_menu,$data->sub_level+1);
				if(isset($htmlChild)){
					$html .= $htmlChild;
				}
				
				$html .= "</li>";
			}
			
			
			$html .= "</ul>";
		}
		else{
			return $html;
		}
		
		return $html;
	}
	
	private function orderMenuKanan($dataMenu,$parent = 0,$depthLevel = 1){
		$html = null;
		
		if(isset($dataMenu[$parent])){
			
			if($depthLevel == 1){
				$html .= "<ul class='header-dropdown-list hidden-xs'>";
			}
			else{
				$html .= "<ul class='dropdown-menu pull-right'>";
			}
			
			
			foreach($dataMenu[$parent] as $data){
				$html .= "<li>";
				
				
				if($depthLevel == 1){
					$html .= "<a href='$data->url' class='dropdown-toogle' data-toggle='dropdown'><i class='$data->icon'></i> <span> $data->nama_menu </span> <i class='fa fa-angle-down'></i> </a>";
				}
				else{
					$html .= "<a href='$data->url'><i class='$data->icon'></i> $data->nama_menu  </a>";
				}
				
				
				
				$htmlChild = $this->orderMenuKanan($dataMenu,$data->id_menu,$data->sub_level+1);
				if(isset($htmlChild)){
					$html .= $htmlChild;
				}
				
				$html .= "</li>";
			}
			
			
			$html .= "</ul>";
		}
		else{
			return $html;
		}
		
		return $html;
	}
	
	public function setMenuKiri(){
		$whereValue = array(
			'letak_menu' => 'kiri',
		);
		
		$orderBy = array(
			'id_parent','urutan_menu'
		);
		
		$arrMenuKiri = parent::selectFrom('w_menu','ALL',$whereValue,$orderBy);
		$objMenuKiri = $this->arrayToObject($arrMenuKiri);
		
		$data = array();
		
		foreach($objMenuKiri as $menuKiri){
			$data[$menuKiri->id_parent][] = $menuKiri;
		}
		
		$htmlMenuKiri = $this->orderMenuKiri($data);
		
		return $htmlMenuKiri;
	}
	
	public function setMenuKanan(){
		$whereValue = array(
			'letak_menu' => 'kanan',
		);
		$arrMenuKanan = parent::selectFrom('w_menu','ALL',$whereValue);
		$objMenuKanan = $this->arrayToObject($arrMenuKanan);
		
		$data = array();
		
		foreach($objMenuKanan as $menuKanan){
			$data[$menuKanan->id_parent][] = $menuKanan;
		}
		
		$htmlMenuKanan = $this->orderMenuKanan($data);
		
		return $htmlMenuKanan;
	}
}

?>