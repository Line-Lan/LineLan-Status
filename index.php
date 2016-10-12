<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
       'mail' => 'imappro.zoho.com',
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
               <a class="navbar-brand" href="#">Line-Lan Server Status</a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-main">
               
               <ul class="nav navbar-nav navbar-left">  
                  <li><a href="https://line-lan.net">Homepage</a></li>
                  <li><a href="https://api.line-lan.net">API</a></li>
                  <li><a href="https://event.line-lan.net">Events</a></li>
                  <li><a href="https://line-lan.net/teamspeak/">Teamspeak</a></li>
               </ul>
               
               <ul class="nav navbar-nav navbar-right">
                   <li><a href="#"><strong>last update</strong> <script>print_todays_date();</script></a></li>
                   <li><a href="#"><strong>refreshing in</strong> <span id="cID3">   Init<script>countdown(59, 'cID3');</script></span></a></li>
                   <li><a href="#"><strong>connection latency</strong> <?php echo getPing('google.de'); ?> ms</a></li>
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
                  echo ('
                           <div class="alert alert alert-success">             
                              <h4>All services available!</h4> All services are available without any issues!
                            </div>
                        ');
               } elseif ($downcount != 0 && $downcount <= 2) {
                  echo ('
                           <div class="alert alert alert-warning">             
                              <h4>Minor outage!</h4> Some services might be unavailable, but the most parts should work fine!
                           </div>
                        ');
               } else {
                  echo ('
                           <div class="alert alert alert-danger">             
                              <h4>Major outage!</h4> Major parts of the system are offline! We\'re already trying to solve this issue!
                           </div>
                        ');
               }

               foreach ($addresses as $key => $value) {
                  if ($statuses[$key]) {
                     $badgeColor = 'success';
                     $badgeText = 'online';
                  } else {
                     $badgeColor = 'danger';
                     $badgeText = 'offline';
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
                     <a href="https://line-lan.net/datenschutz/">Privacy Policy</a> -
                     <a href="https://line-lan.net/impressum/">Legal notice</a> -
                     <a href="https://line-lan.net/kontakt/">Contact us</a>

                     <br/><br />
                     (c) 2012-<?php echo date("Y"); ?> <a href="https://line-lan.net">Line-Lan.net</a>
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
