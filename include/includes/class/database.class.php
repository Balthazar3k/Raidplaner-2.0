<?php
class database {
	var $sessionKey = 'duplicate';
	var $results = array();
	
	public function __construct(){
		if( isset( $_SESSION ) ){
			$_SESSION[$this->sessionKey] = array();
		}else{
			exit('no session found, on line:' __LINE__);
		}
	}
	
	public function query( $sql, $option=false ){
		# Wenn zweites Argument "true" und noch nicht ausgeführt wurde, erstellen!
		if( $option == true && !$this->is_duplicate( $sql ) ){
			$this->set( $sql );
			return db_query( $sql );
		}
		
		if( $option == false && $this->is_duplicate( $sql ) ){
			return db_query( $sql );
		}
		
		
	}
	
	public function is_duplicate( $sql ){
		if( is_array( $_SESSION[$this->sessionKey] ) ){
			return in_array( $this->lock( $sql ), $_SESSION[$this->sessionKey] );
		}else{
			exit('no session found, on line:' __LINE__);
		}
	}
	
	public function set( $sql ){
		$_SESSION[$this->sessionKey][] = $this->lock( $sql );
	}
	
	public function lock( $sql ){
		return md5( $sql );
	}
	
	public function log( $method, $sql, $res ){
		$this->results[$method] = array(
			'lockkey' => $this->lock( $sql ),
			'locked' = $this->is_duplicate( $sql ),
			'query' => $sql,
			'result' = $res
		);
	}
	
	#---------------------------------------------------------------------#
	
	public function getRow( $sql, $option=false ){
		$res = db_fetch_assoc( $this->query( $sql, $option ) );
		$this->log(__METHOD__, $sql, $res);
		return $res;
	}
	
	public function getRows( $sql, $option=false ){
	}
	
	public function simpleArray( $sql, $option=false ){
	}
	
	public function result(){
	}
	
}
?>