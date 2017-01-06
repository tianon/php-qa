<?php /* $Id$ */

/*
What this file does:
	- Generates the download links found at qa.php.net
	- Determines which test results are emailed to news.php.net/php.qa.reports
	- Defines $QA_RELEASES for internal and external (api.php) use, contains all qa related information for future PHP releases

Documentation:
	$QA_RELEASES documentation:
		Configuration:
		- Key is future PHP version number
			- Example: If 5.3.6 is the latest stable release, then use 5.3.7 because 5.3.7-dev is our qa version
			- Typically, this is the only part needing changed
		- active (bool): 
			- It's active and being tested here 
			- Meaning, the version will be reported to the qa.reports list, and be linked at qa.php.net
			- File extensions .tar.gz and .tar.bz2 are assumed to be available
		- release (array):
			- type: RC, alpha, and beta are examples (case should match filename case)
			- version: 0 if no such release exists, otherwise an integer of the rc/alpha/beta number
			- md5_bz2: md5 checksum of this downloadable .tar.bz2 file
			- md5_gz:  md5 checksum of this downloadable .tar.gz file
			- md5_xz: md5 checksum of this downloadble .xz file
			- date: date of release e.g., 21 May 2011
			- baseurl: base url of where these downloads are located
			- Multiple checksums can be available, see the $QA_CHECKSUM_TYPES array below
		Other variables within $QA_RELEASES are later defined including:
			- reported: versions that make it to the qa.reports mailing list
			- release: all current qa releases, including paths to dl urls (w/ md5 info)
			- dev_version: dev version
			- $QA_RELEASES is made available at qa.php.net/api.php

TODO:
	- Save all reports (on qa server) for all tests, categorize by PHP version (see buildtest-process.php)
	- Consider storing rc downloads at one location, independent of release master
	- Determine best way to handle rc baseurl, currently assumes .tar.gz/tar.bz2 will exist
	- Determine if $QA_RELEASES is compatible with all current, and most future configurations
	- Determine if $QA_RELEASES can be simplified
	- Determine if alpha/beta options are desired
	- Unify then create defaults for most settings
	- Add option to allow current releases (e.g., retrieve current release info via daily cron, cache, check, configure ~ALLOW_CURRENT_RELEASES)
*/

$QA_RELEASES = array(
	'5.6.30' => array(
		'active'		=> true,
		'release'		=> array(
			'type'	    	=> 'RC',
			'number'    	=> 1,
			'md5_bz2'   	=> '042bc241b39b42f398e6ad7df95cc5bf',
			'md5_gz'    	=> '3c2a6c2e5b00cb33adb734a05a2ec306',
			'md5_xz'    	=> 'f3646e8a7bf28dbd8d99cb908edf6093',
			'sha256_bz2'	=> 'c8c6b378685d7051686364e3b5f15be1dd0804616171fa1f7be3866948a30628',
			'sha256_gz'	=> 'c848b3b0dcb811a824ea8169cdfa2b9cf7736be3f92eae3f6ad1a1e0efcd194b',
			'sha256_xz'	=> '7950bacb586152ddf4a054d86d1a926e9acc80148658c5eef84cce36fe74016e',
			'date'      	=> '05 January 2017',
			'baseurl'   	=> 'http://downloads.php.net/tyrael/',
		),
	),

        '7.0.15' => array(
                'active'                => true,
                'release'               => array(
                        'type'      	=> 'RC',
                        'number'    	=> 1,
                        'md5_bz2'   	=> '57665fccccc72c9fe28a82aaa780daf4',
                        'md5_gz'    	=> '4d26a7c57784f8a0f54833d7e43781ee',
                        'md5_xz'    	=> '4c0c6d6236e39054527fa12bddac72a7',
			'sha256_bz2'	=> 'e875e749d4f9d972934e5086fe6de0c1eeac7711ed1f5960b52b7be4d10fae6d',
			'sha256_gz'	=> 'dc29a8213e4bc7500a6d12218db985736bd846c0b4ae7d5ef6a1398bd84e5cd2',
			'sha256_xz'	=> 'c9391caea5288ba457afd1b01c9e250cc17ecf2a9789ee1f50cbe8e10610c138',
                        'date'      	=> '05 January 2017',
                        'baseurl'   	=> 'http://downloads.php.net/ab/',
                ),
        ),

        '7.1.0' => array(
                'active'                => true,
                'release'		=> array(
                        'type'          => 'RC',
                        'number'        => 6,
                        'md5_bz2'       => 'f9242579d2305b005c84c515e2f57b2a',
                        'md5_gz'        => '1239bc0566d0b8fd18165fdd3aed354f',
                        'md5_xz'        => '26bb2d5dc690b3463a0fea3d71fcf935',
                        'sha256_bz2'    => '7ed531c0697350ee22ac2b5ae3caf6c50643e773a2a61aace990afe9be305d1b',
                        'sha256_gz'     => '82207f7954f5e35da24072f296dd204d261e61df16eca925c8d4e2280173705b',
                        'sha256_xz'     => '3812b54ff84b32cb3750994088161e9c6455000499f4716b635b7c1e64a75a2c',
                        'date'          => '10 November 2016',
                        'baseurl'       => 'http://downloads.php.net/~krakjoe/',
                ),
	)
);

// This is a list of the possible checksum values that can be supplied with a QA release. Any 
// new algorithm is read from the $QA_RELEASES array under the 'release' index for each version 
// in the form of "$algorithm_$filetype".
//
// For example, if SHA256 were to be supported, the following indices would have to be added:
//
// 'sha256_bz2' => 'xxx', 
// 'sha256_gz'	=> 'xxx', 
// 'sha256_xz'	=> 'xxx', 

$QA_CHECKSUM_TYPES = Array(
				'md5', 
				'sha256'
				);

/*** End Configuration *******************************************************************/

// $QA_RELEASES eventually contains just about everything, also for external use
// release  : These are encouraged for use (e.g., linked at qa.php.net)
// reported : These are allowed to report @ the php.qa.reports mailing list

foreach ($QA_RELEASES as $pversion => $info) {

	if (isset($info['active']) && $info['active']) {
	
		// Allow -dev versions of all active types
		// Example: 5.3.6-dev
		$QA_RELEASES['reported'][] = "{$pversion}-dev";
		$QA_RELEASES[$pversion]['dev_version'] = "{$pversion}-dev";
		
		// Allow -dev version of upcoming qa releases (rc/alpha/beta)
		// @todo confirm this php version format for all dev versions
		if ((int)$info['release']['number'] > 0) {
			$QA_RELEASES['reported'][] = "{$pversion}{$info['release']['type']}{$info['release']['number']}";
			if (!empty($info['release']['baseurl'])) {
				
				// php.net filename format for qa releases
				// example: php-5.3.0RC2
				$fn_base = 'php-' . $pversion . $info['release']['type'] . $info['release']['number'];

				$QA_RELEASES[$pversion]['release']['version'] = $pversion . $info['release']['type'] . $info['release']['number'];
				$QA_RELEASES[$pversion]['release']['files']['bz2']['path']= $info['release']['baseurl'] . $fn_base . '.tar.bz2'; 
				$QA_RELEASES[$pversion]['release']['files']['gz']['path'] = $info['release']['baseurl'] . $fn_base . '.tar.gz';

				foreach($QA_CHECKSUM_TYPES as $algo)
				{
					$QA_RELEASES[$pversion]['release']['files']['bz2'][$algo] = $info['release'][$algo . '_bz2'];
					$QA_RELEASES[$pversion]['release']['files']['gz'][$algo]  = $info['release'][$algo . '_gz'];

					if (!empty($info['release'][$algo . '_xz'])) {
						if(!isset($QA_RELEASES[$pversion]['release']['files']['xz']))
						{
							$QA_RELEASES[$pversion]['release']['files']['xz']['path'] = $info['release']['baseurl'] . $fn_base . '.tar.xz';
						}

						$QA_RELEASES[$pversion]['release']['files']['xz'][$algo]  = $info['release'][$algo . '_xz'];
					}
				}
			}
		} else {
			$QA_RELEASES[$pversion]['release']['enabled'] = false;
		}
	}
}

// Sorted information for later use
// @todo need these?
// $QA_RELEASES['releases']   : All current versions with active qa releases
foreach ($QA_RELEASES as $pversion => $info) {
	if (isset($info['active']) && $info['active'] && !empty($info['release']['number'])) {
		$QA_RELEASES['releases'][$pversion] = $info['release'];
	}
}

/* Content */
function show_release_qa($QA_RELEASES) {
	// The checksum configuration array
	global $QA_CHECKSUM_TYPES;

	echo "<!-- RELEASE QA -->\n";
	
	if (!empty($QA_RELEASES['releases'])) {
		
		$plural = count($QA_RELEASES['releases']) > 1 ? 's' : '';
		
		// QA Releases
		echo "<span class='lihack'>\n";
		echo "Providing QA for the following <a href='/rc.php'>test release{$plural}</a>:<br> <br>\n";
		echo "</span>\n";
		echo "<table>\n";

		// @todo check for vars, like if md5_* are set
		foreach ($QA_RELEASES['releases'] as $pversion => $info) {

			echo "<tr>\n";
			echo "<td colspan=\"" . (sizeof($QA_CHECKSUM_TYPES) + 1) . "\">\n";
			echo "<h3 style=\"margin: 0px;\">{$info['version']}</h3>\n";
			echo "</td>\n";
			echo "</tr>\n";

			foreach (Array('bz2', 'gz', 'xz') as $file_type) {
				if (!isset($info['files'][$file_type])) {
					continue;
				}

				echo "<tr>\n";
				echo "<td width=\"20%\"><a href=\"{$info['files'][$file_type]['path']}\">php-{$info['version']}.tar.{$file_type}</a></td>\n";

				foreach ($QA_CHECKSUM_TYPES as $algo) {
					echo '<td>';
					echo '<strong>' . strtoupper($algo) . ':</strong> ';

					if (isset($info['files'][$file_type][$algo]) && !empty($info['files'][$file_type][$algo])) {
						echo $info['files'][$file_type][$algo];
					} else {
						echo '(<em><small>No checksum value available</small></em>)&nbsp;';
					}

					echo "</td>\n";
				}

				echo "</tr>\n";
			}
		}

		echo "</table>\n";
	}

	echo "<!-- END -->\n";
}
