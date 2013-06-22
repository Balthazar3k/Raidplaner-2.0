<form method="post" action="admin.php?chars-edit-{$charakter.id}">
    <table width="73%" border="0" cellpadding="2" cellspacing="1" class="border left">
      <tr class="Chead">
        <th colspan="2">Bearbeiten von {$charakter.name}</th>
      </tr>
	  <tr class="Cnorm">
        <td>User:</td>
        <td>
			{html_options name=user options=$user selected=$charakter.uid}
		</td>
      </tr>
	  <tr class="Cnorm">
        <td>Realm:</td>
        <td><input name="realm" type="text" id="realm" value="{$charakter.realm}" /></td>
      </tr>
      <tr class="Cnorm">
        <td>Name & Level:</td>
        <td>
			<input name="id" type="hidden" value="{$charakter.id}" />
			<input name="name" type="text" id="name" value="{$charakter.name}" />
			<input id="intWheel" name="level" type="text" id="name" value="{$charakter.level}" size="2" maxlength="2" maxvalue="90" />
        </td>
      </tr>
      <tr class="Cnorm">
        <td width="244">Rang:</td>
        <td width="246">
			{html_options name=rank options=$rank selected=$charakter.rank}
		</td>
      </tr>
	  <tr class="Cnorm">
        <td>Rasse:</td>
        <td>
			{html_options name=rassen options=$rassen selected=$charakter.rassen}		
		</td>
      </tr>
      <tr class="Cnorm">
        <td>Klasse:</td>
        <td>
			{html_options name=klassen options=$klassen selected=$charakter.klassen}		
		</td>
      </tr>
      <tr class="Cnorm">
        <td>
			Spezialiesierung:
		</td>
        <td id="autoLoad" href="index.php?chars" post="kid={$charakter.klassen}" class="Spezialiesierung">
			{html_options name=s1 options=$spz selected=$charakter.s1}
			{html_options name=s2 options=$spz selected=$charakter.s2}
		</td>
      </tr>
      <tr class="Cnorm">
        <td valign="top">Warum:</td>
        <td><textarea name="warum" id="warum" cols="45" rows="5">{$charakter.warum}</textarea></td>
      </tr>
      <tr class="Cnorm">
        <td>Teamspeak:</td>
        <td>
			<input type="radio" name="teamspeak" value="1"{if $charakter.teamspeak == 1} checked="checked"{/if}/> Ja
			<input type="radio" name="teamspeak" value="0"{if $charakter.teamspeak == 0} checked="checked"{/if}/> Nein
        </td>
      </tr>
      <tr class="Cnorm">
        <td>&nbsp;</td>
        <td>
			<input type="submit" value="Speichern" />
		</td>
      </tr>
    </table>
</form>

<form id="standart" method="post" action="admin.php?chars-modulerights-{$charakter.uid}">
    <table width="25%" border="0" cellpadding="2" cellspacing="1" class="border left" style="margin-bottom: 3px;">
      <tr class="Chead">
        <td colspan="2">Seitenrechte im Adminbereich</td>
      </tr>
	  {foreach $modules as $i}<tr class="{if $i.menu == 'Raidplaner'}Cmite{else}Cnorm{/if}">
        <td align="right"><input type="checkbox" name="mid[{$i.menu}][]" value="{$i.id}" {$i.checked}></td>
        <td>{$i.name}</td>
      </tr>{/foreach}
	  <tr class="Cdark">
        <td colspan="2"><input type="Submit" value="Speichern" /></td>
      </tr>
    </table>
</form>
<br style="clear: both"/>