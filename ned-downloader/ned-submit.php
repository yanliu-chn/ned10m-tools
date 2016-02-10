<?php
# Author: Yan Liu (yanliu@uiuc.edu)
# Date: May 09, 2011
# submit ned request to gisdata.usgs.gov
# return requestID and serviceEndpoint
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
$rurl = $argv[1]; # whole url string
$urlroot = $argv[2]; # where original page root is
$s1 = preg_split("/DownloadPage=/", $rurl);
if (empty($s1[1])) {
	e("Could not get DownloadPage URL");
}
$download_url = $s1[1];
$s2 = preg_split("/initiateDownload\?/", $download_url);
if (empty($s2[0])) {
	e("Could not get downloadServiceEndPoint");
}
$downloadServiceEndPoint = $s2[0];
$url = $urlroot . 'json_wrapper.php?SERVICE=DL_SERVICE&OP=initiateDownload&downloadServiceEndPoint=' . $downloadServiceEndPoint . 'initiateDownload?' . $s2[1];
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
	print $r['service_response'] . " " . $downloadServiceEndPoint . "\n";
} else {
	e("curl returns error");
}
?>
