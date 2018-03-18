<?php


/**
*	Processing request 'Form_First'
*/
class Form_First
{
	protected $protecteds = array('out' => '', 'dbtable_name' => 'comments',
		'columns' => array('parent_table', 'parent_id', 'users_id', 'text', 'udate'));

	private $privates = array('status' => '');
	public $publics = array();

	function __CONSTRUCT($checked) {

			// @toDo : permissions for each database table


			// get credentials to access database
		$lines = file('backend/environment.txt');
		$count_lines = count($lines);
		$settings = array();
		for($i = 0; $i < $count_lines; $i++) {
			$row = explode(':', $lines[$i]);
			$settings[$row[0]] = trim($row[1]);
		}

		try {
				// building PDO query
			$columns = $this->protecteds['columns'];
			$count_columns = count($columns);
			$valuesStr = implode(', :', $columns);
			$valuesStr = ':' . $valuesStr;
			$valuesStr = trim($valuesStr, ', ');

			$qry = "INSERT INTO " . trim($this->protecteds['dbtable_name']) . "
				(" . implode(', ', $columns) . ") VALUES (" . $valuesStr . ")";

				// test values('content', 134, 2) are for fields ('parent_table', 'parent_id', 'users_id')
			$udate = date("Y-m-d");
			$values1 = array('content', 134, 2, $checked['comment'], (string) $udate );
			$values2 = array();
			for($i = 0; $i < $count_columns; $i++) {
				$column = $columns[$i];
				$values2[':' . $column] = $values1[$i];
			}

				// connecting to database
			$dns =  $settings['database_type'] . ":host=" . $settings['database_host'] . ";dbname=" . $settings['database_name'];
			$conn = new PDO($dns, $settings['username'], $settings['password']);
				// set PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// PDO prepared statement
			$stm = $conn->prepare($qry);

				// running query
			$stm->execute($values2);

				// rows affected
			$lastId = $conn->lastInsertId();
			if($lastId > 0){
					// output for ajax
				$innerHTML = '<br><br><hr>Saved:<br>' . $checked['fname'] . ' ' . $checked['lname']	. ' ' . date("Y-m-d")  . '<br>' . $checked['comment'];
				$this->privates['toCaller'] = array('tag' => 'asis', 'innerHTML' => $innerHTML );
				$this->privates['status'] = 'Ok';
			}
			else {
				$this->privates['toCaller'] = array('Failed 67' => 'The inputs are NOT saved.');
				$this->privates['status'] = 'Failed';
			}

			$stm = null;
			$conn = null;

		} catch (PDOException $e) {
			// print "Error!: " .  $e->getMessage() . "<br/>";

			$this->privates['toDeveloper'] = array('Error 77' => $e->getMessage() );
			$this->privates['toCaller'] = array('Failed 78' => 'The inputs are NOT saved.');

			$this->privates['status'] = 'Failed';
			$innerHTML = '<br><br><hr>' . $this->privates['toDeveloper'];
			$this->privates['toCaller'] = array('tag' => 'asis', 'innerHTML' => $innerHTML );

		}


		$this->protecteds['out'] = json_encode($this->privates['toCaller']);

	}

		/**
	*
	*/
	public function getOut()
	{
		return $this->protecteds['out'];
	}

}