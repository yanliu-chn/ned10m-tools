<?php
# Author: Yan Liu (yanliu@uiuc.edu)
# Date: May 09, 2011
# Check ned dataserver status upon downloading request processing
$rurl = "";
$urlroot = "";
$download_url = "";
$downloadServiceEndPoint= "";
$reqid = "";
$url = "";
$result="";
function p($msg) {
	global $rurl, $urlroot, $download_url, $reqid, $downloadServiceEndPoint, $url, $result;
	print "ERROR: $msg\n";
	print "INFO: \n";
	print "\tOrig URL: $rurl\n";
	print "\tURL root: $urlroot\n";
	print "\tDownload URL: $download_url\n";
	print "\tRequestID: $reqid\n";
	print "\tdownloadServiceEndPoint: $downloadServiceEndPoint\n";
	print "\tcurl url: $url\n";
	print "\tcurl result: " . var_export($result, true) . "\n";
}
function e($msg = "Generic Error") {
	p($msg);
	exit(1);
};
$urlroot = $argv[1]; # where original page root is
$reqid = $argv[2];
$downloadServiceEndPoint = $argv[3];
$url = $urlroot . 'json_wrapper.php?SERVICE=DL_SERVICE&OP=getDownloadStatus&requestID=' . $reqid . '&downloadServiceEndPoint=' . $downloadServiceEndPoint;
$curl = curl_init();
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 200);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl, CURLOPT_URL, $url);
$result = curl_exec($curl);
curl_close($curl);
$r = json_decode($result, true);
if (!empty($r) && isset($r['service_response'])) {
	$intval = (int)$r['service_response']; # ignore text after number
	if ($intval == 400) {
		print $downloadServiceEndPoint . "getData?downloadID=" . $reqid . "\n";
	} else {
		print "\n";
	}
} else {
	e("curl returns error");
}
?>
