<?php

// Both the code and state are set
if (isset($_REQUEST['code']) && isset($_REQUEST['state'])) {

    // Parse the passed state URL
    $aUrl = parse_url($_REQUEST['state']);
    if ($aUrl !== false) {
        
        // Set the new location using the state
        $sLocation = sprintf("%s://%s%s%s?code=%s",
            $aUrl['scheme'],
            $aUrl['host'],
            $aUrl['port'] ?? '',
            $aUrl['path'],
            urlencode($_REQUEST['code'])
        );

        // Add any existing parameters
        if (isset($aUrl['query'])) {
            $sLocation .= '&' . $aUrl['query'];
        }

        // Redirect and exit
        header("Location: " . $sLocation);
        exit;
    }
}

// Forwarding failed - log error and provide an error message
error_log('oauth redirect failed, request: ' . json_encode($_REQUEST));

?>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
    <title>Authorization Failed</title>
</head>

<body>

<h1>Authorization Failed</h1>

</body>
</html>

