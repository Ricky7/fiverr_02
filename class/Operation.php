<?php

    class Operation {

        private $db; 

        function __construct($db_conn) {

            $this->db = $db_conn;

        }

        public function total_records($table)
		{
			 $statement = $this->db->prepare("SELECT * FROM {$table}");
			 $statement->execute();
			 $result = $statement->fetchAll();
			 return $statement->rowCount();
		}

		public function event_fetch_single($id)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT * FROM event 
				WHERE idEvent = '".$id."' 
				LIMIT 1"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["names"] = $row["name"];
				$output["event_start"] = $row["eventdateIni"];
				$output["event_finish"] = $row["eventdateFin"];
				$output["sale_start"] = $row["salesdateIni"];
				$output["sale_finish"] = $row["salesdateFin"];
				$output["stats"] = $row["active"];
			}
			echo json_encode($output);
		}

		public function exec_fetch_single($id)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT * FROM executive 
				WHERE idEjecutive = '".$id."' 
				LIMIT 1"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["names"] = $row["name"];
				$output["stats"] = $row["active"];
			}
			echo json_encode($output);
		}

		public function goal_fetch_single($idEje, $idEve)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT DATE_FORMAT(month,'%m %Y') AS YearMonth, goal 
				FROM goalsale 
				WHERE idEvent = '".$idEve."' 
				AND idEjecutive = '".$idEje."'"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			
			echo json_encode($result);
		}

		public function salesreal_fetch_single($idEje, $idEve, $date)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT COUNT(idStand) AS SalesReal
				FROM Stand S 
				INNER JOIN Prospect P ON S.idProspect = P.idProspect
				INNER JOIN ProspectExecutiveEvent PEE ON P.idProspect = PEE.idProspect
				WHERE S.status = 2 
				AND PEE.idEvent = '".$idEve."' 
				AND PEE.idEjecutive = '".$idEje."'
				AND DATE_FORMAT(S.salesdate,'%m %Y') = '".$date."'"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["real"] = $row["SalesReal"];
			}
			echo json_encode($output);
		}

		public function compare_fetch_single($idEje, $idEve)
		{
			$output = array();
			$result2 = array();
			$statement = $this->db->prepare(
				"SELECT DATE_FORMAT(month,'%m %Y') AS YearMonth, 
					goal
				FROM goalsale
				WHERE idEvent = '".$idEve."' 
				AND idEjecutive = '".$idEje."'"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			
		    echo json_encode($result);
            
		}

		public function buss_fetch_single($id)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT * FROM businessline 
				WHERE idBusinessline = '".$id."' 
				LIMIT 1"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["names"] = $row["name"];
				$output["stats"] = $row["active"];
			}
			echo json_encode($output);
		}

		public function pros_fetch_single($id)
		{
			$output = array();
			$statement = $this->db->prepare(
				"SELECT *, businessline.name as bname FROM prospect inner join businessline
				inner join prospectexecutiveevent on (prospect.idBusinessline=businessline.idBusinessline)
				AND (prospect.idProspect=prospectexecutiveevent.idProspect)WHERE prospect.idProspect = '".$id."' 
				LIMIT 1"
			);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$eve_data = $this->getEventByID($row['idEvent']);
				$exe_data = $this->getExecutiveByID($row['idEjecutive']);
				$output["names"] = $row["name"];
				$output["company"] = $row["company"];
				$output["telephone"] = $row["telephone"];
				$output["whatsapp"] = $row["whatsapp"];
				$output["facebook"] = $row["facebook"];
				$output["email"] = $row["email"];
				$output["webpage"] = $row["webpage"];
				$output["idBL"] = $row["idBusinessline"];
				$output["bname"] = $row["bname"];
				$output["idEve"] = $row["idEvent"];
				$output["evename"] = $eve_data['name'];
				$output["idExe"] = $row["idEjecutive"];
				$output["exename"] = $exe_data['name'];
				$output["bOther"] = $row["businesslineother"];
				$output["stats"] = $row["active"];
			}
			echo json_encode($output);
		}

		public function getBusinessline() {

            try {
                // Ambil data kategori dari database
                $query = $this->db->prepare("SELECT * FROM businessline WHERE active = 1");
                $query->execute();
                return $query->fetchAll();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getEvent() {

            try {
                // Ambil data kategori dari database
                $query = $this->db->prepare("SELECT * FROM event WHERE active = 1");
                $query->execute();
                return $query->fetchAll();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getExecutive() {

            try {
                // Ambil data kategori dari database
                $query = $this->db->prepare("SELECT * FROM executive WHERE active = 1");
                $query->execute();
                return $query->fetchAll();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getBusinesslineByID($id) {

	        $stmt = $this->db->prepare("SELECT * FROM businessline WHERE idBusinessline=:id");
	        $stmt->execute(array(":id"=>$id));
	        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
	        return $editRow;
	    }

	    public function getEventByID($id) {

	        $stmt = $this->db->prepare("SELECT * FROM event WHERE idEvent=:id");
	        $stmt->execute(array(":id"=>$id));
	        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
	        return $editRow;
	    }

	    public function getExecutiveByID($id) {

	        $stmt = $this->db->prepare("SELECT * FROM executive WHERE idEjecutive=:id");
	        $stmt->execute(array(":id"=>$id));
	        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
	        return $editRow;
	    }

		public function delete($id, $table, $idfield)
		{
			$statement = $this->db->prepare(
				"DELETE FROM {$table} WHERE {$idfield} = :id"
			);
			$result = $statement->execute(
				array(
					':id'	=>	$id
				)
			);
			
			if(!empty($result))
			{
				echo 'Data Deleted';
			}
		}

		public function delGoalSale($idEje, $table, $idEve)
		{
			$statement = $this->db->prepare(
				"DELETE FROM {$table} WHERE idEvent = :idEve
				AND idEjecutive = :idEje"
			);
			$result = $statement->execute(
				array(
					':idEve'	=>	$idEve,
					':idEje'	=>	$idEje
				)
			);
			
			if(!empty($result))
			{
				echo 'Data Deleted';
			}
		}

		public function insertGoal($id_event, $id_ejecutive, $goal) {

			$check = "SELECT idEvent, idEjecutive, idGoalSale FROM goalsale WHERE idEvent = {$id_event} 
			AND idEjecutive = {$id_ejecutive} ORDER BY idGoalSale asc";
			$check = $this->db->prepare($check);
			$check->execute();
			//$checks = $check->fetch(PDO::FETCH_ASSOC);
			$checks = $check->fetchAll();

			if($check->rowCount()>0){

				$sql = $this->db->prepare("UPDATE goalsale SET goal = :goal WHERE idGoalSale = :idGoalSale");

			    for($i=0; $i<$check->rowCount(); $i++) {
			        $sql->bindparam(':goal', $goal[$i]);      
			        $sql->bindparam(':idGoalSale', $checks[$i]['idGoalSale']);
			        $sql->execute();
			    }
			    return $sql;
			}

		}

		public function insertSale($id_event, $id_ejecutive) {

			$check = "SELECT idEvent, idEjecutive FROM goalsale WHERE idEvent = {$id_event} 
			AND idEjecutive = {$id_ejecutive}";
			$check = $this->db->prepare($check);
			$check->execute();
			if(!$check->rowCount()>0){
				//if there
				$alldt = "SELECT PERIOD_DIFF(DATE_FORMAT(salesdateFin, '%Y%m'), 
					DATE_FORMAT(salesdateIni, '%Y%m')) as countDate,
					DATE_FORMAT(salesdateIni, '%d') as startDateD, 
					DATE_FORMAT(salesdateIni, '%m') as startDateM, 
					DATE_FORMAT(salesdateIni, '%Y') as startDateY,
					DATE_FORMAT(salesdateFin, '%d') as startDateFinD 
					FROM event WHERE idEvent={$id_event}";
	        	$alldt = $this->db->prepare($alldt);
	        	$alldt->execute();
	        	$cdt = $alldt->fetch(PDO::FETCH_ASSOC);

	        	$month = $cdt['startDateM'];
				$year = $cdt['startDateY'];
				$dayIni = $cdt['startDateD'];
				$dayFin = $cdt['startDateFinD'];
	        	for($i=1; $i<=$cdt['countDate']+1; $i++)
	        	{
	        		if($i == 1){
	        			$day = $dayIni;
	        		} else if($i == $cdt['countDate']+1) {
	        			$day = $dayFin;
	        		} else {
	        			$day = 15;
	        		}
	        		$statement = $this->db->prepare("INSERT INTO goalsale (month, goal, idEvent, idEjecutive) VALUES (STR_TO_DATE('{$day},{$month},{$year}','%d,%m,%Y'), :goal, :idEvent, :idEjecutive)");
					$result = $statement->execute(
						array(
							':goal'	=>	0,
							':idEvent'	=>	$id_event,
							':idEjecutive'	=>	$id_ejecutive
						)
					);
					$month += 1;
	        		if($month == 13) {
	        			$month = 1;
	        			$year += 1;
	        		}
	        	}
			}
        }

    }
?>