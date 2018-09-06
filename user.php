<?php
/*File for connection and getting running mongodb quries and getting output and retuning it to class.api.php file */
require_once('dbconnection.php');
require_once('class.api.php');
class User
{
	
const COLLECTIONPLATFORM = 'platforms';
const COLLECTIONARTICLE = 'articles';
const COLLECTIONUSER = 'users';

private $_mongo;
private $_collectionplatform;   
private $_collectionarticle;
private $_collectionusers;
private $_user;

//variable declaration for functions
public $check,$plat,$change_pass_query,$changepass,$art,$user;
//Connection with mongodb
public function __construct()
{
$this->_mongo = DBConnection::instantiate();
$this->_collectionplatform = $this->_mongo->
getCollection(User::COLLECTIONPLATFORM);

$this->_collectionarticle = $this->_mongo->
getCollection(User::COLLECTIONARTICLE);

$this->_collectionusers = $this->_mongo->
getCollection(User::COLLECTIONUSER);
}
/* Here we are taking request from class.api.php page and passing
 it in below functions to run mongodb query and returning output for request function*/
// login function
 /**
				 * Dynamically generates section
				 * @param  request  key => $authenticate
				 * @param  String function value => $email,$password  
				 * @return string  response=> $check 
     **/
public function authenticate($email,$password)
{
//variable require to check login in mongodb
$query = array(
'email' => $email,
'password' => $password
);
//Monog Query to check login and password in mongodb
$check = $this->_collectionusers->findOne($query);

$outcheck = $check['deleted'];
// return data in check array to login function 
if($outcheck=='false'){
             if (empty($check)){
	           return False;
	           }
             else{ 
   
               return $check;
}}
 else{
	          
	         return False;
 }
}
//syncronize data function
 /**
 Here in this function we are connecting to all three collections and comparing timestamps 
				 * Dynamically generates section
				 * @param  request  key => $all
				 * @param  String  body => $timestamp  
				 * @return string  response=> $data 
	
     **/
	
	public function all($timestamp){
/*Here we are checking all three collections for the updated data after last update time in phone.*/
	$query= array(
	'ptimestamp'=> array('$gt'=>$timestamp));
	$query1= array(
	'atimestamp'=> array('$gt'=>$timestamp));
	$query2= array(
	'utimestamp'=> array('$gt'=>$timestamp));
	$plat=$this->_collectionplatform->find($query);
	$art=$this->_collectionarticle->find($query1);
	$user=$this->_collectionusers->find($query2);
	$platformdata=[];
	$articledata=[];
	$userdata=[];
	$deletedplatform=[];	
$deletedarticle=[];
$deleteduser=[];
	
	/**we are taking all documents whose records are updated in above declared arrays respectively**/
	foreach($plat as $doc){
		  $deleted=$doc['deleted'];
		 if($deleted =='false'){
	    array_push($platformdata,array('name'=>$doc['pname'],
		                               'photo'=>$doc['pphoto'],
									   'types'=>$doc['types']
		 ));
		
		 }
		 else{
			array_push($deletedplatform,array('name'=>$doc['pname']));
		 }
	} 
	
	foreach($art as $doc){
		 
		  $deleted=$doc['deleted'];
		 if($deleted =='false'){
	    array_push($articledata,array(
	
		                             'id'=>$doc['uniqueid'],
		                             'title'=> $doc['atitle'],
									 'type'=> $doc['atype'],
									 'platform'=> $doc['aplatform'],
									 'author'=> $doc['author'],
									 'photo'=> $doc['aphoto'],
									 'timestamp'=> $doc['atimestamp'],
									 'description'=> $doc['description']
		 ));}
		 else{
			array_push($deletedarticle,array('id'=>$doc['uniqueid']));
		 }
		 
		
	
	}
	
	foreach($user as $doc){
		$permis = $doc['permission'];
		  $deleted=$doc['deleted'];
		 if($deleted =='false'){
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
		array_push($userdata,array( 
		                             'email'=>$doc['email'],
									'pass' => $doc['password'],
									'name' => $doc['name'],
									'photo' => $doc['uphoto'],
									'permission'=> $perm,
									'userrole' => $doc['userrole'],
									'contributions' => $doc['contributions'],
									'phone' => $doc['phone'],
									'timestamp' => $doc['utimestamp']
		 ));}
		 else{
			array_push($deleteduser,array('email'=>$doc['email']));
		 }
		
	
	}
	
	         $response =(array('platforms'=> $platformdata,'articles'=>$articledata,'users'=>$userdata));
			 $removeData=array('platforms'=> $deletedplatform,'articles'=>$deletedarticle,'users'=>$deleteduser);
			 $data =(array("code" => "200","Lastupdated" => $timestamp,"data"=>$response,"removeData"=>$removeData));
	if (empty($response)){
	return False;
	}
else{ 
   
     return $data;
}
	

}
/* This function is working in two steps first it will verify the current password and if 
valid then it will update the new password */
 /**
				 * Dynamically generates section
				 * @param  request  key => $changepass
				 * @param  function  body => $email,$password,newpassword
				 * @return string  response=> Boolean 
     **/
public function changepass($email,$password,$newpassword)
{
          $query = array(
              'email' => $email,
              'password' => $password
               );
               $changepass = $this->_collectionusers->findOne($query);  

if (empty($changepass)){
	return False;
	}
else{ 
        $condition = array("email"=>$email);
       
        $newpass = array('$set'=>array("password"=>md5($newpassword)));
		$change_pass_query = $this->_collectionusers->update($condition,$newpass);
        return True;
}
}

}
