<table width="100%" border="0" cellspacing="1" cellpadding="2" class='border'>
	<tr>
		<th class="Chead" colspan="7">Event Optionen</th>
	</tr>
	<tr>
		<td class="Cmite" colspan="7">
			<div class="buttonset left">
			{perm authmod=createEvent}<a class="button" href="admin.php?raid-create" fancybox="inline">Event Erstellen</a>{/perm}
			<a class="button" href="index.php?raidlist-kalender" fancybox="kalender">Kalender Ansicht</a>
			</div>
			<div style="float: right;">
				{perm authmod=removeEvents}
					<a class="button" href="admin.php?raid-removeEvents" confirm="confirm">alle Events L&ouml;schen</a>
					<confirm>{status id=2}ACHTUNG! Es werden alle Events und Anmeldungen aus der Datenbank unwiederruflich gel&ouml;scht! Wollen sie wirklich die Aktion ausf&uuml;hren?{/status}</confirm>
				{/perm}
			</div>
		</td>
	</tr>
	<tr>
		<th class="Chead" colspan="7">Event Filter: Monate & Jahre in denen Events sind!</th>
	</tr>
	<tbody id="eventsList" class="ubuntu">
	<tr>
		<td class="Cnorm" colspan="7">
			<form id="filter" class="filter" action="admin.php?raid" method="post" replace="#eventsList">
				<input type="hidden" name="day" value="{'today'|date_format:'%d'}" />
				<span id="radio" class="left">
				{foreach from=$months key=id item=value}
					<!--<a class="button {if ('today'|date_format:'%m') == $id}ui-active-state{/if}">{$value}</a>-->
					<input id="radio{$value}" type="radio" name="month" value="{$id}" {if $id==$smarty.post.month} checked="checked"{/if} />
					<label for="radio{$value}" tooltip="{$monthName[$value|intval]}">{$value}</label>
				{/foreach}
				</span>
				<span id="radio" class="right">
				{foreach from=$jahre item=year}
					<!--<a class="button {if ('today'|date_format:'%m') == $id}ui-active-state{/if}">{$value}</a>-->
					<input id="radio{$year}" type="radio" name="year" value="{$year}" {if $smarty.post.year==$year}checked="checked"{/if} />
					<label for="radio{$year}">{$year}</label>
				{/foreach}
				</span>
			</form>
		</td>
	</tr>
	<tr>
		<th class="Chead" colspan="7">alle {$events|count} Events in diesem Monat</th>
	</tr>
	<tr>
		<th class="Cdark" align="center">#id</th>
		<th class="Cdark">Event Datum</th>
		<th class="Cdark" align="center">Dungeon</th>
		<th class="Cdark" align="center">Leader</th>
		<th class="Cdark" align="center">Gruppe</th>
		<th class="Cdark" align="center">Status</th>
		<th class="Cdark" width="1">Options</th>
	</tr>
	{if $events|count=='0'}
	<tr>
		<td class="Cnorm" colspan="7">{status id=2}Es gibt keine Events zum {$monthName[$smarty.post.month]} {$smarty.post.year}{/status}</td>
	</tr>
	{else}
	{foreach $events as $i}
	<tr id="event{$i.id}">
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.id}:{$i.multi}</td>
		<td class="Cnorm">
			
			<div style="float:left; width: 30px;"><b>{$i.inv|date_format:"%a"}</div> 
			<div style="float:left;">{$i.inv|date_format:"%d.%m.%Y"}</b> <span class="ubuntu">({$i.info})</span></div>
			<br style="clear: both;" />
			<span class="small ubuntu">Invite: {$i.start} | Pull: {$i.begin} | Ende: {$i.ende}</span>
			
		</td>
		<td class="Cnorm" align="center"><b>{$i.alias}{$i.size}</b><br />{$i.inzen}</td>
		<td class="Cnorm" align="center">{$i.leader}</td>
		<td class="Cnorm" align="center">{$i.grpname}</td>
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.statusmsg}</td>
		<td class="Cnorm" align="center">
			{perm authmod=removeEvent}
				<a confirm="confirm" href="admin.php?raid-removeEvent" perm="id={$i.id}" remove="#event{$i.id}">{'cancel'|icon}</a>
				<confirm>
					{status id=2}M&ouml;chten Sie den Event vom {$i.inv|date_format:"%d.%m.%Y"} ({$i.info}) wirklich L&ouml;schen?{/status}
				</confirm>
			{/perm}
			
			{if $i.multi >= 1 }
				{perm authmod=removeEventsMulti}
					<a confirm="confirm" href="admin.php?raid-removeEventsMulti" perm="id={$i.id}&erstellt={$i.erstellt}"  tooltip="Alle dazugeh&ouml;rigen Events L&ouml;schen">{'cancel'|icon}</a>
					<confirm>
						{status id=2}M&ouml;chten Sie alle dazugeh&ouml;rigen Events vom {$i.inv|date_format:"%d.%m.%Y"} ({$i.info}) wirklich L&ouml;schen?{/status}
					</confirm>
				{/perm}
			{/if}
			
			{'smart'|icon}
		</td>
	</tr>
	{/foreach}
	{/if}
	</tbody>
	<tr>
		<td width="50%" class="Cmite" colspan="7"></td>
	</tr>
</table>