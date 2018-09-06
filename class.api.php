<?php
/**Here in this api class we are taking decoded reponse from $response of call_api_task function 
and calling particular function on the basis of key value passed in URL**/
require('user.php');

class Api{
	   /**
     * Dynamically generates section
     * @param  array  key => $validMethods
     * @param  function   value => 'login','sync','changePassword'  
     * @return string  response=> $output 
     **/
	 // COMMON METHOD TO IDENTIFY ROUTE
       function call_api_task($task, $request) {
	   $validMethods = array('login','sync','changePassword');
		if( !in_array($task,$validMethods) ){
			return json_encode( array("code"=>"404","message" => "No such request found.") );
		} else {
			$output = self::$task($request);
			return $output;
		}		
	}
	/**Call LOGIN function if value of key is matched with login string. and pass $request to the function**/
	 /**
				 * Dynamically generates section
				 * @param  array  key => $requireParams
				 * @param  function   value => $request
				 * @param  array  fields => 'email','pass'  
				 * @return string  response=> $json_encode 
     **/
	function login($request){
		
            		
		**/
		$requireParams = array(
							'email' => 'email',
							'pass' => 'pass'
						); 
             /**create object for User class**/
             $user = new User();
			 $email=$request['email'];
			 $password=$request['pass'];
			 /**call authenticate function for login and pass email and password as request**/
			 /**
				 * Dynamically generates section
				 * @param  array  key => $authenticate
				 * @param  function   value => 'email','pass'  
				 * @return string  response=> $json_encode 
     **/
             if ($user->authenticate($email,$password)){
				 
				 /**store returned result in $result params**/
                   $result=$user->authenticate($email,$password);						
                 
				  						
									
									$permis = $result['permission'];
									
									$perm= array(     "addUser"=>$permis[0],
													  "editUser"=> $permis[1],
													  "deleteUser"=> $permis[2],
													  "addArticle"=> $permis[3],
													  "editArticle"=> $permis[4],
													  "deleteArticle"=>$permis[5],
													  "addPlatform"=> $permis[6],
													  "editPlatform"=> $permis[7],
													  "deletePlatform"=>$permis[8],
													  "addType"=> $permis[9],
													  "editType"=> $permis[10],
													  "deleteType"=>$permis[11]);
											   
                     $response = array(		
                                     'code'=>'200',				 
									'email'=>$result['email'],
									'pass' => $result['password'],
									'name' => $result['name'],
									'photo' => $result['uphoto'],
									'permission'=> $perm,
									'userrole' => $result['userrole'],
									'contributions' => $result['contributions'],
									'phone' => $result['phone'],
									'timestamp' => $result['utimestamp']
								);				   
                 return json_encode ($response);
              }  
             else{
				 return json_encode( array("code" => 702 ,"message" => "Sorry Invalid mail ID. Please enter valid ID" ));	 
			 }				 
	
}           // fetching all data
  /**
				 * Dynamically generates section
				 * @param  array  key => $sync
				 * @param  function  body => $request  
				 * @return string  response=> $json_encode 
     **/
          function sync($request){
			 
		  $requireParams = array('timestamp' => 'timestamp');
		      
			             
             
             $user = new User();
			 $timestamp=$request['timestamp'];
			 
             if ($user->all($timestamp)) {
                   $result=$user->all($timestamp);	    		   
                 return json_encode($result);
              }  
             else{
				 return json_encode( array("code" => 702 ,"message" => "Sorry For internal Problems" ));	 
			 }				 
	     
			  
		  }
		  
		 // forgot password
		   /**
				 * Dynamically generates section
				 * @param  array  key => $sync
				 * @param  function  body => $request  
				 * @return string  response=> $json_encode 
     **/
		function changePassword($request){
			
			  $requireParams = array( 'email' => 'email',
			                          'pass' => 'pass',
									  'newPass' => 'newPass');
		  
		 
		  $user = new User();
		  $email=$request['email'];
		  $password=$request['pass'];
		  $newpassword=$request['newPass'];
		  if($user->changepass($email,$password,$newpassword)){
			     return json_encode(array("code"=> "200"));
		  }
		  else{
			  return json_encode( array("code" => 702 ,"message" => "Please Enter valid details"));
}  }
   
}
?>