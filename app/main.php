<?php

//Php's simplexml to load xml file
$xml = simplexml_load_file('App/data.xml');

//Error handling
libxml_use_internal_errors(true);
if ($xml === false) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
}

//Initial function to convert xml data to an array object
function xml2array($xml)
{
    $arr = array();
    foreach ($xml->children() as $child)
    {
        $a = array();
        if(count($child->children()) == 0)//Check if children node from xml file is zero
        {
            $arr[$child->getName()] = strval($child); //Assign the name of of xml child element to its string value
        }else{
            $arr[$child->getName()][] = xml2array($child); //Loops through the DOM and appending child to an array
        }
    }
    return $arr;

}

//Second function that converts the array of above function to an json object
function array2json($xml) {

    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($xml);
    $max_length = count($xml)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) //See if the first key is 0 and last key is length - 1
    {
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($xml as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);

    if($is_list) return $json;//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
}

$result = array2json(xml2array($xml));
echo $result;
