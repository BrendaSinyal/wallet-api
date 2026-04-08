<?php

$body = '{"payment_reference":"PAY-002-CZIH0W","external_transaction_id":"DW-TEST-002","status":"paid","paid_at":"2026-04-08 10:30:00"}';
$secret = 'MY_SUPER_SECRET_KEY_123';

echo hash_hmac('sha256', $body, $secret);