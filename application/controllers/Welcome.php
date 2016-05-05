<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * http://example.com/index.php/welcome
     * - or -
     * http://example.com/index.php/welcome/index
     * - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * 
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        var_dump('111111111');
        phpinfo();
    }

    public function demo()
    {
        $command = $_POST['command'];
        $text = $_POST['text'];
        $token = $_POST['token'];
        // Check the token and make sure the request is from our team
        if ($token != 'Moj5ICFUmSAXZOeE4eeHbw40') { // replace this with the token from your slash command configuration page
            $msg = "The token for the slash command doesn't match. Check your script.";
            die($msg);
            echo $msg;
        }
        // isitup.org doesn't require you to use API keys, but they do require that any automated script send in a user agent string.
        // You can keep this one, or update it to something that makes more sense for you
        $user_agent = "IsitupForSlack/1.0 (https://github.com/mccreath/istiupforslack; mccreath@gmail.com)";
        // We're just taking the text exactly as it's typed by the user. If it's not a valid domain, isitup.org will respond with a `3`.
        // We want to get the JSON version back (you can also get plain text).
        $url_to_check = "https://isitup.org/" . $text . ".json";
        // Set up cURL
        $ch = curl_init($url_to_check);
        // Set up options for cURL
        // We want to get the value back from our query
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Send in our user agent string
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // Make the call and get the response
        $ch_response = curl_exec($ch);
        // Close the connection
        curl_close($ch);
        // Decode the JSON array sent back by isitup.org
        $response_array = json_decode($ch_response, true);
        // Build our response
        // Note that we're using the text equivalent for an emoji at the start of each of the responses.
        // You can use any emoji that is available to your Slack team, including the custom ones.
        if ($ch_response === FALSE) {
            // isitup.org could not be reached
            $reply = "Ironically, isitup could not be reached.";
        } else {
            if ($response_array["status_code"] == 1) {
                // Yay, the domain is up!
                $reply = ":thumbsup: I am happy to report that *<http://" . $response_array["domain"] . "|" . $response_array["domain"] . ">* is *up*!";
            } else 
                if ($response_array["status_code"] == 2) {
                    // Boo, the domain is down.
                    $reply = ":disappointed: I am sorry to report that *<http://" . $response_array["domain"] . "|" . $response_array["domain"] . ">* is *not up*!";
                } else 
                    if ($response_array["status_code"] == 3) {
                        // Uh oh, isitup.org doesn't think the domain entered by the user is valid
                        $reply = ":interrobang: *" . $text . "* does not appear to be a valid domain. \n";
                        $reply .= "Please enter both the domain name AND suffix (example: *amazon.com* or *whitehouse.gov*).";
                    }
        }
        // Send the reply back to the user.
        echo $reply;
    }
}
