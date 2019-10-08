<?php
require_once ('config.php');
session_start(); 
$noerror = false;
$username = '';
$password = '';
if (($_SERVER['REQUEST_METHOD'] === 'POST') && !isset($_POST['action'])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
}else{
  if(!empty($_SESSION['username']) &&
     !empty($_SESSION['password'])){
    $username = $_SESSION["username"];
    $password = $_SESSION["password"];
  }else{
    header('Location: index.php');
    die();
  }
}
try {
  $conn = new mysqli(MYSQL_SERVER, $username, $password, MYSQL_DATABASE, MYSQL_PORT);
  if ($conn->connect_error) {
    header('Location: index.php');
    die();
  }
  $_SESSION["username"] = $username;
  $_SESSION["password"] = $password;
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: ondemand.php');
  }
  $noerror = true;
} catch (Exception $e ) {
 exit;
}
if($noerror){
  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
      $pageno = 1;
  }
  if (isset($_GET['sortby'])) {
    switch ($_GET['sortby']) {
      case 'id':
        $sortby = 'id';
        break;
      case 'name':
        $sortby = 'stream_display_name';
        break;
      case 'category':
        $sortby = 'category_id';
        break;
      case 'added':
        $sortby = 'added';
        break;
      case 'ondemand':
        $sortby = 'on_demand';
        break;
      default:
        $sortby = 'id';
        break;
    }
    $_SESSION['sortby'] = $sortby;
  } else {
    if (isset($_SESSION['sortby'])) {
      $sortby = $_SESSION['sortby'];
    }else{
      $_SESSION['sortby'] = 'id';
      $sortby = 'id';
    }
  }
  if (isset($_GET['sortorder'])) {
    $sortorder = ($_GET['sortorder']==1)?'ASC':'DESC';
    $_SESSION['sortorder'] = $sortorder;
  } else {
    if (isset($_SESSION['sortorder'])) {
      $sortorder = $_SESSION['sortorder'];
    }else{
      $_SESSION['sortorder'] = 'ASC';
      $sortorder = 'ASC';
    }
  }
if (isset($_GET['action'])) {
  $action = $_GET['action'];
  $streamId = isset($_GET['id'])?$_GET['id']:'0';
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $url = strtok($actual_link, '?');
  $parts = parse_url($actual_link);
  parse_str($parts['query'], $query);
  if (isset($query['pageno'])) {
    $url = $url.'?pageno='.$query['pageno'];
  }
  if (strcmp($action,"enable")==0){
    $sql = "UPDATE streams SET on_demand=1 WHERE id=$streamId";
    $conn->query($sql);
    header('Location: '.$url);
    die();
  }else if (strcmp($action,"disable")==0){
    $sql = "UPDATE streams SET on_demand=0 WHERE id=$streamId";
    $conn->query($sql);
    header('Location: '.$url);
    die();
  }
}else {
    if (isset($_POST['action'])) {
      $action=$_POST['action'];
      //die();
      $list = "(".implode(', ', $_POST['tick']).")"; 
      $sql = "UPDATE streams SET on_demand=".(strcmp($action, "enable")==0?1:0)." WHERE id IN $list";
      $conn->query($sql);
      //die();
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $url = strtok($actual_link, '?');
      $parts = parse_url($actual_link);
      parse_str($parts['query'], $query);
      if (isset($query['pageno'])) {
        $url = $url.'?pageno='.$query['pageno'];
      }
      if (strcmp($action,"enable")==0){
        $sql = "UPDATE streams SET on_demand=1 WHERE id=$streamId";
        $conn->query($sql);
        header('Location: '.$url);
        die();
      }else if (strcmp($action,"disable")==0){
        $sql = "UPDATE streams SET on_demand=0 WHERE id=$streamId";
        $conn->query($sql);
        header('Location: '.$url);
        die();
      }
    }else{
      $action = 'none';
    }
}

$no_of_records_per_page = RECORDS_PER_PAGE;
  $offset = ($pageno-1) * $no_of_records_per_page; 
  if ($offset<0){
    $offset = 0;
  }
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://getbootstrap.com/docs/4.0/assets/img/favicons/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <title>Xtream Code - On-Demand</title>

    <!-- Bootstrap core CSS -->
    <link href="./dashboard_files/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./dashboard_files/dashboard.css" rel="stylesheet">


  <style type="text/css">/* Chart.js */
#main {
  margin-bottom: 10px;
}
h2 {
  margin: 0;
    display: inline-block;
}
#stbutton, #debutton, #enbutton, #stbutton, #spbutton {
  float: right;
  margin-left: 10px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
  float:right;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input.default:checked + .slider {
  background-color: #444;
}
input.primary:checked + .slider {
  background-color: #2196F3;
}
input.success:checked + .slider {
  background-color: #8bc34a;
}
input.info:checked + .slider {
  background-color: #3de0f5;
}
input.warning:checked + .slider {
  background-color: #FFC107;
}
input.danger:checked + .slider {
  background-color: #32cd32;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}
@-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style></head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Xtreme Codes</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="./index.php?action=logout">Logout</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                  Streams <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./service.php">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                  Service
                </a>
              </li>
            </ul>
          </div>
        </nav>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm</h2>
            </div>
            <div class="modal-body">
                Do you really want to stop On-Demand service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok" href="?action=stop">Yes</a>
            </div>
        </div>
    </div>
</div>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>

          
          <div id="main">
            <h2>On Demand - Streams</h2> <button id="debutton" class="btn btn-secondary">Disable</button> <button id="enbutton" class="btn btn-primary">Enable</button>
          </div>
          <form id="form" method="POST" action="">
          <input type="hidden" name="action" id="action"/>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th><input id="allcb" type="checkbox"></th>
                  <th><a href="?sortby=id&sortorder=<?=strcmp($sortby, 'id')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">Id <i class="fa fa-angle-<?=strcmp($sortby, 'id')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                  <th><a href="?sortby=name&sortorder=<?=strcmp($sortby, 'stream_display_name')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">Name <i class="fa fa-angle-<?=strcmp($sortby, 'stream_display_name')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                  <th><a href="?sortby=category&sortorder=<?=strcmp($sortby, 'category_id')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">Category <i class="fa fa-angle-<?=strcmp($sortby, 'category_id')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                  <th><a href="?sortby=type&sortorder=<?=strcmp($sortby, 'type')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">Type <i class="fa fa-angle-<?=strcmp($sortby, 'type')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                  <th><a href="?sortby=added&sortorder=<?=strcmp($sortby, 'added')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">Added On <i class="fa fa-angle-<?=strcmp($sortby, 'added')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                  <th><a href="?sortby=ondemand&sortorder=<?=strcmp($sortby, 'on_demand')==0?(strcmp($sortorder, 'ASC')==0?2:1):1?>">On Demand <i class="fa fa-angle-<?=strcmp($sortby, 'on_demand')==0?(strcmp($sortorder, 'ASC')==0?'up':'down'):'none'?>"></i></a></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  //writeValue('sessionid', session_id());
		  //echo getValue('sessionid');
		  //writeValue('sessionid', 'asas212121');
                  $total_pages_sql = "SELECT COUNT(*) FROM streams WHERE type = 1";
                  $result = mysqli_query($conn,$total_pages_sql);
                  $total_rows = mysqli_fetch_array($result)[0];
                  $total_pages = ceil($total_rows / $no_of_records_per_page);
                  $sql = "SELECT id, category_name FROM stream_categories WHERE category_type = 'live'";
                  $result = $conn->query($sql);
                  $categories = [];
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                      $categories[$row['id']] = $row['category_name'];
                    }
                  }
                  $sql = "SELECT id, stream_display_name, type, category_id, added, on_demand FROM streams WHERE type = 1 ORDER BY $sortby $sortorder LIMIT $offset, $no_of_records_per_page";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                ?>
                <tr>
                  <td><input id="tick" name="tick[]" value="<?=$row['id'] ?>" type="checkbox"></td>
                  <td><?=$row['id'] ?></td>
                  <td><a href="./updateondemand.php?server=<?=$row['id'] ?>"><?=$row['stream_display_name'] ?></a></td>
                  <td><?=$categories[$row['category_id']] ?></td>
                  <?php
                    switch ($row['type']) {
                        case 1:
                  ?>
                  <td>Live Channel</td>
                  <?php
                            break;
                        case 2:
                  ?>
                  <td>Movie</td>
                  <?php
                            break;
                    }
                  ?>                  
                  <td><?=date("M-d-Y", $row['added']);?></td>
                  <td>
                  <label class="switch">
                    <input id="sth<?=$row['id'] ?>" type="checkbox" class="danger" <?=$row['on_demand']==1?'checked':''?>>
                    <span class="slider round"></span>
                  </label>
                  </td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
              <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                  <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
              </li>
              <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                  <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
              </li>
              <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
          </ul>
          <form>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./dashboard_files/jquery-3.2.1.slim.min.js.download" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="./dashboard_files/popper.min.js.download"></script>
    <script src="./dashboard_files/bootstrap.min.js.download"></script>

    <!-- Icons -->
    <script src="./dashboard_files/feather.min.js.download"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="./dashboard_files/Chart.min.js.download"></script>
    <script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#allcb').change(function(){
    if($(this).prop('checked')){
        $('table tbody tr td input[id="tick"]').each(function(){
            $(this).prop('checked', true);
        });
    }else{
        $('table tbody tr td input[id="tick"]').each(function(){
            $(this).prop('checked', false);
        });
    }
});
$("#debutton").click(function(){
    // $.each($("input[id='tick']:checked"), function(){
    $('#action').val('disable');
    //     $('#form').submit();
    // });
    $('#form').submit();
});
$("#enbutton").click(function(){
    // $.each($("input[id='tick']:checked"), function(){
    $('#action').val('enable');
    //     $('#form').submit();
    // });
    $('#form').submit();
});
$('.danger').change(function(){
    var res = $(this).prop('id').substring(3);
    var url = updateURLParameter(window.location.href, 'id', res);
    if($(this).prop('checked')){
      url = updateURLParameter(url, 'action', 'enable');
      window.location.href = url;
    }else{
      url = updateURLParameter(url, 'action', 'disable');
      window.location.href = url;
    }
});

function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

    </script>
</body></html>
<?php
}else{
  header('Location: index.php');
  die();
}
?>
