<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, user-scalable=no">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
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

      <!-- Bootstrap -->
      <link href="res/css/bootstrap.min.css" rel="stylesheet">
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="res/js/html5shiv.min.js"></script>
        <script src="hres/js/respond.min.js"></script>
      <![endif]-->
      <script>
         function pad(n, width, z) {
            z = z || '0';
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
         }

         function countdown(time, id) {
            t = time;
            d = Math.floor(t / (60 * 60 * 24)) % 24;
            h = Math.floor(t / (60 * 60)) % 24;
            m = Math.floor(t / 60) % 60;

            s = t % 60;
            d = (d > 0) ? d + "d " : "";
            h = (h < 10) ? "0" + h : h;
            m = (m < 10) ? "0" + m : m;
            s = (s < 10) ? "0" + s : s;

            strZeit = /*d + h + ":" + m + ":" + */s + " seconds";

            if (time > 0) {
               window.setTimeout('countdown(' + --time + ',\'' + id + '\')', 1000);
            } else {
               location.reload(1);
            }
            document.getElementById(id).innerHTML = strZeit;
         }

         function countdown2(d, h, m, s, id)
         {
            countdown(d * 60 * 60 * 24 + h * 60 * 60 + m * 60 + s, id);
         }

         function print_todays_date( ) {
            var d = new Date();
            var hours = pad(d.getHours(), 2);
            var minutes = pad(d.getMinutes(), 2);
            var seconds = pad(d.getSeconds(), 2);
            //document.write(d.toLocaleString( ));
            document.write(hours + ":" + minutes + ":" + seconds);
         }

      </script>
   </head>

   <?php
   /*
     // Always use HTTPS
     if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https") {
     header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
     exit();
     }

     if($_SERVER['HTTPS'] != "on") {
     header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
     exit();
     }
    */

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
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="#">Line-Lan Server Status</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
               <ul class="nav navbar-nav navbar-right">
                  <p class="navbar-text"><strong>last update</strong> <script>print_todays_date();</script></p>
                  <p class="navbar-text"><strong>refreshing in</strong> <span id="cID3">   Init<script>countdown(59, 'cID3');</script></span></p>
                  <p class="navbar-text"><strong>connection latency</strong> <?php echo getPing('google.de'); ?> ms</p>
                  <!--
                  
                  <li><a href="https://line-lan.net">Homepage</a></li>
                  <li><a href="https://api.line-lan.net">API</a></li>
                  <li><a href="https://event.line-lan.net">Events</a></li>-->
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
                              <strong>Everything Online!</strong><br /> It seems like all services are fully available
                            </div>
                        ');
               } elseif ($downcount != 0 && $downcount <= 2) {
                  echo ('
                           <div class="alert alert alert-warning">             
                              <strong>Minor outage!</strong><br /> Some services are unavialable, but the most parts should work fine
                           </div>
                        ');
               } else {
                  echo ('
                           <div class="alert alert alert-danger">             
                              <strong>Major outage!</strong><br /> Oh crap, something must go completely wrong. We\'re trying to fix it already!
                           </div>
                        ');
               }
               ?>

               <!--
               <div class="alert alert alert-success">             
                  <strong>Everything Online!</strong> It seems like all services are fully available</a>.
               </div>
               <div class="alert alert alert-warning">             
                  <strong>Everything Online!</strong> It seems like all services are fully available</a>.
               </div>
               <div class="alert alert alert-danger">             
                  <strong>Everything Online!</strong> It seems like all services are fully available</a>.
               </div>
               -->
               <?php
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
                  /*
                    echo ('<div class="panel panel-'.$panelColor.'">'
                    . '<div class="panel-heading">'
                    .   '<h3 class="panel-title">'.$disp[$key].'</h3>'
                    . '</div>'
                    . '<div class="panel-body">'
                    .      'blabla'
                    .   '</div>'
                    . '</div>');
                   */
                  unset($badgeColor);
               }
               ?>
               <!--
                 <div class="panel panel-success">
                    <div class="panel-heading">
                       <h3 class="panel-title">Panel success</h3>
                    </div>
                    <div class="panel-body">
                       Panel content
                    </div>
                 </div>
               -->

               <div class="well">
                  <center>
                     <a href="https://line-lan.net/datenschutz/">Privacy Policy</a> -
                     <a href="https://line-lan.net/impressum/">Imprint</a> -
                     <a href="https://line-lan.net/kontakt/">Contact us</a>

                     <br/><br />
                     (c) 2012-<?php echo date("Y"); ?> Line-Lan
                  </center>                 
               </div>


            </div>

            <div class="col-sm-3">
            </div>
         </div>
      </div>

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="res/js/jquery-1.12.3.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="res/js/bootstrap.min.js"></script>
   </body>
</html>