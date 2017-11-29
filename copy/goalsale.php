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
		        <div class="panel-heading" style="background:#87B459;">
		          <h3 class="panel-title" style="color:#fff;">Metas de Venta '<?php echo $eve_data['name'] ?>'</h3>
		        </div>
		        <center style="padding-top:10px;">
		          <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#userModal" style="background:#34495e;color:#fff;">Nuevo Executivo</button>
		        </center>

		        <div class="content table-responsive table-full-width">
	              <table class="table table-striped">
	                  <thead>
	                    <th>Executivo</th>
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
		                    				$monthName = 'Enero';
		                    				break;
		                    			case '2':
		                    				$monthName = 'Febrero';
		                    				break;
		                    			case '3':
		                    				$monthName = 'Marzo';
		                    				break;
		                    			case '4':
		                    				$monthName = 'Abril';
		                    				break;
		                    			case '5':
		                    				$monthName = 'Mayo';
		                    				break;
		                    			case '6':
		                    				$monthName = 'Junio';
		                    				break;
		                    			case '7':
		                    				$monthName = 'Julio';
		                    				break;
		                    			case '8':
		                    				$monthName = 'Agosto';
		                    				break;
		                    			case '9':
		                    				$monthName = 'Septiembre';
		                    				break;
		                    			case '10':
		                    				$monthName = 'Octubre';
		                    				break;
		                    			case '11':
		                    				$monthName = 'Noviembre';
		                    				break;
		                    			case '12':
		                    				$monthName = 'Diciembre';
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
	                    <th>Chart</th>
	                    <th>Delete</th>
	                  </thead>
	                  <tbody>
	                    <?php

	                        $tb = "SELECT executive.name, executive.idEjecutive as id, GROUP_CONCAT(goalsale.goal ORDER BY goalsale.month) as goals,
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
                                            <td><button type="button" class="btn btn-danger btn-xs compare" data-idEve="<?php print($id_event); ?>" data-id="<?php print($tbls['id']); ?>">Vs Real</button>
                                            <td><button type="button" class="btn btn-info btn-xs delete" data-idEve="<?php print($id_event); ?>" data-id="<?php print($tbls['id']); ?>">Delete</button>
                                        </tr>
                                   	<?php 
                                }
                                ?>
                                	<tr>
                                		<td><b>Subtotal</b></td>
	                                	<td><?php echo $subtotal; ?></td>
	                                	<?php
	                                		for($i=0; $i<$cdt['countDate']+3; $i++) {
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
          <h4 class="modal-title">Agrega Ejecutivo a Metas</h4>
        </div>
        <div class="modal-body">
          <label>Executivo</label>
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
          <h4 class="modal-title">Definir Metas de Venta</h4>
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
	    				$monthName = 'Enero';
	    				break;
	    			case '2':
	    				$monthName = 'Febrero';
	    				break;
	    			case '3':
	    				$monthName = 'Marzo';
	    				break;
	    			case '4':
	    				$monthName = 'Abril';
	    				break;
	    			case '5':
	    				$monthName = 'Mayo';
	    				break;
	    			case '6':
	    				$monthName = 'Junio';
	    				break;
	    			case '7':
	    				$monthName = 'Julio';
	    				break;
	    			case '8':
	    				$monthName = 'Agosto';
	    				break;
	    			case '9':
	    				$monthName = 'Septiembre';
	    				break;
	    			case '10':
	    				$monthName = 'Octubre';
	    				break;
	    			case '11':
	    				$monthName = 'Noviembre';
	    				break;
	    			case '12':
	    				$monthName = 'Diciembre';
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
</div>

<div id="userModalCompare" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Compare Chart</h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      		<div id="chart_div"></div>
        </div>
        <div class="modal-footer">
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
$(document).on("click", '.compare',function(e) {

	var x_id = $(this).data('id');
	var idEve = $(this).attr("data-idEve");
    //$(".id").val(x_id);
    var table = 'compare';

    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{x_id:x_id,table:table,idEve:idEve},
      dataType:"json",
      success:function(data)
      {
        $('#userModalCompare').modal('show');
        var count = Object.keys(data).length;
        //alert(data);
        //alert(data[7]['YearMonth']);
        /*
        var month = [];
        var goal = [];
        var monthR = [];
        var real = [];
        for(i = 0; i < Object.keys(data).length; i++) {
        	month[i] = data[i]['YearMonth'];
        	goal[i] = data[i]['goal'];
        	monthR[i] = data[i]['YearMonth2'];
        	real[i] = data[i]['SalesReal'];
        	//replaceUndefined(real[i]);
        	if(typeof(data[i]['SalesReal']) === "undefined"){
		        real[i] = 0;
		        monthR[i] = 0; // return 0 as replace, and end function execution
		    } 
        	// if(typeof data[i]['SalesReal'] !== "undefined") {
        		
        	// }
        	// if(month[i] != data[i]['YearMonth2']) {
        	// 	real[i] = 0;
        	// } else {
        	// 	real[i] = data[i]['SalesReal'];
        	// }

        }
        //remove all
        var monthLength = month.length;
        month = month.filter(function(e){return e});
        
        //var monthx = month.splice(0,month.length);
        //goal = goal.filter(function(e){return e});
        //real = real.filter(function(e){return e});
        var reals = real.slice(month.length, month.length+month.length);
        var monthRs = monthR.slice(month.length, month.length+month.length);
        //monthR = monthR.filter(function(e){return e}); 
        //alert(monthRs);
        var realss = [];
        //var monthRs = [];
        for(i = 0; i < month.length; i++) {
        	if(month[i] !== monthR) {
        		realss[i] = 0;
        	} else {
        		realss[i] = 1;
        	}
        }
        //alert(datax); */

        

		google.charts.load('current', {'packages':['bar']});
	    google.charts.setOnLoadCallback(drawChart);

	    function drawChart() {

	      	var month = ['2001', '2002', '2003', '2004', '2005', '2006', '2007'];
	  		var goal = [4, 3, 6, 6, 3, 8, 4];
	  		var real = [0, 1, 1, 0, 1, 0, 0];

	  		var datas = new google.visualization.DataTable();
			datas.addColumn('string', 'Year Month');
			datas.addColumn('number', 'Goal');
			datas.addColumn('number', 'Real');

			// for(i = 0; i < month.length; i++)
	  //   	datas.addRow([month[i], goal[i], real[i]]);
	  		var yearMonth = [];
	  		var goalValue = [];
	  		var salesReal = [];
			for (i = 0; i < count; i++) {
				// var x_id = $(this).data('id');
				// var idEve = $(this).attr("data-idEve");
				//yearMonth = data[i]['YearMonth'];
				//goalValue = data[i]['goal'];
				yearMonth.push(data[i]['YearMonth']);
				goalValue.push(data[i]['goal']);
			    var table = 'salesreal';
			    var date = data[i]['YearMonth'];

			    var getData = getJson('fetch_single.php');
			    salesReal.push(getData['real']);
			    //datas.addRow([data[0]['YearMonth'], data[0]['goal'], datax[0]['SalesReal']]);
			    
				// $.ajax({
			 //      	url:"fetch_single.php",
			 //      	method:"POST",
			 //      	data:{x_id:x_id,table:table,idEve:idEve,date:date},
			 //      	dataType:"json",
			 //      	success:function(datax)
			 //      	{
			 //      		//alert(yearMonth);
			 //      		for (j = 0; j < Object.keys(datax).length; j++) {
			 //      			//alert(datax.real);
			 //      			salesReal.push(datax.real);
			 //      			//datas.addRow([data[0]['YearMonth'], data[0]['goal'], datax[0]['SalesReal']]);
			 //      		}
			      		
			 //      	}
			 //  	})
				
			}

			function getJson(url) {
	            return JSON.parse($.ajax({
	                url:url,
			      	method:"POST",
			      	data:{x_id:x_id,table:table,idEve:idEve,date:date},
	                dataType: 'json',
	                global: false,
	                async: false,
	                success: function (datax) {
	                    return datax;
	                }
	            }).responseText);
	        }
	        var goalValueX = goalValue.toString().split(',').map(function(el){ return +el;});
	        var salesRealX = salesReal.toString().split(',').map(function(el){ return +el;});
	        //alert(salesRealX);
			for(i = 0; i < month.length; i++)
	    	datas.addRow([yearMonth[i], goalValueX[i], salesRealX[i]]);
			//alert(salesReal);
			// for(i = 0; i < yearMonth.length; i++)
	  //    	datas.addRow([yearMonth[i], goalValue[i], salesReal[i]]);
			
	        var options = {
	          chart: {
	            title: 'Chart',
	            subtitle: 'Sales Goal vs Real',
	          },
	          bars: 'vertical',
	          vAxis: {format: 'decimal'},
	          height: 350,
	          colors: ['#1b9e77', '#d95f02', '#7570b3']
	        };

	        var chart = new google.charts.Bar(document.getElementById('chart_div'));

	        chart.draw(datas, google.charts.Bar.convertOptions(options));

	        //var btns = document.getElementById('btn-group');

	        // btns.onclick = function (e) {

	        //   if (e.target.tagName === 'BUTTON') {
	        //     options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
	        //     chart.draw(datas, google.charts.Bar.convertOptions(options));
	        //   }
	        // }
	     }
      }
    })
});
$(document).on('click', '.delete', function(){
    var x_id = $(this).data("id");
    var table = 'goalsale';
    var idEve = $(this).attr("data-idEve");
    if(confirm("Are you sure you want to delete this?"))
    {
      $.ajax({
        url:"delete.php",
        method:"POST",
        data:{x_id:x_id,table:table,idEve:idEve},
        success:function(data)
        {
          alert(data);
          location.reload();
        }
      });
    }
    else
    {
      return false; 
    }
  });
$(".modal").on("hidden.bs.modal", function(){
    location.reload();
});
</script>
