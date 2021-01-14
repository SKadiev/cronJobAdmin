<?php
namespace App\Services;
use App\Models\YoutubeChannel;

class ChannelsFromCsv

{
    private $youtube_key;
    protected $resources = array(
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
        'search.list' => 'https://www.googleapis.com/youtube/v3/search',
        'channels.list' => 'https://www.googleapis.com/youtube/v3/channels',
        'playlists.list' => 'https://www.googleapis.com/youtube/v3/playlists',
        'playlistItems.list' => 'https://www.googleapis.com/youtube/v3/playlistItems',
        'activities' => 'https://www.googleapis.com/youtube/v3/activities',
        'channelUrlPath' => 'https://www.youtube.com/channel/'
        
    );

    public function __construct ($youtube_api_key) {
        $this->youtube_key = $youtube_api_key;
    }

    public function parseChannels ($temp) {
        $list = $this->filter_insert_channel_list($temp);
        return  $this->insert_channels_from_list($list);
    }

    public function parseChannel ($tempData) {
        $list = $this->filter_insert_channel_list($tempData);
        return  $this->insert_channel($list);
    }
    
    private function extract_string_after_occurrence($string, $search){
        $occurrence_position = strpos($string,$search);
        $search_length = strlen($search);
        return substr($string,$occurrence_position + $search_length + 1) ;
    }

    private function filter_insert_channel_list ($list) {
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


    private function  insert_channel($fulllist) {
        $inserted_records = [];
        
        foreach ($fulllist as $i => $items) {

            foreach($items as $i => $channel_name) {
                
                if ($i === 'username') {
                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name['channel']);
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => $channel_name,
                        'channel_name' => null,
                        'channel_id' => null
                    ];
                    $channel_id = $this->fetch_id_if_channel_is_inserted('username', $channel_name);
                    dd($channel_id);
                    if (!empty($channel_id)) {
                        
                        if ($this->update_video_channel($data, $channel_id)) {
                            continue;
                        };

                    } 
                    if ($this->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };

                } else {

                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name, $i);
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => null,
                        'channel_name' => $item->snippet->title,
                        'channel_id' => $item->id
                    ];
                    $channel_id = $this->fetch_id_if_channel_is_inserted('channel_id', $item->id);
                    if (!empty($channel_id)) {
                        
                        if ($this->update_video_channel($data, $channel_id)) {
                            continue;
                        };

                    } 
                    if ($this->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };
                }
            }
        }
        
        $max_score = $this->channel_stats_sum();
    
        foreach ($inserted_records as $index => $record) {
           
            $this->calculate_relevance_score($record,$max_score);
        }

        return $inserted_records;
    }



    private function  insert_channels_from_list ($fulllist) {
        YoutubeChannel::truncate();
        $inserted_records = [];
        
        foreach ($fulllist as $i => $items) {

            foreach($items as $i => $channel_name) {
                
                if ($i === 'username') {
                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name['channel']);
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => $channel_name,
                        'channel_name' => null,
                        'channel_id' => null
                    ];
                    $channel_id = $this->fetch_id_if_channel_is_inserted('username', $channel_name);
                    if (!empty($channel_id)) {
                        
                        if ($this->update_video_channel($data, $channel_id)) {
                            continue;
                        };

                    } 
                    if ($this->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };

                } else {

                    $item = $this->fetch_user_upload_list_stats_and_id($channel_name, $i);
                    $data = [
                        'views_count' => $item->statistics->viewCount,
                        'subscribers' => $item->statistics->subscriberCount,
                        'video_count' => $item->statistics->videoCount,
                        'channel_username' => null,
                        'channel_name' => $item->snippet->title,
                        'channel_id' => $item->id
                    ];
                    $channel_id = $this->fetch_id_if_channel_is_inserted('channel_id', $item->id);
                    if (!empty($channel_id)) {
                        
                        if ($this->update_video_channel($data, $channel_id)) {
                            continue;
                        };

                    } 
                    if ($this->insert_video_channel($data)) {
                        array_push($inserted_records,$data);
                    };
                }
            }
        }
        
        $max_score = $this->channel_stats_sum();
    
        foreach ($inserted_records as $index => $record) {
           
            $this->calculate_relevance_score($record,$max_score);
        }

        return $inserted_records;
    }

    

    private function fetch_user_upload_list_stats_and_id(string $channel_name, $request_by_type = 'username') {
        $request_data;

        if ($request_by_type === 'username') {

            $request_data = [
                'part' => 'statistics', 
                'forUsername' => $channel_name,
                'key' => $this->youtube_key
            ];

        } elseif ($request_by_type === 'channel') {

            $request_data = [
                'part' => 'statistics,snippet', 
                'id' => $channel_name,
                'key' => $this->youtube_key
            ];
        }
        
        $responseObj = $this->youtube_api_request($this->resources['channels.list'], $request_data);

        if (is_a($responseObj, "stdClass")) {

            
            foreach ($responseObj->items as $i => $item) {
    
                return $item;
            }
            

        }  else  {

            die('Wrong data suplied');
        }
    }

    private function youtube_api_request($_url,$option)  {

         $url = $_url . "?" . http_build_query($option, 'a', '&');
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
         curl_setopt($curl, CURLOPT_REFERER, "https://localhost:8001");
      
        
         if (($response = curl_exec($curl)) === FALSE) {
            echo curl_error($curl);
            exit();
         }
         curl_close($curl);
      
         $responseObj = json_decode($response);
         
         return $responseObj;
    }




    private function update_video_channel($insertValues, $channel_id) {


        $channel = YoutubeChannel::find($channel_id);
            
        foreach ($insertValues as $index => $value) {
            if (!isset($value) ) {
               $value = null;
            }
            
        }

        $channel->update([
            
            "subscribers" => request("name"),
            "views_count" =>request("views_count"),
            "views_count" =>request("views_count")

        ]);
        
        return true;


    }


    
    private function insert_video_channel($insertValues) {
    
        YoutubeChannel::create(
            $insertValues
        );

        return true;
       
    }

    private function fetch_id_if_channel_is_inserted ($check_by_type, $search_value) {

       $channel_id =  YoutubeChannel::where("$check_by_type" , '=', $search_value )->first();
       if (!empty($channel_id)) {
           return $channel_id->id;
       }
       return  false;
   

    }

    private function channel_stats_sum () {

       $temp_score_table = [];
       $channels =  YoutubeChannel::orderBy('subscribers', 'DESC')->get();

        if (!$channels->isEmpty()) {
            foreach ($channels as $index => $data) {
                

                if ($data['views_count'] === 0  || $data['video_count'] === 0 ) {
                    
                    $score =  ($data['subscribers'] ?? 0 ) ;
                } else {
                    $score =  (round(($data['views_count'] ?? 0) / ($data['video_count'] ?? 0)) ?? 0) + ($data['subscribers'] ?? 0 ) ;

                }

                if (is_nan($score)) {
                    continue;
                }
                array_push($temp_score_table, $score);
            }


            return max($temp_score_table);
        } else {
            return false;
        }
    
       
    }

    private function  calculate_relevance_score ($insertValues, $maxScore) {

        $score;   
        
        if ($insertValues['views_count'] == 0  || $insertValues['video_count'] == 0 ) {
                    
            $score =  ($insertValues['subscribers'] ?? 0 ) ;
        } else {
            $score =  (round(($insertValues['views_count'] ?? 0) / ($insertValues['video_count'] ?? 0)) ?? 0) + ($insertValues['subscribers'] ?? 0 ) ;

        }
        if (is_nan($score)) {
            $score = 0;
        }
        if (!empty($maxScore)) {
            
            $newScore = round(( $score / $maxScore) * 100);
            if (is_nan($newScore)){
                return;
            }
        } else {
            $newScore = 100;
        }
        
        
        if (is_null($insertValues['channel_id']) && !empty($insertValues['channel_username'])) {
           
            YoutubeChannel::where(['channel_username' => $insertValues['channel_username'] ])->first()->update(["score" => $newScore]);

        } else {
          
            YoutubeChannel::where(['channel_id' => $insertValues['channel_id'] ])->first()->update(["score" => $newScore]);
       
        }
     
    }

    public function composeChannelUrls ($channelList) {

        $temp = [];

        foreach ($channelList as $channel) {

            if ($channel->channel_username) 
                array_push($temp,  $this->resources['channelUrlPath'] . $channel->channel_username);
            elseif ($channel->channel_name) 
                array_push($temp, $this->resources['channelUrlPath'] . $channel->channel_name);

        }

        return $temp;
    }

}