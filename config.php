<?php
    
// configuration cache
$maxage = 3600;
$gmt = 7; // If your server datetime located in south east, so it means GMT+7.

// firewall
$firewall_origin = false;   // this to activate firewall (default is false)
$allow_no_referer = false;  // allow access for no-referer policy (default is false)
$allow_origin = [
    'https://yourdomain1.com',
    'https://yourdomain2.com'
];