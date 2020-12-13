<?php
require 'vendor/autoload.php';
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
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Auto FB</title>

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
	</section>

	<form role="form" id="form-input" method="post" enctype="multipart/form-data">
	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Sản phẩm</a></li>
				<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Tài khoản</a></li>
				<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true">Link ảnh</a></li>
				<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="true">Vị trí</a></li>
				<li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="true">Thẻ tag</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="row">

						<div class="col-md-6">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Tiêu đề 1</span>
									<textarea class="form-control min-h-title" rows="3" name="titles1" placeholder="" required>Giày
Giày Quảng Ngãi
Giày nam, nữ
Giày da</textarea>
									<div id="error__titles1">

									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="box-body">
								<div class="form-group">
									<span class="label label-primary bd-r-0">Tiêu đề 2</span>
									<textarea class="form-control min-h-title" rows="3" name="titles2" placeholder="" required>xuất khẩu
thanh lý
xả hàng</textarea>
									<div id="error__titles2">

									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="box-body">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Giá</span>
											<input type="number" class="form-control" name="price" min="1" max="100000000" placeholder="" required value="50000">
											<div id="error__price">

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Hạng mục</span>
											<select class="form-control" name="category" required>
												<option value="0">Công cụ</option>
												<option value="1">Nội thất</option>
												<option value="2">Hộ gia đình</option>
												<option value="3">Vườn</option>
												<option value="4">Thiết bị</option>
												<option value="5" disabled="true">Trò chơi điện tử</option>
												<option value="6">Sách phim nhạc</option>
												<option value="7">Túi & hành lý</option>
												<option value="8">Quần áo & giày dép nữ</option>
												<option value="9">Quần áo & giày dép nam</option>
												<option value="10">Trang sức & phụ kiện</option>
												<option value="11">Sức khỏe và làm đẹp</option>
												<option value="12">Đồ dùng cho thứ cưng</option>
												<option value="13">Trẻ sơ sinh và trẻ nhỏ</option>
												<option value="14">Đồ chơi và trò chơi</option>
												<option value="15">Điện tử điện máy</option>
												<option value="16" disabled="true">Điện thoại di động</option>
												<option value="17">Xe đạp</option>
												<option value="18">Thủ công mỹ nghệ</option>
												<option value="19">Thể thao và hoạt động ngoài trời</option>
												<option value="20">Phụ tùng xe hơi</option>
												<option value="21">Nhạc cụ</option>
												<option value="22">Đồ cổ & bộ sự tập</option>
												<option value="23">Thanh lý đồ cũ</option>
												<option value="24">Hỗn hợp</option>
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
											<input type="text" class="form-control" name="brand" placeholder="" required value="Thương hiệu">
											<div id="error__brand">

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="label label-primary bd-r-0">Tình trạng</span>
											<select class="form-control" name="condition" required>
												<option value="0">Mới</option>
												<option value="1">Đã qua sử dụng - Như mới</option>
												<option value="2">Đã qua sử dụng - Tốt</option>
												<option value="3">Đã qua sử dụng - Khá tốt</option>
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

								<div class="form-group">
									<span class="label label-primary bd-r-0">Mô tả</span>
									<textarea class="form-control min-h-des" rows="3" name="description" placeholder="" required>Sản phẩm phân phối chất lượng cao</textarea>
									<div id="error__description">

									</div>
								</div>

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

				<div class="tab-pane" id="tab_3">
					<div class="box-body">
						<div class="form-group">
							<span class="label label-primary bd-r-0">Mỗi dòng một ảnh</span>
							<textarea class="form-control min-h-text" rows="3" name="images" placeholder="" required>C:\Users\ITSJ\OneDrive\Desktop\giay\FortaRun_AC_K_trang_FY1554_01_standard.jpg
C:\Users\ITSJ\OneDrive\Desktop\giay\Giay_Mickey_4uture_Sport_AC_Mau_tim_FV4256_01_standard.jpg
C:\Users\ITSJ\OneDrive\Desktop\giay\Giay_Mickey_4uture_Sport_AC_trang_FV4255_01_standard.jpg
C:\Users\ITSJ\OneDrive\Desktop\giay\Giay_Pulseboost_HD_trang_EF0914_01_standard.jpg
C:\Users\ITSJ\OneDrive\Desktop\giay\Giay_Runfalcon_2.0_DJen_FY9494_01_standard.jpg
C:\Users\ITSJ\OneDrive\Desktop\giay\Giay_Runfalcon_2.0_trang_FY9496_01_standard.jpg</textarea>
							<div id="error__images">

							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab_4">
					<div class="box-body">
						<div class="form-group">
							<span class="label label-primary bd-r-0">Mỗi dòng một vị trí</span>
							<textarea class="form-control min-h-text" name="locations" rows="3" placeholder="" required>Hồ Chí Minh
Binh Thanh, Ho Chi Minh City
Thủ Dầu Một</textarea>
							<div id="error__locations">

							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab_5">
					<div class="box-body">
						<div class="form-group">
							<span class="label label-primary bd-r-0">Mỗi dòng một tag (tối đa 20)</span>
							<textarea class="form-control min-h-text" rows="3" name="tags" placeholder="" required>tagname
tagname2
tagname3</textarea>
							<div id="error__tags">

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
				for (i in accounts)
		    	{
		    		let tmp = splitEmailPass(accounts[i]);
		    		user = {email: tmp[0], pass: tmp[1]};
		    		
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
			            },
			            error: function (XMLHttpRequest, textStatus, errorThrown) {
			               $('#option-process').append('<option class="label label-process m-t-3 label-danger">'+user.email+' - Xảy ra lỗi với người dùng này</option>');
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
			var lines = string.split(' ');
			for(let a = 0; a < lines.length; a++)
			{
				if(lines[a] == " ")
				{
					lines.splice(a, 1);
				}
			}
			return lines;
		}

		function getInput()
		{
			var titles1 = $('form#form-input :input[name="titles1"]').val();
			var titles2 = $('form#form-input :input[name="titles2"]').val();
			var price = $('form#form-input :input[name="price"]').val();
			var category = $('form#form-input :input[name="category"]').val();
			var condition = $('form#form-input :input[name="condition"]').val();
			var brand = $('form#form-input :input[name="brand"]').val();
			var locations = $('form#form-input :input[name="locations"]').val();
			var images = $('form#form-input :input[name="images"]').val();
			var description = $('form#form-input :input[name="description"]').val();
			var tags = $('form#form-input :input[name="tags"]').val();
			titles1 = multipleLinesToArray(titles1);
			titles2 = multipleLinesToArray(titles2);
			locations =  multipleLinesToArray(locations);
			images = multipleLinesToArray(images);
			tags = multipleLinesToArray(tags);
			return {titles1: titles1, titles2: titles2, price: price, category: category, condition: condition, brand: brand, locations: locations, images: images, description: description, tags: tags, email: null, pass: null};
		}
		$('#btn-refresh').click(function () {
	        $('#option-process').empty();
	    });

	    const foreachAccounts = async  (inputs, accounts) =>
		{
			for (let i = 0; i < accounts.length; i++)
	    	{
	    		inputs.email = accounts[i].id;
	    		await sendRequestFB(inputs);
	    	}
	    	$('#btn-publish').removeAttr("disabled", true);
	    	$('#btn-reset').removeAttr("disabled", true);
	    	$('#btn-refresh').removeClass('d-none');
	    	$('#icon-processing').addClass('d-none');
	    	$('#btn-cancel').addClass('d-none');
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
	               $('#option-process').append('<option class="label label-process m-t-3 label-default">'+input_data.email+' - Xảy ra lỗi với người dùng này</option>');
	            }
	        });
		}

		function sleep(ms) {
			return new Promise(resolve => setTimeout(resolve, ms));
		}

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
	            $('#btn-publish').attr("disabled", true);
		        $('#btn-reset').attr("disabled", true);
		        $('#btn-refresh').addClass('d-none');
		        $('#icon-processing').removeClass('d-none');
		        $('#btn-cancel').removeClass('d-none');
		    	let inputs = getInput();
		    	let accounts = $('.list-group').children();
		    	foreachAccounts(inputs, accounts);
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