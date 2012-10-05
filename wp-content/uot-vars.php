<?php 

// this file contains variables and arrays useful for 
// automating wordpress installations

// these set a default backdoor admin user
// useful when creating student sites
$UOTUSERNAME ='mattprice';
$UOTUSEREMAIL='moptop99@gmail.com';

// here is an array of plugins w/ version to load
$PLUGINS=array(
               "attachments" => '1.6.2.1',
               "contact-form-7" => '3.3',
               "cryptx" => '3.2.2',
               "custom-content-type-manager" => '0.9.6',
               "email-users" => '4.3.14',
               "fd-footnotes" => '',
               // "geo-mashup" => '1.4.1',
               // "grader" => '1.0',
               "iframe-embed-for-youtube" => '1.0',
               "zotpress" => '4.5.4',
               "simple-taxonomy" => '3.4.1',
               //"featured-post-widget" => '3.1',
               "image-widget" => '3.3.7',
               // "import-users-from-csv" => '0.3.2',
               // "welcome-email-editor" => '3.4',
               "smooth-slider" => '2.4',
               "tinymce-advanced" => '3.4.9',
               "wp-db-backup" => '2.2.3',
               "youtube-embed" => '2.5.6'
               );


// same for themes
$THEMES=array(
              "twentytwelve" => '1.0'
              );
// cctm file
$CCTMDEFS='historicalimagesoct2012.cctm.json';

$GLOBALS['UOTUSERNAME'] = $UOTUSERNAME;
$GLOBALS['UOTUSEREMAIL'] = $UOTUSEREMAIL;
$GLOBALS['PLUGINS'] = $PLUGINS;
$GLOBALS['THEMES'] = $THEMES;
$GLOBALS['CCTMDEFS'] = $CCTMDEFS;
