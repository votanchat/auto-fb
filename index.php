<?php
require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;


// get user
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

// get input
$inputs = [];
if(file_exists('input.txt'))
{
	$inputs = file_get_contents('input.txt');
	$inputs = unserialize($inputs);
}

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
$session = $driver->getSessionID();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Đăng bài</title>

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
	<section class="content" style="min-height: 0px;">
		<a href="messenger.php" class="btn btn-primary" target="_blank">Tin nhắn</a>
		<a href="posts.php" class="btn btn-primary" target="_blank">Xóa bài</a>
		<a href="group.php" class="btn btn-primary" target="_blank">Đăng bài nhóm</a>
	</section>

	<form role="form" id="form-input" method="post" enctype="multipart/form-data">
	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Sản phẩm</a></li>
				<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="true">Vị trí</a></li>
				<li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="true">Thẻ tag</a></li>
				<li class="red"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Tài khoản</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="col-md-3">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Tiêu đề 1</span>
									<textarea class="form-control min-h-title" rows="3" name="titles1" placeholder="" required><?php echo isset($inputs['titles1']) ? $inputs['titles1'] : ''; ?></textarea>
									<div id="error__titles1">

									</div>
									<input type="hidden" name="session" value="<?php echo $session; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Tiêu đề 2</span>
									<textarea class="form-control min-h-title" rows="3" name="titles2" placeholder="" required><?php echo isset($inputs['titles2']) ? $inputs['titles2'] : ''; ?></textarea>
									<div id="error__titles2">

									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Mô tả 1</span>
									<textarea class="form-control min-h-title" rows="3" name="descriptions1" placeholder="" required><?php echo isset($inputs['descriptions1']) ? $inputs['descriptions1'] : ''; ?></textarea>
									<div id="error__descriptions1">

									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Mô tả 2</span>
									<textarea class="form-control min-h-title" rows="3" name="descriptions2" placeholder="" required><?php echo isset($inputs['descriptions2']) ? $inputs['descriptions2'] : ''; ?></textarea>
									<div id="error__descriptions2">

									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-6">
							<div class="box-body">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Giá</span>
											<input type="number" class="form-control" name="price" min="1" max="100000000" placeholder="" required value="<?php echo isset($inputs['price']) ? $inputs['price'] : ''; ?>">
											<div id="error__price">

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Hạng mục</span>
											<select class="form-control" name="category" required>
												<option value="0" <?php echo isset($inputs['category']) && $inputs['category'] == 0 ? 'selected' : ''; ?>>Công cụ</option>
												<option value="1" <?php echo isset($inputs['category']) && $inputs['category'] == 1 ? 'selected' : ''; ?>>Nội thất</option>
												<option value="2" <?php echo isset($inputs['category']) && $inputs['category'] == 2 ? 'selected' : ''; ?>>Hộ gia đình</option>
												<option value="3" <?php echo isset($inputs['category']) && $inputs['category'] == 3 ? 'selected' : ''; ?>>Vườn</option>
												<option value="4" <?php echo isset($inputs['category']) && $inputs['category'] == 4 ? 'selected' : ''; ?>>Thiết bị</option>
												<option value="5" disabled="true">Trò chơi điện tử</option>
												<option value="6" <?php echo isset($inputs['category']) && $inputs['category'] == 6 ? 'selected' : ''; ?>>Sách phim nhạc</option>
												<option value="7" <?php echo isset($inputs['category']) && $inputs['category'] == 7 ? 'selected' : ''; ?>>Túi & hành lý</option>
												<option value="8" <?php echo isset($inputs['category']) && $inputs['category'] == 8 ? 'selected' : ''; ?>>Quần áo & giày dép nữ</option>
												<option value="9" <?php echo isset($inputs['category']) && $inputs['category'] == 9 ? 'selected' : ''; ?>>Quần áo & giày dép nam</option>
												<option value="10" <?php echo isset($inputs['category']) && $inputs['category'] == 10 ? 'selected' : ''; ?>>Trang sức & phụ kiện</option>
												<option value="11" <?php echo isset($inputs['category']) && $inputs['category'] == 11 ? 'selected' : ''; ?>>Sức khỏe và làm đẹp</option>
												<option value="12" <?php echo isset($inputs['category']) && $inputs['category'] == 12 ? 'selected' : ''; ?>>Đồ dùng cho thứ cưng</option>
												<option value="13" <?php echo isset($inputs['category']) && $inputs['category'] == 13 ? 'selected' : ''; ?>>Trẻ sơ sinh và trẻ nhỏ</option>
												<option value="14" <?php echo isset($inputs['category']) && $inputs['category'] == 14 ? 'selected' : ''; ?>>Đồ chơi và trò chơi</option>
												<option value="15" <?php echo isset($inputs['category']) && $inputs['category'] == 15 ? 'selected' : ''; ?>>Điện tử điện máy</option>
												<option value="16" disabled="true">Điện thoại di động</option>
												<option value="17" <?php echo isset($inputs['category']) && $inputs['category'] == 17 ? 'selected' : ''; ?>>Xe đạp</option>
												<option value="18" <?php echo isset($inputs['category']) && $inputs['category'] == 18 ? 'selected' : ''; ?>>Thủ công mỹ nghệ</option>
												<option value="19" <?php echo isset($inputs['category']) && $inputs['category'] == 19 ? 'selected' : ''; ?>>Thể thao và hoạt động ngoài trời</option>
												<option value="20" <?php echo isset($inputs['category']) && $inputs['category'] == 20 ? 'selected' : ''; ?>>Phụ tùng xe hơi</option>
												<option value="21" <?php echo isset($inputs['category']) && $inputs['category'] == 21 ? 'selected' : ''; ?>>Nhạc cụ</option>
												<option value="22" <?php echo isset($inputs['category']) && $inputs['category'] == 22 ? 'selected' : ''; ?>>Đồ cổ & bộ sự tập</option>
												<option value="23" <?php echo isset($inputs['category']) && $inputs['category'] == 23 ? 'selected' : ''; ?>>Thanh lý đồ cũ</option>
												<option value="24" <?php echo isset($inputs['category']) && $inputs['category'] == 24 ? 'selected' : ''; ?>>Hỗn hợp</option>
											</select>
											<div id="error__category">

											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Thương hiệu</span>
											<input type="text" class="form-control" name="brand" placeholder="" required value="<?php echo isset($inputs['brand']) ? $inputs['brand'] : ''; ?>">
											<div id="error__brand">

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Tình trạng</span>
											<select class="form-control" name="condition" required>
												<option value="0" <?php echo isset($inputs['condition']) && $inputs['condition'] == 0 ? 'selected' : ''; ?>>Mới</option>
												<option value="1" <?php echo isset($inputs['condition']) && $inputs['condition'] == 1 ? 'selected' : ''; ?>>Đã qua sử dụng - Như mới</option>
												<option value="2" <?php echo isset($inputs['condition']) && $inputs['condition'] == 2 ? 'selected' : ''; ?>>Đã qua sử dụng - Tốt</option>
												<option value="3" <?php echo isset($inputs['condition']) && $inputs['condition'] == 3 ? 'selected' : ''; ?>>Đã qua sử dụng - Khá tốt</option>
											</select>
											<div id="error__condition">

											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="col-md-6">
							<div class="box-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Số lượng ảnh một bài đăng</span>
											<input type="number" class="form-control" name="number_image" min="1" max="100000000" placeholder="" required value="<?php echo isset($inputs['number_image']) ? $inputs['number_image'] : ''; ?>">
											<div id="error__number_image">

											</div>
										</div>
									</div>
									<div class="col-md-6 row">
										<div class="col-md-6">
											<div class="form-group">
												<span class="label label-primary bd-r-0">Delay Vị trí</span>
												<input type="number" class="form-control" name="delay_location" min="0" max="100000000" placeholder="" required value="<?php echo isset($inputs['delay_location']) ? $inputs['delay_location'] : ''; ?>">
												<div id="error__delay_account">

												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<span class="label label-primary bd-r-0">Delay Tài khoản</span>
												<input type="number" class="form-control" name="delay_account" min="0" max="100000000" placeholder="" required value="<?php echo isset($inputs['delay_account']) ? $inputs['delay_account'] : ''; ?>">
												<div id="error__delay_account">

												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Thư mục ảnh</span>
											<input type="text" class="form-control" name="images" placeholder="" required value="<?php echo isset($inputs['images']) ? $inputs['images'] : ''; ?>">
											<div id="error__images">

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="tab-pane" id="tab_4">
					<div class="box-body">
						<div class="form-group">
							<span class="label label-primary bd-r-0">Mỗi dòng một vị trí</span>
							<textarea class="form-control min-h-text" name="locations" rows="3" placeholder="" required><?php echo isset($inputs['locations']) ? $inputs['locations'] : ''; ?></textarea>
							<div id="error__locations">

							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab_5">
					<div class="box-body">
						<div class="form-group">
							<span class="label label-primary bd-r-0">Mỗi dòng một tag (tối đa 20)</span>
							<textarea class="form-control min-h-text" rows="3" name="tags" placeholder="" required><?php echo isset($inputs['tags']) ? $inputs['tags'] : ''; ?></textarea>
							<div id="error__tags">

							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab_2">
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
										<li id="<?php echo $user['email']; ?>" class="list-group-item <?php if($user['status'] == 'fail'){ echo 'bg-gray'; } ?>"><img src="<?php echo $user['image'] ?>" style="width: 18px; height: 18px; border-radius: 50%;"> <?php echo $user['email']; ?><a href="javascript:void(0)" class="pull-right del-user">x</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="box-footer">
				<button type="reset" id="btn-reset" class="btn btn-default">Nhập lại</button>
				<button type="submit" id="btn-publish" class="btn btn-info">Đăng bài</button>
			</div>	
		</div>
	</section>

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
	</form>
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
		let delay_location = 0;
		let delay_account = 0;
		let number_image = 0;

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
			            		$('.list-group').append('<li id="'+data['info']['email']+'" class="list-group-item"><img src="'+data['info']['image']+'" style="width: 18px; height: 18px; border-radius: 50%;"> '+data['info']['email']+'<a href="javascript:void(0)" class="pull-right del-user">x</a></li>');
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
		})
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

		function getAllfile(path)
		{
			return $.ajax({
	            type: "post",
	            dataType: "json",
	            data: {path: path},
	            url:"http://localhost/auto-fb/get_images.php",
	            beforeSend: function(xhr) {
	                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	            },
	            success: function (data) {
	            	images = data;
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	           		images = [];
	            }
	        });
		}

		function saveInput()
		{
			var titles1 = $('form#form-input :input[name="titles1"]').val();
			var titles2 = $('form#form-input :input[name="titles2"]').val();
			var descriptions1 = $('form#form-input :input[name="descriptions1"]').val();
			var descriptions2 = $('form#form-input :input[name="descriptions2"]').val();
			var price = $('form#form-input :input[name="price"]').val();
			var category = $('form#form-input :input[name="category"]').val();
			var condition = $('form#form-input :input[name="condition"]').val();
			var brand = $('form#form-input :input[name="brand"]').val();
			var number_image = $('form#form-input :input[name="number_image"]').val();
			var delay_location = $('form#form-input :input[name="delay_location"]').val();
			var delay_account = $('form#form-input :input[name="delay_account"]').val();
			var images = $('form#form-input :input[name="images"]').val();
			var tags = $('form#form-input :input[name="tags"]').val();
			var locations = $('form#form-input :input[name="locations"]').val();

			var inputs = {titles1: titles1, titles2: titles2, descriptions1: descriptions1, descriptions2: descriptions2, price: price, category: category, condition: condition, brand: brand, number_image: number_image, delay_location: delay_location, delay_account: delay_account, images: images, tags: tags, locations: locations};

			return $.ajax({
	            type: "post",
	            dataType: "json",
	            data: inputs,
	            url:"http://localhost/auto-fb/save_input.php",
	            beforeSend: function(xhr) {
	                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	            },
	            success: function (data) {
	            	
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	           		
	            }
	        });
		}

		function getInput()
		{
			let session = $('input[name="session"]').val();
			var titles1 = $('form#form-input :input[name="titles1"]').val();
			var titles2 = $('form#form-input :input[name="titles2"]').val();
			var descriptions1 = $('form#form-input :input[name="descriptions1"]').val();
			var descriptions2 = $('form#form-input :input[name="descriptions2"]').val();
			var price = $('form#form-input :input[name="price"]').val();
			var category = $('form#form-input :input[name="category"]').val();
			var condition = $('form#form-input :input[name="condition"]').val();
			var brand = $('form#form-input :input[name="brand"]').val();
			var tags = $('form#form-input :input[name="tags"]').val();
			titles1 = multipleLinesToArray(titles1);
			titles2 = multipleLinesToArray(titles2);
			descriptions1 = multipleLinesToArray(descriptions1);
			descriptions2 = multipleLinesToArray(descriptions2);
			tags = multipleLinesToArray(tags);
			return {session: session,titles1: titles1, titles2: titles2, price: price, category: category, condition: condition, brand: brand, location: null, images: null, number_image: number_image, descriptions1: descriptions1, descriptions2: descriptions2, tags: tags, email: null};
		}

		$('#btn-refresh').click(function () {
	        $('#option-process').empty();
	    });

	    async function foreachLocation(inputs, accounts, locations)
		{
			beforeProcess();
			await saveInput();
			let path = $('form#form-input :input[name="images"]').val();
			let images = await getAllfile(path);
			for(let x = 0; x < locations.length; x++)
			{
				await sleep(delay_location*1000);
				inputs.location = locations[x];
				let imgs_tmp = randomImages(images);
				if(imgs_tmp.length == 0)
				{
					break;	
				}
				inputs.images = imgs_tmp;
				await foreachAccount(inputs, accounts);
			}
			afterProcess();
		}

		function beforeProcess()
		{
			$('#btn-refresh').addClass('d-none');
	        $('#icon-processing').removeClass('d-none');
	        $('#btn-login').empty();
	        $('#btn-login').addClass('not-active');
	        $('#btn-login').append('<i class="fa fa-spinner"></i>');
		}

		function afterProcess()
		{
			$('#btn-refresh').removeClass('d-none');
			$('#icon-processing').addClass('d-none');
			$('#btn-login').empty();
			$('#btn-login').removeClass('not-active');
			$('#btn-login').append('<i class="fa fa-play"></i>');
		}

		async function foreachAccount(inputs, accounts)
		{
			for(let i = 0; i < accounts.length; i++)
			{
				await sleep(delay_account*1000);
				inputs.email = accounts[i].id;
				await sendRequestFB(inputs);
			}
			return 0;
		}

		function sendRequestFB(input_data)
		{
			return $.ajax({
	            type: "post",
	            dataType: "json",
	            data: input_data,
	            url:"http://localhost/auto-fb/process_fb.php",
	            beforeSend: function(xhr) {
	                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	            },
	            success: function (data) {
	            	for(let x = 0; x < data.length; x++)
	            	{
	            		if(data[x].status == 'login_fail')
	            		{
	            			document.getElementById(input_data.email).classList.add("bg-gray");
	            		}
	            		$('#option-process').append('<option class="label label-process m-t-3 '+sts[data[x].status]+'">'+data[x].msg+'</option>');
	            	}
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	               $('#option-process').append('<option class="label label-process m-t-3 label-danger">'+input_data.email+' - Xảy ra lỗi với người dùng này</option>');
	            }
	        });
		}

		function sleep(ms) {
			return new Promise(resolve => setTimeout(resolve, ms));
		}

		function randomImages(images)
		{
			let result = [];
			// shuffle(images);
			let img_lent = images.length;
			for(let y = 0; y < img_lent; y++)
			{
				result.push(images[y]);
				if(y == number_image - 1)
				{
					break;
				}
			}

			for(let k = 0; k < result.length; k++)
			{
				images.remove(result[k]);
			}
			return result;
		}

		function shuffle(array)
		{
			var currentIndex = array.length, temporaryValue, randomIndex;
			while (0 !== currentIndex) {
				randomIndex = Math.floor(Math.random() * currentIndex);
				currentIndex -= 1;
				temporaryValue = array[currentIndex];
				array[currentIndex] = array[randomIndex];
				array[randomIndex] = temporaryValue;
			}
			return array;
		}

		Array.prototype.remove = function() {
			var what, a = arguments, L = a.length, ax;
			while (L && this.length) {
				what = a[--L];
				while ((ax = this.indexOf(what)) !== -1) {
					this.splice(ax, 1);
				}
			}
			return this;
		};

		function formatUser(accounts)
		{
			let users = [];
			for (i in accounts)
	    	{
	    		let tmp = {email: null, email: null};
	    		let user = splitEmailPass(accounts[i]);
	    		tmp.email = user[0];
	    		tmp.pass = user[1];
	    		users[i] = tmp;
	    	}
	    	return users;
		}
	</script>
	<script type="text/javascript">
		$("#form-input").validate({
	        onkeyup: false,
	        ignore: false,
	        onfocusout: function(element) {
	            this.element(element);
	        },
	        rules: {
	        	accounts: {
	        		required: false
	        	},
	        },
	        messages: {
	            
	        },
	        errorPlacement: function (error, element) {
	            var name = element.attr("name");
	            $(document.getElementById("error__" + name)).append(error);
	        },
	        errorElement: 'p',
	        errorClass: 'label-error',
	        submitHandler: function(form) {
		        delay_location = $('input[name="delay_location"]').val();
		        delay_account = $('input[name="delay_account"]').val();
		        number_image = $('input[name="number_image"]').val();

		    	let inputs = getInput();
		    	let accounts = $('.list-group').children();
		    	let locations = $('form#form-input :input[name="locations"]').val();
		    	locations =  multipleLinesToArray(locations);
		    	foreachLocation(inputs, accounts, locations);
	        }
	    });
	</script>
	<script>
	    //add error message when input forcusout
	    $.extend($.validator.messages, {
	        required: 'Vui lòng nhập thông tin này',
	    });
	    //trim value on forcusout
	    $.each($.validator.methods, function (key, value) {
	        $.validator.methods[key] = function () {
	            if(arguments.length > 0) {
	                arguments[0] = $.trim(arguments[0]);
	                if($(arguments[1]).attr('type') === 'email') {
	                    $(arguments[1]).val('');
	                }
	                if(!($(arguments[1]).attr('type') === 'file' || $(arguments[1]).attr('type') === 'checkbox' || $(arguments[1]).attr('type') === 'radio')) {
	                    $(arguments[1]).val(arguments[0]);
	                }
	            }
	            return value.apply(this, arguments);
	        };
	    });
	</script>
</body>
</html>