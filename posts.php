<?php
require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
$users = [];
$dir = 'cookie_fb';
if ($handle = opendir($dir))
{
	while (false !== ($file = readdir($handle)))
	{
		$users[]['email'] = $file;
	}
	closedir($handle);
}
foreach ($users as $key => $value)
{
	if (!preg_match("/txt/i", $value['email'])) {
	    unset($users[$key]);
	}
	else
	{
		if(file_exists('images_fb/'.$value['email']))
		{
			$users[$key]['image'] = file_get_contents('images_fb/'.$value['email']);
		}
		else
		{
			$users[$key]['image'] = '';
		}
		if(file_exists('sts_fb/'.$value['email']))
		{
			$users[$key]['status'] = file_get_contents('sts_fb/'.$value['email']);
		}
		else
		{
			$users[$key]['status'] = 'fail';
		}
		$users[$key]['email'] = str_replace('.txt','', $users[$key]['email']);
	}
}
$users = array_values($users);
// dump($users);
/*-------------------------------------------Create host-----------------------------------------------*/
$host = 'http://localhost:4444';
$capabilities = DesiredCapabilities::chrome();
/*-------------------------------------------Start process-----------------------------------------------*/
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--no-sandbox', '--disable-gpu', '--disable-notifications']);
$chromeOptions->addArguments(['--headless']); //on | off chrome
/*-------------------------------------------Open chrome-----------------------------------------------*/
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
/*-------------------------------------------Host-----------------------------------------------*/
$driver = RemoteWebDriver::create($host, $capabilities);
$driver->manage()->window()->maximize();
$session = $driver->getSessionID();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Quản lý bài đăng</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="assets/bower_components/jquery-ui/themes/pepper-grinder/jquery-ui.min.css">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/dist/css/common.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
	<!-- jvectormap -->
	<link rel="stylesheet" href="assets/bower_components/jvectormap/jquery-jvectormap.css">
	<link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="assets/dist/css/message.css">
	<!-- fullCalendar -->
	<link rel="stylesheet" href="assets/bower_components/fullcalendar/dist/fullcalendar.min.css">
	<link rel="stylesheet" href="assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
	<!-- Select2 -->
	<link rel="stylesheet" href="assets/bower_components/select2/dist/css/select2.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="assets/dist/css/custom.css">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="assets/plugins/iCheck/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/iCheck/all.css">
</head>
<body>
	<form role="form" id="form-input" method="post" action="login_message.php" enctype="multipart/form-data">
	<section class="content" style="min-height: 0px;">
		<div class="box-body">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<span class="label label-primary bd-r-0">Tài khoản / Mật khẩu</span>
						<textarea class="form-control min-h-text" name="accounts" rows="3" placeholder="" required></textarea>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group min-h-btn p-t-80">
						<a class="btn btn-app p-t-19" id="btn-login">
							<i class="fa fa-play"></i>
						</a>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<span class="label label-primary bd-r-0">Tài khoản đã đăng nhập thành công</span>
						<ul class="list-group">
							<?php foreach($users as $key => $user){ ?>
							<li id="<?php echo $user['email']; ?>" class="list-group-item <?php if($user['status'] == 'fail'){ echo 'bg-gray'; } ?>"><a class="img-ms" data-id="<?php echo $user['email']; ?>" href="javascript:void(0)"><img src="<?php echo $user['image'] ?>" style="width: 18px; height: 18px; border-radius: 50%;"></a> <?php echo $user['email']; ?><a href="javascript:void(0)" class="pull-right del-user">x</a></li>
							<?php } ?>
						</ul>
						<input type="hidden" name="session" value="<?php echo $session; ?>">
					</div>
				</div>
			</div>
		</div>
	</section>
	</form>

	<section id="process" class="content">
		<div class="box box-info">
			<div class="box-header with-border row">
				<div class="col-md-6">
					<i id="icon-processing" class="fa fa-spinner d-none"></i>
					<button type="button" id="btn-refresh" class="btn btn-process">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
				<div class="col-md-6 text-right">
					
				</div>
			</div>
			
			<div class="box-body">
				<select id="option-process" multiple="" class="form-control min-h-200">
				</select>		
			</div>
		</div>
	</section>

	<section class="content">
		<div class="box-body">
			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<th class="text-center" style="width: 30px;">No</th>
					<th class="text-center" style="width: 20%;">Tiêu đề</th>
					<th class="text-center" style="width: 10%;">Giá</th>
					<th class="text-center" style="width: 20%;">Trạng thái - Đăng lúc</th>
					<th class="text-center" style="width: 20%;">Lượt xem</th>
					<th class="text-center" style="width: 20%;">Thông tin bài đăng</th>
					<th class="text-center" style="width: 10%;">Hành động</th>
				</thead>
				<tbody class="bt-data">
					
				</tbody>
			</table>
		</div>
	</section>
	<div class="modal modal-full fade" id="dialogSearchLoading" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content align-items-center justify-content-center">
				<img src="assets/dist/img/loading.gif" alt="Loading" class="img-fluid">        
			</div>
		</div>
	</div>

	<!-- jQuery 3 -->
	<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- FastClick -->
	<script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
	<!-- Sparkline -->
	<script src="assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
	<!-- jvectormap  -->
	<script src="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<!-- SlimScroll -->
	<script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<!-- ChartJS -->
	<script src="assets/bower_components/Chart.js-2.9.3/Chart.js"></script>
	<!-- Select2 -->
	<script src="assets/bower_components/select2/dist/js/select2.full.min.js"></script>
	<!-- AdminLTE App -->
	<script src="assets/dist/js/adminlte.min.js"></script>
	<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

	<script src="assets/dist/js/jquery.validate.min.js"></script>
	<script src="assets/dist/js/additional-methods.min.js"></script>
	<script type="text/javascript">
		var sts = {success: 'label-default', fail: 'label-danger', login_fail: 'label-danger', warn: 'label-warning'};
	</script>
	<script type="text/javascript">
		$( document ).ready(function() {
			$('#btn-login').click(function(){
				let accounts = multipleLinesToArray($('form#form-input :input[name="accounts"]').val());
				if(accounts.length > 0)
				{
					$('#dialogSearchLoading').modal('show');
				}
				
				for (let i = 0; i < accounts.length; i++)
		    	{
		    		user = {email: 'a', pass: 'a', check: '0'};
		    		let tmp = splitEmailPass(accounts[i]);
		    		if(typeof tmp[0] !== 'undefined')
		    		{
		    			user.email = tmp[0];
		    		}
		    		if(typeof tmp[1] !== 'undefined')
		    		{
		    			user.pass = tmp[1];
		    		}
		    		if(typeof tmp[2] !== 'undefined')
		    		{
		    			user.check = tmp[2];
		    		}

		    		$.ajax({
			            type: "post",
			            dataType: "json",
			            data: user,
			            url:"http://localhost/auto-fb/login_fb.php",
			            beforeSend: function(xhr) {
			                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			            },
			            success: function (data) {
			            	$('#option-process').append('<option class="label label-process m-t-3 '+sts[data['message'].status]+'">'+data['message'].msg+'</option>');
			            	var ss = document.getElementById(data['info']['email']);
			            	if(data['message']['status'] == 'success')
			            	{
			            		if(ss != null)
			            		{
			            			ss.remove();
			            		}
			            		$('.list-group').append('<li id="'+data['info']['email']+'" class="list-group-item"><a class="img-ms" data-id="'+data['info']['email']+'" href="javascript:void(0)"><img src="'+data['info']['image']+'" style="width: 18px; height: 18px; border-radius: 50%;"></a> '+data['info']['email']+'<a href="javascript:void(0)" class="pull-right del-user">x</a></li>');
			            	}
			            	if(i == accounts.length - 1)
			            	{
			            		$('#dialogSearchLoading').modal('hide');
			            	}
			            },
			            error: function (XMLHttpRequest, textStatus, errorThrown) {
			            	$('#option-process').append('<option class="label label-process m-t-3 label-danger">'+user.email+' - Xảy ra lỗi với người dùng này</option>');
			            	if(i == accounts.length - 1)
			            	{
			            		$('#dialogSearchLoading').modal('hide');
			            	}
			            }
			        });
		    	}

			});
			$(document).on("click", ".del-user", function () {
				$(this).parent().remove();
			});

			$(document).on("click", ".img-ms", function () {
				$('#dialogSearchLoading').modal('show');
				let session = $('input[name="session"]').val();
				let email = $(this).data('id');

				$.ajax({
			            type: "post",
			            dataType: "json",
			            data: {},
			            url:"http://localhost/auto-fb/process_posts.php?session="+session+"&email="+email,
			            beforeSend: function(xhr) {
			                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			            },
			            success: function (data) {
			            	for(let x = 0; x < data['message'].length; x++)
			            	{
			            		if(data['message'][x].status == 'login_fail')
			            		{
			            			document.getElementById(email).classList.add("bg-gray");
			            		}
			            		$('#option-process').append('<option class="label label-process m-t-3 '+sts[data['message'][x].status]+'">'+data['message'][x].msg+'</option>');
			            	}
			            	renderTable(data['data'], email);
			            	$('#dialogSearchLoading').modal('hide');
			            },
			            error: function (XMLHttpRequest, textStatus, errorThrown) {
			            	$('#option-process').append('<option class="label label-process m-t-3 label-danger">'+email+' - Xảy ra lỗi với người dùng này</option>');
			            	$('#dialogSearchLoading').modal('hide');
			            }
			        });
			});



			$(document).on("click", "#btn-refresh", function (){
				$('#option-process').empty();
			});

			function renderTable(data, email)
			{
				$('.bt-data').empty();
				let i = 0;
		        while(i < data.length)
		        {
		            var item = data[i];
		            var action = '<a href="javascript:void(0)" class="del-post" data-id="'+i+'" data-email="'+email+'" data-title="'+item['title']+'">Xóa</a> <br>';
		            if(item['renew'] == 1)
		            {
		            	action += ' <a href="javascript:void(0)" class="renew-post" data-id="'+i+'" data-email="'+email+'" data-title="'+item['title']+'">'+item['renew_text']+'</a>';
		            }
		            else
		            {
		            	action+= ''+item['renew_text'];
		            }
		            if(item['delete'] == 0)
		            {
		            	action = 'Không tác động được';
		            }
		            var str =	'<tr>'
		                            +'<td class="text-center">'+(i+1)+'</td>'
		                            +'<td>'+item['title']+'</td>'
		                            +'<td>'+item['price']+'</td>'
		                            +'<td>'+item['status']+'</td>'
		                            +'<td>'+item['view']+'</td>'
		                            +'<td>'+item['info']+'</td>'
		                            +'<td class="text-center">'+action+'</td>'
		                        +'</tr>';
		            $('.bt-data').append(str);
		            i++;
		        }
			}

			$(document).on("click", ".renew-post", function (){
				$('#dialogSearchLoading').modal('show');
				let id = $(this).data('id');
				let session = $('input[name="session"]').val();
				let email = $(this).data('email');
				let title = $(this).data('title');
				$.ajax({
		            type: "post",
		            dataType: "json",
		            data: {id: id, session: session, email: email, title: title},
		            url:"http://localhost/auto-fb/post_renew.php",
		            beforeSend: function(xhr) {
		                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		            },
		            success: function (data) {
		            	for(let x = 0; x < data['message'].length; x++)
		            	{
		            		$('#option-process').append('<option class="label label-process m-t-3 '+sts[data['message'][x].status]+'">'+data['message'][x].msg+'</option>');
		            	}
		            	renderTable(data['data'], email);
		            	$('#dialogSearchLoading').modal('hide');
		            },
		            error: function (XMLHttpRequest, textStatus, errorThrown) {
		            	$('#option-process').append('<option class="label label-process m-t-3 label-danger">'+email+' - Xảy ra lỗi với người dùng này</option>');
		            	$('#dialogSearchLoading').modal('hide');
		            }
		        });
			});

			$(document).on("click", ".del-post", function (){
				$('#dialogSearchLoading').modal('show');
				let id = $(this).data('id');
				let session = $('input[name="session"]').val();
				let email = $(this).data('email');
				let title = $(this).data('title');
				$.ajax({
		            type: "post",
		            dataType: "json",
		            data: {id: id, session: session, email: email, title: title},
		            url:"http://localhost/auto-fb/post_delete.php",
		            beforeSend: function(xhr) {
		                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		            },
		            success: function (data) {
		            	for(let x = 0; x < data['message'].length; x++)
		            	{
		            		$('#option-process').append('<option class="label label-process m-t-3 '+sts[data['message'][x].status]+'">'+data['message'][x].msg+'</option>');
		            	}
		            	renderTable(data['data'], email);
		            	$('#dialogSearchLoading').modal('hide');
		            },
		            error: function (XMLHttpRequest, textStatus, errorThrown) {
		            	$('#option-process').append('<option class="label label-process m-t-3 label-danger">'+email+' - Xảy ra lỗi với người dùng này</option>');
		            	$('#dialogSearchLoading').modal('hide');
		            }
		        });
			});

		});
		
	</script>
	<script type="text/javascript">
		function multipleLinesToArray(value)
		{
			var lines = value.split(/\n/);
			var output = [];
			var outputText = [];
			for (var i = 0; i < lines.length; i++) {

				if (/\S/.test(lines[i])) {
					outputText.push('"' + $.trim(lines[i]) + '"');
					output.push($.trim(lines[i]));
				}
			}
			return output;
		}

		function splitEmailPass(string)
		{
			string = string+'';
			var lines = string.split(" ");
			for(let a = 0; a < lines.length; a++)
			{
				if(lines[a] == " ")
				{
					lines.splice(a, 1);
				}
			}
			return lines;
		}
	</script>
</body>
</html>