<?php
;

if(defined('YOUNET_IN_UNITTEST')) {
	$bIsAdmin = Phpfox::getService('unittest.test.socialad')->getIsAdmin();
	$iUserId = Phpfox::getService('unittest.test.socialad')->getUserId();
	$bCanDenyApproveAd = Phpfox::getService('unittest.test.socialad')->getCanDenyApproveAd();
	$bRedirect = false;
};
;
