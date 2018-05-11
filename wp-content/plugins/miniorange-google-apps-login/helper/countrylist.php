<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mo_Gsuite_CountryList {

	public static $countries = array(
		array( 'name' => 'All Countries', 'alphacode' => '', 'countryCode' => '' ),
		array( 'name' => 'Afghanistan (‫افغانستان‬‎)', 'alphacode' => 'af', 'countryCode' => '+93' ),
		array( 'name' => 'Albania (Shqipëri)', 'alphacode' => 'al', 'countryCode' => '+355' ),
		array( 'name' => 'Algeria (‫الجزائر‬‎)', 'alphacode' => 'dz', 'countryCode' => '+213' ),
		array( 'name' => 'American Samoa', 'alphacode' => 'as', 'countryCode' => '+1684' ),
		array( 'name' => 'Andorra', 'alphacode' => 'ad', 'countryCode' => '+376' ),
		array( 'name' => 'Angola', 'alphacode' => 'ao', 'countryCode' => '+244' ),
		array( 'name' => 'Anguilla', 'alphacode' => 'ai', 'countryCode' => '+1264' ),
		array( 'name' => 'Antigua and Barbuda', 'alphacode' => 'ag', 'countryCode' => '+1268' ),
		array( 'name' => 'Argentina', 'alphacode' => 'ar', 'countryCode' => '+54' ),
		array( 'name' => 'Armenia (Հայաստան)', 'alphacode' => 'am', 'countryCode' => '+374' ),
		array( 'name' => 'Aruba', 'alphacode' => 'aw', 'countryCode' => '+297' ),
		array( 'name' => 'Australia', 'alphacode' => 'au', 'countryCode' => '+61' ),
		array( 'name' => 'Austria (Österreich)', 'alphacode' => 'at', 'countryCode' => '+43' ),
		array( 'name' => 'Azerbaijan (Azərbaycan)', 'alphacode' => 'az', 'countryCode' => '+994' ),
		array( 'name' => 'Bahamas', 'alphacode' => 'bs', 'countryCode' => '+1242' ),
		array( 'name' => 'Bahrain (‫البحرين‬‎)', 'alphacode' => 'bh', 'countryCode' => '+973' ),
		array( 'name' => 'Bangladesh (বাংলাদেশ)', 'alphacode' => 'bd', 'countryCode' => '+880' ),
		array( 'name' => 'Barbados', 'alphacode' => 'bb', 'countryCode' => '+1246' ),
		array( 'name' => 'Belarus (Беларусь)', 'alphacode' => 'by', 'countryCode' => '+375' ),
		array( 'name' => 'Belgium (België)', 'alphacode' => 'be', 'countryCode' => '+32' ),
		array( 'name' => 'Belize', 'alphacode' => 'bz', 'countryCode' => '+501' ),
		array( 'name' => 'Benin (Bénin)', 'alphacode' => 'bj', 'countryCode' => '+229' ),
		array( 'name' => 'Bermuda', 'alphacode' => 'bm', 'countryCode' => '+1441' ),
		array( 'name' => 'Bhutan (འབྲུག)', 'alphacode' => 'bt', 'countryCode' => '+975' ),
		array( 'name' => 'Bolivia', 'alphacode' => 'bo', 'countryCode' => '+591' ),
		array( 'name' => 'Bosnia and Herzegovina (Босна и Херцеговина)', 'alphacode' => 'ba', 'countryCode' => '+387' ),
		array( 'name' => 'Botswana', 'alphacode' => 'bw', 'countryCode' => '+267' ),
		array( 'name' => 'Brazil (Brasil)', 'alphacode' => 'br', 'countryCode' => '+55' ),
		array( 'name' => 'British Indian Ocean Territory', 'alphacode' => 'io', 'countryCode' => '+246' ),
		array( 'name' => 'British Virgin Islands', 'alphacode' => 'vg', 'countryCode' => '+1284' ),
		array( 'name' => 'Brunei', 'alphacode' => 'bn', 'countryCode' => '+673' ),
		array( 'name' => 'Bulgaria (България)', 'alphacode' => 'bg', 'countryCode' => '+359' ),
		array( 'name' => 'Burkina Faso', 'alphacode' => 'bf', 'countryCode' => '+226' ),
		array( 'name' => 'Burundi (Uburundi)', 'alphacode' => 'bi', 'countryCode' => '+257' ),
		array( 'name' => 'Cambodia (កម្ពុជា)', 'alphacode' => 'kh', 'countryCode' => '+855' ),
		array( 'name' => 'Cameroon (Cameroun)', 'alphacode' => 'cm', 'countryCode' => '+237' ),
		array( 'name' => 'Canada', 'alphacode' => 'ca', 'countryCode' => '+1' ),
		array( 'name' => 'Cape Verde (Kabu Verdi)', 'alphacode' => 'cv', 'countryCode' => '+238' ),
		array( 'name' => 'Caribbean Netherlands', 'alphacode' => 'bq', 'countryCode' => '+599' ),
		array( 'name' => 'Cayman Islands', 'alphacode' => 'ky', 'countryCode' => '+1345' ),
		array( 'name'        => 'Central African Republic (République centrafricaine)',
		       'alphacode'   => 'cf',
		       'countryCode' => '+236'
		),
		array( 'name' => 'Chad (Tchad)', 'alphacode' => 'td', 'countryCode' => '+235' ),
		array( 'name' => 'Chile', 'alphacode' => 'cl', 'countryCode' => '+56' ),
		array( 'name' => 'China (中国)', 'alphacode' => 'cn', 'countryCode' => '+86' ),
		array( 'name' => 'Christmas Island', 'alphacode' => 'cx', 'countryCode' => '+61' ),
		array( 'name' => 'Cocos (Keeling) Islands', 'alphacode' => 'cc', 'countryCode' => '+61' ),
		array( 'name' => 'Colombia', 'alphacode' => 'co', 'countryCode' => '+57' ),
		array( 'name' => 'Comoros (‫جزر القمر‬‎)', 'alphacode' => 'km', 'countryCode' => '+269' ),
		array( 'name'        => 'Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)',
		       'alphacode'   => 'cd',
		       'countryCode' => '+243'
		),
		array( 'name' => 'Congo (Republic) (Congo-Brazzaville)', 'alphacode' => 'cg', 'countryCode' => '+242' ),
		array( 'name' => 'Cook Islands', 'alphacode' => 'ck', 'countryCode' => '+682' ),
		array( 'name' => 'Costa Rica', 'alphacode' => 'cr', 'countryCode' => '+506' ),
		array( 'name' => 'Côte d’Ivoire', 'alphacode' => 'ci', 'countryCode' => '+225' ),
		array( 'name' => 'Croatia (Hrvatska)', 'alphacode' => 'hr', 'countryCode' => '+385' ),
		array( 'name' => 'Cuba', 'alphacode' => 'cu', 'countryCode' => '+53' ),
		array( 'name' => 'Curaçao', 'alphacode' => 'cw', 'countryCode' => '+599' ),
		array( 'name' => 'Cyprus (Κύπρος)', 'alphacode' => 'cy', 'countryCode' => '+357' ),
		array( 'name' => 'Czech Republic (Česká republika)', 'alphacode' => 'cz', 'countryCode' => '+420' ),
		array( 'name' => 'Denmark (Danmark)', 'alphacode' => 'dk', 'countryCode' => '+45' ),
		array( 'name' => 'Djibouti', 'alphacode' => 'dj', 'countryCode' => '+253' ),
		array( 'name' => 'Dominica', 'alphacode' => 'dm', 'countryCode' => '+1767' ),
		array( 'name' => 'Dominican Republic (República Dominicana)', 'alphacode' => 'do', 'countryCode' => '+1' ),
		array( 'name' => 'Ecuador', 'alphacode' => 'ec', 'countryCode' => '+593' ),
		array( 'name' => 'Egypt (‫مصر‬‎)', 'alphacode' => 'eg', 'countryCode' => '+20' ),
		array( 'name' => 'El Salvador', 'alphacode' => 'sv', 'countryCode' => '+503' ),
		array( 'name' => 'Equatorial Guinea (Guinea Ecuatorial)', 'alphacode' => 'gq', 'countryCode' => '+240' ),
		array( 'name' => 'Eritrea', 'alphacode' => 'er', 'countryCode' => '+291' ),
		array( 'name' => 'Estonia (Eesti)', 'alphacode' => 'ee', 'countryCode' => '+372' ),
		array( 'name' => 'Ethiopia', 'alphacode' => 'et', 'countryCode' => '+251' ),
		array( 'name' => 'Falkland Islands (Islas Malvinas)', 'alphacode' => 'fk', 'countryCode' => '+500' ),
		array( 'name' => 'Faroe Islands (Føroyar)', 'alphacode' => 'fo', 'countryCode' => '+298' ),
		array( 'name' => 'Fiji', 'alphacode' => 'fj', 'countryCode' => '+679' ),
		array( 'name' => 'Finland (Suomi)', 'alphacode' => 'fi', 'countryCode' => '+358' ),
		array( 'name' => 'France', 'alphacode' => 'fr', 'countryCode' => '+33' ),
		array( 'name' => 'French Guiana (Guyane française)', 'alphacode' => 'gf', 'countryCode' => '+594' ),
		array( 'name' => 'French Polynesia (Polynésie française)', 'alphacode' => 'pf', 'countryCode' => '+689' ),
		array( 'name' => 'Gabon', 'alphacode' => 'ga', 'countryCode' => '+241' ),
		array( 'name' => 'Gambia', 'alphacode' => 'gm', 'countryCode' => '+220' ),
		array( 'name' => 'Georgia (საქართველო)', 'alphacode' => 'ge', 'countryCode' => '+995' ),
		array( 'name' => 'Germany (Deutschland)', 'alphacode' => 'de', 'countryCode' => '+49' ),
		array( 'name' => 'Ghana (Gaana)', 'alphacode' => 'gh', 'countryCode' => '+233' ),
		array( 'name' => 'Gibraltar', 'alphacode' => 'gi', 'countryCode' => '+350' ),
		array( 'name' => 'Greece (Ελλάδα)', 'alphacode' => 'gr', 'countryCode' => '+30' ),
		array( 'name' => 'Greenland (Kalaallit Nunaat)', 'alphacode' => 'gl', 'countryCode' => '+299' ),
		array( 'name' => 'Grenada', 'alphacode' => 'gd', 'countryCode' => '+1473' ),
		array( 'name' => 'Guadeloupe', 'alphacode' => 'gp', 'countryCode' => '+590' ),
		array( 'name' => 'Guam', 'alphacode' => 'gu', 'countryCode' => '+1671' ),
		array( 'name' => 'Guatemala', 'alphacode' => 'gt', 'countryCode' => '+502' ),
		array( 'name' => 'Guernsey', 'alphacode' => 'gg', 'countryCode' => '+44' ),
		array( 'name' => 'Guinea (Guinée)', 'alphacode' => 'gn', 'countryCode' => '+224' ),
		array( 'name' => 'Guinea-Bissau (Guiné Bissau)', 'alphacode' => 'gw', 'countryCode' => '+245' ),
		array( 'name' => 'Guyana', 'alphacode' => 'gy', 'countryCode' => '+592' ),
		array( 'name' => 'Haiti', 'alphacode' => 'ht', 'countryCode' => '+509' ),
		array( 'name' => 'Honduras', 'alphacode' => 'hn', 'countryCode' => '+504' ),
		array( 'name' => 'Hong Kong (香港)', 'alphacode' => 'hk', 'countryCode' => '+852' ),
		array( 'name' => 'Hungary (Magyarország)', 'alphacode' => 'hu', 'countryCode' => '+36' ),
		array( 'name' => 'Iceland (Ísland)', 'alphacode' => 'is', 'countryCode' => '+354' ),
		array( 'name' => 'India (भारत)', 'alphacode' => 'in', 'countryCode' => '+91' ),
		array( 'name' => 'Indonesia', 'alphacode' => 'id', 'countryCode' => '+62' ),
		array( 'name' => 'Iran (‫ایران‬‎)', 'alphacode' => 'ir', 'countryCode' => '+98' ),
		array( 'name' => 'Iraq (‫العراق‬‎)', 'alphacode' => 'iq', 'countryCode' => '+964' ),
		array( 'name' => 'Ireland', 'alphacode' => 'ie', 'countryCode' => '+353' ),
		array( 'name' => 'Isle of Man', 'alphacode' => 'im', 'countryCode' => '+44' ),
		array( 'name' => 'Israel (‫ישראל‬‎)', 'alphacode' => 'il', 'countryCode' => '+972' ),
		array( 'name' => 'Italy (Italia)', 'alphacode' => 'it', 'countryCode' => '+39' ),
		array( 'name' => 'Jamaica', 'alphacode' => 'jm', 'countryCode' => '+1876' ),
		array( 'name' => 'Japan (日本)', 'alphacode' => 'jp', 'countryCode' => '+81' ),
		array( 'name' => 'Jersey', 'alphacode' => 'je', 'countryCode' => '+44' ),
		array( 'name' => 'Jordan (‫الأردن‬‎)', 'alphacode' => 'jo', 'countryCode' => '+962' ),
		array( 'name' => 'Kazakhstan (Казахстан)', 'alphacode' => 'kz', 'countryCode' => '+7' ),
		array( 'name' => 'Kenya', 'alphacode' => 'ke', 'countryCode' => '+254' ),
		array( 'name' => 'Kiribati', 'alphacode' => 'ki', 'countryCode' => '+686' ),
		array( 'name' => 'Kosovo', 'alphacode' => 'xk', 'countryCode' => '+383' ),
		array( 'name' => 'Kuwait (‫الكويت‬‎)', 'alphacode' => 'kw', 'countryCode' => '+965' ),
		array( 'name' => 'Kyrgyzstan (Кыргызстан)', 'alphacode' => 'kg', 'countryCode' => '+996' ),
		array( 'name' => 'Laos (ລາວ)', 'alphacode' => 'la', 'countryCode' => '+856' ),
		array( 'name' => 'Latvia (Latvija)', 'alphacode' => 'lv', 'countryCode' => '+371' ),
		array( 'name' => 'Lebanon (‫لبنان‬‎)', 'alphacode' => 'lb', 'countryCode' => '+961' ),
		array( 'name' => 'Lesotho', 'alphacode' => 'ls', 'countryCode' => '+266' ),
		array( 'name' => 'Liberia', 'alphacode' => 'lr', 'countryCode' => '+231' ),
		array( 'name' => 'Libya (‫ليبيا‬‎)', 'alphacode' => 'ly', 'countryCode' => '+218' ),
		array( 'name' => 'Liechtenstein', 'alphacode' => 'li', 'countryCode' => '+423' ),
		array( 'name' => 'Lithuania (Lietuva)', 'alphacode' => 'lt', 'countryCode' => '+370' ),
		array( 'name' => 'Luxembourg', 'alphacode' => 'lu', 'countryCode' => '+352' ),
		array( 'name' => 'Macau (澳門)', 'alphacode' => 'mo', 'countryCode' => '+853' ),
		array( 'name' => 'Macedonia (FYROM) (Македонија)', 'alphacode' => 'mk', 'countryCode' => '+389' ),
		array( 'name' => 'Madagascar (Madagasikara)', 'alphacode' => 'mg', 'countryCode' => '+261' ),
		array( 'name' => 'Malawi', 'alphacode' => 'mw', 'countryCode' => '+265' ),
		array( 'name' => 'Malaysia', 'alphacode' => 'my', 'countryCode' => '+60' ),
		array( 'name' => 'Maldives', 'alphacode' => 'mv', 'countryCode' => '+960' ),
		array( 'name' => 'Mali', 'alphacode' => 'ml', 'countryCode' => '+223' ),
		array( 'name' => 'Malta', 'alphacode' => 'mt', 'countryCode' => '+356' ),
		array( 'name' => 'Marshall Islands', 'alphacode' => 'mh', 'countryCode' => '+692' ),
		array( 'name' => 'Martinique', 'alphacode' => 'mq', 'countryCode' => '+596' ),
		array( 'name' => 'Mauritania (‫موريتانيا‬‎)', 'alphacode' => 'mr', 'countryCode' => '+222' ),
		array( 'name' => 'Mauritius (Moris)', 'alphacode' => 'mu', 'countryCode' => '+230' ),
		array( 'name' => 'Mayotte', 'alphacode' => 'yt', 'countryCode' => '+262' ),
		array( 'name' => 'Mexico (México)', 'alphacode' => 'mx', 'countryCode' => '+52' ),
		array( 'name' => 'Micronesia', 'alphacode' => 'fm', 'countryCode' => '+691' ),
		array( 'name' => 'Moldova (Republica Moldova)', 'alphacode' => 'md', 'countryCode' => '+373' ),
		array( 'name' => 'Monaco', 'alphacode' => 'mc', 'countryCode' => '+377' ),
		array( 'name' => 'Mongolia (Монгол)', 'alphacode' => 'mn', 'countryCode' => '+976' ),
		array( 'name' => 'Montenegro (Crna Gora)', 'alphacode' => 'me', 'countryCode' => '+382' ),
		array( 'name' => 'Montserrat', 'alphacode' => 'ms', 'countryCode' => '+1664' ),
		array( 'name' => 'Morocco (‫المغرب‬‎)', 'alphacode' => 'ma', 'countryCode' => '+212' ),
		array( 'name' => 'Mozambique (Moçambique)', 'alphacode' => 'mz', 'countryCode' => '+258' ),
		array( 'name' => 'Myanmar (Burma) (မြန်မာ)', 'alphacode' => 'mm', 'countryCode' => '+95' ),
		array( 'name' => 'Namibia (Namibië)', 'alphacode' => 'na', 'countryCode' => '+264' ),
		array( 'name' => 'Nauru', 'alphacode' => 'nr', 'countryCode' => '+674' ),
		array( 'name' => 'Nepal (नेपाल)', 'alphacode' => 'np', 'countryCode' => '+977' ),
		array( 'name' => 'Netherlands (Nederland)', 'alphacode' => 'nl', 'countryCode' => '+31' ),
		array( 'name' => 'New Caledonia (Nouvelle-Calédonie)', 'alphacode' => 'nc', 'countryCode' => '+687' ),
		array( 'name' => 'New Zealand', 'alphacode' => 'nz', 'countryCode' => '+64' ),
		array( 'name' => 'Nicaragua', 'alphacode' => 'ni', 'countryCode' => '+505' ),
		array( 'name' => 'Niger (Nijar)', 'alphacode' => 'ne', 'countryCode' => '+227' ),
		array( 'name' => 'Nigeria', 'alphacode' => 'ng', 'countryCode' => '+234' ),
		array( 'name' => 'Niue', 'alphacode' => 'nu', 'countryCode' => '+683' ),
		array( 'name' => 'Norfolk Island', 'alphacode' => 'nf', 'countryCode' => '+672' ),
		array( 'name' => 'North Korea (조선 민주주의 인민 공화국)', 'alphacode' => 'kp', 'countryCode' => '+850' ),
		array( 'name' => 'Northern Mariana Islands', 'alphacode' => 'mp', 'countryCode' => '+1670' ),
		array( 'name' => 'Norway (Norge)', 'alphacode' => 'no', 'countryCode' => '+47' ),
		array( 'name' => 'Oman (‫عُمان‬‎)', 'alphacode' => 'om', 'countryCode' => '+968' ),
		array( 'name' => 'Pakistan (‫پاکستان‬‎)', 'alphacode' => 'pk', 'countryCode' => '+92' ),
		array( 'name' => 'Palau', 'alphacode' => 'pw', 'countryCode' => '+680' ),
		array( 'name' => 'Palestine (‫فلسطين‬‎)', 'alphacode' => 'ps', 'countryCode' => '+970' ),
		array( 'name' => 'Panama (Panamá)', 'alphacode' => 'pa', 'countryCode' => '+507' ),
		array( 'name' => 'Papua New Guinea', 'alphacode' => 'pg', 'countryCode' => '+675' ),
		array( 'name' => 'Paraguay', 'alphacode' => 'py', 'countryCode' => '+595' ),
		array( 'name' => 'Peru (Perú)', 'alphacode' => 'pe', 'countryCode' => '+51' ),
		array( 'name' => 'Philippines', 'alphacode' => 'ph', 'countryCode' => '+63' ),
		array( 'name' => 'Poland (Polska)', 'alphacode' => 'pl', 'countryCode' => '+48' ),
		array( 'name' => 'Portugal', 'alphacode' => 'pt', 'countryCode' => '+351' ),
		array( 'name' => 'Puerto Rico', 'alphacode' => 'pr', 'countryCode' => '+1' ),
		array( 'name' => 'Qatar (‫قطر‬‎)', 'alphacode' => 'qa', 'countryCode' => '+974' ),
		array( 'name' => 'Réunion (La Réunion)', 'alphacode' => 're', 'countryCode' => '+262' ),
		array( 'name' => 'Romania (România)', 'alphacode' => 'ro', 'countryCode' => '+40' ),
		array( 'name' => 'Russia (Россия)', 'alphacode' => 'ru', 'countryCode' => '+7' ),
		array( 'name' => 'Rwanda', 'alphacode' => 'rw', 'countryCode' => '+250' ),
		array( 'name' => 'Saint Barthélemy', 'alphacode' => 'bl', 'countryCode' => '+590' ),
		array( 'name' => 'Saint Helena', 'alphacode' => 'sh', 'countryCode' => '+290' ),
		array( 'name' => 'Saint Kitts and Nevis', 'alphacode' => 'kn', 'countryCode' => '+1869' ),
		array( 'name' => 'Saint Lucia', 'alphacode' => 'lc', 'countryCode' => '+1758' ),
		array( 'name'        => 'Saint Martin (Saint-Martin (partie française))',
		       'alphacode'   => 'mf',
		       'countryCode' => '+590'
		),
		array( 'name'        => 'Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)',
		       'alphacode'   => 'pm',
		       'countryCode' => '+508'
		),
		array( 'name' => 'Saint Vincent and the Grenadines', 'alphacode' => 'vc', 'countryCode' => '+1784' ),
		array( 'name' => 'Samoa', 'alphacode' => 'ws', 'countryCode' => '+685' ),
		array( 'name' => 'San Marino', 'alphacode' => 'sm', 'countryCode' => '+378' ),
		array( 'name' => 'São Tomé and Príncipe (São Tomé e Príncipe)', 'alphacode' => 'st', 'countryCode' => '+239' ),
		array( 'name' => 'Saudi Arabia (‫المملكة العربية السعودية‬‎)', 'alphacode' => 'sa', 'countryCode' => '+966' ),
		array( 'name' => 'Senegal (Sénégal)', 'alphacode' => 'sn', 'countryCode' => '+221' ),
		array( 'name' => 'Serbia (Србија)', 'alphacode' => 'rs', 'countryCode' => '+381' ),
		array( 'name' => 'Seychelles', 'alphacode' => 'sc', 'countryCode' => '+248' ),
		array( 'name' => 'Sierra Leone', 'alphacode' => 'sl', 'countryCode' => '+232' ),
		array( 'name' => 'Singapore', 'alphacode' => 'sg', 'countryCode' => '+65' ),
		array( 'name' => 'Sint Maarten', 'alphacode' => 'sx', 'countryCode' => '+1721' ),
		array( 'name' => 'Slovakia (Slovensko)', 'alphacode' => 'sk', 'countryCode' => '+421' ),
		array( 'name' => 'Slovenia (Slovenija)', 'alphacode' => 'si', 'countryCode' => '+386' ),
		array( 'name' => 'Solomon Islands', 'alphacode' => 'sb', 'countryCode' => '+677' ),
		array( 'name' => 'Somalia (Soomaaliya)', 'alphacode' => 'so', 'countryCode' => '+252' ),
		array( 'name' => 'South Africa', 'alphacode' => 'za', 'countryCode' => '+27' ),
		array( 'name' => 'South Korea (대한민국)', 'alphacode' => 'kr', 'countryCode' => '+82' ),
		array( 'name' => 'South Sudan (‫جنوب السودان‬‎)', 'alphacode' => 'ss', 'countryCode' => '+211' ),
		array( 'name' => 'Spain (España)', 'alphacode' => 'es', 'countryCode' => '+34' ),
		array( 'name' => 'Sri Lanka (ශ්‍රී ලංකාව)', 'alphacode' => 'lk', 'countryCode' => '+94' ),
		array( 'name' => 'Sudan (‫السودان‬‎)', 'alphacode' => 'sd', 'countryCode' => '+249' ),
		array( 'name' => 'Suriname', 'alphacode' => 'sr', 'countryCode' => '+597' ),
		array( 'name' => 'Svalbard and Jan Mayen', 'alphacode' => 'sj', 'countryCode' => '+47' ),
		array( 'name' => 'Swaziland', 'alphacode' => 'sz', 'countryCode' => '+268' ),
		array( 'name' => 'Sweden (Sverige)', 'alphacode' => 'se', 'countryCode' => '+46' ),
		array( 'name' => 'Switzerland (Schweiz)', 'alphacode' => 'ch', 'countryCode' => '+41' ),
		array( 'name' => 'Syria (‫سوريا‬‎)', 'alphacode' => 'sy', 'countryCode' => '+963' ),
		array( 'name' => 'Taiwan (台灣)', 'alphacode' => 'tw', 'countryCode' => '+886' ),
		array( 'name' => 'Tajikistan', 'alphacode' => 'tj', 'countryCode' => '+992' ),
		array( 'name' => 'Tanzania', 'alphacode' => 'tz', 'countryCode' => '+255' ),
		array( 'name' => 'Thailand (ไทย)', 'alphacode' => 'th', 'countryCode' => '+66' ),
		array( 'name' => 'Timor-Leste', 'alphacode' => 'tl', 'countryCode' => '+670' ),
		array( 'name' => 'Togo', 'alphacode' => 'tg', 'countryCode' => '+228' ),
		array( 'name' => 'Tokelau', 'alphacode' => 'tk', 'countryCode' => '+690' ),
		array( 'name' => 'Tonga', 'alphacode' => 'to', 'countryCode' => '+676' ),
		array( 'name' => 'Trinidad and Tobago', 'alphacode' => 'tt', 'countryCode' => '+1868' ),
		array( 'name' => 'Tunisia (‫تونس‬‎)', 'alphacode' => 'tn', 'countryCode' => '+216' ),
		array( 'name' => 'Turkey (Türkiye)', 'alphacode' => 'tr', 'countryCode' => '+90' ),
		array( 'name' => 'Turkmenistan', 'alphacode' => 'tm', 'countryCode' => '+993' ),
		array( 'name' => 'Turks and Caicos Islands', 'alphacode' => 'tc', 'countryCode' => '+1649' ),
		array( 'name' => 'Tuvalu', 'alphacode' => 'tv', 'countryCode' => '+688' ),
		array( 'name' => 'U.S. Virgin Islands', 'alphacode' => 'vi', 'countryCode' => '+1340' ),
		array( 'name' => 'Uganda', 'alphacode' => 'ug', 'countryCode' => '+256' ),
		array( 'name' => 'Ukraine (Україна)', 'alphacode' => 'ua', 'countryCode' => '+380' ),
		array( 'name'        => 'United Arab Emirates (‫الإمارات العربية المتحدة‬‎)',
		       'alphacode'   => 'ae',
		       'countryCode' => '+971'
		),
		array( 'name' => 'United Kingdom', 'alphacode' => 'gb', 'countryCode' => '+44' ),
		array( 'name' => 'United States', 'alphacode' => 'us', 'countryCode' => '+1' ),
		array( 'name' => 'Uruguay', 'alphacode' => 'uy', 'countryCode' => '+598' ),
		array( 'name' => 'Uzbekistan (Oʻzbekiston)', 'alphacode' => 'uz', 'countryCode' => '+998' ),
		array( 'name' => 'Vanuatu', 'alphacode' => 'vu', 'countryCode' => '+678' ),
		array( 'name' => 'Vatican City (Città del Vaticano)', 'alphacode' => 'va', 'countryCode' => '+39' ),
		array( 'name' => 'Venezuela', 'alphacode' => 've', 'countryCode' => '+58' ),
		array( 'name' => 'Vietnam (Việt Nam)', 'alphacode' => 'vn', 'countryCode' => '+84' ),
		array( 'name' => 'Wallis and Futuna (Wallis-et-Futuna)', 'alphacode' => 'wf', 'countryCode' => '+681' ),
		array( 'name' => 'Western Sahara (‫الصحراء الغربية‬‎)', 'alphacode' => 'eh', 'countryCode' => '+212' ),
		array( 'name' => 'Yemen (‫اليمن‬‎)', 'alphacode' => 'ye', 'countryCode' => '+967' ),
		array( 'name' => 'Zambia', 'alphacode' => 'zm', 'countryCode' => '+260' ),
		array( 'name' => 'Zimbabwe', 'alphacode' => 'zw', 'countryCode' => '+263' ),
		array( 'name' => 'Åland Islands', 'alphacode' => 'ax', 'countryCode' => '+358' ),
	);

	public static function getCountryCodeList() {
		return self::$countries;
	}

	public static function getDefaultCountryCode() {
		$oldValue = self::getDefaultCountryCode_deprecated();
		$newValue = self::getDefaultCountryData();

		return ! Mo_GSuite_Utility::isBlank( $oldValue ) ? $oldValue : ( ! Mo_GSuite_Utility::isBlank( $newValue ) ? $newValue['countryCode'] : null );
	}

	public static function getDefaultCountryCode_deprecated() {
		return ! Mo_GSuite_Utility::isBlank( get_mo_gsuite_option( 'mo_customer_validation_default_country_code' ) )
			? get_mo_gsuite_option( 'mo_customer_validation_default_country_code' ) : null;
	}

	public static function getDefaultCountryData() {
		return ! Mo_GSuite_Utility::isBlank( get_mo_gsuite_option( 'mo_customer_validation_default_country' ) )
			? maybe_unserialize( get_mo_gsuite_option( 'mo_customer_validation_default_country' ) ) : null;
	}

	public static function isCountrySelected( $value, $alphacode ) {
		$oldValue = self::isCountrySelected_deprecated( $value );
		$newValue = self::getDefaultCountryData();

		return $oldValue ? $oldValue : ( ! Mo_GSuite_Utility::isBlank( $newValue ) && $alphacode == $newValue['alphacode'] );
	}

	public static function isCountrySelected_deprecated( $value ) {
		return ! Mo_GSuite_Utility::isBlank( get_mo_gsuite_option( 'mo_customer_validation_default_country_code' ) )
		       && $value == get_mo_gsuite_option( 'mo_customer_validation_default_country_code' );
	}

	public static function getDefaultCountryIsoCode() {
		$oldValue = self::getDefaultCountryCode_deprecated();
		$newValue = self::getDefaultCountryData();
		if ( ! Mo_GSuite_Utility::isBlank( $newValue ) ) {
			return $newValue['alphacode'];
		}
		if ( ! Mo_GSuite_Utility::isBlank( $oldValue ) ) {
			foreach ( self::$countries as $country ) {
				if ( $oldValue == $country['countryCode'] ) {
					return $country['alphacode'];
				}
			}
		}

		return '';
	}

	public static function getOnlyMo_Gsuite_CountryList() {
		return null;
	}
}

new Mo_Gsuite_CountryList;
