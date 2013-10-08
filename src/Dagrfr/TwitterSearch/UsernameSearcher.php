<?php namespace Dagrfr\TwitterSearch;

class UsernameSearcher
{
    static public function getFeedByUsername($sUserName, $limit = 10)
    {
        $aRet = array();

        $consumerKey                   = \Config::get('twitter-search::consumer.key');
        $consumerSecret                = \Config::get('twitter-search::consumer.secret');
        $bearerTokenCredentials        = $consumerKey . ":" . $consumerSecret;
        $encodedBearerTokenCredentials = base64_encode($bearerTokenCredentials);

        $requestTokenUrl = \Config::get('twitter-search::twitter.requestTokenUrl');
        $authorizeUrl    = \Config::get('twitter-search::twitter.authorizeUrl');
        $accessTokenUrl  = \Config::get('twitter-search::twitter.accessTokenUrl');
        $callbackUrl     = \Config::get('twitter-search::twitter.callbackUrl');
        $searchEndpoint  = \Config::get('twitter-search::twitter.timelineSearchUrl');

        // STEP 1 - get our access token
        $data = http_build_query(array('grant_type'=>'client_credentials'));
        $opts = array(
            'http'=>array(
                'method'=>  'POST',
                'header'=>  'Authorization: Basic ' . $encodedBearerTokenCredentials."\r\n"
                    . 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'."\r\n"
                    . 'Content-Length: '.strlen($data)."\r\n",
                'content'=> $data
            )
        );
        $context = stream_context_create($opts);
        $contents = file_get_contents($requestTokenUrl,null,$context);
        $response = json_decode($contents);

        // now we have an access token
        $accessToken = $response->access_token;

        // STEP 2 - Search the API with the new Token
        $opts = array(
            'http' => array(
                'method' =>  'GET',
                'header' =>  'Authorization: Bearer ' . $accessToken."\r\n"
            )
        );
        $context = stream_context_create($opts);
        $apiJson = file_get_contents($searchEndpoint."?count=".$limit."&screen_name=".$sUserName, null, $context);

        $jsonTweets = json_decode($apiJson);

        if(is_array($jsonTweets))
        {
            foreach($jsonTweets as $twit)
            {
                //$description = stripslashes(htmlentities($twit->text,ENT_QUOTES,'UTF-8'));
                $description = $twit->text;
                // ADD HYPERLINKS
                $description = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i', '<a href="\1">\1</a>', $description);
                // ADD TWITTER LINKS
                $description = preg_replace('/@([a-z0-9]+)/i', '<a href="http://www.twitter.com/#!/\1">@\1</a>', $description);
                $twit->linkedText = $description;
                $aRet[] = $twit;
            }
        }

        return $aRet;
    }

}