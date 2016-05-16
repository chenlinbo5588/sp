var jumpUrl = {json_encode($jumUrl)};

{if !empty($feedback)}alert("{$feedback}");{/if}
{if $registerOk}
		location.href= jumpUrl['{$jumUrlType}'];
{/if}