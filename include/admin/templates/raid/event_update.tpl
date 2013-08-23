<form id="standart" name="form" method="post" action="admin.php?raid-updateEvent">
	<input type="hidden" name="created" value="{$event.created}">
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
			<select name="status" size="8">
				{html_options options=$status selected=$event.status}
			</select>
		</td>
		<td align="center">
			<select name="leader" size="8">
				{html_options options=$leader selected=$event.leader}
			</select>
		</td>
		<td align="center">
			<select name="group" size="8">
				{html_options options=$gruppe selected=$event.group}
			</select>
		</td>
		<td align="center">
			<select name="dungeon" size="8">
				{html_options options=$inzen selected=$event.dungeon}
			 </select>
		</td>
		<td align="center">
			<select name="time" size="8">
			{foreach $time as $i}
				<option hide=".timeManual" value="{$i.id}" tooltip="{$i.time}" {if $i.id==$event.time} selected="selected"{/if}>{$i.info}</option>
			{/foreach}
				<option toggle=".timeManual" value="0" {if 0==$event.time} selected="selected"{/if}>Zeit Manuel festlegen</option>
			 </select>
		</td>
	  </tr>
	  <tr class='Cnorm'> 
		<td colspan="6">
		  <span id="radio">
	      </span>
			<input id="datepicker" name="from" value="{$event.from}" maxlength="10" size="12" />
		</td>
	  </tr>
	  <tr class='Cnorm hide timeManual' tooltip="Sie k&ouml;nnen mit dem Mausrad die Zeit einstellen."> 
		<td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">
			<input id="timeWheel" type="text" name="inv" value="{$event.inv|date_format:"H:i"}" size="5">
			<input id="timeWheel" type="text" name="pull" value="{$event.pull|date_format:"H:i"}" size="5">
			<input id="timeWheel" type="text" name="end" value="{$event.end|date_format:"H:i"}" size="5">
			<input id="intWheel" type="text" name="lock" value="{$event.lock}" size="2">
		</td>
	  </tr>
	  <tr class='Cdark'> 
		<td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">{$bbcode}</td>
	  </tr>
	  <tr class='Cnorm'>
		<td colspan="6"><textarea name="txt" cols="110" rows="8" id="txt">{$event.txt}</textarea></td>
	  </tr>
	  <tr class='Cdark'> 
		<td colspan="6" align="center">
		  <input type="submit" name="event" value="update Event">
		  <input type="reset" name="button" id="button" value="Zur&uuml;cksetzen" /></td>
	  </tr>
	</table>
</form>