<?php 
/**
 * @Author	Marius@Thoora
 * @link http://thoora.com
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright Copyright (C) 1991, 1999 Free Software Foundation, Inc.
				51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
				Everyone is permitted to copy and distribute verbatim copies
				of this license document, but changing it is not allowed.
 * @Since 1.0
 * 
 * Plugin Name: Thoora Widget
 * Plugin URI: http://thoora.com
 * Description: This project is currently closed. Please visit http://thoora.com
 * Version: 2.0
 * Author: Marius@Thoora
 * Author URI: http://thoora.com
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/** Used to reference local files such as web assets and images **/
define('THOORA_PLUGIN_URL', plugin_dir_url( __FILE__ ));

/** Prefix of API call **/
define('THOORA_API_URL', "http://thoora.com/api/1/");

/** Your site URL for our debugging purposes **/
define('THOORA_YOUR_URL', $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);

/** Link to the wordpress Thoora page **/
define('THOORA_WORDPRESS_URL', "http://wordpress.org/extend/plugins/thoora-wordpress-widget/faq/");

/** default topic upon install **/
define('THOORA_DEFAULT_TOPIC', 'http://thoora.com/Thoora/social-media');
define('THOORA_DEFAULT_TYPE', 'news');
define('THOORA_DEFAULT_MAXRESULT', 15);
define('THOORA_DEFAULT_APIKEY', '55d2a5bd2afd70affa9ecce5d78340c2');

//build an array of settings
$docWidget = array(
	'id' => 'thoora-custom',	//make sure that this is unique
	'title' => 'Thoora Widget',	
	'classname' => 'th-custom',	
	'do_wrapper' => true,	
	'show_view' => 'thoora_initView',	
	'fields' => array(
		array(
			'name' => 'Thoora URL',
			'desc' => 'Thoora Topic URL [Change it]',
			'id' => 'url',
			'type' => 'text',
			'std' => THOORA_DEFAULT_TOPIC
		),
		array(
			'name' => 'Type',
			'id' => 'infoType',
			'type' => 'select',
			'options' => array('News', 'Favorites', 'Feeds', 'Images', 'Tweets')
		),
		array(
			'name' => 'Max Results',
			'desc' => 'Max number of items returned',
			'id' => 'maxResult',
			'type' => 'text',
			'std' => THOORA_DEFAULT_MAXRESULT
		),
		array(
			'name' => 'API Key',
			'desc' => 'Used to make requests',
			'id' => 'apiKey',
			'type' => 'text',
			'std' => THOORA_DEFAULT_APIKEY
		),
		array(
			'name' => 'Allow links to Thoora',
			'desc' => 'Provides best experience',
			'id' => 'allowThoora',
			'type' => 'checkbox'
		),
		array (
			'type' => 'custom',
			'std' => '<a href="'.THOORA_WORDPRESS_URL.'" target="_blank">Need help? Click me for FAQ</a>'
		)
	)
);

/*
 * Initialize the JS and CSS that we need. Jquery should exist already 
*/
function thoora_init(){
	wp_register_style('thoora.css', THOORA_PLUGIN_URL . 'thoora.css');
	wp_enqueue_style('thoora.css');
	wp_register_script('thoora.js', THOORA_PLUGIN_URL . 'thoora.js', array('jquery'));
	wp_enqueue_script('thoora.js');
	wp_register_script('jquery.scrollTo-1.4.2-min.js', THOORA_PLUGIN_URL . 'jquery.scrollTo-1.4.2-min.js', array('jquery'));
	wp_enqueue_script('jquery.scrollTo-1.4.2-min.js');
}
add_action('init', 'thoora_init');

/*
 * Get the vid given the URL the user provided
 */

function thoora_getVid($url, $apiKey) {

	$urlParts = parse_url($url);
	
	if ($urlParts['host'] != "thoora.com" || $urlParts['path'] == "") return;

	$path = explode("/", $urlParts['path']);
	
	if (!$path || sizeof($path) < 2) return;
	
	$user  = $path[1];
	$topic = $path[2];
	
	$apiCall = THOORA_API_URL."topics/topic_id.json";
	
	$data = file_get_contents("$apiCall?api_token=$apiKey&screen_name=$user&topic_name_url=$topic&url=".urlencode(THOORA_YOUR_URL));

	$data = json_decode($data);

	return $data->vid;
}

/*
 * Fetch data depending on the dropdown the user selected
 */
function thoora_fetchContent($vid, $type, $count, $apiKey){
	$apiCall = "";

	switch ($type){
		case "news":
			$apiCall = THOORA_API_URL."news/all_stories.json";
			break;
		case "feeds":
			$apiCall = THOORA_API_URL."feeds/all_articles.json";
			break;
		case "tweets":
			$apiCall = THOORA_API_URL."tweets/all_tweets.json";
			break;
		case "images":
			$apiCall = THOORA_API_URL."images/all_images.json";
			break;
		case "favorites":
			$apiCall = THOORA_API_URL."favorites/all_favorites.json";
			break;
	}
	

	$data = file_get_contents("$apiCall?api_token=$apiKey&topic_id=$vid&stories_per_page=$count&url=".urlencode(THOORA_YOUR_URL));
	
	return json_decode($data);
}

/*
 * Fetch the meta data about the vertical
 */

function thoora_fetchInfo($vid, $apiKey) {
	$apiCall = THOORA_API_URL."topics/info.json";
	
	$data = file_get_contents("$apiCall?api_token=$apiKey&topic_id=$vid&url=".urlencode(THOORA_YOUR_URL));
	
	return json_decode($data);
}

/*
 * Load images
 */
function thoora_image($image){
	$imagePath = THOORA_PLUGIN_URL."images/";
	return $imagePath.$image;
}

/*
 * first function that is loaded when widget is loaded
 */

function thoora_initView( $args )
{
	try {
		extract($args);
		
		$url 		= ($args['params']['url'])?$args['params']['url']:THOORA_DEFAULT_TOPIC;
		$type 		= ($args['params']['infoType'])?strtolower($args['params']['infoType']):THOORA_DEFAULT_TYPE;
		$count 		= ($args['params']['maxResult'])?$args['params']['maxResult']:THOORA_DEFAULT_MAXRESULT;
		$count 		= (intval($count)>0)?$count:THOORA_DEFAULT_MAXRESULT;
		$apiKey 	= ($args['params']['apiKey'])?$args['params']['apiKey']:THOORA_DEFAULT_APIKEY;
		$linkThoora = (bool)$args['params']['allowThoora'];
		
		
		
		if ($url == "" || $type == "" || $apiKey == "") throw new Exception;

		$vid 	= thoora_getVid($url, $apiKey);
		$data 	= thoora_fetchContent($vid, $type, $count, $apiKey);
		$info 	= thoora_fetchInfo($vid, $apiKey);
	
		if (!$vid || !$info) throw new Exception;
		
		$topicName = strtoupper($info->topic_name);
		$userName = $info->screen_name;
		$followers = $info->followers_count;
		$topicImg = $info->topic_image_small;
		$typeIcon = $type;
	
		switch ($type){
			case "news":
				$data = $data->stories;
				break;
			case "favorites":
				$data = $data->favorites;
				break;
			case "images":
				$data = $data->images;
				break;
			case "feeds":
				$data = $data->articles;
				break;
			case "tweets":
				$data = $data->tweets;
				break;
		}
	
		?>
	
		<div id="thoora-wrapper">
			<div class="thoora-header">
				<div class="thoora-topicImg"><img src="<?= $topicImg; ?>" /></div>
				<div class="thoora-topicTitle"><?= $topicName; ?></div>
				<div class="thoora-topicInfo">By <?= $userName; ?> &nbsp;&nbsp;<?= $followers?> Follower<?= ($followers == 1)?"":"s"; ?></div>
				<? if ($linkThoora): ?>
					<div class="thoora-button">
						<a href="<?= $url; ?>" target="_blank">
							FOLLOW
						</a>
					</div>
				<? endif; ?>
				
			</div>
			<div class="thoora-backgroundWrapper">
				<div class="thoora-up thoora-arrowRow thoora-arrowHide">
					<div class="thoora-arrowContainer">
						<img src="<?= thoora_image("widget_arrowup.png")?>" />
					</div>
				</div>
				<div class="thoora-scroll">
				<? foreach ($data as $k => $v): ?>
					<? $typeIcon 	= thoora_checkIconType($v, $type); ?>
					<? $typeContent = thoora_checkContentType($v, $type); ?>
					<div class="th-custom-container <?= "thoora$k" ?>">
						<div class="th-custom-containerContent">
							<div class="th-custom-typeIcon"><img src="<?= thoora_image("{$typeIcon}_ico.png")?>" /></div>
								<div class="th-custom-innerContent">										
									<?= thoora_htmlContainer($v, $typeContent); ?>
								</div>
						</div>
					</div>
				<? endforeach;?>
				</div>
				<div class="thoora-down thoora-arrowRow thoora-arrowHide">
					<div class="thoora-arrowContainer">
						<img src="<?= thoora_image("widget_arrowdown.png")?>" />
					</div>
				</div>
			</div>
			<div class="thoora-footer">
				<? if ($linkThoora): ?>
					<a href="<?= $url; ?>" target="_blank">
				<? endif; ?>
						<img src="<?= thoora_image("footer.png")?>" />
				<? if ($linkThoora): ?>
					</a>
				<? endif; ?>
			</div>
		</div>
		<?php
	}
	catch (Exception $e){
		echo "<a href='".THOORA_WORDPRESS_URL."' target='_blank'>Thoora widget not properly configured</a>";
	}
}

/*
 * Private function for sorting data returned
 */

function thoora_checkIconType($data, $type) {
	
	if ($type == "favorites") {
		if ($data->fav_type == "story") 	return "news";
		if ($data->fav_type == "article") 	return "feeds";
		if ($data->fav_type == "image") 	return "images";
		if ($data->fav_type == "tweet") 	return "tweets";
	}
	else
		return $type;
}

/*
 * Private function for sorting data returned
 */

function thoora_checkContentType($data, $type) {

	if ($type == "favorites") {
		if ($data->fav_type == "story" || $data->fav_type == "article" || $data->fav_type == "image") $type = "news";
		if ($data->fav_type == "tweet") $type = "tweets";
	}
	elseif ($type == "images" || $type == "feeds") {
		$type = "news";
	}
	
	return $type;
}

/*
 * Prepare objects before html is loaded
 */

function thoora_htmlContainer($data, $type) {
	$tempData = array();
	
	switch ($type){
		case "news":
			$tempData['title'] 	= thoora_prepareOutput($data->title);
			$tempData['desc'] 	= thoora_prepareOutput($data->description);
			$tempData['url'] 	= $data->source_url;
			$tempData['image'] 	= $data->image_medium;
			$tempData['date'] 	= date("F d, o, h:i A", $data->timestamp);
			return thoora_htmlNewsbox($tempData);
			break;
		case "tweets":
			$tempData['text'] 		= thoora_prepareOutput($data->text);
			$tempData['name'] 		= $data->screen_name;
			$tempData['username'] 	= "@".$data->screen_name;
			$tempData['image'] 		= $data->profile_image;
			$tempData['date'] 		= date("F d, o, h:i A", $data->created_at);
			$tempData['url'] 		= "http://twitter.com/{$data->screen_name}";
			return thoora_htmlTweetsbox($tempData);
			break;		
	}
}

/*
 * Load twitter html container
 */

function thoora_htmlTweetsbox($data) {
	$html = "";
	$html .= "<div class='thoora-tweets'>";
	
		$html .= "<div class='thoora-text'>";
			$html .= thoora_prepare_twitter_text($data['text'], "thoora-twitterLink");
		$html .= "</div>";
		
		$html .= "<div class='thoora-arrow'><img src='".thoora_image("twitter_arrow.png")."' /></div>";
		
		$html .= "<div class='thoora-authorRow'>";
			$html .= "<div class='thoora-image'>";
				$html .= "<img src='{$data['image']}' />";
			$html .= "</div>";
			$html .= "<div class='thoora-name'>";
				$html .= "<a href='{$data['url']}' target='_blank'>";
					$html .= $data['name'];
				$html .= '</a>';
			$html .= "</div>";
		$html .= "</div>";
		
		$html .= "<div class='thoora-infoRow'>";
			$html .= "<div class='thoora-username'>";
				$html .= $data['username'];
			$html .= "</div>";
			$html .= "<div class='thoora-date'>";
				$html .= $data['date'];
			$html .= "</div>";
		$html .= "</div>";
		
	$html .= "</div>";
	$html .="";
	
	return $html;
}

/*
 * Load news html container
 */

function thoora_htmlNewsbox($data){
	$html = "";
	$html = "<a href='{$data['url']}' target='_blank'>";
	if ($data['image']){
		if (strlen($data['title']) > 30) $data['title'] = substr($data['title'], 0, 30)."...";
		$html .= "<div class= 'thoora-news thoora-withPic'>";
		$html .= "<div class='thoora-image'><img src='{$data['image']}' /></div>";
		$html .= "<div class='thoora-title'>{$data['title']}</div>";
		$html .= "<div class='thoora-date'>{$data['date']}</div>";
		$html .= "</div>";
	}
	else {
		if (strlen($data['title']) > 60) $data['title'] = substr($data['title'], 0, 60)."...";
		$html .= "<div class= 'thoora-news thoora-noPic'>";
		$html .= "<div class='thoora-title'>{$data['title']}</div>";
		$html .= "<div class='thoora-desc'>{$data['desc']}</div>";
		$html .= "<div class='thoora-date'>{$data['date']}</div>";
		$html .= "</div>";
	}
	
	$html .= "</a>";
			
	return $html;
	
}

/*
 * Below are just some helper functions that we might need
 */

//encapsulate twitter #topic with css and a link
function thoora_auto_link_twitterTopic($str, $class, $newWindow = true) {	
	$targetBlank = "";
	if ($newWindow) $targetBlank = "target='_blank'";
	
	return preg_replace("/[^&]#([A-Za-z0-9_]+)/", "&nbsp;<span class='$class'><a href='http://twitter.com/#!/search/%23$1' $targetBlank>#$1</a></span>", $str);
}

//encapsulate twitter @user with css and a link
function thoora_auto_link_twitterAccount($str, $class, $newWindow = true) {
	$targetBlank = "";
	if ($newWindow) $targetBlank = "target='_blank'";
	return preg_replace("/@([A-Za-z0-9_]+)/", "<span class='$class'><a href='http://twitter.com/$1' $targetBlank>@$1</a></span>", $str);
}

//encapsulate a link in a span with a given css class
function thoora_auto_link_class($str, $class, $newWindow = true){
	$targetBlank = "";
	if ($newWindow) $targetBlank = "target='_blank'";
	return preg_replace("/(http:\/\/[^\s]+)/", "<span class='$class'><a href='$1' $targetBlank>$1</a></span>", $str);
}

function thoora_prepare_twitter_text($text, $linkClass) {
	return thoora_auto_link_twitterTopic(thoora_auto_link_twitterAccount(thoora_auto_link_class($text, $linkClass) , $linkClass), $linkClass);
}

function thoora_prepareOutput($string){
	return html_entity_decode($string, ENT_QUOTES, "UTF-8");
}


/**
 * Register a New Master Widget
 * 
 * The following is an array of settings for a single widget.
 * All that you need to worry about is defining this array and the 
 * logic + administrative options for the widget is handled.
 * 
 * The actual display of the widget is not handled by the Master
 * Widget Class and requires that you provide a callback or a file that
 * can be displayed when the widget is shown on the front end.
 * 
 * A nice array of values is provided to you when displaying the widget
 * UI, simply use extract($args) to retrieve three variables full of
 * useful data.
 * 
 * The following code should be placed within your theme/functions.php
 * 
 * ************** ************* *************
*/

/**
 * Only display once
 * 
 * This line of code will ensure that we only run the master widget class
 * a single time. We don't need to be throwing errors.
 */
if (!class_exists('MasterWidgetClass')) :

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', 'widgets_init_declare_registered', 1 );

/**
 * Register a widget
 * 
 * @param $widget
 */
function register_master_widget( $widget = null )
{
	global $masterWidgets;
	if (!isset($masterWidgets))
	{
		$masterWidgets = array();
	}
	
	if (!is_array($widget)) return false;
	
	$defaults = array(
		'id' => '1',
		'title' => 'Generic Widget',
		'classname' => $widget['id'],
		'do_wrapper' => true,
		'description' => '',
		'width' => 200,
		'height' => 200,
		'fields' => array(),
	);
	
	$masterWidgets[$widget['id']] = wp_parse_args($widget, $defaults);
	
	return true;
}

/**
 * Get the registered widgets
 * 
 * @return array
 */
function get_registered_masterwidgets()
{
	global $masterWidgets;
	
	if (!did_action('init_master_widgets'))
		do_action('init_master_widgets');
		
	return $masterWidgets;
}

/**
 * Initialize the widgets
 * 
 * @return boolean
 */
function widgets_init_declare_registered()
{
	//initialziing variables
	global $wp_widget_factory;
	$widgets = get_registered_masterwidgets();
	
	//reasons to fail
	if (empty($widgets) || !is_array($widgets)) return false;
	
	foreach ($widgets as $id => $widget)
	{
		$wp_widget_factory->widgets[$id] =& new MasterWidgetClass( $widget );
	}
	
	return false;
}

/**
 * Multiple Widget Master Class
 * 
 * This class allows us to easily create qidgets without having to deal with the
 * mass of php code.
 * 
 * @author byrd
 * @since 1.3
 */
class MasterWidgetClass extends WP_Widget
{
	/**
	 * Constructor.
	 * 
	 * @param $widget
	 */
	function MasterWidgetClass( $widget )
	{
		$this->widget = apply_filters('master_widget_setup', $widget);
		$widget_ops = array(
			'classname' => $this->widget['classname'], 
			'description' => $this->widget['description'] 
		);
		$this->WP_Widget($this->widget['id'], $this->widget['title'], $widget_ops);
	}
	
	/**
	 * Display the Widget View
	 * 
	 * @example extract the args within the view template
	 
	 extract($args[1]); 
	 
	 * @param $args
	 * @param $instance
	 */
	function widget($sidebar, $instance)
	{
		//initializing variables
		$widget = $this->widget;
		$widget['number'] = $this->number;
		
		$args = array(
			'sidebar' => $sidebar,
			'widget' => $widget,
			'params' => $instance,
		);
		
		$show_view = apply_filters('master_widget_view', $this->widget['show_view'], $widget, $instance, $sidebar);
		$title = apply_filters( 'master_widget_title', $instance['title'] );
		
		if ( $widget['do_wrapper'] )
			echo $sidebar['before_widget'];
		
		if ( $title && $widget['do_wrapper'] )
			echo $sidebar['before_title'] . $title . $sidebar['after_title'];
		
		//give people an opportunity
		do_action('master_widget_show_'.$widget['id']);
		
		//load the file if it exists
		if (file_exists($show_view))
			require $show_view;
			
		//call the function if it exists
		elseif (is_callable($show_view))
			call_user_func( $show_view, $args );
			
		//echo if we can't figure it out
		else echo $show_view;
		
		if ($widget['do_wrapper'])
			echo $sidebar['after_widget'];
		
	}
	
	/**
	 * Update from within the admin
	 * 
	 * @param $new_instance
	 * @param $old_instance
	 */
	function update($new_instance, $old_instance)
	{
		//initializing variables
		$new_instance = array_map('strip_tags', $new_instance);
		$instance = wp_parse_args($new_instance, $old_instance);
		
		return $instance;
	}
	
	/**
	 * Display the options form
	 * 
	 * @param $instance
	 */
	function form($instance)
	{
		//reasons to fail
		if (empty($this->widget['fields'])) return false;
		
		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
		);
		
		do_action('master_widget_before');
		foreach ($this->widget['fields'] as $field)
		{
			//making sure we don't throw strict errors
			$field = wp_parse_args($field, $defaults);
			
			$meta = false;
			if (isset($field['id']) && array_key_exists($field['id'], $instance))
				@$meta = attribute_escape($instance[$field['id']]);
			
			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '<p><label for="',$this->get_field_id($field['id']),'">';
			}
			if (isset($field['name']) && $field['name']) echo $field['name'],':';
			
			switch ($field['type'])
			{
				case 'text':
					echo '<input type="text" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="', ($meta ? $meta : @$field['std']), '" class="vibe_text" />', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
					echo '<select class="vibe_select" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '">';
					foreach ($field['options'] as $option)
					{
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ($field['options'] as $option)
					{
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name($field['id']), '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', 
						$option['name'];
					}
					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':				
					echo '<input type="hidden" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" /> ', 
						 '<input class="vibe_checkbox" type="checkbox" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '"', $meta ? ' checked="checked"' : '', ' /> ', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'custom':
					echo $field['std'];
					break;
			}
			
			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '</label></p>';
			}
		}
		do_action('master_widget_after');
		return;
	}
	
}// ends Master Widget Class

//register this widget
register_master_widget($docWidget);
endif; //if !class_exists