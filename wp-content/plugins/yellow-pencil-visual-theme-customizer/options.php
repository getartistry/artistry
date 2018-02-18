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

// arrow icon for list
$arrow_icon = "<span class='dashicons yp-arrow-icon dashicons-arrow-up'></span><span class='dashicons yp-arrow-icon dashicons-arrow-down'></span>";

/* ---------------------------------------------------- */
/* All CSS Options and settings							*/
/* ---------------------------------------------------- */
echo "<ul class='yp-editor-list'>
		
		<li class='text-option'>
		
			<h3>Text ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_select_markup(
					'font-family',
					'Font Family',
					array(
					
						// Safe Fonts.
						"Georgia, serif" => "Georgia",
						"'Helvetica Neue',Helvetica,Arial,sans-serif" => "Helvetica Neue",
						"'Times New Roman', Times, serif" => "Times New Roman",
						"Arial, Helvetica, sans-serif" => "Arial",
						"'Arial Black', Gadget, sans-serif" => "Arial Black",
						"Impact, Charcoal, sans-serif" => "Impact",
						"Tahoma, Geneva, sans-serif" => "Tahoma",
						"Verdana, Geneva, sans-serif" => "Verdana",
						
						// Google Fonts.
						"'ABeeZee', sans-serif" => "ABeeZee",
						"'Abel', sans-serif" => "Abel",
						"'Abhaya Libre', serif" => "Abhaya Libre",
						"'Abril Fatface', display" => "Abril Fatface",
						"'Aclonica', sans-serif" => "Aclonica",
						"'Acme', sans-serif" => "Acme",
						"'Actor', sans-serif" => "Actor",
						"'Adamina', serif" => "Adamina",
						"'Advent Pro', sans-serif" => "Advent Pro",
						"'Aguafina Script', handwriting" => "Aguafina Script",
						"'Akronim', display" => "Akronim",
						"'Aladin', handwriting" => "Aladin",
						"'Aldrich', sans-serif" => "Aldrich",
						"'Alef', sans-serif" => "Alef",
						"'Alegreya', serif" => "Alegreya",
						"'Alegreya SC', serif" => "Alegreya SC",
						"'Alegreya Sans', sans-serif" => "Alegreya Sans",
						"'Alegreya Sans SC', sans-serif" => "Alegreya Sans SC",
						"'Alex Brush', handwriting" => "Alex Brush",
						"'Alfa Slab One', display" => "Alfa Slab One",
						"'Alice', serif" => "Alice",
						"'Alike', serif" => "Alike",
						"'Alike Angular', serif" => "Alike Angular",
						"'Allan', display" => "Allan",
						"'Allerta', sans-serif" => "Allerta",
						"'Allerta Stencil', sans-serif" => "Allerta Stencil",
						"'Allura', handwriting" => "Allura",
						"'Almendra', serif" => "Almendra",
						"'Almendra Display', display" => "Almendra Display",
						"'Almendra SC', serif" => "Almendra SC",
						"'Amarante', display" => "Amarante",
						"'Amaranth', sans-serif" => "Amaranth",
						"'Amatic SC', handwriting" => "Amatic SC",
						"'Amethysta', serif" => "Amethysta",
						"'Amiko', sans-serif" => "Amiko",
						"'Amiri', serif" => "Amiri",
						"'Amita', handwriting" => "Amita",
						"'Anaheim', sans-serif" => "Anaheim",
						"'Andada', serif" => "Andada",
						"'Andika', sans-serif" => "Andika",
						"'Angkor', display" => "Angkor",
						"'Annie Use Your Telescope', handwriting" => "Annie Use Your Telescope",
						"'Anonymous Pro', monospace" => "Anonymous Pro",
						"'Antic', sans-serif" => "Antic",
						"'Antic Didone', serif" => "Antic Didone",
						"'Antic Slab', serif" => "Antic Slab",
						"'Anton', sans-serif" => "Anton",
						"'Arapey', serif" => "Arapey",
						"'Arbutus', display" => "Arbutus",
						"'Arbutus Slab', serif" => "Arbutus Slab",
						"'Architects Daughter', handwriting" => "Architects Daughter",
						"'Archivo', sans-serif" => "Archivo",
						"'Archivo Black', sans-serif" => "Archivo Black",
						"'Archivo Narrow', sans-serif" => "Archivo Narrow",
						"'Aref Ruqaa', serif" => "Aref Ruqaa",
						"'Arima Madurai', display" => "Arima Madurai",
						"'Arimo', sans-serif" => "Arimo",
						"'Arizonia', handwriting" => "Arizonia",
						"'Armata', sans-serif" => "Armata",
						"'Arsenal', sans-serif" => "Arsenal",
						"'Artifika', serif" => "Artifika",
						"'Arvo', serif" => "Arvo",
						"'Arya', sans-serif" => "Arya",
						"'Asap', sans-serif" => "Asap",
						"'Asap Condensed', sans-serif" => "Asap Condensed",
						"'Asar', serif" => "Asar",
						"'Asset', display" => "Asset",
						"'Assistant', sans-serif" => "Assistant",
						"'Astloch', display" => "Astloch",
						"'Asul', sans-serif" => "Asul",
						"'Athiti', sans-serif" => "Athiti",
						"'Atma', display" => "Atma",
						"'Atomic Age', display" => "Atomic Age",
						"'Aubrey', display" => "Aubrey",
						"'Audiowide', display" => "Audiowide",
						"'Autour One', display" => "Autour One",
						"'Average', serif" => "Average",
						"'Average Sans', sans-serif" => "Average Sans",
						"'Averia Gruesa Libre', display" => "Averia Gruesa Libre",
						"'Averia Libre', display" => "Averia Libre",
						"'Averia Sans Libre', display" => "Averia Sans Libre",
						"'Averia Serif Libre', display" => "Averia Serif Libre",
						"'Bad Script', handwriting" => "Bad Script",
						"'Bahiana', display" => "Bahiana",
						"'Baloo', display" => "Baloo",
						"'Baloo Bhai', display" => "Baloo Bhai",
						"'Baloo Bhaijaan', display" => "Baloo Bhaijaan",
						"'Baloo Bhaina', display" => "Baloo Bhaina",
						"'Baloo Chettan', display" => "Baloo Chettan",
						"'Baloo Da', display" => "Baloo Da",
						"'Baloo Paaji', display" => "Baloo Paaji",
						"'Baloo Tamma', display" => "Baloo Tamma",
						"'Baloo Tammudu', display" => "Baloo Tammudu",
						"'Baloo Thambi', display" => "Baloo Thambi",
						"'Balthazar', serif" => "Balthazar",
						"'Bangers', display" => "Bangers",
						"'Barlow', sans-serif" => "Barlow",
						"'Barlow Condensed', sans-serif" => "Barlow Condensed",
						"'Barlow Semi Condensed', sans-serif" => "Barlow Semi Condensed",
						"'Barrio', display" => "Barrio",
						"'Basic', sans-serif" => "Basic",
						"'Battambang', display" => "Battambang",
						"'Baumans', display" => "Baumans",
						"'Bayon', display" => "Bayon",
						"'Belgrano', serif" => "Belgrano",
						"'Bellefair', serif" => "Bellefair",
						"'Belleza', sans-serif" => "Belleza",
						"'BenchNine', sans-serif" => "BenchNine",
						"'Bentham', serif" => "Bentham",
						"'Berkshire Swash', handwriting" => "Berkshire Swash",
						"'Bevan', display" => "Bevan",
						"'Bigelow Rules', display" => "Bigelow Rules",
						"'Bigshot One', display" => "Bigshot One",
						"'Bilbo', handwriting" => "Bilbo",
						"'Bilbo Swash Caps', handwriting" => "Bilbo Swash Caps",
						"'BioRhyme', serif" => "BioRhyme",
						"'BioRhyme Expanded', serif" => "BioRhyme Expanded",
						"'Biryani', sans-serif" => "Biryani",
						"'Bitter', serif" => "Bitter",
						"'Black Ops One', display" => "Black Ops One",
						"'Bokor', display" => "Bokor",
						"'Bonbon', handwriting" => "Bonbon",
						"'Boogaloo', display" => "Boogaloo",
						"'Bowlby One', display" => "Bowlby One",
						"'Bowlby One SC', display" => "Bowlby One SC",
						"'Brawler', serif" => "Brawler",
						"'Bree Serif', serif" => "Bree Serif",
						"'Bubblegum Sans', display" => "Bubblegum Sans",
						"'Bubbler One', sans-serif" => "Bubbler One",
						"'Buda', display" => "Buda",
						"'Buenard', serif" => "Buenard",
						"'Bungee', display" => "Bungee",
						"'Bungee Hairline', display" => "Bungee Hairline",
						"'Bungee Inline', display" => "Bungee Inline",
						"'Bungee Outline', display" => "Bungee Outline",
						"'Bungee Shade', display" => "Bungee Shade",
						"'Butcherman', display" => "Butcherman",
						"'Butterfly Kids', handwriting" => "Butterfly Kids",
						"'Cabin', sans-serif" => "Cabin",
						"'Cabin Condensed', sans-serif" => "Cabin Condensed",
						"'Cabin Sketch', display" => "Cabin Sketch",
						"'Caesar Dressing', display" => "Caesar Dressing",
						"'Cagliostro', sans-serif" => "Cagliostro",
						"'Cairo', sans-serif" => "Cairo",
						"'Calligraffitti', handwriting" => "Calligraffitti",
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
						"'Carter One', display" => "Carter One",
						"'Catamaran', sans-serif" => "Catamaran",
						"'Caudex', serif" => "Caudex",
						"'Caveat', handwriting" => "Caveat",
						"'Caveat Brush', handwriting" => "Caveat Brush",
						"'Cedarville Cursive', handwriting" => "Cedarville Cursive",
						"'Ceviche One', display" => "Ceviche One",
						"'Changa', sans-serif" => "Changa",
						"'Changa One', display" => "Changa One",
						"'Chango', display" => "Chango",
						"'Chathura', sans-serif" => "Chathura",
						"'Chau Philomene One', sans-serif" => "Chau Philomene One",
						"'Chela One', display" => "Chela One",
						"'Chelsea Market', display" => "Chelsea Market",
						"'Chenla', display" => "Chenla",
						"'Cherry Cream Soda', display" => "Cherry Cream Soda",
						"'Cherry Swash', display" => "Cherry Swash",
						"'Chewy', display" => "Chewy",
						"'Chicle', display" => "Chicle",
						"'Chivo', sans-serif" => "Chivo",
						"'Chonburi', display" => "Chonburi",
						"'Cinzel', serif" => "Cinzel",
						"'Cinzel Decorative', display" => "Cinzel Decorative",
						"'Clicker Script', handwriting" => "Clicker Script",
						"'Coda', display" => "Coda",
						"'Coda Caption', sans-serif" => "Coda Caption",
						"'Codystar', display" => "Codystar",
						"'Coiny', display" => "Coiny",
						"'Combo', display" => "Combo",
						"'Comfortaa', display" => "Comfortaa",
						"'Coming Soon', handwriting" => "Coming Soon",
						"'Concert One', display" => "Concert One",
						"'Condiment', handwriting" => "Condiment",
						"'Content', display" => "Content",
						"'Contrail One', display" => "Contrail One",
						"'Convergence', sans-serif" => "Convergence",
						"'Cookie', handwriting" => "Cookie",
						"'Copse', serif" => "Copse",
						"'Corben', display" => "Corben",
						"'Cormorant', serif" => "Cormorant",
						"'Cormorant Garamond', serif" => "Cormorant Garamond",
						"'Cormorant Infant', serif" => "Cormorant Infant",
						"'Cormorant SC', serif" => "Cormorant SC",
						"'Cormorant Unicase', serif" => "Cormorant Unicase",
						"'Cormorant Upright', serif" => "Cormorant Upright",
						"'Courgette', handwriting" => "Courgette",
						"'Cousine', monospace" => "Cousine",
						"'Coustard', serif" => "Coustard",
						"'Covered By Your Grace', handwriting" => "Covered By Your Grace",
						"'Crafty Girls', handwriting" => "Crafty Girls",
						"'Creepster', display" => "Creepster",
						"'Crete Round', serif" => "Crete Round",
						"'Crimson Text', serif" => "Crimson Text",
						"'Croissant One', display" => "Croissant One",
						"'Crushed', display" => "Crushed",
						"'Cuprum', sans-serif" => "Cuprum",
						"'Cutive', serif" => "Cutive",
						"'Cutive Mono', monospace" => "Cutive Mono",
						"'Damion', handwriting" => "Damion",
						"'Dancing Script', handwriting" => "Dancing Script",
						"'Dangrek', display" => "Dangrek",
						"'David Libre', serif" => "David Libre",
						"'Dawning of a New Day', handwriting" => "Dawning of a New Day",
						"'Days One', sans-serif" => "Days One",
						"'Dekko', handwriting" => "Dekko",
						"'Delius', handwriting" => "Delius",
						"'Delius Swash Caps', handwriting" => "Delius Swash Caps",
						"'Delius Unicase', handwriting" => "Delius Unicase",
						"'Della Respira', serif" => "Della Respira",
						"'Denk One', sans-serif" => "Denk One",
						"'Devonshire', handwriting" => "Devonshire",
						"'Dhurjati', sans-serif" => "Dhurjati",
						"'Didact Gothic', sans-serif" => "Didact Gothic",
						"'Diplomata', display" => "Diplomata",
						"'Diplomata SC', display" => "Diplomata SC",
						"'Domine', serif" => "Domine",
						"'Donegal One', serif" => "Donegal One",
						"'Doppio One', sans-serif" => "Doppio One",
						"'Dorsa', sans-serif" => "Dorsa",
						"'Dosis', sans-serif" => "Dosis",
						"'Dr Sugiyama', handwriting" => "Dr Sugiyama",
						"'Duru Sans', sans-serif" => "Duru Sans",
						"'Dynalight', display" => "Dynalight",
						"'EB Garamond', serif" => "EB Garamond",
						"'Eagle Lake', handwriting" => "Eagle Lake",
						"'Eater', display" => "Eater",
						"'Economica', sans-serif" => "Economica",
						"'Eczar', serif" => "Eczar",
						"'El Messiri', sans-serif" => "El Messiri",
						"'Electrolize', sans-serif" => "Electrolize",
						"'Elsie', display" => "Elsie",
						"'Elsie Swash Caps', display" => "Elsie Swash Caps",
						"'Emblema One', display" => "Emblema One",
						"'Emilys Candy', display" => "Emilys Candy",
						"'Encode Sans', sans-serif" => "Encode Sans",
						"'Encode Sans Condensed', sans-serif" => "Encode Sans Condensed",
						"'Encode Sans Expanded', sans-serif" => "Encode Sans Expanded",
						"'Encode Sans Semi Condensed', sans-serif" => "Encode Sans Semi Condensed",
						"'Encode Sans Semi Expanded', sans-serif" => "Encode Sans Semi Expanded",
						"'Engagement', handwriting" => "Engagement",
						"'Englebert', sans-serif" => "Englebert",
						"'Enriqueta', serif" => "Enriqueta",
						"'Erica One', display" => "Erica One",
						"'Esteban', serif" => "Esteban",
						"'Euphoria Script', handwriting" => "Euphoria Script",
						"'Ewert', display" => "Ewert",
						"'Exo', sans-serif" => "Exo",
						"'Exo 2', sans-serif" => "Exo 2",
						"'Expletus Sans', display" => "Expletus Sans",
						"'Fanwood Text', serif" => "Fanwood Text",
						"'Farsan', display" => "Farsan",
						"'Fascinate', display" => "Fascinate",
						"'Fascinate Inline', display" => "Fascinate Inline",
						"'Faster One', display" => "Faster One",
						"'Fasthand', serif" => "Fasthand",
						"'Fauna One', serif" => "Fauna One",
						"'Faustina', serif" => "Faustina",
						"'Federant', display" => "Federant",
						"'Federo', sans-serif" => "Federo",
						"'Felipa', handwriting" => "Felipa",
						"'Fenix', serif" => "Fenix",
						"'Finger Paint', display" => "Finger Paint",
						"'Fira Mono', monospace" => "Fira Mono",
						"'Fira Sans', sans-serif" => "Fira Sans",
						"'Fira Sans Condensed', sans-serif" => "Fira Sans Condensed",
						"'Fira Sans Extra Condensed', sans-serif" => "Fira Sans Extra Condensed",
						"'Fjalla One', sans-serif" => "Fjalla One",
						"'Fjord One', serif" => "Fjord One",
						"'Flamenco', display" => "Flamenco",
						"'Flavors', display" => "Flavors",
						"'Fondamento', handwriting" => "Fondamento",
						"'Fontdiner Swanky', display" => "Fontdiner Swanky",
						"'Forum', display" => "Forum",
						"'Francois One', sans-serif" => "Francois One",
						"'Frank Ruhl Libre', serif" => "Frank Ruhl Libre",
						"'Freckle Face', display" => "Freckle Face",
						"'Fredericka the Great', display" => "Fredericka the Great",
						"'Fredoka One', display" => "Fredoka One",
						"'Freehand', display" => "Freehand",
						"'Fresca', sans-serif" => "Fresca",
						"'Frijole', display" => "Frijole",
						"'Fruktur', display" => "Fruktur",
						"'Fugaz One', display" => "Fugaz One",
						"'GFS Didot', serif" => "GFS Didot",
						"'GFS Neohellenic', sans-serif" => "GFS Neohellenic",
						"'Gabriela', serif" => "Gabriela",
						"'Gafata', sans-serif" => "Gafata",
						"'Galada', display" => "Galada",
						"'Galdeano', sans-serif" => "Galdeano",
						"'Galindo', display" => "Galindo",
						"'Gentium Basic', serif" => "Gentium Basic",
						"'Gentium Book Basic', serif" => "Gentium Book Basic",
						"'Geo', sans-serif" => "Geo",
						"'Geostar', display" => "Geostar",
						"'Geostar Fill', display" => "Geostar Fill",
						"'Germania One', display" => "Germania One",
						"'Gidugu', sans-serif" => "Gidugu",
						"'Gilda Display', serif" => "Gilda Display",
						"'Give You Glory', handwriting" => "Give You Glory",
						"'Glass Antiqua', display" => "Glass Antiqua",
						"'Glegoo', serif" => "Glegoo",
						"'Gloria Hallelujah', handwriting" => "Gloria Hallelujah",
						"'Goblin One', display" => "Goblin One",
						"'Gochi Hand', handwriting" => "Gochi Hand",
						"'Gorditas', display" => "Gorditas",
						"'Goudy Bookletter 1911', serif" => "Goudy Bookletter 1911",
						"'Graduate', display" => "Graduate",
						"'Grand Hotel', handwriting" => "Grand Hotel",
						"'Gravitas One', display" => "Gravitas One",
						"'Great Vibes', handwriting" => "Great Vibes",
						"'Griffy', display" => "Griffy",
						"'Gruppo', display" => "Gruppo",
						"'Gudea', sans-serif" => "Gudea",
						"'Gurajada', serif" => "Gurajada",
						"'Habibi', serif" => "Habibi",
						"'Halant', serif" => "Halant",
						"'Hammersmith One', sans-serif" => "Hammersmith One",
						"'Hanalei', display" => "Hanalei",
						"'Hanalei Fill', display" => "Hanalei Fill",
						"'Handlee', handwriting" => "Handlee",
						"'Hanuman', serif" => "Hanuman",
						"'Happy Monkey', display" => "Happy Monkey",
						"'Harmattan', sans-serif" => "Harmattan",
						"'Headland One', serif" => "Headland One",
						"'Heebo', sans-serif" => "Heebo",
						"'Henny Penny', display" => "Henny Penny",
						"'Herr Von Muellerhoff', handwriting" => "Herr Von Muellerhoff",
						"'Hind', sans-serif" => "Hind",
						"'Hind Guntur', sans-serif" => "Hind Guntur",
						"'Hind Madurai', sans-serif" => "Hind Madurai",
						"'Hind Siliguri', sans-serif" => "Hind Siliguri",
						"'Hind Vadodara', sans-serif" => "Hind Vadodara",
						"'Holtwood One SC', serif" => "Holtwood One SC",
						"'Homemade Apple', handwriting" => "Homemade Apple",
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
						"'Iceberg', display" => "Iceberg",
						"'Iceland', display" => "Iceland",
						"'Imprima', sans-serif" => "Imprima",
						"'Inconsolata', monospace" => "Inconsolata",
						"'Inder', sans-serif" => "Inder",
						"'Indie Flower', handwriting" => "Indie Flower",
						"'Inika', serif" => "Inika",
						"'Inknut Antiqua', serif" => "Inknut Antiqua",
						"'Irish Grover', display" => "Irish Grover",
						"'Istok Web', sans-serif" => "Istok Web",
						"'Italiana', serif" => "Italiana",
						"'Italianno', handwriting" => "Italianno",
						"'Itim', handwriting" => "Itim",
						"'Jacques Francois', serif" => "Jacques Francois",
						"'Jacques Francois Shadow', display" => "Jacques Francois Shadow",
						"'Jaldi', sans-serif" => "Jaldi",
						"'Jim Nightshade', handwriting" => "Jim Nightshade",
						"'Jockey One', sans-serif" => "Jockey One",
						"'Jolly Lodger', display" => "Jolly Lodger",
						"'Jomhuria', display" => "Jomhuria",
						"'Josefin Sans', sans-serif" => "Josefin Sans",
						"'Josefin Slab', serif" => "Josefin Slab",
						"'Joti One', display" => "Joti One",
						"'Judson', serif" => "Judson",
						"'Julee', handwriting" => "Julee",
						"'Julius Sans One', sans-serif" => "Julius Sans One",
						"'Junge', serif" => "Junge",
						"'Jura', sans-serif" => "Jura",
						"'Just Another Hand', handwriting" => "Just Another Hand",
						"'Just Me Again Down Here', handwriting" => "Just Me Again Down Here",
						"'Kadwa', serif" => "Kadwa",
						"'Kalam', handwriting" => "Kalam",
						"'Kameron', serif" => "Kameron",
						"'Kanit', sans-serif" => "Kanit",
						"'Kantumruy', sans-serif" => "Kantumruy",
						"'Karla', sans-serif" => "Karla",
						"'Karma', serif" => "Karma",
						"'Katibeh', display" => "Katibeh",
						"'Kaushan Script', handwriting" => "Kaushan Script",
						"'Kavivanar', handwriting" => "Kavivanar",
						"'Kavoon', display" => "Kavoon",
						"'Kdam Thmor', display" => "Kdam Thmor",
						"'Keania One', display" => "Keania One",
						"'Kelly Slab', display" => "Kelly Slab",
						"'Kenia', display" => "Kenia",
						"'Khand', sans-serif" => "Khand",
						"'Khmer', display" => "Khmer",
						"'Khula', sans-serif" => "Khula",
						"'Kite One', sans-serif" => "Kite One",
						"'Knewave', display" => "Knewave",
						"'Kotta One', serif" => "Kotta One",
						"'Koulen', display" => "Koulen",
						"'Kranky', display" => "Kranky",
						"'Kreon', serif" => "Kreon",
						"'Kristi', handwriting" => "Kristi",
						"'Krona One', sans-serif" => "Krona One",
						"'Kumar One', display" => "Kumar One",
						"'Kumar One Outline', display" => "Kumar One Outline",
						"'Kurale', serif" => "Kurale",
						"'La Belle Aurore', handwriting" => "La Belle Aurore",
						"'Laila', serif" => "Laila",
						"'Lakki Reddy', handwriting" => "Lakki Reddy",
						"'Lalezar', display" => "Lalezar",
						"'Lancelot', display" => "Lancelot",
						"'Lateef', handwriting" => "Lateef",
						"'Lato', sans-serif" => "Lato",
						"'League Script', handwriting" => "League Script",
						"'Leckerli One', handwriting" => "Leckerli One",
						"'Ledger', serif" => "Ledger",
						"'Lekton', sans-serif" => "Lekton",
						"'Lemon', display" => "Lemon",
						"'Lemonada', display" => "Lemonada",
						"'Libre Baskerville', serif" => "Libre Baskerville",
						"'Libre Franklin', sans-serif" => "Libre Franklin",
						"'Life Savers', display" => "Life Savers",
						"'Lilita One', display" => "Lilita One",
						"'Lily Script One', display" => "Lily Script One",
						"'Limelight', display" => "Limelight",
						"'Linden Hill', serif" => "Linden Hill",
						"'Lobster', display" => "Lobster",
						"'Lobster Two', display" => "Lobster Two",
						"'Londrina Outline', display" => "Londrina Outline",
						"'Londrina Shadow', display" => "Londrina Shadow",
						"'Londrina Sketch', display" => "Londrina Sketch",
						"'Londrina Solid', display" => "Londrina Solid",
						"'Lora', serif" => "Lora",
						"'Love Ya Like A Sister', display" => "Love Ya Like A Sister",
						"'Loved by the King', handwriting" => "Loved by the King",
						"'Lovers Quarrel', handwriting" => "Lovers Quarrel",
						"'Luckiest Guy', display" => "Luckiest Guy",
						"'Lusitana', serif" => "Lusitana",
						"'Lustria', serif" => "Lustria",
						"'Macondo', display" => "Macondo",
						"'Macondo Swash Caps', display" => "Macondo Swash Caps",
						"'Mada', sans-serif" => "Mada",
						"'Magra', sans-serif" => "Magra",
						"'Maiden Orange', display" => "Maiden Orange",
						"'Maitree', serif" => "Maitree",
						"'Mako', sans-serif" => "Mako",
						"'Mallanna', sans-serif" => "Mallanna",
						"'Mandali', sans-serif" => "Mandali",
						"'Manuale', serif" => "Manuale",
						"'Marcellus', serif" => "Marcellus",
						"'Marcellus SC', serif" => "Marcellus SC",
						"'Marck Script', handwriting" => "Marck Script",
						"'Margarine', display" => "Margarine",
						"'Marko One', serif" => "Marko One",
						"'Marmelad', sans-serif" => "Marmelad",
						"'Martel', serif" => "Martel",
						"'Martel Sans', sans-serif" => "Martel Sans",
						"'Marvel', sans-serif" => "Marvel",
						"'Mate', serif" => "Mate",
						"'Mate SC', serif" => "Mate SC",
						"'Maven Pro', sans-serif" => "Maven Pro",
						"'McLaren', display" => "McLaren",
						"'Meddon', handwriting" => "Meddon",
						"'MedievalSharp', display" => "MedievalSharp",
						"'Medula One', display" => "Medula One",
						"'Meera Inimai', sans-serif" => "Meera Inimai",
						"'Megrim', display" => "Megrim",
						"'Meie Script', handwriting" => "Meie Script",
						"'Merienda', handwriting" => "Merienda",
						"'Merienda One', handwriting" => "Merienda One",
						"'Merriweather', serif" => "Merriweather",
						"'Merriweather Sans', sans-serif" => "Merriweather Sans",
						"'Metal', display" => "Metal",
						"'Metal Mania', display" => "Metal Mania",
						"'Metamorphous', display" => "Metamorphous",
						"'Metrophobic', sans-serif" => "Metrophobic",
						"'Michroma', sans-serif" => "Michroma",
						"'Milonga', display" => "Milonga",
						"'Miltonian', display" => "Miltonian",
						"'Miltonian Tattoo', display" => "Miltonian Tattoo",
						"'Miniver', display" => "Miniver",
						"'Miriam Libre', sans-serif" => "Miriam Libre",
						"'Mirza', display" => "Mirza",
						"'Miss Fajardose', handwriting" => "Miss Fajardose",
						"'Mitr', sans-serif" => "Mitr",
						"'Modak', display" => "Modak",
						"'Modern Antiqua', display" => "Modern Antiqua",
						"'Mogra', display" => "Mogra",
						"'Molengo', sans-serif" => "Molengo",
						"'Molle', handwriting" => "Molle",
						"'Monda', sans-serif" => "Monda",
						"'Monofett', display" => "Monofett",
						"'Monoton', display" => "Monoton",
						"'Monsieur La Doulaise', handwriting" => "Monsieur La Doulaise",
						"'Montaga', serif" => "Montaga",
						"'Montez', handwriting" => "Montez",
						"'Montserrat', sans-serif" => "Montserrat",
						"'Montserrat Alternates', sans-serif" => "Montserrat Alternates",
						"'Montserrat Subrayada', sans-serif" => "Montserrat Subrayada",
						"'Moul', display" => "Moul",
						"'Moulpali', display" => "Moulpali",
						"'Mountains of Christmas', display" => "Mountains of Christmas",
						"'Mouse Memoirs', sans-serif" => "Mouse Memoirs",
						"'Mr Bedfort', handwriting" => "Mr Bedfort",
						"'Mr Dafoe', handwriting" => "Mr Dafoe",
						"'Mr De Haviland', handwriting" => "Mr De Haviland",
						"'Mrs Saint Delafield', handwriting" => "Mrs Saint Delafield",
						"'Mrs Sheppards', handwriting" => "Mrs Sheppards",
						"'Mukta', sans-serif" => "Mukta",
						"'Mukta Mahee', sans-serif" => "Mukta Mahee",
						"'Mukta Malar', sans-serif" => "Mukta Malar",
						"'Mukta Vaani', sans-serif" => "Mukta Vaani",
						"'Muli', sans-serif" => "Muli",
						"'Mystery Quest', display" => "Mystery Quest",
						"'NTR', sans-serif" => "NTR",
						"'Neucha', handwriting" => "Neucha",
						"'Neuton', serif" => "Neuton",
						"'New Rocker', display" => "New Rocker",
						"'News Cycle', sans-serif" => "News Cycle",
						"'Niconne', handwriting" => "Niconne",
						"'Nixie One', display" => "Nixie One",
						"'Nobile', sans-serif" => "Nobile",
						"'Nokora', serif" => "Nokora",
						"'Norican', handwriting" => "Norican",
						"'Nosifer', display" => "Nosifer",
						"'Nothing You Could Do', handwriting" => "Nothing You Could Do",
						"'Noticia Text', serif" => "Noticia Text",
						"'Noto Sans', sans-serif" => "Noto Sans",
						"'Noto Serif', serif" => "Noto Serif",
						"'Nova Cut', display" => "Nova Cut",
						"'Nova Flat', display" => "Nova Flat",
						"'Nova Mono', monospace" => "Nova Mono",
						"'Nova Oval', display" => "Nova Oval",
						"'Nova Round', display" => "Nova Round",
						"'Nova Script', display" => "Nova Script",
						"'Nova Slim', display" => "Nova Slim",
						"'Nova Square', display" => "Nova Square",
						"'Numans', sans-serif" => "Numans",
						"'Nunito', sans-serif" => "Nunito",
						"'Nunito Sans', sans-serif" => "Nunito Sans",
						"'Odor Mean Chey', display" => "Odor Mean Chey",
						"'Offside', display" => "Offside",
						"'Old Standard TT', serif" => "Old Standard TT",
						"'Oldenburg', display" => "Oldenburg",
						"'Oleo Script', display" => "Oleo Script",
						"'Oleo Script Swash Caps', display" => "Oleo Script Swash Caps",
						"'Open Sans', sans-serif" => "Open Sans",
						"'Open Sans Condensed', sans-serif" => "Open Sans Condensed",
						"'Oranienbaum', serif" => "Oranienbaum",
						"'Orbitron', sans-serif" => "Orbitron",
						"'Oregano', display" => "Oregano",
						"'Orienta', sans-serif" => "Orienta",
						"'Original Surfer', display" => "Original Surfer",
						"'Oswald', sans-serif" => "Oswald",
						"'Over the Rainbow', handwriting" => "Over the Rainbow",
						"'Overlock', display" => "Overlock",
						"'Overlock SC', display" => "Overlock SC",
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
						"'Pacifico', handwriting" => "Pacifico",
						"'Padauk', sans-serif" => "Padauk",
						"'Palanquin', sans-serif" => "Palanquin",
						"'Palanquin Dark', sans-serif" => "Palanquin Dark",
						"'Pangolin', handwriting" => "Pangolin",
						"'Paprika', display" => "Paprika",
						"'Parisienne', handwriting" => "Parisienne",
						"'Passero One', display" => "Passero One",
						"'Passion One', display" => "Passion One",
						"'Pathway Gothic One', sans-serif" => "Pathway Gothic One",
						"'Patrick Hand', handwriting" => "Patrick Hand",
						"'Patrick Hand SC', handwriting" => "Patrick Hand SC",
						"'Pattaya', sans-serif" => "Pattaya",
						"'Patua One', display" => "Patua One",
						"'Pavanam', sans-serif" => "Pavanam",
						"'Paytone One', sans-serif" => "Paytone One",
						"'Peddana', serif" => "Peddana",
						"'Peralta', display" => "Peralta",
						"'Permanent Marker', handwriting" => "Permanent Marker",
						"'Petit Formal Script', handwriting" => "Petit Formal Script",
						"'Petrona', serif" => "Petrona",
						"'Philosopher', sans-serif" => "Philosopher",
						"'Piedra', display" => "Piedra",
						"'Pinyon Script', handwriting" => "Pinyon Script",
						"'Pirata One', display" => "Pirata One",
						"'Plaster', display" => "Plaster",
						"'Play', sans-serif" => "Play",
						"'Playball', display" => "Playball",
						"'Playfair Display', serif" => "Playfair Display",
						"'Playfair Display SC', serif" => "Playfair Display SC",
						"'Podkova', serif" => "Podkova",
						"'Poiret One', display" => "Poiret One",
						"'Poller One', display" => "Poller One",
						"'Poly', serif" => "Poly",
						"'Pompiere', display" => "Pompiere",
						"'Pontano Sans', sans-serif" => "Pontano Sans",
						"'Poppins', sans-serif" => "Poppins",
						"'Port Lligat Sans', sans-serif" => "Port Lligat Sans",
						"'Port Lligat Slab', serif" => "Port Lligat Slab",
						"'Pragati Narrow', sans-serif" => "Pragati Narrow",
						"'Prata', serif" => "Prata",
						"'Preahvihear', display" => "Preahvihear",
						"'Press Start 2P', display" => "Press Start 2P",
						"'Pridi', serif" => "Pridi",
						"'Princess Sofia', handwriting" => "Princess Sofia",
						"'Prociono', serif" => "Prociono",
						"'Prompt', sans-serif" => "Prompt",
						"'Prosto One', display" => "Prosto One",
						"'Proza Libre', sans-serif" => "Proza Libre",
						"'Puritan', sans-serif" => "Puritan",
						"'Purple Purse', display" => "Purple Purse",
						"'Quando', serif" => "Quando",
						"'Quantico', sans-serif" => "Quantico",
						"'Quattrocento', serif" => "Quattrocento",
						"'Quattrocento Sans', sans-serif" => "Quattrocento Sans",
						"'Questrial', sans-serif" => "Questrial",
						"'Quicksand', sans-serif" => "Quicksand",
						"'Quintessential', handwriting" => "Quintessential",
						"'Qwigley', handwriting" => "Qwigley",
						"'Racing Sans One', display" => "Racing Sans One",
						"'Radley', serif" => "Radley",
						"'Rajdhani', sans-serif" => "Rajdhani",
						"'Rakkas', display" => "Rakkas",
						"'Raleway', sans-serif" => "Raleway",
						"'Raleway Dots', display" => "Raleway Dots",
						"'Ramabhadra', sans-serif" => "Ramabhadra",
						"'Ramaraja', serif" => "Ramaraja",
						"'Rambla', sans-serif" => "Rambla",
						"'Rammetto One', display" => "Rammetto One",
						"'Ranchers', display" => "Ranchers",
						"'Rancho', handwriting" => "Rancho",
						"'Ranga', display" => "Ranga",
						"'Rasa', serif" => "Rasa",
						"'Rationale', sans-serif" => "Rationale",
						"'Ravi Prakash', display" => "Ravi Prakash",
						"'Redressed', handwriting" => "Redressed",
						"'Reem Kufi', sans-serif" => "Reem Kufi",
						"'Reenie Beanie', handwriting" => "Reenie Beanie",
						"'Revalia', display" => "Revalia",
						"'Rhodium Libre', serif" => "Rhodium Libre",
						"'Ribeye', display" => "Ribeye",
						"'Ribeye Marrow', display" => "Ribeye Marrow",
						"'Righteous', display" => "Righteous",
						"'Risque', display" => "Risque",
						"'Roboto', sans-serif" => "Roboto",
						"'Roboto Condensed', sans-serif" => "Roboto Condensed",
						"'Roboto Mono', monospace" => "Roboto Mono",
						"'Roboto Slab', serif" => "Roboto Slab",
						"'Rochester', handwriting" => "Rochester",
						"'Rock Salt', handwriting" => "Rock Salt",
						"'Rokkitt', serif" => "Rokkitt",
						"'Romanesco', handwriting" => "Romanesco",
						"'Ropa Sans', sans-serif" => "Ropa Sans",
						"'Rosario', sans-serif" => "Rosario",
						"'Rosarivo', serif" => "Rosarivo",
						"'Rouge Script', handwriting" => "Rouge Script",
						"'Rozha One', serif" => "Rozha One",
						"'Rubik', sans-serif" => "Rubik",
						"'Rubik Mono One', sans-serif" => "Rubik Mono One",
						"'Ruda', sans-serif" => "Ruda",
						"'Rufina', serif" => "Rufina",
						"'Ruge Boogie', handwriting" => "Ruge Boogie",
						"'Ruluko', sans-serif" => "Ruluko",
						"'Rum Raisin', sans-serif" => "Rum Raisin",
						"'Ruslan Display', display" => "Ruslan Display",
						"'Russo One', sans-serif" => "Russo One",
						"'Ruthie', handwriting" => "Ruthie",
						"'Rye', display" => "Rye",
						"'Sacramento', handwriting" => "Sacramento",
						"'Sahitya', serif" => "Sahitya",
						"'Sail', display" => "Sail",
						"'Saira', sans-serif" => "Saira",
						"'Saira Condensed', sans-serif" => "Saira Condensed",
						"'Saira Extra Condensed', sans-serif" => "Saira Extra Condensed",
						"'Saira Semi Condensed', sans-serif" => "Saira Semi Condensed",
						"'Salsa', display" => "Salsa",
						"'Sanchez', serif" => "Sanchez",
						"'Sancreek', display" => "Sancreek",
						"'Sansita', sans-serif" => "Sansita",
						"'Sarala', sans-serif" => "Sarala",
						"'Sarina', display" => "Sarina",
						"'Sarpanch', sans-serif" => "Sarpanch",
						"'Satisfy', handwriting" => "Satisfy",
						"'Scada', sans-serif" => "Scada",
						"'Scheherazade', serif" => "Scheherazade",
						"'Schoolbell', handwriting" => "Schoolbell",
						"'Scope One', serif" => "Scope One",
						"'Seaweed Script', display" => "Seaweed Script",
						"'Secular One', sans-serif" => "Secular One",
						"'Sedgwick Ave', handwriting" => "Sedgwick Ave",
						"'Sedgwick Ave Display', handwriting" => "Sedgwick Ave Display",
						"'Sevillana', display" => "Sevillana",
						"'Seymour One', sans-serif" => "Seymour One",
						"'Shadows Into Light', handwriting" => "Shadows Into Light",
						"'Shadows Into Light Two', handwriting" => "Shadows Into Light Two",
						"'Shanti', sans-serif" => "Shanti",
						"'Share', display" => "Share",
						"'Share Tech', sans-serif" => "Share Tech",
						"'Share Tech Mono', monospace" => "Share Tech Mono",
						"'Shojumaru', display" => "Shojumaru",
						"'Short Stack', handwriting" => "Short Stack",
						"'Shrikhand', display" => "Shrikhand",
						"'Siemreap', display" => "Siemreap",
						"'Sigmar One', display" => "Sigmar One",
						"'Signika', sans-serif" => "Signika",
						"'Signika Negative', sans-serif" => "Signika Negative",
						"'Simonetta', display" => "Simonetta",
						"'Sintony', sans-serif" => "Sintony",
						"'Sirin Stencil', display" => "Sirin Stencil",
						"'Six Caps', sans-serif" => "Six Caps",
						"'Skranji', display" => "Skranji",
						"'Slabo 13px', serif" => "Slabo 13px",
						"'Slabo 27px', serif" => "Slabo 27px",
						"'Slackey', display" => "Slackey",
						"'Smokum', display" => "Smokum",
						"'Smythe', display" => "Smythe",
						"'Sniglet', display" => "Sniglet",
						"'Snippet', sans-serif" => "Snippet",
						"'Snowburst One', display" => "Snowburst One",
						"'Sofadi One', display" => "Sofadi One",
						"'Sofia', handwriting" => "Sofia",
						"'Sonsie One', display" => "Sonsie One",
						"'Sorts Mill Goudy', serif" => "Sorts Mill Goudy",
						"'Source Code Pro', monospace" => "Source Code Pro",
						"'Source Sans Pro', sans-serif" => "Source Sans Pro",
						"'Source Serif Pro', serif" => "Source Serif Pro",
						"'Space Mono', monospace" => "Space Mono",
						"'Special Elite', display" => "Special Elite",
						"'Spectral', serif" => "Spectral",
						"'Spectral SC', serif" => "Spectral SC",
						"'Spicy Rice', display" => "Spicy Rice",
						"'Spinnaker', sans-serif" => "Spinnaker",
						"'Spirax', display" => "Spirax",
						"'Squada One', display" => "Squada One",
						"'Sree Krushnadevaraya', serif" => "Sree Krushnadevaraya",
						"'Sriracha', handwriting" => "Sriracha",
						"'Stalemate', handwriting" => "Stalemate",
						"'Stalinist One', display" => "Stalinist One",
						"'Stardos Stencil', display" => "Stardos Stencil",
						"'Stint Ultra Condensed', display" => "Stint Ultra Condensed",
						"'Stint Ultra Expanded', display" => "Stint Ultra Expanded",
						"'Stoke', serif" => "Stoke",
						"'Strait', sans-serif" => "Strait",
						"'Sue Ellen Francisco', handwriting" => "Sue Ellen Francisco",
						"'Suez One', serif" => "Suez One",
						"'Sumana', serif" => "Sumana",
						"'Sunshiney', handwriting" => "Sunshiney",
						"'Supermercado One', display" => "Supermercado One",
						"'Sura', serif" => "Sura",
						"'Suranna', serif" => "Suranna",
						"'Suravaram', serif" => "Suravaram",
						"'Suwannaphum', display" => "Suwannaphum",
						"'Swanky and Moo Moo', handwriting" => "Swanky and Moo Moo",
						"'Syncopate', sans-serif" => "Syncopate",
						"'Tangerine', handwriting" => "Tangerine",
						"'Taprom', display" => "Taprom",
						"'Tauri', sans-serif" => "Tauri",
						"'Taviraj', serif" => "Taviraj",
						"'Teko', sans-serif" => "Teko",
						"'Telex', sans-serif" => "Telex",
						"'Tenali Ramakrishna', sans-serif" => "Tenali Ramakrishna",
						"'Tenor Sans', sans-serif" => "Tenor Sans",
						"'Text Me One', sans-serif" => "Text Me One",
						"'The Girl Next Door', handwriting" => "The Girl Next Door",
						"'Tienne', serif" => "Tienne",
						"'Tillana', handwriting" => "Tillana",
						"'Timmana', sans-serif" => "Timmana",
						"'Tinos', serif" => "Tinos",
						"'Titan One', display" => "Titan One",
						"'Titillium Web', sans-serif" => "Titillium Web",
						"'Trade Winds', display" => "Trade Winds",
						"'Trirong', serif" => "Trirong",
						"'Trocchi', serif" => "Trocchi",
						"'Trochut', display" => "Trochut",
						"'Trykker', serif" => "Trykker",
						"'Tulpen One', display" => "Tulpen One",
						"'Ubuntu', sans-serif" => "Ubuntu",
						"'Ubuntu Condensed', sans-serif" => "Ubuntu Condensed",
						"'Ubuntu Mono', monospace" => "Ubuntu Mono",
						"'Ultra', serif" => "Ultra",
						"'Uncial Antiqua', display" => "Uncial Antiqua",
						"'Underdog', display" => "Underdog",
						"'Unica One', display" => "Unica One",
						"'UnifrakturCook', display" => "UnifrakturCook",
						"'UnifrakturMaguntia', display" => "UnifrakturMaguntia",
						"'Unkempt', display" => "Unkempt",
						"'Unlock', display" => "Unlock",
						"'Unna', serif" => "Unna",
						"'VT323', monospace" => "VT323",
						"'Vampiro One', display" => "Vampiro One",
						"'Varela', sans-serif" => "Varela",
						"'Varela Round', sans-serif" => "Varela Round",
						"'Vast Shadow', display" => "Vast Shadow",
						"'Vesper Libre', serif" => "Vesper Libre",
						"'Vibur', handwriting" => "Vibur",
						"'Vidaloka', serif" => "Vidaloka",
						"'Viga', sans-serif" => "Viga",
						"'Voces', display" => "Voces",
						"'Volkhov', serif" => "Volkhov",
						"'Vollkorn', serif" => "Vollkorn",
						"'Vollkorn SC', serif" => "Vollkorn SC",
						"'Voltaire', sans-serif" => "Voltaire",
						"'Waiting for the Sunrise', handwriting" => "Waiting for the Sunrise",
						"'Wallpoet', display" => "Wallpoet",
						"'Walter Turncoat', handwriting" => "Walter Turncoat",
						"'Warnes', display" => "Warnes",
						"'Wellfleet', display" => "Wellfleet",
						"'Wendy One', sans-serif" => "Wendy One",
						"'Wire One', sans-serif" => "Wire One",
						"'Work Sans', sans-serif" => "Work Sans",
						"'Yanone Kaffeesatz', sans-serif" => "Yanone Kaffeesatz",
						"'Yantramanav', sans-serif" => "Yantramanav",
						"'Yatra One', display" => "Yatra One",
						"'Yellowtail', handwriting" => "Yellowtail",
						"'Yeseva One', display" => "Yeseva One",
						"'Yesteryear', handwriting" => "Yesteryear",
						"'Yrsa', serif" => "Yrsa",
						"'Zeyada', handwriting" => "Zeyada",
						"'Zilla Slab', serif" => "Zilla Slab",
						"'Zilla Slab Highlight', display" => "Zilla Slab Highlight"
					),
					"inherit",
					'Set an font family.'
				)."
				
				
				".yp_get_select_markup(
					'font-weight',
					'Font Weight',
					array(
						'300' => 'Light'.' 300',
						'400' => 'normal'.' 400',
						'500' => 'Semi-Bold'.' 500',
						'600' => 'Bold'.' 600',
						'700' => 'Extra-Bold'.' 700'
					),
					"normal",
					'Sets how thick or thin characters in text should be displayed.'
				)."
	
				".yp_get_color_markup(
					'color',
					'Color',
					'Set the text color.'
				)."

				".yp_get_select_markup(
					'text-shadow',
					'Text Shadow',
					array(
						'none' => 'none',
						'rgba(0, 0, 0, 0.3) 0px 1px 1px' => 'Basic Shadow',
						'rgb(255, 255, 255) 1px 1px 0px, rgb(170, 170, 170) 2px 2px 0px' => 'Shadow Multiple',
						'rgb(255, 0, 0) -1px 0px 0px, rgb(0, 255, 255) 1px 0px 0px' => 'Anaglyph',
						'rgb(255, 255, 255) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px' => 'Emboss',
						'rgb(255, 255, 255) 0px 0px 2px, rgb(255, 255, 255) 0px 0px 4px, rgb(255, 255, 255) 0px 0px 6px, rgb(255, 119, 255) 0px 0px 8px, rgb(255, 0, 255) 0px 0px 12px, rgb(255, 0, 255) 0px 0px 16px, rgb(255, 0, 255) 0px 0px 20px, rgb(255, 0, 255) 0px 0px 24px' => 'Neon',
						'rgb(0, 0, 0) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px, rgb(0, 0, 0) 1px 0px 1px, rgb(0, 0, 0) -1px 0px 1px' => 'Outline'
					),
					"none",
					'Adds shadow to text.'
				)."

				".yp_get_slider_markup(
					'font-size',
					'Font Size',
					"inherit",
					0,        // decimals
					'8,100',   // px value
					'0,100',  // percentage value
					'1,6',     // Em value
					'Sets the size of a font.'
				)."
				
				".yp_get_slider_markup(
					'line-height',
					'Line Height',
					"inherit",
					1,        // decimals
					'0,100',   // px value
					'0,100',  // percentage value
					'1,6',     // Em value,
					'Set the leading.'
				)."
				
				".yp_get_radio_markup(
					'font-style',
					'Font Style',
					array(
						'normal' => 'Normal',
						'italic' => 'Italic'
					),
					"normal",
					'Specifies the font style for a text.'
				)."

				".yp_get_radio_markup(
					'text-align',
					'Text Align',
					array(
						'left' => 'left',
						'center' => 'center',
						'right' => 'right',
						'justify' => 'justify'
					),
					"start",
					'Specifies the horizontal alignment of text in an element.'
				)."
				
				".yp_get_radio_markup(
					'text-transform',
					'Text Transform',
					array(
						'uppercase' => 'uppercase',
						'lowercase' => 'lowercase',
						'capitalize' => 'capitalize'
					),
					"none",
					'Controls the capitalization of text.'					
				)."
			
				
				".yp_get_slider_markup(
					'letter-spacing',
					'Letter Spacing',
					"normal",
					1,        // decimals
					'-5,10',   // px value
					'0,100',  // percentage value
					'-1,3',     // Em value
					'Increases or decreases the space between characters in a text.'
				)."
				
				".yp_get_slider_markup(
					'word-spacing',
					'Word Spacing',
					"normal",
					1,        // decimals
					'-5,20',   // px value
					'0,100',  // percentage value
					'-1,3',     // Em value,
					'increases or decreases the white space between words.'
				)."

				".yp_get_radio_markup(
					'text-decoration',
					'Text Decoration',
					array(
						'overline' => 'overline',
						'line-through' => 'line-through',
						'underline' => 'underline'
					),
					"none",
					'Specifies the decoration added to text.'
				)."

				".yp_get_slider_markup(
					'text-indent',
					'Text Indent',
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-100,100',  // percentage value
					'-15,15',     // Em value
					'Specifies the indentation of the first line in a text-block.'
				)."

				".yp_get_radio_markup(
					'word-wrap',
					'Word Wrap',
					array(
						'normal' => 'normal',
						'break-word' => 'break-word'
					),
					"normal",
					'Allows long words to be able to be broken and wrap onto the next line.'
				)."
				
			</div>
		</li>
		
		<li class='background-option'>
			<h3>Background ".$arrow_icon."</h3>
			<div class='yp-this-content'>
				
				".yp_get_color_markup(
					'background-color',
					'Background Color',
					'Sets the background color of an element.'
				)."
				
				".yp_get_input_markup(
					'background-image',
					'Background Image',
					"none",
					'Sets background image for an element.'
				)."

				".yp_get_radio_markup(
					'background-clip',
					'Background Clip',
					array(
						'text' => 'text',
						'padding-box' => 'padding-box',
						'content-box' => 'content-box'
					),
					"border-box",
					"defines how far the background should extend within the element."
				)."

				".yp_get_radio_markup(
					'background-blend-mode',
					'BG. Blend Mode',
					array(
						'multiply' => 'multiply',
						'darken' => 'darken',
						'luminosity' => 'luminosity'
					),
					"normal",
					'Defines the blending mode of background color and image.'
				)."

				".yp_get_select_markup(
					'background-position',
					'BG. Position',
					array(
						'0% 0%' => 'left top',
						'0% 50%' => 'left center',
						'0% 100%' => 'left bottom',
						'100% 0%' => 'right top',
						'100% 50%' => 'right center',
						'100% 100%' => 'right bottom',
						'50% 0%' => 'center top',
						'50% 50%' => 'center center',
						'50% 100%' => 'center bottom'
					),
					'0% 0%',
					'Sets the starting position of a background image.'
				)."

				".yp_get_radio_markup(
					'background-size',
					'Background Size',
					array(
						'length' => 'length',
						'cover' => 'cover',
						'contain' => 'contain'
					),
					"auto auto",
					'The size of the background image.'
				)."				
				
				".yp_get_radio_markup(
					'background-repeat',
					'Background Repeat',
					array(
						'repeat-x' => 'repeat-x',
						'repeat-y' => 'repeat-y',
						'no-repeat' => 'no-repeat'
					),
					"repeat",
					'Sets if background image will be repeated.'
				)."
				
				".yp_get_radio_markup(
					'background-attachment',
					'BG. Attachment',
					array(
						'fixed' => 'fixed',
						'local' => 'local'
					),
					"scroll",
					'Sets whether a background image is fixed or scrolls with the rest of the page.'
				)."				
				
			</div>
		</li>
		
		<li class='margin-option'>
			<h3>Margin ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				<div class='lock-btn'></div>

				".yp_get_slider_markup(
					'margin-left',
					'Margin Left',
					"auto",
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value,
					'Sets the left margin of an element.'
				)."
				
				".yp_get_slider_markup(
					'margin-right',
					'Margin Right',
					"auto",
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					'Sets the right margin of an element.'
				)."

				".yp_get_slider_markup(
					'margin-top',
					'Margin Top',
					'0',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					'Sets the top margin of an element.'
				)."
				
				".yp_get_slider_markup(
					'margin-bottom',
					'Margin Bottom',
					'0',
					0,        // decimals
					'-50,200',   // px value
					'-100,100',  // percentage value
					'-6,26',     // Em value
					'Sets the bottom margin of an element.'
				)."
				
				
				
			</div>
		</li>
		
		<li class='padding-option'>
			<h3>Padding ".$arrow_icon."</h3>
			<div class='yp-this-content'>
				
				<div class='lock-btn'></div>

				".yp_get_slider_markup(
					'padding-left',
					'Padding Left',
					'0',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					'Sets the left padding (space) of an element.'
				)."

				".yp_get_slider_markup(
					'padding-right',
					'Padding Right',
					'0',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					'Sets the right padding (space) of an element.'
				)."

				".yp_get_slider_markup(
					'padding-top',
					'Padding Top',
					'0',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					'Sets the top padding (space) of an element.'
				)."
				
				".yp_get_slider_markup(
					'padding-bottom',
					'Padding Bottom',
					'0',
					0,        // decimals
					'0,200',   // px value
					'0,100',  // percentage value
					'0,26',     // Em value
					'Sets the bottom padding (space) of an element.'
				)."

				
				
			
			</div>
		</li>

		
		<li class='border-option'>
			<h3>Border ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_radio_markup(
					'border-type',
					'Border Type',
					array(
						'all' => 'all',
						'top' => 'top',
						'right' => 'right',
						'bottom' => 'bottom',
						'left' => 'left'
					),
					"none",
					'Select the border you want to edit.'
				)."
				
				<div class='yp-border-all-section'>

					".yp_get_radio_markup(
						'border-style',
						'Border Style',
						array(
							'solid' => 'solid',
							'dotted' => 'dotted',
							'dashed' => 'dashed',
							'hidden' => 'hidden'
						),
						"none",
						'Sets the style of an element four borders.'
					)."
					
					
					".yp_get_slider_markup(
						'border-width',
						'Border Width',
						'medium',
						0,        // decimals
						'0,20',   // px value
						'0,100',  // percentage value
						'0,3',     // Em value
						'Sets the width of an element four borders.'
					)."

					".yp_get_color_markup(
						'border-color',
						'Border Color',
						'Sets the color of an element four borders.'
					)."

				</div>
				
				<div class='yp-border-top-section'>

					".yp_get_radio_markup(
						'border-top-style',
						'Border Top Style',
						array(
							'solid' => 'solid',
							'dotted' => 'dotted',
							'dashed' => 'dashed',
							'hidden' => 'hidden'
						),
						"none",
						'Sets the style of an element top border.'
					)."
					
					".yp_get_slider_markup(
						'border-top-width',
						'Border Top Width',
						'medium',
						0,        // decimals
						'0,20',   // px value
						'0,100',  // percentage value
						'0,3',     // Em value
						'Sets the width of an element top border.'
					)."

					".yp_get_color_markup(
						'border-top-color',
						'Border Top Color',
						'Sets the color of an element top border.'
					)."

				</div>
				
				<div class='yp-border-right-section'>

					".yp_get_radio_markup(
						'border-right-style',
						'Border Right Style',
						array(
							'solid' => 'solid',
							'dotted' => 'dotted',
							'dashed' => 'dashed',
							'hidden' => 'hidden'
						),
						"none",
						'Sets the style of an element right border.'
					)."
					
					".yp_get_slider_markup(
						'border-right-width',
						'Border Right Width',
						'medium',
						0,        // decimals
						'0,20',   // px value
						'0,100',  // percentage value
						'0,3',     // Em value
						'Sets the width of an element right border.'
					)."

					".yp_get_color_markup(
						'border-right-color',
						'Border Right Color',
						'Sets the color of an element right border.'
					)."

				</div>
				
				
				<div class='yp-border-bottom-section'>
				
					".yp_get_radio_markup(
						'border-bottom-style',
						'Border Bottom Style',
						array(
							'solid' => 'solid',
							'dotted' => 'dotted',
							'dashed' => 'dashed',
							'hidden' => 'hidden'
						),
						"none",
						'Sets the style of an element bottom border.'
					)."
					
					".yp_get_slider_markup(
						'border-bottom-width',
						'Border Bottom Width',
						'medium',
						0,        // decimals
						'0,20',   // px value
						'0,100',  // percentage value
						'0,3',     // Em value
						'Sets the width of an element bottom border.'
					)."

					".yp_get_color_markup(
						'border-bottom-color',
						'Border Bottom Color',
						'Sets the color of an element bottom border.'
					)."

				</div>
				
				
				<div class='yp-border-left-section'>

					".yp_get_radio_markup(
						'border-left-style',
						'Border Left Style',
						array(
							'solid' => 'solid',
							'dotted' => 'dotted',
							'dashed' => 'dashed',
							'hidden' => 'hidden'
						),
						"none",
						'Sets the style of an element left border.'
					)."
					
					".yp_get_slider_markup(
						'border-left-width',
						'Border Left Width',
						'medium',
						0,        // decimals
						'0,20',   // px value
						'0,100',  // percentage value
						'0,3',     // Em value
						'Sets the width of an element left border.'
					)."

					".yp_get_color_markup(
						'border-left-color',
						'Border Left Color',
						'Sets the color of an element left border.'
					)."
				
				</div>
				
			</div>
		</li>
		
		<li class='border-radius-option'>
			<h3>Border Radius ".$arrow_icon."</h3>
			<div class='yp-this-content'>
				
				<div class='lock-btn'></div>
				".yp_get_slider_markup(
					'border-top-left-radius',
					'Top Left Radius',
					'0',
					"0",        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					'Defines the radius of the top-left corner.'
				)."
				
				".yp_get_slider_markup(
					'border-top-right-radius',
					'Top Right Radius',
					'0',
					"0",        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					'Defines the radius of the top-right corner.'
				)."
				
				".yp_get_slider_markup(
					'border-bottom-right-radius',
					'Bottom Right Radius',
					'0',
					"0",        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					'Defines the radius of the bottom-right corner.'
				)."

				".yp_get_slider_markup(
					'border-bottom-left-radius',
					'Bottom Left Radius',
					'0',
					"0",        // decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,6',     // Em value
					'Defines the radius of the bottom-left corner.'
				)."
				
				
			</div>
		</li>
		
		<li class='position-option'>
			<h3>Position ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_slider_markup(
					'z-index',
					'Z Index',
					"auto",
					0,        // decimals
					'-10,1000',   // px value
					'-10,1000',  // percentage value
					'-10,1000',     // Em value
					'Specifies the stack order of an element. Z index only works on positioned elements (absolute, relative, or fixed).'
				)."	
				
				".yp_get_radio_markup(
					'position',
					'Position',
					array(
						'relative' => 'relative',
						'absolute' => 'absolute',
						'fixed' => 'fixed'
					),
					"static",
					'Specifies the type of positioning method used for an element.'
					
				)."
				
				".yp_get_slider_markup(
					'top',
					'Top',
					"auto",
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					'For absolutely: positioned elements, the top property sets the top edge of an element to a unit above/below the top edge of its containing element.<br><br>For relatively: positioned elements, the top property sets the top edge of an element to a unit above/below its normal position.'
				)."

				".yp_get_slider_markup(
					'left',
					'Left',
					"auto",
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					'For absolutely: positioned elements, the left property sets the left edge of an element to a unit to the left/right of the left edge of its containing element.<br><br>For relatively: positioned elements, the left property sets the left edge of an element to a unit to the left/right to its normal position.'
				)."

				".yp_get_slider_markup(
					'bottom',
					'Bottom',
					"auto",
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					'For absolutely: positioned elements, the bottom property sets the bottom edge of an element to a unit above/below the bottom edge of its containing element.<br><br>For relatively: positioned elements, the bottom property sets the bottom edge of an element to a unit above/below its normal position.'
				)."
				
				".yp_get_slider_markup(
					'right',
					'Right',
					"auto",
					0,        // decimals
					'-200,400',   // px value
					'0,100',  // percentage value
					'-12,12',     // Em value
					'For absolutely: positioned elements, the right property sets the right edge of an element to a unit to the left/right of the right edge of its containing element.<br><br>For relatively: positioned elements, the right property sets the right edge of an element to a unit to the left/right to its normal position.'
				)."
				
			</div>
		</li>
		
		<li class='size-option'>
			<h3>Size <span class='yp-badge yp-lite'>Pro</span> ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_slider_markup(
					'width',
					'Width',
					"auto",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Sets the width of an element.'
				)."
				
				".yp_get_slider_markup(
					'height',
					'Height',
					"auto",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Sets the height of an element'
				)."

				".yp_get_radio_markup(
					'box-sizing',
					'Box Sizing',
					array(
						'border-box' => 'border-box',
						'content-box' => 'content-box'
					),
					false,
					'Defines how the width and height of an element are calculated: should they include padding and borders, or not.'
				)."
				
				".yp_get_slider_markup(
					'min-width',
					'Min Width',
					"initial",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Set the minimum width of an element.'
				)."

				".yp_get_slider_markup(
					'min-height',
					'Min Height',
					"initial",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',    // Em value
					'Set the minimum height of an element.'
				)."
				
				".yp_get_slider_markup(
					'max-width',
					'Max Width',
					"none",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Set the maximum width of an element.'
				)."
				
				".yp_get_slider_markup(
					'max-height',
					'Max Height',
					"none",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Set the maximum height of an element.'
				)."
				
				
			</div>
		</li>

		<li class='flex-option'>
			<h3>Flexbox ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_radio_markup(
					'flex-direction',
					'Flex Direction',
					array(
						'row-reverse' => 'row-reverse',
						'column' => 'column',
						'column-reverse' => 'column-reverse'
					),
					'row',
					'Specifies the direction of the flexible items.'
					
				)."

				".yp_get_radio_markup(
					'flex-wrap',
					'Flex Wrap',
					array(
						'wrap' => 'wrap',
						'wrap-reverse' => 'wrap-reverse'
					),
					'nowrap',
					'Specifies whether the flexible items should wrap or not.'
					
				)."

				".yp_get_select_markup(
					'justify-content',
					'Justify Content',
					array(
						'flex-start' => 'flex-start',
						'flex-end' => 'flex-end',
						'center' => 'center',
						'space-between' => 'space-between',
						'space-around' => 'space-around',
					),
					"normal",
					'Aligns the flexible containers items when the items do not use all available space on the main-axis (horizontally).'
				)."


				".yp_get_select_markup(
					'align-items',
					'Align Items',
					array(
						'stretch' => 'stretch',
						'center' => 'center',
						'flex-start' => 'flex-start',
						'flex-end' => 'flex-end',
						'baseline' => 'baseline',
					),
					"normal",
					'Specifies the default alignment for items inside the flexible container.'
				)."

				".yp_get_select_markup(
					'align-content',
					'Align Content',
					array(
						'stretch' => 'stretch',
						'center' => 'center',
						'flex-start' => 'flex-start',
						'flex-end' => 'flex-end',
						'space-between' => 'space-between',
						'space-around' => 'space-around',
					),
					"normal",
					'Modifies the behavior of the flex-wrap property. It is similar to align-items, but instead of aligning flex items, it aligns flex lines.'
				)."


				".yp_get_slider_markup(
					'flex-basis',
					'Flex Basis',
					"auto",
					0,        // decimals
					'0,500',   // px value
					'0,100',  // percentage value
					'0,52',     // Em value
					'Specifies the initial length of a flexible item.'
				)."

				".yp_get_select_markup(
					'align-self',
					'Align Self',
					array(
						'stretch' => 'stretch',
						'center' => 'center',
						'flex-start' => 'flex-start',
						'flex-end' => 'flex-end',
						'baseline' => 'baseline',
					),
					"auto",
					'Specifies the alignment for the selected item inside the flexible container.'
				)."

				".yp_get_slider_markup(
					'flex-grow',
					'Flex Grow',
					'0',
					0,        // decimals
					'0,20',   // px value
					'0,20',  // percentage value
					'0,20',     // Em value
					'Specifies how much the item will grow relative to the rest of the flexible items inside the same container.'
				)."

				".yp_get_slider_markup(
					'flex-shrink',
					'Flex Shrink',
					'1',
					0,        // decimals
					'0,20',   // px value
					'0,20',  // percentage value
					'0,20',     // Em value
					'Specifies how the item will shrink relative to the rest of the flexible items inside the same container.'
				)."

			</div>
		</li>
		
		<li class='lists-option'>
			<h3>Lists ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				".yp_get_select_markup(
					'list-style-type',
					'List Style Type'
					,array(
						'disc' => 'disc',
						'circle' => 'circle',
						'decimal' => 'decimal',
						'lower-alpha' => 'lower alpha',
						'upper-alpha' => 'upper alpha',
						'upper-roman' => 'upper roman'
					),
					"none",
					'Specifies the type of list-item marker in a list.'
				)."

				".yp_get_input_markup(
					'list-style-image',
					'List Style Image',
					"none",
					'Replaces the list-item marker with an image.'
				)."

				".yp_get_radio_markup(
					'list-style-position',
					'List Style Position',
					array(
						'inside' => 'inside',
						'outside' => 'outside'
					),
					"none",
					'Specifies if the list-item markers should appear inside or outside the content flow.'
				)."	

			</div>
		</li>

		<li class='animation-option'>
			<h3>Animation <span class='yp-badge yp-lite'>Pro</span> <span class='yp-badge yp-anim-recording'>Rec</span> ".$arrow_icon."</h3>
			<div class='yp-this-content'>
				
				<div class='animation-links-control yp-just-desktop'>

				<a class='yp-advanced-link yp-special-css-link yp-just-desktop yp-add-animation-link'>Create Animation</a>

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
					'Animation',
					$animations,
					"none",
					'Adds an animation to an element.'
				)."
				
				".yp_get_select_markup(
					'animation-play',
					'Condition',
					array(
						'yp_onscreen' => 'onScreen',
						'yp_hover' => 'Hover',
						'yp_click' => 'Click',
						'yp_focus' => 'Focus'
					),
					'yp_onscreen',
					'OnScreen: Playing animation when element visible on screen.<br><br>Hover: Playing animation when mouse on element.<br><br>Click: Playing animation when element clicked.<br><br>Focus: Playing element when click on an text field.'
				)."
				
				".yp_get_select_markup(
					'animation-iteration-count',
					'Animation Iteration',
					array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'infinite' => 'infinite'
					),
					'1',
					'Specifies the number of times an animation should be played.'
				)."

				".yp_get_slider_markup(
					'animation-duration',
					'Animation Duration',
					'0s',
					2,        // decimals
					'1,10',   // px value
					'1,10',   // percentage value
					'1,10',   // Em/ms value
					'Defines how long an animation should take to complete one cycle.'
				)."

				".yp_get_slider_markup(
					'animation-delay',
					'Animation Delay',
					'0s',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10',     // Em/ms value
					'Specifies a delay for the start of an animation.'
				)."

				".yp_get_radio_markup(
					'animation-fill-mode',
					'Animation Fill Mode',
					array(
						'forwards' => 'forwards',
						'backwards' => 'backwards',
						'both' => 'both',
					),
					"none",
					'Sets the state of the end animation when the animation is not running.'
				)."		
				
			</div>
		</li>
		
		<li class='box-shadow-option'>
			<h3>Box Shadow ".$arrow_icon."</h3>
			<div class='yp-this-content'>
			
				".yp_get_color_markup(
					'box-shadow-color',
					'Color',
					'Sets color of the shadow.'
				)."
				
				".yp_get_slider_markup(
					'box-shadow-blur-radius',
					'Blur Radius',
					'0',
					0,        	// decimals
					'0,50',   // px value
					'0,50',  // percentage value
					'0,50',     // Em value
					'Sets blur radius of the shadow.'
				)."
				
				".yp_get_slider_markup(
					'box-shadow-spread',
					'Spread',
					'0',
					0,        	// decimals
					'-50,100',   // px value
					'-50,100',  // percentage value
					'-50,100',     // Em value
					'Set size of the shadow.'
				)."

				".yp_get_radio_markup(
					'box-shadow-inset',
					'Position',
					array(
						'no' => 'Outer',
						'inset' => 'Inner'
					),
					false,
					'Defines whether the shadow is inside or outside.'
				)."		

				".yp_get_slider_markup(
					'box-shadow-horizontal',
					'Horizontal Length',
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50',     // Em value
					'Sets horizontal length of the shadow.'
				)."
				
				".yp_get_slider_markup(
					'box-shadow-vertical',
					'Vertical Length',
					'0',
					0,        	// decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50',     // Em value
					'Sets vertical length of the shadow.'
				)."

			</div>
		</li>
		
		<li class='extra-option'>
			<h3>Extra ".$arrow_icon."</h3>
			<div class='yp-this-content'>

				<a class='yp-advanced-link yp-top yp-special-css-link yp-filter-link'>Filters</a>
				<div class='yp-advanced-option yp-special-css-area yp-filter-area'>

				".yp_get_slider_markup(
					'blur-filter',
					'Blur',
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'brightness-filter',
					'Brightness',
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'grayscale-filter',
					'Grayscale',
					'0',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1'     // Em value
				)."
				
				".yp_get_slider_markup(
					'contrast-filter',
					'Contrast',
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'hue-rotate-filter',
					'Hue Rotate',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'saturate-filter',
					'Saturate',
					'0',
					2,        // decimals
					'0,10',   // px value
					'0,10',  // percentage value
					'0,10'     // Em value
				)."
				
				".yp_get_slider_markup(
					'sepia-filter',
					'Sepia',
					'0',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1'     // Em value
				)."

				</div>

				<a class='yp-advanced-link yp-top yp-special-css-link yp-transform-link'>Transform</a>
				<div class='yp-advanced-option yp-special-css-area yp-transform-area'>
				".yp_get_slider_markup(
					'scale-transform',
					'Scale',
					'0',
					2,        // decimals
					'0,5',   // px value
					'0,5',  // percentage value
					'0,5'     // Em value
				)."
				
				".yp_get_slider_markup(
					'rotate-transform',
					'Rotate',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatex-transform',
					'Rotate X',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatey-transform',
					'Rotate Y',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'rotatez-transform',
					'Rotate Z',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'translate-x-transform',
					'Translate X',
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'translate-y-transform',
					'Translate Y',
					'0',
					0,        // decimals
					'-50,50',   // px value
					'-50,50',  // percentage value
					'-50,50'     // Em value
				)."
				
				".yp_get_slider_markup(
					'skew-x-transform',
					'Skew X',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."
				
				".yp_get_slider_markup(
					'skew-y-transform',
					'skew Y',
					'0',
					0,        // decimals
					'0,360',   // px value
					'0,360',  // percentage value
					'0,360'     // Em value
				)."

				".yp_get_slider_markup(
					'perspective',
					'Perspective',
					'0',
					0,        // decimals
					'0,1000',   // px value
					'0,100',  // percentage value
					'0,62'     // Em value
				)."

				</div>
				
				
				".yp_get_slider_markup(
					'opacity',
					'Opacity',
					'1',
					2,        // decimals
					'0,1',   // px value
					'0,1',  // percentage value
					'0,1',     // Em value
					'Sets the opacity level for an element.'
				)."

				".yp_get_select_markup(
					'display',
					'Display',
					array(
						'block' => 'block',
						'flex' => 'flex',
						'inline' => 'inline',
						'inline-block' => 'inline-block',
						'inline-flex' => 'inline-flex',
						'table-cell' => 'table-cell',
						'none' => 'none',
					),
					"inline",
					'Specifies the type of box used for an element.'
				)."

				".yp_get_select_markup(
					'cursor',
					'Cursor',
					array(
						'alias' => 'alias',
						'all-scroll' => 'All Scroll',
						'copy' => 'Copy',
						'crosshair' => 'CrossHair',
						'grab' => 'Grab',
						'grabbing' => 'Grabbing',
						'help' => 'Help',
						'not-allowed' => 'Not Allowed',
						'pointer' => 'Pointer',
						'progress' => 'Progress',
						'text' => 'Text',
						'wait' => 'Wait',
						'zoom-in' => 'Zoom In',
						'zoom-out' => 'Zoom Out'
					),
					"auto",
					'Specifies the type of cursor to be displayed when pointing on an element.'
				)."
				
				".yp_get_radio_markup(
					'float',
					'Float',
					array(
						'left' => 'left',
						'right' => 'right'
					),
					"none",
					'Specifies how an element should float.'
				)."

				".yp_get_radio_markup(
					'clear',
					'Clear',
					array(
						'left' => 'left',
						'right' => 'right',
						'both' => 'both'
					),
					"none",
					'Specifies on which sides of an element floating elements are not allowed to float.'
				)."

				".yp_get_radio_markup(
					'visibility',
					'Visibility',
					array(
						'visible' => 'visible',
						'hidden' => 'hidden'
					),
					"initial",
					'Specifies whether or not an element is visible.'
				)."

				".yp_get_radio_markup(
					'pointer-events',
					'Pointer Events',
					array(
						'auto' => 'auto',
						'none' => 'none'
					),
					"auto",
					'Specifies under what circumstances (if any) a particular graphic element can become the target of mouse events.'
				)."
				
				".yp_get_radio_markup(
					'overflow-x',
					'Overflow X',
					array(
						'hidden' => 'hidden',
						'scroll' => 'scroll',
						'auto' => 'auto'
					),
					"visible",
					'Specifies what to do with the left/right edges of the content - if it overflows the elements content area.'
				)."
				
				".yp_get_radio_markup(
					'overflow-y',
					'Overflow Y',
					array(
						'hidden' => 'hidden',
						'scroll' => 'scroll',
						'auto' => 'auto'
					),
					"visible",
					'specifies what to do with the bottom edges of the content - if it overflows the elements content area.'
				)."
				
				
			</div>

		</li>
			
	</ul>";