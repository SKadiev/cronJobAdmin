<?php 
namespace Youtube\Database;
USE PDO;
    class Database
    {   
        protected $pdo_pgsql;
        public function connect($credential) {

            $pghostname = $credential['DB_HOST'];
            $pgdatabase = $credential['DB_NAME'];
            $pgusername =  $credential['DB_USER'];
            $pgpassword = $credential['DB_PASS'];
            
            $dsn_pgsql = "mysql:host=$pghostname;dbname=$pgdatabase";
            
            $options_pgsql = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                $pdo_pgsql = new \PDO($dsn_pgsql, $pgusername, $pgpassword, $options_pgsql);
                $this->pdo_pgsql = $pdo_pgsql;
                return $this;
            } 
            
            catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        public function getConnection() {
            return $this->pdo_pgsql;
        }

        public function insert_video_in_table($insertValues, $fileHadle) {
            $sql  = "INSERT INTO videos( embed, title, url, published_by, video_uuid, views, comments, likes, dislikes, favorite)
            VALUES (?, ?, ?, ?, ?, ?, ? , ? , ? , ?)";

            foreach ($insertValues as $index => $value) {
                if (!isset($value) ) {
                   $value = null;
                }
               
            }
            try {
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute([
                    $insertValues['embed'],
                    $insertValues['title'],
                    $insertValues['url'],
                    $insertValues['published_by'],
                    $insertValues['video_id'],
                    $insertValues['views'],
                    $insertValues['comments'],
                    $insertValues['likes'],
                    $insertValues['dislikes'],
                    $insertValues['favorite']

                ]);
                return true;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }

        public function get_youtube_channels () : array {

            $sql  = "select * from youtube_channel";
           
            try {
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute();
                return $statement->fetchAll();
            } catch (PDOException $e) {
                var_dump($e->getMessage());
            }
        }

        public function insert_search_phrase($insertValues) {
            
            $sql  = "INSERT INTO search_phrases(
                keywords, cpc, comp, avarage_search_volume) 
            VALUES (?, ?, ?, ?);";

            foreach ($insertValues as $index => $value) {
                if (!isset($value) ) {
                   $value = null;
                }
                
            }
            try {
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute([
                    $insertValues['keywords'],
                    $insertValues['cpc'],
                    $insertValues['comp'],
                    $insertValues['avarage_search_volume']
                
                ]);
                return true;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }

        public function getCrawlingRange($youtube_channel_rule_from, $youtube_channel_rule_to) {
            $statement = $this->pdo_pgsql->prepare("SELECT * FROM youtube_channel WHERE score BETWEEN ? AND ? ");
            $statement->execute([$youtube_channel_rule_from, $youtube_channel_rule_to]);
        
            if ($statement->rowCount() > 0 ) {
                $res = $statement->fetchAll();
                return $res;
            }
        
            $statement = null;
        
            die('Incorect rule name');
        
        }

        public function channel_stats_sum () {
            $temp_score_table = [];
            $sql  = "SELECT subscribers, video_count, views_count 
            FROM youtube_channel order by  subscribers desc";



            try {
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute();
                $res = $statement->fetchAll();
                if (count($res) > 0  && !empty($res)) {

                    foreach ($res as $index => $data) {

                        $score =  (round(($data['views_count'] ?? 0) / ($data['video_count'] ?? 0)) ?? 0) + ($res['subscribers'] ?? 0 ) ;
                        if (is_nan($score)) {
                            continue;
                        }
                        array_push($temp_score_table, $score);
                        // return $score;
                    }
                    return max($temp_score_table);
                } else {
                    return false;
                }
        
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
 
        }


        private function filter_channels_with_username ($channels) {

        }

        public function  calculate_relevance_score ($insertValues, $maxScore) {

           
            $score =  (round(($insertValues['views_count']) / ($insertValues['video_count'] ))) ;
            if (is_nan($score)) {
                $score = 0;
            }
            if (!empty($maxScore )) {

                $newScore = round(( $score / $maxScore) * 100);
                if (is_nan($newScore)){
                    return;
                }
            } else {
                $newScore = 100;
            }
            
            $sql  = "UPDATE  youtube_channel SET score = ?";

            if (is_null($insertValues['channel_id']) && !empty($insertValues['channel_username'])) {
                $sql .= ' WHERE channel_username = ?';
                $statement = $this->pdo_pgsql->prepare($sql);
                try {
                    $statement->execute([$newScore, $insertValues['channel_username']]);
                    return true;
                    
                } catch (PDOException $e) {
                    var_dump($e->getMessage());
                    return false;
                }
            } else {
                $sql .= ' WHERE channel_id = ?';
                $statement = $this->pdo_pgsql->prepare($sql);
                try {
                    $statement->execute([$newScore, $insertValues['channel_id']]);
                    return true;
                    
                } catch (PDOException $e) {
                    var_dump($e->getMessage());
                    return false;
                }
                
            }
            $statement = $this->pdo_pgsql->prepare($sql);
         
        }

        public function fetch_id_if_channel_is_inserted ($check_by_type, $search_value) {

            $sql = "SELECT id from youtube_channel where";
            if ($check_by_type === 'username') {
                $sql .= " channel_username = ?";
                $statement = $this->pdo_pgsql->prepare($sql);
                $row = $statement->execute([$search_value]);
                if ($statement->rowCount() > 0 ) {
                    return ($statement->fetch())['id'];
                } 
                return false;

            } else if ($check_by_type === 'channel_id') {
                $sql .= " channel_id = ?";
                $statement = $this->pdo_pgsql->prepare($sql);
                $row = $statement->execute([$search_value]);
                if ($statement->rowCount() > 0 ) {
                    return ($statement->fetch())['id'];
                } 
                return false; 
            }

        }

        public function update_video_channel($insertValues, $channel_id) {
            
            $sql  = "UPDATE youtube_channel set  subscribers = ? , views_count = ?, video_count = ? WHERE id = ? ";

            foreach ($insertValues as $index => $value) {
                if (!isset($value) ) {
                   $value = null;
                }
                
            }
            try {
                
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute([
                    $insertValues['subscribers'],
                    $insertValues['views_count'],
                    $insertValues['video_count'],
                    $channel_id
                
                ]);
                return true;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }


        public function update_video_stats($insertValues, $video_id) {
            
            $sql  = "UPDATE videos set  views = ? , comments = ?, likes = ?, dislikes = ?, favorite = ? WHERE video_uuid = ? ";

            foreach ($insertValues as $index => $value) {
                if (!isset($value) ) {
                   $value = null;
                }
                
            }
            try {
                
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute([
                    $insertValues['views'],
                    $insertValues['comments'],
                    $insertValues['likes'],
                    $insertValues['dislikes'],
                    $insertValues['favorite'],
                    $video_id
                
                ]);
                return true;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }



        public function insert_video_channel($insertValues) {
            
            $sql  = "INSERT INTO youtube_channel(
                channel_name, channel_username, channel_id, subscribers, 
                views_count, video_count)
            VALUES (?, ?, ?, ?, ?, ?);";

            foreach ($insertValues as $index => $value) {
                if (!isset($value) ) {
                   $value = null;
                }
                
            }
            try {
                
                // $score = ($insertValues['subscribers'] ?? 0 ) + ($insertValues['views_count'] ?? 0) + ($insertValues['video_count'] ?? 0);
                // if (!empty($maxScore )) {

                //     $newScore = ($score / $maxScore['sum']) * 100;
                // } else {
                //     $newScore = 100;
                // }
                $statement = $this->pdo_pgsql->prepare($sql);
                $statement->execute([
                    $insertValues['channel_name'],
                    $insertValues['channel_username'],
                    $insertValues['channel_id'],
                    $insertValues['subscribers'],
                    $insertValues['views_count'],
                    $insertValues['video_count']

                
                ]);
                return true;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }


        public function is_video_inserted($video_id) {

            $statement = $this->pdo_pgsql->prepare("SELECT id FROM videos WHERE video_uuid=?");
            $statement->execute([$video_id]);
        
            if ($statement->rowCount() > 0 ) {
                return true;
            }

            return false;
        
        }
    }


?>