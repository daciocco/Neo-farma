<?php
/**
 * Version 1.1
 * Updated: 26 Dec 2010
 */

include_once 'PHPExcel.php';

class PHPDacExcel {

	/**
	 * Converts an excel to array 
	 * @param unknown_type $filename
	 */
	static function xls2array($filename) {
		$objReader = new PHPExcel_Reader_Excel5 ();
		$objReader->setReadDataOnly ( true );
		$obj = $objReader->load ( $filename );
		$cells = $obj->getActiveSheet ()->getCellCollection ();
		$coords = array ();
		foreach ( $cells as $cell ) {
			$value = $obj->getActiveSheet ()->getCell ( $cell )->getValue ();
			$coord = PHPExcel_Cell::coordinateFromString ( $cell );
			$col = $coord [1] - 1;
			$row = PHPExcel_Cell::columnIndexFromString ( $coord [0] ) - 1;
			$coords [$col] [$row] = $value;
		}
		return $coords;
	}
	
	/**
	 * Converts an excel to array 
	 * @param unknown_type $filename
	 */
	static function csv2array($filename, $delimiter=";") {
		$result = array();
		//echo "arranca";
		if ($content = file_get_contents($filename) ){
			//echo "open";
			$rows = explode("\r\n", $content);
			foreach ($rows as $row) {
				//echo "row|";
				$cells = explode($delimiter, $row);
				if ( is_array($cells) && count($cells) > 1 ) {
					$result[] = $cells; 
				}
			}
		}
		//var_dump($result) ;
		return $result ;  	
		
	}
	
	
	/**
	 * 
	 * Converts Array to SQL INSERT statement
	 * 
	 * @param array $array
	 * @param array $columns - Column map (null to avoid certain columns)
	 * @param string $table - Name of the table to be inserted
	 * @param array $parameters - associative array of key-values
	 */
	static function array2sql($array, $columns, $table, $parameters = array()) {
		extract($parameters);// $limit, $start
		if (!isset($start)) $start = 0 ; 
		$sql = "INSERT into $table (" . implode ( ",", array_filter ( $columns, 'is_string' ) ) . ") VALUES ";
		$i = 0 ;
		foreach ( $array as $row_num => $row ) {
			$i++;
			if ( $i <= $start) continue;
			if ( isset($limit) && $i > $start + $limit) break;
			$sql .= "(";
			foreach ( $row as $col_num => $cell ) {
				if (isset($columns [$col_num])) {
					$sql .= "'$cell',";
				}
			}
			$sql = substr ( $sql, 0, - 1 );
			$sql .= "),";
		}
		$sql= substr($sql, 0, -1);
		return $sql;
	}
	
	
	
	static function xls2sql($filename,  $columns, $table, $parameters) {
		return self::array2sql(self::xls2array($filename), $columns, $table, $parameters);
	}
	
	
}