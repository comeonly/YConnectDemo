<?php
	session_start();
	if (!isset($_SESSION['y_token'])) {
		header('HTTP/1.1 302 Moved Temporary');
		header('Location: ./opauth/yahoojp/');
		exit();
	}

	require_once 'lib/YahooApi.php';
	define('TMP', dirname(__FILE__) . '/tmp/');
	// settings for curl if you need.
	$settings = array(
		// 'id' => 'your_yahoo_id', /* use for cookie file name */
		// 'cookieFilePath' => '/path/to/file/',
		// 'log' => false,
		// 'userAgent' => 'YConnect',
	);
	$type = 'selling';
	$availableTypes = array('selling', 'not_sold', 'sold');
	if (array_key_exists('type', $_GET) && in_array($_GET['type'], $availableTypes)) {
		$type = $_GET['type'];
	}
	if (array_key_exists('page', $_GET)) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
	$token = $_SESSION['y_token'];

	$YahooApi = new YahooApi();
	$results = $YahooApi->itemList($settings, $type, $token, $page);
	if (array_key_exists('ResultSet', $results)) {
		if (empty($results['ResultSet']['totalResultsReturned'])) {
			$items = array();
		} else {
			$totalPages = $results['ResultSet']['totalResultsAvailable'] / 50;
			$totalPages = (int)ceil($totalPages);
			$items = $results['ResultSet']['Result'];
		}
	} elseif (array_key_exists('Error', $results)) {
		if (preg_match('/error_description=\\\"(.+)\\\"/', $results['Error']['Messages'], $message)) {
			if ($message[1] === 'expired token') {
				header('HTTP/1.1 302 Moved Temporary');
				header('Location: ./opauth/yahoojp/');
				exit();
			}
			trigger_error($message[1]);
		}
		exit();
	}
?>

<!DOCTYPE html>
<html lang="jp">
	<head>
		<meta charset="utf-8">
		<title>YCoonect Demo</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="vendor/twbs/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
		<style>
			body {
					padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>
		<link href="vendor/twbs/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="vendor/twbs/bootstrap/docs/assets/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="vendor/twbs/bootstrap/docs/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="vendor/twbs/bootstrap/docs/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="vendor/twbs/bootstrap/docs/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="vendor/twbs/bootstrap/docs/assets/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="vendor/twbs/bootstrap/docs/assets/ico/favicon.png">
	</head>
	<body>


		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="/">YConnect Demo</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="https://github.com/comeonly/YConnectDemo">About</a></li>
							<li><a href="https://github.com/comeonly/YConnectDemo">Contact</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">

		<?php if (empty($items)): ?>
		<p>表示する一覧はありません</p>
		<?php else: ?>
		<div class="pagination">
			<ul>
				<?php $query = array('type' => $type, 'page' => 1); ?>
				<li><a href="?<?php echo http_build_query($query); ?>">«</a></li>
				<?php if ($page === 1): ?>
				<li class="disabled"><a href="#">‹</a></li>
				<?php else: ?>
				<?php $query = array('type' => $type, 'page' => $page - 1); ?>
				<li><a href="?<?php echo http_build_query($query); ?>">‹</a></li>
				<?php endif; ?>
				<?php for ($i = $page; $i < $page + 5; $i++): ?>
				<?php if ($i > $totalPages): ?>
				<li class="disabled"><a href="#"><?php echo $i; ?></a></li>
				<?php elseif ($i === $page): ?>
				<li class="active"><a href="#"><?php echo $i; ?></a></li>
				<?php else: ?>
				<?php $query = array('type' => $type, 'page' => $i); ?>
				<li><a href="?<?php echo http_build_query($query); ?>"><?php echo $i; ?></a></li>
				<?php endif; ?>
				<?php endfor; ?>
				<?php if ($page >= $totalPages): ?>
				<li class="disabled"><a href="#">›</a></li>
				<?php else: ?>
				<?php $query = array('type' => $type, 'page' => $i); ?>
				<li><a href="?<?php echo http_build_query($query); ?>">›</a></li>
				<?php endif; ?>
				<?php $query = array('type' => $type, 'page' => $totalPages); ?>
				<li><a href="?<?php echo http_build_query($query); ?>">»</a></li>
			</ul>
			<ul class="pager">
				<?php foreach ($availableTypes as $typeName): ?>
				<?php if ($typeName === $type): ?>
				<li class="active"><a href="#"><?php echo $typeName; ?></a></li>
				<?php else: ?>
				<?php $query = array('type' => $typeName); ?>
				<li><a href="?<?php echo http_build_query($query); ?>"><?php echo $typeName; ?></a></li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<ul id="grid-content" class="thumbnails">
			<?php foreach ($items as $item): ?>
			<li class="span2">
			<div class="thumbnail">
				<img src="<?php echo $item['Image']['Url']; ?>" alt="<?php echo $item['Title']; ?>" width="<?php echo $item['Image']['Width']; ?>" height="<?php echo $item['Image']['Height']; ?>">
				<a href="<?php echo $item['AuctionItemUrl']; ?>"><?php echo $item['Title']; ?></a>
				<?php foreach ($item['Option'] as $iconName => $icon): ?>
				<?php if (strpos($iconName, 'IconUrl') !== false && !empty($icon)): ?>
				<img src="<?php echo $icon; ?>" alt="<?php echo $iconName; ?>">
				<?php endif; ?>
				<?php endforeach; ?>
				<p>
				<?php
					$price = $type === 'selling' ? $item['CurrentPrice'] : $item['HighestPrice'];
					echo number_format($price, 0) . ' 円';
				?>
				</p>
				<p><?php echo date('Y-m-d H:i:s', strtotime($item['EndTime'])) . ' 終了'; ?></p>
			</div>
			</li>
			<?php endforeach; ?>
		</ul>

		<?php endif; ?>

	</div> <!-- /container -->

	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="bower_components/jquery/jquery.min.js"></script>
	<script src="bower_components/jquery.easing/js/jquery.easing.min.js"></script>
	<script src="vendor/twbs/bootstrap/docs/assets/js/bootstrap.min.js"></script>
	<script src="bower_components/jquery_vgrid_plugin/jquery.vgrid.min.js"></script>
	<script type="text/javascript">
	//<![CDATA[
	$(function(){
		var vg = $("#grid-content").vgrid({
		easing: "easeOutQuint",
		useLoadImageEvent: true,
		time: 400,
		delay: 20,
		fadeIn: {
			time: 500,
			delay: 50,
			wait: 500
		}
		});
	});
	$(window).load(function(e){
		vg.vgrefresh();
	});
	//]]>
	</script>
	</body>
</html>
