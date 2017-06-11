<?php
/**
 * @package    Internals
 * @since      1.8.0
 * @version    1.8.0
 */

// Avoid direct calls to this file.
if ( ! class_exists( 'WPSEO_Video_Sitemap' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 *****************************************************************
 * Add support for the WordPress Video plugin
 *
 * 123videonl > http://www.123video.nl/123videoPlayer.swf?mediaSrc=###ID###
 * aniboom > http://api.aniboom.com/e/###ID###
 * archive > archive.org
 * bliptv > http://a.blip.tv/api.swf####ID###
 * break > http://embed.break.com/###ID###
 * brightcove > http://brightcove.com/
 * cbs > http://www.cbs.com/e/###ID###/cbs/1/
 * cellfish > http://cellfish.com/static/swf/player8.swf?Id=###ID###
 * clipfish > http://www.clipfish.de/cfng/flash/clipfish_player_3.swf?as=0&vid=###ID###&r=1&area=e&c=990000
 * clipsyndicate > http://eplayer.clipsyndicate.com/
 * collegehumor > http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id=###ID###&fullscreen=1
 * comedycentral > http://media.mtvnservices.com/mgid:cms:video:thedailyshow.com:###ID###
 * current > http://current.com/e/###ID###/en_US
 * d1g > http://www.d1g.com/swf/embedded_video_player.swf?id=2378&file=http://www.d1g.com/video/play_video/###ID###&usefullscreen=false&autostart=false&overstretch=false&repeat=false&shuffle=false
 * dailymotion > http://www.dailymotion.com/embed/video/###ID###
 * dotsub > http://dotsub.com/media/###ID###/e/m
 * FB > http://www.facebook.com/v/###ID###
 * flash
 * flickr
 * funnyordie > http://www2.funnyordie.com/
 * gametrailers > http://www.gametrailers.com/remote_wrap.php?mid=###ID###
 * gamevideos > http://www.gamevideos.com:80/swf/gamevideos11.swf?embedded=1&fullscreen=1&autoplay=0&src=http://www.gamevideos.com:80/video/videoListXML%3Fid%3D###ID###%26ordinal%3D1184588561564%26adPlay%3Dfalse
 * glumbert > http://www.glumbert.com/embed/###ID###
 * goalvideoz > http://www.goalvideoz.com/vpod2.swf?id=###ID###
 * google > http://video.google.de/googleplayer.swf?docid=###ID###&hl=de&fs=true
 * grouper > http://grouper.com/mtg/mtgPlayer.swf?v=1.7ap=0&rf=-1&vfver=8&extid=-1&extsite=-1&id=###ID###
 * guba > http://www.guba.com/f/root.swf?video_url=http://free.guba.com/uploaditem/###ID###/flash.flv&isEmbeddedPlayer=true
 * hamburg1 > http://www.hamburg1video.de/p/de/###ID###.html
 * hulu
 * ifilm > http://www.ifilm.com/efp?flvbaseclip=###ID###
 * iseeittv > http://www.iseeit.tv/video/###ID###
 * jumpcut > http://jumpcut.com/media/flash/jump.swf?id=###ID###&asset_type=movie&asset_id=###ID###&eb=1
 * kewego > http://sa.kewego.com/swf/p3/epix.swf
 * lastfm > http://cdn.last.fm/videoplayer/33/VideoPlayer.swf?=###ID###
 * liveleak > http://www.liveleak.com/e/###ID###
 * megavideo > http://www.megavideo.com/v/###ID###.3920544471.0
 * metacafe > http://www.metacafe.com/fplayer/###ID###/.swf
 * mncast > http://dory.mncast.com/mncHMovie.swf?movieID=###ID###&skinNum=1
 * mojvideo > http://www.mojvideo.com/v/###ID###
 * mpora > http://video.mpora.com/ep/###ID###/
 * mqstovideocom > http://mqsto.com/video/  http://mqsto.com/vids/
 * msn
 * myspacetv > http://mediaservices.myspace.com/services/media/embed.aspx/m=###ID####,t=1,mt=video
 * myvideo > http://www.myvideo.de/embed/###ID###
 * novamov > http://www.novamov.com/embed.php?v=###ID###
 * onsmash > http://videos.onsmash.com/e/###ID###
 * reason > http://www.reason.tv/embed/video.php?id=###ID###
 * reelzchannel > http://cache.reelzchannel.com/
 * revver > http://flash.revver.com/player/1.0/player.swf?mediaId=###ID###
 * screencast
 * sevenload > http://embed.sevenload.com/widgets/singlePlayer/###ID###/?autoplay=false&env=slcom-ext
 * slideshare > http://static.slidesharecdn.com/swf/ssplayer2.swf?doc=###ID###
 * smotri > http://pics.smotri.com/scrubber_custom8.swf?file=###ID###&bufferTime=3&autoStart=false&str_lang=eng&xmlsource=http%3A%2F%2Fpics.smotri.com%2Fcskins%2Fblue%2Fskin_color_lightaqua.xml&xmldatasource=http%3A%2F%2Fpics.smotri.com%2Fskin_ng.xml
 * sumotv > http://www.sumo.tv/embed.swf?file=###ID###.flv&autostart=false
 * teachertube > http://www.teachertube.com/
 * trilulilu > http://www.trilulilu.ro/embed-video/keskifa/###ID###
 * tutv > http://www.tu.tv/tutvweb.swf?kpt=###ID###
 * uncut > http://uncutvideo.aol.com/en-US/uc_videoplayer.swf?aID=1###ID###
 * veoh > http://www.veoh.com/veohplayer.swf?permalinkId=###ID###&id=anonymous&player=videodetailsembedded&videoAutoPlay=0
 * videotube > http://www.videotube.de/ci/flash/videotube_player_4.swf?videoId=###ID###&svsf=0&lang=german&host=www.videotube.de
 * vimeo > http://player.vimeo.com/video/###ID###
 * vsocial > http://static.vsocial.com/flash/ups.swf?d=###ID###&a=0
 * vzaar
 * wandeo > http://www.wandeo.com/v/###ID###
 * wat > http://www.wat.tv/swf2/###ID###
 * yahoo > http://d.yimg.com/static.video.yahoo.com/
 * youreporter > http://www.youreporter.it/
 * youtube > http://www.youtube.com/embed/###ID###?wmode=transparent
 *
 * @see      https://wordpress.org/plugins/wordpress-video-plugin/
 *
 * {@internal Last update: July 2014 based upon v 0.759.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Wordpress_Video' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Wordpress_Video
	 */
	class WPSEO_Video_Plugin_Wordpress_Video extends WPSEO_Video_Supported_Plugin {

		/**
		 * @var array $shortcodes_to_add Shortcodes added by this plugin
		 *
		 * {@internal This is the complete list of shortcodes they support. I've commented out the youtubeplaylist
		 * one as this plugin does not support playlists.}}
		 */
		private $shortcodes_to_add = array(
			'123videonl',
			'aniboom',
			'archive',
			'bliptv',
			'break',
			'brightcove',
			'cbs',
			'cellfish',
			'clipfish',
			'clipsyndicate',
			'collegehumor',
			'comedycentral',
			'current',
			'd1g',
			'dailymotion',
			'dotsub',
			'FB',
			'flash',
			'flickr',
			'funnyordie',
			'gametrailers',
			'gamevideos',
			'glumbert',
			'goalvideoz',
			'google',
			'grouper',
			'guba',
			'hamburg1',
			'hulu',
			'ifilm',
			'iseeittv',
			'jumpcut',
			'kewego',
			'lastfm',
			'liveleak',
			'megavideo',
			'metacafe',
			'mncast',
			'mojvideo',
			'mpora',
			'mqstovideocom',
			'msn',
			'myspacetv',
			'myvideo',
			'novamov',
			'onsmash',
			'reason',
			'reelzchannel',
			'revver',
			'screencast',
			'sevenload',
			'slideshare',
			'smotri',
			'sumotv',
			'teachertube',
			'trilulilu',
			'tutv',
			'uncut',
			'veoh',
			'videotube',
			'vimeo',
			'vsocial',
			'vzaar',
			'wandeo',
			'wat',
			'yahoo',
			'youreporter',
			'youtube',
		);


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'youtube_plugin_callback' ) && defined( 'YOUTUBE_TARGET' ) ) {
				$this->shortcodes = $this->shortcodes_to_add;
			}
		}


		/**
		 * Analyse a video shortcode from the plugin for usable video information
		 *
		 * @param  string $full_shortcode Full shortcode as found in the post content.
		 * @param  string $sc             Shortcode found.
		 * @param  array  $atts           Shortcode attributes - already decoded if needed.
		 * @param  string $content        The shortcode content, i.e. the bit between [sc]content[/sc].
		 *
		 * @return array   An array with the usable information found or else an empty array.
		 */
		public function get_info_from_shortcode( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();

			$id_or_url = '';
			if ( isset( $content ) && ( is_string( $content ) && $content !== '' ) ) {
				/*
				 * The non-attribute name content has already been placed in $content
				 * [collegehumor 1727961 200 100]
				 * If this does not work - just do a regex on full_shortcode '`\[' . preg_quote( $sc, '`' ) . '(.+?)\]`i'
				 */

				$content = str_replace( '  ', ' ', $content );

				// Split in id, width, height if applicable.
				$list      = explode( ' ', $content );
				$id_or_url = trim( $list[0] );
				$atts      = $this->normalize_dimension_attributes( $list, $atts );
			}

			switch ( $sc ) {
				case 'blip.tv':
				case 'bliptv':
					$vid = $this->what_the_blip( $vid, $id_or_url, $full_shortcode );
					break;

				case 'flash':
					if ( $id_or_url !== '' ) {
						$vid['url'] = $id_or_url;
					}
					break;

				case 'lastfm':
					/**
					 * {@internal The plugin author treats the whole thing as an id including the 'embed='.
					 * As we don't deal with lastfm as a service anyway, not something to worry too much about for now.
					 * [lastfm embed=true&creator=Kettcar&title=Landungsbr%C3%BCcken+raus&uniqueName=Landungsbr%C3%BCcken+raus&albumArt=http://cdn.last.fm/coverart/130x130/1422004.jpg&album=Du+Und+Wieviel+Von+Deinen+Freunden&duration=257&image=http://panther3.last.fm/storable/videocap/15582/0/original.jpg&FSSupport=trueS] }}
					 */
					if ( $id_or_url !== '' ) {
						$vid['id'] = $id_or_url;
					}
					elseif ( isset( $atts['embed'] ) && ( is_string( $atts['embed'] ) && $atts['embed'] !== '' ) ) {
						$vid['id'] = 'embed=' . $atts['embed'];
					}
					break;

				case 'slideshare':
					/**
					 * Deal with wordpress.com format as provided by Slideshare themselves
					 * [slideshare id=82836&doc=slidecasting-1013073]
					 */
					if ( $id_or_url !== '' ) {
						$id = $id_or_url;
					}
					elseif ( isset( $atts['id'] ) && ( is_string( $atts['id'] ) && $atts['id'] !== '' ) ) {
						$id = $atts['id'];
					}
					if ( isset( $id ) ) {
						$id        = explode( '&', $id );
						$vid['id'] = $id[0];
						unset( $id );
					}
					break;

				default:
					if ( $id_or_url !== '' ) {
						$vid['id'] = $id_or_url;
					}
					break;
			}

			if ( $vid !== array() ) {
				$vid['type'] = $this->determine_type_from_shortcode( $sc );

				// Width/height for video services without detail retrieval.
				$vid = $this->maybe_get_dimensions( $vid, $atts, true );
			}

			return $vid;
		}


		/**
		 * Determine the video type to set based on the Shortcode found.
		 *
		 * @todo: figure out what the service type should be for some of the other shortcodes
		 *
		 * @param  string $sc Shortcode.
		 *
		 * @return string      Video type
		 */
		protected function determine_type_from_shortcode( $sc ) {
			// Deal with non-standard service names.
			switch ( $sc ) {
				case 'archive':
					$type = 'archiveorg';
					break;

				case 'blip.tv':
				case 'bliptv':
					$type = 'blip';
					break;

				case 'FB':
					$type = 'facebook';
					break;

				case 'google':
					$type = 'googlevideo';
					break;

				case 'ifilm':
					$type = 'spike';
					break;

				case 'myspacetv':
					$type = 'myspace';
					break;

				default:
					$type = $sc;
					break;
			}

			return $type;
		}
	} /* End of class */

} /* End of class-exists wrapper */
