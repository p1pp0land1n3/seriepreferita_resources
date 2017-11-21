<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html>
<html lang="', $txt['lang_dictionary'],'" ', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/webtiryaki.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/font-awesome.min.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery.min.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/bootstrap.min.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/app.js?fin20"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '  
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	echo '
	<style type="text/css">
	@media (min-width: 979px) 
	{
		.container {
			width: ' . $settings['forum_width'] . ';
		}
	}
	</style>';
	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body class="hold-transition skin-blue sidebar-mini">';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<div class="wrapper">
<header class="main-header">
   <h1 class="logo">
		<a href="', $scripturl, '"><i class="fa fa-graduation-cap"></i>&nbsp;', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
	</h1>
   <nav class="navbar navbar-static-top" role="navigation">
    <a href="', $scripturl, '" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  <div class="navbar-custom-menu">';
	if ($context['user']['is_logged'])
	{
		if (!empty($context['user']['avatar']))
			echo '
		<a href="', $scripturl, '?action=profile" class="dropdown-toggle" data-toggle="dropdown">
			<img src="', ($context['user']['avatar'] ? $context['user']['avatar']['href'] : $settings['images_url']. '/theme/noavatar.png'), '" class="navbar-profile-avatar" alt="', $txt['profile'], '" />
			', $context['user']['name'], ($context['user']['unread_messages'] == 0) ? '' : ' <span class="label label-primary visible-xs-inline">' . $context['user']['unread_messages'] . '</span>', ' <span class="caret"></span>
			</a>
 <ul class="dropdown-menu" role="menu">
					<li><a href="', $scripturl, '?action=profile"><span class="fa fa-user"></span> ', $txt['summary'], '</a></li>
										<li><a href="', $scripturl, '?action=profile;area=forumprofile"><span class="fa fa-wrench"></span> ', $txt['forumprofile'], '</a></li>
										<li><a href="', $scripturl, '?action=profile;area=account"><span class="fa fa-cog"></span> ', $txt['account'], '</a></li>
										<li><a href="', $scripturl, '?action=unread"><span class="fa fa-list"></span> ', $txt['unread_topics_visit'], '</a></li>
										<li><a href="', $scripturl, '?action=unreadreplies"><span class="fa fa-comment"></span> ', $txt['unread_replies'], '</a></li>
										<li class="divider"></li>
										', !empty($context['user']['unread_messages']) ? '<li class="visible-xs"><a href="'. $scripturl. '?action=pm"><span class="fa fa-inbox"></span> '. $txt['pm_short']. ' <span class="label label-primary">' . $context['user']['unread_messages'] . '</span></li>' : '', '
										', ($context['user']['unread_messages'] == 0) ? '' : '<li class="divider visible-xs"></li>', '';
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
					<li class="dropdown-header">', $txt['maintain_mode_on'], '</li>';
		if (!empty($context['unapproved_members']))
			echo '<li>
					', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve"><span class="generic_icons approve"></span> ', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</li>';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
					<li><a href="', $scripturl, '?action=moderate;area=reports"><span class="generic_icons warning_moderate"></span>  ', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';

		echo '<li><a href="', $scripturl, '?action=logout;' . $context['session_var'] . '=' . $context['session_id']. '"><span class="fa fa-sign-out"></span> ', $txt['logout'], '</a></li> </ul>
';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '
		<a  data-toggle="modal" data-target="#giris"><i class="fa fa-lock" aria-hidden="true"></i>',$txt['login'],'</a>
		<a class="register" href="' . $scripturl . '?action=register"><i class="fa fa-user-plus" aria-hidden="true"></i>',$txt['register'],'</a>
		<div id="giris" class="modal fade " role="dialog">
		  <div class="modal-dialog modal-sm">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">',$txt['hello_guest'] . $txt['guest_title'],'</h6>
				<img class="avatar img-circle" src="'.$settings['images_url'].'/default_avatar.png" alt="*" style="height: 96px;width: 96px"/>
			  </div>
			  <div class="modal-body">
				<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form class="form-horizontal" id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
					<input type="text" name="user"  class="form-control" placeholder="',$txt['username'],'"/>
					<input type="password" name="passwrd"  class="form-control" placeholder="',$txt['password'],'"/>
					<select name="cookielength" class="form-control">
						<option value="60">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1" selected="selected">', $txt['forever'], '</option>
					</select>
					<p class="text-center">', $txt['quick_login_dec'], '</p>
					<div class="form-group text-center"><input type="submit" class="btn btn-success" value="', $txt['login'], '"  /></div>';
		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';
		echo '
					<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</form> </div>
				</div>
			</div></div>';
	}
	echo '
	</div>
    </nav>
  </header>';
  echo '
   <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
		<img src="', ($context['user']['avatar'] ? $context['user']['avatar']['href'] : $settings['images_url']. '/theme/noavatar.png'), '" class="img-circle" alt="', $txt['profile'], '" />
        </div>
        <div class="pull-left info">
		  <h3 class="username">', $txt['hello_member_ndt'], '</h3>
          <p> ', $context['user']['name'], '</p> 
        </div>
      </div>
      <!-- search form -->
      <form class="sidebar-form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
        <div class="input-group">
		<input class="form-control" type="text" name="search" value="', $txt['forum_search'], '" onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['forum_search'], '\';" />
              <span class="input-group-btn">
                <input class="search_button" type="submit" name="submit" value="" />
              </span>
        </div>';
      // Search within current topic?
		if (!empty($context['current_topic']))
		echo '
		<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
						
		// If we're on a certain board, limit it to this board ;).
		elseif (!empty($context['current_board']))
		echo '
	   <input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
						
		echo '
	</form>
	', template_menu(), '
    </section>
    <!-- /.sidebar -->
  </aside>';

	// The main content should go here.
	echo '
	<div class="content-wrapper">
		<section class="content-header">
    ', theme_linktree(), '
    </section>
	<section class="content">';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '
	</section></div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<footer class="main-footer">
		<ul class="reset">
			<li class="copyright">',theme_copyright(),' | ' , $txt['wt_copyright'], '</li>
			<li><a id="button_xhtml" href="http://validator.w3.org/check?uri=referer" target="_blank" class="new_win" title="', $txt['valid_xhtml'], '"><span>', $txt['xhtml'], '</span></a></li>
			', !empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']) ? '<li><a id="button_rss" href="' . $scripturl . '?action=.xml;type=rss" class="new_win"><span>' . $txt['rss'] . '</span></a></li>' : '', '
			<li class="last"><a id="button_wap2" href="', $scripturl , '?wap2" class="new_win"><span>', $txt['wap2'], '</span></a></li>
		</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '</footer>', !empty($settings['forum_width']) ? '
</div>' : '';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;
	
	echo '
		<ul class="sidebar-menu treeview-menu">';

	// Note: Menu markup has been cleaned up to remove unnecessary spans and classes.
	foreach ($context['menu_buttons'] as $act => $button)
	{
			// Remove useless actions from menu
			if (in_array($act, $prevent_actions))
				continue;

			echo '
			<li id="button_', $act, '"', $button['active_button'] ? ' class="active treeview'. (!empty($button['active_button']) ? ' dropdown' : ''). '"' : '', '', (!$button['active_button'] && !empty($button['sub_buttons']) ? ' class="dropdown"' : ''), '>
			<a href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '', !empty($button['sub_buttons']) ? ' class="dropdown-toggle" data-toggle="dropdown"' :'', '>
			<i class="fa fa-',$act,'"></i>&nbsp;
			<span>', $button['title'], '</span>', !empty($button['sub_buttons']) ? ' ' :'', '
			<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
									</a>';

		if (!empty($button['sub_buttons']))
		{
			echo '
									<ul class="dropdown-menu" role="menu">';
									
							// People always complaining about the toggle button... Let's add the main button here to make them happy.
							echo '
										<li>
											<a href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>', $button['title'], '</a>
										</li>';
										
							// Theme settings
							if ($act == 'admin')
								echo '
										<li>
											<a href="', $scripturl, '?action=admin;area=theme;sa=settings;th=', $settings['theme_id'], '">', $txt['current_theme'], '</a>
										</li>';
										
			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								<span', isset($childbutton['is_last']) ? ' class="last"' : '', '>', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '</span>
							</a>';
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										<span', isset($grandchildbutton['is_last']) ? ' class="last"' : '', '>', $grandchildbutton['title'], '</span>
									</a>
								</li>';

					echo '
											</ul>';
				}

				echo '
										</li>';
			}
				echo '
									</ul>';
		}
		echo '
								</li>';
	}

	echo '
							</ul>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><i class="fa fa-'.$value['text'].' fa-fw"></i> <span class="hidden-xs">' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul class="nav nav-pills">',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>