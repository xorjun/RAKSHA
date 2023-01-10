<?php
//Bad Words
$queryfc = $mysqli->query("SELECT * FROM `psec_bad-words`");
$countfc = mysqli_num_rows($queryfc);

if ($countfc > 0) {
    
    //Content Filtering
    function bad_words($buffer, $mysqli)
    {
        global $settings;
		
		$query1 = $mysqli->query("SELECT * FROM `psec_bad-words`");

        while ($row1 = $query1->fetch_array()) {
            $buffer = str_replace($row1['word'], $settings['badword_replace'], $buffer);
        }
        
        return $buffer;
    }
    
    ob_start(function($buffer) use ($mysqli) {
        return bad_words($buffer, $mysqli);
    });
    
    //POST Filtering
    function badwords_checker($input, $mysqli)
    {
        global $settings;
		
		$query2 = $mysqli->query("SELECT * FROM `psec_bad-words`");
        
        while ($row2 = $query2->fetch_array()) {
            $badwords2[] = $row2['word'];
        }
        
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = badwords_checker($val, $mysqli);
            }
        } else {
            $query2 = $mysqli->query("SELECT * FROM `psec_bad-words`");
            while ($row3 = $query2->fetch_array()) {
                $input = str_replace($row3['word'], $settings['badword_replace'], $input);
                
            }
            $output = $input;
        }
        return @$output;
    }
    
    $_POST = badwords_checker($_POST, $mysqli);
    //$_GET  = badwords_checker($_GET);
}
?>

This code appears to be used for bad word filtering on a website. 
It checks for the presence of any "bad words" in user input that's sent to the server through the $_POST and $_GET superglobals and replace them with a string defined in a global variable $settings['badword_replace'].

This script starts by making a database query to fetch all the bad words from a database table "psec_bad-words". 
Then it defines two functions:

bad_words - this is used to filter the content of the website. 
The function takes two parameters: the buffer, which is the content of the webpage, and $mysqli, which is the MySQLi object used to interact with the database. 
It searches through each word in the bad word list and replaces it with the string defined in the $settings['badword_replace'].
badwords_checker - This function is used to check user input for bad words. 
This function recursively filters the values of the $_POST array, looking for bad words and replacing them with the string defined in $settings['badword_replace'].
Finally, the script uses the ob_start() function to start output buffering. 
The ob_start() function takes a callback function, which uses the bad_words function to filter the content of the website before it is sent to the client.
And at last the $_POST superglobal is filtered using the badwords_checker function.