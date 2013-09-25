Results 
<div class="clear"></div>
<ul >
	{foreach from=$aResults item=aResult}
	<li> 
		<!-- if this case is true -->
		{if $aResult.result} 
			OK 
		{else}
			<div style="color:red"> Failed : {$aResult.message} </div>
		{/if}
	</li>
	{/foreach}
</ul>
