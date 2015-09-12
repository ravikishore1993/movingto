<?php
require 'vendor/autoload.php';
include 'libs/quikr.php';
include 'libs/simple_html_dom.php';
$config = include 'config/config.php';
$tokenobject = generateToken($config);
$config['tokenobject'] = json_decode($tokenobject);

$app = new \Slim\Slim(
	array
		(
            'debug' => $config['DEBUG'],
            'mode' => $config['MODE'],
            'templates.path' => 'templates',
        ));

$app->get('/trending/:id', function ($id) {
	global $tokenobject;
	global $config;
	$url = $config['API_LINK'].$config['TRENDING'];
	$params = array('city'=>$id);
	$url .= '?' . http_build_query($params);
    $ch = curl_init( $url);
    $ch = generateCurl($ch, $config['TRENDING'], $config);
    $result = curl_exec($ch);
	curl_close($ch);
	echo $result;
});

$app->get('/category/:category/:city', function ($category,$city) {
	global $tokenobject;
	global $config;	
	$url = $config['API_LINK'].$config['CATEGORY'];
	$params = array('categoryId'=>$category, 'city' => $city,'attribute_Ad_Type' => 'offer', 'verified_mobile' => '1');
	$url .= '?' . http_build_query($params);
    $ch = curl_init( $url);
    $ch = generateCurl($ch, $config['CATEGORY'], $config);
    $result = curl_exec($ch);
	curl_close($ch);
	$html = file_get_html('http://delhi.quikr.com/Honda-city-1.5-exi-1st-owner-W0QQAdIdZ233752016');
	foreach($html->find('.mobile-no') as $element) 
       $text =  $element->plaintext;
   	   $number = substr($text, strpos($text, '+'))	;
	echo $result;	
});


$app->get('/',function () use($app) {
	global $tokenobject;
	global $config;	
	$app->render('homepage.php');
});

$app->get('/category/:category/:city/:lta1/:lon1', function ($category,$city,$lat1,$lon1) {
	global $tokenobject;
	global $config;	
	$cnt=0;
	$final_result=array();
	$final_result=json_encode($final_result);
	$url = $config['API_LINK'].$config['CATEGORY'];
	$params = array('categoryId'=>$category, 'city' => $city, 'size'=>100);
	$url .= '?' . http_build_query($params);
    $ch = curl_init( $url);
    $ch = generateCurl($ch, $config['CATEGORY'], $config);
    $result = curl_exec($ch);
	curl_close($ch);
	$result=json_decode($result,true);
	$nums=$result['AdsByCategoryResponse']['AdsByCategoryData']['total'];
	$result=json_encode($result);
	$nums=intval($nums/100);
	if($nums==0)
	{
		$final_result=json_decode($final_result,true);
		$data=json_decode($result,true);
		$docs=$data['AdsByCategoryResponse']['AdsByCategoryData']['docs'];
		foreach ($docs as $i)
		 {
			if(array_key_exists('attribute_Ad_Type',$i) && array_key_exists('geo_pin',$i) && array_key_exists('title',$i) && array_key_exists('url',$i))
			{
				$attribute_Ad_Typ=$i['attribute_Ad_Type'];
				$geo_pin=$i['geo_pin'];
				$lat2=explode(",", $geo_pin)[0];
				$lon2=explode(",", $geo_pin)[1];

				$theta = $lon1 - $lon2;
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				$dist = acos($dist);
				$dist = rad2deg($dist);
				$miles = $dist * 60 * 1.1515;
			

				if ($attribute_Ad_Typ=="offer" && $miles * 1.609344<=20) 
				{
					$res=array();
					$res["title"]=$i['title'];	
					$res["lat"]=$lat2;	
					$res["lon"]=$lon2;	
					$res["url"]=$i['url'];	
					$final_result[]=$res;
					$cnt++;
					if($cnt==10)
						break;
				}
			}
		}
		
	  	echo json_encode(array('count' => count($final_result), 'data' => $final_result));
	}
	else
	{
		for($j=0;$j<$nums;$j+=1)
		{
			if($cnt==10)
				break;
			else
			{
				$url = $config['API_LINK'].$config['CATEGORY'];
				$params = array('categoryId'=>$category,'city' => $city, 'from'=>$j*100, 'size'=>100);
				$url .= '?' . http_build_query($params);
			    $ch = curl_init( $url);
			    $ch = generateCurl($ch, $config['CATEGORY'], $config);
			    $result = curl_exec($ch);
				curl_close($ch);
				{
					$final_result=json_decode($final_result,true);
					$data=json_decode($result,true);
					$docs=$data['AdsByCategoryResponse']['AdsByCategoryData']['docs'];
					foreach ($docs as $i)
					 {
						if(array_key_exists('attribute_Ad_Type',$i) && array_key_exists('geo_pin',$i) && array_key_exists('title',$i) && array_key_exists('url',$i))
						{
							$attribute_Ad_Typ=$i['attribute_Ad_Type'];
							$geo_pin=$i['geo_pin'];
							$lat2=explode(",", $geo_pin)[0];
							$lon2=explode(",", $geo_pin)[1];

							/*$theta = $lon1 - $lon2;
							$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
							$dist = acos($dist);
							$dist = rad2deg($dist);
							$miles = $dist * 60 * 1.1515;*/
						

							if ($attribute_Ad_Typ=="offer") //&& $miles * 1.609344<=20) 
							{
								$res=array();
								$res["title"]=$i['title'];	
								$res["lat"]=$lat2;	
								$res["lon"]=$lon2;	
								$res["url"]=$i['url'];	
								$final_result[]=$res;
								$cnt++;
								if($cnt==10)
									break;
							}
						}
					}
					
				  	
				}
			}
		}
		echo json_encode(array('count' => count($final_result), 'data' => $final_result));
	}
});

$app->run();
