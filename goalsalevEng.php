<?php

	require_once "class/DB.php";
	require_once "class/Operation.php";

	$opt = new Operation($db);
	$exe_data = $opt->getExecutive();

	if(isset($_REQUEST['id'])) {
		$id_event = $_REQUEST['id'];
		$eve_data = $opt->getEventByID($id_event);
	}

	if(isset($_POST['submit'])) {
    
      try {
          $opt->insertSale($id_event, $_POST['exe']);
            header("Location: goalsale.php?id=$id_event");
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    if(isset($_POST['goalSubmit'])) {

		//echo implode($_POST['goal']);

		// for ($i=0; $i < $cdt['countDate']+1; $i++) {
		//     echo $_POST['goal'][$i];
		// }
    
      try {
          $opt->insertGoal($id_event, $_POST['exe'], $_POST['goal']);
            header("Location: goalsale.php?id=$id_event");
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
?>
<?php
	include "header.php";
?>
<div class="container">
	<div class="row">                            
        <div class="col-md-12">
        	<div class="panel panel">
		        <div class="panel-heading" style="background:#34495e;">
		          <h3 class="panel-title" style="color:#fff;">Goal Sale '<?php echo $eve_data['name'] ?>'</h3>
		        </div>
		        <center style="padding-top:10px;">
		          <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#userModal" style="background:#34495e;color:#fff;">Add Executive</button>
		        </center>

		        <div class="content table-responsive table-full-width">
	              <table class="table table-striped">
	                  <thead>
	                    <th>Executive</th>
	                    <th>No. Stand</th>
	                    <?php
	                    	$alldt = "SELECT PERIOD_DIFF(DATE_FORMAT(salesdateFin, '%Y%m'),DATE_FORMAT(salesdateIni, '%Y%m')) as countDate, DATE_FORMAT(salesdateIni, '%m') as startDate, DATE_FORMAT(salesdateIni, '%Y') as startDateY FROM event WHERE idEvent={$id_event}";
	                    	$alldt = $db->prepare($alldt);
	                    	$alldt->execute();
	                    	$cdt = $alldt->fetch(PDO::FETCH_ASSOC);
	                    	//echo $cdt['countDate']+1;

	                    	if(!$cdt['countDate'] == 0) {
	                    		
		                    	$month = $cdt['startDate'];
		                    	$year = $cdt['startDateY'];
		                    	for($i=1; $i<=$cdt['countDate']+1; $i++)
		                    	{
		                    		switch ($month) {
		                    			case '1':
		                    				$monthName = 'January';
		                    				break;
		                    			case '2':
		                    				$monthName = 'February';
		                    				break;
		                    			case '3':
		                    				$monthName = 'Maret';
		                    				break;
		                    			case '4':
		                    				$monthName = 'April';
		                    				break;
		                    			case '5':
		                    				$monthName = 'May';
		                    				break;
		                    			case '6':
		                    				$monthName = 'June';
		                    				break;
		                    			case '7':
		                    				$monthName = 'July';
		                    				break;
		                    			case '8':
		                    				$monthName = 'August';
		                    				break;
		                    			case '9':
		                    				$monthName = 'September';
		                    				break;
		                    			case '10':
		                    				$monthName = 'Oktober';
		                    				break;
		                    			case '11':
		                    				$monthName = 'November';
		                    				break;
		                    			case '12':
		                    				$monthName = 'Desember';
		                    				break;
		                    		}
		                    		?>
		                    			<th><?php echo $monthName.' '.$year; ?></th>
		                    		<?php
		                    		$month += 1;
		                    		if($month == 13) {
		                    			$month = 1;
		                    			$year += 1;
		                    		}
		                    	}
	                    	} else {
	                    		?>
	                    			<th>No Data</th>
	                    		<?php
	                    	}
	                    ?>
	                  </thead>
	                  <tbody>
	                    <?php

	                        $tb = "SELECT executive.name, executive.idEjecutive as id, GROUP_CONCAT(goalsale.goal) as goals,
	                        		SUM(goalsale.goal) as countGoal 
	                        		FROM executive INNER JOIN goalsale ON 
	                        		(executive.idEjecutive=goalsale.idEjecutive) 
	                        		WHERE goalsale.idEvent = {$id_event} 
	                        		GROUP BY executive.name";
                            $tbl = $db->prepare($tb);
                            $tbl->execute();

                            $subtotal = 0;
                            if($tbl->rowCount()>0)
                            {
                                while($tbls=$tbl->fetch(PDO::FETCH_ASSOC))
                                {
                                	$subtotal += $tbls['countGoal'];
                                   	?>
                                        <tr>
                                            <td><button type="button" class="btn btn-link goalInput" data-idEve="<?php print($id_event); ?>" data-id="<?php print($tbls['id']); ?>"><?php echo $tbls['name'] ?></button>
                                            </td>
                                            <td><?php echo $tbls['countGoal'] ?></td>
                                            <?php 
	                                            $value = $tbls['goals'];
												$values = explode(",", $value);
                                            	for($i=0; $i<$cdt['countDate']+1; $i++) {
                                            		?> 
                                            			<td><?php echo $values[$i] ?></td> 
                                            		<?php
                                            	}
                                            ?>
                                        </tr>
                                   	<?php 
                                }
                                ?>
                                	<tr>
                                		<td><b>Subtotal</b></td>
	                                	<td><?php echo $subtotal; ?></td>
	                                	<?php
	                                		for($i=0; $i<$cdt['countDate']+1; $i++) {
                                        		?> 
                                        			<td>&nbsp;</td> 
                                        		<?php
                                        	}
	                                	?>
	                                </tr>
                                <?php
                            }

	                    ?>
	                   </tbody>
	               </table>
	           </div>

		      </div>
        </div>
    </div>
</div>

<div id="userModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Event</h4>
        </div>
        <div class="modal-body">
          <label>Executive</label>
          <select class="form-control" name="exe" id="exe" required>
            <option></option>
            <?php foreach ($exe_data as $value): ?>
              <option value="<?php echo $value['idEjecutive']; ?>"><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
          </select>
        <div class="modal-footer">
          <input type="submit" name="submit" id="action" class="btn btn-success" value="Add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<div id="userModalGoal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Goal</h4>
        </div>
        <div class="modal-body">
        	<input type="hidden" class="id" name="exe">
       	<?php
       		$month = $cdt['startDate'];
	    	$year = $cdt['startDateY'];
	    	for($i=0; $i<$cdt['countDate']+1; $i++)
	    	{
	    		switch ($month) {
	    			case '1':
	    				$monthName = 'January';
	    				break;
	    			case '2':
	    				$monthName = 'February';
	    				break;
	    			case '3':
	    				$monthName = 'Maret';
	    				break;
	    			case '4':
	    				$monthName = 'April';
	    				break;
	    			case '5':
	    				$monthName = 'May';
	    				break;
	    			case '6':
	    				$monthName = 'June';
	    				break;
	    			case '7':
	    				$monthName = 'July';
	    				break;
	    			case '8':
	    				$monthName = 'August';
	    				break;
	    			case '9':
	    				$monthName = 'September';
	    				break;
	    			case '10':
	    				$monthName = 'Oktober';
	    				break;
	    			case '11':
	    				$monthName = 'November';
	    				break;
	    			case '12':
	    				$monthName = 'Desember';
	    				break;
	    		}
	    		?>
	    		<div class="content table-responsive table-full-width">
	    			<table class="table">
	    				<tbody>
		    				<tr>
		    					<td width="30%"><label><?php echo $monthName." ".$year; ?></label></td>
		    					<td width="30%"><input type="number" size="10" class="goal" name="goal[]" id="goal"></td>
		    				</tr>
		    			</tbody>
	    			</table>
	    		</div>	    			
	    		<?php
	    		$month += 1;
	    		if($month == 13) {
	    			$month = 1;
	    			$year += 1;
	    		}
	    	}
       	?>
          
        <div class="modal-footer">
          <input type="submit" name="goalSubmit" id="action" class="btn btn-success" value="Add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
$(document).on( "click", '.goalInput',function(e) {

	var x_id = $(this).data('id');
	var idEve = $(this).attr("data-idEve");
    $(".id").val(x_id);
    var table = 'goalsale';

    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{x_id:x_id,table:table,idEve:idEve},
      dataType:"json",
      success:function(data)
      {
        $('#userModalGoal').modal('show');
 
        var inputs = document.getElementsByName('goal[]');
		for (i = 0; i < inputs.length; i++) {
		    inputs[i].value = data[i]['goal'];
		}
      }
    })
});
$(".modal").on("hidden.bs.modal", function(){
    location.reload();
});
</script>
