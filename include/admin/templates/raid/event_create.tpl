<form id="standart" name="form" method="post" action="admin.php?raid-createEvent">
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
				{html_options options=$status}
			</select>
		</td>
		<td align="center">
			<select name="leader" size="8">
				{html_options options=$leader}
			</select>
		</td>
		<td align="center">
			<select name="group" size="8">
				{html_options options=$gruppe}
			</select>
		</td>
		<td align="center">
			<select name="dungeon" size="8">
				{html_options options=$inzen}
			 </select>
		</td>
		<td align="center">
			<select name="time" size="8">
			{foreach $time as $i}
				<option hide=".timeManual" value="{$i.id}" tooltip="{$i.time}">{$i.info}</option>
			{/foreach}
				<option toggle=".timeManual" value="0">Zeit Manuel festlegen</option>
			 </select>
		</td>
	  </tr>
	  <tr class='Cnorm'> 
		<td colspan="6">
		  <span id="radio">
			{assign "multi" "0"}
			{foreach from=$zyklus key=id item=val}
		    <input id="radio{$id}" type="radio" name="cycle" value="{$id}" {if $id==$multi}checked="checked"{/if} /><label for="radio{$id}">{$val}</label>
			{/foreach}
	      </span>
		  <span id="vonbis" style="display: none;">Vom </span><input id="datepicker" name="from" value="{"today"|strtotime|date_format:"d.m.Y"} " maxlength="10" size="12" />
		  <span id="vonbis" style="display: none;"> bis zum <input id="datepicker2" name="to" value="" maxlength="10" size="12" /></span> <em>*</em>
		</td>
	  </tr>
	  <tr class='Cnorm hide timeManual' tooltip="Sie k&ouml;nnen mit dem Mausrad die Zeit einstellen."> 
		<td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">
			<input id="timeWheel" type="text" name="inv" value="18:00" size="5">
			<input id="timeWheel" type="text" name="pull" value="18:15" size="5">
			<input id="timeWheel" type="text" name="end" value="22:00" size="5">
			<input id="intWheel" type="text" name="lock" value="2" size="2">
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