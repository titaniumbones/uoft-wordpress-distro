<?php 

$DEFAULTPLUGINS=array(
               "custom-content-type-manager" => '0.9.6',
               #"simple-taxonomy" => 'latest',
               "all-in-one-event-calendar" => '1.2.5',
               );

if (file_exists('./wp-content/uot-vars.php')) {
    require_once('./wp-content/uot-vars.php');
  } else {
    $PLUGINS=$DEFAULTPLUGINS;
  }

/**
 * Implode an array with the key and value pair giving
 * a glue, a separator between pairs and the array
 * to implode.
 * @param string $glue The glue between key and value
 * @param string $separator Separator between pairs
 * @param array $array The array to implode
 * @return string The imploded array
 */
function array_implode( $glue, $separator, $array ) {
    if ( ! is_array( $array ) ) return $array;
    $string = array();
    foreach ( $array as $key => $val ) {
        if ( is_array( $val ) )
            $val = implode( ',', $val );
        $string[] = "{$key}{$glue}{$val}";
       
    }
    return implode( $separator, $string );
}

// use the above function to create a string that we can write to a temp file
// and then pass to bash
  $OUTFILE="/tmp/uot-file";

function uot_vars_to_bash($title, $array, $fileobject) {
    // open file
    $pvalue=array_implode(',',"\n", $array);
    $filestring="#!/bin/bash" . "\n\n";
    $filestring .= $title . "=\"";
    $filestring .= $pvalue . "\"\n";
    $filestring .= "export " . $title ."\n";
    fwrite($fileobject,$filestring);
}

$f = fopen($OUTFILE, 'w');
uot_vars_to_bash("PLUGINS", $PLUGINS, $f);
uot_vars_to_bash("THEMES", $THEMES, $f);
fclose ($f);