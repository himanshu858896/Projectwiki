<?php
/*This file is used for connecting mobile application to api here.
 We passing key through url path component and request through body in json format*/
require('dbconnection.php');
include('class.api.php');
	/** API CLASS OBJ**/  
	    $apiObj = new Api();	
		$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     
		$id = substr($url, strrpos($url, '/') + 1);
		$task=$id;
	    $request_body = file_get_contents('php://input');
	/**
     * Dynamically generates section
     * @param  string  key => $task
     * @param  string   value => $request_body          
     * @return string  response=> $response 
     **/
	 //check for getting data from request body
	if(isset($request_body) && !empty($request_body) ){	
	//  decode json request in @param => params
		$params = json_decode($request_body, TRUE);
		if( isset($task) && !empty($task) ) {
			//pass key and request_body to call_api_task 
			echo $response = $apiObj->call_api_task($task,$params);
		} else {
			echo $response = json_encode( array("code"=>"404","message" => "No such request found.") );
	}}
	 else {
		echo $response = json_encode( array("code"=>"400","message" => "Wrong Data.") );
	}		
?>
