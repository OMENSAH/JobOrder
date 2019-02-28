<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo TAGLINE?></title>
    <link href="<?php echo BASE_URL?>templates/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL?>templates/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href=" <?php echo BASE_URL?>templates/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href=" <?php echo BASE_URL?>templates/css/dataTables.bootstrap.min.css">
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
  </head>
  <body id="body">
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo BASE_URL?>dashboard.php"><?php echo TAGLINE?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
         
          <ul class="nav navbar-nav navbar-right">
           <?php if($isLoggedIn): ?>
            <li><a href="#">Welcome, <?php echo  $_SESSION['name'];?></a></li>
            <li><a href="logoutUser.php">Logout</a></li>
           <!-- <li><a type="button" data-toggle="modal" data-target="#addPass">Change Password</a> -->
          <?php endif;?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <header id="header">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <h1><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Dashboard</h1>
          </div>
          <div class="col-md-2">
            <?php if(getUserType() == ADMIN || getUserType() == RECEPTIONIST )  : ?>
              <div class="dropdown create">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Perform Task
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                  
                  <?php if(getUserType() == ADMIN): ?>
                    <li><a type="button" data-toggle="modal" data-target="#addUser">Add User</a>
                    <!-- <li><a type="button" data-toggle="modal" data-target="#addUserType">Add UserType</a> -->
                    <li><a type="button" data-toggle="modal" data-target="#addDepartment">Add Department</a>
                  <?php endif?>

                  <?php if(getUserType() == RECEPTIONIST): ?>
                    <li><a type="button" data-toggle="modal" data-target="#addTask">Add Job</a>
                  <?php endif?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </header>
    <?php displayMessage();?>
    <section id="main">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <!-- Website Overview -->
            <div class="panel panel-default">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Overview</h3>
              </div>
              <div class="panel-body">
              <?php if(getUserType() == ADMIN): ?>
                <div class="col-md-6">
                  <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span></h2>
                    <h4> <?php echo sizeof($users);?> Users</h4>
                  </div>
                </div>
              <?php endif; ?> 
                <div class="col-md-6">
                  <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 
                    <h4> <?php echo sizeof($tasks);?> Printing Tasks</h4>
                  </div>
                </div>
              </div>
              </div>

              <!-- Latest Users -->
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Activity Logs</h3>
                </div>
                <div class="panel-body">
                 <form  method="post" >
                    <table class="table table-bordered" id="documentTable">
                      <thead>
                        <tr>
                          <th>Client's Name</th>
                          <th>Task Identifier</th>
                          <th>Files</th>
                          <th>Job Description</th>
                          <th>Number of Job Copies</th>
                          <th>Assigned To</th>
                          <th>Submitted Date</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($tasks as $task):?>
                           <?php 
                                if($task->files){
                                  $files = explode(SEPARATOR, $task->files);
                                  $filesData = null ;
                                  $url = array();
                                  foreach($files as $file){
                                    $url =  BASE_URL . "uploads/".$file;
                                    $urls[] = $url;
                                    $filesData .= "<a href='$url' target='_blank'>. $file</a><br/><br/>";
                                  }
                                }
                            ?>
                          <tr>
                              <td><?php echo $task->owner; ?></td>
                              <td id="identifier">
                                  <?php echo $task->task_identifier ?>
                              </td>
                              <td><?php echo $filesData; ?></td>
                              <td><?php echo $task->description ?></td>
                              <td><?php echo $task->copies ?></td>
                              <td><?php echo $task->assignTo ?></td>
                              <td><?php echo dateFormat($task->submitted_date); ?></td>
                              <td>
                                  <?php if(getUserType() == RECEPTIONIST): ?>
                                      <button 
                                        id="uTask"
                                        type="button" 
                                        class="btn btn-primary"
                                        data-id ="<?php echo $task->id?>"
                                        data-owner ="<?php echo $task->owner?>"
                                        data-description ="<?php echo $task->description?>"
                                        data-copies ="<?php echo $task->copies?>"
                                        data-files ="<?php echo $task->files?>"
                                        data-assign ="<?php echo $task->assignTo?>"
                                        data-toggle="modal" data-target="#updateTask"
                                        >Edit</button> <br/>
                                      <button 
                                        id ="delete"
                                        type="button" 
                                        class="btn btn-danger"
                                        data-id ="<?php echo $task->id?>"
                                        data-toggle="modal" data-target="#deleteTask"
                                        data-files ="<?php echo $task->files?>"
                                      >Delete</button> <br/>  
                                      <button type="button" class="btn btn-warning" onclick="printJS({ printable: 'identifier', type:'html', header: 'Task Identification Number'})">Print Task Id</button><br/>                                 
                                  <?php endif?>
                              
                                  <?php if($task->assignTo == $_SESSION['department']): ?>
                                    <?php for($i= 0; $i< sizeof($urls); $i++ ){?>
                                        <button type="button" class="btn btn-primary" onclick='printJS("<?php echo  $urls[$i]?>")'>Print doc<?php echo $i +1?></button><br/>   
                                    <?}; ?>                                  
                                  <?php endif?>

                              </td>
                          </tr>
                        <?php endforeach;?>                         
                      </tbody>
                    </table>
                </form>
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>


    <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Add a New User</h4>
        </div>
        <div class="modal-body">
        <form method="post" action="addUser.php">
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" class="form-control" placeholder="Full Name" required name="name">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" placeholder="Email" required name="email">
          </div>
          <div class="form-group">
            <label>PassWord</label>
            <input type="password" class="form-control" placeholder="Password" required name="password">
          </div>
          <div class="form-group">
            <label>Confirm PassWord</label>
            <input type="password" class="form-control" placeholder="Confirm Password " required name="confirm_password">
          </div>
          <div class="form-group">
            <label for="sel1">Department</label>
            <select class="form-control" name="department">
              <option value = "">Select Department</option>
              <?php foreach($departments as $department):?>
                  <option value = "<?php echo $department->name?>"><?php echo $department->name?></option>
              <?php endforeach;?>   
            </select>
          </div>
          <div class="form-group">
            <label for="sel1">User Type</label>
            <select class="form-control" name="userType">
              <option value = "">User Type</option>
              <option value = "<?php echo ADMIN?>"><?php echo ADMIN?></option>
              <option value = "<?php echo RECEPTIONIST?>"><?php echo RECEPTIONIST?></option>
            </select>
          </div>
          
           <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          <button type="submit" class="btn btn-primary" name="addUser">Add Client</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Send Document(s)</h4>
        </div>
        <div class="modal-body">
        <form method="post" action="addTask.php"  enctype="multipart/form-data">
          <div class="form-group">
            <label>Document Owner</label>
            <input  class="form-control" placeholder="Document Owner" required name="owner" />
          </div>
          <div class="form-group">
            <label>Document Description</label>
            <textarea  class="form-control" placeholder="Document Description" required name="description" cols="40" rows="5"></textarea>
          </div>
          <div class="form-group">
            <label>Number of Copies</label>
            <input type="number" class="form-control" placeholder="Number of Copies" required name="copies">
          </div>
          <div class="form-group">
            <label>File(s)</label>
            <input type="file" name="file_array[]"  multiple="multiple" required>
          </div>
          <div class="form-group">
            <label for="sel1">Assign To</label>
            <select class="form-control" name="department" required>
            <option value = "">Select Department</option>
              <?php foreach($departments as $department):?>
                  <option value = "<?php echo $department->name?>"><?php echo $department->name?></option>
              <?php endforeach;?>   
            </select>
          </div>
           <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          <button type="submit" class="btn btn-primary" name="addTask">Save</button>
          </form>
           <ul class="list-group" id="fileList"></ul>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Add Department<i>(Department can be Graphic Department, etc.)</i></h4>
        </div>
        <div class="modal-body">
        <form method="post" action="addDepartment.php">
          <div class="form-group">
            <label>Department Name</label>
            <input type="text" class="form-control" required name="name">
          </div>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="addDepartment">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Update</h4>
        </div>
        <div class="modal-body">
        <form method="post" action="addTask.php"  enctype="multipart/form-data">
          <div class="form-group">
            <label>Document Owner</label>
            <input  class="form-control" placeholder="Document Owner" id="owner" required name="owner" />
          </div>
          <div class="form-group">
            <label>Document Description</label>
            <textarea  class="form-control" placeholder="Document Description"  id="description" required name="description" cols="40" rows="5"></textarea>
          </div>
          <div class="form-group">
            <label>Number of Copies</label>
            <input type="number" class="form-control" placeholder="Number of Copies"  id="copies" required name="copies">
          </div>
          <div class="form-group">
            <label>File(s)</label>
            <input type="file" name="file_array[]"  multiple="multiple" id ="files" required>
          </div>
          <div class="form-group">
            <label for="sel1">Assign To</label>
            <select class="form-control" name="department" required id="assign">
            <option value = "">Select Department</option>
              <?php foreach($departments as $department):?>
                  <option value = "<?php echo $department->name?>"><?php echo $department->name?></option>
              <?php endforeach;?>   
            </select>
          </div>
           <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          <button type="submit" class="btn btn-primary" name="addTask">Save</button>
          </form>
           <ul class="list-group" id="fileList"></ul>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Change Password</h4>
        </div>
        <div class="modal-body">
        <form method="post" action="adminChangePassword.php">
          <div class="form-group">
            <label>Old Password</label>
            <input type="password" class="form-control"  required name="p0">
            <input type="hidden" value="<?php echo $admin->password?>" name="hidden">
          </div>
          <div class="form-group">
            <label>New Password</label>
            <input type="password" class="form-control" required name="p1">
          </div>
          <div class="form-group">
            <label>Confirm  New Password</label>
            <input type="password" class="form-control"  required name="p2">
          </div>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="do_change">Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Deleting</i></h4>
        </div>
        <div class="modal-body">
        <form method="post" action="deleteTask.php">
          <div class="form-group">
            <label>Are you sure you want to delete this</label>
            <input type="text" class="form-control" required name="id" id="id" >
            <input type="text" class="form-control" required name="files" id="files" >
          </div>
          <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
          <button type="submit" class="btn btn-danger" name="deleteTask">Yes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="<?php echo BASE_URL?>templates/js/bootstrap.min.js"></script>
 <script src=" <?php echo BASE_URL?>templates/js/bootstrap-select.min.js"></script>
 <script src=" <?php echo BASE_URL?>templates/js/jquery.dataTables.min.js"></script>
 <script src=" <?php echo BASE_URL?>templates/js/dataTables.bootstrap.min.js"></script>

 <script type="text/javascript">
$(document).ready(function() {
  $('#documentTable').dataTable( {
    "bSort": false,
    destroy: true,
    });

    $(".alert").delay(4000).fadeOut();

    // setInterval(function () {
		// 		$('#body').load('dashboard.php')
		// 	}, 3000);

    $('#uTask').click(function () {
            $('#owner').val($(this).data("owner"));
            $('#description').val($(this).data("description"));
            $('#copies').val($(this).data("copies"));
            // $('#files').val($(this).data("files"));
            $('#assign').val($(this).data("assign")).prop('selected', true)
            $('#editStockingOnboarding').attr("action", $(this).data("href"));
        });
  });

  $('#delete').click(function () {
    $('#id').val($(this).data("id"));
    $('#files').val($(this).data("files"));
  });

</script>
  </body>
</html>
