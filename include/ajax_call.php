<?php
class ajax_call {
	private $ajax_parameter;
	private $false_value = '999';
	public function __construct() {
		$this->ajax_parameter = array(
			'url'=>$this->false_value,
			'data'=>$this->false_value,
			'type'=>'GET',
			'beforeSend'=>'',
			'success'=>$this->false_value,
			'complete'=>'function(data) { 
							if(data.responseText==\'error\'){
								xhr.abort();
								alert(\'login dulu mas\');
							}
						}',
			'error'=>'function(a,b,c) { alert(a+b+c);}',
		);
	}
	function ajax_request( $params = '' ) {
		if(is_array($params)) {
			$this->_set_params( $params );
			
			//cek param
			if(in_array( $this->false_value,$this->ajax_parameter )) {
				$return = ' error ada parameter yang belum di set, yaitu: ';
				foreach($this->ajax_parameter as $option => $value) {
					if( $value == $this->false_value ) {
						$return.= $option;
						$return.= ', ';
					}
				}
				return $return;
			}
			
			//lanjut..
			$ajax_request = 'var xhr = $.ajax({'."\n";
			foreach( $this->ajax_parameter as $option => $value ) {
				if( $value=='' ) continue;
				$ajax_request.= ' '.$option.' : '.$value.','."\n";
			}
			$ajax_request.= '});';
			
			return $ajax_request;
		}
	}
	function _set_params( $params ) {
		//set param
		foreach( $this->ajax_parameter as $option => $value ){
			if(isset($params[$option])) { 
				$this->ajax_parameter[$option]=$params[$option]; 
			}
		}
	}
}

//Sengaja gk di tutup tag PHP nya