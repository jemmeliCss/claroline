<?php //     $Id$
/***************************************************************************
                           functions.php  -  description
                             -------------------
    begin                : Sat June 17 2000
    copyright            : (C) 2001 The phpBB Group
    email                : support@phpbb.com
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/**
 * Start session-management functions - Nathan Codding, July 21, 2000.
 */

/**
 * new_session()
 * Adds a new session to the database for the given userid.
 * Returns the new session ID.
 * Also deletes all expired sessions from the database, based on the given session lifespan.
 */
function new_session($userid, $remote_ip, $lifespan, $db)
{
	global $tbl_sessions;

	mt_srand((double)microtime()*1000000);
	$sessid = mt_rand();

	$currtime = (string) (time());
	$expirytime = (string) (time() - $lifespan);

	$deleteSQL = "DELETE FROM `$tbl_sessions` WHERE (start_time < $expirytime)";
	$delresult = mysql_query($deleteSQL, $db);

	if (!$delresult) die("Delete failed in new_session()");

	$result = mysql_query("INSERT INTO `$tbl_sessions`
	                      (sess_id, user_id, start_time, remote_ip)
	                       VALUES
	                      ($sessid, $userid, $currtime, '$remote_ip')", $db)
	          or die(mysql_errno().": ".mysql_error()."<br>Insert failed in new_session()");

	return $sessid;
}												// end new_session()

/**
 * Sets the sessID cookie for the given session ID. the $cookietime parameter
 * is no longer used, but just hasn't been removed yet. It'll break all the modules
 * (just login) that call this code when it gets removed.
 * Sets a cookie with no specified expiry time. This makes the cookie last until the
 * user's browser is closed. (at last that's the case in IE5 and NS4.7.. Haven't tried
 * it with anything else.)
 */
function set_session_cookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure)
{
	// This sets a cookie that will persist until the user closes their browser window.
	// since session expiry is handled on the server-side, cookie expiry time isn't a big deal.
	setcookie($cookiename,$sessid,'',$cookiepath,$cookiedomain,$cookiesecure);

}				// set_session_cookie()


/**
 * Returns the userID associated with the given session, based on
 * the given session lifespan $cookietime and the given remote IP
 * address. If no match found, returns 0.
 */
function get_userid_from_session($sessid, $cookietime, $remote_ip, $db)
{
	global $tbl_sessions;

	$mintime = time() - $cookietime;

	$result = mysql_query("SELECT user_id FROM `$tbl_sessions`
	                       WHERE (sess_id = $sessid)
	                       AND (start_time > $mintime)
	                       AND (remote_ip = '$remote_ip')", $db)
	          or die("Error doing DB query in get_userid_from_session()");

	$row = mysql_fetch_array($result);

	if (!$row)	return 0;
	else		return $row[user_id];

}				// get_userid_from_session()

/**
 * Refresh the start_time of the given session in the database.
 * This is called whenever a page is hit by a user with a valid session.
 */
function update_session_time($sessid, $db)
{
	global $tbl_sessions;

	$newtime = (string) time();

	$result = mysql_query("UPDATE `$tbl_sessions`
	                       SET start_time=$newtime
	                       WHERE (sess_id = $sessid)", $db)
	          or die(mysql_error()."<br>Error doing DB update in update_session_time()");
	return 1;
}												// update_session_time()

/**
 * Delete the given session from the database. Used by the logout page.
 */
function end_user_session($userid, $db)
{
	global $tbl_sessions;

	$result = mysql_query("DELETE FROM `$tbl_sessions`
	                       WHERE (user_id = $userid)", $db)
	          or die (mysql_error() . "<br>Delete failed in end_user_session()");
	return 1;
}				// end_session()

/**
 * Prints either "logged in as [username]. Log out." or
 * "Not logged in. Log in.", depending on the value of
 * $user_logged_in.
 */
function print_login_status($user_logged_in, $username, $url_phpbb)
{
	global $phpEx;
	global $l_loggedinas, $l_notloggedin, $l_logout, $l_login;

	if($user_logged_in)
	{
		echo	"<b>",$l_loggedinas," ",$username,". ",
				"<a href=\"",$url_phpbb,"/logout.",$phpEx,"\">",$l_logout,".</a>",
				"</b><br>\n";
	}
	else
	{
		echo	"<b>",$l_notloggedin,". ",
				"<a href=\"",$url_phpbb,"/login.",$phpEx,"\">",$l_login,".</a>",
				"</b><br>\n";
	}
}				// print_login_status()

/**
 * Prints a link to either login.php or logout.php, depending
 * on whether the user's logged in or not.
 */

function make_login_logout_link($user_logged_in, $url_phpbb)
{
	global $phpEx;

	global $l_logout, $l_login;

	if ($user_logged_in)
	{
		$link = "<a href=\"$url_phpbb/logout.$phpEx\">$l_logout</a>";
	}
	else
	{
		$link = "<a href=\"$url_phpbb/login.$phpEx\">$l_login</a>";
	}

	return $link;
}				// make_login_logout_link()


/*---------------------- End session-management functions -------------------*/

/**
 * Gets the total number of topics in a form
 */
function get_total_topics($forum_id, $db)
{
	global $l_error, $tbl_topics;

	$sql = "SELECT count(*) AS total
	        FROM `$tbl_topics`
	        WHERE forum_id = '$forum_id'";

	if(!$result = mysql_query($sql, $db))		return($l_error);
	if(!$myrow = mysql_fetch_array($result))	return($l_error);

	return($myrow[total]);
}
/**
 * Shows the 'header' data from the header/meta/footer table
 */
function showheader($db)
{
	global $tbl_headermetafooter;

	$sql = "SELECT header FROM `$tbl_headermetafooter`";

	if($result = mysql_query($sql, $db))
	{
		if($header = mysql_fetch_array($result))
		{
			echo stripslashes($header[header]);
		}
	}
}
/**
 * Shows the meta information from the header/meta/footer table
 */
function showmeta($db)
{
	global $tbl_headermetafooter;

	$sql = "SELECT meta FROM `$tbl_headermetafooter`";

	if($result = mysql_query($sql, $db))
	{
		if($meta = mysql_fetch_array($result))
		{
				echo stripslashes($meta[meta]);
		}
	}
}

/**
 * Show the footer from the header/meta/footer table
 */
function showfooter($db)
{
	global $tbl_headermetafooter;

	$sql = "SELECT footer FROM `$tbl_headermetafooter`";
	if($result = mysql_query($sql, $db))
	{
		if($footer = mysql_fetch_array($result))
		{
			echo stripslashes($footer[footer]);
		}
	}
}

/**
 * Used to keep track of all the people viewing the forum at this time
 * Anyone who's been on the board within the last 300 seconds will be
 * returned. Any data older then 300 seconds will be removed
 */
function get_whosonline($IP, $username, $forum, $db)
{
	global $sys_lang, $tbl_whosonline;

	if($username == '') $username = get_syslang_string($sys_lang, "l_guest");

	$time       = explode(  " ", microtime());
	$userusec   = (double)$time[0];
	$usersec    = (double)$time[1];
	$username   = addslashes($username);

	$deleteuser = mysql_query( "DELETE FROM `$tbl_whosonline` WHERE date < $usersec - 300", $db);
	$userlog    = mysql_fetch_row(MYSQL_QUERY( "SELECT * FROM `$tbl_whosonline` where IP = '$IP'", $db));
	if($userlog == false)
	{
		$ok= @mysql_query( "INSERT INTO `$tbl_whosonline`
		                    (ID,IP,DATE,username,forum)
		                    VALUES
		                    ('$User_Id','$IP','$usersec', '$username', '$forum')", $db)
		     or die( "Unable to query db!");
	}

	$resultlogtab   = mysql_query("SELECT Count(*) as total FROM `$tbl_whosonline`", $db);
	$numberlogtab   = mysql_fetch_array($resultlogtab);
	return($numberlogtab[total]);
}

/**
 * Returns the total number of posts in the whole system, a forum, or a topic
 * Also can return the number of users on the system.
 */
function get_total_posts($id, $db, $type)
{
	global $tbl_users, $tbl_posts;

	switch($type)
	{
		case 'users':
			$sql = "SELECT count(*) AS total FROM `$tbl_users` WHERE (user_id != -1) AND (user_level != -1)";
		break;
		case 'all':
			$sql = "SELECT count(*) AS total FROM `$tbl_posts`";
		break;
		case 'forum':
			$sql = "SELECT count(*) AS total FROM `$tbl_posts` WHERE forum_id = '$id'";
		break;
		case 'topic':
			$sql = "SELECT count(*) AS total FROM `$tbl_posts` WHERE topic_id = '$id'";
		break;
		// Old, we should never get this.
		case 'user':
			die("Should be using the users.user_posts column for this.");
	}

	if(!$result = mysql_query($sql, $db))       return("ERROR");
	if(!$myrow = mysql_fetch_array($result))    return("0");

	return($myrow[total]);
}

/**
 * Returns the most recent post in a forum, or a topic
 */
function get_last_post($id, $db, $type)
{
	global $l_error, $l_noposts, $l_by, $tbl_posts, $tbl_users ;

	switch($type)
	{
		case 'time_fix':
			$sql = "SELECT p.post_time FROM `$tbl_posts` p
			        WHERE p.topic_id = '$id'
			        ORDER BY post_time DESC LIMIT 1";
		break;

		case 'forum':
			$sql = "SELECT p.post_time, p.poster_id, u.username
			        FROM `$tbl_posts` p, `$tbl_users` u
		            WHERE p.forum_id = '$id'
		            AND p.poster_id = u.user_id
		            ORDER BY post_time DESC LIMIT 1";
		break;

		case 'topic':
			$sql = "SELECT p.post_time, u.username
			        FROM `$tbl_posts` p, `$tbl_users` u
			        WHERE p.topic_id = '$id'
			        AND p.poster_id = u.user_id
			        ORDER BY post_time DESC LIMIT 1";
		break;

		case 'user':
			$sql = "SELECT p.post_time
			        FROM `$tbl_posts` p
			        WHERE p.poster_id = '$id'
			        LIMIT 1";
		break;
	}

	if(!$result = mysql_query($sql, $db))       return($l_error);
	if(!$myrow = mysql_fetch_array($result))    return($l_noposts);

	if(($type != 'user') && ($type != 'time_fix'))
	{
		$val = sprintf("%s <br> %s %s", $myrow[post_time], $l_by, $myrow[username]);
	}
	else
	{
		$val = $myrow[post_time];
	}

	return($val);
}

/**
 * Returns an array of all the moderators of a forum
 */
function get_moderators($forum_id, $db)
{
	global $tbl_users, $tbl_forum_mods;

	$sql = "SELECT u.user_id, u.username
	        FROM `$tbl_users` u, `$tbl_forum_mods` f
	        WHERE f.forum_id = '$forum_id'
	        AND f.user_id = u.user_id";

	if(!$result = mysql_query($sql, $db))   return(array());
	if(!$myrow = mysql_fetch_array($result)) return(array());

	do
	{
		$array[] = array("$myrow[user_id]" => "$myrow[username]");

	}	while($myrow = mysql_fetch_array($result));

	return($array);
}

/**
 * Checks if a user (user_id) is a moderator of a perticular forum (forum_id)
 * Retruns 1 if TRUE, 0 if FALSE or Error
 */
function is_moderator($forum_id, $user_id, $db)
{
	global $tbl_forum_mods;

	$sql = "SELECT user_id
	        FROM `$tbl_forum_mods`
	        WHERE forum_id = '$forum_id'
	        AND user_id = '$user_id'";

	if(!$result = mysql_query($sql, $db))    return("0");
	if(!$myrow = mysql_fetch_array($result)) return("0");
	if($myrow[user_id] != '')                return("1");
	else                                     return("0");
}

/**
 * Checks the given password against the DB for the given username.
 * Returns true if good, false if not.
 * @author  Nathan Codding - July 19, 2000
 */
function check_user_pw($username, $password, $db)
{
	global $tbl_users;

	$password = md5($password);
	$username = addslashes($username);

	$sql = "SELECT user_id
	        FROM `$tbl_users`
	        WHERE username      = '$username'
	        AND   user_password = '$password'";

	$resultID = mysql_query($sql, $db)
	            or die(mysql_error() . "<br>Error doing DB query in check_user_pw()");

	return mysql_num_rows($resultID);
}				// check_user_pw()


/**
 * Returns a count of the given userid's private messages.
 * @author Nathan Codding - July 19, 2000
 */
function get_pmsg_count($user_id, $db)
{
	global $tbl_priv_msgs;

	$sql = "SELECT msg_id FROM `$tbl_priv_msgs`
	         WHERE (to_userid = $user_id)";

	$resultID = mysql_query($sql)
	            or die(mysql_error() . "<br>Error doing DB query in get_pmsg_count");

	return mysql_num_rows($resultID);
}				// get_pmsg_count()


/**
 * Checks if a given username exists in the DB. Returns true if so, false if not.
 * @author Nathan Codding - July 19, 2000
 */
function check_username($username, $db)
{
	$username = addslashes($username);

	$sql = "SELECT user_id FROM `$tbl_users`
	        WHERE (username = '$username')
	        AND (user_level != '-1')";

	$resultID = mysql_query($sql)
	            or die(mysql_error() . "<br>Error doing DB query in check_username()");

	return mysql_num_rows($resultID);
}				// check_username()


/**
 * Nathan Codding, July 19/2000
 * Get a user's data, given their user ID.
 */

function get_userdata_from_id($userid, $db)
{
	global $tbl_users;

	$sql = "SELECT * FROM `$tbl_users`
	        WHERE user_id = $userid";

	if(!$result = mysql_query($sql, $db))
	{
		$userdata = array("error" => "1");
		return ($userdata);
	}

	if(!$myrow = mysql_fetch_array($result))
	{
		$userdata = array("error" => "1");
		return ($userdata);
	}

	return($myrow);
}

/**
 * Gets user's data based on their username
 */
function get_userdata($username, $db)
{
	global $tbl_users;

	$username = addslashes($username);

	$sql = "SELECT * FROM `$tbl_users`
	        WHERE username = '$username'
	        AND user_level != -1";

	if(!$result = mysql_query($sql, $db))    $userdata = array("error" => "1");
	if(!$myrow = mysql_fetch_array($result)) $userdata = array("error" => "1");

	return($myrow);
}

/**
 * Returns all the rows in the themes table
 */
function setuptheme($theme, $db)
{
	global $tbl_themes;

	$sql = "SELECT * FROM `$tbl_themes`
	        WHERE theme_id = '$theme'";

	if(!$result = mysql_query($sql, $db))    return(0);
	if(!$myrow = mysql_fetch_array($result)) return(0);

	return($myrow);
}

/**
 * Checks if a forum or a topic exists in the database. Used to prevent
 * users from simply editing the URL to post to a non-existant forum or topic
 */
function does_exists($id, $db, $type)
{
	global $tbl_forums, $tbl_topics;

	switch($type)
	{
		case 'forum':
			$sql = "SELECT forum_id FROM `$tbl_forums` WHERE forum_id = '$id'";
		break;
		case 'topic':
			$sql = "SELECT topic_id FROM `$tbl_topics` WHERE topic_id = '$id'";
		break;
	}

	if(!$result = mysql_query($sql, $db))    return(0);
	if(!$myrow = mysql_fetch_array($result)) return(0);

	return(1);
}

/**
 * Checks if a topic is locked
 */
function is_locked($topic, $db)
{
	global $tbl_topics;

	$sql = "SELECT topic_status FROM `$tbl_topics` WHERE topic_id = '$topic'";

	if(!$r = mysql_query($sql, $db)) return(FALSE);
	if(!$m = mysql_fetch_array($r))  return(FALSE);
	if($m[topic_status] == 1)        return(TRUE);
	else                             return(FALSE);
}

/**
 * Changes :) to an <IMG> tag based on the smiles table in the database.
 *
 * Smilies must be either:
 * 	- at the start of the message.
 * 	- at the start of a line.
 * 	- preceded by a space or a period.
 * This keeps them from breaking HTML code and BBCode.
 * TODO: Get rid of global variables.
 */
function smile($message)
{
	global $db, $url_smiles, $tbl_smiles;

	// Pad it with a space so the regexp can match.
	$message = ' ' . $message;

	if ($getsmiles = mysql_query("SELECT *, length(code) as length
	                              FROM `$tbl_smiles`
	                              ORDER BY length DESC"))
	{
		while ($smiles = mysql_fetch_array($getsmiles))
		{
			$smile_code = preg_quote($smiles[code]);
			$smile_code = str_replace('/', '//', $smile_code);
			$message = preg_replace("/([\n\\ \\.])$smile_code/si",
			                         '\1<IMG SRC="' . $url_smiles . '/' . $smiles[smile_url] . '">',
			                          $message);
		}
	}

	// Remove padding, return the new string.
	$message = substr($message, 1);
	return($message);
}

/**
 * Changes a Smiliy <IMG> tag into its corresponding smile
 * TODO: Get rid of golbal variables, and implement a method of
 * distinguishing between :D and :grin: using the <IMG> tag
 */
function desmile($message)
{
	// Ick Ick Global variables...remind me to fix these! - theFinn
	global $db, $url_smiles, $tbl_smiles;

	if ($getsmiles = mysql_query("SELECT * FROM `$tbl_smiles`"))
	{
		while ($smiles = mysql_fetch_array($getsmiles))
		{
			$message = str_replace("<IMG SRC=\"$url_smiles/$smiles[smile_url]\">",
			                        $smiles[code], $message);
		}
	}

	return($message);
}

/**
 * bbdecode/bbencode functions:
 * Rewritten - Nathan Codding - Aug 24, 2000
 * quote, code, and list rewritten again in Jan. 2001.
 * All BBCode tags now implemented. Nesting and multiple occurances should be
 * handled fine for all of them. Using str_replace() instead of regexps often
 * for efficiency. quote, list, and code are not regular, so they are
 * implemented as PDAs - probably not all that efficient, but that's the way it is.
 *
 * Note: all BBCode tags are case-insensitive.
 */

function bbencode($message, $is_html_disabled)
{
	// pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
	// This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
	$message = " " . $message;

	// First: If there isn't a "[" and a "]" in the message, don't bother.
	if (! (strpos($message, "[") && strpos($message, "]")) )
	{
		// Remove padding, return.
		$message = substr($message, 1);
		return $message;
	}

	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$message = bbencode_code($message, $is_html_disabled);

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$message = bbencode_quote($message);

	// [list] and [list=x] for (un)ordered lists.
	$message = bbencode_list($message);

	// [b] and [/b] for bolding text.
	$message = preg_replace("/\[b\](.*?)\[\/b\]/si", "<!-- BBCode Start --><B>\\1</B><!-- BBCode End -->", $message);

	// [i] and [/i] for italicizing text.
	$message = preg_replace("/\[i\](.*?)\[\/i\]/si", "<!-- BBCode Start --><I>\\1</I><!-- BBCode End -->", $message);

	// [img]image_url_here[/img] code..
	$message = preg_replace("/\[img\](.*?)\[\/img\]/si", "<!-- BBCode Start --><IMG SRC=\"\\1\" BORDER=\"0\"><!-- BBCode End -->", $message);

	// Patterns and replacements for URL and email tags..
	$patterns = array();
	$replacements = array();

	// [url]xxxx://www.phpbb.com[/url] code..
	$patterns[0] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
	$replacements[0] = '<!-- BBCode u1 Start --><A HREF="\1\2" TARGET="_blank">\1\2</A><!-- BBCode u1 End -->';

	// [url]www.phpbb.com[/url] code.. (no xxxx:// prefix).
	$patterns[1] = "#\[url\](.*?)\[/url\]#si";
	$replacements[1] = '<!-- BBCode u1 Start --><A HREF="http://\1" TARGET="_blank">\1</A><!-- BBCode u1 End -->';

	// [url=xxxx://www.phpbb.com]phpBB[/url] code..
	$patterns[2] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
	$replacements[2] = '<!-- BBCode u2 Start --><A HREF="\1\2" TARGET="_blank">\3</A><!-- BBCode u2 End -->';

	// [url=www.phpbb.com]phpBB[/url] code.. (no xxxx:// prefix).
	$patterns[3] = "#\[url=(.*?)\](.*?)\[/url\]#si";
	$replacements[3] = '<!-- BBCode u2 Start --><A HREF="http://\1" TARGET="_blank">\2</A><!-- BBCode u2 End -->';

	// [email]user@domain.tld[/email] code..
	$patterns[4] = "#\[email\](.*?)\[/email\]#si";
	$replacements[4] = '<!-- BBCode Start --><A HREF="mailto:\1">\1</A><!-- BBCode End -->';

	$message = preg_replace($patterns, $replacements, $message);

	// Remove our padding from the string..
	$message = substr($message, 1);
	return $message;

} // bbencode()



function bbdecode($message)
{
		// Undo [code]
		$code_start_html = "<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>";
		$code_end_html = "</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->";
		$message = str_replace($code_start_html, "[code]", $message);
		$message = str_replace($code_end_html, "[/code]", $message);

		// Undo [quote]
		$quote_start_html = "<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>";
		$quote_end_html = "</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->";
		$message = str_replace($quote_start_html, "[quote]", $message);
		$message = str_replace($quote_end_html, "[/quote]", $message);

		// Undo [b] and [i]
		$message = preg_replace("#<!-- BBCode Start --><B>(.*?)</B><!-- BBCode End -->#s", "[b]\\1[/b]", $message);
		$message = preg_replace("#<!-- BBCode Start --><I>(.*?)</I><!-- BBCode End -->#s", "[i]\\1[/i]", $message);

		// Undo [url] (long form)
		$message = preg_replace("#<!-- BBCode u2 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- BBCode u2 End -->#s", "[url=\\1\\2]\\3[/url]", $message);

		// Undo [url] (short form)
		$message = preg_replace("#<!-- BBCode u1 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- BBCode u1 End -->#s", "[url]\\3[/url]", $message);

		// Undo [email]
		$message = preg_replace("#<!-- BBCode Start --><A HREF=\"mailto:(.*?)\">(.*?)</A><!-- BBCode End -->#s", "[email]\\1[/email]", $message);

		// Undo [img]
		$message = preg_replace("#<!-- BBCode Start --><IMG SRC=\"(.*?)\" BORDER=\"0\"><!-- BBCode End -->#s", "[img]\\1[/img]", $message);

		// Undo lists (unordered/ordered)

		// <li> tags:
		$message = str_replace("<!-- BBCode --><LI>", "[*]", $message);

		// [list] tags:
		$message = str_replace("<!-- BBCode ulist Start --><UL>", "[list]", $message);

		// [list=x] tags:
		$message = preg_replace("#<!-- BBCode olist Start --><OL TYPE=([A1])>#si", "[list=\\1]", $message);

		// [/list] tags:
		$message = str_replace("</UL><!-- BBCode ulist End -->", "[/list]", $message);
		$message = str_replace("</OL><!-- BBCode olist End -->", "[/list]", $message);

		return($message);
}
/**
 * This function does exactly what the PHP4 function array_push() does
 * however, to keep phpBB compatable with PHP 3 we had to come up with out own
 * method of doing it.
 * @author James Atkinson - Feb 5, 2001
 */
function bbcode_array_push(&$stack, $value)
{
   $stack[] = $value;
   return(sizeof($stack));
}

/**
 * This function does exactly what the PHP4 function array_pop() does
 * however, to keep phpBB compatable with PHP 3 we had to come up with out own
 * method of doing it.
 * @author James Atkinson - Feb 5, 2001
 */

function bbcode_array_pop(&$stack)
{
	$arrSize = count($stack);
	$x = 1;

	while(list($key, $val) = each($stack))
	{
		if($x < count($stack))
		{
			$tmpArr[] = $val;
		}
		else
		{
			$return_val = $val;
		}
		$x++;
	}

	$stack = $tmpArr;
	return($return_val);
}

/**
 * Nathan Codding - Jan. 12, 2001.
 * Performs [quote][/quote] bbencoding on the given string, and returns the results.
 * Any unmatched "[quote]" or "[/quote]" token will just be left alone.
 * This works fine with both having more than one quote in a message, and with nested quotes.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 */
function bbencode_quote($message)
{
	// First things first: If there aren't any "[quote]" strings in the message, we don't
	// need to process it at all.

	if (!strpos(strtolower($message), "[quote]"))
	{
		return $message;
	}

	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($message)))
	{
		$curr_pos = strpos($message, "[", $curr_pos);

		// If not found, $curr_pos will be 0, and the loop will end.
		if ($curr_pos)
		{
			// We found a [. It starts at $curr_pos.
			// check if it's a starting or ending quote tag.
			$possible_start = substr($message, $curr_pos, 7);
			$possible_end = substr($message, $curr_pos, 8);
			if (strcasecmp("[quote]", $possible_start) == 0)
			{
				// We have a starting quote tag.
				// Push its position on to the stack, and then keep going to the right.
				bbcode_array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/quote]", $possible_end) == 0)
			{
				// We have an ending quote tag.
				// Check if we've already found a matching starting tag.
				if (sizeof($stack) > 0)
				{
					// There exists a starting tag.
					// We need to do 2 replacements now.
					$start_index = bbcode_array_pop($stack);

					// everything before the [quote] tag.
					$before_start_tag = substr($message, 0, $start_index);

					// everything after the [quote] tag, but before the [/quote] tag.
					$between_tags = substr($message, $start_index + 7, $curr_pos - $start_index - 7);

					// everything after the [/quote] tag.
					$after_end_tag = substr($message, $curr_pos + 8);

					$message = $before_start_tag . "<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>";
					$message .= $between_tags . "</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->";
					$message .= $after_end_tag;

					// Now.. we've screwed up the indices by changing the length of the string.
					// So, if there's anything in the stack, we want to resume searching just after it.
					// otherwise, we go back to the start.
					if (sizeof($stack) > 0)
					{
						$curr_pos = bbcode_array_pop($stack);
						bbcode_array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					// No matching start tag found. Increment pos, keep going.
					++$curr_pos;
				}
			}
			else
			{
				// No starting tag or ending tag.. Increment pos, keep looping.,
				++$curr_pos;
			}
		}
	} // while

	return $message;

} // bbencode_quote()


/**
 * Performs [code][/code] bbencoding on the given string, and returns the results.
 * Any unmatched "[code]" or "[/code]" token will just be left alone.
 * This works fine with both having more than one code block in a message, and with nested code blocks.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 *
 * @author Nathan Codding - Jan. 12, 2001.
 */
function bbencode_code($message, $is_html_disabled)
{
	// First things first: If there aren't any "[code]" strings in the message, we don't
	// need to process it at all.
	if (!strpos(strtolower($message), "[code]"))
	{
		return $message;
	}

	// Second things second: we have to watch out for stuff like [1code] or [/code1] in the
	// input.. So escape them to [#1code] or [/code#1] for now:
	$message = preg_replace("/\[([0-9]+?)code\]/si", "[#\\1code]", $message);
	$message = preg_replace("/\[\/code([0-9]+?)\]/si", "[/code#\\1]", $message);

	$stack = Array();
	$curr_pos = 1;
	$max_nesting_depth = 0;
	while ($curr_pos && ($curr_pos < strlen($message)))
	{
		$curr_pos = strpos($message, "[", $curr_pos);

		// If not found, $curr_pos will be 0, and the loop will end.
		if ($curr_pos)
		{
			// We found a [. It starts at $curr_pos.
			// check if it's a starting or ending code tag.
			$possible_start = substr($message, $curr_pos, 6);
			$possible_end = substr($message, $curr_pos, 7);
			if (strcasecmp("[code]", $possible_start) == 0)
			{
				// We have a starting code tag.
				// Push its position on to the stack, and then keep going to the right.
				bbcode_array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/code]", $possible_end) == 0)
			{
				// We have an ending code tag.
				// Check if we've already found a matching starting tag.
				if (sizeof($stack) > 0)
				{
					// There exists a starting tag.
					$curr_nesting_depth = sizeof($stack);
					$max_nesting_depth = ($curr_nesting_depth > $max_nesting_depth) ? $curr_nesting_depth : $max_nesting_depth;

					// We need to do 2 replacements now.
					$start_index = bbcode_array_pop($stack);

					// everything before the [code] tag.
					$before_start_tag = substr($message, 0, $start_index);

					// everything after the [code] tag, but before the [/code] tag.
					$between_tags = substr($message, $start_index + 6, $curr_pos - $start_index - 6);

					// everything after the [/code] tag.
					$after_end_tag = substr($message, $curr_pos + 7);

					$message = $before_start_tag . "[" . $curr_nesting_depth . "code]";
					$message .= $between_tags . "[/code" . $curr_nesting_depth . "]";
					$message .= $after_end_tag;

					// Now.. we've screwed up the indices by changing the length of the string.
					// So, if there's anything in the stack, we want to resume searching just after it.
					// otherwise, we go back to the start.
					if (sizeof($stack) > 0)
					{
						$curr_pos = bbcode_array_pop($stack);
						bbcode_array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					// No matching start tag found. Increment pos, keep going.
					++$curr_pos;
				}
			}
			else
			{
				// No starting tag or ending tag.. Increment pos, keep looping.,
				++$curr_pos;
			}
		}
	} // while

	if ($max_nesting_depth > 0)
	{
		for ($i = 1; $i <= $max_nesting_depth; ++$i)
		{
			$start_tag = escape_slashes(preg_quote("[" . $i . "code]"));
			$end_tag = escape_slashes(preg_quote("[/code" . $i . "]"));

			$match_count = preg_match_all("/$start_tag(.*?)$end_tag/si", $message, $matches);

			for ($j = 0; $j < $match_count; $j++)
			{
				$before_replace = escape_slashes(preg_quote($matches[1][$j]));
				$after_replace = $matches[1][$j];

				if (($i < 2) && !$is_html_disabled)
				{
					// don't escape special chars when we're nested, 'cause it was already done
					// at the lower level..
					// also, don't escape them if HTML is disabled in this post. it'll already be done
					// by the posting routines.
					$after_replace = htmlspecialchars($after_replace);
				}

				$str_to_match = $start_tag . $before_replace . $end_tag;

				$message = preg_replace("/$str_to_match/si", "<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>$after_replace</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->", $message);
			}
		}
	}

	// Undo our escaping from "second things second" above..
	$message = preg_replace("/\[#([0-9]+?)code\]/si", "[\\1code]", $message);
	$message = preg_replace("/\[\/code#([0-9]+?)\]/si", "[/code\\1]", $message);

	return $message;

} // bbencode_code()


/**
 * Performs [list][/list] and [list=?][/list] bbencoding on the given string, and returns the results.
 * Any unmatched "[list]" or "[/list]" token will just be left alone.
 * This works fine with both having more than one list in a message, and with nested lists.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 * @auhtor Nathan Codding - Jan. 12, 2001.
 */
function bbencode_list($message)
{
	$start_length = Array();
	$start_length[ordered] = 8;
	$start_length[unordered] = 6;

	// First things first: If there aren't any "[list" strings in the message, we don't
	// need to process it at all.

	if (!strpos(strtolower($message), "[list"))
	{
		return $message;
	}

	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($message)))
	{
		$curr_pos = strpos($message, "[", $curr_pos);

		// If not found, $curr_pos will be 0, and the loop will end.
		if ($curr_pos)
		{
			// We found a [. It starts at $curr_pos.
			// check if it's a starting or ending list tag.
			$possible_ordered_start = substr($message, $curr_pos, $start_length[ordered]);
			$possible_unordered_start = substr($message, $curr_pos, $start_length[unordered]);
			$possible_end = substr($message, $curr_pos, 7);
			if (strcasecmp("[list]", $possible_unordered_start) == 0)
			{
				// We have a starting unordered list tag.
				// Push its position on to the stack, and then keep going to the right.
				bbcode_array_push($stack, array($curr_pos, ""));
				++$curr_pos;
			}
			else if (preg_match("/\[list=([a1])\]/si", $possible_ordered_start, $matches))
			{
				// We have a starting ordered list tag.
				// Push its position on to the stack, and the starting char onto the start
				// char stack, the keep going to the right.
				bbcode_array_push($stack, array($curr_pos, $matches[1]));
				++$curr_pos;
			}
			else if (strcasecmp("[/list]", $possible_end) == 0)
			{
				// We have an ending list tag.
				// Check if we've already found a matching starting tag.
				if (sizeof($stack) > 0)
				{
					// There exists a starting tag.
					// We need to do 2 replacements now.
					$start = bbcode_array_pop($stack);
					$start_index = $start[0];
					$start_char = $start[1];
					$is_ordered = ($start_char != "");
					$start_tag_length = ($is_ordered) ? $start_length[ordered] : $start_length[unordered];

					// everything before the [list] tag.
					$before_start_tag = substr($message, 0, $start_index);

					// everything after the [list] tag, but before the [/list] tag.
					$between_tags = substr($message, $start_index + $start_tag_length, $curr_pos - $start_index - $start_tag_length);
					// Need to replace [*] with <LI> inside the list.
					$between_tags = str_replace("[*]", "<!-- BBCode --><LI>", $between_tags);

					// everything after the [/list] tag.
					$after_end_tag = substr($message, $curr_pos + 7);

					if ($is_ordered)
					{
						$message = $before_start_tag . "<!-- BBCode olist Start --><OL TYPE=" . $start_char . ">";
						$message .= $between_tags . "</OL><!-- BBCode olist End -->";
					}
					else
					{
						$message = $before_start_tag . "<!-- BBCode ulist Start --><UL>";
						$message .= $between_tags . "</UL><!-- BBCode ulist End -->";
					}

					$message .= $after_end_tag;

					// Now.. we've screwed up the indices by changing the length of the string.
					// So, if there's anything in the stack, we want to resume searching just after it.
					// otherwise, we go back to the start.
					if (sizeof($stack) > 0)
					{
						$a = bbcode_array_pop($stack);
						$curr_pos = $a[0];
						bbcode_array_push($stack, $a);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					// No matching start tag found. Increment pos, keep going.
					++$curr_pos;
				}
			}
			else
			{
				// No starting tag or ending tag.. Increment pos, keep looping.,
				++$curr_pos;
			}
		}
	} // while

	return $message;

} // bbencode_list()



/**
 * Escapes the "/" character with "\/". This is useful when you need
 * to stick a runtime string into a PREG regexp that is being delimited
 * with slashes.
 * @author Nathan Codding - Oct. 30, 2000
 */
function escape_slashes($input)
{
	$output = str_replace('/', '\/', $input);
	return $output;
}

/*
 * Returns the name of the forum based on ID number
 */
function get_forum_name($forum_id, $db)
{
	global $tbl_forums ;

	$sql = "SELECT forum_name
	        FROM `$tbl_forums`
	        WHERE forum_id = '$forum_id'";

	if(!$r = mysql_query($sql, $db)) return("ERROR");
	if(!$m = mysql_fetch_array($r))  return("None");

	return($m[forum_name]);
}



/**
 * Rewritten by Nathan Codding - Feb 6, 2001.
 * - Goes through the given string, and replaces xxxx://yyyy with an HTML <a> tag linking
 * 	to that URL
 * - Goes through the given string, and replaces www.xxxx.yyyy[zzzz] with an HTML <a> tag linking
 * 	to http://www.xxxx.yyyy[/zzzz]
 * - Goes through the given string, and replaces xxxx@yyyy with an HTML mailto: tag linking
 *		to that email address
 * - Only matches these 2 patterns either after a space, or at the beginning of a line
 *
 * Notes: the email one might get annoying - it's easy to make it more restrictive, though.. maybe
 * have it require something like xxxx@yyyy.zzzz or such. We'll see.
 */

function make_clickable($text)
{

	// pad it with a space so we can match things at the start of the 1st line.
	$ret = " " . $text;

	// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
	// xxxx can only be alpha characters.
	// yyyy is anything up to the first space, newline, or comma.
	$ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<!-- BBCode auto-link start --><a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a><!-- BBCode auto-link end -->", $ret);

	// matches a "www.xxxx.yyyy[/zzzz]" kinda lazy URL thing
	// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
	// yyyy contains either alphanum, "-", or "."
	// zzzz is optional.. will contain everything up to the first space, newline, or comma.
	// This is slightly restrictive - it's not going to match stuff like "forums.foo.com"
	// This is to keep it from getting annoying and matching stuff that's not meant to be a link.
	$ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<!-- BBCode auto-link start --><a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a><!-- BBCode auto-link end -->", $ret);

	// matches an email@domain type address at the start of a line, or after a space.
	// Note: before the @ sign, the only valid characters are the alphanums and "-", "_", or ".".
	// After the @ sign, we accept anything up to the first space, linebreak, or comma.
	$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<!-- BBcode auto-mailto start --><a href=\"mailto:\\2@\\3\">\\2@\\3</a><!-- BBCode auto-mailto end -->", $ret);

	// Remove our padding..
	$ret = substr($ret, 1);

	return($ret);
}


/**
 * Reverses the effects of make_clickable(), for use in editpost.
 * - Does not distinguish between "www.xxxx.yyyy" and "http://aaaa.bbbb" type URLs.
 * @author Nathan Codding - Feb 6, 2001
 */

function undo_make_clickable($text)
{
	$text = preg_replace("#<!-- BBCode auto-link start --><a href=\"(.*?)\" target=\"_blank\">.*?</a><!-- BBCode auto-link end -->#i", "\\1", $text);
	$text = preg_replace("#<!-- BBcode auto-mailto start --><a href=\"mailto:(.*?)\">.*?</a><!-- BBCode auto-mailto end -->#i", "\\1", $text);

	return $text;
}



/**
 * Takes a string, and does the reverse of the PHP standard function
 * htmlspecialchars().
 * @author Nathan Codding - August 24, 2000.
 */
function undo_htmlspecialchars($input)
{
	$input = preg_replace("/&gt;/i", ">", $input);
	$input = preg_replace("/&lt;/i", "<", $input);
	$input = preg_replace("/&quot;/i", "\"", $input);
	$input = preg_replace("/&amp;/i", "&", $input);

	return $input;
}
/**
 * Make sure a username isn't on the disallow list
 */

function validate_username($username, $db)
{
	global $tbl_disallow;

	$sql = "SELECT disallow_username
	        FROM `$tbl_disallow`
	        WHERE disallow_username = '" . addslashes($username) . "'";

	if(!$r = mysql_query($sql, $db)) return(0);

	if($m = mysql_fetch_array($r))
	{
		if($m[disallow_username] == $username) return(1);
		else                                   return(0);
	}

	return(0);
}

/**
 * Check if this is the first post in a topic. Used in editpost.php
 */

function is_first_post($topic_id, $post_id, $db)
{
	global $tbl_posts;

	$sql = "SELECT post_id FROM `$tbl_posts`
	        WHERE topic_id = '$topic_id'
	        ORDER BY post_id LIMIT 1";

	if(!$r = mysql_query($sql, $db)) return(0);
	if(!$m = mysql_fetch_array($r))  return(0);
	if($m[post_id] == $post_id)      return(1);
	else                             return(0);
}

/**
 * Replaces banned words in a string with their replacements
 */

function censor_string($string, $db)
{
	global $tbl_words;

	$r = mysql_query("SELECT word, replacement FROM `$tbl_words`", $db)
	     or die("Error, could not contact the database!
	             Please check your database settings in config.php");

	while($w = mysql_fetch_array($r))
	{
		$word        = quotemeta(stripslashes($w[word]));
		$replacement = stripslashes($w[replacement]);
		$string      = eregi_replace(" $word", " $replacement", $string);
		$string      = eregi_replace("^$word", "$replacement", $string);
		$string      = eregi_replace("<BR>$word", "<BR>$replacement", $string);
	}

	return($string);
}

function is_banned($ipuser, $type, $db)
{
	global $tbl_banlist;

	// Remove old bans
	$sql = "DELETE FROM `$tbl_banlist`
	        WHERE (ban_end < ". mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")).")
	        AND (ban_end > 0)";

	@mysql_query($sql, $db);

	switch($type)
	{
		case "ip":
			$sql = "SELECT ban_ip FROM `$tbl_banlist`";

			if($r = mysql_query($sql, $db))
			{
				while($iprow = mysql_fetch_array($r))
				{
					$ip = $iprow[ban_ip];
					if($ip[strlen($ip) - 1] == ".")
					{
						$db_ip = explode(".", $ip);
						$this_ip = explode(".", $ipuser);

						for($x = 0; $x < count($db_ip) - 1; $x++) $my_ip .= $this_ip[$x] . ".";

						if($my_ip == $ip) return(TRUE);
					}
					else
					{
						if($ipuser == $ip)
						return(TRUE);
					}
				}
			}
			else return(FALSE);
		break;

		case "username":
			$sql = "SELECT ban_userid FROM `$tbl_banlist`
			        WHERE ban_userid = '$ipuser'";
			if($r = mysql_query($sql, $db))
			{
				if(mysql_num_rows($r) > 0) return(TRUE);
			}
		break;
	}

	return(FALSE);
}

/**
 * Checks if the given userid is allowed to log into the given (private) forumid.
 * If the "is_posting" flag is true, checks if the user is allowed to post to that forum.
 */
function check_priv_forum_auth($userid, $forumid, $is_posting, $db)
{
	global $tbl_forum_access;

	$sql = "SELECT count(*) AS user_count
	        FROM `$tbl_forum_access`
	        WHERE (user_id = $userid) AND (forum_id = $forumid) ";

	if ($is_posting)                       $sql .= "AND (can_post = 1)";

	if (!$result = mysql_query($sql, $db)) return FALSE; // no good ...
	if(!$row = mysql_fetch_array($result)) return FALSE;
	if ($row[user_count] <= 0)             return FALSE;

	return TRUE;
}

/**
 * Displays an error message and exits the script. Used in the posting files.
 */
function error_die($msg)
{
	global $tablewidth, $table_bgcolor, $color1;
	global $phpEx;
	global $db, $userdata, $user_logged_in;
	global $FontFace, $FontSize3, $textcolor, $phpbbversion;
	global $starttime;

	print("<br>
		<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"$tablewidth\">
		<TR><TD BGCOLOR=\"$table_bgcolor\">
			<TABLE BORDER=\"0\" CALLPADDING=\"1\" CELLSPACEING=\"1\" WIDTH=\"100%\">
			<TR BGCOLOR=\"$color1\" ALIGN=\"LEFT\">
				<TD>
					<p><font face=\"Verdana\" size=\"2\"><ul>$msg</ul></font></P>
				</TD>
			</TR>
			</TABLE>
		</TD></TR>
	 	</TABLE>
	 <br>");

	 include('page_tail.'.$phpEx);
	 exit;
}

function make_jumpbox()
{
	global $phpEx, $db;
	global $FontFace, $FontSize2, $textcolor;
	global $l_jumpto, $l_selectforum, $l_go;
	global $tbl_catagories, $tbl_forums;

	?>
	<FORM ACTION="viewforum.<?php echo $phpEx?>" METHOD="GET">
	<SELECT NAME="forum"><OPTION VALUE="-1"><?php echo $l_selectforum?></OPTION>
	<?php
		$sql = "SELECT cat_id, cat_title
		        FROM `$tbl_catagories`
		        ORDER BY cat_order";

	if($result = mysql_query($sql, $db))
	{
	   $myrow = mysql_fetch_array($result);
	   do {
	      echo "<OPTION VALUE=\"-1\">&nbsp;</OPTION>\n";
	      echo "<OPTION VALUE=\"-1\">$myrow[cat_title]</OPTION>\n";
	      echo "<OPTION VALUE=\"-1\">----------------</OPTION>\n";
	      $sub_sql = "SELECT forum_id, forum_name FROM `$tbl_forums` WHERE cat_id =
	'$myrow[cat_id]' ORDER BY forum_id";
	      if($res = mysql_query($sub_sql, $db)) {
	    if($row = mysql_fetch_array($res)) {
	       do {
		  $name = stripslashes($row[forum_name]);
		  echo "<OPTION VALUE=\"$row[forum_id]\">$name</OPTION>\n";
	       } while($row = mysql_fetch_array($res));
	    }
	    else {
	       echo "<OPTION VALUE=\"0\">No More Forums</OPTION>\n";
	    }
	      }
	      else {
	    echo "<OPTION VALUE=\"0\">Error Connecting to DB</OPTION>\n";
	      }
	   } while($myrow = mysql_fetch_array($result));
	}
	else {
	   echo "<OPTION VALUE=\"-1\">ERROR</OPTION>\n";
	}
	echo "</select>\n<input type=\"submit\" value=\"$l_go\">\n</form>";
}

function language_select($default, $name="language", $dirname="language/")
{
	global $phpEx;
	$dir = opendir($dirname);
	$lang_select = "<select name=\"$name\" id=\"$name\">\n";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".$phpEx", "", $file);
			$file == $default ? $selected = " SELECTED" : $selected = "";
			$lang_select .= "  <OPTION$selected>$file\n";
		}
	}
	$lang_select .= "</SELECT>\n";
	closedir($dir);
	return $lang_select;
}

function get_translated_file($file)
{
	global $default_lang;

	// Try adding -default_lang to the filename. i.e.:
	// reply.jpg  becomes something like  reply-nederlands.jpg

	$trans_file = preg_replace("/(.*)(\..*?)/", "\\1-$default_lang\\2", $file);

	if(is_file($trans_file)) return $trans_file;
	else                     return $file;
}

function get_syslang_string($sys_lang, $string)
{
	global $phpEx;
	@include('language/lang_'.$sys_lang.'.'.$phpEx);
	$ret_string = $$string;
	return($ret_string);
}


/**
 * Translates any sequence of whitespace (\t, \r, \n, or space) in the given
 * string into a single space character.
 * Returns the result.
 */
function normalize_whitespace($str)
{
	$output = "";

	$tok = preg_split("/[ \t\r\n]+/", $str);
	$tok_count = sizeof($tok);
	for ($i = 0; $i < ($tok_count - 1); $i++)
	{
		$output .= $tok[$i] . " ";
	}

	$output .= $tok[$tok_count - 1];

	return $output;
}

function sync($db, $id, $type)
{
	global $tbl_posts, $tbl_topics, $tbl_forums, $tbl_forums;

	switch($type)
	{
		case 'forum':
			$sql = "SELECT max(post_id) AS last_post FROM `$tbl_posts` WHERE forum_id = $id";
			if(!$result = mysql_query($sql, $db))
			{
				die("Could not get post ID");
			}
			if($row = mysql_fetch_array($result))
			{
				$last_post = $row["last_post"];
			}

			$sql = "SELECT count(post_id) AS total FROM `$tbl_posts` WHERE forum_id = $id";

			if(!$result = mysql_query($sql, $db)) die("Could not get post count");
			if($row = mysql_fetch_array($result)) $total_posts = $row["total"];

			$sql = "SELECT count(topic_id) AS total FROM `$tbl_topics` WHERE forum_id = $id";
			if(!$result = mysql_query($sql, $db)) die("Could not get topic count");
			if($row = mysql_fetch_array($result)) $total_topics = $row["total"];

			$sql = "UPDATE `$tbl_forums`
			        SET forum_last_post_id = '$last_post',
			        forum_posts = $total_posts,
			        forum_topics = $total_topics
			        WHERE forum_id = $id";

			if(!$result = mysql_query($sql, $db))
			{
				die("Could not update forum $id");
			}
		break;

	case 'topic':
		$sql = "SELECT max(post_id) AS last_post FROM `$tbl_posts` WHERE topic_id = $id";

		if(!$result = mysql_query($sql, $db)) die("Could not get post ID");
		if($row = mysql_fetch_array($result)) $last_post = $row["last_post"];

		$sql = "SELECT count(post_id) AS total FROM `$tbl_posts` WHERE topic_id = $id";
		if(!$result = mysql_query($sql, $db)) die("Could not get post count");
		if($row = mysql_fetch_array($result)) $total_posts = $row["total"];
		$total_posts -= 1;

		$sql = "UPDATE `$tbl_topics`
				SET topic_replies = $total_posts, topic_last_post_id = $last_post
				WHERE topic_id = $id";
		if(!$result = mysql_query($sql, $db)) die("Could not update topic $id");
	break;

	case 'all forums':
		$sql = "SELECT forum_id FROM `$tbl_forums`";
		if(!$result = mysql_query($sql, $db)) die("Could not get forum IDs");

		while($row = mysql_fetch_array($result))
		{
			$id = $row["forum_id"];
			sync($db, $id, "forum");
		}
	break;

	case 'all topics':
		$sql = "SELECT topic_id FROM `$tbl_topics`";
		if(!$result = mysql_query($sql, $db)) die("Could not get topic ID's");

		while($row = mysql_fetch_array($result))
		{
			$id = $row["topic_id"];
			sync($db, $id, "topic");
		}
	break;

	}				// end switch

	return(TRUE);
}

function login_form()
{
	global $TableWidth, $table_bgcolor, $color1, $color2, $textcolor;
	global $FontFace, $FontSize2;
	global $phpEx, $userdata, $PHP_SELF;
	global $l_userpass, $l_username, $l_password, $l_passwdlost, $l_submit;
	global $mode, $msgid;

?>
<FORM ACTION="<?php echo $PHP_SELF?>" METHOD="POST">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP">
<TR><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="10" CELLSPACING="1" WIDTH="100%">
	<TR BGCOLOR="<?php echo $color1?>" ALIGN="CENTER">
  		<TD COLSPAN="2">
			<FONT FACE="<?php echo $FontFace?>" SIZE="<?php echo $FontSize2?>" COLOR="<?php echo $textcolor?>">
  			<b><?php echo $l_userpass?></b>
			</FONT>
			<br>
		</TD>
	</TR><TR BGCOLOR="<?php echo $color2?>">
		<TD>
			<FONT FACE="<?php echo $FontFace?>" SIZE="<?php echo $FontSize2?>" COLOR="<?php echo $textcolor?>">
			<b><label for="user"><?php echo $l_username?></label>: &nbsp;</b></font>
			</FONT>
		</TD>
		<TD>
			<input type="text" id="user" name="user" size="25" maxlength="40" value="<?php echo $userdata[username]?>">
		</TD>
	</TR><TR BGCOLOR="<?php echo $color2?>">
		<TD>
			<FONT FACE="<?php echo $FontFace?>" SIZE="<?php echo $FontSize2?>" COLOR="<?php echo $textcolor?>">
			<b><label for="password"><?php echo $l_password?></label>: </b>
			</FONT>
		</TD><TD>
			<input type="password" id="password" name="passwd" size="25" maxlength="25">
		</TD>
	</TR><TR BGCOLOR="<?php echo $color2?>">
		<TD COLSPAN="2" ALIGN="CENTER">
			<FONT FACE="<?php echo $FontFace?>" SIZE="<?php echo $FontSize2?>" COLOR="<?php echo $textcolor?>">
			<a href="sendpassword.<?php echo $phpEx?>"><?php echo $l_passwdlost?></a><br><br>
			</FONT>
			<?PHP
			if (isset($mode))
			{
			?>
				<input type="hidden" name="mode" value="<?php echo $mode?>">
			<?PHP
			}
			?>
			<?PHP
			// Need to pass through the msgid for deleting private messages.
			if (isset($msgid))
			{
			?>
				<input type="hidden" name="msgid" value="<?php echo $msgid?>">
			<?PHP
			}
			?>
			<input type="submit" name="submit" value="<?php echo $l_submit?>">
		</TD>
	</TR>
</TABLE>
</TD></TR>
</TABLE>
</FORM>


<?php
}

/**
 * Less agressive version of stripslashes. Only replaces \\ \' and \"
 * The PHP stripslashes() also removed single backslashes from the string.
 * Expects a string or array as an argument.
 * Returns the result.
 */
function own_stripslashes($string)
{
	$find = array(
			'/\\\\\'/',  // \\\'
			'/\\\\/',    // \\
				'/\\\'/',    // \'
			'/\\\"/');   // \"
	$replace = array(
			'\'',   // \
			'\\',   // \
			'\'',   // '
			'"');   // "
	return preg_replace($find, $replace, $string);
}

/**
 * convert a SQL date or datetime to a unix time stamp
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string SQL DATETIME or DATE
 * @return int unix time stamp
 */

function datetime_to_timestamp($dateTime)
{
    $dateTimeList = explode(' ', $dateTime);
    if ( count($dateTimeList) == 1 ) $dateTimeList[1] = '00:00:00'; // complete the missing time
    list($date, $time) = $dateTimeList;

    list($year, $month, $day) = explode('-', $date);
    list($hour, $min, $sec)   = explode(':', $time);

    return mktime($hour, $min, $sec, $month, $day, $year);
}


?>