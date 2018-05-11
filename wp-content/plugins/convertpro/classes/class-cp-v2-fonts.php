<?php
/**
 * CP_V2_Fonts.
 *
 * @package ConvertPro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CP_V2_Fonts' ) ) {

	/**
	 * Class CP_V2_Fonts.
	 */
	class CP_V2_Fonts {

		/**
		 * An array of fonts / weights.
		 *
		 * @var array
		 */
		static private $fonts = array();

		/**
		 * Get font list for dropdown
		 *
		 * @since  0.0.1
		 */
		static public function cp_get_fonts() {

			$google_fonts  = self::$google;
			$default_fonts = self::$default;

			$google_fonts = apply_filters( 'cp_add_google_fonts', $google_fonts );

			foreach ( $default_fonts as $font => $value ) {
				array_unshift( $value, 'Inherit' );
				$default_fonts[ $font ] = $value;
			}

			foreach ( $google_fonts as $font => $value ) {
				array_unshift( $value, 'Inherit' );
				$google_fonts[ $font ] = $value;
			}

			$fonts = array(
				'Default' => $default_fonts,
				'Google'  => $google_fonts,
			);

			return $fonts;

		}

		/**
		 * Array with a list of default fonts.
		 *
		 * @var array
		 */
		static public $default = array(
			'inherit'   => array(
				'Normal',
				'Bold',
			),
			'Helvetica' => array(
				'Normal',
				'Bold',
			),
			'Verdana'   => array(
				'Normal',
				'Bold',
			),
			'Arial'     => array(
				'Normal',
				'Bold',
			),
			'Times'     => array(
				'Normal',
				'Bold',
			),
			'Courier'   => array(
				'Normal',
				'Bold',
			),
		);

		/**
		 * Array with Google Fonts.
		 *
		 * @var array
		 */
		static public $google = array(
			'ABeeZee'                  => array(
				'Normal',
			),
			'Abel'                     => array(
				'Normal',
			),
			'Abril Fatface'            => array(
				'Normal',
			),
			'Aclonica'                 => array(
				'Normal',
			),
			'Acme'                     => array(
				'Normal',
			),
			'Actor'                    => array(
				'Normal',
			),
			'Adamina'                  => array(
				'Normal',
			),
			'Advent Pro'               => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
			),
			'Aguafina Script'          => array(
				'Normal',
			),
			'Akronim'                  => array(
				'Normal',
			),
			'Aladin'                   => array(
				'Normal',
			),
			'Aldrich'                  => array(
				'Normal',
			),
			'Alef'                     => array(
				'Normal',
				'700',
			),
			'Alegreya'                 => array(
				'Normal',
				'700',
				'900',
			),
			'Alegreya SC'              => array(
				'Normal',
				'700',
				'900',
			),
			'Alegreya Sans'            => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
			),
			'Alegreya Sans SC'         => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
			),
			'Alex Brush'               => array(
				'Normal',
			),
			'Alfa Slab One'            => array(
				'Normal',
			),
			'Alice'                    => array(
				'Normal',
			),
			'Alike'                    => array(
				'Normal',
			),
			'Alike Angular'            => array(
				'Normal',
			),
			'Allan'                    => array(
				'Normal',
				'700',
			),
			'Allerta'                  => array(
				'Normal',
			),
			'Allerta Stencil'          => array(
				'Normal',
			),
			'Allura'                   => array(
				'Normal',
			),
			'Almendra'                 => array(
				'Normal',
				'700',
			),
			'Almendra Display'         => array(
				'Normal',
			),
			'Almendra SC'              => array(
				'Normal',
			),
			'Amarante'                 => array(
				'Normal',
			),
			'Amaranth'                 => array(
				'Normal',
				'700',
			),
			'Amatic SC'                => array(
				'Normal',
				'700',
			),
			'Amethysta'                => array(
				'Normal',
			),
			'Amiri'                    => array(
				'Normal',
				'700',
			),
			'Amita'                    => array(
				'Normal',
				'700',
			),
			'Anaheim'                  => array(
				'Normal',
			),
			'Andada'                   => array(
				'Normal',
			),
			'Andika'                   => array(
				'Normal',
			),
			'Angkor'                   => array(
				'Normal',
			),
			'Annie Use Your Telescope' => array(
				'Normal',
			),
			'Anonymous Pro'            => array(
				'Normal',
				'700',
			),
			'Antic'                    => array(
				'Normal',
			),
			'Antic Didone'             => array(
				'Normal',
			),
			'Antic Slab'               => array(
				'Normal',
			),
			'Anton'                    => array(
				'Normal',
			),
			'Arapey'                   => array(
				'Normal',
			),
			'Arbutus'                  => array(
				'Normal',
			),
			'Arbutus Slab'             => array(
				'Normal',
			),
			'Architects Daughter'      => array(
				'Normal',
			),
			'Archivo Black'            => array(
				'Normal',
			),
			'Archivo Narrow'           => array(
				'Normal',
				'700',
			),
			'Arimo'                    => array(
				'Normal',
				'700',
			),
			'Arizonia'                 => array(
				'Normal',
			),
			'Armata'                   => array(
				'Normal',
			),
			'Artifika'                 => array(
				'Normal',
			),
			'Arvo'                     => array(
				'Normal',
				'700',
			),
			'Arya'                     => array(
				'Normal',
				'700',
			),
			'Asap'                     => array(
				'Normal',
				'700',
			),
			'Asar'                     => array(
				'Normal',
			),
			'Asset'                    => array(
				'Normal',
			),
			'Astloch'                  => array(
				'Normal',
				'700',
			),
			'Asul'                     => array(
				'Normal',
				'700',
			),
			'Atomic Age'               => array(
				'Normal',
			),
			'Aubrey'                   => array(
				'Normal',
			),
			'Audiowide'                => array(
				'Normal',
			),
			'Autour One'               => array(
				'Normal',
			),
			'Average'                  => array(
				'Normal',
			),
			'Average Sans'             => array(
				'Normal',
			),
			'Averia Gruesa Libre'      => array(
				'Normal',
			),
			'Averia Libre'             => array(
				'Normal',
				'300',
				'700',
			),
			'Averia Sans Libre'        => array(
				'Normal',
				'300',
				'700',
			),
			'Averia Serif Libre'       => array(
				'Normal',
				'300',
				'700',
			),
			'Bad Script'               => array(
				'Normal',
			),
			'Balthazar'                => array(
				'Normal',
			),
			'Bangers'                  => array(
				'Normal',
			),
			'Basic'                    => array(
				'Normal',
			),
			'Battambang'               => array(
				'Normal',
				'700',
			),
			'Baumans'                  => array(
				'Normal',
			),
			'Bayon'                    => array(
				'Normal',
			),
			'Belgrano'                 => array(
				'Normal',
			),
			'Belleza'                  => array(
				'Normal',
			),
			'BenchNine'                => array(
				'Normal',
				'300',
				'700',
			),
			'Bentham'                  => array(
				'Normal',
			),
			'Berkshire Swash'          => array(
				'Normal',
			),
			'Bevan'                    => array(
				'Normal',
			),
			'Bigelow Rules'            => array(
				'Normal',
			),
			'Bigshot One'              => array(
				'Normal',
			),
			'Bilbo'                    => array(
				'Normal',
			),
			'Bilbo Swash Caps'         => array(
				'Normal',
			),
			'Biryani'                  => array(
				'Normal',
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
			),
			'Bitter'                   => array(
				'Normal',
				'700',
			),
			'Black Ops One'            => array(
				'Normal',
			),
			'Bokor'                    => array(
				'Normal',
			),
			'Bonbon'                   => array(
				'Normal',
			),
			'Boogaloo'                 => array(
				'Normal',
			),
			'Bowlby One'               => array(
				'Normal',
			),
			'Bowlby One SC'            => array(
				'Normal',
			),
			'Brawler'                  => array(
				'Normal',
			),
			'Bree Serif'               => array(
				'Normal',
			),
			'Bubblegum Sans'           => array(
				'Normal',
			),
			'Bubbler One'              => array(
				'Normal',
			),
			'Buda'                     => array(
				'300',
			),
			'Buenard'                  => array(
				'Normal',
				'700',
			),
			'Butcherman'               => array(
				'Normal',
			),
			'Butterfly Kids'           => array(
				'Normal',
			),
			'Cabin'                    => array(
				'Normal',
				'500',
				'600',
				'700',
			),
			'Cabin Condensed'          => array(
				'Normal',
				'500',
				'600',
				'700',
			),
			'Cabin Sketch'             => array(
				'Normal',
				'700',
			),
			'Caesar Dressing'          => array(
				'Normal',
			),
			'Cagliostro'               => array(
				'Normal',
			),
			'Calligraffitti'           => array(
				'Normal',
			),
			'Cambay'                   => array(
				'Normal',
				'700',
			),
			'Cambo'                    => array(
				'Normal',
			),
			'Candal'                   => array(
				'Normal',
			),
			'Cantarell'                => array(
				'Normal',
				'700',
			),
			'Cantata One'              => array(
				'Normal',
			),
			'Cantora One'              => array(
				'Normal',
			),
			'Capriola'                 => array(
				'Normal',
			),
			'Cardo'                    => array(
				'Normal',
				'700',
			),
			'Carme'                    => array(
				'Normal',
			),
			'Carrois Gothic'           => array(
				'Normal',
			),
			'Carrois Gothic SC'        => array(
				'Normal',
			),
			'Carter One'               => array(
				'Normal',
			),
			'Catamaran'                => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Caudex'                   => array(
				'Normal',
				'700',
			),
			'Caveat'                   => array(
				'Normal',
				'700',
			),
			'Caveat Brush'             => array(
				'Normal',
			),
			'Cedarville Cursive'       => array(
				'Normal',
			),
			'Ceviche One'              => array(
				'Normal',
			),
			'Changa One'               => array(
				'Normal',
			),
			'Chango'                   => array(
				'Normal',
			),
			'Chau Philomene One'       => array(
				'Normal',
			),
			'Chela One'                => array(
				'Normal',
			),
			'Chelsea Market'           => array(
				'Normal',
			),
			'Chenla'                   => array(
				'Normal',
			),
			'Cherry Cream Soda'        => array(
				'Normal',
			),
			'Cherry Swash'             => array(
				'Normal',
				'700',
			),
			'Chewy'                    => array(
				'Normal',
			),
			'Chicle'                   => array(
				'Normal',
			),
			'Chivo'                    => array(
				'Normal',
				'900',
			),
			'Chonburi'                 => array(
				'Normal',
			),
			'Cinzel'                   => array(
				'Normal',
				'700',
				'900',
			),
			'Cinzel Decorative'        => array(
				'Normal',
				'700',
				'900',
			),
			'Clicker Script'           => array(
				'Normal',
			),
			'Coda'                     => array(
				'Normal',
				'800',
			),
			'Coda Caption'             => array(
				'800',
			),
			'Codystar'                 => array(
				'Normal',
				'300',
			),
			'Combo'                    => array(
				'Normal',
			),
			'Comfortaa'                => array(
				'Normal',
				'300',
				'700',
			),
			'Coming Soon'              => array(
				'Normal',
			),
			'Concert One'              => array(
				'Normal',
			),
			'Condiment'                => array(
				'Normal',
			),
			'Content'                  => array(
				'Normal',
				'700',
			),
			'Contrail One'             => array(
				'Normal',
			),
			'Convergence'              => array(
				'Normal',
			),
			'Cookie'                   => array(
				'Normal',
			),
			'Copse'                    => array(
				'Normal',
			),
			'Corben'                   => array(
				'Normal',
				'700',
			),
			'Courgette'                => array(
				'Normal',
			),
			'Cousine'                  => array(
				'Normal',
				'700',
			),
			'Coustard'                 => array(
				'Normal',
				'900',
			),
			'Covered By Your Grace'    => array(
				'Normal',
			),
			'Crafty Girls'             => array(
				'Normal',
			),
			'Creepster'                => array(
				'Normal',
			),
			'Crete Round'              => array(
				'Normal',
			),
			'Crimson Text'             => array(
				'Normal',
				'600',
				'700',
			),
			'Croissant One'            => array(
				'Normal',
			),
			'Crushed'                  => array(
				'Normal',
			),
			'Cuprum'                   => array(
				'Normal',
				'700',
			),
			'Cutive'                   => array(
				'Normal',
			),
			'Cutive Mono'              => array(
				'Normal',
			),
			'Damion'                   => array(
				'Normal',
			),
			'Dancing Script'           => array(
				'Normal',
				'700',
			),
			'Dangrek'                  => array(
				'Normal',
			),
			'Dawning of a New Day'     => array(
				'Normal',
			),
			'Days One'                 => array(
				'Normal',
			),
			'Dekko'                    => array(
				'Normal',
			),
			'Delius'                   => array(
				'Normal',
			),
			'Delius Swash Caps'        => array(
				'Normal',
			),
			'Delius Unicase'           => array(
				'Normal',
				'700',
			),
			'Della Respira'            => array(
				'Normal',
			),
			'Denk One'                 => array(
				'Normal',
			),
			'Devonshire'               => array(
				'Normal',
			),
			'Dhurjati'                 => array(
				'Normal',
			),
			'Didact Gothic'            => array(
				'Normal',
			),
			'Diplomata'                => array(
				'Normal',
			),
			'Diplomata SC'             => array(
				'Normal',
			),
			'Domine'                   => array(
				'Normal',
				'700',
			),
			'Donegal One'              => array(
				'Normal',
			),
			'Doppio One'               => array(
				'Normal',
			),
			'Dorsa'                    => array(
				'Normal',
			),
			'Dosis'                    => array(
				'Normal',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
			),
			'Dr Sugiyama'              => array(
				'Normal',
			),
			'Droid Sans'               => array(
				'Normal',
				'700',
			),
			'Droid Sans Mono'          => array(
				'Normal',
			),
			'Droid Serif'              => array(
				'Normal',
				'700',
			),
			'Duru Sans'                => array(
				'Normal',
			),
			'Dynalight'                => array(
				'Normal',
			),
			'EB Garamond'              => array(
				'Normal',
			),
			'Eagle Lake'               => array(
				'Normal',
			),
			'Eater'                    => array(
				'Normal',
			),
			'Economica'                => array(
				'Normal',
				'700',
			),
			'Eczar'                    => array(
				'Normal',
				'500',
				'600',
				'700',
				'800',
			),
			'Ek Mukta'                 => array(
				'Normal',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
			),
			'Electrolize'              => array(
				'Normal',
			),
			'Elsie'                    => array(
				'Normal',
				'900',
			),
			'Elsie Swash Caps'         => array(
				'Normal',
				'900',
			),
			'Emblema One'              => array(
				'Normal',
			),
			'Emilys Candy'             => array(
				'Normal',
			),
			'Engagement'               => array(
				'Normal',
			),
			'Englebert'                => array(
				'Normal',
			),
			'Enriqueta'                => array(
				'Normal',
				'700',
			),
			'Erica One'                => array(
				'Normal',
			),
			'Esteban'                  => array(
				'Normal',
			),
			'Euphoria Script'          => array(
				'Normal',
			),
			'Ewert'                    => array(
				'Normal',
			),
			'Exo'                      => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Exo 2'                    => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Expletus Sans'            => array(
				'Normal',
				'500',
				'600',
				'700',
			),
			'Fanwood Text'             => array(
				'Normal',
			),
			'Fascinate'                => array(
				'Normal',
			),
			'Fascinate Inline'         => array(
				'Normal',
			),
			'Faster One'               => array(
				'Normal',
			),
			'Fasthand'                 => array(
				'Normal',
			),
			'Fauna One'                => array(
				'Normal',
			),
			'Federant'                 => array(
				'Normal',
			),
			'Federo'                   => array(
				'Normal',
			),
			'Felipa'                   => array(
				'Normal',
			),
			'Fenix'                    => array(
				'Normal',
			),
			'Finger Paint'             => array(
				'Normal',
			),
			'Fira Mono'                => array(
				'Normal',
				'700',
			),
			'Fira Sans'                => array(
				'Normal',
				'300',
				'500',
				'700',
			),
			'Fjalla One'               => array(
				'Normal',
			),
			'Fjord One'                => array(
				'Normal',
			),
			'Flamenco'                 => array(
				'300',
				'Normal',
			),
			'Flavors'                  => array(
				'Normal',
			),
			'Fondamento'               => array(
				'Normal',
			),
			'Fontdiner Swanky'         => array(
				'Normal',
			),
			'Forum'                    => array(
				'Normal',
			),
			'Francois One'             => array(
				'Normal',
			),
			'Freckle Face'             => array(
				'Normal',
			),
			'Fredericka the Great'     => array(
				'Normal',
			),
			'Fredoka One'              => array(
				'Normal',
			),
			'Freehand'                 => array(
				'Normal',
			),
			'Fresca'                   => array(
				'Normal',
			),
			'Frijole'                  => array(
				'Normal',
			),
			'Fruktur'                  => array(
				'Normal',
			),
			'Fugaz One'                => array(
				'Normal',
			),
			'GFS Didot'                => array(
				'Normal',
			),
			'GFS Neohellenic'          => array(
				'Normal',
				'700',
			),
			'Gabriela'                 => array(
				'Normal',
			),
			'Gafata'                   => array(
				'Normal',
			),
			'Galdeano'                 => array(
				'Normal',
			),
			'Galindo'                  => array(
				'Normal',
			),
			'Gentium Basic'            => array(
				'Normal',
				'700',
			),
			'Gentium Book Basic'       => array(
				'Normal',
				'700',
			),
			'Geo'                      => array(
				'Normal',
			),
			'Geostar'                  => array(
				'Normal',
			),
			'Geostar Fill'             => array(
				'Normal',
			),
			'Germania One'             => array(
				'Normal',
			),
			'Gidugu'                   => array(
				'Normal',
			),
			'Gilda Display'            => array(
				'Normal',
			),
			'Give You Glory'           => array(
				'Normal',
			),
			'Glass Antiqua'            => array(
				'Normal',
			),
			'Glegoo'                   => array(
				'Normal',
				'700',
			),
			'Gloria Hallelujah'        => array(
				'Normal',
			),
			'Goblin One'               => array(
				'Normal',
			),
			'Gochi Hand'               => array(
				'Normal',
			),
			'Gorditas'                 => array(
				'Normal',
				'700',
			),
			'Goudy Bookletter 1911'    => array(
				'Normal',
			),
			'Graduate'                 => array(
				'Normal',
			),
			'Grand Hotel'              => array(
				'Normal',
			),
			'Gravitas One'             => array(
				'Normal',
			),
			'Great Vibes'              => array(
				'Normal',
			),
			'Griffy'                   => array(
				'Normal',
			),
			'Gruppo'                   => array(
				'Normal',
			),
			'Gudea'                    => array(
				'Normal',
				'700',
			),
			'Gurajada'                 => array(
				'Normal',
			),
			'Habibi'                   => array(
				'Normal',
			),
			'Halant'                   => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Hammersmith One'          => array(
				'Normal',
			),
			'Hanalei'                  => array(
				'Normal',
			),
			'Hanalei Fill'             => array(
				'Normal',
			),
			'Handlee'                  => array(
				'Normal',
			),
			'Hanuman'                  => array(
				'Normal',
				'700',
			),
			'Happy Monkey'             => array(
				'Normal',
			),
			'Headland One'             => array(
				'Normal',
			),
			'Henny Penny'              => array(
				'Normal',
			),
			'Heebo'                    => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
			),
			'Herr Von Muellerhoff'     => array(
				'Normal',
			),
			'Hind'                     => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Hind Siliguri'            => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Hind Vadodara'            => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Holtwood One SC'          => array(
				'Normal',
			),
			'Homemade Apple'           => array(
				'Normal',
			),
			'Homenaje'                 => array(
				'Normal',
			),
			'IM Fell DW Pica'          => array(
				'Normal',
			),
			'IM Fell DW Pica SC'       => array(
				'Normal',
			),
			'IM Fell Double Pica'      => array(
				'Normal',
			),
			'IM Fell Double Pica SC'   => array(
				'Normal',
			),
			'IM Fell English'          => array(
				'Normal',
			),
			'IM Fell English SC'       => array(
				'Normal',
			),
			'IM Fell French Canon'     => array(
				'Normal',
			),
			'IM Fell French Canon SC'  => array(
				'Normal',
			),
			'IM Fell Great Primer'     => array(
				'Normal',
			),
			'IM Fell Great Primer SC'  => array(
				'Normal',
			),
			'Iceberg'                  => array(
				'Normal',
			),
			'Iceland'                  => array(
				'Normal',
			),
			'Imprima'                  => array(
				'Normal',
			),
			'Inconsolata'              => array(
				'Normal',
				'700',
			),
			'Inder'                    => array(
				'Normal',
			),
			'Indie Flower'             => array(
				'Normal',
			),
			'Inika'                    => array(
				'Normal',
				'700',
			),
			'Inknut Antiqua'           => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Irish Grover'             => array(
				'Normal',
			),
			'Istok Web'                => array(
				'Normal',
				'700',
			),
			'Italiana'                 => array(
				'Normal',
			),
			'Italianno'                => array(
				'Normal',
			),
			'Itim'                     => array(
				'Normal',
			),
			'Jacques Francois'         => array(
				'Normal',
			),
			'Jacques Francois Shadow'  => array(
				'Normal',
			),
			'Jaldi'                    => array(
				'Normal',
				'700',
			),
			'Jim Nightshade'           => array(
				'Normal',
			),
			'Jockey One'               => array(
				'Normal',
			),
			'Jolly Lodger'             => array(
				'Normal',
			),
			'Josefin Sans'             => array(
				'Normal',
				'100',
				'300',
				'600',
				'700',
			),
			'Josefin Slab'             => array(
				'Normal',
				'100',
				'300',
				'600',
				'700',
			),
			'Joti One'                 => array(
				'Normal',
			),
			'Judson'                   => array(
				'Normal',
				'700',
			),
			'Julee'                    => array(
				'Normal',
			),
			'Julius Sans One'          => array(
				'Normal',
			),
			'Junge'                    => array(
				'Normal',
			),
			'Jura'                     => array(
				'Normal',
				'300',
				'500',
				'600',
			),
			'Just Another Hand'        => array(
				'Normal',
			),
			'Just Me Again Down Here'  => array(
				'Normal',
			),
			'Kadwa'                    => array(
				'Normal',
				'700',
			),
			'Kalam'                    => array(
				'Normal',
				'300',
				'700',
			),
			'Kameron'                  => array(
				'Normal',
				'700',
			),
			'Kanit'                    => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Kantumruy'                => array(
				'Normal',
				'300',
				'700',
			),
			'Karla'                    => array(
				'Normal',
				'700',
			),
			'Karma'                    => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Kaushan Script'           => array(
				'Normal',
			),
			'Kavoon'                   => array(
				'Normal',
			),
			'Kdam Thmor'               => array(
				'Normal',
			),
			'Keania One'               => array(
				'Normal',
			),
			'Kelly Slab'               => array(
				'Normal',
			),
			'Kenia'                    => array(
				'Normal',
			),
			'Khand'                    => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Khmer'                    => array(
				'Normal',
			),
			'Khula'                    => array(
				'Normal',
				'300',
				'600',
				'700',
				'800',
			),
			'Kite One'                 => array(
				'Normal',
			),
			'Knewave'                  => array(
				'Normal',
			),
			'Kotta One'                => array(
				'Normal',
			),
			'Koulen'                   => array(
				'Normal',
			),
			'Kranky'                   => array(
				'Normal',
			),
			'Kreon'                    => array(
				'Normal',
				'300',
				'700',
			),
			'Kristi'                   => array(
				'Normal',
			),
			'Krona One'                => array(
				'Normal',
			),
			'Kurale'                   => array(
				'Normal',
			),
			'La Belle Aurore'          => array(
				'Normal',
			),
			'Laila'                    => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Lakki Reddy'              => array(
				'Normal',
			),
			'Lancelot'                 => array(
				'Normal',
			),
			'Lateef'                   => array(
				'Normal',
			),
			'Lato'                     => array(
				'Normal',
				'100',
				'300',
				'700',
				'900',
			),
			'League Script'            => array(
				'Normal',
			),
			'Leckerli One'             => array(
				'Normal',
			),
			'Ledger'                   => array(
				'Normal',
			),
			'Lekton'                   => array(
				'Normal',
				'700',
			),
			'Lemon'                    => array(
				'Normal',
			),
			'Libre Baskerville'        => array(
				'Normal',
				'700',
			),
			'Life Savers'              => array(
				'Normal',
				'700',
			),
			'Lilita One'               => array(
				'Normal',
			),
			'Lily Script One'          => array(
				'Normal',
			),
			'Limelight'                => array(
				'Normal',
			),
			'Linden Hill'              => array(
				'Normal',
			),
			'Lobster'                  => array(
				'Normal',
			),
			'Lobster Two'              => array(
				'Normal',
				'700',
			),
			'Londrina Outline'         => array(
				'Normal',
			),
			'Londrina Shadow'          => array(
				'Normal',
			),
			'Londrina Sketch'          => array(
				'Normal',
			),
			'Londrina Solid'           => array(
				'Normal',
			),
			'Lora'                     => array(
				'Normal',
				'700',
			),
			'Love Ya Like A Sister'    => array(
				'Normal',
			),
			'Loved by the King'        => array(
				'Normal',
			),
			'Lovers Quarrel'           => array(
				'Normal',
			),
			'Luckiest Guy'             => array(
				'Normal',
			),
			'Lusitana'                 => array(
				'Normal',
				'700',
			),
			'Lustria'                  => array(
				'Normal',
			),
			'Macondo'                  => array(
				'Normal',
			),
			'Macondo Swash Caps'       => array(
				'Normal',
			),
			'Magra'                    => array(
				'Normal',
				'700',
			),
			'Maiden Orange'            => array(
				'Normal',
			),
			'Mako'                     => array(
				'Normal',
			),
			'Mallanna'                 => array(
				'Normal',
			),
			'Mandali'                  => array(
				'Normal',
			),
			'Marcellus'                => array(
				'Normal',
			),
			'Marcellus SC'             => array(
				'Normal',
			),
			'Marck Script'             => array(
				'Normal',
			),
			'Margarine'                => array(
				'Normal',
			),
			'Marko One'                => array(
				'Normal',
			),
			'Marmelad'                 => array(
				'Normal',
			),
			'Martel'                   => array(
				'Normal',
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
			),
			'Martel Sans'              => array(
				'Normal',
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
			),
			'Marvel'                   => array(
				'Normal',
				'700',
			),
			'Mate'                     => array(
				'Normal',
			),
			'Mate SC'                  => array(
				'Normal',
			),
			'Maven Pro'                => array(
				'Normal',
				'500',
				'700',
				'900',
			),
			'McLaren'                  => array(
				'Normal',
			),
			'Meddon'                   => array(
				'Normal',
			),
			'MedievalSharp'            => array(
				'Normal',
			),
			'Medula One'               => array(
				'Normal',
			),
			'Megrim'                   => array(
				'Normal',
			),
			'Meie Script'              => array(
				'Normal',
			),
			'Merienda'                 => array(
				'Normal',
				'700',
			),
			'Merienda One'             => array(
				'Normal',
			),
			'Merriweather'             => array(
				'Normal',
				'300',
				'700',
				'900',
			),
			'Merriweather Sans'        => array(
				'Normal',
				'300',
				'700',
				'800',
			),
			'Metal'                    => array(
				'Normal',
			),
			'Metal Mania'              => array(
				'Normal',
			),
			'Metamorphous'             => array(
				'Normal',
			),
			'Metrophobic'              => array(
				'Normal',
			),
			'Michroma'                 => array(
				'Normal',
			),
			'Milonga'                  => array(
				'Normal',
			),
			'Miltonian'                => array(
				'Normal',
			),
			'Miltonian Tattoo'         => array(
				'Normal',
			),
			'Miniver'                  => array(
				'Normal',
			),
			'Miss Fajardose'           => array(
				'Normal',
			),
			'Modak'                    => array(
				'Normal',
			),
			'Modern Antiqua'           => array(
				'Normal',
			),
			'Molengo'                  => array(
				'Normal',
			),
			'Molle'                    => array(),
			'Monda'                    => array(
				'Normal',
				'700',
			),
			'Monofett'                 => array(
				'Normal',
			),
			'Monoton'                  => array(
				'Normal',
			),
			'Monsieur La Doulaise'     => array(
				'Normal',
			),
			'Montaga'                  => array(
				'Normal',
			),
			'Montez'                   => array(
				'Normal',
			),
			'Montserrat'               => array(
				'Normal',
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Montserrat Alternates'    => array(
				'Normal',
				'700',
			),
			'Montserrat Subrayada'     => array(
				'Normal',
				'700',
			),
			'Moul'                     => array(
				'Normal',
			),
			'Moulpali'                 => array(
				'Normal',
			),
			'Mountains of Christmas'   => array(
				'Normal',
				'700',
			),
			'Mouse Memoirs'            => array(
				'Normal',
			),
			'Mr Bedfort'               => array(
				'Normal',
			),
			'Mr Dafoe'                 => array(
				'Normal',
			),
			'Mr De Haviland'           => array(
				'Normal',
			),
			'Mrs Saint Delafield'      => array(
				'Normal',
			),
			'Mrs Sheppards'            => array(
				'Normal',
			),
			'Muli'                     => array(
				'Normal',
				'300',
			),
			'Mystery Quest'            => array(
				'Normal',
			),
			'NTR'                      => array(
				'Normal',
			),
			'Neucha'                   => array(
				'Normal',
			),
			'Neuton'                   => array(
				'Normal',
				'200',
				'300',
				'700',
				'800',
			),
			'New Rocker'               => array(
				'Normal',
			),
			'News Cycle'               => array(
				'Normal',
				'700',
			),
			'Niconne'                  => array(
				'Normal',
			),
			'Nixie One'                => array(
				'Normal',
			),
			'Nobile'                   => array(
				'Normal',
				'700',
			),
			'Nokora'                   => array(
				'Normal',
				'700',
			),
			'Norican'                  => array(
				'Normal',
			),
			'Nosifer'                  => array(
				'Normal',
			),
			'Nothing You Could Do'     => array(
				'Normal',
			),
			'Noticia Text'             => array(
				'Normal',
				'700',
			),
			'Noto Sans'                => array(
				'Normal',
				'700',
			),
			'Noto Serif'               => array(
				'Normal',
				'700',
			),
			'Nova Cut'                 => array(
				'Normal',
			),
			'Nova Flat'                => array(
				'Normal',
			),
			'Nova Mono'                => array(
				'Normal',
			),
			'Nova Oval'                => array(
				'Normal',
			),
			'Nova Round'               => array(
				'Normal',
			),
			'Nova Script'              => array(
				'Normal',
			),
			'Nova Slim'                => array(
				'Normal',
			),
			'Nova Square'              => array(
				'Normal',
			),
			'Numans'                   => array(
				'Normal',
			),
			'Nunito'                   => array(
				'Normal',
				'300',
				'700',
			),
			'Odor Mean Chey'           => array(
				'Normal',
			),
			'Offside'                  => array(
				'Normal',
			),
			'Old Standard TT'          => array(
				'Normal',
				'700',
			),
			'Oldenburg'                => array(
				'Normal',
			),
			'Oleo Script'              => array(
				'Normal',
				'700',
			),
			'Oleo Script Swash Caps'   => array(
				'Normal',
				'700',
			),
			'Open Sans'                => array(
				'Normal',
				'300',
				'600',
				'700',
				'800',
			),
			'Open Sans Condensed'      => array(
				'300',
				'700',
			),
			'Oranienbaum'              => array(
				'Normal',
			),
			'Orbitron'                 => array(
				'Normal',
				'500',
				'700',
				'900',
			),
			'Oregano'                  => array(
				'Normal',
			),
			'Orienta'                  => array(
				'Normal',
			),
			'Original Surfer'          => array(
				'Normal',
			),
			'Oswald'                   => array(
				'300',
				'Normal',
				'700',
			),
			'Over the Rainbow'         => array(
				'Normal',
			),
			'Overlock'                 => array(
				'Normal',
				'700',
				'900',
			),
			'Overlock SC'              => array(
				'Normal',
			),
			'Ovo'                      => array(
				'Normal',
			),
			'Oxygen'                   => array(
				'300',
				'Normal',
				'700',
			),
			'Oxygen Mono'              => array(
				'Normal',
			),
			'PT Mono'                  => array(
				'Normal',
			),
			'PT Sans'                  => array(
				'Normal',
				'700',
			),
			'PT Sans Caption'          => array(
				'Normal',
				'700',
			),
			'PT Sans Narrow'           => array(
				'Normal',
				'700',
			),
			'PT Serif'                 => array(
				'Normal',
				'700',
			),
			'PT Serif Caption'         => array(
				'Normal',
			),
			'Pacifico'                 => array(
				'Normal',
			),
			'Palanquin'                => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
			),
			'Palanquin Dark'           => array(
				'Normal',
				'500',
				'600',
				'700',
			),
			'Paprika'                  => array(
				'Normal',
			),
			'Parisienne'               => array(
				'Normal',
			),
			'Passero One'              => array(
				'Normal',
			),
			'Passion One'              => array(
				'Normal',
				'700',
				'900',
			),
			'Pathway Gothic One'       => array(
				'Normal',
			),
			'Patrick Hand'             => array(
				'Normal',
			),
			'Patrick Hand SC'          => array(
				'Normal',
			),
			'Patua One'                => array(
				'Normal',
			),
			'Paytone One'              => array(
				'Normal',
			),
			'Peddana'                  => array(
				'Normal',
			),
			'Peralta'                  => array(
				'Normal',
			),
			'Permanent Marker'         => array(
				'Normal',
			),
			'Petit Formal Script'      => array(
				'Normal',
			),
			'Petrona'                  => array(
				'Normal',
			),
			'Philosopher'              => array(
				'Normal',
				'700',
			),
			'Piedra'                   => array(
				'Normal',
			),
			'Pinyon Script'            => array(
				'Normal',
			),
			'Pirata One'               => array(
				'Normal',
			),
			'Plaster'                  => array(
				'Normal',
			),
			'Play'                     => array(
				'Normal',
				'700',
			),
			'Playball'                 => array(
				'Normal',
			),
			'Playfair Display'         => array(
				'Normal',
				'700',
				'900',
			),
			'Playfair Display SC'      => array(
				'Normal',
				'700',
				'900',
			),
			'Podkova'                  => array(
				'Normal',
				'700',
			),
			'Poiret One'               => array(
				'Normal',
			),
			'Poller One'               => array(
				'Normal',
			),
			'Poly'                     => array(
				'Normal',
			),
			'Pompiere'                 => array(
				'Normal',
			),
			'Pontano Sans'             => array(
				'Normal',
			),
			'Poppins'                  => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Port Lligat Sans'         => array(
				'Normal',
			),
			'Port Lligat Slab'         => array(
				'Normal',
			),
			'Pragati Narrow'           => array(
				'Normal',
				'700',
			),
			'Prata'                    => array(
				'Normal',
			),
			'Preahvihear'              => array(
				'Normal',
			),
			'Press Start 2P'           => array(
				'Normal',
			),
			'Princess Sofia'           => array(
				'Normal',
			),
			'Prociono'                 => array(
				'Normal',
			),
			'Prosto One'               => array(
				'Normal',
			),
			'Puritan'                  => array(
				'Normal',
				'700',
			),
			'Purple Purse'             => array(
				'Normal',
			),
			'Quando'                   => array(
				'Normal',
			),
			'Quantico'                 => array(
				'Normal',
				'700',
			),
			'Quattrocento'             => array(
				'Normal',
				'700',
			),
			'Quattrocento Sans'        => array(
				'Normal',
				'700',
			),
			'Questrial'                => array(
				'Normal',
			),
			'Quicksand'                => array(
				'Normal',
				'300',
				'700',
			),
			'Quintessential'           => array(
				'Normal',
			),
			'Qwigley'                  => array(
				'Normal',
			),
			'Racing Sans One'          => array(
				'Normal',
			),
			'Radley'                   => array(
				'Normal',
			),
			'Rajdhani'                 => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Raleway'                  => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Raleway Dots'             => array(
				'Normal',
			),
			'Ramabhadra'               => array(
				'Normal',
			),
			'Ramaraja'                 => array(
				'Normal',
			),
			'Rambla'                   => array(
				'Normal',
				'700',
			),
			'Rammetto One'             => array(
				'Normal',
			),
			'Ranchers'                 => array(
				'Normal',
			),
			'Rancho'                   => array(
				'Normal',
			),
			'Ranga'                    => array(
				'Normal',
				'700',
			),
			'Rationale'                => array(
				'Normal',
			),
			'Ravi Prakash'             => array(
				'Normal',
			),
			'Redressed'                => array(
				'Normal',
			),
			'Reenie Beanie'            => array(
				'Normal',
			),
			'Revalia'                  => array(
				'Normal',
			),
			'Rhodium Libre'            => array(
				'Normal',
			),
			'Ribeye'                   => array(
				'Normal',
			),
			'Ribeye Marrow'            => array(
				'Normal',
			),
			'Righteous'                => array(
				'Normal',
			),
			'Risque'                   => array(
				'Normal',
			),
			'Roboto'                   => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
				'900',
			),
			'Roboto Condensed'         => array(
				'Normal',
				'300',
				'700',
			),
			'Roboto Mono'              => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
			),
			'Roboto Slab'              => array(
				'Normal',
				'100',
				'300',
				'700',
			),
			'Rochester'                => array(
				'Normal',
			),
			'Rock Salt'                => array(
				'Normal',
			),
			'Rokkitt'                  => array(
				'Normal',
				'700',
			),
			'Romanesco'                => array(
				'Normal',
			),
			'Ropa Sans'                => array(
				'Normal',
			),
			'Rosario'                  => array(
				'Normal',
				'700',
			),
			'Rosarivo'                 => array(
				'Normal',
			),
			'Rouge Script'             => array(
				'Normal',
			),
			'Rozha One'                => array(
				'Normal',
			),
			'Rubik'                    => array(
				'Normal',
				'300',
				'500',
				'700',
				'900',
			),
			'Rubik Mono One'           => array(
				'Normal',
			),
			'Rubik One'                => array(
				'Normal',
			),
			'Ruda'                     => array(
				'Normal',
				'700',
				'900',
			),
			'Rufina'                   => array(
				'Normal',
				'700',
			),
			'Ruge Boogie'              => array(
				'Normal',
			),
			'Ruluko'                   => array(
				'Normal',
			),
			'Rum Raisin'               => array(
				'Normal',
			),
			'Ruslan Display'           => array(
				'Normal',
			),
			'Russo One'                => array(
				'Normal',
			),
			'Ruthie'                   => array(
				'Normal',
			),
			'Rye'                      => array(
				'Normal',
			),
			'Sacramento'               => array(
				'Normal',
			),
			'Sahitya'                  => array(
				'Normal',
				'700',
			),
			'Sail'                     => array(
				'Normal',
			),
			'Salsa'                    => array(
				'Normal',
			),
			'Sanchez'                  => array(
				'Normal',
			),
			'Sancreek'                 => array(
				'Normal',
			),
			'Sansita One'              => array(
				'Normal',
			),
			'Sarala'                   => array(
				'Normal',
				'700',
			),
			'Sarina'                   => array(
				'Normal',
			),
			'Sarpanch'                 => array(
				'Normal',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Satisfy'                  => array(
				'Normal',
			),
			'Scada'                    => array(
				'Normal',
				'700',
			),
			'Scheherazade'             => array(
				'Normal',
				'700',
			),
			'Schoolbell'               => array(
				'Normal',
			),
			'Seaweed Script'           => array(
				'Normal',
			),
			'Sevillana'                => array(
				'Normal',
			),
			'Seymour One'              => array(
				'Normal',
			),
			'Shadows Into Light'       => array(
				'Normal',
			),
			'Shadows Into Light Two'   => array(
				'Normal',
			),
			'Shanti'                   => array(
				'Normal',
			),
			'Share'                    => array(
				'Normal',
				'700',
			),
			'Share Tech'               => array(
				'Normal',
			),
			'Share Tech Mono'          => array(
				'Normal',
			),
			'Shojumaru'                => array(
				'Normal',
			),
			'Short Stack'              => array(
				'Normal',
			),
			'Siemreap'                 => array(
				'Normal',
			),
			'Sigmar One'               => array(
				'Normal',
			),
			'Signika'                  => array(
				'Normal',
				'300',
				'600',
				'700',
			),
			'Signika Negative'         => array(
				'Normal',
				'300',
				'600',
				'700',
			),
			'Simonetta'                => array(
				'Normal',
				'900',
			),
			'Sintony'                  => array(
				'Normal',
				'700',
			),
			'Sirin Stencil'            => array(
				'Normal',
			),
			'Six Caps'                 => array(
				'Normal',
			),
			'Skranji'                  => array(
				'Normal',
				'700',
			),
			'Slabo 13px'               => array(
				'Normal',
			),
			'Slabo 27px'               => array(
				'Normal',
			),
			'Slackey'                  => array(
				'Normal',
			),
			'Smokum'                   => array(
				'Normal',
			),
			'Smythe'                   => array(
				'Normal',
			),
			'Sniglet'                  => array(
				'Normal',
				'800',
			),
			'Snippet'                  => array(
				'Normal',
			),
			'Snowburst One'            => array(
				'Normal',
			),
			'Sofadi One'               => array(
				'Normal',
			),
			'Sofia'                    => array(
				'Normal',
			),
			'Sonsie One'               => array(
				'Normal',
			),
			'Sorts Mill Goudy'         => array(
				'Normal',
			),
			'Source Code Pro'          => array(
				'Normal',
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
			),
			'Source Sans Pro'          => array(
				'Normal',
				'200',
				'300',
				'600',
				'700',
				'900',
			),
			'Source Serif Pro'         => array(
				'Normal',
				'600',
				'700',
			),
			'Special Elite'            => array(
				'Normal',
			),
			'Spicy Rice'               => array(
				'Normal',
			),
			'Spinnaker'                => array(
				'Normal',
			),
			'Spirax'                   => array(
				'Normal',
			),
			'Squada One'               => array(
				'Normal',
			),
			'Sree Krushnadevaraya'     => array(
				'Normal',
			),
			'Stalemate'                => array(
				'Normal',
			),
			'Stalinist One'            => array(
				'Normal',
			),
			'Stardos Stencil'          => array(
				'Normal',
				'700',
			),
			'Stint Ultra Condensed'    => array(
				'Normal',
			),
			'Stint Ultra Expanded'     => array(
				'Normal',
			),
			'Stoke'                    => array(
				'Normal',
				'300',
			),
			'Strait'                   => array(
				'Normal',
			),
			'Sue Ellen Francisco'      => array(
				'Normal',
			),
			'Sumana'                   => array(
				'Normal',
				'700',
			),
			'Sunshiney'                => array(
				'Normal',
			),
			'Supermercado One'         => array(
				'Normal',
			),
			'Sura'                     => array(
				'Normal',
				'700',
			),
			'Suranna'                  => array(
				'Normal',
			),
			'Suravaram'                => array(
				'Normal',
			),
			'Suwannaphum'              => array(
				'Normal',
			),
			'Swanky and Moo Moo'       => array(
				'Normal',
			),
			'Syncopate'                => array(
				'Normal',
				'700',
			),
			'Tangerine'                => array(
				'Normal',
				'700',
			),
			'Taprom'                   => array(
				'Normal',
			),
			'Tauri'                    => array(
				'Normal',
			),
			'Teko'                     => array(
				'Normal',
				'300',
				'500',
				'600',
				'700',
			),
			'Telex'                    => array(
				'Normal',
			),
			'Tenali Ramakrishna'       => array(
				'Normal',
			),
			'Tenor Sans'               => array(
				'Normal',
			),
			'Text Me One'              => array(
				'Normal',
			),
			'The Girl Next Door'       => array(
				'Normal',
			),
			'Tienne'                   => array(
				'Normal',
				'700',
				'900',
			),
			'Tillana'                  => array(
				'Normal',
				'500',
				'600',
				'700',
				'800',
			),
			'Timmana'                  => array(
				'Normal',
			),
			'Tinos'                    => array(
				'Normal',
				'700',
			),
			'Titan One'                => array(
				'Normal',
			),
			'Titillium Web'            => array(
				'Normal',
				'200',
				'300',
				'600',
				'700',
				'900',
			),
			'Trade Winds'              => array(
				'Normal',
			),
			'Trocchi'                  => array(
				'Normal',
			),
			'Trochut'                  => array(
				'Normal',
				'700',
			),
			'Trykker'                  => array(
				'Normal',
			),
			'Tulpen One'               => array(
				'Normal',
			),
			'Ubuntu'                   => array(
				'Normal',
				'300',
				'500',
				'700',
			),
			'Ubuntu Condensed'         => array(
				'Normal',
			),
			'Ubuntu Mono'              => array(
				'Normal',
				'700',
			),
			'Ultra'                    => array(
				'Normal',
			),
			'Uncial Antiqua'           => array(
				'Normal',
			),
			'Underdog'                 => array(
				'Normal',
			),
			'Unica One'                => array(
				'Normal',
			),
			'UnifrakturCook'           => array(
				'700',
			),
			'UnifrakturMaguntia'       => array(
				'Normal',
			),
			'Unkempt'                  => array(
				'Normal',
				'700',
			),
			'Unlock'                   => array(
				'Normal',
			),
			'Unna'                     => array(
				'Normal',
			),
			'VT323'                    => array(
				'Normal',
			),
			'Vampiro One'              => array(
				'Normal',
			),
			'Varela'                   => array(
				'Normal',
			),
			'Varela Round'             => array(
				'Normal',
			),
			'Vast Shadow'              => array(
				'Normal',
			),
			'Vesper Libre'             => array(
				'Normal',
				'500',
				'700',
				'900',
			),
			'Vibur'                    => array(
				'Normal',
			),
			'Vidaloka'                 => array(
				'Normal',
			),
			'Viga'                     => array(
				'Normal',
			),
			'Voces'                    => array(
				'Normal',
			),
			'Volkhov'                  => array(
				'Normal',
				'700',
			),
			'Vollkorn'                 => array(
				'Normal',
				'700',
			),
			'Voltaire'                 => array(
				'Normal',
			),
			'Waiting for the Sunrise'  => array(
				'Normal',
			),
			'Wallpoet'                 => array(
				'Normal',
			),
			'Walter Turncoat'          => array(
				'Normal',
			),
			'Warnes'                   => array(
				'Normal',
			),
			'Wellfleet'                => array(
				'Normal',
			),
			'Wendy One'                => array(
				'Normal',
			),
			'Wire One'                 => array(
				'Normal',
			),
			'Work Sans'                => array(
				'Normal',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Yanone Kaffeesatz'        => array(
				'Normal',
				'200',
				'300',
				'700',
			),
			'Yantramanav'              => array(
				'Normal',
				'100',
				'300',
				'500',
				'700',
				'900',
			),
			'Yellowtail'               => array(
				'Normal',
			),
			'Yeseva One'               => array(
				'Normal',
			),
			'Yesteryear'               => array(
				'Normal',
			),
			'Zeyada'                   => array(
				'Normal',
			),
		);

		/**
		 * Get font list for dropdown
		 *
		 * @since  0.0.1
		 * @param string $family font Family name.
		 * @return boolean
		 */
		static public function is_google_font( $family ) {

			if ( isset( self::$google[ $family ] ) ) {
				return true;
			}

			return false;
		}

	}
}
