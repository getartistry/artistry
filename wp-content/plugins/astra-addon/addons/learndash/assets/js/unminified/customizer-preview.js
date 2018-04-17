/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Addon
 * @since  1.0.0
 */

( function( $ ) {

	/**
	 * Table Border Radius
	 */
	wp.customize( 'astra-settings[learndash-table-border-radius]', function( value ) {
		value.bind( function( border_radius ) {

			var dynamicStyle = '';

			/**
			 * Table Border Radius
			 */
			dynamicStyle += ' body #learndash_lessons #lesson_heading, body #learndash_profile .learndash_profile_heading, body #learndash_quizzes #quiz_heading, body #learndash_lesson_topics_list div > strong { border-top-left-radius: ' + border_radius + 'px; border-top-right-radius: ' + border_radius + 'px;}';
			dynamicStyle += ' body #learndash_lessons #quiz_list, body #learndash_lessons .lessons_list, body #learndash_quizzes #quiz_list, body #learndash_quizzes .lessons_list, body #learndash_lesson_topics_list .learndash_topic_dots ul, body #learndash_profile .profile_info, body #learndash_profile #course_list { border-bottom-left-radius: ' + border_radius + 'px; border-bottom-right-radius: ' + border_radius + 'px;}';

			astra_add_dynamic_css( 'learndash-table-border-radius', dynamicStyle );
		} );
	} );

	astra_responsive_font_size( 'astra-settings[font-size-learndash-table-heading]', 'body #learndash_lessons #lesson_heading, body #learndash_profile .learndash_profile_heading, body #learndash_quizzes #quiz_heading, body #learndash_lesson_topics_list div > strong' );
	astra_responsive_font_size( 'astra-settings[font-size-learndash-table-content]', 'body #learndash_lessons #quiz_list, body #learndash_lessons .lessons_list, body #learndash_quizzes #quiz_list, body #learndash_quizzes .lessons_list, body #learndash_lesson_topics_list .learndash_topic_dots ul, body #learndash_profile .profile_info, body #learndash_profile #course_list,body #learndash_lessons a, #learndash_quizzes a, .learndash_topic_dots a, .learndash_topic_dots a > span, #learndash_lesson_topics_list span a, #learndash_profile a, #learndash_profile a span' );

	astra_css( 'astra-settings[text-transform-learndash-table-heading]', 'text-transform', 'body #learndash_lessons #lesson_heading, body #learndash_profile .learndash_profile_heading, body #learndash_quizzes #quiz_heading, body #learndash_lesson_topics_list div > strong' );
	astra_css( 'astra-settings[text-transform-learndash-table-content]', 'text-transform', 'body #learndash_lessons #quiz_list, body #learndash_lessons .lessons_list, body #learndash_quizzes #quiz_list, body #learndash_quizzes .lessons_list, body #learndash_lesson_topics_list .learndash_topic_dots ul, body #learndash_profile .profile_info, body #learndash_profile #course_list, #learndash_lessons a, #learndash_quizzes a, .learndash_topic_dots a, .learndash_topic_dots a > span, #learndash_lesson_topics_list span a, #learndash_profile a, #learndash_profile a span' );

} )( jQuery );
