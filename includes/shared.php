<?php

function checkPluginSettings()
{
	$pluginSettings['amazon_access_key'] = get_option('amazon_access_key');
	$pluginSettings['amazon_secret_access_key'] = get_option('amazon_secret_access_key');
	$pluginSettings['amazon_url'] = get_option('amazon_url');
	$pluginSettings['amazon_video_bucket'] = get_option('amazon_video_bucket');

	return $pluginSettings;
}
