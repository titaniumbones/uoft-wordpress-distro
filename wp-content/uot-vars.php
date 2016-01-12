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
               "contact-form-7" => '4.3.1',
               "cryptx" => '3.2.12',
               "types" => '1.8.11',
               "advanced-custom-fields" => "4.4.5",
               "email-users" => '4.3.14',
               "imagemapper" => 1.2.6,
               "draw-attention" => 1.6.2,
               "footnotes" => '1.6.2',
               // "geo-mashup" => '1.4.1',
               // "grader" => '1.0',
               "zotpress" => '6.1.1',
               "simple-taxonomy" => '3.4.1',
               //"featured-post-widget" => //"image-widget" => '3.3.7',
               "owl-carousel" =>  '0.5.1',
               // "import-users-from-csv" => '0.3.2',
               // "welcome-email-editor" => '3.4',
	       // "smooth-slider" => '2.4',
               "tinymce-advanced" => '4.2.8',
               "wp-db-backup" => '4.2.6'
               );


// same for themes
$THEMES=array(
                          );
// cctm file
$CCTMDEFS='historicalimagesoct2012.cctm.json';

$GLOBALS['UOTUSERNAME'] = $UOTUSERNAME;
$GLOBALS['UOTUSEREMAIL'] = $UOTUSEREMAIL;
$GLOBALS['PLUGINS'] = $PLUGINS;
$GLOBALS['THEMES'] = $THEMES;
$GLOBALS['CCTMDEFS'] = $CCTMDEFS;
