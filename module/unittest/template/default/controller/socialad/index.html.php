<form method="POST" action='{url link='unittest.socialad'}'>
{foreach from=$aTestSuites item=aTestSuite}
	<input type="checkbox" name="val[test_suite][]" value="{$aTestSuite.id}">{$aTestSuite.description}<br>
{/foreach}

	<input type="submit" class="button" value="Kill it!!!!" />
</form>
