<?php

$body = '{"payment_reference":"PAY-004-MKSLKD","external_transaction_id":"DW-TEST-003","status":"paid","paid_at":"2026-04-08 14:30:00"}';
$secret = 'MY_SUPER_SECRET_KEY_123';

echo hash_hmac('sha256', $body, $secret);