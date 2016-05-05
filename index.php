<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta name="theme-color" content="#212121">
<meta name="twitter:card" content="summary">
<meta name="twitter:creator" content="@L1n3m4st3r"/>
<meta name="twitter:site" content="@TeamLineLan">
<meta name="twitter:title" content="Line-Lan Server-Status"/>
<meta property="og:type" content="website">
<meta property="og:site_name" content="Line-Lan Status">
<meta property="og:title" content="Line-Lan Server-Status">
<meta property="description" content="Monitoring the uptime of Line-Lan services">
<meta property="og:description" content="Monitoring the uptime of Line-Lan services">
<meta property="og:image" content="https://line-lan.net/wp-content/uploads/2015/08/favicon-192.png">
<link rel="shortcut icon" ref="/favicon.ico">
<title>Line-Lan Server-Status</title>	
<?php
// Always use HTTPS
/*
if($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https") { 
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); 
	exit(); 
}
*/
if($_SERVER['HTTPS'] != "on") { 
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); 
	exit(); 
}
// Check the Status of a Webserver
function checkStatus($host, $port) {
	if($socket =@ fsockopen($host, $port, $errno, $errstr, 2)) {
		echo '<font color="green">Online</font>';
		fclose($socket);
	} else {
		echo '<font color="red">Offline</font>';
	}
}
// Check the latency to a Webserver
function pingStatus($domain){
    $starttime = microtime(true);
    $file      = @fsockopen($domain, 80, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;
    if (!$file){
        $status = -1;
    } else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}
?>
<style type="text/css">
	.style1 {
		font-family: "Courier New", Courier, monospace;
		color: #FFFFFF;
		text-align: center;
	}
	.style2 {
		margin-top: 0px;
	}
	.style3 {
		font-family: "Courier New", Courier, monospace;
		color: #FFFFFF;
	}
	.style4 {
		font-family: "Courier New", Courier, monospace;
		color: #FFFFFF;
		text-align: center;
	}
	.style5 {
		font-family: "Courier New", Courier, monospace;
		color: #FFFFFF;
		text-align: right;
	}

	a:visited {
		color: #FFFFFF;
	}
	a:active {
		color: #FFFFFF;
	}
	a:hover {
		color: #FFFFFF;
	}
	a {
		color: #FFFFFF;
	}
	</style>

<script>
function countdown(time,id){
	t = time;
	d = Math.floor(t/(60*60*24)) % 24; 
	h = Math.floor(t/(60*60)) % 24;
	m = Math.floor(t/60) %60;

	s = t %60;
	d = (d >  0) ? d+"d ":"";
	h = (h < 10) ? "0"+h : h;
	m = (m < 10) ? "0"+m : m;
	s = (s < 10) ? "0"+s : s;

	strZeit =/*d + h + ":" + m + ":" + */s + " seconds";

	if(time > 0) {
		window.setTimeout('countdown('+ --time+',\''+id+'\')',1000);
	} else {
   	location.reload(1);
	}
	document.getElementById(id).innerHTML = strZeit;
}

function countdown2(d,h,m,s,id)
{
  countdown(d*60*60*24+h*60*60+m*60+s,id);
}

function print_todays_date( ) {
	var d = new Date( );
	document.write(d.toLocaleString( ));
}
</script>
</head>
<body style="background-color: #212121">
<h2><br/></h2>
<table align="center" style="width:25%" class="style2">
	<tr>
		<td class="style4" colspan="2">
		<h1>
		<strong>Line-Lan Server-Status</strong>
		</h1>
		<h2><br/><br/></h2>
		<!--<h2><font color="orange">Cache deaktiviert.</font></h2> <br/> -->
		<br/><br/>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 70%">
		<h2><strong>Website</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('Line-Lan.net', 80) ?>
		</strong></h2>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 70%">
		<h2><strong>Database</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('localhost', 3306) ?>
		</strong></h2>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 70%">
		<h2><strong>Mail</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('imappro.zoho.com', 993) ?>
		</strong></h2>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 70%">
		<h2><strong>API</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('api.line-lan.net', 80) ?>
		</strong></h2>
		</td>
	</tr>
   <tr>
		<td class="style3" style="width: 70%">
		<h2><strong>Teamspeak</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('5.230.10.109', 80) ?>
		</strong></h2>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 70%">
		<h2><strong>DNS</strong></h2>
		</td>
		<td class="style5">
		<h2><strong>
		<?php checkStatus('kevin.ns.cloudflare.com', 53) ?>
		</strong></h2>
		</td>
	</tr>
</table>
<br/><br/><br/>
<table align="center" style="width: 25%" class="style2">	
	<tr>
		<td class="style3" style="width: 50%">
		<h4>
		last update:</h4></td><td class="style3" style="width: 50%">
		<h4>
		<script>print_todays_date();</script>
		</h4>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 50%">
		<h4>refreshing in:</h4>
		</td><td class="style3" style="width: 50%" align="center"><h4>
		<b id="cID3">   Init<script>countdown(30,'cID3');</script></b>
		</h4>
		</td>
	</tr>
	<tr>
		<td class="style3" style="width: 50%">
		<h4>connection latency:</h4>
		</td><td class="style3" style="width: 50%" align="center"><h4>
		<?php echo pingStatus('google.de'); ?> ms
		</h4>
		</td>
	</tr>
</table>
</body>
