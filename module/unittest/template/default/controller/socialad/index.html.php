<form method="POST" action='{url link='unittest.socialad'}'>
{foreach from=$aTestSuites item=aTestSuite}
	<input type="checkbox" name="val[test_suite][]" value="{$aTestSuite.id}" class="jsTestSuite">{$aTestSuite.description}<br>
{/foreach}

	<input type="submit" class="button" value="Kill it!!!!" />
	<input type="button" class="button" value="Select all" onclick="$('.jsTestSuite').attr('checked', 'checked'); return false;"/>
</form>
