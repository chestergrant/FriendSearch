<?
class search_engine{
    private $screen_name;
    private $query;
    private $url;
    private $paging_url;
    private $search_url;
    private $followers;
    private $tweets;
    private $stop_tweets;
    private $stop_followers;
    private $follower_paging_url;
    private $closure;
    function  __construct($scr_name, $qry) {
        $this->closure = "-1";
        $this->stop_followers = false;
        $this->followers = array();
        $this->stop_tweets = false;
        $this->tweets = new collect_tweet;
        $this->screen_name = $scr_name;
        $this->query = $qry;
        $this->url = "http://api.twitter.com/1/statuses/friends/".$this->screen_name.".json?cursor=";
        $this->search_url = "http://search.twitter.com/search.json?q=".$this->query."&lang=en&rpp=100&show_user=true";
    }
    function getUsers(){
        $callstr = $this->follower_paging_url;
        $ch = curl_init($callstr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $apiresponse = curl_exec($ch);
        curl_close($ch);

        if ($apiresponse) {
                $json = json_decode($apiresponse,true);
                

                if (($json != null)&&(!$json->error)){
                    
                     if(($json["next_cursor_str"]==0)||($json["next_cursor_str"]=="0")){
                         $this->closure = -2;
                     }else{
                            $this->closure = $json["next_cursor_str"];
                     }
                    $this->json_arr($json["users"]);
                }
        }
        

    }
    function json_arr($json){
        
        foreach ($json as $u){
            $this->followers[] = $u["screen_name"];
        }
        
    }
    function getTweets(){

        $callstr = $this->paging_url;
        $ch = curl_init($callstr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $apiresponse = curl_exec($ch);
        curl_close($ch);

        if ($apiresponse) {
                $json = json_decode($apiresponse,true);
              
                if (($json != null)&&(!$json->error)){
                    if(count($json["results"]) == 0){$this->stop_tweets = true; return;}
                    $this->json_tweets($json["results"]);
                    
                }else{
                    $this->stop_tweets = true;
                }
        }
        return null;
    }
    function json_tweets($json){
        $num=0;
        foreach ($json as $u){
            $this->tweets->addTweet($u);
            $num++;
        }   
        
    }
    function cross_ref($followers, $tweets){
        $qualified_tweets = new collect_tweet;
        for($i = 0; $i<$tweets->count();$i++){
            $u = $tweets->getTweet($i);
            if($this->contains($u["from_user"],$followers)){
                $qualified_tweets->addTweet($u);
            }
        }
        return $qualified_tweets;
    }
    function contains($id, $followers){
        for($j=0; $j < count($followers); $j++){
            if($id ==$followers[$j]){
                return true;
            }
        }
        return false;
    }
    function getResults(){
        while(!$this->stop_followers){
           if($this->closure != -2){
            $this->follower_paging_url = $this->url.$this->closure;
            $this->getUsers();
           }else{
               $this->stop_followers=true;
           }
        }
        
        $page_counter = 1;
        while(!$this->stop_tweets){
            $this->paging_url =$this->search_url."&page=".$page_counter;
            $tweets = $this->getTweets();
            $page_counter++;
        }
        
        if(($this->followers==null) ||($this->tweets==null) ){
            return null;
        }
        $aResult= $this->cross_ref($this->followers, $this->tweets);
        
        return $aResult;
    }
}

?>