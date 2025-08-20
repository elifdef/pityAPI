<?php

const MIN_CHAR_USERNAME = 5;
const MAX_CHAR_USERNAME = 32;
const MIN_CHAR_PASSWORD = 8;

const DEFAULT_ROLE = 'user';
const LENGTH_USER_TOKEN = 64;
const LENGTH_USER_SESSION_TOKEN = 32;

const CLOUDFLARE_IP_LIST = [
    '103.21.244.0/22',
    '103.22.200.0/22',
    '103.31.4.0/22',
    '104.16.0.0/13',
    '104.24.0.0/14',
    '108.162.192.0/18',
    '131.0.72.0/22',
    '141.101.64.0/18',
    '162.158.0.0/15',
    '172.64.0.0/13',
    '173.245.48.0/20',
    '188.114.96.0/20',
    '190.93.240.0/20',
    '197.234.240.0/22',
    '198.41.128.0/17'
];
