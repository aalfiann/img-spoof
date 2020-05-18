<?php

// CORE
$timeout = 30;              // Timeout to request get image (default is 30)
    
// CACHE
$maxage = 3600;             // Max age of cache before expires (default is 3600)

// FIREWALL
$firewall_request = false;   // this to activate firewall (default is false)
$allow_no_referer = true;   // allow access with no referer policy (default is true)
$allow_domain = [           // just write the domain or sub domain without scheme and port
    'yourdomain1.com',
    'yourdomain2.com'
];