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
				{perm authmod=removeEvent}
					<a class="button" href="admin.php?raid-delAll" confirm="ACHTUNG! Es werden alle Events und Anmeldungen aus der Datenbank unwiederruflich gel&ouml;scht! Wollen sie wirklich die Aktion ausf&uuml;hren?">alle Events L&ouml;schen</a>
				{/perm}
			</div>
		</td>
	</tr>
	<tr>
		<th class="Chead" colspan="7">Event Filter</th>
	</tr>
	<tr>
		<td class="Cnorm" colspan="7">
			{debug}
			<span id="radio" class="left">
			{foreach from=$monatsnamen key=id item=value}
				<!--<a class="button {if ('today'|date_format:'%m') == $id}ui-active-state{/if}">{$value}</a>-->
				<input id="radio{$value}" type="radio" name="monat" value="{$value}" {if $id==('today'|date_format:'%m')}checked="checked"{/if} /><label for="radio{$value}">{$value}</label>
			{/foreach}
			</span>
			<span id="radio" class="right">
			{foreach from=$jahre item=year}
				<!--<a class="button {if ('today'|date_format:'%m') == $id}ui-active-state{/if}">{$value}</a>-->
				<input id="radio{$year}" type="radio" name="jahr" value="{$i.year}" {if ('today'|date_format:'%Y')==$year}checked="checked"{/if} /><label for="radio{$year}">{$year}</label>
			{/foreach}
			</span>
		</td>
	</tr>
	<tr>
		<th class="Chead" colspan="7">alle Events in diesem Monat</th>
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
	{foreach $events as $i}
	<tr>
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.id}</td>
		<td class="Cnorm">
			
				<div style="float:left; width: 30px;"><b>{$i.inv|date_format:"%a"}</div> 
				<div style="float:left;">{$i.inv|date_format:"%d.%m.%Y"}</b> <span class="ubuntu">({$i.info})</span></div>
				<br style="clear: both;" />
				<span class="small ubuntu">Invite: {$i.start} | Pull: {$i.begin} | Ende: {$i.ende}</span>
			
		</td>
		<td class="Cnorm" align="center">{$i.inzen}</td>
		<td class="Cnorm" align="center">{$i.leader}</td>
		<td class="Cnorm" align="center">{$i.grpname}</td>
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.statusmsg}</td>
		<td class="Cnorm" align="center">
			{'smart'|icon}
			{'cancel'|icon}
		</td>
	</tr>
	{/foreach}
	<tr>
		<td width="50%" class="Cmite" colspan="7"></td>
	</tr>
</table>