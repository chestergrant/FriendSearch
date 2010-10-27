<?
 class collect_tweet{
     private $count;
     private $tweets;
     function  __construct() {
        $this->count =0;
        $this->tweets = array();
    }
     function addTweet($u){
         $this->tweets[] = $u;
         $this->count++;
     }
     function count(){
         return $this->count;
     }
     function getTweet($i){
         if(($i<0)&&($i>=$this->count)){
             return null;
         }
         return $this->tweets[$i];
     }
 }
?>