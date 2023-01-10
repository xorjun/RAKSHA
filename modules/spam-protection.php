<?php
//Spam Protection
if ($settings['spam_protection'] == 1) {
    
    $dnsbl_lookup = array();
    
    $query = $mysqli->query("SELECT * FROM `psec_dnsbl-databases`");
    while ($row = $query->fetch_assoc()) {
        
        $dnsbl_lookup[] = $row['database'];
        $reverse_ip     = implode(".", array_reverse(explode(".", $ip)));
        
        foreach ($dnsbl_lookup as $host) {
            if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
                
                $type = "Spammer";
                
                //Logging
                if ($settings['spam_logging'] == 1) {
                    psec_logging($mysqli, $type);
                }
                
                //E-Mail Notification
                if ($settings['mail_notifications'] == 1 && $settings['spam_mail'] == 1) {
                    psec_mail($mysqli, $type);
                }
                
                echo '<meta http-equiv="refresh" content="0;url=' . $settings['spam_redirect'] . '" />';
                exit;
            }
        }
    }
}
?>


This code is checking if a visitor's IP address is listed in any of the DNSBL (DNS-based Blackhole List) 
databases that have been specified in the psec_dnsbl-databases table. If the IP address is found on any of 
these lists, the visitor is considered a spammer and is redirected to a specified URL. 
The code also logs the visitor's information and sends an email notification if specified in the settings.

To check if an IP address is listed in a DNSBL, 
the IP address is reversed and a DNS query is made to the DNSBL server, 
with the reversed IP address as the hostname. If the DNSBL server returns an IP address, 
it means that the IP is listed as a spamming IP in the DNSBL.

It's important to note that false positives are possible with any IP-based filtering system, 
and that some legitimate email can be blocked by DNSBLs, so you should carefully consider the trade-offs 
between security and usability before implementing this kind of protection