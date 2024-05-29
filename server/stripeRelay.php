<?php
    // the sole purpose of this php file is to act as a relay for stripe calls from the cordial minuet server to
    // the upgraded stripe API that requires new TLS
    // the calling server must have a shared secret and know what this php file is expecting
    // expects to be passed a single argument 'call'
    // the contents of that arugment are encrypted and base64 encoded, so they can be passed securely through POST
    // this client will decrypt them based on the shared secret and they explode them to extract the stripe call
    // the username and argument set.
    // once username and call are removed from exploded array arguments are imploded again to be passed to stripe
    // example of expected argument (after decryption):
    // tokens&sk_test_BQokikJOvBiI2HlWgH4olfQ2:&card[number]=4242424242424242&card[exp_month]=12&card[exp_year]=2017&card[cvc]=123


$sharedSecretKey =
    "96E9DD3EB4161A8525AA83EA9A38EFA8FCC1CF56871E1747CC9D12737A9775CD";


    // get the HTTP method, path and body of the request
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        // this is the shared key between CM and relay
        $key = pack('H*', $sharedSecretKey);
        $key_size =  strlen($key);
        // pull text from request
        $ciphertext_base64 = $_POST['call'];
        if($ciphertext_base64 != null){
            // continue if we found text to decrypt
            // first decode the text back to binary
            $ciphertext_dec = base64_decode($ciphertext_base64);

            // this information is shared between CM and relay call        
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
            $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    
            // retrieves the cipher text (everything except the $iv_size in the front)
            $ciphertext_dec = substr($ciphertext_dec, $iv_size);

            // may remove 00h valued characters from end of plain text
            $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
            // strip null characters - encryption pads with 0's, so they need to be stripped
            $plaintext_dec = str_replace("\0", "", $plaintext_dec);
            //echo $plaintext_dec;
            $arguments_array = explode("&", $plaintext_dec);
            if($arguments_array != null && count($arguments_array) > 2){
                $stripe_command = array_shift($arguments_array);
                //echo $stripe_command."<br>";
                $stripe_user = array_shift($arguments_array);
                //echo $stripe_user."<br>";
                $arguments = implode("&", $arguments_array);
                //echo $arguments."<br>";
                
                // hardcoded to stripe so can't be used as an open relay
                $curlcommand = "https://api.stripe.com/v1/".$stripe_command;
                $ch = curl_init($curlcommand);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
                curl_setopt($ch, CURLOPT_USERPWD, $stripe_user);
                curl_setopt($ch, CURLOPT_POST, true);
                $output = curl_exec($ch);
                echo $output;
            }
        }
    } else {
        echo "error: you don't belong here.";
    }
?>