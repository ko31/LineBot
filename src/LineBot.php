<?php

class LineBot
{
    const API_URL = 'https://trialbot-api.line.me/v1/events';
    const TO_CHANNEL = '1383378250';
    const EVENT_TYPE = '138311608800106203';

    public $channel_id;
    public $channel_secret;
    public $channel_mid;
    public $response_header;
    public $response_text;
    public $response_to;

    public function __construct($channel_id = null, $channel_secret = null, $channel_mid = null)
    {
        $this->channel_id = $channel_id;
        $this->channel_secret = $channel_secret;
        $this->channel_mid = $channel_mid;

        $this->response_header = array(
            'Content-Type: application/json; charset=UTF-8',
            'X-Line-ChannelID: ' . $this->channel_id,
            'X-Line-ChannelSecret: ' . $this->channel_secret,
            'X-Line-Trusted-User-With-ACL: ' . $this->channel_mid,
        );

        $content = $this->getContent();
        $this->response_to = $content->from;
        $this->response_text = $content->text;
    }

    public function getContent()
    {
        $contents = file_get_contents('php://input');
        $json = json_decode($contents);
        $content = $json->result{0}->content;

        return $content;
    }

    public function sendText($format = null)
    {
        if ($format) {
            $text = sprintf($format, $this->response_text);
        } else {
            $text = $this->response_text;
        }

        $response = array(
            'contentType' => 1,
            'toType' => 1,
            'text' => $text
        );

        $post_data = array(
            'to' => array($this->response_to),
            'toChannel' => self::TO_CHANNEL,
            'eventType' => self::EVENT_TYPE,
            'content' => $response
        );

        return $this->sendMessage($post_data);
    }

    public function sendImage($account_key = null, $text = null)
    {
        if (!$account_key) {
            $this->sendText('チョウシガワルイデス...');
            return;
        }

        if (!$text) {
            $text = $this->response_text;
        }

        $keyword = urlencode("'" . $text . "'");
        $credencial = 'Authorization: Basic ' . base64_encode($account_key . ":" . $account_key);
        $context = stream_context_create(array(
            'http' => array(
                'header' => $credencial
            )
        ));
        $contents = file_get_contents('https://api.datamarket.azure.com/Bing/Search/Image?$format=json&$top=10&Adult='."'".'Strict'."'".'&Query='.$keyword, 0, $context);
        $json = json_decode($contents);

        if (!$json->d->results) {
            $this->sendText('ワカリマセンデシタ...');
            return;
        }

        $images = array();
        foreach ($json->d->results as $result) {
            $images[] = array($result->MediaUrl, $result->Thumbnail->MediaUrl);
        }
        $rand = rand(0, count($images)-1);
        $image_url = $images[$rand][0];
        $image_thumb_url = $images[$rand][1];

        $response = array(
            'contentType' => 2,
            'toType' => 1,
            'originalContentUrl' => $image_url,
            'previewImageUrl' => $image_thumb_url
        );

        $post_data = array(
            'to' => array($this->response_to),
            'toChannel' => self::TO_CHANNEL,
            'eventType' => self::EVENT_TYPE,
            'content' => $response
        );

        return $this->sendMessage($post_data);
    }

    public function sendMessage($post_data)
    {
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->response_header);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
