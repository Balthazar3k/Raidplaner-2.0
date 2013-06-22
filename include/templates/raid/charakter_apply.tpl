{if {$smarty.session.authid} == 0 || {$smarty.session.authid} == 1}
	{literal}
	<script type="text/javascript">
		$(document).ready(function(){$("#apply").validate({messages:{password2: "Bitte geben sie das gleiche Passwort ein!"}});});
	</script>
	{/literal}
	
	<form id="apply" method="post" action="index.php?bewerbung-submitted">
		<p>{'bewerbung'|cfg}</p>
		<table width="100%" cellspacing="1" cellpadding="5" border="0" class="border">
			<tr class="Chead" >
				<th colspan="2">Bewerbungs Formular</th>
			</tr>
			<tr class="Cmite">
				<td align="right" width="175">Realm:</td>
				<td>{if $allgAr.otherrealm}<input name="realm" type="text" value="{'realm'|cfg}" class="required"/>{else}<input name="realm" type="hidden" value="{'realm'|cfg}"/>{'realm'|cfg}{/if}
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Geburtsdatum:</td>
				<td>
					<input id="applyDatepicker" maxDate="{'maxDate'|cfg}" class="required" name="gebdatum" type="text" value="{$mingebdate|date_format:"%Y-%m-%d"}" />
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Charaktername & Level:</td>
				<td>
					<input id="battleNet"  class="required" minlength="3" name="name" type="text" />
					<input id="intWheel" name="level" type="text" value="{'maxlevel'|cfg}" size="3" maxlength="3" maxvalue="{'maxlevel'|cfg}" style="text-align: center;" tooltip="LEVEL (Scroll mit der Maus um den Wert zu ver&auml;ndern!" class="required"/>
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Passwort:</td>
				<td>
					<input id="password1" type="password" name="pass" size="20"  maxlength="40" class="required">
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Passwort wiederholen:</td>
				<td>
					<input id="password2" type="password" name="password2" equalto="#password1" class="required" >
				</td>
			</tr>
			<tr class="Cmite">
				<td align="right">eMail:</td>
				<td>
					<input name="email" type="text" class="required email" />
				</td>
			</tr>

			<tr class="Cnorm">
				<td align="right" width="150">Rassen:</td>
				<td>
					<select name="rassen" class="required">
						<option></option>
						{foreach key=key item=val from=$rassen}<option value="{$key}">{$val}</option>{/foreach}
					</select>
				</td>
			</tr>
			<tr class="Cmite">
				<td align="right">Klasse:</td>
				<td>
					<select name="klassen" class="required">
						<option></option>
						{foreach key=key item=val from=$klassen}<option value="{$key}">{$val}</option>{/foreach}
					</select>
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Spezialiesierung:</td>
				<td id="Spezialiesierung">Bitte w&auml;hlen Sie zu erst eine Klasse aus!</td>
			</tr>
		
			<tr class="Cmite">
				<td align="right"  width="150">Teamspeak:</td>
				<td>
					<label for="ts1"><input type="radio" id="ts1" name="teamspeak" value="1" validate="required:true">Ja</label>
					<label for="ts2"><input type="radio" id="ts2" name="teamspeak" value="0" checked="checked">Nein</label>
				</td>
			</tr>
			
			<tr class="Cmite">
				<td align="right" valign="top"  width="150">Schreib was &uuml;ber dich & warum du zu uns m&ouml;chtest</td>
				<td><textarea name="warum" rows="6" cols="40"></textarea></td>
			</tr>
			
			{if {'charakterzeiten'|cfg} == '2' || ($smarty.session.charzeiten == 0 and {'charakterzeiten'|cfg} == 1 ) }
				
				<tr class="Cdark">
					<th colspan="2" align="center">zu welchen Raid Zeiten k&ouml;nnen Sie teilnehmen?</th>
				</tr>
				
				<tr class="Cmite">
					<td align="right">{'info'|icon}</td>
					<td>Die Raidzeiten sind Pflicht angaben, wenn Sie keine Zeit ausw&auml;hlen, k&ouml;nnen Sie sich zu keinem Raid/Event Anmelden!</td>
				</tr>
			
		
				{foreach $time as $i}
					<tr class="Cnorm">
						<td align="right"><input type="checkbox" name="time[]" value="{$i.id}"></td>
						<td>{$i.info}: von {$i.start} bis {$i.ende}</td>
					</tr>
				{/foreach}
			{/if}
		
			<tr class="Cdark">
				<th align="right">
					<input type="hidden" name="avatar" value="" />
					<input type="hidden" name="img" value="" />
					<input type="hidden" name="points" value="" />
				</th>
				<th><input type="Submit" value="Bewerben"></th>
			</tr>
		</table>

	</form>
{else}
		Sorry, Sie k&ouml;nnen sich nicht Bewerben, da Sie ein activen Account haben.<br />
		Wenn Sie die n&ouml;tigen rechte haben k&ouml;nnen sie sich einen Charakter <a href="index.php?char">hier erstellen</a>.
{/if}