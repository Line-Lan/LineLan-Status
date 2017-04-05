<?php

    include "functions.inc.php";

    if (file_exists("config.inc.php")) {
        include "config.inc.php";
    } else if (file_exists("config.sample.inc.php")) {
        include "config.sample.inc.php";
    } else {
        die();
    }

   // Detecting the users language
   if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
   } else {
      $user_lang = "en";
   }

   if (file_exists('lang/'.$user_lang.'.php')) {
      require_once 'lang/'.$user_lang.'.php';
   } else {
      require_once 'lang/en.php';
   }

   // Push Value with key into array: $data[$key] = $value;
   $downcount = 0;

   foreach ($addresses as $key => $value) {
      $temp = isPingable($value, $ports[$key]);
      $statuses[$key] = $temp;
      if (!$temp) {
         $downcount++;
      }
      unset($temp);
   }

   if ($downcount == 0) {
      $alert_type = "success";
      $alert_title = $lang['all_services_available'];
      $alert_text = $lang['all_services_available_detail'];

   } elseif ($downcount != 0 && $downcount <= 2) {
      $alert_type = "warning";
      $alert_title = $lang['minor_outage'];
      $alert_text = $lang['minor_outage_detail'];

   } else {
      $alert_type = "danger";
      $alert_title = $lang['major_outage'];
      $alert_text = $lang['major_outage_detail'];
   }
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, user-scalable=no">

      <title>Line-Lan.net Server Status</title>

      <meta name="theme-color" content="#212121">
      <meta name="description" content="Monitoring the uptime of Line-Lan services"/>
      <meta name="twitter:card" content="summary">
      <meta name="twitter:creator" content="@L1n3m4st3r"/>
      <meta name="twitter:site" content="@TeamLineLan">
      <meta name="twitter:title" content="Line-Lan.net Server Status"/>

      <meta property="og:type" content="website">
      <meta property="og:site_name" content="Line-Lan.net Server Status">
      <meta property="og:title" content="Line-Lan.net Server Status">
      <meta property="description" content="Monitoring the uptime of Line-Lan services">
      <meta property="og:description" content="Monitoring the uptime of Line-Lan services">
      <meta property="og:image" content="https://line-lan.net/wp-content/uploads/2016/10/no_preview_available.jpg">

      <link rel="shortcut icon" href="/favicon.ico">

      <link href="res/css/bootstrap.min.css" rel="stylesheet">
      <link href="res/css/custom.css" rel="stylesheet">

      <!--[if lt IE 9]>
        <script src="res/js/html5shiv.min.js"></script>
        <script src="hres/js/respond.min.js"></script>
      <![endif]-->
      <script src="res/js/countdown.js"></script>

   </head>
   
   <body>
      <nav class="navbar navbar-inverse">
         <div class="container-fluid">

            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="#">Line-Lan.net Server Status</a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-main">
               <ul class="nav navbar-nav navbar-left hidden-sm">
                  <li><a href="https://line-lan.net">Home</a></li>
                  <li><a href="https://api.line-lan.net">API</a></li>
                  <li><a href="https://event.line-lan.net">Events</a></li>
                  <li><a href="https://line-lan.net/teamspeak/">Teamspeak</a></li>
               </ul>
               
                <ul class="nav navbar-nav navbar-left visible-sm-inline">
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links<span class="caret"></span></a>
                     <ul class="dropdown-menu">
                        <li><a href="https://line-lan.net">Home</a></li>
                        <li><a href="https://api.line-lan.net">API</a></li>
                        <li><a href="https://event.line-lan.net">Events</a></li>
                        <li><a href="https://line-lan.net/teamspeak/">Teamspeak</a></li>
                     </ul>
                 </li>
               </ul>

               <ul class="nav navbar-nav navbar-right">
                  <li><a href="#"><strong><?php echo $lang['last_update']; ?></strong> <?php echo date("H:i:s", time()); ?></a></li>
                  <li><a href="#"><strong><?php echo $lang['refreshing_in']; ?></strong> <span id="cID3"> Init<script>countdown(59, 'cID3');</script></span> <strong><?php echo $lang['seconds']; ?></strong></a></li>
                  <li class="visible-lg-inline"><a href="#"><strong><?php echo $lang['connection_latency']; ?></strong> <?php echo getPing('google.de'); ?> ms</a></li>
               </ul>
            </div>
            
         </div>
      </nav>

      <div class="container">
         <div class="row">
            <div class="col-sm-3">
            </div>

            <div class="col-sm-6">
               <?php

               echo ('
                     <div class="alert alert-' . $alert_type . '">
                        <h4> '. $alert_title .' </h4> '. $alert_text .'
                     </div>
                     ');

               foreach ($addresses as $key => $value) {
                  if ($statuses[$key]) {
                     $badgeColor = 'success';
                     $badgeText = $lang['online'];
                  } else {
                     $badgeColor = 'danger';
                     $badgeText = $lang['offline'];
                  }

                  echo ('
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <h5>' . $disp[$key] . '<span class="label pull-right label-' . $badgeColor . '">' . $badgeText . '</span></h5>
                        </div>
                     </div>
                  ');

                  unset($badgeColor);
               }
               ?>

               <div class="text-center well">
                     <a href="https://line-lan.net/datenschutz/"><?php echo $lang['privacy_policy']; ?></a> -
                     <a href="https://line-lan.net/impressum/"><?php echo $lang['legal_notice']; ?></a> -
                     <a href="https://line-lan.net/kontakt/"><?php echo $lang['contact']; ?></a>

                     <br/><br />
                     &copy; <?php echo date("Y"); ?> <a href="https://line-lan.net">Line-Lan.net</a>
               </div>

            </div>

            <div class="col-sm-3">
            </div>
         </div>
      </div>

      <script src="res/js/jquery-1.12.3.min.js"></script>
      <script src="res/js/bootstrap.min.js"></script>

   </body>
</html>