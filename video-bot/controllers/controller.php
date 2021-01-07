<?php
require_once 'vendor/autoload.php';
include_once "settings/conf.php";
include_once "includes/simple_html_dom.php";
include_once "includes/url_to_absolute.php";
// include_once "logs/log-messages.php";
use Youtube\Database;
use Youtube\controllers\facade\YouTubeApiFacade;

use function GuzzleHttp\json_decode;

$db = new Database\Database();
$handle = $db->connect([
    "DB_HOST" => getenv('DB_HOST'),
    "DB_NAME" => getenv('DB_NAME'), 
    "DB_USER" => getenv('DB_USER'), 
    "DB_PASS" => getenv('DB_PASS')
]);

$log_type = getenv('LOG_TYPE');
$api_key = getenv('API_KEY');

$facade = new YouTubeApiFacade($api_key, $handle, $log_type);
if (!empty($_GET['reindex'])) {
   $facade->set_reindex_value($_GET['reindex']);
} 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo '_GET has parameters<br>';

    if (!empty($_GET['all']) && !empty($_GET['rule'])) {
        // crawl from channels table
        if (!empty($_GET['reindex'])) {
            $facade->set_reindex_value((int)$_GET['reindex']);
        }
        $rules = explode('-', htmlspecialchars(urldecode($_GET['rule'])));
        $facade->search_videos_from_channels(['from' => $rules[0] , 'to' => $rules[1]],$db);
        fclose($facade->get_handle());
        die();
    }

    if (!empty($_GET['search']) && !empty($_GET['phrase']) && !empty($_GET['iterations'])) {
        // crawl from search phrase
        $phrase = urldecode($_GET['phrase']);
        $iter = $_GET['iterations'];
        $facade->search_videos_from_phrase($phrase, $iter, $db);
        fclose($facade->get_handle());
        die();
    }

    if (!empty($_GET['feed']) && !empty($_GET['rule'])) {
        // crawl feeds from feeds
        $rules = explode('-', htmlspecialchars(urldecode($_GET['rule'])));
        $facade->search_videos_from_channels_feeds(['from' => $rules[0] , 'to' => $rules[1]],$db);
        fclose($facade->get_handle());
        die();
    }
    

} elseif ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["csv_data"]) && $_FILES["csv_data"]['type'] === 'text/csv') {
      
        if ($f_pointer = fopen($_FILES['csv_data']['tmp_name'], 'r')) {
            $temp = [];

            while (! feof($f_pointer)) {
                $payload = fgetcsv($f_pointer);
                array_push($temp,$payload[0]);
                
            }
            
            $alist = $facade->filter_insert_channel_list($temp);
            $facade->insert_channels_from_list($alist,$db);
        
        }
        fclose($facade->get_handle());

    }


