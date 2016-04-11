<?php

require_once('LineBot.php');

// LINE:チャンネルID
$CHANNEL_ID = '[LINEのチャンネルID]';
// LINE:チャンネルシークレット
$CHANNEL_SECRET = '[LINEのチェンネルシークレット';
// LINE:MID
$CHANNEL_MID = '[LINEのMID]';

// Bingアカウントキー
$ACCOUNT_KEY = '[Bing Search APIのアカウントキー]';

$bot = new LineBot($CHANNEL_ID, $CHANNEL_SECRET, $CHANNEL_MID);
$bot->sendText('「%s」デスネ...');
$bot->sendImage($ACCOUNT_KEY);

