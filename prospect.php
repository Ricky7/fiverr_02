<?php
  require_once "class/DB.php";
  require_once "class/Operation.php";

  $opt = new Operation($db);
  $bl_data = $opt->getBusinessline();
  $eve_data = $opt->getEvent();
  $exe_data = $opt->getExecutive();

  include "header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel">
        <div class="panel-heading" style="background:#34495e;">
          <h3 class="panel-title" style="color:#fff;">Prospect</h3>
        </div>
        <center style="padding-top:10px;">
          <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#userModal" style="background:#34495e;color:#fff;">Add Prospect</button>
        </center>

          <div class="table-responsive">
            <table id="user_data" class="table table-bordered table-striped">
              <thead>
                <th>#</th>
                <th>Name</th>
                <th>Company</th>
                <th>Telephone</th>
                <th>Whatsapp</th>
                <th>Facebook</th>
                <th>Email</th>
                <th>Webpage</th>
                <th>Business Line</th>
                <th>Event</th>
                <th>Executive</th>
                <th>Business Other</th>
                <th>Create Date</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>

        </div>
    </div>
  </div>
</div>

<div id="userModal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="user_form" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Event</h4>
        </div>
        <div class="modal-body">
          <label>Name</label>
          <input type="text" name="name" id="name" class="form-control" required/>
          <br />
          <label>Company</label>
          <input type="text" name="comp" id="comp" class="form-control" required/>
          <br />
          <label>Telephone</label>
          <input type="text" name="telp" id="telp" class="form-control" required/>
          <br />
          <label>WhatsApp</label>
          <input type="text" name="w_app" id="w_app" class="form-control" required/>
          <br />
          <label>Facebook</label>
          <input type="text" name="fb" id="fb" class="form-control" required/>
          <br />
          <label>Email</label>
          <input type="text" name="email" id="email" class="form-control" required/>
          <br />
          <label>Webpage</label>
          <input type="text" name="web" id="web" class="form-control" required/>
          <br />
          <label>Business Line</label>
          <select class="form-control" name="b_line" id="b_line" required>
            <option></option>
            <?php foreach ($bl_data as $value): ?>
              <option value="<?php echo $value['idBusinessline']; ?>"><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <br />
          <label>Business Other</label>
          <input type="text" name="b_other" id="b_other" class="form-control" />
          <br />
          <label>Event</label>
          <select class="form-control" name="eve" id="eve" required>
            <option></option>
            <?php foreach ($eve_data as $value): ?>
              <option value="<?php echo $value['idEvent']; ?>"><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <br />
          <label>Executive</label>
          <select class="form-control" name="exe" id="exe" required>
            <option></option>
            <?php foreach ($exe_data as $value): ?>
              <option value="<?php echo $value['idEjecutive']; ?>"><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <br />
          <label>Status</label>
          <select class="form-control" name="status" id="status" required>
            <option></option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="x_id" id="x_id"/>
          <input type="hidden" name="operation" id="operation"/>
          <input type="hidden" name="table" id="table" value="prospect"/>
          <input type="submit" name="action" id="action" class="btn btn-success" value="Add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
  $('#operation').val("Add");
  $('#add_button').click(function(){
    $('#user_form')[0].reset();
    $('.modal-title').text("Add User");
    $('#action').val("Add");
    $('#operation').val("Add");
  });
  var op_id = 'prospect';
  var dataTable = $('#user_data').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"fetch.php",
      type:"POST",
      data:{op_id:op_id},
      dataType:"json"
    },
    "columnDefs":[
      {
        "targets":[0, 3, 4],
        "orderable":false,
      },
    ],

  });

  $(document).on('submit', '#user_form', function(event){
    event.preventDefault();
    var name = $('#name').val();
    var comp = $('#comp').val();
    var telp = $('#telp').val();
    var w_app = $('#w_app').val();
    var fb = $('#fb').val();
    var email = $('#email').val();
    var web = $('#web').val();
    var b_line = $('#b_line').val();
    var eve = $('#eve').val();
    var exe = $('#exe').val();
    var b_other = $('#b_other').val();
    var status = $('#status').val();
 
    if(name != '' && comp != '' && telp != '' && b_line != '')
    {
      $.ajax({
        url:"insert.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data)
        {
          alert(data);
          $('#user_form')[0].reset();
          $('#userModal').modal('hide');
          dataTable.ajax.reload();
        }
      });
    }
    else
    {
      alert("All Fields are Required");
    }
  });
  
  $(document).on('click', '.update', function(){
    var x_id = $(this).attr("id");
    var table = 'prospect';
    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{x_id:x_id,table:table},
      dataType:"json",
      success:function(data)
      {
        $('#userModal').modal('show');
        $('#b_line').prop('selectedIndex',-1);
        $('#name').val(data.names);
        $('#comp').val(data.company);
        $('#telp').val(data.telephone);
        $('#w_app').val(data.whatsapp);
        $('#fb').val(data.facebook);
        $('#email').val(data.email);
        $('#web').val(data.webpage);
        //$('#b_line').append('<option value="'+data.idBL+'" selected>'+data.bname+'</option>');
        $("#b_line").val(data.idBL).change();
        $('#eve').html('');
        $('#eve').append('<option value="'+data.idEve+'" selected>'+data.evename+'</option>');
        $('#exe').html('');
        $('#exe').append('<option value="'+data.idExe+'" selected>'+data.exename+'</option>');
        $('#b_other').val(data.bOther);
        
        $('#status').html('');
        if(data.stats == 1){
          statx = 'Active';
          $('#status').append(
            '<option value="'+data.stats+'" selected>'+statx+'</option>',
            '<option value="0">Inactive</option>'
          );
        } else {
          statx = 'Inactive';
          $('#status').append(
            '<option value="'+data.stats+'" selected>'+statx+'</option>',
            '<option value="1">Active</option>',
          );
        }
        $('.modal-title').text("Edit Prospect");
        $('#x_id').val(x_id);
        $('#action').val("Edit");
        $('#operation').val("Edit");
      }
    })
  });
  
  $(document).on('click', '.delete', function(){
    var x_id = $(this).attr("id");
    var table = 'prospect';
    var idf = 'idProspect';
    if(confirm("Are you sure you want to delete this?"))
    {
      $.ajax({
        url:"delete.php",
        method:"POST",
        data:{x_id:x_id,table:table,idf:idf},
        success:function(data)
        {
          alert(data);
          dataTable.ajax.reload();
        }
      });
    }
    else
    {
      return false; 
    }
  });
  

});
$('#userModal').on('hide.bs.modal', function (e) {
    location.reload();
    //$(this).find("input,textarea,select").val('').end();
    $('.modal-title').text("Add Event");
    $('#x_id').val('');
    $('#action').val("Add");
    $('#operation').val("Add");
});

$('#event_start,#event_finish').datetimepicker({
  dateFormat: 'yy-mm-dd',
  timeFormat: 'HH:mm:ss',
  stepHour: 1,
  stepMinute: 1,
  stepSecond: 10
});
</script>