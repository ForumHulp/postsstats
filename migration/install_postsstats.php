<?php
/**
*
* @package Posts Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\postsstats\migration;

class install_postsstats extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['postsstats_version']) && version_compare($this->config['postsstats_version'], '3.1.0.RC4', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			// We have to create our own tables
			'add_tables'	=> array(
				$this->table_prefix . 'postsstat_config'	=> array(
					'COLUMNS'			=> array(
						'max_posts_per_user'	=> array('UINT:4', 20),
						'max_posts_per_topic'	=> array('UINT:4', 20),
						'max_topics_per_user'	=> array('UINT:4', 20),
						'max_topics_per_forum'	=> array('UINT:4', 20),
						'max_topics_views'		=> array('UINT:4', 20),
						'max_groups_posts'		=> array('UINT:4', 20),
						'max_topic_tracks'		=> array('UINT:4', 20),
						'max_online'			=> array('UINT:4', 20),
						'start_screen'			=> array('VCHAR:25', 'default'),
					),
				),
				$this->table_prefix . 'postsstat_domains'	=> array(
					'COLUMNS'			=> array(
						'id'			=> array('UINT', null, 'auto_increment'),
						'domain'		=> array('VCHAR:20', ''),
						'description'	=> array('VCHAR:50', ''),
					),
					'PRIMARY_KEY'		=> 'id',
					'KEYS'				=> array(
						'domain'		=> array('INDEX', 'domain'),
					)
				),
			)
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'postsstat_config',
				$this->table_prefix . 'postsstat_domains',
			)
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('postsstatistics_version', '3.1.0.RC4')),
			array('module.add', array(
				'acp', 'ACP_QUICK_ACCESS', array(
					'module_basename'	=> '\forumhulp\postsstats\acp\postsstats_module',
					'auth'				=> 'ext_forumhulp/postsstats && acl_a_viewlogs',
					'modes'				=> array('stat'),
				),
			)),
			array('custom', array(
				array(&$this, 'update_tables')
			)),
		);
	}

	public function update_tables()
	{
		global $db;
		// before we fill anything in this table, we truncate it.
		$db->sql_query('TRUNCATE TABLE ' . $this->table_prefix . 'postsstat_config');
		$sql = 'INSERT INTO ' . $this->table_prefix . 'postsstat_config' . ' (start_screen) VALUES("default")';
		$db->sql_query($sql);

		$db->sql_query('TRUNCATE TABLE ' . $this->table_prefix . 'postsstat_domains');

		$sql = 'INSERT INTO ' . $this->table_prefix . 'postsstat_domains VALUES
				(1, "ac", "Ascension Island"), (2, "ad", "Andorra"), (3, "ae", "United Arab Emirates"), (4, "af", "Afghanistan"), (5, "ag", "Antigua and Barbuda"), (6, "ai", "Anguilla"),
				(7, "al", "Albania"), (8, "am", "Armenia"), (9, "an", "Netherlands Antilles"), (10, "ao", "Angola"), (11, "aq", "Antarctica"), (12, "ar", "Argentina"), 
				(13, "as", "American Samoa"), (14, "at", "Austria"), (15, "au", "Australia"), (16, "aw", "Aruba"), (17, "ax", "land"), (18, "az", "Azerbaijan"), 
				(19, "ba", "Bosnia and Herzegovina"), (20, "bb", "Barbados"), (21, "bd", "Bangladesh"), (22, "be", "Belgium"), (23, "bf", "Burkina Faso"), (24, "bg", "Bulgaria"),
				(25, "bh", "Bahrain"), (26, "bi", "Burundi"), (27, "bj", "Benin"), (28, "bm", "Bermuda"), (29, "bn", "Brunei"), (30, "bo", "Bolivia"), (31, "br", "Brazil"),
				(32, "bs", "Bahamas"), (33, "bt", "Bhutan"), (34, "bv", "Bouvet Island"), (35, "bw", "Botswana"), (36, "by", "Belarus"), (37, "bz", "Belize"), (38, "ca", "Canada"),
				(39, "cc", "Cocos (Keeling) Islands"), (40, "cd", "Democratic Republic of the Congo"), (41, "cf", "Central African Republic"), (42, "cg", "Republic of the Congo"),
				(43, "ch", "Switzerland"), (44, "ci", "Côte d\'Ivoire"), (45, "ck", "Cook Islands"), (46, "cl", "Chile"), (47, "cm", "Cameroon"), (48, "cn", "People\'s Republic of China"),
				(49, "co", "Colombia"), (50, "cr", "Costa Rica"), (51, "cs", "Czechoslovakia"), (52, "cu", "Cuba"), (53, "cv", "Cape Verde"), (54, "cw", "Cura‡ao"), 
				(55, "cx", "Christmas Island"), (56, "cy", "Cyprus"), (57, "cz", "Czech Republic"), (58, "dd", "East Germany"), (59, "de", "Germany"), (60, "dj", "Djibouti"),
				(61, "dk", "Denmark"), (62, "dm", "Dominica"), (63, "do", "Dominican Republic"), (64, "dz", "Algeria"), (65, "ec", "Ecuador"), (66, "ee", "Estonia"),(67, "eg", "Egypt"),
				(68, "eh", "Western Sahara"), (69, "er", "Eritrea"), (70, "es", "Spain"), (71, "et", "Ethiopia"), (72, "eu", "European Union"), (73, "fi", "Finland"), (74, "fj", "Fiji"),
				(75, "fk", "Falkland Islands"), (76, "fm", "Federated States of Micronesia"), (77, "fo", "Faroe Islands"), (78, "fr", "France"), (79, "ga", "Gabon"), 
				(80, "gb", "United Kingdom"), (81, "gd", "Grenada"), (82, "ge", "Georgia"), (83, "gf", "French Guiana"), (84, "gg", "Guernsey"), (85, "gh", "Ghana"), 
				(86, "gi", "Gibraltar"), (87, "gl", "Greenland"), (88, "gm", "The Gambia"), (89, "gn", "Guinea"), (90, "gp", "Guadeloupe"), (91, "gq", "Equatorial Guinea"),
				(92, "gr", "Greece"), (93, "gs", "South Georgia and the South Sandwich Islands"), (94, "gt", "Guatemala"), (95, "gu", "Guam"), (96, "gw", "Guinea-Bissau"),
				(97, "gy", "Guyana"), (98, "hk", "Hong Kong"), (99, "hm", "Heard Island and McDonald Islands"), (100, "hn", "Honduras"), (101, "hr", "Croatia"), (102, "ht", "Haiti"),
				(103, "hu", "Hungary"), (104, "id", "Indonesia"), (105, "ie", "Ireland"), (106, "il", "Israel"), (107, "im", "Isle of Man"), (108, "in", "India"), 
				(109, "io", "British Indian Ocean Territory"), (110, "iq", "Iraq"), (111, "ir", "Iran"), (112, "is", "Iceland"), (113, "it", "Italy"), (114, "je", "Jersey"),
				(115, "jm", "Jamaica"), (116, "jo", "Jordan"), (117, "jp", "Japan"), (118, "ke", "Kenya"), (119, "kg", "Kyrgyzstan"), (120, "kh", "Cambodia"), (121, "ki", "Kiribati"),
				(122, "km", "Comoros"), (123, "kn", "Saint Kitts and Nevis"), (124, "kp", "Democratic People\'s Republic of Korea"), (125, "kr", "Republic of Korea"), (126, "kw", "Kuwait"),
				(127, "ky", "Cayman Islands"), (128, "kz", "Kazakhstan"), (129, "la", "Laos"), (130, "lb", "Lebanon"), (131, "lc", "Saint Lucia"), (132, "li", "Liechtenstein"),
				(133, "lk", "Sri Lanka"), (134, "lr", "Liberia"), (135, "ls", "Lesotho"), (136, "lt", "Lithuania"), (137, "lu", "Luxembourg"), (138, "lv", "Latvia"), (139, "ly", "Libya"),
				(140, "ma", "Morocco"), (141, "mc", "Monaco"), (142, "md", "Moldova"), (143, "me", "Montenegro"), (144, "mg", "Madagascar"), (145, "mh", "Marshall Islands"),
				(146, "mk", "Macedonia"), (147, "ml", "Mali"), (148, "mm", "Myanmar"), (149, "mn", "Mongolia"), (150, "mo", "Macau"), (151, "mp", "Northern Mariana Islands"),
				(152, "mq", "Martinique"), (153, "mr", "Mauritania"), (154, "ms", "Montserrat"), (155, "mt", "Malta"), (156, "mu", "Mauritius"), (157, "mv", "Maldives"), 
				(158, "mw", "Malawi"), (159, "mx", "Mexico"), (160, "my", "Malaysia"), (161, "mz", "Mozambique"), (162, "na", "Namibia"), (163, "nc", "New Caledonia"), (164, "ne", "Niger"),
				(165, "nf", "Norfolk Island"), (166, "ng", "Nigeria"), (167, "ni", "Nicaragua"), (168, "nl", "Netherlands"), (169, "no", "Norway"), (170, "np", "Nepal"), 
				(171, "nr", "Nauru"), (172, "nu", "Niue"), (173, "nz", "New Zealand"), (174, "om", "Oman"), (175, "pa", "Panama"), (176, "pe", "Peru"), (177, "pf", "French Polynesia"),
				(178, "pg", "Papua New Guinea"), (179, "ph", "Philippines"), (180, "pk", "Pakistan"), (181, "pl", "Poland"), (182, "pm", "Saint-Pierre and Miquelon"), 
				(183, "pn", "Pitcairn Islands"), (184, "pr", "Puerto Rico"), (185, "ps", "State of Palestine[19]"), (186, "pt", "Portugal"), (187, "pw", "Palau"), (188, "py", "Paraguay"),
				(189, "qa", "Qatar"), (190, "re", "R‚union"), (191, "ro", "Romania"), (192, "rs", "Serbia"), (193, "ru", "Russia"), (194, "rw", "Rwanda"), (195, "sa", "Saudi Arabia"),
				(196, "sb", "Solomon Islands"), (197, "sc", "Seychelles"), (198, "sd", "Sudan"), (199, "se", "Sweden"), (200, "sg", "Singapore"), (201, "sh", "Saint Helena"),
				(202, "si", "Slovenia"), (203, "sj", "Svalbard and Jan Mayen Islands"), (204, "sk", "Slovakia"), (205, "sl", "Sierra Leone"), (206, "sm", "San Marino"), 
				(207, "sn", "Senegal"), (208, "so", "Somalia"),	(209, "sr", "Suriname"), (210, "ss", "South Sudan"), (211, "st", "São Tomé and Príncipe"), (212, "su", "Soviet Union"),
				(213, "sv", "El Salvador"), (214, "sx", "Sint Maarten"), (215, "sy", "Syria"), (216, "sz", "Swaziland"), (217, "tc", "Turks and Caicos Islands"), (218, "td", "Chad"),
				(219, "tf", "French Southern and Antarctic Lands"), (220, "tg", "Togo"), (221, "th", "Thailand"), (222, "tj", "Tajikistan"), (223, "tk", "Tokelau"), 
				(224, "tl", "East Timor"), (225, "tm", "Turkmenistan"), (226, "tn", "Tunisia"), (227, "to", "Tonga"), (228, "tp", "East Timor"), (229, "tr", "Turkey"),
				(230, "tt", "Trinidad and Tobago"), (231, "tv", "Tuvalu"), (232, "tw", "Taiwan"), (233, "tz", "Tanzania"), (234, "ua", "Ukraine"), (235, "ug", "Uganda"),
				(236, "uk", "United Kingdom"), (237, "us", "United States of America"), (238, "uy", "Uruguay"), (239, "uz", "Uzbekistan"), (240, "va", "Vatican City"), 
				(241, "vc", "Saint Vincent and the Grenadines"), (242, "ve", "Venezuela"), (243, "vg", "British Virgin Islands"), (244, "vi", "United States Virgin Islands"),
				(245, "vn", "Vietnam"), (246, "vu", "Vanuatu"), (247, "wf", "Wallis and Futuna"), (248, "ws", "Samoa"), (249, "ye", "Yemen"), (250, "yt", "Mayotte"), 
				(251, "yu", "SFR Yugoslavia"), (252, "za", "South Africa"), (253, "zm", "Zambia"), (254, "zw", "Zimbabwe"), (255, "com", "Commercial"),	(256, "org", "Organization"), 
				(257, "net", "Network"), (258, "lo", "Localhost"), (259, "un", "Unknown")';
		$db->sql_query($sql);
	}
}
