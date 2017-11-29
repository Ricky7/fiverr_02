<?php
  include "header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel">
        <div class="panel-heading" style="background:#34495e;">
          <h3 class="panel-title" style="color:#fff;">Executive</h3>
        </div>
        <center style="padding-top:10px;">
          <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#userModal" style="background:#34495e;color:#fff;">Add Executive</button>
        </center>

          <div class="table-responsive">
            <table id="user_data" class="table table-bordered table-striped">
              <thead>
                <th>#</th>
                <th>Name</th>
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
          <h4 class="modal-title">Add Executive</h4>
        </div>
        <div class="modal-body">
          <label>Name</label>
          <input type="text" name="name" id="name" class="form-control" />
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
          <input type="hidden" name="table" id="table" value="executive"/>
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
    $('.modal-title').text("Add Executive");
    $('#action').val("Add");
    $('#operation').val("Add");
  });
  var op_id = 'executive';
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
    var status = $('#status').val();
 
    if(name != '' && status != '')
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
    var table = 'executive';
    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{x_id:x_id,table:table},
      dataType:"json",
      success:function(data)
      {
        $('#userModal').modal('show');
        $('#name').val(data.names);        
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
        $('.modal-title').text("Edit Executive");
        $('#x_id').val(x_id);
        $('#action').val("Edit");
        $('#operation').val("Edit");
      }
    })
  });
  
  $(document).on('click', '.delete', function(){
    var x_id = $(this).attr("id");
    var table = 'executive';
    var idf = 'idEjecutive';
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
$(".modal").on("hidden.bs.modal", function(){
    //location.reload();
    $('#name').val('');
    $('#status').prop('selectedIndex',-1);
    $('.modal-title').text("Add Executive");
    $('#x_id').val('');
    $('#action').val("Add");
    $('#operation').val("Add");
});

</script>