<form id="standart" method="post" action="index.php?chars-addChar" page="reload">
	<div id="step1">
		<table width="600" cellspacing="1" cellpadding="5" border="0" class="border">
			<tr class="Chead" >
				<th colspan="2">Schritt 1: Realm, Name & Level</th>
			</tr>
			<tr class="Cmite">
				<td align="right" width="150">Realm:</td>
				<td>{if $allgAr.otherrealm}<input name="realm" type="text" value="{'realm'|cfg}"/>{else}<input name="realm" type="hidden" value="{'realm'|cfg}"/>{'realm'|cfg}{/if}
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Name & Level:</td>
				<td>
					<input id="battleNet" name="name" type="text" />
					<input id="intWheel" name="level" type="text" value="{'maxlevel'|cfg}" size="3" maxlength="3" maxvalue="{'maxlevel'|cfg}" style="text-align: center;" tooltip="LEVEL (Scroll mit der Maus um den Wert zu ver&auml;ndern!"/>
				</td>
			</tr>
			<tr class="Cdark">
				<th></th>
				<th><input class="sub" type="button" value="Weiter zu Schritt 2" goto="#step1, #step2"></th>
			</tr>
		</table>
	</div>
	
	<div id="step2" class="hide">
		<table width="600" cellspacing="1" cellpadding="5" border="0" class="border">
			<tr class="Chead">
				<th colspan="2">Schritt 2: Rassen, Klassen & Spezialiesierung</th>
			</tr>
			<tr class="Cmite">
				<td align="right" width="150">Rassen:</td>
				<td>
					<select name="rassen">
						<option></option>
						{foreach $rassen as $i}
							<option value="{$i.id}">{$i.rassen}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Klasse:</td>
				<td>
					<select name="klassen">
						<option></option>
						{foreach $klassen as $i}
							<option value="{$i.id}">{$i.klassen}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr class="Cmite">
				<td align="right">Spezialiesierung:</td>
				<td id="Spezialiesierung">Bitte w&auml;hlen Sie zu erst eine Klasse aus!</td>
			</tr>
			<tr class="Cdark">
				<th align="right"><input class="sub" type="button" value="Zur&uuml;ck zu Schritt 2" goto="#step2, #step1"></th>
				<th><input class="sub" type="button" value="Weiter zu Schritt 3" goto="#step2, #step3"></th>
			</tr>
		</table>
	</div>
	
	<div id="step3" class="hide">
		<table width="600" cellspacing="1" cellpadding="5" border="0" class="border">
			<tr class="Chead">
				<th colspan="2">Schritt 3: Teamspeak & Raid Zeiten</th>
			</tr>
		
			<tr class="Cnorm">
				<td align="right"  width="150">Teamspeak:</td>
				<td>
					<input type="radio" id="ts1" name="teamspeak" value="1"><label for="ts1">Ja</label>
					<input type="radio" id="ts2" name="teamspeak" value="0"><label for="ts2">Nein</label>
				</td>
			</tr>
			
			{if {'charakterzeiten'|cfg} == '2' || ($smarty.session.charzeiten == 0 and {'charakterzeiten'|cfg} == 1 ) }
				
				<tr class="Cdark">
					<th colspan="2" align="center">zu welchen Raid Zeiten k&ouml;nnen Sie teilnehmen? {'charakterzeiten'|cfg}  </th>
				</tr>
				
				<tr class="Cmite">
					<td align="right">{'info'|icon}</td>
					<td>Die Raidzeiten sind Pflicht angaben, wenn Sie keine Zeit ausw&auml;hlen, k&ouml;nnen Sie sich zu keinem Raid/Event Anmelden!</td>
				</tr>
			
		
				{foreach $time as $i}
					<tr class="Cnorm">
						<td align="right"><input type="checkbox" name="time[]" value="{$i.id}"></td>
						<td>{$i.info}: von {$i.start} bis {$i.begin}</td>
					</tr>
				{/foreach}
			{/if}
		
			<tr class="Cdark">
				<th align="right">
					<input type="hidden" name="avatar" value="" />
					<input type="hidden" name="img" value="" />
					<input type="hidden" name="points" value="" />
					<input class="sub" type="button" value="Zur&uuml;ck zu Schritt 2" goto="#step3, #step2">
				</th>
				<th><input type="Submit" value="Charakter Erstellen"></th>
			</tr>
		</table>
	</div>
</form>