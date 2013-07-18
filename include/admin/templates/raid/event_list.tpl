<table width="100%" border="0" cellspacing="1" cellpadding="2" class='border ubuntu'>
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
	<tbody id="eventsList">
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
		<td class="Chead" colspan="7">alle {$events|count} Events in diesem Monat</td>
	</tr>
	<tr>
		<th class="Cdark" align="center"></th>
		<th class="Cdark" width="20">Options</th>
		<th class="Cdark">Event Datum</th>
		<th class="Cdark" align="center">Dungeon</th>
		<th class="Cdark" align="center">Leader</th>
		<th class="Cdark" align="center">Gruppe</th>
		<th class="Cdark" align="center">Status</th>
	</tr>
	{if $events|count=='0'}
	<tr>
		<td class="Cnorm" colspan="7">{status id=2}Es gibt keine Events zum {$monthName[$smarty.post.month]} {$smarty.post.year}{/status}</td>
	</tr>
	{else}
	{foreach $events as $i}
	<tr id="event{$i.id}">
		<td class="Cdark" align="center" style=""><b>{$i.alias}{$i.size}</b></td>
		<td class="Cmite" style="max-width: 56px;">		
			<ul class="automenu ubuntu">
				<li>
					<a href="#"><span class="ui-icon ui-icon-gear"></span>Optionen</a>
					<ul>
						<li>
							<a href="admin.php?raid-update-{$i.id}" fancybox="inline"><span class="ui-icon ui-icon-person"></span>Spieler</a>
						</li>
						<li>
							<a href="#"><span class="ui-icon ui-icon-gear"></span>Status</a>
							<ul>
								
								{foreach from=$status key=key item=value}
								<li class="{if $i.status == $key}ui-state-disabled{/if}"><a href="#"><span class="ui-icon ui-icon-triangle-1-e"></span>{$value}</a></li>
								{/foreach}
							</ul>
						</li>
						{perm authmod=removeEvent}
							<li>
								<a confirm="confirm" href="admin.php?raid-removeEvent" perm="id={$i.id}" remove="#event{$i.id}"><span class="ui-icon ui-icon-trash"></span>L&ouml;schen</a>
								<confirm>
									{status id=2}M&ouml;chten Sie den Event vom {$i.inv|date_format:"%d.%m.%Y"} ({$i.info}) wirklich L&ouml;schen?{/status}
								</confirm>
							</li>
						{/perm}
						
						{if $i.cycle >= 1 }
						{perm authmod=removeEventsMulti}
						<li>
							<a confirm="confirm" href="admin.php?raid-removeEventsMulti" perm="id={$i.id}&created={$i.created}"><span class="ui-icon ui-icon-trash"></span>alle L&ouml;schen</a>
							<confirm>
								{status id=2}M&ouml;chten Sie alle dazugeh&ouml;rigen Events vom {$i.inv|date_format:"%d.%m.%Y"} ({$i.info}) wirklich L&ouml;schen?{/status}
							</confirm>
						</li>
						{/perm}
						{/if}
						
						{perm authmod=updateEvent}
						<li>
							<a href="admin.php?raid-update-{$i.id}" fancybox="inline"><span class="ui-icon ui-icon-gear"></span>Bearbeiten</a>
						</li>
						{/perm}
						
					</ul>
				</li>
			</ul>
		</td>
		<td class="Cmite">
			
			<div class="left">
			<span style="width: 30px;"><b>{$i.inv|date_format:"%a"}</span> 
			<span>{$i.inv|date_format:"%d.%m.%Y"}</b> {if isset($i.info)}<span class="ubuntu">({$i.info})</span>{/if}</span>
			<br />
			<span class="left small ubuntu">Invite: {$i.inv|date_format:"%H:%M"} | Pull: {$i.pull|date_format:"%H:%M"} | Ende: {$i.end|date_format:"%H:%M"}</span>
			</div>
			
			<div class="right buttonset">
				<a icon="ui-icon-person" href="#">
						{$i.registrations}/{$i.size}
				</a>
				
				<a onlyIcon="ui-icon-notice" href="#">info</a>
			</div>
			
		</td>
		<td class="Cmite" align="center">{$i.nameDungeon} ({$i.size})</td>
		<td class="Cmite" align="center">{$i.nameLeader}</td>
		<td class="Cmite" align="center">{$i.nameGroup}</td>
		<td class="statusMsg" align="center" style="{$i.style}">{$i.nameStatus}</td>
	</tr>
	{/foreach}
	{/if}
	</tbody>
	<tr>
		<td width="50%" class="Cdark" colspan="7"></td>
	</tr>
</table>