<?php
namespace Itoop\Facebook\Fusion;

/*
 * This file is part of the Itoop.Facebook package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class FacebookImplementation extends AbstractFusionObject {
	public function evaluate() {
			
			$appID = $this->tsValue('appID');
			$appSecret = $this->tsValue('appSecret');
			$token = $this->tsValue('token');
			$fburl = $this->tsValue('fburl');
			
			$url = '/'.$fburl.'?fields=posts.limit(10){created_time,backdated_time,full_picture,picture,message,story}&locale=de_DE';
			
			// placeholder with advice for missing credentials
			if(empty($fburl) || empty($appID) || empty($appSecret) || empty($token)) {
				$feed[] = array(
					'error' => 'Please fill in data (appID, app secret, token and facebook url).',						
				);
				return $feed;	
			}			
			
			$fb = new \Facebook\Facebook([
  				'app_id' => '$appID',
  				'app_secret' => '$appSecret',
  				'default_graph_version' => 'v2.9',
  			]);
				
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get($url, $token);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
    			echo 'Graph returned an error: ' . $e->getMessage();
    			exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
    			echo 'Facebook SDK returned an error: ' . $e->getMessage();
    			exit;
			}
				
			$response = $fb->get($url, $token);
			$graphObject = $response->getGraphObject();
			$array= json_decode($graphObject, true);
			
			foreach($array['posts'] as $post) {
															
				$posttime = false;
				if($posttime === false && isset($post['created_time']) && !isset($post['backdated_time']) && !empty($post['created_time'])) {
					$posttime = $post['created_time']['date'];
				} else {
					$posttime = $post['backdated_time']['date'];
				}
				// get nice posttime format
				//				
				$pt = date("d.m.Y", strtotime($posttime));
				$today = date('d.m.Y');
				$oneday = strtotime ('-1 day',strtotime($today));
				$twodays = strtotime ('-2 days',strtotime($today));
				$yesterday = date ('d.m.Y',$oneday );
				$daybeforeyesterday = date ('d.m.Y',$twodays );
				$monthnames = array(
					1=>"Januar",
   					2=>"Februar",
   					3=>"März",
   					4=>"April",
   					5=>"Mai",
   					6=>"Juni",
   					7=>"Juli",
   					8=>"August",
   					9=>"September",
   					10=>"Oktober",
   					11=>"November",
   					12=>"Dezember"
   				);
				if($pt == $today) {
					$posttime = 'Heute';
				} elseif ($pt == $yesterday) {
					$posttime = 'Gestern';			
				} elseif ($pt == $daybeforeyesterday) {
					$posttime = 'Vorgestern';			
				} else {
					$posttimeday = date("d", strtotime($posttime));
					$posttimemonth = date("n", strtotime($posttime));
					$ptm = $monthnames[$posttimemonth];
   					$posttime = $posttimeday.'. '.$ptm;
   				}
				
				$story = false;
				if($story === false && isset($post['story']) && !empty($post['story'])) {
					$story = $post['story'];
				}
				
					
				$message = false;
				if($message === false && isset($post['message']) && !empty($post['message'])) {
					$message = $post['message'];
						
					// render url's in text - see https://css-tricks.com/snippets/php/find-urls-in-text-make-links/
					// The Regular Expression filter
					$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
         				preg_match_all($reg_exUrl, $message, $matches);
         				// set link for each url in text respectively message
         				$usedPatterns = array();
         				foreach($matches[0] as $pattern){
	         				if(!array_key_exists($pattern, $usedPatterns)){
               					$usedPatterns[$pattern]=true;
                  				$message = str_replace($pattern, '<a href="'.$pattern.'" rel="nofollow" target="_blank">'.$pattern.'</a>', $message);
						}
            				}
				}
				
					
				$image = false;
				if($image === false && isset($post['picture']) && !empty($post['picture'])) {
					$image = $post['picture'];
				}
				
					
				$full_picture = false;
				if($full_picture === false && isset($post['full_picture']) && !empty($post['full_picture'])) {
					$full_picture = $post['full_picture'];
				}
				
					
				$feed[] = array(
					'posttime' => $posttime,						
					'message' => $message,
					'story' => $story,
					'image' => $image,
					'full_picture' => $full_picture
				);
					
			}
				
			return $feed;
	}
}
