<?php
class database {
	
	var $sql = array();
	var $sessionKey = 'duplicate';
	var $results = array();
	var $timer;
	
	public function __construct(){
		$this->timer = microtime();
		$this->resetSession();
		if( !isset( $_SESSION ) ){
			exit('no session found, on line:'. __LINE__);
		}
		
	}
	
	## Query, wenn 2tes Argument "true" kann die SQL nur einmal ausgeführt werden.
	## Query, wenn 2tes Argument "false" kann die SQL mehrfach ausgeführt werden.
	public function query( $sql, $option=false ){
		# Wenn zweites Argument "true" und noch nicht ausgeführt wurde, erstellen!
		if( $option && !$this->is_duplicate( $sql ) ){
			#arrPrint(__METHOD__, 'test1', $sql, $this->is_duplicate( $sql ));
			$this->set( $sql );
			return db_query( $sql );
		}
		
		if( !$option && !$this->is_duplicate( $sql ) ){
			return db_query( $sql );
		}
		
		return false;
	}
	
	public function is_duplicate( $sql ){
		return (isset( $_SESSION[$this->sessionKey][$this->lock( $sql )] ) ? true : false );
	}
	
	public function set( $sql ){
		$_SESSION[$this->sessionKey][$this->lock( $sql )] = ( is_admin() ? $sql : 0 );
	}
	
	public function lock( $sql ){
		return md5( $sql );
	}
	
	public function log( $method, $sql, $res ){
		if( isset( $this->results[$method] ) && !is_array( $this->results[$method] ) ){ 
			$this->results[$method] = array(); 
		}
			
		$this->results[$method][] = array(
			'lockkey' => $this->lock( $sql ),
			'locked' => ( $this->is_duplicate( $sql ) ? 'Yes' : 'No' ),
			'timer' => round(microtime()-$this->timer, 2) . ' sec',
			'time' => date('H:i:s'),
			'query' => $sql,
			'result' => $res
		);
	}
	
	public function resetSession(){
		if( isset($_REQUEST['resetSession']) && is_admin() ){
			$_SESSION[$this->sessionKey] = array();
		}
	}
	
	#---------------------------------------------------------------------#
	
	public function getRow( $sql, $option=false ){
		if( $i = $this->query( $sql, $option ) ){
			$res = db_fetch_assoc( $i );
			$this->log(__METHOD__, $sql, $res);
			return $res;
		}
	}
	
	public function getRows( $sql, $option=false ){
		if( $i = $this->query( $sql, $option ) ){
			$res = array();
			while( $row = db_fetch_assoc( $i ) ){
				$res[]=$row;
			}
			
			$this->log(__METHOD__, $sql, $res);
			return $res;
		}
	}
	
	# eignet sich für selects
	public function simpleArray( $sql, $option=false ){
		if( $i = $this->query( $sql, $option ) ){
			$res = array();
			while( $row = mysqli_fetch_array( $i ) ){
				$res[$row[0]] = $row[1];
			}
			
			$this->log(__METHOD__, $sql, $res);
			return $res;
		}
	}
	
	# eignet sich für selects
	function sameKeyVal( $sql, $option=false ){
		if( $i = db_query( $sql ) ){
			$res = array();
			while( $row = mysqli_fetch_array( $i ) ){
				$res[$row[0]] = $row[0];
			}
			$this->log(__METHOD__, $sql, $res);
			return $res;
		}
	}

	function getArray( $sql ){
		if( $i = db_query( $sql ) ){
			$i = 0;
			$res = array();
			
			while( $row = db_fetch_assoc($i) ){
				foreach( $row as $key => $value ){
					$res[$key][$i] = $value;
				}
				
				$i++;
			}
			
			$this->log(__METHOD__, $sql, $res);
			return $res;
		}
	}
	
	public function result(){
		arrPrint('database', $_SESSION[$this->sessionKey], $this->results );
	}
	
}
?>