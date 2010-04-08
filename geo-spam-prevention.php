<?php
/*
Plugin Name: Graceful Email Obfuscation
Description: An email obfuscator to prevent spam, using Roel Van Gils' method, GEO. In brief: there is always a clickable, copyable link at the end for the user; transparently if JavaScript is enabled, and links are (so far) impossible to harvest.
Version: 0.1.1
Author: Nicholas Wilson

Copyright: Nicholas Wilson, 2010
Licence: GPL v.2

*/

add_shortcode('email','geo_emailshortcode');
add_filter('the_content','geo_hijackemailload');
wp_register_script('geo-spam-prevention',plugin_dir_url(__FILE__).'geo-spam-prevention.js',
                array('jquery'), 0.1);
wp_enqueue_script('geo-spam-prevention');

function geo_hijackemailload($content) {
    if (isset($_GET['geo-address'])) {
        $fail = false;
        if (isset($_GET['geo-result'])) {
            //check against target:
            $target = isset($_GET['geo-target']) ?
                (int)$_GET['geo-target'] - 5 : 0;
            if ((int)$_GET['geo-result'] != $target)
                $fail = true;
            else {
                $email = rawurldecode(str_rot13($_GET['geo-address']));
                $email = str_replace(array('A','N'),array('.','@'),$email);
                return '<p>Sorry for making you jump through those hoops! The email you were looking for is <a href="mailto:'.$email.'">'.$email.'</a>.</p>';
            }
        }

        //Now here we generate the form to ask the question
        $return = '';
        if ($fail) $return .= '<p style="color:red;">Sorry! Your answer seemed to be wrong. If there is a mistake in the site, you are probably getting frustrated now. If you have already tried again, do contact me at <a href="mailto:webmaster@hopegreatham.org">webmaster@hopegreatham.org</a> so I can fix the problem.</p>';
        $one = rand(1,10);
        $two = rand(1,10);
        $return .= "<form action=\"\" method=\"get\">
        <fieldset>
            <legend>Please enter the sum of $one and $two.</legend>
            <input type=\"text\" size=\"2\" maxlength=\"2\" name=\"geo-result\" id=\"geo-result\" />
            <input type=\"hidden\" name=\"geo-target\" value=\"".($one + $two + 5)."\" />
            <input type=\"hidden\" name=\"geo-address\" value=\"{$_GET['geo-address']}\" />
            <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Enter\" />
        </fieldset>
        </form>
        <h4>Why am I being asked to fill this in?</h4>
        <p>If email addresses are left ‘out in the open’, spammers find them quickly. You have probably seen various spam avoiding facilities on other sites. Because you have JavaScript disabled in your browser, we are running this extra simple check. Thank you for your patience!</p>";
        return $return;
    }
    return $content;
}

function geo_emailshortcode($atts, $content) {
    //We want an href attribute, and non-empty content
    if (!isset($atts['href'])) {
        //Test roughly for email in text
        if (strpos($content,'@') > 1) {
            $atts['href'] = $content;
            $content = 'email';
        } else {
            return "[email]{$content}[/email]";
        }
    }
    //Now code up the email:
    $email = str_replace(array('.','@'),array('A','N'),strtolower($atts['href']));
    $email = rawurlencode(str_rot13($email));
    return '<a href="'.get_bloginfo('url')."/?geo-address=$email\" class=\"geo-address".
        (isset($atts['class'])?' '.$atts['class']:'').
        '"'.(isset($atts['style'])? ' style="'.$atts['style'].'"':'').'>'.$content.'</a>';
}

?>
