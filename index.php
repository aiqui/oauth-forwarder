<?php

// Both the code and state are set
if (isset($_REQUEST['code']) && isset($_REQUEST['state'])) {

    // Parse the passed state URL
    $aUrl = parse_url($_REQUEST['state']);
    if ($aUrl !== false) {
        
        // Set the new location using the state
        $sLocation = sprintf("%s://%s%s?code=%s", $aUrl['scheme'], $aUrl['host'],
                             $aUrl['path'], urlencode($_REQUEST['code']));
        if (isset($aUrl['query'])) {
            $sLocation .= '&' . $aUrl['query'];
        }

        // Redirect and exit
        header("Location: " . $sLocation);
        exit;
    }
}

// Unless forwarding was successful, the error message will appear
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
         <title>Authorization Failed</title>
         </head>

         <body>

         <h1>Authorization Failed</h1>

    </body>
    </html>

