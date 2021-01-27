<?php
namespace Youtube\controllers\facade;
use Youtube\Database;

class YouTubeApiFacade
{
    protected $youtube;
    protected $ffmpeg;
    protected $channel_list;
    protected $youtube_key;
    protected $upload_list_ids = [];
    protected $video_list_ids = [];
    protected $video_list_items = [];
    protected $search_channel_list = [];
    protected $search_video_list = [];
    protected $pdo_pgsql;
    protected $next_page_token = '';
    protected $data_index = 0;
    protected $current_channel_name = '';
    protected $channel_feed;
    protected $url_watch_part  =  "https://www.youtube.com/watch?v=";
    protected $url_embed_part =  "https://www.youtube.com/embed/";
    protected $url_fed_part_channel =  "https://www.youtube.com/feeds/videos.xml?channel_id=";
    protected $url_fed_part_user =  "https://www.youtube.com/feeds/videos.xml?user=";
    protected $handle = '';
    // protected $messages = []; 
    protected $reindex = 0;
    protected $log_type = '1';


    protected $resources = array(
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
        'search.list' => 'https://www.googleapis.com/youtube/v3/search',
        'channels.list' => 'https://www.googleapis.com/youtube/v3/channels',
        'playlists.list' => 'https://www.googleapis.com/youtube/v3/playlists',
        'playlistItems.list' => 'https://www.googleapis.com/youtube/v3/playlistItems',
        'activities' => 'https://www.googleapis.com/youtube/v3/activities',
    );


 
    public function __construct(string $youtubeApiKey, $db,$log_type)

    {
        $this->pdo_pgsql = $db;
        $this->youtube_key = $youtubeApiKey;
        // $this->messages = $messages;
        // if ($log_type === '1') {
        //     $this->handle = \makeNewLogFile();
        // }
        // $this->log_type = $log_type;
    }

   
    public function resolve_channel_list ($channel_list) 

    {
        /*
        * Read the list of youtube channels
        */
    }

    public function get_handle () {
        return $this->handle;
    }

    public function get_videos_list () : array

    {
        return $this->video_list_items;
    }



    public function get_upload_list () : array

    {
        return $this->upload_list_ids;

    }


    public function get_search_list () : array

    {
        return $this->search_video_list;

    }

    
    public function get_reindex_value() {
        return $this->reindex;
    }

    public function set_reindex_value($reindex_value) {
        if (isset($reindex_value) && !empty($reindex_value)) {

            $this->reindex = $reindex_value;
        }
    }

    public function search_upload_list ($search_term) : array

    {

        $request_data = [
            'part' => 'snippet', 
            'q' => $search_term,
            'maxResults' => 10,
            'type' => 'channel',
            "order" => 'relevance',    
            'key' => $this->youtube_key
        ];
      
      
        //log_to_file('upload',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['search.list'],[
            'part' => 'snippet', 
            'q' => $search_term,
            'maxResults' => 10,
            'type' => 'channel',
            "order" => 'relevance',    
            'key' => $this->youtube_key
        ]);
        

        if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveSearchIds',$this->log_type,$this->handle);

            foreach ($responseObj->items as $i => $item) {
                array_push($this->search_channel_list, $item->snippet->channelId);
            }
            
            //log_to_file('completed',$this->log_type,$this->handle);

            return $this->search_channel_list;

        }  else  {

            die('Wrong data suplied');
        }

    }

    public function search_video_id_list ($search_term) : array

    {

        $request_data = [
            'part' => 'snippet', 
            'q' => $search_term,
            'maxResults' => 50,
            'type' => 'video',
            "order" => 'relevance',    
            'key' => $this->youtube_key
        ];
      
        
        //log_to_file('searchVideo',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['search.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveVideosIds',$this->log_type,$this->handle);
            
            foreach ($responseObj->items as $i => $item) {
                array_push($this->search_video_list, $item);
                $this->current_channel_name = $item->snippet->channelTitle;

            }
            
            if(isset($responseObj->nextPageToken) && !empty($responseObj->nextPageToken)) {
                $this->next_page_token = $responseObj->nextPageToken;
            } else {
                $this->next_page_token = null;
            }

            //log_to_file('completed',$this->log_type,$this->handle);
            return $this->search_video_list;

        }  else  {

            die('Wrong data suplied');
        }

    }
    public function search_video_next_result ($next_page_token,$search_term) : array

    {

        $request_data = [
            'part' => 'snippet', 
            'pageToken' => $next_page_token,
            'maxResults' => 50,
            'q' => $search_term,
            'type' => 'video',
            "order" => 'relevance',    
            'key' => $this->youtube_key
        ];
      
        
        //log_to_file('searchVideo',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['search.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveVideosIds',$this->log_type,$this->handle);

            foreach ($responseObj->items as $i => $item) {
                array_push($this->search_video_list, $item);
                $this->current_channel_name = $item->snippet->channelTitle;

            }
            
            if(isset($responseObj->nextPageToken) && !empty($responseObj->nextPageToken)) {
                $this->next_page_token = $responseObj->nextPageToken;
            } else {
                $this->next_page_token = null;
            }

            //log_to_file('completed',$this->log_type,$this->handle);

            return $this->search_video_list;

        }  else  {

            die('Wrong data suplied');
        }

    }

    public function fetch_user_video_list_stats( $id)

    {

        $request_data = [
            'part' => 'statistics', 
            'id' => $id,
            'key' => $this->youtube_key
        ];


        //log_to_file('fetchVideoStats',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['videos.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {

            
            foreach ($responseObj->items as $i => $item) {
           
                //log_to_file('completed',$this->log_type,$this->handle);
                return $item;
            }
            

        }  else  {

            die('Wrong data suplied');
        }
    }



    public function fetch_user_upload_list_stats_and_id(string $channel_name, $request_by_type = 'username')

    {
        if ($request_by_type === 'username') {

            $request_data = [
                'part' => 'statistics', 
                'forUsername' => $channel_name,
                'key' => $this->youtube_key
            ];

        } elseif ($request_by_type === 'channel_id') {

            $request_data = [
                'part' => 'statistics,snippet', 
                'id' => $channel_name,
                'key' => $this->youtube_key
            ];
        }
        

        //log_to_file('fetchUploadListStats',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['channels.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveUploadList',$this->log_type,$this->handle);
            
            foreach ($responseObj->items as $i => $item) {
                // if (!isset($this->channel_feed) && empty($this->channel_feed)) {
                    
                    // $this->fetch_channel_feed($item->id);
                    // }
                    // array_push($this->upload_list_ids, $item->contentDetails->relatedPlaylists->uploads);
                //log_to_file('completed',$this->log_type,$this->handle);
                return $item;
            }
            

        }  else  {

            die('Wrong data suplied');
        }
    }




    public function fetch_user_upload_list(string $channel_name, $request_by_type = 'username'): void

    {
        if ($request_by_type === 'username') {

            $request_data = [
                'part' => 'contentDetails', 
                'forUsername' => $channel_name,
                'key' => $this->youtube_key
            ];
        } elseif ($request_by_type === 'channel_id') {

            $request_data = [
                'part' => 'contentDetails', 
                'id' => $channel_name,
                'key' => $this->youtube_key
            ];
        }
        
        //log_to_file('fetchUploadList',$this->log_type,$this->handle);


        $responseObj = $this->youtube_api_request($this->resources['channels.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {


            //log_to_file('saveUploadListId',$this->log_type,$this->handle);

            
            foreach ($responseObj->items as $i => $item) {
                if (!isset($this->channel_feed) && empty($this->channel_feed)) {

                    $this->fetch_channel_feed($item->id);
                }
                array_push($this->upload_list_ids, $item->contentDetails->relatedPlaylists->uploads);
            }
            //log_to_file('completed',$this->log_type,$this->handle);



        }  else  {

            die('Wrong data suplied');
        }
    }


     
    protected function youtube_api_request($_url,$option) 

    {

         $url = $_url . "?" . http_build_query($option, 'a', '&');
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
         curl_setopt($curl, CURLOPT_REFERER, "https://localhost");
      
        //  $json_response = curl_exec($curl);
        
         if (($response = curl_exec($curl)) === FALSE) {
            echo curl_error($curl);
            exit();
        }
         curl_close($curl);
      
         $responseObj = json_decode($response);
         
         return $responseObj;
    }

    public function generate_video_data( $video_item, $publisher): array

    {
    
        //log_to_file('fetchVideoProperties',$this->log_type,$this->handle);

        $video_data  = [];
        $video_data['embed'] = $this->fetch_video_embed_code($video_item);
        $video_data['title'] = $this->fetch_video_title($video_item);
        $video_data['url'] = $this->fetch_video_url($video_item);
        $video_data['video_id'] = $this->fetch_video_id($video_item);
        $video_data['published_by'] = $publisher;
        return $video_data;


    }
  
    
    public function fetch_videos_id_list(string $uplod_list_id): void

    {
        

        //log_to_file('fetchIdVideoList',$this->log_type,$this->handle);


         $responseObj = $this->youtube_api_request($this->resources['playlistItems.list'], [
         'part' => 'snippet', 
         'playlistId' => $uplod_list_id,
         'maxResults' => 10,
         'page' => $this->youtube_key,
         'key' => $this->youtube_key

         ]);
        
         if (is_a($responseObj, "stdClass")) {
 
            //log_to_file('saveUploadListId',0,$this->handle);

            
            foreach ($responseObj->items as $i => $item) {
                array_push($this->video_list_ids, $item->snippet->resourceId->videoId);
                $this->current_channel_name = $item->snippet->channelTitle;
            }
            //log_to_file('completed',$this->log_type,$this->handle);


        }  else  {
            die('Wrong data suplied');
        }
    }

    public function fetch_next_videos_id_list($uplod_list_id,string $next_page_token) :void

    {
        //log_to_file('fetchIdVideoList',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['playlistItems.list'], [
            'part' => 'snippet',
            'playlistId' => $uplod_list_id,
            'maxResults' => 10,
            'key' => $this->youtube_key,
            "pageToken" => $next_page_token
        ]);

         if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveUploadListId',$this->log_type,$this->handle);

            foreach ($responseObj->items as $i => $item) {
                if ($i === 0) {

                    $this->data_index++;
                }
                array_push($this->video_list_items, $item);
            }

            if(isset($responseObj->nextPageToken) && !empty($responseObj->nextPageToken)) {
                $this->next_page_token = $responseObj->nextPageToken;
            } else {
                $this->next_page_token = null;
            }
            //log_to_file('completed',$this->log_type,$this->handle);

            
        }  else  {
            die('Wrong data suplied');
        }
    }

    public function fetch_videos_item_list(string $uplod_list_id): array

    {

        //log_to_file('fetchingPlaylistIds',$this->log_type,$this->handle);

        $responseObj = $this->youtube_api_request($this->resources['playlistItems.list'], [
            'part' => 'snippet', 
            'playlistId' => $uplod_list_id,
            'maxResults' => 10,
            'key' => $this->youtube_key
        ]);

         if (is_a($responseObj, "stdClass")) {

            //log_to_file('saveUploadListId',$this->log_type,$this->handle);

            
            foreach ($responseObj->items as $i => $item) {
                array_push($this->video_list_items, $item);
            }

            $this->data_index = $i;

            if (isset($responseObj->nextPageToken) && !empty($responseObj->nextPageToken)) {
                $this->next_page_token = $responseObj->nextPageToken;
            }

            return $this->video_list_items;
            
        }  else  {
            die('Wrong data suplied');
        }
    }

    public function fetch_video_embed_code($video): string

    {  
  
        //log_to_file('fetchEmbedCode',$this->log_type,$this->handle);


        if (!empty($video) && isset($video) && isset($video->snippet->resourceId->videoId)) {

            return $this->url_embed_part . $video->snippet->resourceId->videoId;
        } elseif (!empty($video) && isset($video) && isset($video->id->videoId)) {
            return $this->url_embed_part . $video->id->videoId;

        }
        
    }

    public function get_next_page_token()

    {  
        //log_to_file('fetchNextToken',$this->log_type,$this->handle);

        return  $this->next_page_token;
        
    }

    private function get_html_from_feed() : string 
    
    {
        if (isset($this->channel_feed) && !empty($this->channel_feed)) {
             
            try {
                return file_get_contents($this->channel_feed);
            } 
            
            catch (Exception $e) {
                throw new Exception($e->getMessage(), (int)$e->getCode());
            }
        }
    }

   

    private function get_feed_urls($simple_dom) : array

    {  
        $links = $simple_dom->find('entry link');
        $temp = [];

        if (!empty($links)) {
            
            foreach ($links as $entry_index => $entry) {
                    $temp[] = $entry->attr['href'];
            }
        }
        
        return $temp;
    }

    private function get_feed_videos_title($simple_dom) : array

    {  
        $titles = $simple_dom->find('entry title');
        $temp = [];

        if (!empty($titles)) {
            
            foreach ($titles as $entry_index => $entry) {
                $temp[] = $entry->nodes[0]->_['4'];
            }
        }
        
        return $temp;
    }

    private function get_feed_videos_embed_code($simple_dom) : array

    {  
        $embed_codes = $simple_dom->find('entry yt:videoId');
        $temp = [];

        if (!empty($embed_codes)) {
            
            foreach ($embed_codes as $entry_index => $entry) {
                $temp[] = $this->url_embed_part . $entry->nodes[0]->_['4'];
            }
        }
        
        return $temp;
    }


    private function get_videos_author($simple_dom) : array

    {  
        $video_authors = $simple_dom->find('author name');
        $temp = [];

        if (!empty($video_authors)) {
            
            foreach ($video_authors as $entry_index => $entry) {
                    $temp[] = $entry->nodes[0]->_['4'];
            }
        }
        
        return $temp;
    }


    private function get_videos_uuid($simple_dom) : array

    {  
        {  
            $video_uuids = $simple_dom->find('entry yt:videoId');
            $temp = [];
    
            if (!empty($video_uuids)) {
                
                foreach ($video_uuids as $entry_index => $entry) {
                    $temp[] = $entry->nodes[0]->_['4'];
                }
            }
            
            return $temp;
        }
    }

    


    public function fetch_channel_feed($channel_id): void

    {  
        //log_to_file('fetchFeed',$this->log_type,$this->handle);

        if (empty($this->channel_feed) && !isset($this->channel_feed)) {
            $feed_videos_entries = [];
            $this->channel_feed = $this->url_fed_part_channel . $channel_id;
            echo "feed" . $this->channel_feed;
            $text = file_get_contents($this->channel_feed);
            $a =$this->get_text_beetween_tags($text,'link');
            $html = $this->get_html_from_feed();
            $simple_dom  = str_get_html($html);
            $feed_videos_entries['urls'] = $this->get_feed_urls($simple_dom);
            $feed_videos_entries['authors'] = $this->get_videos_author($simple_dom);
            $feed_videos_entries['titles'] = $this->get_feed_videos_title($simple_dom);
            $feed_videos_entries['embeds'] = $this->get_feed_videos_embed_code($simple_dom);
           
 
        }
        
    }


    public function fetch_video_url($video): string

    {  
        //log_to_file('fetchUrl',$this->log_type,$this->handle);

        if (!empty($video) && isset($video) && isset($video->snippet->resourceId->videoId)) {

            return $this->url_watch_part . $video->snippet->resourceId->videoId;
        } elseif (!empty($video) && isset($video) && isset($video->id->videoId)) {
            return $this->url_watch_part . $video->id->videoId;

        }
        
    }

    public function fetch_video_id($video): string

    {  

        //log_to_file('fetchId',$this->log_type,$this->handle);


        if (!empty($video) && isset($video) && isset($video->snippet->resourceId->videoId)) {

            return  $video->snippet->resourceId->videoId;
        } elseif (!empty($video) && isset($video) && isset($video->id->videoId)) {
            return  $video->id->videoId;

        }
        
    }


    public function get_data_index(): int

    {  
      
        return $this->data_index;
        
    }

    public function increment_data_index(): void

    {  
      
         $this->data_index++;
        
    }

    public function fetch_video_title($video_item): string

    {
        return $video_item->snippet->title;
    }

    


    
     private function get_text_beetween_tags($string, $tagname) 
     {
        
        if ($tagname === 'link'){
            preg_match_all("/<$tagname(.*?)\/>/s", $string, $matches);
            return $matches[1];
        } 

        preg_match_all("/<$tagname>(.*?)<\/$tagname>/s", $string, $matches);
        return $matches[1];
    }

    public function get_channel_title()
    
    {   
        if (!empty($this->current_channel_name)) 

        {
            return $this->current_channel_name;
        }
    }


    public function get_channel_title_from_video($video)
    
    {   

        //log_to_file('fetchTitle',$this->log_type,$this->handle);


        if (!empty($video) && isset($video) && isset($video->snippet->channelTitle)) {

            return  $video->snippet->channelTitle;
        } elseif (!empty($video) && isset($video) && isset($video->id->videoId)) {
            return "No title";

        }
    }
    
    private function  serch_list_not_empty() {
        return ($this->get_search_list() !== null)  && (!empty($this->get_search_list()));
    }

    public function search_next_videos_iteration(string $search_term, int $max_iterations) : array
    
    {   
        $cureent_iteration = 0;
        $this->search_video_id_list($search_term);

        while (!empty($this->get_next_page_token()) && ($cureent_iteration < $max_iterations - 1)) {

            $this->search_video_next_result($this->get_next_page_token(), $search_term); 
            $cureent_iteration++;

        }

        if ( $this->serch_list_not_empty()) {
            return $this->get_search_list();
        }
    }

    public function parse_feed_from_channels(array $channels_from_to,$db)
    {
        $channels = $db->getCrawlingRange($channels_from_to['from'], $channels_from_to['to']);

    }

    public function search_videos_from_channels_feeds(array $channels_from_to,$db) 
    
    {   

        $feed_list = [];

        $channels = $db->getCrawlingRange($channels_from_to['from'], $channels_from_to['to']);
        foreach ($channels as $channel_id => $channel) {
            if (!is_null($channel['channel_id'])) {
                $this->channel_feed = $this->url_fed_part_channel . $channel['channel_id'];
            } else {
                $this->channel_feed = $this->url_fed_part_user . $channel['channel_username'];

            }

            if (!empty($this->channel_feed) && isset($this->channel_feed)) {
                $feed_videos_entries = [];
                echo "feed" . $this->channel_feed;
                $text = file_get_contents($this->channel_feed);
                $a =$this->get_text_beetween_tags($text,'link');
                $html = $this->get_html_from_feed();
                $simple_dom  = str_get_html($html);
                $feed_videos_entries['urls'] = ($this->get_feed_urls($simple_dom));
                $feed_videos_entries['authors'] = ($this->get_videos_author($simple_dom));
                $feed_videos_entries['titles'] = ($this->get_feed_videos_title($simple_dom));
                $feed_videos_entries['embeds'] = $this->get_feed_videos_embed_code($simple_dom);
                $feed_videos_entries['video_ids'] = $this->get_videos_uuid($simple_dom);
                $feed_videos_entries['views'] = [];
                $feed_videos_entries['comments'] = [];
                $feed_videos_entries['likes'] = [];
                $feed_videos_entries['dislikes'] = [];
                $feed_videos_entries['favorite'] = [];
    
                foreach($feed_videos_entries['video_ids'] as $index => $id) {
                    $video_stats = $this->fetch_user_video_list_stats($id);
                    array_push($feed_videos_entries['views'],(int)$video_stats->statistics->viewCount);
                    array_push($feed_videos_entries['comments'],(int)$video_stats->statistics->commentCount);
                    array_push($feed_videos_entries['likes'],(int)$video_stats->statistics->likeCount);
                    array_push($feed_videos_entries['dislikes'],(int)$video_stats->statistics->dislikeCount);
                    array_push($feed_videos_entries['favorite'],(int)$video_stats->statistics->favoriteCount);
    
                }
        
            }
            array_push($feed_list,$feed_videos_entries);
            
        }
        $all_links = [];


        foreach ($feed_list as $ind => $feed_data) {
            foreach ($feed_data['urls'] as $i => $data) {
                $insert_data = [];
                $insert_data['url'] = $data; 
                $insert_data['published_by'] = $feed_data['authors'][$i]; 
                $insert_data['title'] = $feed_data['titles'][$i]; 
                $insert_data['embed'] = $feed_data['embeds'][$i]; 
                $insert_data['video_id'] = $feed_data['video_ids'][$i]; 
                $insert_data['views'] =$feed_data['views'][$i];
                $insert_data['comments'] = $feed_data['comments'][$i]; 
                $insert_data['likes'] = $feed_data['likes'][$i]; 
                $insert_data['dislikes'] = $feed_data['dislikes'][$i]; 
                $insert_data['favorite'] = $feed_data['favorite'][$i]; 


                array_push($all_links, $insert_data);

            }
        }


        foreach ($all_links as $link_index => $link) {
            if (!$db->is_video_inserted($link['video_id'])) {

                $video_stats = $this->fetch_user_video_list_stats($link['video_id']);
                $video_data['views'] = (int)$link['views'];
                $video_data['comments'] = (int)$link['comments'];
                $video_data['likes'] = (int)$link['likes'];
                $video_data['dislikes'] = (int)$link['dislikes'];
                $video_data['favorite'] = (int)$link['favorite'];
                $db->insert_video_in_table($link, $this->handle);
                // log_url_to_file($this->log_type, $link['url'],$link['title'],$this->handle);

            } else {
                if ($this->get_reindex_value() == 1) {
                    $video_data = [];
                    $video_stats = $this->fetch_user_video_list_stats($link['video_id']);
                    $video_data['views'] = (int)$link['views'];
                    $video_data['comments'] = (int)$link['comments'];
                    $video_data['likes'] = (int)$link['likes'];
                    $video_data['dislikes'] = (int)$link['dislikes'];
                    $video_data['favorite'] = (int)$link['favorite'];
                    $db->update_video_stats($video_data, $link['video_id']);
                    log_reindex_url($this->log_type,$link['url'],$link['title'],$this->handle);
                }

            }
        }

    }


     public function search_videos_from_channels(array $channels_from_to,$db) 
    
    {   
        $channels = $db->getCrawlingRange($channels_from_to['from'], $channels_from_to['to']);
        
        foreach ($channels as $channel_id => $channel) {
            if (!is_null($channel['channel_id'])) {
                $this->fetch_user_upload_list($channel['channel_id'], 'channel_id');
            } else {
                $this->fetch_user_upload_list($channel['channel_username']);
            }
        
        }


        foreach ($this->get_upload_list() as $index => $upload_id ) {
            $this->fetch_videos_id_list($upload_id);
            $this->video_list_items = [];
                foreach ($this->fetch_videos_item_list($upload_id) as $index => $video) {
                    $video_data = $this->generate_video_data($video,$this->get_channel_title());
                    
                    if (!$db->is_video_inserted($this->fetch_video_id($video))) {

                        $video_stats = $this->fetch_user_video_list_stats($video_data['video_id']);
                        $video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                        $video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                        $video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                        $video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                        $video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                        $db->insert_video_in_table($video_data, $this->handle);

                        // log_url_to_file($this->log_type, $video_data['url'],$video_data['title'],$this->handle);
 
                    } else {
                        if ($this->get_reindex_value() == 1) {
                            $_video_data = [];
                            $video_stats = $this->fetch_user_video_list_stats($this->fetch_video_id($video));
                            $_video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                            $_video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                            $_video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                            $_video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                            $_video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                            $db->update_video_stats($_video_data,$this->fetch_video_id($video));
                            log_reindex_url($this->log_type,$video_data['url'],$video_data['title'],$this->handle);
                        }
        
                    }
                }

                while (!empty($this->get_next_page_token())) {
                    $this->fetch_next_videos_id_list($upload_id, $this->get_next_page_token());
                    $current_index = $this->get_data_index();
                    $video_list = $this->get_videos_list();
                    $video_list_index = count($video_list);

                    for ($current_index;$current_index < $video_list_index;$current_index++){
                        $video_data = $this->generate_video_data($video_list[$current_index],$this->get_channel_title());
                        $this->increment_data_index();

                        if (!$db->is_video_inserted($this->fetch_video_id($video_list[$current_index]))) {
                            $video_stats = $this->fetch_user_video_list_stats($video_data['video_id']);
                            $video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                            $video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                            $video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                            $video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                            $video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                            $db->insert_video_in_table($video_data, $this->handle);
                            // log_url_to_file($this->log_type,$video_data['url'],$video_data['title'],$this->handle);

                        } else {
                            if ($this->get_reindex_value() == 1) {
                                $video_stats = $this->fetch_user_video_list_stats($video_data['video_id']);
                                $video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                                $video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                                $video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                                $video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                                $video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                                $db->update_video_stats($video_data, $video_data['video_id']);
                                log_reindex_url($this->log_type,$video_data['url'],$video_data['title'],$this->handle);
                            }
            
                        }
                    }
                
                }
                
        }
    }

    public function search_videos_from_phrase($phrase,$iterations, $db) 
    
    {
        $this->search_next_videos_iteration($phrase, $iterations);

        foreach ($this->get_search_list() as $index => $video) {
            $video_data = $this->generate_video_data($video,$this->get_channel_title_from_video($video));
            
            if (!$db->is_video_inserted($video->id->videoId)) {

                $video_stats = $this->fetch_user_video_list_stats($video->id->videoId);
                $video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                $video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                $video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                $video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                $video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                $db->insert_video_in_table($video_data, $this->handle);

                // log_url_to_file($this->log_type, $video_data['url'],$video_data['title'],$this->handle);

            } else {
                if ($this->get_reindex_value() == 1) {
                    $_video_data = [];
                    $video_stats = $this->fetch_user_video_list_stats($video->id->videoId);
                    $_video_data['views'] = (int)$video_stats->statistics->viewCount ?? null;
                    $_video_data['comments'] = (int)$video_stats->statistics->commentCount ?? null;
                    $_video_data['likes'] = (int)$video_stats->statistics->likeCount ?? null;
                    $_video_data['dislikes'] = (int)$video_stats->statistics->dislikeCount ?? null ;
                    $_video_data['favorite'] = (int)$video_stats->statistics->favoriteCount ?? null;
                    $db->update_video_stats($_video_data, $video->id->videoId);
                    // log_reindex_url($this->log_type ,$video_data['url'],$video_data['title'],$this->handle);
                }

            }
        }   
    }

    
    function insert_channels_from_list ($list,$db) {
        $inserted_records = [];
        
        foreach($list as $index => $url) {

            foreach($url as $i => $channel_name) {
                
                if ($i === 'username') {
                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name);
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => $channel_name,
                        'channel_name' => null,
                        'channel_id' => null
                    ];
                    $channel_id = $db->fetch_id_if_channel_is_inserted('username', $channel_name);
                    if (!empty($channel_id)) {
                        
                        if ($db->update_video_channel($data, $channel_id)) {
                            //log_to_file('updateChannel',$this->log_type,$this->handle);
                            continue;
                        };

                    } 
                    if ($db->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };
                } else {
                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name, 'channel_id');
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => null,
                        'channel_name' => $item->snippet->title,
                        'channel_id' => $item->id
                    ];
                    $channel_id = $db->fetch_id_if_channel_is_inserted('channel_id', $item->id);
                    if (!empty($channel_id)) {
                        
                        if ($db->update_video_channel($data, $channel_id)) {
                            log_to_file('updateChannel',$this->log_type,$this->handle);
                            continue;
                        };

                    } 
                    if ($db->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };
                }
            }
        }
        $max_score = $db->channel_stats_sum();
        foreach ($inserted_records as $index => $record) {
            $db->calculate_relevance_score($record,$max_score);
        }
    }

    private function extract_string_after_occurrence($string, $search){
        $occurrence_position = strpos($string,$search);
        $search_length = strlen($search);
        return substr($string,$occurrence_position + $search_length + 1) ;
    }

     public function filter_insert_channel_list ($list) {
        $new_list = [];

        foreach ($list as $channel_list_item_index => $channel_list_item) {
            if (strpos($channel_list_item, 'user')) {
                $new_list[] = ['username' => ($this->extract_string_after_occurrence($channel_list_item,'/user'))];
            } elseif (strpos($channel_list_item, 'channel')) {
                $new_list[] = ['channel' => ($this->extract_string_after_occurrence($channel_list_item,'/channel'))];
            }
        }

        return $new_list;
    
    }
    
    
   
}


