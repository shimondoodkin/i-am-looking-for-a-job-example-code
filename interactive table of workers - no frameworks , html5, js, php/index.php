<?php
/*
Task:

PHP home lab :

BEFORE YOU START: 

Do not use a framework to write the code - only code from scratch. 
Make sure that your code is not vulnerable to basic hacking attempts.
 
Create a PHP page (using mysql db)containing the following:

•	A Table containing the following fields: 

Company Name | Company Address | Contact Name | Contact number | Note.

 **one company can be linked to several contacts***

•	Above the table there should be a "Create  Contact" Button.
    When clicking on the "Create Contact" button ,
    a pop up will appear with the following fields:

Company  Name (will be a drop down list of the companies that exists in the company table). 
Company Address (Once the company is chosen
                 the address will be filled out automatically - the address can be modified)
Contact Name
Contact Number
Note

after pressing "save" button ,
 a new row with the info should appear in the table - WITHOUT REFRESHING THE PAGE.
 
•	Update Pop up:
Each row should have an update button, 
after pressing it, a pop up will appear (the same pop up as the insert) 
 with the existing data filled out.
 the user can modify the data and click save.
 After pressing the "save" button,
 only the row grid should update with the new info - without
 refreshing the entire table .
 
•	Search Engine above the table:
company name| contact name| contact number | note . 

*for example If I write "Apple" in the company field AND write Mic -  It 
should find Michal from the company "apple".

Good Luck !!!


*/


/*
 
 CREATE USER 'tester'@'localhost' IDENTIFIED BY 'tester1234';
 GRANT USAGE ON *.* TO 'tester'@'localhost' IDENTIFIED BY 'tester1234' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

 CREATE DATABASE IF NOT EXISTS `tester`;
 GRANT ALL PRIVILEGES ON `tester`.* TO 'tester'@'localhost';
 
 
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `address` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `number` varchar(20) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `company_id`, `address`, `name`, `number`, `note`) VALUES
(1, 2, 'address2', 'shimon', '1', 'good'),
(2, 3, 'apple address', 'Michael', '1234', 'a note');

ALTER TABLE `contact`  ADD PRIMARY KEY (`id`);
 
ALTER TABLE `contact`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;


CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `address`) VALUES
(1, 'company1', 'adderss1'),
(2, 'company2', 'address2'),
(3, 'apple', 'apple address');
 
ALTER TABLE `company`  ADD PRIMARY KEY (`id`);

ALTER TABLE `company`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;


*/




class Contacts  {
	function __construct() {				
		$this->db = new PDO('mysql:host=localhost;dbname=tester;charset=utf8mb4', 'tester', 'tester1234');

		$this->take_actions();
	}

	function take_actions()
	{
		$action=@$_REQUEST['action'];

		if($action=="tablejson") {
			$this->tablejson();
		}
		if($action=="contact_insert") {
			$this->contact_insert();
		}
		if($action=="contact_update") {
			$this->contact_update();
		}
		if($action=="select_row") {
			$this->select_row(@$_REQUEST['id']);
		}
	}

	function tablejson() {
		try {
			$query = $this->db->prepare("SELECT 
			`company`.`id` as \"company.id\", 
			`company`.`name` as \"company.name\",
			`company`.`address` as \"company.address\",
			contact.*
			FROM `contact` left join `company` on `contact`.`company_id`=`company`.`id`
			WHERE 
			    `company`.`name` LIKE :company_name
			and  `contact`.`address` LIKE :address
			and  `contact`.`name` LIKE :name
			and  `contact`.`number` LIKE :number
			and  `contact`.`note` LIKE :note"); 
			$query->bindValue(':company_name', "%".(@$_REQUEST["company_name"])."%", PDO::PARAM_STR);
			$query->bindValue(':address', "%".(@$_REQUEST["address"])."%", PDO::PARAM_STR); 
			$query->bindValue(':name', "%".(@$_REQUEST["name"])."%", PDO::PARAM_STR); 
			$query->bindValue(':number', "%".(@$_REQUEST["number"])."%", PDO::PARAM_STR); 
			$query->bindValue(':note', "%".(@$_REQUEST["note"])."%", PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			header("content-type:application/json");
			echo json_encode($data);
			exit();
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	function select_row($id) { // usage: $this->select_row(@$_REQUEST['id'])
		try {
			  
			if(!$id)
			{
				exit("not id specified");
				return;
			}
			$query = $this->db->prepare("SELECT 
			`company`.`id` as \"company.id\",
			`company`.`name` as \"company.name\",
			`company`.`address` as \"company.address\",
			contact.* 
			FROM `contact` left join `company` on `contact`.`company_id`=`company`.`id`
			WHERE
			`contact`.`id`=:id");
			$query->bindValue(':id', $id, PDO::PARAM_INT); 
			$query->execute();
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			header("content-type:application/json");
			echo json_encode($data[0]);
			exit();
			
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	function contact_insert() {
		try {
			  
			$query = $this->db->prepare("INSERT INTO `contact` SET 
			`company_id`=:company_id,
			`address`=:address,
			`name`=:name,
			`number`=:number,
			`note`=:note
			;
			");
			
			$query->bindValue(':company_id', @$_REQUEST['company_id'], PDO::PARAM_INT);
			$query->bindValue(':address', @$_REQUEST['address'], PDO::PARAM_STR);
			$query->bindValue(':name', @$_REQUEST['name'], PDO::PARAM_STR);
			$query->bindValue(':number', @$_REQUEST['number'], PDO::PARAM_STR);
			$query->bindValue(':note', @$_REQUEST['note'], PDO::PARAM_STR);
			if(!$query->execute()){
				echo "insert failed\nPDOStatement::errorInfo():\n";
				$arr = $query->errorInfo();
				print_r($arr);
				var_dump($query);
				var_dump($this->db);
			}
			$insertId = $this->db->lastInsertId();
			 
			$this->select_row($insertId);
			
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	
	function contact_update() {
		try
		{
			if(!@$_REQUEST['id'])
			{
				header("content-type:application/json");
				echo '{"error":" not id "}';
				exit();
			}
			
			$query = $this->db->prepare("UPDATE `contact` SET 
			`company_id`=:company_id,
			`address`=:address,
			`name`=:name,
			`number`=:number,
			`note`=:note
			WHERE `id`=:id
			;");
			
			$query->bindValue(':company_id', @$_REQUEST['company_id'], PDO::PARAM_INT);
			$query->bindValue(':address', @$_REQUEST['address'], PDO::PARAM_STR);
			$query->bindValue(':name', @$_REQUEST['name'], PDO::PARAM_STR);
			$query->bindValue(':number', @$_REQUEST['number'], PDO::PARAM_STR);
			$query->bindValue(':note', @$_REQUEST['note'], PDO::PARAM_STR);
			$query->bindValue(':id', @$_REQUEST['id'], PDO::PARAM_INT);
			$query->execute();
			 
			$this->select_row(@$_REQUEST['id']);
			
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
}

$contacts=new Contacts();
 
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>contacts</title>

	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
</head>
<body> 
	<div class="container"> 
	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					 <div class="panel-heading">Contacts</div>
					 <table class="table">
						<thead>
							<form id="contacts_head_form" onsubmit="event.preventDefault();"  autocomplete="off">
							<tr>
								<th>Company Name<br><input class="table_search_input" name="company_name" size="15" type="text" value=""></th>
								<th>Company Address<br><input class="table_search_input" name="address" size="15" type="text" value=""></th>
								<th>Contact Name<br><input class="table_search_input" name="name" size="15" type="text" value=""></th>
								<th>Contact number<br><input class="table_search_input" name="number" size="15" type="text" value=""></th>
								<th>Note<br><input class="table_search_input" name="note" size="15" type="text" value=""></th>
								<th><button type="button" class="btn btn-info" data-toggle="modal" data-target="#AddContactModal">Add Contact</button></th>
							</tr>
							</form>
						</thead>
						<tbody id="table_content">
							
						</tbody>
					 </table>
				</div><!-- panel -->
			</div> <!-- col -->
		</div><!-- row -->
	</div><!-- container -->
	
	
	
	<div id="AddContactModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Contact</h4>
		  </div>
		  <div class="modal-body">
			<form action="?action=add" id="AddContactForm" method="post">
			
				Company: <select name="company_id" onchange="
				var i=this.selectedIndex;
				if(i==-1) return;
				$('#addForm_address').val($(this.options[i]).data('address'))
				">
				<option value="" data-address="">select company</option>
				<?php	
					ob_start();
					try {
					
						$query = $contacts->db->prepare("SELECT * FROM `company`");
						$query->execute();
						$data = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach( $data as &$row  ){
							echo "<option value=\"$row[id]\" data-address=\"".htmlspecialchars($row["address"])."\">".htmlspecialchars($row["name"])."</option>";
						}
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
					$options=ob_get_contents();
					ob_end_flush();
				?>
				</select> <br />
				Contact Address: <input type="text" id="addForm_address" name="address" value=""> <br />
				Contact Name: <input type="text" name="name" value=""> <br />
				Contact Number: <input type="text" name="number" value=""> <br />
				Note: <textarea name="note"></textarea><br />
				<button type="submit"  class="btn btn-default">Save</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>

	  </div>
	</div> 
	
	
	
	<div id="UpdateContactModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Update Contact</h4>
		  </div>
		  <div class="modal-body">
			<form action="?action=update" id="UpdateContactForm" method="post"><input type="hidden" id="updateForm_id" name="id" value="">
			
				Company: <select name="company_id" onchange="
				var i=this.selectedIndex;
				if(i==-1) return;
				$('#updateForm_address').val($(this.options[i]).data('address'))
				">
				<option value="" data-address="">select company</option>
				<?php	
					echo $options;
				?>
				</select> <br />
				Contact Address: <input type="text" id="updateForm_address" name="address" value=""> <br />
				Contact Name: <input type="text" name="name" value=""> <br />
				Contact Number: <input type="text" name="number" value=""> <br />
				Note: <textarea name="note"></textarea><br />
				<button type="submit">Save</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>

	  </div>
	</div> 

	
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" language="javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" >
	
		var search_timeout
		function search()
		{
			if(search_timeout)clearTimeout(search_timeout);			
			search_timeout=setTimeout(loaddata,500);
		}
		
		function update(data)
		{
			var id=this.row.id;
			var tr=this.tr
			$.get('?action=select_row&id='+id, function(data, status, xhr){
				console.log(data)
				var form=$('#UpdateContactForm').get(0);
				 $(form).data('related_tr',tr);
				
				$("input[name=id]",form).val(data.id);
				$("select[name=company_id]",form).val(data.company_id);
				$("input[name=address]",form).val(data.address);
				$("input[name=name]",form).val(data.name);
				$("input[name=number]",form).val(data.number);
				$("textarea[name=note]",form).val(data.note);
				 
				$("#UpdateContactModal").modal();
			})
		}
		
		function make_tr_from_row(row)
		{
			var tr=$('<tr>'+
				 '<td>Company Name</td>'+
				 '<td>Company Address</td>'+
				 '<td>Contact Name</td>'+
				 '<td>Contact number</td>'+
				 '<td>Note</td>'+
				 '<td><Input type="button" class="btn btn-info btn-xs" value="Update"></td>'+
				 '</tr>');
			
				
			$(tr).data('id',row.id);										
			$(tr.children().get(0)).text(row['company.name']);  // Company Name
			$(tr.children().get(1)).text(row['address']);  // Company Address
			$(tr.children().get(2)).text(row['name']);  // Contact Name
			$(tr.children().get(3)).text(row['number']);  // Contact number
			$(tr.children().get(4)).text(row['note']);  // Note
			$(tr.children().get(5)).click(update.bind({tr:tr,row:row}));  // Update
			return tr;
		}
		
		function loaddata()
		{				
		
			$form=$('#contacts_head_form');
 			$.post('?action=tablejson',$form.serialize(), function(data, status, xhr){
				console.log(data);
				
				var tbody=$('<tbody id="table_content"></tbody>');

				data.forEach(function(row){

					var tr=make_tr_from_row(row);
					tbody.append(tr);
				});
				
				$("#table_content").replaceWith(tbody);
				
			});
		}
		
		 
		
		
		$(document).ready(function() {
			
			var $search_inputr=$('.table_search_input');
			$search_inputr.change(search);
			$search_inputr.keyup(search);
 
			$('#AddContactForm').submit(function(e){
				  e.preventDefault();
				  $.post('?action=contact_insert',$('#AddContactForm').serialize(), function(data, status, xhr){
					   var new_tr=make_tr_from_row(data);
					   $("#table_content").append(new_tr);
					   $('#AddContactModal').modal('hide'); 
					   $(new_tr).addClass('alert-success');
					   $(new_tr).one('mouseover',function(){
						$(new_tr).removeClass('alert-success');   
					   });
					   $(window).one('scroll',function(){
						$(new_tr).removeClass('alert-success');   
					   });
				  });
		    });
			 
			$('#UpdateContactForm').submit(function(e){
				  e.preventDefault();
				  $.post('?action=contact_update',$('#UpdateContactForm').serialize(), function(data, status, xhr){
					   // do something here with response;
					   //location.reload();
					   var tr=$('#UpdateContactForm').data('related_tr');
					   var new_tr=make_tr_from_row(data);
					   $(tr).replaceWith(new_tr);
					   $('#UpdateContactModal').modal('hide'); 
					   $(new_tr).addClass('alert-success');
					   $(new_tr).one('mouseover',function(){
						$(new_tr).removeClass('alert-success');   
					   });
					   $(window).one('scroll',function(){
						$(new_tr).removeClass('alert-success');   
					   });
				  });
		    });
			
		
			loaddata();   
			
		} );
	</script>
</body>
</html>

