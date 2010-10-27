<?
class result_view{
    private $tweets;
    function __construct($tweeps){
        $this->tweets= $tweeps;
    }
    function displayATweet($aTweet){
        echo "<BR>";
        echo "User: ".$aTweet["from_user"]."<BR>";
        echo "Tweet: ".$aTweet["text"]."<BR>";
        echo "Date: ".$aTweet["created_at"]."<BR>";
    }
    function display(){
        for($i=0; $i<$this->tweets->count();$i++){
            $u = $this->tweets->getTweet($i);
            $this->displayATweet($u);
        }
    }
}
?>