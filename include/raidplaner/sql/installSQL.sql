DROP TABLE 
`prefix_raid_berufe`, 
`prefix_raid_bosscounter`, 
`prefix_raid_bosse`, 
`prefix_raid_dkp`, 
`prefix_raid_dkps`, 
`prefix_raid_grpsize`, 
`prefix_raid_info`, 
`prefix_raid_kalender`, 
`prefix_raid_level`, 
`prefix_raid_loot`, 
`prefix_raid_rang`, 
`prefix_raid_stammgrp`, 
`prefix_raid_stammrechte`, 
`prefix_raid_zeitgruppen`, 
`prefix_raid_zeitgruppen_chars`
`prefix_raid_klassen`;


ALTER TABLE `prefix_raid_charaktere` DROP `mberuf`, DROP `mskill`, DROP `rang`;
ALTER TABLE `prefix_raid_charaktere` CHANGE `sberuf` `avatar` VARCHAR( 255 ) NOT NULL ,CHANGE `sskill` `img` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `prefix_raid_charaktere` DROP `stammgrp`;
ALTER TABLE `prefix_raid_charaktere` DROP `s3`;
ALTER TABLE `prefix_raid_charaktere` CHANGE `punkte` `points` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_raid_charaktere` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `prefix_raid_charaktere` CHANGE `s1` `s1` VARCHAR( 255 ) NOT NULL DEFAULT '0', CHANGE `s2` `s2` VARCHAR( 255 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_raid_gruppen` ADD `pos` INT( 3 ) NOT NULL;
ALTER TABLE `prefix_raid_klassen` CHANGE `klassen` `klassen` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `s1b` `s1b` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `s2b` `s2b` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `s3b` `s3b` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `color` `color` VARCHAR(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '#555555';
ALTER TABLE `prefix_raid_statusmsg` ADD `type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,ADD `pos` INT NOT NULL;
ALTER TABLE `prefix_raid_statusmsg` CHANGE `statusmsg` `statusmsg` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `color` `color` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `prefix_raid_raid` CHANGE `bosskey` `multi` INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_raid_raid` CHANGE `erstellt` `erstellt` INT NOT NULL;
ALTER TABLE `prefix_raid_raid` CHANGE `loot` `time` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_raid_raid` ADD `von` INT( 11 ) NOT NULL, ADD `bis` INT( 11 ) NOT NULL;
ALTER TABLE `prefix_raid_raid` CHANGE `von` `cid` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `prefix_raid_charaktere` CHANGE `name` `name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'Name', 
CHANGE `s1` `s1` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
CHANGE `s2` `s2` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '0', 
CHANGE `rlname` `rlname` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '', 
CHANGE `avatar` `avatar` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, 
CHANGE `img` `img` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, 
CHANGE `warum` `warum` TEXT CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, 
CHANGE `pvp` `pvp` TEXT CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, 
CHANGE `raiden` `raiden` TEXT CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, 
CHANGE `realm` `realm` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'Realm';

ALTER TABLE `prefix_raid_charaktere`
  DROP `alter`,
  DROP `rlname`,
  DROP `pvp`,
  DROP `raiden`,
  DROP `gruppen`;


CREATE TABLE IF NOT EXISTS `prefix_raid_klassen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klassen` varchar(255) NOT NULL DEFAULT '',
  `s1b` varchar(50) NOT NULL DEFAULT '',
  `s2b` varchar(50) NOT NULL DEFAULT '',
  `s3b` varchar(50) NOT NULL DEFAULT '',
  `rs1b` int(2) NOT NULL DEFAULT '0',
  `rs2b` int(2) NOT NULL DEFAULT '0',
  `rs3b` int(2) NOT NULL DEFAULT '0',
  `color` varchar(7) NOT NULL DEFAULT '#666',
  `aufnahmestop` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT AUTO_INCREMENT=0;

INSERT INTO `prefix_credits` (`id`, `sys`, `name`, `version`, `url`, `lizenzname`, `lizenzurl`) VALUES (NULL, 'ilch', 'Raidplaner Modul', '2.0', 'http://balthazar3k.github.com/Raidplaner/', 'GPL', 'http://www.gnu.de/gpl-ger.html');

INSERT INTO `prefix_raid_klassen` (`id`, `klassen`, `s1b`, `s2b`, `s3b`, `rs1b`, `rs2b`, `rs3b`, `color`, `aufnahmestop`) VALUES
(1, 'Krieger', 'Waffen', 'Furor', 'Schutz', 0, 0, 0, '#C69B6D', 1),
(2, 'Paladin', 'Heilig', 'Schutz', 'Vergeltung', 0, 0, 0, '#f48cba', 1),
(3, 'J&auml;ger', 'Tierherrschaft', 'Treffsicherheit', '&Uuml;berleben', 0, 0, 0, '#AAD372', 0),
(4, 'Schurke', 'M&auml;ucheln', 'Kampf', 'T&auml;uschung', 0, 0, 0, '#fff468', 0),
(5, 'Priester', 'Disziplin', 'Heilig', 'Schatten', 0, 0, 0, '#ffffff', 0),
(6, 'Todesritter', 'Blut', 'Frost', 'Unheilig', 0, 0, 0, '#C41E3B', 0),
(7, 'Schamane', 'Elementar', 'Verst&auml;rkung', 'Wiederherstellung', 0, 0, 0, '#2359FF', 1),
(8, 'Magier', 'Arcan', 'Feuer', 'Eis', 0, 0, 0, '#68CCEF', 1),
(9, 'Hexenmeister', 'Gebrechen', 'D&auml;monologie', 'Zerst&ouml;rung', 0, 3, 0, '#9382C9', 0),
(10, 'M&ouml;nch', 'Braumeister', 'Nebelwirker', 'Windl&auml;ufer', 0, 0, 0, '#00FFBA', 0),
(11, 'Druide', 'Gleichgewicht', 'Wilder Kampf', 'Wiederherstellung', 0, 0, 0, '#ff7c0a', 1),
(12, 'Testchar', 'Franzose', 'Italiener', 'Pole', 0, 0, 0, '#666', 0);

UPDATE prefix_raid_charaktere SET klassen=1 WHERE klassen=3;
UPDATE prefix_raid_charaktere SET klassen=2 WHERE klassen=6;
UPDATE prefix_raid_charaktere SET klassen=3 WHERE klassen=8;
UPDATE prefix_raid_charaktere SET klassen=4 WHERE klassen=2;
UPDATE prefix_raid_charaktere SET klassen=6 WHERE klassen=11;
UPDATE prefix_raid_charaktere SET klassen=8 WHERE klassen=4;
UPDATE prefix_raid_charaktere SET klassen=9 WHERE klassen=9;
UPDATE prefix_raid_charaktere SET klassen=11 WHERE klassen=10;


DELETE FROM `prefix_modules` WHERE `prefix_config`.`kat` LIKE 'R:%';
DELETE FROM `prefix_config` WHERE `prefix_config`.`kat` = 'Raidplaner';

INSERT INTO `prefix_config` (`schl`, `typ`, `typextra`, `kat`, `frage`, `wert`, `pos`, `hide`, `helptext`) VALUES
('addchar', 'grecht', NULL, 'Raidplaner', 'Ab welchem Rang darf ein User Charaktere anlegen?', '-4', 0, 0, NULL),
('maxchars', 'input', NULL, 'Raidplaner', 'Wieviel Chars darf ein User besitzen?', '20', 1, 0, NULL),
('maxlevel', 'input', '', 'Raidplaner', 'Maximal Level:', '90', 2, 0, NULL),
('charakterzeiten', 'select', '{"keys":[0, 1, 2], "values":["kein Charakter (Disabled)", "main Charaktere", "alle Charaktere"]}', 'Raidplaner', 'Raidzeiten f&uuml;r', '1', 3, 0, NULL),
('faction', 'select', '{"keys":[0, 1, 2], "values":["Horde", "Allianz", "Horde & Allianz"]}', 'Raidplaner', 'Fraktion', '0', 4, 0, NULL),
('otherrealm', 'r2', NULL, 'Raidplaner', 'anderen Realm zulassen?', '0', 5, 0, NULL),
('realm', 'input', NULL, 'Raidplaner', 'Standart Realm (WoW Server):', 'Khaz''goroth', 6, 0, NULL),
('charakterurl', 'input', NULL, 'Raidplaner', 'Battle.net Charakter API URL', 'http://eu.battle.net/api/wow/character/', 7, 0, NULL),
('arsenalurl', 'input', NULL, 'Raidplaner', 'Charakter URL', 'http://eu.battle.net/wow/de/character/', 8, 0, NULL),
('avatarurl', 'input', NULL, 'Raidplaner', 'Battle.net Avatar URL', 'http://eu.battle.net/static-render/eu/', 9, 0, NULL),
('maxDate', 'input', NULL, 'Raidplaner', 'min Alter zum Bewerben', '18', '0', '0', NULL),
('bewerbung', 'textarea', NULL, 'Raidplaner', 'Bewerbung''s Msg', 'Um dich bei uns Bewerben zu k&ouml;nnen musst du dich [url={domain}index.php?user-regist]Regestrieren[/url]!\r\n\r\nWenn du Jedoch schon Rang Member hast kannst du dir einen Charakter auf der Charakter Seite erstellen. [url={domain}index.php?chars-newchar]Charakter Erstellen[/url]', 10, 0, NULL),
('arrPrint', 'r2', NULL, 'Raidplaner', 'arrPrint anzeigen? (ist nur sichtbar f&uuml;r admins)', '1', 11, 0, NULL);