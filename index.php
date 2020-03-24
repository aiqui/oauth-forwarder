<?php

require_once('./config.php');

/**
 * @return string
 */
function getUserIp () {
    return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
}


/**
 * @return PDO
 */
function getPdo () {
    $sHost = DB_HOST ? 'host=' . DB_HOST : '';
    $sDsn = sprintf('mysql:%s;dbname=%s;charset=utf8', $sHost, DB_NAME);
    try {
        $oPdo = new PDO($sDsn, DB_USER, DB_PW);
        $oPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    return $oPdo;
}

/**
 * @param string $sCode
 * @param string $sState
 * @param string $sRedirect
 * @param string $sReferrer
 * @param bool $bSuccess
 * @return void
 */
function saveLog (string $sCode, string $sState, string $sRedirect, string $sReferrer, bool $bSuccess = true) {
    $oStmt = getPdo()->prepare(
        "INSERT INTO  oauth_forward_log (ip_address, code, state, redirect, referrer, success)
              VALUES  (:ip_address, :code, :state, :redirect, :referrer, :success)");
    $oStmt->execute(
        [':ip_address' => getUserIp(),
         ':code'       => $sCode,
         ':state'      => $sState,
         ':redirect'   => $sRedirect,
         ':referrer'   => $sReferrer,
         ':success'    => $bSuccess ? 1 : 0]);
}

// Referrer should be defined
$sReferrer = $_SERVER["HTTP_REFERER"] ?? 'none';

// Both the code and state are set
if (isset($_REQUEST['code']) && isset($_REQUEST['state'])) {

    // Parse the passed state URL
    $aUrl = parse_url($_REQUEST['state']);
    if ($aUrl !== false && isset($aUrl['scheme']) && isset($aUrl['host']) && isset($aUrl['path'])) {

        $sPort  = isset($aUrl['port']) ? ':' . $aUrl['port'] : '';
        $sQuery = isset($aUrl['query']) ? '&' . $aUrl['query'] : '';

        // Set the new location using the state
        $sLocation = sprintf("%s://%s%s%s?code=%s%s",
            $aUrl['scheme'],
            $aUrl['host'],
            $sPort,
            $aUrl['path'],
            urlencode($_REQUEST['code']),
            $sQuery
        );

        // Save to the log
        saveLog($_REQUEST['code'], $_REQUEST['state'], $sLocation, $sReferrer);

        // Redirect and exit
        header("Location: " . $sLocation);
        exit;
    }
}

// Forwarding failed - log error and provide an error message
$sCode  = $_REQUEST['code'] ?? 'none';
$sState = $_REQUEST['state'] ?? 'none';
saveLog($sCode, $sState, 'none', $sReferrer, false);
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

