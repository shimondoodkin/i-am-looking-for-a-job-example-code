<?php
/**
 * run basic tests
 */
echo "<pre>";

include "tokenizer.class.php";

/**
* test
*
* execute few tests to validate that everything is still working 
*
* @param string $filename filename to load to tokenizer class for example "tokenizer-forever-21.json"
*/
function test($filename)
{
	$t=new Tokenizer($filename);
	// simple texts and color
	
	$re=$t->tokenize($s="red dress"); 
	echo "$s:";
	echo " 1".(in_array (  "red"  ,$re["properties"]["color"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
	
    echo "\n\n";
	
	$re=$t->tokenize($s="brick red dress"); 
	echo "$s:";
	echo " 1".(in_array (  "brick red"  ,$re["properties"]["color"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
		
    echo "\n\n";
	
	$re=$t->tokenize($s="blueish dress");
	echo "$s:";
	echo " 1".(in_array (  "blue"  ,$re["properties"]["color"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
	//var_dump($re);
    echo "\n\n";
		

	// gender
	$re=$t->tokenize($s="woman dress");
	echo "$s:";
	echo " 1".(in_array (  "woman"  ,$re["properties"]["gender"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
    echo "\n\n";
	
	$re=$t->tokenize($s="men dress");
	echo "$s:";
	echo " 1".(in_array (  "men"  ,$re["properties"]["gender"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
    echo "\n\n";
	
	// pattern
	$re=$t->tokenize($s="woman rust red white polka dot dress");
	echo "$s:";
	echo " 1".(in_array (  "polka dot"  ,$re["properties"]["pattern"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
    echo "\n\n";
	
	// brand
	$re=$t->tokenize($s="gap woman rust red white polka dot dress");
	echo "$s:";
	echo " 1".(in_array (  "polka dot"  ,$re["properties"]["pattern"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
	echo "\n\n";
	
	$re=$t->tokenize($s="forever 21 woman rust red white polka dot dress");
	echo "$s:";
	echo " 1".(in_array (  "polka dot"  ,$re["properties"]["pattern"], true  )?"success":"failed");
	echo " 2".($re["keywords"]=="dress"?"success":"failed");
	echo "\n\n";
	var_dump($re);
}

//test("tokenizer-forever-21.json");
test("tokenizer-generic.json");

?>