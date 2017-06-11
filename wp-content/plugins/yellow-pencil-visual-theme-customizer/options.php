<?php
/**
 * CSS properties list.
 *
 * @author 		WaspThemes
 * @category 	Options
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

	
/* ---------------------------------------------------- */
/* All CSS Options and settings							*/
/* ---------------------------------------------------- */
echo "<ul class='yp-editor-list'>
		
		<li class='yp-li-about active'>
			<h3><small>".__('You are customizing','yp')."</small> <div>".yp_customizer_name()."</div></h3>
		</li>
		
		<li class='text-option'>
			<h3>".__('Text','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				".yp_get_select_markup(
					'font-family',
					__('Font Family','yp')
					,array(
					
						// Safe Fonts.
						"Georgia, serif" => "Georgia",
						"'Helvetica Neue',Helvetica,Arial,sans-serif" => "Helvetica Neue",
						"'Times New Roman', Times, serif" => "Times New Roman",
						"Arial, Helvetica, sans-serif" => "Arial",
						"'Arial Black', Gadget, sans-serif" => "Arial Black",
						"Impact, Charcoal, sans-serif" => "Impact",
						"Tahoma, Geneva, sans-serif" => "Tahoma",
						"Verdana, Geneva, sans-serif" => "Verdana",
						
						// Google fonts.
						"'ABeeZee', sans-serif" => "ABeeZee",
						"'Abel', sans-serif" => "Abel",
						"'Abhaya Libre', serif" => "Abhaya Libre",
						"'Abril Fatface', cursive" => "Abril Fatface",
						"'Aclonica', sans-serif" => "Aclonica",
						"'Acme', sans-serif" => "Acme",
						"'Actor', sans-serif" => "Actor",
						"'Adamina', serif" => "Adamina",
						"'Advent Pro', sans-serif" => "Advent Pro",
						"'Aguafina Script', cursive" => "Aguafina Script",
						"'Akronim', cursive" => "Akronim",
						"'Aladin', cursive" => "Aladin",
						"'Aldrich', sans-serif" => "Aldrich",
						"'Alef', sans-serif" => "Alef",
						"'Alegreya', serif" => "Alegreya",
						"'Alegreya SC', serif" => "Alegreya SC",
						"'Alegreya Sans', sans-serif" => "Alegreya Sans",
						"'Alegreya Sans SC', sans-serif" => "Alegreya Sans SC",
						"'Alex Brush', cursive" => "Alex Brush",
						"'Alfa Slab One', cursive" => "Alfa Slab One",
						"'Alice', serif" => "Alice",
						"'Alike', serif" => "Alike",
						"'Alike Angular', serif" => "Alike Angular",
						"'Allan', cursive" => "Allan",
						"'Allerta', sans-serif" => "Allerta",
						"'Allerta Stencil', sans-serif" => "Allerta Stencil",
						"'Allura', cursive" => "Allura",
						"'Almendra', serif" => "Almendra",
						"'Almendra Display', cursive" => "Almendra Display",
						"'Almendra SC', serif" => "Almendra SC",
						"'Amarante', cursive" => "Amarante",
						"'Amaranth', sans-serif" => "Amaranth",
						"'Amatic SC', cursive" => "Amatic SC",
						"'Amatica SC', cursive" => "Amatica SC",
						"'Amethysta', serif" => "Amethysta",
						"'Amiko', sans-serif" => "Amiko",
						"'Amiri', serif" => "Amiri",
						"'Amita', cursive" => "Amita",
						"'Anaheim', sans-serif" => "Anaheim",
						"'Andada', serif" => "Andada",
						"'Andika', sans-serif" => "Andika",
						"'Angkor', cursive" => "Angkor",
						"'Annie Use Your Telescope', cursive" => "Annie Use Your Telescope",
						"'Anonymous Pro', monospace" => "Anonymous Pro",
						"'Antic', sans-serif" => "Antic",
						"'Antic Didone', serif" => "Antic Didone",
						"'Antic Slab', serif" => "Antic Slab",
						"'Anton', sans-serif" => "Anton",
						"'Arapey', serif" => "Arapey",
						"'Arbutus', cursive" => "Arbutus",
						"'Arbutus Slab', serif" => "Arbutus Slab",
						"'Architects Daughter', cursive" => "Architects Daughter",
						"'Archivo Black', sans-serif" => "Archivo Black",
						"'Archivo Narrow', sans-serif" => "Archivo Narrow",
						"'Aref Ruqaa', serif" => "Aref Ruqaa",
						"'Arima Madurai', cursive" => "Arima Madurai",
						"'Arimo', sans-serif" => "Arimo",
						"'Arizonia', cursive" => "Arizonia",
						"'Armata', sans-serif" => "Armata",
						"'Arsenal', sans-serif" => "Arsenal",
						"'Artifika', serif" => "Artifika",
						"'Arvo', serif" => "Arvo",
						"'Arya', sans-serif" => "Arya",
						"'Asap', sans-serif" => "Asap",
						"'Asar', serif" => "Asar",
						"'Asset', cursive" => "Asset",
						"'Assistant', sans-serif" => "Assistant",
						"'Astloch', cursive" => "Astloch",
						"'Asul', sans-serif" => "Asul",
						"'Athiti', sans-serif" => "Athiti",
						"'Atma', cursive" => "Atma",
						"'Atomic Age', cursive" => "Atomic Age",
						"'Aubrey', cursive" => "Aubrey",
						"'Audiowide', cursive" => "Audiowide",
						"'Autour One', cursive" => "Autour One",
						"'Average', serif" => "Average",
						"'Average Sans', sans-serif" => "Average Sans",
						"'Averia Gruesa Libre', cursive" => "Averia Gruesa Libre",
						"'Averia Libre', cursive" => "Averia Libre",
						"'Averia Sans Libre', cursive" => "Averia Sans Libre",
						"'Averia Serif Libre', cursive" => "Averia Serif Libre",
						"'Bad Script', cursive" => "Bad Script",
						"'Bahiana', cursive" => "Bahiana",
						"'Baloo', cursive" => "Baloo",
						"'Baloo Bhai', cursive" => "Baloo Bhai",
						"'Baloo Bhaina', cursive" => "Baloo Bhaina",
						"'Baloo Chettan', cursive" => "Baloo Chettan",
						"'Baloo Da', cursive" => "Baloo Da",
						"'Baloo Paaji', cursive" => "Baloo Paaji",
						"'Baloo Tamma', cursive" => "Baloo Tamma",
						"'Baloo Thambi', cursive" => "Baloo Thambi",
						"'Balthazar', serif" => "Balthazar",
						"'Bangers', cursive" => "Bangers",
						"'Barrio', cursive" => "Barrio",
						"'Basic', sans-serif" => "Basic",
						"'Battambang', cursive" => "Battambang",
						"'Baumans', cursive" => "Baumans",
						"'Bayon', cursive" => "Bayon",
						"'Belgrano', serif" => "Belgrano",
						"'Belleza', sans-serif" => "Belleza",
						"'BenchNine', sans-serif" => "BenchNine",
						"'Bentham', serif" => "Bentham",
						"'Berkshire Swash', cursive" => "Berkshire Swash",
						"'Bevan', cursive" => "Bevan",
						"'Bigelow Rules', cursive" => "Bigelow Rules",
						"'Bigshot One', cursive" => "Bigshot One",
						"'Bilbo', cursive" => "Bilbo",
						"'Bilbo Swash Caps', cursive" => "Bilbo Swash Caps",
						"'BioRhyme', serif" => "BioRhyme",
						"'BioRhyme Expanded', serif" => "BioRhyme Expanded",
						"'Biryani', sans-serif" => "Biryani",
						"'Bitter', serif" => "Bitter",
						"'Black Ops One', cursive" => "Black Ops One",
						"'Bokor', cursive" => "Bokor",
						"'Bonbon', cursive" => "Bonbon",
						"'Boogaloo', cursive" => "Boogaloo",
						"'Bowlby One', cursive" => "Bowlby One",
						"'Bowlby One SC', cursive" => "Bowlby One SC",
						"'Brawler', serif" => "Brawler",
						"'Bree Serif', serif" => "Bree Serif",
						"'Bubblegum Sans', cursive" => "Bubblegum Sans",
						"'Bubbler One', sans-serif" => "Bubbler One",
						"'Buda', cursive" => "Buda",
						"'Buenard', serif" => "Buenard",
						"'Bungee', cursive" => "Bungee",
						"'Bungee Hairline', cursive" => "Bungee Hairline",
						"'Bungee Inline', cursive" => "Bungee Inline",
						"'Bungee Outline', cursive" => "Bungee Outline",
						"'Bungee Shade', cursive" => "Bungee Shade",
						"'Butcherman', cursive" => "Butcherman",
						"'Butterfly Kids', cursive" => "Butterfly Kids",
						"'Cabin', sans-serif" => "Cabin",
						"'Cabin Condensed', sans-serif" => "Cabin Condensed",
						"'Cabin Sketch', cursive" => "Cabin Sketch",
						"'Caesar Dressing', cursive" => "Caesar Dressing",
						"'Cagliostro', sans-serif" => "Cagliostro",
						"'Cairo', sans-serif" => "Cairo",
						"'Calligraffitti', cursive" => "Calligraffitti",
						"'Cambay', sans-serif" => "Cambay",
						"'Cambo', serif" => "Cambo",
						"'Candal', sans-serif" => "Candal",
						"'Cantarell', sans-serif" => "Cantarell",
						"'Cantata One', serif" => "Cantata One",
						"'Cantora One', sans-serif" => "Cantora One",
						"'Capriola', sans-serif" => "Capriola",
						"'Cardo', serif" => "Cardo",
						"'Carme', sans-serif" => "Carme",
						"'Carrois Gothic', sans-serif" => "Carrois Gothic",
						"'Carrois Gothic SC', sans-serif" => "Carrois Gothic SC",
						"'Carter One', cursive" => "Carter One",
						"'Catamaran', sans-serif" => "Catamaran",
						"'Caudex', serif" => "Caudex",
						"'Caveat', cursive" => "Caveat",
						"'Caveat Brush', cursive" => "Caveat Brush",
						"'Cedarville Cursive', cursive" => "Cedarville Cursive",
						"'Ceviche One', cursive" => "Ceviche One",
						"'Changa', sans-serif" => "Changa",
						"'Changa One', cursive" => "Changa One",
						"'Chango', cursive" => "Chango",
						"'Chathura', sans-serif" => "Chathura",
						"'Chau Philomene One', sans-serif" => "Chau Philomene One",
						"'Chela One', cursive" => "Chela One",
						"'Chelsea Market', cursive" => "Chelsea Market",
						"'Chenla', cursive" => "Chenla",
						"'Cherry Cream Soda', cursive" => "Cherry Cream Soda",
						"'Cherry Swash', cursive" => "Cherry Swash",
						"'Chewy', cursive" => "Chewy",
						"'Chicle', cursive" => "Chicle",
						"'Chivo', sans-serif" => "Chivo",
						"'Chonburi', cursive" => "Chonburi",
						"'Cinzel', serif" => "Cinzel",
						"'Cinzel Decorative', cursive" => "Cinzel Decorative",
						"'Clicker Script', cursive" => "Clicker Script",
						"'Coda', cursive" => "Coda",
						"'Coda Caption', sans-serif" => "Coda Caption",
						"'Codystar', cursive" => "Codystar",
						"'Coiny', cursive" => "Coiny",
						"'Combo', cursive" => "Combo",
						"'Comfortaa', cursive" => "Comfortaa",
						"'Coming Soon', cursive" => "Coming Soon",
						"'Concert One', cursive" => "Concert One",
						"'Condiment', cursive" => "Condiment",
						"'Content', cursive" => "Content",
						"'Contrail One', cursive" => "Contrail One",
						"'Convergence', sans-serif" => "Convergence",
						"'Cookie', cursive" => "Cookie",
						"'Copse', serif" => "Copse",
						"'Corben', cursive" => "Corben",
						"'Cormorant', serif" => "Cormorant",
						"'Cormorant Garamond', serif" => "Cormorant Garamond",
						"'Cormorant Infant', serif" => "Cormorant Infant",
						"'Cormorant SC', serif" => "Cormorant SC",
						"'Cormorant Unicase', serif" => "Cormorant Unicase",
						"'Cormorant Upright', serif" => "Cormorant Upright",
						"'Courgette', cursive" => "Courgette",
						"'Cousine', monospace" => "Cousine",
						"'Coustard', serif" => "Coustard",
						"'Covered By Your Grace', cursive" => "Covered By Your Grace",
						"'Crafty Girls', cursive" => "Crafty Girls",
						"'Creepster', cursive" => "Creepster",
						"'Crete Round', serif" => "Crete Round",
						"'Crimson Text', serif" => "Crimson Text",
						"'Croissant One', cursive" => "Croissant One",
						"'Crushed', cursive" => "Crushed",
						"'Cuprum', sans-serif" => "Cuprum",
						"'Cutive', serif" => "Cutive",
						"'Cutive Mono', monospace" => "Cutive Mono",
						"'Damion', cursive" => "Damion",
						"'Dancing Script', cursive" => "Dancing Script",
						"'Dangrek', cursive" => "Dangrek",
						"'David Libre', serif" => "David Libre",
						"'Dawning of a New Day', cursive" => "Dawning of a New Day",
						"'Days One', sans-serif" => "Days One",
						"'Dekko', cursive" => "Dekko",
						"'Delius', cursive" => "Delius",
						"'Delius Swash Caps', cursive" => "Delius Swash Caps",
						"'Delius Unicase', cursive" => "Delius Unicase",
						"'Della Respira', serif" => "Della Respira",
						"'Denk One', sans-serif" => "Denk One",
						"'Devonshire', cursive" => "Devonshire",
						"'Dhurjati', sans-serif" => "Dhurjati",
						"'Didact Gothic', sans-serif" => "Didact Gothic",
						"'Diplomata', cursive" => "Diplomata",
						"'Diplomata SC', cursive" => "Diplomata SC",
						"'Domine', serif" => "Domine",
						"'Donegal One', serif" => "Donegal One",
						"'Doppio One', sans-serif" => "Doppio One",
						"'Dorsa', sans-serif" => "Dorsa",
						"'Dosis', sans-serif" => "Dosis",
						"'Dr Sugiyama', cursive" => "Dr Sugiyama",
						"'Droid Sans', sans-serif" => "Droid Sans",
						"'Droid Sans Mono', monospace" => "Droid Sans Mono",
						"'Droid Serif', serif" => "Droid Serif",
						"'Duru Sans', sans-serif" => "Duru Sans",
						"'Dynalight', cursive" => "Dynalight",
						"'EB Garamond', serif" => "EB Garamond",
						"'Eagle Lake', cursive" => "Eagle Lake",
						"'Eater', cursive" => "Eater",
						"'Economica', sans-serif" => "Economica",
						"'Eczar', serif" => "Eczar",
						"'Ek Mukta', sans-serif" => "Ek Mukta",
						"'El Messiri', sans-serif" => "El Messiri",
						"'Electrolize', sans-serif" => "Electrolize",
						"'Elsie', cursive" => "Elsie",
						"'Elsie Swash Caps', cursive" => "Elsie Swash Caps",
						"'Emblema One', cursive" => "Emblema One",
						"'Emilys Candy', cursive" => "Emilys Candy",
						"'Engagement', cursive" => "Engagement",
						"'Englebert', sans-serif" => "Englebert",
						"'Enriqueta', serif" => "Enriqueta",
						"'Erica One', cursive" => "Erica One",
						"'Esteban', serif" => "Esteban",
						"'Euphoria Script', cursive" => "Euphoria Script",
						"'Ewert', cursive" => "Ewert",
						"'Exo', sans-serif" => "Exo",
						"'Exo 2', sans-serif" => "Exo 2",
						"'Expletus Sans', cursive" => "Expletus Sans",
						"'Fanwood Text', serif" => "Fanwood Text",
						"'Farsan', cursive" => "Farsan",
						"'Fascinate', cursive" => "Fascinate",
						"'Fascinate Inline', cursive" => "Fascinate Inline",
						"'Faster One', cursive" => "Faster One",
						"'Fasthand', serif" => "Fasthand",
						"'Fauna One', serif" => "Fauna One",
						"'Federant', cursive" => "Federant",
						"'Federo', sans-serif" => "Federo",
						"'Felipa', cursive" => "Felipa",
						"'Fenix', serif" => "Fenix",
						"'Finger Paint', cursive" => "Finger Paint",
						"'Fira Mono', monospace" => "Fira Mono",
						"'Fira Sans', sans-serif" => "Fira Sans",
						"'Fira Sans Condensed', sans-serif" => "Fira Sans Condensed",
						"'Fira Sans Extra Condensed', sans-serif" => "Fira Sans Extra Condensed",
						"'Fjalla One', sans-serif" => "Fjalla One",
						"'Fjord One', serif" => "Fjord One",
						"'Flamenco', cursive" => "Flamenco",
						"'Flavors', cursive" => "Flavors",
						"'Fondamento', cursive" => "Fondamento",
						"'Fontdiner Swanky', cursive" => "Fontdiner Swanky",
						"'Forum', cursive" => "Forum",
						"'Francois One', sans-serif" => "Francois One",
						"'Frank Ruhl Libre', sans-serif" => "Frank Ruhl Libre",
						"'Freckle Face', cursive" => "Freckle Face",
						"'Fredericka the Great', cursive" => "Fredericka the Great",
						"'Fredoka One', cursive" => "Fredoka One",
						"'Freehand', cursive" => "Freehand",
						"'Fresca', sans-serif" => "Fresca",
						"'Frijole', cursive" => "Frijole",
						"'Fruktur', cursive" => "Fruktur",
						"'Fugaz One', cursive" => "Fugaz One",
						"'GFS Didot', serif" => "GFS Didot",
						"'GFS Neohellenic', sans-serif" => "GFS Neohellenic",
						"'Gabriela', serif" => "Gabriela",
						"'Gafata', sans-serif" => "Gafata",
						"'Galada', cursive" => "Galada",
						"'Galdeano', sans-serif" => "Galdeano",
						"'Galindo', cursive" => "Galindo",
						"'Gentium Basic', serif" => "Gentium Basic",
						"'Gentium Book Basic', serif" => "Gentium Book Basic",
						"'Geo', sans-serif" => "Geo",
						"'Geostar', cursive" => "Geostar",
						"'Geostar Fill', cursive" => "Geostar Fill",
						"'Germania One', cursive" => "Germania One",
						"'Gidugu', sans-serif" => "Gidugu",
						"'Gilda Display', serif" => "Gilda Display",
						"'Give You Glory', cursive" => "Give You Glory",
						"'Glass Antiqua', cursive" => "Glass Antiqua",
						"'Glegoo', serif" => "Glegoo",
						"'Gloria Hallelujah', cursive" => "Gloria Hallelujah",
						"'Goblin One', cursive" => "Goblin One",
						"'Gochi Hand', cursive" => "Gochi Hand",
						"'Gorditas', cursive" => "Gorditas",
						"'Goudy Bookletter 1911', serif" => "Goudy Bookletter 1911",
						"'Graduate', cursive" => "Graduate",
						"'Grand Hotel', cursive" => "Grand Hotel",
						"'Gravitas One', cursive" => "Gravitas One",
						"'Great Vibes', cursive" => "Great Vibes",
						"'Griffy', cursive" => "Griffy",
						"'Gruppo', cursive" => "Gruppo",
						"'Gudea', sans-serif" => "Gudea",
						"'Gurajada', serif" => "Gurajada",
						"'Habibi', serif" => "Habibi",
						"'Halant', serif" => "Halant",
						"'Hammersmith One', sans-serif" => "Hammersmith One",
						"'Hanalei', cursive" => "Hanalei",
						"'Hanalei Fill', cursive" => "Hanalei Fill",
						"'Handlee', cursive" => "Handlee",
						"'Hanuman', serif" => "Hanuman",
						"'Happy Monkey', cursive" => "Happy Monkey",
						"'Harmattan', sans-serif" => "Harmattan",
						"'Headland One', serif" => "Headland One",
						"'Heebo', sans-serif" => "Heebo",
						"'Henny Penny', cursive" => "Henny Penny",
						"'Herr Von Muellerhoff', cursive" => "Herr Von Muellerhoff",
						"'Hind', sans-serif" => "Hind",
						"'Hind Guntur', sans-serif" => "Hind Guntur",
						"'Hind Madurai', sans-serif" => "Hind Madurai",
						"'Hind Siliguri', sans-serif" => "Hind Siliguri",
						"'Hind Vadodara', sans-serif" => "Hind Vadodara",
						"'Holtwood One SC', serif" => "Holtwood One SC",
						"'Homemade Apple', cursive" => "Homemade Apple",
						"'Homenaje', sans-serif" => "Homenaje",
						"'IM Fell DW Pica', serif" => "IM Fell DW Pica",
						"'IM Fell DW Pica SC', serif" => "IM Fell DW Pica SC",
						"'IM Fell Double Pica', serif" => "IM Fell Double Pica",
						"'IM Fell Double Pica SC', serif" => "IM Fell Double Pica SC",
						"'IM Fell English', serif" => "IM Fell English",
						"'IM Fell English SC', serif" => "IM Fell English SC",
						"'IM Fell French Canon', serif" => "IM Fell French Canon",
						"'IM Fell French Canon SC', serif" => "IM Fell French Canon SC",
						"'IM Fell Great Primer', serif" => "IM Fell Great Primer",
						"'IM Fell Great Primer SC', serif" => "IM Fell Great Primer SC",
						"'Iceberg', cursive" => "Iceberg",
						"'Iceland', cursive" => "Iceland",
						"'Imprima', sans-serif" => "Imprima",
						"'Inconsolata', monospace" => "Inconsolata",
						"'Inder', sans-serif" => "Inder",
						"'Indie Flower', cursive" => "Indie Flower",
						"'Inika', serif" => "Inika",
						"'Inknut Antiqua', serif" => "Inknut Antiqua",
						"'Irish Grover', cursive" => "Irish Grover",
						"'Istok Web', sans-serif" => "Istok Web",
						"'Italiana', serif" => "Italiana",
						"'Italianno', cursive" => "Italianno",
						"'Itim', cursive" => "Itim",
						"'Jacques Francois', serif" => "Jacques Francois",
						"'Jacques Francois Shadow', cursive" => "Jacques Francois Shadow",
						"'Jaldi', sans-serif" => "Jaldi",
						"'Jim Nightshade', cursive" => "Jim Nightshade",
						"'Jockey One', sans-serif" => "Jockey One",
						"'Jolly Lodger', cursive" => "Jolly Lodger",
						"'Jomhuria', cursive" => "Jomhuria",
						"'Josefin Sans', sans-serif" => "Josefin Sans",
						"'Josefin Slab', serif" => "Josefin Slab",
						"'Joti One', cursive" => "Joti One",
						"'Judson', serif" => "Judson",
						"'Julee', cursive" => "Julee",
						"'Julius Sans One', sans-serif" => "Julius Sans One",
						"'Junge', serif" => "Junge",
						"'Jura', sans-serif" => "Jura",
						"'Just Another Hand', cursive" => "Just Another Hand",
						"'Just Me Again Down Here', cursive" => "Just Me Again Down Here",
						"'Kadwa', serif" => "Kadwa",
						"'Kalam', cursive" => "Kalam",
						"'Kameron', serif" => "Kameron",
						"'Kanit', sans-serif" => "Kanit",
						"'Kantumruy', sans-serif" => "Kantumruy",
						"'Karla', sans-serif" => "Karla",
						"'Karma', serif" => "Karma",
						"'Katibeh', cursive" => "Katibeh",
						"'Kaushan Script', cursive" => "Kaushan Script",
						"'Kavivanar', cursive" => "Kavivanar",
						"'Kavoon', cursive" => "Kavoon",
						"'Kdam Thmor', cursive" => "Kdam Thmor",
						"'Keania One', cursive" => "Keania One",
						"'Kelly Slab', cursive" => "Kelly Slab",
						"'Kenia', cursive" => "Kenia",
						"'Khand', sans-serif" => "Khand",
						"'Khmer', cursive" => "Khmer",
						"'Khula', sans-serif" => "Khula",
						"'Kite One', sans-serif" => "Kite One",
						"'Knewave', cursive" => "Knewave",
						"'Kotta One', serif" => "Kotta One",
						"'Koulen', cursive" => "Koulen",
						"'Kranky', cursive" => "Kranky",
						"'Kreon', serif" => "Kreon",
						"'Kristi', cursive" => "Kristi",
						"'Krona One', sans-serif" => "Krona One",
						"'Kumar One', cursive" => "Kumar One",
						"'Kumar One Outline', cursive" => "Kumar One Outline",
						"'Kurale', serif" => "Kurale",
						"'La Belle Aurore', cursive" => "La Belle Aurore",
						"'Laila', serif" => "Laila",
						"'Lakki Reddy', cursive" => "Lakki Reddy",
						"'Lalezar', cursive" => "Lalezar",
						"'Lancelot', cursive" => "Lancelot",
						"'Lateef', cursive" => "Lateef",
						"'Lato', sans-serif" => "Lato",
						"'League Script', cursive" => "League Script",
						"'Leckerli One', cursive" => "Leckerli One",
						"'Ledger', serif" => "Ledger",
						"'Lekton', sans-serif" => "Lekton",
						"'Lemon', cursive" => "Lemon",
						"'Lemonada', cursive" => "Lemonada",
						"'Libre Baskerville', serif" => "Libre Baskerville",
						"'Libre Franklin', sans-serif" => "Libre Franklin",
						"'Life Savers', cursive" => "Life Savers",
						"'Lilita One', cursive" => "Lilita One",
						"'Lily Script One', cursive" => "Lily Script One",
						"'Limelight', cursive" => "Limelight",
						"'Linden Hill', serif" => "Linden Hill",
						"'Lobster', cursive" => "Lobster",
						"'Lobster Two', cursive" => "Lobster Two",
						"'Londrina Outline', cursive" => "Londrina Outline",
						"'Londrina Shadow', cursive" => "Londrina Shadow",
						"'Londrina Sketch', cursive" => "Londrina Sketch",
						"'Londrina Solid', cursive" => "Londrina Solid",
						"'Lora', serif" => "Lora",
						"'Love Ya Like A Sister', cursive" => "Love Ya Like A Sister",
						"'Loved by the King', cursive" => "Loved by the King",
						"'Lovers Quarrel', cursive" => "Lovers Quarrel",
						"'Luckiest Guy', cursive" => "Luckiest Guy",
						"'Lusitana', serif" => "Lusitana",
						"'Lustria', serif" => "Lustria",
						"'Macondo', cursive" => "Macondo",
						"'Macondo Swash Caps', cursive" => "Macondo Swash Caps",
						"'Mada', sans-serif" => "Mada",
						"'Magra', sans-serif" => "Magra",
						"'Maiden Orange', cursive" => "Maiden Orange",
						"'Maitree', serif" => "Maitree",
						"'Mako', sans-serif" => "Mako",
						"'Mallanna', sans-serif" => "Mallanna",
						"'Mandali', sans-serif" => "Mandali",
						"'Marcellus', serif" => "Marcellus",
						"'Marcellus SC', serif" => "Marcellus SC",
						"'Marck Script', cursive" => "Marck Script",
						"'Margarine', cursive" => "Margarine",
						"'Marko One', serif" => "Marko One",
						"'Marmelad', sans-serif" => "Marmelad",
						"'Martel', serif" => "Martel",
						"'Martel Sans', sans-serif" => "Martel Sans",
						"'Marvel', sans-serif" => "Marvel",
						"'Mate', serif" => "Mate",
						"'Mate SC', serif" => "Mate SC",
						"'Maven Pro', sans-serif" => "Maven Pro",
						"'McLaren', cursive" => "McLaren",
						"'Meddon', cursive" => "Meddon",
						"'MedievalSharp', cursive" => "MedievalSharp",
						"'Medula One', cursive" => "Medula One",
						"'Meera Inimai', sans-serif" => "Meera Inimai",
						"'Megrim', cursive" => "Megrim",
						"'Meie Script', cursive" => "Meie Script",
						"'Merienda', cursive" => "Merienda",
						"'Merienda One', cursive" => "Merienda One",
						"'Merriweather', serif" => "Merriweather",
						"'Merriweather Sans', sans-serif" => "Merriweather Sans",
						"'Metal', cursive" => "Metal",
						"'Metal Mania', cursive" => "Metal Mania",
						"'Metamorphous', cursive" => "Metamorphous",
						"'Metrophobic', sans-serif" => "Metrophobic",
						"'Michroma', sans-serif" => "Michroma",
						"'Milonga', cursive" => "Milonga",
						"'Miltonian', cursive" => "Miltonian",
						"'Miltonian Tattoo', cursive" => "Miltonian Tattoo",
						"'Miniver', cursive" => "Miniver",
						"'Miriam Libre', sans-serif" => "Miriam Libre",
						"'Mirza', cursive" => "Mirza",
						"'Miss Fajardose', cursive" => "Miss Fajardose",
						"'Mitr', sans-serif" => "Mitr",
						"'Modak', cursive" => "Modak",
						"'Modern Antiqua', cursive" => "Modern Antiqua",
						"'Mogra', cursive" => "Mogra",
						"'Molengo', sans-serif" => "Molengo",
						"'Molle', cursive" => "Molle",
						"'Monda', sans-serif" => "Monda",
						"'Monofett', cursive" => "Monofett",
						"'Monoton', cursive" => "Monoton",
						"'Monsieur La Doulaise', cursive" => "Monsieur La Doulaise",
						"'Montaga', serif" => "Montaga",
						"'Montez', cursive" => "Montez",
						"'Montserrat', sans-serif" => "Montserrat",
						"'Montserrat Alternates', sans-serif" => "Montserrat Alternates",
						"'Montserrat Subrayada', sans-serif" => "Montserrat Subrayada",
						"'Moul', cursive" => "Moul",
						"'Moulpali', cursive" => "Moulpali",
						"'Mountains of Christmas', cursive" => "Mountains of Christmas",
						"'Mouse Memoirs', sans-serif" => "Mouse Memoirs",
						"'Mr Bedfort', cursive" => "Mr Bedfort",
						"'Mr Dafoe', cursive" => "Mr Dafoe",
						"'Mr De Haviland', cursive" => "Mr De Haviland",
						"'Mrs Saint Delafield', cursive" => "Mrs Saint Delafield",
						"'Mrs Sheppards', cursive" => "Mrs Sheppards",
						"'Mukta Vaani', sans-serif" => "Mukta Vaani",
						"'Muli', sans-serif" => "Muli",
						"'Mystery Quest', cursive" => "Mystery Quest",
						"'NTR', sans-serif" => "NTR",
						"'Neucha', cursive" => "Neucha",
						"'Neuton', serif" => "Neuton",
						"'New Rocker', cursive" => "New Rocker",
						"'News Cycle', sans-serif" => "News Cycle",
						"'Niconne', cursive" => "Niconne",
						"'Nixie One', cursive" => "Nixie One",
						"'Nobile', sans-serif" => "Nobile",
						"'Nokora', serif" => "Nokora",
						"'Norican', cursive" => "Norican",
						"'Nosifer', cursive" => "Nosifer",
						"'Nothing You Could Do', cursive" => "Nothing You Could Do",
						"'Noticia Text', serif" => "Noticia Text",
						"'Noto Sans', sans-serif" => "Noto Sans",
						"'Noto Serif', serif" => "Noto Serif",
						"'Nova Cut', cursive" => "Nova Cut",
						"'Nova Flat', cursive" => "Nova Flat",
						"'Nova Mono', monospace" => "Nova Mono",
						"'Nova Oval', cursive" => "Nova Oval",
						"'Nova Round', cursive" => "Nova Round",
						"'Nova Script', cursive" => "Nova Script",
						"'Nova Slim', cursive" => "Nova Slim",
						"'Nova Square', cursive" => "Nova Square",
						"'Numans', sans-serif" => "Numans",
						"'Nunito', sans-serif" => "Nunito",
						"'Nunito Sans', sans-serif" => "Nunito Sans",
						"'Odor Mean Chey', cursive" => "Odor Mean Chey",
						"'Offside', cursive" => "Offside",
						"'Old Standard TT', serif" => "Old Standard TT",
						"'Oldenburg', cursive" => "Oldenburg",
						"'Oleo Script', cursive" => "Oleo Script",
						"'Oleo Script Swash Caps', cursive" => "Oleo Script Swash Caps",
						"'Open Sans', sans-serif" => "Open Sans",
						"'Open Sans Condensed', sans-serif" => "Open Sans Condensed",
						"'Oranienbaum', serif" => "Oranienbaum",
						"'Orbitron', sans-serif" => "Orbitron",
						"'Oregano', cursive" => "Oregano",
						"'Orienta', sans-serif" => "Orienta",
						"'Original Surfer', cursive" => "Original Surfer",
						"'Oswald', sans-serif" => "Oswald",
						"'Over the Rainbow', cursive" => "Over the Rainbow",
						"'Overlock', cursive" => "Overlock",
						"'Overlock SC', cursive" => "Overlock SC",
						"'Overpass', sans-serif" => "Overpass",
						"'Overpass Mono', monospace" => "Overpass Mono",
						"'Ovo', serif" => "Ovo",
						"'Oxygen', sans-serif" => "Oxygen",
						"'Oxygen Mono', monospace" => "Oxygen Mono",
						"'PT Mono', monospace" => "PT Mono",
						"'PT Sans', sans-serif" => "PT Sans",
						"'PT Sans Caption', sans-serif" => "PT Sans Caption",
						"'PT Sans Narrow', sans-serif" => "PT Sans Narrow",
						"'PT Serif', serif" => "PT Serif",
						"'PT Serif Caption', serif" => "PT Serif Caption",
						"'Pacifico', cursive" => "Pacifico",
						"'Padauk', sans-serif" => "Padauk",
						"'Palanquin', sans-serif" => "Palanquin",
						"'Palanquin Dark', sans-serif" => "Palanquin Dark",
						"'Pangolin', cursive" => "Pangolin",
						"'Paprika', cursive" => "Paprika",
						"'Parisienne', cursive" => "Parisienne",
						"'Passero One', cursive" => "Passero One",
						"'Passion One', cursive" => "Passion One",
						"'Pathway Gothic One', sans-serif" => "Pathway Gothic One",
						"'Patrick Hand', cursive" => "Patrick Hand",
						"'Patrick Hand SC', cursive" => "Patrick Hand SC",
						"'Pattaya', sans-serif" => "Pattaya",
						"'Patua One', cursive" => "Patua One",
						"'Pavanam', sans-serif" => "Pavanam",
						"'Paytone One', sans-serif" => "Paytone One",
						"'Peddana', serif" => "Peddana",
						"'Peralta', cursive" => "Peralta",
						"'Permanent Marker', cursive" => "Permanent Marker",
						"'Petit Formal Script', cursive" => "Petit Formal Script",
						"'Petrona', serif" => "Petrona",
						"'Philosopher', sans-serif" => "Philosopher",
						"'Piedra', cursive" => "Piedra",
						"'Pinyon Script', cursive" => "Pinyon Script",
						"'Pirata One', cursive" => "Pirata One",
						"'Plaster', cursive" => "Plaster",
						"'Play', sans-serif" => "Play",
						"'Playball', cursive" => "Playball",
						"'Playfair Display', serif" => "Playfair Display",
						"'Playfair Display SC', serif" => "Playfair Display SC",
						"'Podkova', serif" => "Podkova",
						"'Poiret One', cursive" => "Poiret One",
						"'Poller One', cursive" => "Poller One",
						"'Poly', serif" => "Poly",
						"'Pompiere', cursive" => "Pompiere",
						"'Pontano Sans', sans-serif" => "Pontano Sans",
						"'Poppins', sans-serif" => "Poppins",
						"'Port Lligat Sans', sans-serif" => "Port Lligat Sans",
						"'Port Lligat Slab', serif" => "Port Lligat Slab",
						"'Pragati Narrow', sans-serif" => "Pragati Narrow",
						"'Prata', serif" => "Prata",
						"'Preahvihear', cursive" => "Preahvihear",
						"'Press Start 2P', cursive" => "Press Start 2P",
						"'Pridi', serif" => "Pridi",
						"'Princess Sofia', cursive" => "Princess Sofia",
						"'Prociono', serif" => "Prociono",
						"'Prompt', sans-serif" => "Prompt",
						"'Prosto One', cursive" => "Prosto One",
						"'Proza Libre', sans-serif" => "Proza Libre",
						"'Puritan', sans-serif" => "Puritan",
						"'Purple Purse', cursive" => "Purple Purse",
						"'Quando', serif" => "Quando",
						"'Quantico', sans-serif" => "Quantico",
						"'Quattrocento', serif" => "Quattrocento",
						"'Quattrocento Sans', sans-serif" => "Quattrocento Sans",
						"'Questrial', sans-serif" => "Questrial",
						"'Quicksand', sans-serif" => "Quicksand",
						"'Quintessential', cursive" => "Quintessential",
						"'Qwigley', cursive" => "Qwigley",
						"'Racing Sans One', cursive" => "Racing Sans One",
						"'Radley', serif" => "Radley",
						"'Rajdhani', sans-serif" => "Rajdhani",
						"'Rakkas', cursive" => "Rakkas",
						"'Raleway', sans-serif" => "Raleway",
						"'Raleway Dots', cursive" => "Raleway Dots",
						"'Ramabhadra', sans-serif" => "Ramabhadra",
						"'Ramaraja', serif" => "Ramaraja",
						"'Rambla', sans-serif" => "Rambla",
						"'Rammetto One', cursive" => "Rammetto One",
						"'Ranchers', cursive" => "Ranchers",
						"'Rancho', cursive" => "Rancho",
						"'Ranga', cursive" => "Ranga",
						"'Rasa', serif" => "Rasa",
						"'Rationale', sans-serif" => "Rationale",
						"'Ravi Prakash', cursive" => "Ravi Prakash",
						"'Redressed', cursive" => "Redressed",
						"'Reem Kufi', sans-serif" => "Reem Kufi",
						"'Reenie Beanie', cursive" => "Reenie Beanie",
						"'Revalia', cursive" => "Revalia",
						"'Rhodium Libre', serif" => "Rhodium Libre",
						"'Ribeye', cursive" => "Ribeye",
						"'Ribeye Marrow', cursive" => "Ribeye Marrow",
						"'Righteous', cursive" => "Righteous",
						"'Risque', cursive" => "Risque",
						"'Roboto', sans-serif" => "Roboto",
						"'Roboto Condensed', sans-serif" => "Roboto Condensed",
						"'Roboto Mono', monospace" => "Roboto Mono",
						"'Roboto Slab', serif" => "Roboto Slab",
						"'Rochester', cursive" => "Rochester",
						"'Rock Salt', cursive" => "Rock Salt",
						"'Rokkitt', serif" => "Rokkitt",
						"'Romanesco', cursive" => "Romanesco",
						"'Ropa Sans', sans-serif" => "Ropa Sans",
						"'Rosario', sans-serif" => "Rosario",
						"'Rosarivo', serif" => "Rosarivo",
						"'Rouge Script', cursive" => "Rouge Script",
						"'Rozha One', serif" => "Rozha One",
						"'Rubik', sans-serif" => "Rubik",
						"'Rubik Mono One', sans-serif" => "Rubik Mono One",
						"'Ruda', sans-serif" => "Ruda",
						"'Rufina', serif" => "Rufina",
						"'Ruge Boogie', cursive" => "Ruge Boogie",
						"'Ruluko', sans-serif" => "Ruluko",
						"'Rum Raisin', sans-serif" => "Rum Raisin",
						"'Ruslan Display', cursive" => "Ruslan Display",
						"'Russo One', sans-serif" => "Russo One",
						"'Ruthie', cursive" => "Ruthie",
						"'Rye', cursive" => "Rye",
						"'Sacramento', cursive" => "Sacramento",
						"'Sahitya', serif" => "Sahitya",
						"'Sail', cursive" => "Sail",
						"'Salsa', cursive" => "Salsa",
						"'Sanchez', serif" => "Sanchez",
						"'Sancreek', cursive" => "Sancreek",
						"'Sansita', sans-serif" => "Sansita",
						"'Sarala', sans-serif" => "Sarala",
						"'Sarina', cursive" => "Sarina",
						"'Sarpanch', sans-serif" => "Sarpanch",
						"'Satisfy', cursive" => "Satisfy",
						"'Scada', sans-serif" => "Scada",
						"'Scheherazade', serif" => "Scheherazade",
						"'Schoolbell', cursive" => "Schoolbell",
						"'Scope One', serif" => "Scope One",
						"'Seaweed Script', cursive" => "Seaweed Script",
						"'Secular One', sans-serif" => "Secular One",
						"'Sevillana', cursive" => "Sevillana",
						"'Seymour One', sans-serif" => "Seymour One",
						"'Shadows Into Light', cursive" => "Shadows Into Light",
						"'Shadows Into Light Two', cursive" => "Shadows Into Light Two",
						"'Shanti', sans-serif" => "Shanti",
						"'Share', cursive" => "Share",
						"'Share Tech', sans-serif" => "Share Tech",
						"'Share Tech Mono', monospace" => "Share Tech Mono",
						"'Shojumaru', cursive" => "Shojumaru",
						"'Short Stack', cursive" => "Short Stack",
						"'Shrikhand', cursive" => "Shrikhand",
						"'Siemreap', cursive" => "Siemreap",
						"'Sigmar One', cursive" => "Sigmar One",
						"'Signika', sans-serif" => "Signika",
						"'Signika Negative', sans-serif" => "Signika Negative",
						"'Simonetta', cursive" => "Simonetta",
						"'Sintony', sans-serif" => "Sintony",
						"'Sirin Stencil', cursive" => "Sirin Stencil",
						"'Six Caps', sans-serif" => "Six Caps",
						"'Skranji', cursive" => "Skranji",
						"'Slabo 13px', serif" => "Slabo 13px",
						"'Slabo 27px', serif" => "Slabo 27px",
						"'Slackey', cursive" => "Slackey",
						"'Smokum', cursive" => "Smokum",
						"'Smythe', cursive" => "Smythe",
						"'Sniglet', cursive" => "Sniglet",
						"'Snippet', sans-serif" => "Snippet",
						"'Snowburst One', cursive" => "Snowburst One",
						"'Sofadi One', cursive" => "Sofadi One",
						"'Sofia', cursive" => "Sofia",
						"'Sonsie One', cursive" => "Sonsie One",
						"'Sorts Mill Goudy', serif" => "Sorts Mill Goudy",
						"'Source Code Pro', monospace" => "Source Code Pro",
						"'Source Sans Pro', sans-serif" => "Source Sans Pro",
						"'Source Serif Pro', serif" => "Source Serif Pro",
						"'Space Mono', monospace" => "Space Mono",
						"'Special Elite', cursive" => "Special Elite",
						"'Spicy Rice', cursive" => "Spicy Rice",
						"'Spinnaker', sans-serif" => "Spinnaker",
						"'Spirax', cursive" => "Spirax",
						"'Squada One', cursive" => "Squada One",
						"'Sree Krushnadevaraya', serif" => "Sree Krushnadevaraya",
						"'Sriracha', cursive" => "Sriracha",
						"'Stalemate', cursive" => "Stalemate",
						"'Stalinist One', cursive" => "Stalinist One",
						"'Stardos Stencil', cursive" => "Stardos Stencil",
						"'Stint Ultra Condensed', cursive" => "Stint Ultra Condensed",
						"'Stint Ultra Expanded', cursive" => "Stint Ultra Expanded",
						"'Stoke', serif" => "Stoke",
						"'Strait', sans-serif" => "Strait",
						"'Sue Ellen Francisco', cursive" => "Sue Ellen Francisco",
						"'Suez One', serif" => "Suez One",
						"'Sumana', serif" => "Sumana",
						"'Sunshiney', cursive" => "Sunshiney",
						"'Supermercado One', cursive" => "Supermercado One",
						"'Sura', serif" => "Sura",
						"'Suranna', serif" => "Suranna",
						"'Suravaram', serif" => "Suravaram",
						"'Suwannaphum', cursive" => "Suwannaphum",
						"'Swanky and Moo Moo', cursive" => "Swanky and Moo Moo",
						"'Syncopate', sans-serif" => "Syncopate",
						"'Tangerine', cursive" => "Tangerine",
						"'Taprom', cursive" => "Taprom",
						"'Tauri', sans-serif" => "Tauri",
						"'Taviraj', serif" => "Taviraj",
						"'Teko', sans-serif" => "Teko",
						"'Telex', sans-serif" => "Telex",
						"'Tenali Ramakrishna', sans-serif" => "Tenali Ramakrishna",
						"'Tenor Sans', sans-serif" => "Tenor Sans",
						"'Text Me One', sans-serif" => "Text Me One",
						"'The Girl Next Door', cursive" => "The Girl Next Door",
						"'Tienne', serif" => "Tienne",
						"'Tillana', cursive" => "Tillana",
						"'Timmana', sans-serif" => "Timmana",
						"'Tinos', serif" => "Tinos",
						"'Titan One', cursive" => "Titan One",
						"'Titillium Web', sans-serif" => "Titillium Web",
						"'Trade Winds', cursive" => "Trade Winds",
						"'Trirong', serif" => "Trirong",
						"'Trocchi', serif" => "Trocchi",
						"'Trochut', cursive" => "Trochut",
						"'Trykker', serif" => "Trykker",
						"'Tulpen One', cursive" => "Tulpen One",
						"'Ubuntu', sans-serif" => "Ubuntu",
						"'Ubuntu Condensed', sans-serif" => "Ubuntu Condensed",
						"'Ubuntu Mono', monospace" => "Ubuntu Mono",
						"'Ultra', serif" => "Ultra",
						"'Uncial Antiqua', cursive" => "Uncial Antiqua",
						"'Underdog', cursive" => "Underdog",
						"'Unica One', cursive" => "Unica One",
						"'UnifrakturCook', cursive" => "UnifrakturCook",
						"'UnifrakturMaguntia', cursive" => "UnifrakturMaguntia",
						"'Unkempt', cursive" => "Unkempt",
						"'Unlock', cursive" => "Unlock",
						"'Unna', serif" => "Unna",
						"'VT323', monospace" => "VT323",
						"'Vampiro One', cursive" => "Vampiro One",
						"'Varela', sans-serif" => "Varela",
						"'Varela Round', sans-serif" => "Varela Round",
						"'Vast Shadow', cursive" => "Vast Shadow",
						"'Vesper Libre', serif" => "Vesper Libre",
						"'Vibur', cursive" => "Vibur",
						"'Vidaloka', serif" => "Vidaloka",
						"'Viga', sans-serif" => "Viga",
						"'Voces', cursive" => "Voces",
						"'Volkhov', serif" => "Volkhov",
						"'Vollkorn', serif" => "Vollkorn",
						"'Voltaire', sans-serif" => "Voltaire",
						"'Waiting for the Sunrise', cursive" => "Waiting for the Sunrise",
						"'Wallpoet', cursive" => "Wallpoet",
						"'Walter Turncoat', cursive" => "Walter Turncoat",
						"'Warnes', cursive" => "Warnes",
						"'Wellfleet', cursive" => "Wellfleet",
						"'Wendy One', sans-serif" => "Wendy One",
						"'Wire One', sans-serif" => "Wire One",
						"'Work Sans', sans-serif" => "Work Sans",
						"'Yanone Kaffeesatz', sans-serif" => "Yanone Kaffeesatz",
						"'Yantramanav', sans-serif" => "Yantramanav",
						"'Yatra One', cursive" => "Yatra One",
						"'Yellowtail', cursive" => "Yellowtail",
						"'Yeseva One', cursive" => "Yeseva One",
						"'Yesteryear', cursive" => "Yesteryear",
						"'Yrsa', serif" => "Yrsa",
						"'Zeyada', cursive" => "Zeyada"
					),
					'inherit',
					__('Set an font family','yp')
				)."
				
				
				".yp_get_select_markup(
					'font-weight',
					__('Font Weight','yp')
					,array(
						'300' => __('Light',"yp").' 300',
						'400' => __('normal',"yp").' 400',
						'500' => __('Semi-Bold',"yp").' 500',
						'600' => __('Bold',"yp").' 600',
						'700' => __('Extra-Bold',"yp").' 700'
					),
					'inherit',
					__('Set the font family','yp')
				)."
	
				".yp_get_color_markup(
					'color',
					__('Color','yp'),
					'Set the text color'
				)."

				".yp_get_select_markup(
					'text-shadow',
					__('Text Shadow','yp')
					,array(
						'none' => 'none',
						'rgba(0, 0, 0, 0.3) 0px 1px 1px' => 'Basic Shadow',
						'rgb(255, 255, 255) 1px 1px 0px, rgb(170, 170, 170) 2px 2px 0px' => 'Shadow Multiple',
						'rgb(255, 0, 0) -1px 0px 0px, rgb(0, 255, 255) 1px 0px 0px' => 'Anaglyph',
						'rgb(255, 255, 255) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px' => 'Emboss',
						'rgb(255, 255, 255) 0px 0px 2px, rgb(255, 255, 255) 0px 0px 4px, rgb(255, 255, 255) 0px 0px 6px, rgb(255, 119, 255) 0px 0px 8px, rgb(255, 0, 255) 0px 0px 12px, rgb(255, 0, 255) 0px 0px 16px, rgb(255, 0, 255) 0px 0px 20px, rgb(255, 0, 255) 0px 0px 24px' => 'Neon',
						'rgb(0, 0, 0) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px, rgb(0, 0, 0) 1px 0px 1px, rgb(0, 0, 0) -1px 0px 1px' => 'Outline'
					),
					'none'
				)."

				".yp_get_slider_markup(
					'font-size',
					__('Font Size','yp'),
					'inherit',
					0,        // decimals
					'8,100',   // px value
					'0,100',  // percentage value
					'1,6'     // Em value
				)."
				
				".yp_get_slider_markup(
					'line-height',
					__('Line Height','yp'),
					'inherit',
					1,        // decimals
					'0,100',   // px value
					'0,100',  // percentage value
					'1,6',     // Em value,
					__('Set the leading','yp')
				)."
				
				".yp_get_radio_markup(
					'font-style',
					__('Font Style','yp'),
					array(
						'normal' => __('Normal','yp'),
						'italic' => __('Italic','yp')
					),
					'inherit'
				)."

				".yp_get_radio_markup(
					'text-align',
					__('Text Align','yp'),
					array(
						'left' => __('left','yp'),
						'center' => __('center','yp'),
						'right' => __('right','yp'),
						'justify' => __('justify','yp')
					),
					'start'
				)."
				
				".yp_get_radio_markup(
					'text-transform',
					__('Text Transform','yp'),
					array(
						'uppercase' => __('upprcase','yp'),
						'lowercase' => __('lowercase','yp'),
						'capitalize' => __('capitalize','yp')
					),
					'none'						
				)."
			
				
				".yp_get_slider_markup(
					'letter-spacing',
					__('Letter Spacing','yp'),
					'inherit',
					1,        // decimals
					'-5,10',   // px value
					'0,100',  // percentage value
					'-1,3'     // Em value
				)."
				
				".yp_get_slider_markup(
					'word-spacing',
					__('Word Spacing','yp'),
					'inherit',
					1,        // decimals
					'-5,20',   // px value
					'0,100',  // percentage value
					'-1,3'     // Em value,
				)."

				".yp_get_radio_markup(
					'text-decoration',
					__('text Decoration','yp'),
					array(
						'overline' => __('overline','yp'),
						'line-through' => __('through','yp'),
						'underline' => __('underline','yp')
					),
					'none'
				)."
				
			</div>
		</li>
		
		<li class='background-option'>
			<h3>".__('Background','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>
			
				<a class='yp-advanced-link yp-top yp-special-css-link yp-just-desktop yp-parallax-link'>".__('Background Parallax','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-just-desktop background-parallax-div'>

					<div class='little-break yp-lite'></div>

					".yp_get_radio_markup( // Special CSS
						'background-parallax',
						__('Effect Status','yp'),
						array(
							'true' => __('Enable','yp'),
							'disable' => __('Disable','yp')
						),
						false						
					)."
					
					".yp_get_slider_markup(
						'background-parallax-speed',
						__('Parallax Speed','yp'),
						'',
						2,        // decimals
						'1,10',   // px value
						'1,10',  // percentage value
						'1,10'     // Em value
					)."
					
					".yp_get_slider_markup(
						'background-parallax-x',
						__('Parallax Position X','yp'),
						'',
						2,        // decimals
						'1,100',   // px value
						'1,100',  // percentage value
						'1,100'     // Em value
					)."
					
				</div>
				
				".yp_get_color_markup(
					'background-color',
					__('Background Color','yp')
				)."
				
				".yp_get_input_markup(
					'background-image',
					__('Background Image','yp'),
					'none'
				)."

				".yp_get_radio_markup(
					'background-blend-mode',
					__('BG. Blend Mode','yp'),
					array(
						'multiply' => __('multiply','yp'),
						'darken' => __('darken','yp'),
						'luminosity' => __('luminosity','yp')			
					),
					'normal',
					__('Mix the background color with the background image.','yp')
				)."

				".yp_get_select_markup(
					'background-position',
					__('BG. Position','yp'),
					array(
						'0% 0%' => __('left top','yp'),
						'0% 50%' => __('left center','yp'),
						'0% 100%' => __('left bottom','yp'),
						'100% 0%' => __('right top','yp'),
						'100% 50%' => __('right center','yp'),
						'100% 100%' => __('right bottom','yp'),
						'50% 0%' => __('center top','yp'),
						'50% 50%' => __('center center','yp'),
						'50% 100%' => __('center bottom','yp')
					),
					'0% 0%',
					__('Sets the starting position of a background image','yp')
				)."

				".yp_get_radio_markup(
					'background-size',
					__('Background Size','yp'),
					array(
						'length' => __('length','yp'),
						'cover' => __('cover','yp'),
						'contain' => __('contain','yp')
					),
					'auto auto',
					__('The size of the background image','yp')
				)."				
				
				".yp_get_radio_markup(
					'background-repeat',
					__('Background Repeat','yp'),
					array(
						'repeat-x' => __('repeat-x','yp'),
						'repeat-y' => __('repeat-y','yp'),
						'no-repeat' => __('no-repeat','yp')
					),
					'repeat',
					__('Sets if background image will be repeated','yp')
				)."
				
				".yp_get_radio_markup(
					'background-attachment',
					__('BG. Attachment','yp'),
					array(
						'fixed' => __('fixed','yp'),
						'local' => __('local','yp')
					),
					'scroll',
					__('Sets whether a background image is fixed or scrolls with the rest of the page','yp')
				)."				
				
			</div>
		</li>
		
		<li class='margin-option'>
			<h3>".__('Margin','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				<div class='lock-btn'></div>

				".yp_get_slider_markup(
					'margin-left',
					__('Margin Left','yp'),
					'auto',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value,
					__('The margin clears an area around an element. The margin does not have a background color, and is completely transparent.','yp')
				)."
				
				".yp_get_slider_markup(
					'margin-right',
					__('Margin Right','yp'),
					'auto',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					__('The margin clears an area around an element. The margin does not have a background color, and is completely transparent.','yp')
				)."

				".yp_get_slider_markup(
					'margin-top',
					__('Margin Top','yp'),
					'auto',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					__('The margin clears an area around an element. The margin does not have a background color, and is completely transparent.','yp')
				)."
				
				".yp_get_slider_markup(
					'margin-bottom',
					__('Margin Bottom','yp'),
					'auto',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					__('The margin clears an area around an element. The margin does not have a background color, and is completely transparent.','yp')
				)."
				
				
				
			</div>
		</li>
		
		<li class='padding-option'>
			<h3>".__('Padding','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>
				
				<div class='lock-btn'></div>

				".yp_get_slider_markup(
					'padding-left',
					__('Padding Left','yp'),
					'',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					__('The padding clears an area around the content of an element. The padding is affected by the background color of the element.','yp')
				)."

				".yp_get_slider_markup(
					'padding-right',
					__('Padding Right','yp'),
					'',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					__('The padding clears an area around the content of an element. The padding is affected by the background color of the element.','yp')
				)."

				".yp_get_slider_markup(
					'padding-top',
					__('Padding Top','yp'),
					'',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					__('The padding clears an area around the content of an element. The padding is affected by the background color of the element.','yp')
				)."
				
				".yp_get_slider_markup(
					'padding-bottom',
					__('Padding Bottom','yp'),
					'',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					__('The padding clears an area around the content of an element. The padding is affected by the background color of the element.','yp')
				)."

				
				
			
			</div>
		</li>

		
		<li class='border-option'>
			<h3>".__('Border','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>
				
				
				".yp_get_radio_markup(
					'border-style',
					__('Border Style','yp'),
					array(
						'solid' => __('solid','yp'),
						'dotted' => __('dotted','yp'),
						'dashed' => __('dashed','yp'),
						'hidden' => __('hidden','yp')
					),
					'none',
					__('Sets the style of an elements four borders. This property can have from one to four values.','yp')
				)."
				
				
				".yp_get_slider_markup(
					'border-width',
					__('Border Width','yp'),
					'',
					0,        // decimals
					'0,20',   // px value
					'0,100',  // percentage value
					'0,3',     // Em value
					__('Sets the width of an elements four borders. This property can have from one to four values.','yp')
				)."
				
				".yp_get_color_markup(
					'border-color',
					__('Border Color','yp'),
					__('Sets the color of an elements four borders.','yp')
				)."
				
				
				<a class='yp-advanced-link yp-special-css-link yp-border-special'>".__('Border Top','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-border-special-content'>
				".yp_get_radio_markup(
					'border-top-style',
					__('Style','yp'),
					array(
						'solid' => __('solid','yp'),
						'dotted' => __('dotted','yp'),
						'dashed' => __('dashed','yp'),
						'hidden' => __('hidden','yp')
					),
					'none',
					__('Sets the style of an elements top border.','yp')
				)."
				
				".yp_get_slider_markup(
					'border-top-width',
					__('Width','yp'),
					'',
					0,        // decimals
					'0,20',   // px value
					'0,100',  // percentage value
					'0,3',     // Em value
					__('Sets the width of an elements top border.','yp')
				)."
				
				".yp_get_color_markup(
					'border-top-color',
					__('Color','yp'),
					__('Sets the color of an elements top border.','yp')
				)."
				</div>
				
				<a class='yp-advanced-link yp-special-css-link yp-border-special'>".__('Border Right','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-border-special-content'>
				".yp_get_radio_markup(
					'border-right-style',
					__('Style','yp'),
					array(
						'solid' => __('solid','yp'),
						'dotted' => __('dotted','yp'),
						'dashed' => __('dashed','yp'),
						'hidden' => __('hidden','yp')
					),
					'none',
					__('Sets the style of an elements right border.','yp')
				)."
				
				".yp_get_slider_markup(
					'border-right-width',
					__('Width','yp'),
					'',
					0,        // decimals
					'0,20',   // px value
					'0,100',  // percentage value
					'0,3',     // Em value
					__('Sets the width of an elements right border.','yp')
				)."
				
				".yp_get_color_markup(
					'border-right-color',
					__('Color','yp'),
					__('Sets the color of an elements right border.','yp')
				)."
				</div>
				
				
				<a class='yp-advanced-link yp-special-css-link yp-border-special'>".__('Border Bottom','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-border-special-content'>
				".yp_get_radio_markup(
					'border-bottom-style',
					__('Style','yp'),
					array(
						'solid' => __('solid','yp'),
						'dotted' => __('dotted','yp'),
						'dashed' => __('dashed','yp'),
						'hidden' => __('hidden','yp')
					),
					'none',
					__('Sets the style of an elements bottom border.','yp')
				)."
				
				".yp_get_slider_markup(
					'border-bottom-width',
					__('Width','yp'),
					'',
					0,        // decimals
					'0,20',   // px value
					'0,100',  // percentage value
					'0,3',     // Em value
					__('Sets the width of an elements bottom border.','yp')
				)."
				
				".yp_get_color_markup(
					'border-bottom-color',
					__('Color','yp'),
					__('Sets the color of an elements bottom border.','yp')
				)."
				</div>
				
				
				<a class='yp-advanced-link yp-special-css-link yp-border-special yp-border-special-last'>".__('Border Left','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-border-special-content'>
				".yp_get_radio_markup(
					'border-left-style',
					__('Style','yp'),
					array(
						'solid' => __('solid','yp'),
						'dotted' => __('dotted','yp'),
						'dashed' => __('dashed','yp'),
						'hidden' => __('hidden','yp')
					),
					'none',
					__('Sets the style of an elements left border.','yp')
				)."
				
				".yp_get_slider_markup(
					'border-left-width',
					__('Width','yp'),
					'',
					0,        // decimals
					'0,20',   // px value
					'0,100',  // percentage value
					'0,3',     // Em value
					__('Sets the width of an elements left border.','yp')
				)."
				
				".yp_get_color_markup(
					'border-left-color',
					__('Color','yp'),
					__('Sets the color of an elements left border.','yp')
				)."
				</div>
				
			</div>
		</li>
		
		<li class='border-radius-option'>
			<h3>".__('Border Radius','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>
				
				<div class='lock-btn'></div>
				".yp_get_slider_markup(
					'border-top-left-radius',
					__('Top Left Radius','yp'),
					'',
					0,        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					__('Defines the shape of the border of the top-left corner','yp')
				)."
				
				".yp_get_slider_markup(
					'border-top-right-radius',
					__('Top Right Radius','yp'),
					'',
					0,        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					__('Defines the shape of the border of the top-right corner','yp')
				)."
				
				".yp_get_slider_markup(
					'border-bottom-right-radius',
					__('Bottom Right Radius','yp'),
					'',
					0,        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					__('Defines the shape of the border of the bottom-right corner','yp')
				)."

				".yp_get_slider_markup(
					'border-bottom-left-radius',
					__('Bottom Left Radius','yp'),
					'',
					0,        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					__('Defines the shape of the border of the bottom-left corner','yp')
				)."
				
				
			</div>
		</li>
		
		<li class='position-option'>
			<h3>".__('Position','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				".yp_get_slider_markup(
					'z-index',
					__('Z Index','yp'),
					'auto',
					0,        // decimals
					'-10,1000',   // px value
					'-10,1000',  // percentage value
					'-10,1000',     // Em value
					__('Specifies the stack order of an element. Z index only works on positioned elements (absolute, relative, or fixed).','yp')
				)."	
				
				".yp_get_radio_markup(
					'position',
					__('Position','yp'),
					array(
						'static' => 'static',
						'relative' => 'relative',
						'absolute' => 'absolute',
						'fixed' => 'fixed'
					),
					'',
					__('Specifies the type of positioning method used for an element','yp')
					
				)."
				
				".yp_get_slider_markup(
					'top',
					__('Top','yp'),
					'auto',
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					__('For absolutely: positioned elements, the top property sets the top edge of an element to a unit above/below the top edge of its containing element.<br><br>For relatively: positioned elements, the top property sets the top edge of an element to a unit above/below its normal position.','yp')
				)."

				".yp_get_slider_markup(
					'left',
					__('Left','yp'),
					'auto',
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					__('For absolutely: positioned elements, the left property sets the left edge of an element to a unit to the left/right of the left edge of its containing element.<br><br>For relatively: positioned elements, the left property sets the left edge of an element to a unit to the left/right to its normal position.','yp')
				)."

				".yp_get_slider_markup(
					'bottom',
					__('Bottom','yp'),
					'auto',
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					__('For absolutely: positioned elements, the bottom property sets the bottom edge of an element to a unit above/below the bottom edge of its containing element.<br><br>For relatively: positioned elements, the bottom property sets the bottom edge of an element to a unit above/below its normal position.','yp')
				)."
				
				".yp_get_slider_markup(
					'right',
					__('Right','yp'),
					'auto',
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					__('For absolutely: positioned elements, the right property sets the right edge of an element to a unit to the left/right of the right edge of its containing element.<br><br>For relatively: positioned elements, the right property sets the right edge of an element to a unit to the left/right to its normal position.','yp')
				)."
				
			</div>
		</li>
		
		<li class='size-option'>
			<h3>".__('Size','yp')." <span class='yp-badge yp-lite'>Pro</span> ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				<p class='yp-alert-warning yp-top-alert yp-lite'>Size ".__('Properties is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>

				".yp_get_slider_markup(
					'width',
					__('Width','yp'),
					'auto',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					__('Sets the width of an element','yp')
				)."
				
				".yp_get_slider_markup(
					'height',
					__('Height','yp'),
					'auto',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					__('sets the height of an element','yp')
				)."

				".yp_get_radio_markup(
					'box-sizing',
					__('Box Sizing','yp'),
					array(
						'border-box' => __('border-box','yp'),
						'content-box' => __('content-box','yp')
					),
					'content-box',
					__('is used to tell the browser what the sizing properties (width and height) should include. Should they include the border-box? Or just the content-box (which is the default value of the width and height properties)?','yp')
				)."
				
				".yp_get_slider_markup(
					'min-width',
					__('Min Width','yp'),
					'initial',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					__('is used to set the minimum width of an element','yp')
				)."

				".yp_get_slider_markup(
					'min-height',
					__('Min Height','yp'),
					'initial',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',    // Em value
					__('is used to set the minimum height of an element','yp')
				)."
				
				".yp_get_slider_markup(
					'max-width',
					__('Max Width','yp'),
					'auto',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					__('is used to set the maximum width of an element','yp')
				)."
				
				".yp_get_slider_markup(
					'max-height',
					__('Max Height','yp'),
					'auto',
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					__('is used to set the maximum height of an element','yp')
				)."
				
				
			</div>
		</li>

		<li class='animation-option'>
			<h3>".__('Animation','yp')." <span class='yp-badge yp-lite'>Pro</span> <span class='yp-badge yp-anim-recording'>".__('Recording','yp')."</span> ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>
				
				<p class='yp-alert-warning yp-top-alert yp-lite'>Animation ".__('Property is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>
				
				<div class='animation-links-control yp-just-desktop'>

				<a class='yp-advanced-link yp-special-css-link yp-just-desktop yp-add-animation-link'>".__('Create Animation','yp')."</a>

				<a class='yp-advanced-link yp-special-css-link yp-just-desktop yp-animation-player'>".__('Play','yp')."</a>

				<a class='yp-advanced-link yp-special-css-link yp-just-desktop yp-animation-creator-start'>".__('Create','yp')."</a>

				<div class='yp-clearfix'></div>

				</div>

			";

				// Default animations
				$animations = array(
					'none' => 'none',
					'bounce' => 'bounce',
					'spin' => 'spin',
					'flash' => 'flash',
					'swing' => 'swing',
					'pulse' => 'pulse',
					'rubberBand' => 'rubberBand',
					'shake' => 'shake',
					'tada' => 'tada',
					'wobble' => 'wobble',
					'jello' => 'jello',
					'bounceIn' => 'bounceIn',
						
					'spaceInUp' => 'spaceInUp',
					'spaceInRight' => 'spaceInRight',
					'spaceInDown' => 'spaceInDown',
					'spaceInLeft' => 'spaceInLeft',
					'push' => 'push',
					'pop' => 'pop',
					'bob' => 'bob',
					'wobble-horizontal' => 'wobble-horizontal',
											
					'bounceInDown' => 'bounceInDown',
					'bounceInLeft' => 'bounceInLeft',
					'bounceInRight' => 'bounceInRight',
					'bounceInUp' => 'bounceInUp',
					'fadeIn' => 'fadeIn',
					'fadeInDown' => 'fadeInDown',
					'fadeInDownBig' => 'fadeInDownBig',
					'fadeInLeft' => 'fadeInLeft',
					'fadeInLeftBig' => 'fadeInLeftBig',
					'fadeInRight' => 'fadeInRight',
					'fadeInRightBig' => 'fadeInRightBig',
					'fadeInUp' => 'fadeInUp',
					'fadeInUpBig' => 'fadeInUpBig',
					'flipInX' => 'flipInX',
					'flipInY' => 'flipInY',
					'lightSpeedIn' => 'lightSpeedIn',
					'rotateIn' => 'rotateIn',
					'rotateInDownLeft' => 'rotateInDownLeft',
					'rotateInDownRight' => 'rotateInDownRight',
					'rotateInUpLeft' => 'rotateInUpLeft',
					'rotateInUpRight' => 'rotateInUpRight',
					'rollIn' => 'rollIn',
					'zoomIn' => 'zoomIn',
					'zoomInDown' => 'zoomInDown',
					'zoomInLeft' => 'zoomInLeft',
					'zoomInRight' => 'zoomInRight',
					'zoomInUp' => 'zoomInUp',
					'slideInDown' => 'slideInDown',
					'slideInLeft' => 'slideInLeft',
					'slideInRight' => 'slideInRight',
					'slideInUp' => 'slideInUp'
				);

				// Add dynamic animations.
				$all_options =  wp_load_alloptions();
				foreach($all_options as $name => $value){
					if(stristr($name, 'yp_anim')){
						$name = str_replace("yp_anim_", "", $name);
						$animations[$name] = ucwords(strtolower($name));
					}
				}
				
				echo " ".yp_get_select_markup(
					'animation-name',
					__('Animation','yp'),
					$animations,
					'none'
				)."
				
				".yp_get_select_markup(
					'animation-play',
					__('Animation Play','yp'),
					array(
						'yp_onscreen' => __('onScreen','yp'),
						'yp_hover' => __('Hover','yp'),
						'yp_click' => __('Click','yp'),
						'yp_focus' => __('Focus','yp')
					),
					'yp_onscreen',
					__('OnScreen: Playing animation when element visible on screen.<br><br>Hover: Playing animation when mouse on element.<br><br>Click: Playing animation when element clicked.<br><br>Focus: Playing element when click on an text field.','yp')
				)."
				
				".yp_get_select_markup(
					'animation-iteration-count',
					__('animation Iteration','yp'),
					array(
						'1' => '1',
						'2' => '2',
						'infinite' => __('infinite','yp')
					),
					'1'
				)."
				
				".yp_get_input_markup(
						'set-animation-name',
						__('Set Animation Name','yp'),
						'none'
					)."

				".yp_get_slider_markup(
					'animation-duration',
					__('Animation Duration','yp'),
					'0',
					2,        // decimals
					'1,10',   // px value
					'1,10',  // percentage value
					'1,10'     // Em/ms value
				)."

				".yp_get_slider_markup(
					'animation-delay',
					__('Animation Delay','yp'),
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em/ms value
				)."

				".yp_get_radio_markup(
					'animation-fill-mode',
					__('Animation Fill Mode','yp'),
					array(
						'forwards' => __('forwards','yp'),
						'backwards' => __('backwards','yp'),
						'both' => __('both','yp'),
					),
					'none',
					__('This property sets the state of the end animation when the animation is not running','yp')
				)."		
				
			</div>
		</li>
		
		<li class='filters-option'>
			<h3>".__('Filters','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				".yp_get_slider_markup(
					'blur-filter',
					__('Blur','yp'),
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'brightness-filter',
					__('Brightness','yp'),
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'grayscale-filter',
					__('Grayscale','yp'),
					'0',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1'     // Em value
				)."
				
				".yp_get_slider_markup(
					'contrast-filter',
					__('Contrast','yp'),
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'hue-rotate-filter',
					__('Hue Rotate','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'saturate-filter',
					__('Saturate','yp'),
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'sepia-filter',
					__('Sepia','yp'),
					'0',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1'     // Em value
				)."
			</div>
		</li>
		
		<li class='box-shadow-option'>
			<h3>".__('Box Shadow','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				<p class='yp-alert-warning yp-top-alert yp-has-box-shadow'>".__('Set transparent color for hide box shadow property.','yp')."</p>

				".yp_get_color_markup(
					'box-shadow-color',
					__('Color','yp')
				)."
				
				".yp_get_slider_markup(
					'box-shadow-blur-radius',
					__('Blur Radius','yp'),
					'0',
					0,        	// decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'box-shadow-spread',
					__('Spread','yp'),
					'0',
					0,        	// decimals
					'-50,100',   // px value
					'-50,100',  // percentage value
					'-50,100'     // Em value
				)."

				".yp_get_radio_markup(
					'box-shadow-inset',
					__('Inset','yp'),
					array(
						'no' => __('no','yp'),
						'inset' => __('inset','yp')
					),
					false
				)."		

				".yp_get_slider_markup(
					'box-shadow-horizontal',
					__('Horizontal Length','yp'),
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'box-shadow-vertical',
					__('Vertical Length','yp'),
					'0',
					0,        	// decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."

			</div>
		</li>
		
		<li class='extra-option'>
			<h3>".__('Extra','yp')." ".yp_arrow_icon()."</h3>
			<div class='yp-this-content'>

				<a class='yp-advanced-link yp-top yp-special-css-link yp-transform-link'>".__('Transform','yp')."</a>
				<div class='yp-advanced-option yp-special-css-area yp-transform-area'>
				".yp_get_slider_markup(
					'scale-transform',
					__('Scale','yp'),
					'0',
					2,        // decimals
					'0,5',   // px value
					'0,5',  // percentage value
					'0,5'     // Em value
				)."
				
				".yp_get_slider_markup(
					'rotate-transform',
					__('Rotate','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatex-transform',
					__('Rotate X','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatey-transform',
					__('Rotate Y','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatez-transform',
					__('Rotate Z','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'translate-x-transform',
					__('Translate X','yp'),
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'translate-y-transform',
					__('Translate Y','yp'),
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'skew-x-transform',
					__('Skew X','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'skew-y-transform',
					__('skew Y','yp'),
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'perspective',
					__('Perspective','yp'),
					'0',
					0,        // decimals
					'0,1000',   // px value
					'0,100',  // percentage value
					'0,62'     // Em value
				)."

				</div>
				
				
				".yp_get_slider_markup(
					'opacity',
					__('Opacity','yp'),
					'auto',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1',     // Em value
					__('The opacity property can take a value from 0.0 - 1.0. The lower value, the more transparent.','yp')
				)."

				".yp_get_select_markup(
					'display',
					__('Display','yp'),
					array(
						'block' => __('block','yp'),
						'flex' => __('flex','yp'),
						'inline-block' => __('inline-block','yp'),
						'inline-flex' => __('inline-flex','yp'),
						'table-cell' => __('table-cell','yp'),
						'none' => __('none','yp'),
					),
					'inline',
					__('Specifies the type of box used for an element.','yp')
				)."
				
				".yp_get_radio_markup(
					'float',
					__('Float','yp'),
					array(
						'left' => __('left','yp'),
						'right' => __('right','yp')
					),
					'none',
					__('Specifies whether or not a box (an element) should float.','yp')
				)."

				".yp_get_radio_markup(
					'clear',
					__('Clear','yp'),
					array(
						'left' => __('left','yp'),
						'right' => __('right','yp'),
						'both' => __('both','yp')
					),
					'none',
					__('Specifies on which sides of an element where floating elements are not allowed to float.','yp')
				)."

				".yp_get_radio_markup(
					'visibility',
					__('Visibility','yp'),
					array(
						'visible' => __('visible','yp'),
						'hidden' => __('hidden','yp')
					),
					'inherit',
					__('specifies whether or not an element is visible.','yp')
				)."
				
				".yp_get_radio_markup(
					'overflow-x',
					__('Overflow X','yp'),
					array(
						'hidden' => __('hidden','yp'),
						'scroll' => __('scroll','yp'),
						'auto' => __('auto','yp')
					),
					'visible',
					__('specifies what to do with the left/right edges of the content - if it overflows the elements content area.','yp')
				)."
				
				".yp_get_radio_markup(
					'overflow-y',
					__('Overflow Y','yp'),
					array(
						'hidden' => __('hidden','yp'),
						'scroll' => __('scroll','yp'),
						'auto' => __('auto','yp')
					),
					'visible',
					__('specifies what to do with the left/right edges of the content - if it overflows the elements content area.','yp')
				)."
				
				
			</div>
		</li>
		
		<li class='yp-li-footer'>
			<h3><a target='_blank' href='http://waspthemes.com/yellow-pencil/documentation/'>".__('Documentation','yp')."</a> / V ".YP_VERSION."</h3>
		</li>
			
	</ul>";