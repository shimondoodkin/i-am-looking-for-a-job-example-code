<?php
 /**
  * Tokenizer API
  *
  * it accepts both POST and GET http requests
  * tokenizer-api.php lets use Tokenizer class through REST API
  *
  * @author Shimon Doodkin <helpmepro1@gmail.com>
  *
  *
  * example:
  *
  * url:
  *
  * tokenizer-api.php?config=generic&query=gap%20woman%20blue%20white%20polka%20dot%20dress
  *
  * result:
  *   
  * {
  *   "status": "success",
  *   "original": "gap woman blue white polka dot dress",
  *   "keywords": "dress",
  *   "properties": {
  *     "color": [
  *       "blue",
  *       "white"
  *     ],
  *     "gender": [
  *       "woman"
  *     ],
  *     "pattern": [
  *       "polka dot"
  *     ],
  *     "brand": [
  *       "gap"
  *     ]
  *   }
  * }
  *
  * @param string config querystring parameter, it loads configuration file from current directory with filename like: tokenizer-YourConfigNameHere.json
  * @param string query  querystring parameter, the query to parse
  *
  * @return void
  */

    while (ob_get_level() > 0)ob_end_clean();//clean any automatic buffers. to emit headers right away
	
	
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Content-Type');
			
	/*
	switch ($_SERVER['HTTP_ORIGIN'])
	{
		case 'http://originating-domain.com':
		case 'https://originating-domain.com':
			header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
			header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			header('Access-Control-Max-Age: 1000');
			header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		break;
	}
	*/

	ob_start(); //hide any 
	header("Content-type: application/json; charset=utf-8");
	
	include "tokenizer.class.php";

	/**
	* error
	*
	* die and exit and return json with a failed result and error reason.
	* @param string $error error message
	*/
	function error($error)
	{
		while (ob_get_level() > 0)ob_end_clean(); //clean any already half printed text
		
		die(json_encode(array( "status" => "failed", "error" => $error ),JSON_PRETTY_PRINT));
	}
	
	$config=array_key_exists('config',$_REQUEST) ? $_REQUEST['config'] : "";
	$query =array_key_exists('query' ,$_REQUEST) ? $_REQUEST['query']  : "";

	//$config="forever-21";
	//$query="forever 21 woman rust red white polka dot dress";
	
	if(strlen($config)==0)
		error("config parameter is empty");
		
	if(preg_match("/[a-zA-Z0-9_-]+/",$config))// sanitize input;
		$filename="tokenizer-$config.json";
	else
		error("wrong chars in config parameter");
	
	if(strlen($query)>500 )
		error("query is to long");
		
	if(strlen($config)>50 )
		error("config is to long");
	
	if(trim( $query )=="")
		error("query parameter is empty");
			
	if(!file_exists ( $filename ))
		error("confing file does not exists");
	
	try {
    	$tokenizer=new Tokenizer($filename);
		$result=$tokenizer->tokenize($query);
	} catch (Exception $e) {
		error('Caught exception: '. $e->getMessage());
	}

	if(empty($result))
		error("result is empty"); //was some unknown error
		
	while (ob_get_level() > 0)ob_end_clean();//clean any already half printed text like startup errors or query errors and just fail;
	echo json_encode($result,JSON_PRETTY_PRINT);
	exit();// don't print any text further;
?>