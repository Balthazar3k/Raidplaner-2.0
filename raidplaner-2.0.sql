INSERT INTO `prefix_config` (`schl`, `typ`, `typextra`, `kat`, `frage`, `wert`, `pos`, `hide`, `helptext`) VALUES
('arrPrint', 'r2', NULL, 'Raidplaner', 'arrPrint anzeigen? (ist nur sichtbar für admins)', '1', 15, 0, NULL),
('maxDate', 'input', NULL, 'Raidplaner', 'min Alter zum Bewerben', '18', 6, 0, NULL),
('bewerbung', 'textarea', NULL, 'Raidplaner', 'Bewerbung''s Msg', 'Hier können Sie sich Bewerben!', 14, 0, NULL),
('BattleNetAccesses', 'input', '2013-06-26', 'Raidplaner', 'Max Zugriffe pro Tag auf die Battle.net API', '1996', 9, 0, NULL),
('avatarurl', 'input', NULL, 'Raidplaner', 'Battle.net Avatar URL', 'http://eu.battle.net/static-render/eu/', 13, 0, NULL),
('arsenalurl', 'input', NULL, 'Raidplaner', 'Charakter URL', 'http://eu.battle.net/wow/de/character/', 12, 0, NULL),
('addchar', 'grecht', NULL, 'Raidplaner', 'Ab welchem Rang darf ein User Charaktere anlegen?', '-3', 3, 0, NULL),
('maxchars', 'input', NULL, 'Raidplaner', 'Wieviel Chars darf ein User besitzen?', '20', 8, 0, NULL),
('maxlevel', 'input', '', 'Raidplaner', 'Maximal Level:', '90', 7, 0, NULL),
('charakterzeiten', 'select', '{"keys":[0, 1, 2], "values":["kein Charakter (Disabled)", "main Charaktere", "alle Charaktere"]}', 'Raidplaner', 'Raidzeiten für', '1', 5, 0, NULL),
('faction', 'select', '{"keys":[0, 1, 2], "values":["Horde", "Allianz", "Horde & Allianz"]}', 'Raidplaner', 'Fraktion', '0', 1, 0, NULL),
('otherrealm', 'r2', NULL, 'Raidplaner', 'anderen Realm zulassen?', '1', 0, 0, NULL),
('realm', 'input', NULL, 'Raidplaner', 'Standart Realm (WoW Server):', 'Khaz''goroth', 2, 0, NULL),
('charakterurl', 'input', NULL, 'Raidplaner', 'Battle.net Charakter API URL', 'http://eu.battle.net/api/wow/character/', 11, 0, NULL),
('bewerberrang', 'select', '{"keys":["0","1","2","3","4","5","6","7"],"values":["Gildemeister","Offizier","Raidleader","Mitglied","Probe Mitglied","Ehren Mitglied","Freund","Bewerber"]}', 'Raidplaner', 'Welchen Rang soll ein Bewerber bekommen?', '7', 4, 0, 'Sie k&ouml;nnen unter User->Grundrechte einen Rang extra Definieren! Zum Beispiel ein Rang "Bewerber"'),
('guildurl', 'input', NULL, 'Raidplaner', 'Battle.net Gilden API URL', 'http://eu.battle.net/api/wow/guild/', 10, 0, NULL);

INSERT INTO `prefix_credits` (`sys`, `name`, `version`, `url`, `lizenzname`, `lizenzurl`) VALUES
('ilch', 'Raidplaner Modul', '2.0', 'http://balthazar3k.github.com/Raidplaner-2.0/', 'GPL', 'http://www.gnu.de/gpl-ger.html');

INSERT INTO `prefix_loader` (`task`, `file`, `description`) VALUES
('func', 'raidplaner.php', 'Raidplaner Funktionen');

INSERT INTO `prefix_modules` (`url`, `name`, `gshow`, `ashow`, `fright`, `menu`, `pos`) VALUES
('raidinzen', 'Instanzen', 1, 1, 1, 'Raidplaner', 12),
('chars', 'Charaktere', 1, 1, 1, 'Raidplaner', 4),
('raidgruppen', 'Gruppen', 1, 1, 1, 'Raidplaner', 8),
('raidrecht', 'Rechte', 0, 1, 1, 'Raidplaner', 15),
('raid', 'Raidplaner', 1, 1, 1, 'Raidplaner', 0),
('raidrang', 'Ränge', 0, 1, 1, 'Raidplaner', 14),
('allg#tabs-raidplaner', 'Einstellungen', 0, 1, 1, 'Raidplaner', 16),
('raidzeiten', 'Zeiten', 1, 1, 1, 'Raidplaner', 13),
('raidconfig', 'Weitere Einstellungen', 0, 1, 1, 'Raidplaner', 17);



CREATE TABLE IF NOT EXISTS `prefix_raid_anmeldung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `grp` int(11) NOT NULL DEFAULT '0',
  `char` int(11) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL DEFAULT '0',
  `kom` varchar(255) NOT NULL DEFAULT '',
  `stat` int(11) NOT NULL DEFAULT '1',
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `prefix_raid_charaktere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT 'Name',
  `rank` int(11) NOT NULL,
  `klassen` int(11) NOT NULL DEFAULT '1',
  `rassen` int(11) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '1',
  `skillgruppe` int(1) NOT NULL DEFAULT '0',
  `s1` varchar(255) NOT NULL,
  `s2` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `warum` text NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `realm` varchar(255) NOT NULL DEFAULT 'Realm',
  `teamspeak` int(11) NOT NULL DEFAULT '0',
  `regist` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `zeit` smallint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_raid_charzeiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `zid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_raid_gruppen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leader` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `recht` int(3) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT '0',
  `beschreibung` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  `pos` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_raid_inzen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '0',
  `grpsize` int(11) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT '',
  `info` int(11) NOT NULL DEFAULT '0',
  `maxbosse` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;


CREATE TABLE IF NOT EXISTS `prefix_raid_klassen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klassen` varchar(255) NOT NULL DEFAULT '',
  `s1b` varchar(50) NOT NULL DEFAULT '',
  `s2b` varchar(50) NOT NULL DEFAULT '',
  `s3b` varchar(50) NOT NULL DEFAULT '',
  `rs1b` int(2) NOT NULL DEFAULT '0',
  `rs2b` int(2) NOT NULL DEFAULT '0',
  `rs3b` int(2) NOT NULL DEFAULT '0',
  `color` varchar(7) NOT NULL DEFAULT '#555555',
  `aufnahmestop` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `prefix_raid_klassen` (`id`, `klassen`, `s1b`, `s2b`, `s3b`, `rs1b`, `rs2b`, `rs3b`, `color`, `aufnahmestop`) VALUES
(9, 'Hexenmeister', 'Gebrechen', 'Dämonologie', 'Zerstörung', 0, 0, 0, '#9382C9', 0),
(8, 'Magier', 'Arcan', 'Feuer', 'Eis', 2, 0, 0, '#68CCEF', 1),
(7, 'Schamane', 'Elementar', 'Verstärkung', 'Wiederherstellung', 0, 0, 0, '#2359FF', 1),
(6, 'Todesritter', 'Blut', 'Frost', 'Unheilig', 0, 0, 0, '#C41E3B', 0),
(5, 'Priester', 'Disziplin', 'Heilig', 'Schatten', 0, 0, 0, '#ffffff', 0),
(4, 'Schurke', 'Mäucheln', 'Kampf', 'Täuschung', 0, 0, 0, '#fff468', 0),
(3, 'Jäger', 'Tierherrschaft', 'Treffsicherheit', 'Überleben', 1, 0, 0, '#AAD372', 0),
(2, 'Paladin', 'Heilig', 'Schutz', 'Vergeltung', 1, 0, 0, '#f48cba', 1),
(1, 'Krieger', 'Waffen', 'Furor', 'Schutz', 4, 0, 1, '#C69B6D', 1),
(10, 'Mönch', 'Braumeister', 'Nebelwirker', 'Windläufer', 0, 0, 0, '#00FFBA', 0),
(11, 'Druide', 'Gleichgewicht', 'Wilder Kampf', 'Wiederherstellung', 0, 0, 0, '#ff7c0a', 1);


CREATE TABLE IF NOT EXISTS `prefix_raid_raid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statusmsg` int(11) NOT NULL DEFAULT '1',
  `leader` int(11) NOT NULL DEFAULT '1',
  `gruppen` int(11) NOT NULL DEFAULT '1',
  `inzen` int(11) NOT NULL DEFAULT '1',
  `time` int(11) NOT NULL DEFAULT '0',
  `inv` int(11) NOT NULL DEFAULT '0',
  `pull` int(11) NOT NULL DEFAULT '0',
  `ende` int(11) NOT NULL DEFAULT '0',
  `invsperre` smallint(2) NOT NULL DEFAULT '0',
  `txt` text NOT NULL,
  `erstellt` int(11) NOT NULL,
  `cid` int(4) DEFAULT NULL,
  `multi` int(1) NOT NULL DEFAULT '0',
  `von` varchar(10) NOT NULL,
  `bis` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_raid_rang` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `rechte` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `prefix_raid_rang` (`id`, `name`, `rechte`) VALUES
(3, 'Mitglied', ''),
(0, 'Gildemeister', '{"Raidplaner":["801","803","805","807","811","808","809","843"],"Permissions":["1","2","3","4","5","6","7","8","9"]}'),
(2, 'Raidleader', '{"Raidplaner":["801","807","811"],"Permissions":["1","2"]}'),
(1, 'Offizier', '{"Raidplaner":["801","803","807","811"],"Permissions":["1","2","3","4","5","6"]}'),
(4, 'Probe Mitglied', ''),
(5, 'Ehren Mitglied', ''),
(6, 'Freund', ''),
(7, 'Bewerber', '');


CREATE TABLE IF NOT EXISTS `prefix_raid_rassen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rassen` varchar(255) NOT NULL DEFAULT '',
  `faction` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `prefix_raid_rassen` (`id`, `rassen`, `faction`) VALUES
(1, 'Mensch', 1),
(2, 'Ork', 0),
(3, 'Zwerg', 1),
(4, 'Nachtelf', 1),
(5, 'Untoter', 0),
(6, 'Tauren', 0),
(7, 'Gnom', 1),
(8, 'Troll', 0),
(9, 'Goblin', 0),
(10, 'Blutelf', 0),
(11, 'Draenei', 1),
(25, 'Pandaren (A)', 1),
(26, 'Pandaren (H)', 0),
(22, 'Worgen', 1);


CREATE TABLE IF NOT EXISTS `prefix_raid_rechte` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `gshow` tinyint(1) NOT NULL DEFAULT '0',
  `ashow` tinyint(1) NOT NULL DEFAULT '0',
  `fright` tinyint(1) NOT NULL DEFAULT '0',
  `menu` varchar(200) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


INSERT INTO `prefix_raid_rechte` (`id`, `url`, `name`, `gshow`, `ashow`, `fright`, `menu`, `pos`) VALUES
(1, 'createEvent', 'Event Erstellen', 0, 0, 1, 'Permissions', 1),
(3, 'removeEvent', 'Event Löschen', 0, 0, 1, 'Permissions', 3),
(2, 'editEvent', 'Event Bearbeiten', 0, 0, 1, 'Permissions', 2),
(4, 'editCharakter', 'Charakter Bearbeiten', 0, 0, 1, 'Permissions', 5),
(5, 'removeCharakter', 'Charakter Löschen', 0, 0, 1, 'Permissions', 6),
(6, 'updateCharakter', 'Charakter Update', 0, 0, 1, 'Permissions', 7),
(7, 'createGruppe', 'Gruppe Erstellen', 0, 0, 1, 'Permissions', 9),
(8, 'editGruppe', 'Gruppe Bearbeiten', 0, 0, 1, 'Permissions', 10),
(9, 'removeGruppe', 'Gruppe Löschen', 0, 0, 1, 'Permissions', 11);


CREATE TABLE IF NOT EXISTS `prefix_raid_statusmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `statusmsg` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;


INSERT INTO `prefix_raid_statusmsg` (`id`, `sid`, `statusmsg`, `color`, `type`, `pos`) VALUES
(1, 1, 'Aktiv', 'green', '', 0),
(2, 1, 'Beendet', 'blue', '', 0),
(3, 1, 'Abgesagt', 'red', '', 0),
(4, 1, 'Abgebrochen', 'orange', '', 0),
(5, 2, 'User: Dabei', 'orange', '', 0),
(6, 2, 'User: Ersatz', 'blue', '', 0),
(8, 2, 'User: Absagen', 'red', '', 0),
(13, 3, 'Raid: Ersatz', 'blue', '', 0),
(12, 3, 'Raid: Zusage', 'green', '', 0),
(14, 3, 'Raid: Absage', 'red', '', 0),
(15, 3, 'Raid: Strafe', 'darkred', '', 0),
(17, 1, 'Ausstehend', 'red', '', 0),
(16, 3, 'Bearbeitung', 'darkorange', '', 0);


CREATE TABLE IF NOT EXISTS `prefix_raid_userrechte` (
  `uid` mediumint(9) NOT NULL DEFAULT '0',
  `mid` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_raid_zeit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` varchar(5) NOT NULL,
  `begin` varchar(5) NOT NULL,
  `ende` varchar(5) NOT NULL,
  `sperre` int(2) NOT NULL DEFAULT '0',
  `info` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `prefix_raid_zeit` (`id`, `start`, `begin`, `ende`, `sperre`, `info`) VALUES
(1, '18:00', '18:15', '22:00', 2, 'Freitag''s Event'),
(2, '18:00', '18:15', '22:30', 2, 'Montag''s Event'),
(3, '17:00', '17:15', '21:30', 5, 'Samstag''s Event'),
(4, '18:00', '18:15', '22:00', 2, 'Normale Raidzeiten');
