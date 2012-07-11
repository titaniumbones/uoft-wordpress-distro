<?php 

// this file contains variables and arrays useful for 
// automating wordpress installations

// these set a default backdoor admin user
// useful when creating student sites
$USERNAME='matt';
$USEREMAIL='matt.price@utoronto.ca';

// here is an array of plugins w/ version to load
$PLUGINS=array(
               "custom-content-type-manager" => '0.9.6',
               "simple-taxonomy" => 'latest',
               "all-in-one-event-calendar" => '1.2.5',
               );

// cctm file
$CCTMDEFS='historydepartmentjune2012.cctm.json'

