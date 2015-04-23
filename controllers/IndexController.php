<?php
/**
 * Controller for Poll.
 *
 * @package Poll
 */
class Poll_IndexController extends Omeka_Controller_AbstractActionController
{    
	public function indexAction()
	{	
	}
	public function submitAction()
	{
		
	    if ($this->getRequest()->isPost()) {    		
    	
			$db = get_db();
			$table = get_db()->getTableName('Poll');

			if(isset($_POST['poll-usage']))
				$q1 = $_POST['poll-usage'];
			if(isset($_POST['poll-usage-other']))
				$q1_other = $_POST['poll-usage-other'];
			if(isset($_POST['poll-location']))
				$q2 = $_POST['poll-location'];
			if(isset($_POST['page']))
				$page = $_POST['page'];
			if(isset($_POST['js']))
				$js = $_POST['js'] == 'true'?1:0;
			 
			if(!empty($q1) && !empty($q2)){
				
				//Zend can use prepared statements
				$sql = "
				INSERT INTO $table
				(
					q1,
					q1_other,
					q2,
					page,
					js
				)
				VALUES(?, ?, ?, ?, ?)
				";
				
				$values = array(
					$q1,
					$q1_other,
					$q2,
					$page,
					$js
					//$this->getRequest()->getRequestUri(),
				);
				
				
				if($db->query($sql, $values)){
				
					//Only shows if JavaScript is disabled
					echo 'Dankuwel. U kunt <a href="'.$page.'">teruggaan</a> naar de pagina.';
				
					setcookie('survey_completed', true, time()+60*60*24*365, '/');//365 days
					
				}
			}
		}
		
	}
	
	public function downloadAction()
	{	
		$db = get_db();
		$table = $db->getTable('Poll');
		$tablename = $db->getTable('Poll')->getTableName();
		$output = "";
		
		

        $select = "SELECT * FROM $tablename";
		
		$result = $db->fetchAssoc($select);
		
		$tabledescription = $db->describeTable($tablename);
		$metaData = $db->describeTable($tablename);
		$columnNames = array_keys($metaData);
		
		
		// Get The Field Name

		
		foreach($columnNames as $colName){
			$output .= '"'.$colName.'",';
		}
		$output .="\n";
		
		// Get Records from the table

		foreach($result as $item) {
			foreach ($item as $row) {
				$output .='"'.$row.'",';
			}
			$output .="\n";
		}

		// Download the file

		$filename = "PollResults-".date('Y-m-d-h:m:s').".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;
	}
		


}
