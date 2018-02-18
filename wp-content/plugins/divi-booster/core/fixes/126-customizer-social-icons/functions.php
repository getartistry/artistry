<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

// Enqueue user scripts
function db121_enqueue_scripts() { 
	wp_enqueue_style('db121_socicons', plugin_dir_url(__FILE__).'icons.css', array(), BOOSTER_VERSION); // Load socicons font (src: http://www.socicon.com/)
}
add_action('wp_enqueue_scripts', 'db121_enqueue_scripts');

// === Define supported networks 
function db121_get_networks() {
	return array(
	''=>'--- Select Icon ---', /*'custom'=>'[Custom Icon]',*/ 
	
"8tracks"=>"8tracks",
"500px"=>"500px",
"airbnb"=>"Airbnb",
"alliance"=>"Alliance",
"amazon"=>"Amazon",
"amplement"=>"Amplement",
"android"=>"Android",
"angellist"=>"AngelList",
"apple"=>"Apple",
"appnet"=>"Appnet",
"baidu"=>"Baidu",
"bandcamp"=>"Bandcamp",
"battlenet"=>"Battle.net",
"bebee"=>"beBee",
"bebo"=>"Bebo",
"behance"=>"Behance",
"blizzard"=>"Blizzard",
"blogger"=>"Blogger",
"buffer"=>"Buffer",
"chrome"=>"Chrome",
"coderwall"=>"Coderwall",
"dailymotion"=>"Dailymotion",
"deezer"=>"Deezer",
"delicious"=>"Delicious",
"deviantart"=>"DeviantART",
"diablo"=>"Diablo",
"digg"=>"Digg",
"discord"=>"Discord",
"disqus"=>"Disqus",
"douban"=>"Douban",
"draugiem"=>"Draugiem.lv",
"dribbble"=>"Dribbble",
"drupal"=>"Drupal",
"ebay"=>"eBay",
"ello"=>"Ello",
"endomondo"=>"Endomondo",
"envato"=>"Envato",
"etsy"=>"Etsy",
"facebook"=>"Facebook",
"feedburner"=>"FeedBurner",
"filmweb"=>"Filmweb",
"firefox"=>"Firefox",
"flattr"=>"Flattr",
"flickr"=>"Flickr",
"formulr"=>"Formulr",
"forrst"=>"Forrst",
"foursquare"=>"Foursquare",
"friendfeed"=>"FriendFeed",
"github"=>"GitHub",
"goodreads"=>"Goodreads",
"google"=>"Google",
"googleplus"=>"Google+",
"googlegroups"=>"Google Groups",
"googlephotos"=>"Google Photos",
"play"=>"Google Play",
"googlescholar"=>"Google Scholar",
"grooveshark"=>"Grooveshark",
"hearthstone"=>"Hearthstone",
"heroes"=>"Hereos of the Storm",
"hitbox"=>"Hitbox",
"horde"=>"Horde",
"houzz"=>"Houzz",
"icq"=>"ICQ",
"identica"=>"Identica",
"imdb"=>"IMDb",
"instagram"=>"Instagram",
"issuu"=>"Issuu",
"istock"=>"iStock",
"itunes"=>"iTunes",
"keybase"=>"Keybase",
"lanyrd"=>"Lanyrd",
"lastfm"=>"Last.fm",
"line"=>"Line",
"linkedin"=>"Linkedin",
"livejournal"=>"LiveJournal",
"lyft"=>"Lyft",
"macos"=>"macOS",
"mail"=>"Mail",
"medium"=>"Medium",
"meetup"=>"Meetup",
"mixcloud"=>"Mixcloud",
"modelmayhem"=>"Model Mayhem",
"persona"=>"Mozilla Persona",
"mumble"=>"Mumble",
"myspace"=>"Myspace",
"newsvine"=>"NewsVine",
"odnoklassniki"=>"Odnoklassniki",
"openid"=>"OpenID",
"opera"=>"Opera",
"outlook"=>"Outlook",
"overwatch"=>"Overwatch",
"patreon"=>"Patreon",
"paypal"=>"Paypal",
"periscope"=>"Periscope",
"pinterest"=>"Pinterest",
"playstation"=>"PlayStation",
"pocket"=>"Pocket",
"qq"=>"QQ",
"quora"=>"Quora",
"raidcall"=>"RaidCall",
"ravelry"=>"Ravelry",
"reddit"=>"Reddit",
"renren"=>"Renren",
"researchgate"=>"ResearchGate",
"residentadvisor"=>"Resident Advisor",
"reverbnation"=>"Reverbnation",
"rss"=>"RSS",
"sharethis"=>"ShareThis",
"weibo"=>"Sina Weibo",
"skype"=>"Skype",
"slideshare"=>"SlideShare",
"smugmug"=>"SmugMug",
"snapchat"=>"Snapchat",
"songkick"=>"Songkick",
"soundcloud"=>"Soundcloud",
"spotify"=>"Spotify",
"stackexchange"=>"StackExchange",
"stackoverflow"=>"StackOverflow",
"starcraft"=>"Starcraft",
"stayfriends"=>"StayFriends",
"steam"=>"Steam",
"storehouse"=>"Storehouse",
"strava"=>"Strava",
"stumbleupon"=>"StumbleUpon",
"swarm"=>"Swarm",
"teamspeak"=>"TeamSpeak",
"teamviewer"=>"TeamViewer",
"technorati"=>"Technorati",
"telegram"=>"Telegram",
"tripadvisor"=>"TripAdvisor",
"tripit"=>"Tripit",
"triplej"=>"TripleJ",
"tumblr"=>"Tumblr",
"twitch"=>"Twitch",
"twitter"=>"Twitter",
"uber"=>"Uber",
"ventrilo"=>"Ventrilo",
"viadeo"=>"Viadeo",
"viber"=>"Viber",
"viewbug"=>"Viewbug",
"vimeo"=>"Vimeo",
"vine"=>"Vine",
"vkontakte"=>"VKontakte",
"warcraft"=>"Warcraft",
"wechat"=>"WeChat",
"whatsapp"=>"WhatsApp",
"wikipedia"=>"Wikipedia",
"windows"=>"Windows",
"wordpress"=>"WordPress",
"wykop"=>"Wykop",
"xbox"=>"Xbox",
"xing"=>"Xing",
"yahoo"=>"Yahoo!",
"yammer"=>"Yammer",
"yandex"=>"Yandex",
"yelp"=>"Yelp",
"younow"=>"Younow",
"youtube"=>"YouTube",
"zapier"=>"Zapier",
"zerply"=>"Zerply",
"zomato"=>"Zomato",
"zynga"=>"Zynga",
	
	);
}

// Convert json string to an array
// - returns an empty array on error
function db121_json2arr($val) {
	$result = json_decode($val, true); 
	return is_array($result)?$result:array(); 
}

/* Add customizer section */
add_action('customize_register', 'db121_customize_register');
function db121_customize_register($wp_customize){
	
	/* Custom controls */
	class DB121_Customize_Control extends WP_Customize_Control {
		
		public function render_content() {
		
			// Load the model
			$model = db121_json2arr($this->value()); 
			
			// Load the customizer jquery
			include(dirname(__FILE__).'/customizer.js.php'); 
			?>

			<input type="text" id="model_icons" <?php $this->link(); ?> value="<?php esc_attr_e($this->value()); ?>" style="display:none;"/>

			<?php 
			
			// Include the box template and new box button
			include(dirname(__FILE__).'/templates/box.php');
			include(dirname(__FILE__).'/templates/add-new.php');

		}
    }
	
	// Register "divi booster" customizer section 
	$wp_customize->add_panel('divibooster-main', array(
		'title'=>'Divi Booster',
		'priority' => 30 // make sure it shows above widgets / menus to stop jumping
	));
	
	// Register social media customizer sub-section
	$wp_customize->add_section('divibooster-social-icons', array(
		'title' => 'Social Media Icons',
		'panel' => 'divibooster-main'
	) );
	
	// Register the setting
	$wp_customize->add_setting('wtfdivi[fixes][126-customizer-social-icons][icons]', array(
		'type' => 'option',
		'transport' => 'refresh',
		'default'=>'[{"id":"","name":"(No network set)","url":""}]'
		)
	);
	$wp_customize->add_control(
		new DB121_Customize_Control($wp_customize, 'db121_control',
			array(
				'label'      => 'Select Icon',
				'section'    => 'divibooster-social-icons',
				'settings'   => 'wtfdivi[fixes][126-customizer-social-icons][icons]'
			)
		)
	); 
}

function db121_icon_js() {
	$networks = db121_get_networks();
	$option = get_option('wtfdivi');
	if (empty($option['fixes']['126-customizer-social-icons']['icons'])) { return; }

	$icons = json_decode($option['fixes']['126-customizer-social-icons']['icons'], true); // decode json to php array

	if (isset($icons) and count($icons)) { 
	?>
	<script>
	jQuery(function($) {
		<?php 
		
		foreach($icons as $k=>$icon) { 
			
			// Get the URL
			$url = empty($icon['url'])?'':$icon['url'];
			$scheme = parse_url($url, PHP_URL_SCHEME);
			$path = parse_url($url, PHP_URL_PATH);
			$url = (empty($scheme) && !empty($path))?"http://$url":$url; // add the scheme if missing
			
			// Get the ID
			$id = empty($icon['id'])?false:$icon['id'];
		
			// Ouput the jQuery to add the icon
			if ($id) {
				
				if ($id === 'custom') { // custom icon
					?>
					$('.et-social-icons').append('<li class="et-social-icon"><a href="<?php esc_attr_e($url); ?>" class="icon socicon socicon-custom"><img src="<?php esc_attr_e($icon['img']); ?>"></img></a></li>');
					$('.et-extra-social-icons').append('<li class="et-extra-social-icon"><a href="<?php esc_attr_e($url); ?>" class="et-extra-icon et-extra-icon-background-hover socicon socicon-custom"><img src="<?php esc_attr_e($icon['img']); ?>"></img></a></li>');
					<?php 
				} else { // pre-defined icon
					$span = isset($networks[$id])?'<span>'.esc_html($networks[$id]).'</span>':'';
					?>
					$('.et-social-icons').append('<li class="et-social-icon"><a href="<?php esc_attr_e($url); ?>" class="icon socicon socicon-<?php esc_attr_e($id) ?>"><?php echo $span; ?></a></li>');
					$('.et-extra-social-icons').append('<li class="et-extra-social-icon"><a href="<?php esc_attr_e($url); ?>" class="et-extra-icon et-extra-icon-background-hover socicon socicon-<?php esc_attr_e($id) ?>"></a></li>');
					<?php 
				}  
			}

		}
		?>
	});
	</script>
	<?php 
	} 
}
add_action('wp_head', 'db121_icon_js');

// In customizer preview, replace the red circle on icon links with an alert box, so it doesn't look like there has been an error adding the link
function db121_improve_customizer_warning() {
	if (is_customize_preview()) {
		?>
		<style>
		.et-social-icon > a.customize-unpreviewable { cursor: pointer !important; }
		</style>
		<script>
		jQuery(function($){
			/* Improve customizer link disabled notification */
			$(document).on('click', '.et-social-icon > a.customize-unpreviewable', function(){ 
				alert('External links are disabled in the customizer preview.'); 
			});
		});
		</script>
		<?php
	}
}
add_action('wp_head', 'db121_improve_customizer_warning');
