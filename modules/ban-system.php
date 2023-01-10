<?php
$cache_file = __DIR__ . "/cache/ip-details/". str_replace(":", "-", $ip) .".json";

//Ban System
$querybanned = $mysqli->query("SELECT ip FROM `psec_bans` WHERE ip='$ip' LIMIT 1");
if ($querybanned->num_rows > 0) {
    $bannedpage_url = $settings['raksha_path'] . "/pages/banned.php";
    echo '<meta http-equiv="refresh" content="0;url=' . $bannedpage_url . '" />';
    exit;
}

//IP Ranges
$querybanned = $mysqli->query("SELECT ip_range FROM `psec_bans-ranges` WHERE ip_range='$ip_range' LIMIT 1");
if ($querybanned->num_rows > 0) {
    $bannedpage_url = $settings['raksha_path'] . "/pages/banned.php";
    echo '<meta http-equiv="refresh" content="0;url=' . $bannedpage_url . '" />';
    exit;
}

//Blocking Country
$query1 = $mysqli->query("SELECT * FROM `psec_bans-country`");

$query2 = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type = 'isp'");
if ($query1->num_rows > 0 OR $query2->num_rows > 0) {
	if (psec_getcache($cache_file) == 'PSEC_NoCache') {
		$url = 'https://ipapi.co/' . $ip . '/json/';
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		@curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
		@$ipcontent = curl_exec($ch);
		curl_close($ch);
    
		$ip_data = @json_decode($ipcontent);
		
		// Grabs API Response and Caches
		file_put_contents($cache_file, $ipcontent);
		
	} else {
		$ip_data = @json_decode(psec_getcache($cache_file));
	}
		
    if ($ip_data && !isset($ip_data->{'error'})) {
        $country_check = $ip_data->{'country_name'};
        $isp_check     = $ip_data->{'org'};
		
		if($country_check == '') {
			$country_check = "Unknown";
		}
    } else {
        $country_check = "Unknown";
        $isp_check     = "Unknown";
    }
    
} else {
    @$isp_check = "Unknown";
    @$country_check = "Unknown";
}

@$querybanned = $mysqli->query("SELECT id, country FROM `psec_bans-country` WHERE country='$country_check'");
@$rowcb = mysqli_fetch_array($querybanned);

if ($settings['countryban_blacklist'] == 1) {
    if ($querybanned->num_rows > 0) {
        $bannedcpage_url = $settings['raksha_path'] . "/pages/banned-country.php?c_id=" . $rowcb['id'];
		echo '<meta http-equiv="refresh" content="0;url=' . $bannedcpage_url . '" />';
        exit;
    }
} else {
    if (strpos(strtolower($useragent), "googlebot") !== false OR strpos(strtolower($useragent), "bingbot") !== false OR strpos(strtolower($useragent), "yahoo! slurp") !== false OR strpos(strtolower($useragent), "yandex") !== false) {
    } else {
        if ($querybanned->num_rows <= 0) {
            $bannedcpage_url = $settings['raksha_path'] . "/pages/banned-country.php";
            echo '<meta http-equiv="refresh" content="0;url=' . $bannedcpage_url . '" />';
            exit;
        }
    }
}

//Blocking Browser
$querybanned = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='browser'");
while ($rowb = $querybanned->fetch_assoc()) {
    if (strpos(strtolower($browser), strtolower($rowb['value'])) !== false) {
        $blockedbpage_url = $settings['raksha_path'] . "/pages/blocked-browser.php";
        echo '<meta http-equiv="refresh" content="0;url=' . $blockedbpage_url . '" />';
        exit;
    }
}

//Blocking Operating System
$querybanned = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='os'");
while ($rowo = $querybanned->fetch_assoc()) {
    if (strpos(strtolower($os), strtolower($rowo['value'])) !== false) {
        $blockedopage_url = $settings['raksha_path'] . "/pages/blocked-os.php";
        echo '<meta http-equiv="refresh" content="0;url=' . $blockedopage_url . '" />';
        exit;
    }
}

//Blocking Internet Service Provider
$querybanned = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='isp'");
while ($rowi = $querybanned->fetch_assoc()) {
    if (strpos(strtolower($isp_check), strtolower($rowi['value'])) !== false) {
        $blockedipage_url = $settings['raksha_path'] . "/pages/blocked-isp.php";
        echo '<meta http-equiv="refresh" content="0;url=' . $blockedipage_url . '" />';
        exit;
    }
}

//Blocking Referrer
$querybanned = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='referrer'");
while ($rowr = $querybanned->fetch_assoc()) {
    if (strpos(strtolower(@$referer), strtolower($rowr['value'])) !== false) {
        $blockedrpage_url = $settings['raksha_path'] . "/pages/blocked-referrer.php";
        echo '<meta http-equiv="refresh" content="0;url=' . $blockedrpage_url . '" />';
        exit;
    }
}
?>


This PHP code checks if a client's IP address has been banned by the website, 
or if the client is accessing the website from a banned country or ISP. 
It does this by comparing the client's IP address and country/ISP information with values stored in a MySQL database.

First, it checks if the client's IP address is present in the "psec_bans" table. 
If it is, the script redirects the client to a "banned" page.

Then it checks if the client's IP address is within a banned range in the "psec_bans-ranges" table. 
If it is, the script again redirects the client to a "banned" page.

Then, it checks if client's country and ISP are banned by checking if there are any rows present in the "psec_bans-country" and "psec_bans-other" tables respectively, using query1 and query2. 
If there are, it uses the service https://ipapi.co/ to fetch the country and ISP of the client from the client's IP address. 
It then compares the client's country and ISP with the banned country and ISP and if there is a match, the script redirects the client to a "banned-country" page.

It also uses caching to save the result of the API call, so that if the same IP address makes a request in a short period of time, 
it will use the cached result instead of making a new API call.