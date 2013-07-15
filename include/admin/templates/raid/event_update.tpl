<form id="standart" name="form" method="post" action="admin.php?raid-updateEvent">
	<input type="hidden" name="id" value="{$event.id}">
	<input type="hidden" name="zyklus" value="{$event.multi}">
	<input type="hidden" name="erstellt" value="{$event.erstellt}">
	<table width="50%" border="0" cellspacing="1" cellpadding="2" class='border'>
	  <tr class='Chead' align="center"> 
		<td>Status</td>
		<td>Leader</td>
		<td>Gruppe</td>
		<td>Dungeon</td>
		<td>Zeit</td>
	  </tr>
	  
	  <tr class='Cmite'> 
		<td align="center">
			<select name="statusmsg" size="8">
				{html_options options=$status selected=$event.statusmsg}
			</select>
		</td>
		<td align="center">
			<select name="leader" size="8">
				{html_options options=$leader selected=$event.leader}
			</select>
		</td>
		<td align="center">
			<select name="gruppen" size="8">
				{html_options options=$gruppe selected=$event.gruppen}
			</select>
		</td>
		<td align="center">
			<select name="inzen" size="8">
				{html_options options=$inzen selected=$event.inzen}
			 </select>
		</td>
		<td align="center">
			<select name="time" size="8">
			{foreach $time as $i}
				<option hide=".timeManual" value="{$i.id}" tooltip="{$i.time}" {if $i.id==$event.time} selected="selected"{/if}>{$i.info}</option>
			{/foreach}
				<option toggle=".timeManual" value="0">Zeit Manuel festlegen</option>
			 </select>
		</td>
	  </tr>
	  <tr class='Cmite'> 
		<td colspan="6">
			<div class="left">
				<input  value="Datum" maxlength="10" size="12" readonly="readonly" style="text-align: right;" />:
				<input id="datepicker" name="startdate" value="{"today"|strtotime|date_format:"d.m.Y"} " maxlength="10" size="12" />
			</div>
			
			<span id="radio" class="right">
				<input type="radio" name="events" value="0" id="multi0" checked="checked" /><label for="multi0">nur diesen Event</label>
				{if $event.multi >= 1 }<input type="radio" name="events" value="1" id="multi1" /><label for="multi1">alle Events aus der Serie</label>{/if}
			</span>
		</td>
	  </tr>
	  <tr class='Cnorm hide timeManual' tooltip="Sie k&ouml;nnen mit dem Mausrad die Zeit einstellen."> 
		<td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">
			<input id="timeWheel" type="text" name="start" value="{$event.inv|date_format:'H:i'}" size="5">
			<input id="timeWheel" type="text" name="begin" value="18:15" size="5">
			<input id="timeWheel" type="text" name="ende" value="22:00" size="5">
			<input id="intWheel" type="text" name="sperre" value="2" size="2">
		</td>
	  </tr>
	  <tr class='Cdark'> 
		<td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">{$bbcode}</td>
	  </tr>
	  <tr class='Cnorm'>
		<td colspan="6"><textarea name="txt" cols="110" rows="8" id="txt"></textarea></td>
	  </tr>
	  <tr class='Cdark'> 
		<td colspan="6" align="center">
		  <input type="submit" name="Submit" value="{$button}">
		  <input type="reset" name="button" id="button" value="Zur&uuml;cksetzen" /></td>
	  </tr>
	</table>
</form>
{debug}