<?php
ini_set('display_errors',1);
require_once '../inc/class.db.php';
require_once '../inc/config.php';

coverageRevert();

function coverageRevert() {
	$today   = date('Y-m-d');
	$nowTime = date('H:i:s');

	$ciphering = 'AES-128-CTR';
	$opts      = 0;
	$enc_iv    = ENCRYPT_IV;
	$enc_key   = ENCRYPT_KEY;

	// Find all users with active coverage that has expired
	$rows = Config::get('db')->get_results("
		SELECT u.id, u.coverUserId, u.coverEndDate, u.coverEndTime,
		       u.first_name_E, u.last_name_E,
		       cu.mobile as coverMobile
		FROM `productiveUser` u
		LEFT JOIN `productiveUser` cu ON cu.id = u.coverUserId
		WHERE u.coverUserId > 0
		AND u.coverEndDate != '0000-00-00'
		AND u.coverEndDate <= '{$today}'
	");

	if (!$rows) {
		echo "No expired coverage records found.\n";
		return;
	}

	$cleared = 0;
	foreach ($rows as $row) {
		$endDate = $row['coverEndDate'];
		$endTime = $row['coverEndTime'];

		// If end date is today and end time is set and not yet passed, skip
		if ($endDate == $today && $endTime != '' && $endTime != '00:00:00') {
			if ($nowTime < $endTime) {
				continue;
			}
		}

		// Send SMS to covering user before clearing
		if (strlen($row['coverMobile']) == 10) {
			$origFirst = openssl_decrypt($row['first_name_E'], $ciphering, $enc_key, $opts, $enc_iv);
			$origLast  = openssl_decrypt($row['last_name_E'],  $ciphering, $enc_key, $opts, $enc_iv);
			$covMob    = $row['coverMobile'];
			$psword    = 'J8775bcgEE2065';
			$keyword4  = 'PN18337370906';
			$slooce    = 'jobalarm45';
			$now       = time();
			$endMsg    = 'Your coverage assignment for ' . $origLast . ', ' . $origFirst . ' has ended. No further alerts will be routed to you.';
			$endXml    = '<message id="' . $covMob . $now . '"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $endMsg . ']]></content></message>';
			$url       = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';
			$header    = array('Content-Type: application/xml');
			curl_request($slooce, $url, $header, $endXml);
		}

		// Clear coverage
		$data = array(
			'coverUserId'      => 0,
			'coverStartDate'   => '0000-00-00',
			'coverStartTime'   => '',
			'coverEndDate'     => '0000-00-00',
			'coverEndTime'     => '',
			'coverPauseSms'    => 0,
			'coverVarianceMin' => 1,
			'coverQblMin'      => 300
		);
		$where = array('id' => intval($row['id']));
		Config::get('db')->update('productiveUser', $data, $where);
		$cleared++;
		echo "Cleared coverage for user {$row['id']}\n";
	}

	echo "Done. Cleared {$cleared} record(s).\n";
}
