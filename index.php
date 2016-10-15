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
      
      <link rel="shortcut icon" ref="/favicon.ico">
      
      <link href="res/css/bootstrap.min.css" rel="stylesheet">
      <link href="res/css/custom.css" rel="stylesheet">

      <!--[if lt IE 9]>
        <script src="res/js/html5shiv.min.js"></script>
        <script src="hres/js/respond.min.js"></script>
      <![endif]-->
      <script src="res/js/countdown.js"></script>

   </head>

   <?php

   // Check the Status of a Webserver
   function isPingable($host, $port) {
      if ($socket = @fsockopen($host, $port, $errno, $errstr, 2)) {
         fclose($socket);
         return true;
      } else {
         return false;
      }
   }

   // Check the latency to a Webserver
   function getPing($domain) {
      $starttime = microtime(true);
      $file = @fsockopen($domain, 80, $errno, $errstr, 10);
      $stoptime = microtime(true);
      $status = 0;
      if (!$file) {
         $status = -1;
      } else {
         fclose($file);
         $fstatus = ($stoptime - $starttime) * 1000;
         $status = floor($fstatus);
      }
      return $status;
   }

   // Array for addresses of servers
   $addresses = array(
       'homepage' => 'line-lan.net',
       'database' => 'localhost',
       'mail' => 'mail.line-lan.net',
       'api' => 'api.line-lan.net',
       'teamspeak' => 'ts.line-lan.net',
       'dns' => 'kevin.ns.cloudflare.com');

   // Array for actual display names
   $disp = array(
       'homepage' => 'Homepage',
       'database' => 'Database',
       'mail' => 'Mail',
       'api' => 'API',
       'teamspeak' => 'Teamspeak',
       'dns' => 'DNS');

   // Array for ports of servers
   $ports = array(
       'homepage' => 443,
       'database' => 3306,
       'mail' => 993,
       'api' => 80,
       'teamspeak' => 10011,
       'dns' => 53);
       
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
   
   // Detecting the users language
   $user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

   if (file_exists('lang/'.$user_lang.'.php')) {
      require_once 'lang/'.$user_lang.'.php';
   } else {
      require_once 'lang/en.php';
   }
   
   ?>

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
               
               <ul class="nav navbar-nav navbar-left">  
                  <li><a href="https://line-lan.net">Homepage</a></li>
                  <li><a href="https://api.line-lan.net">API</a></li>
                  <li><a href="https://event.line-lan.net">Events</a></li>
                  <li><a href="https://line-lan.net/teamspeak/">Teamspeak</a></li>
               </ul>
               
               <ul class="nav navbar-nav navbar-right">
                   <li><a href="#"><strong><?php echo $lang['last_update']; ?></strong> <script> print_todays_date();</script></a></li>
                   <li><a href="#"><strong><?php echo $lang['refreshing_in']; ?></strong> <span id="cID3"> Init<script>countdown(59, 'cID3');</script></span> <strong><?php echo $lang['seconds']; ?></strong></a></li>
                   <li><a href="#"><strong><?php echo $lang['connection_latency']; ?></strong> <?php echo getPing('google.de'); ?> ms</a></li>
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
                           
                           <h5>
                              ' . $disp[$key] . '
                              <span class="label pull-right label-' . $badgeColor . '">' . $badgeText . '</span>
                           </h5>
                        </div>
                     </div>
                  ');

                  unset($badgeColor);
               }
               ?>

               <div class="well">
                  <center>
                     <a href="https://line-lan.net/datenschutz/"><?php echo $lang['privacy_policy']; ?></a> -
                     <a href="https://line-lan.net/impressum/"><?php echo $lang['legal_notice']; ?></a> -
                     <a href="https://line-lan.net/kontakt/"><?php echo $lang['contact']; ?></a>

                     <br/><br />
                     &copy; 2012-<?php echo date("Y"); ?> <a href="https://line-lan.net">Line-Lan.net</a>
                  </center>                 
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