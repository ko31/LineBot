# LineBot

LINE BOT API で作った画像検索ボットくん

## 機能

- コールバックに送られたメッセージをテキスト送信する（フォーマット指定可）
- コールバックに送られたメッセージを検索して取得した画像を送信する（Bing Search APIアカウントキー必要）

## 使い方

```
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

// テキスト送信
$bot->sendText('「%s」デスネ...');

// 画像送信
$bot->sendImage($ACCOUNT_KEY);

```

## 参考URL

* [LINE BOT APIを使ってPHPで画像検索BOT作ってみました | rui live note](http://blog.ko31.com/201604/line-bot-api-php/)
