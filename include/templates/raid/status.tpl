<div class="status">
	{foreach key=curStatus item=array from=$status}
	<div class="status_{$curStatus}" timeout="{if $array.timeout|min == 0}0{else}{$array.timeout|max}{/if}">
		<ol>
		{foreach key=k item=v from=$array.status}
			<li timeout="{$array.timeout[$k]}" tooltip="{$array.script[$k]}">{$array.message[$k]}</li>
		{/foreach}
		</ol>
	</div>
	{/foreach}
</div>