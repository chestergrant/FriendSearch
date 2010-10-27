<?
require_once 'model/search_engine.php';
require_once 'view/result_view.php';
require_once 'model/collection_tweet.php';
set_time_limit(0);
$error = "";
$query = $_POST['query'];
$screen_name = $_POST['screen_name'];
if(isset($_POST['submit'])){
 if(!isset($query) ||($query=="")){ $error .= "Please enter search term<BR>";}
 if(!isset($screen_name) ||($screen_name=="")){ $error .= "Please enter screen name<BR>";}
 if($error ==""){
    
     $search = new search_engine($screen_name,$query);
     $results = $search->getResults();
     if($results->count() == 0){
         $error .= "No Results";
     }
 }
}
echo "".$error;
include 'view/search_frm.htm';
if(isset($results)&&($error=="")) {
    $result_screen = new result_view($results);
    $result_screen->display();
}

?>