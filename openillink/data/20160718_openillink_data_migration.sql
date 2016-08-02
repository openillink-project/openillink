-------------------------------------------------------------------------------
-- orders
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.orders (`date`, `adresse`, `annee`, `arrivee`, `auteurs`, `bibliotheque`, `cgra`, `cgrb`, `code_postal`, `doi`, `edition`, `envoi_par`, `envoye`, `facture`, `historique`, `illinkid`, `ip`, `isbn`, `localisation`, `localite`, `mail`, `nom`, `numero`, `pages`, `pid`, `PMID`, `prenom`, `prepaye`, `prix`, `ref`, `referer`, `refinterbib`, `remarques`, `renouveler`, `saisie_par`, `service`, `sid`, `stade`, `supplement`, `tel`, `titre_article`, `titre_periodique`, `type_doc`, `uid`, `urgent`, `volume`) SELECT `date`, `adresse`, `annee`, `arrivee`, `auteurs`, `bibliotheque`, `cgra`, `cgrb`, `code_postal`, `doi`, `edition`, `envoi_par`, `envoye`, `facture`, `historique`, `illinkid`, `ip`, `isbn`, `localisation`, `localite`, `mail`, `nom`, `numero`, `pages`, `pid`, `PMID`, `prenom`, `prepaye`, `prix`, `ref`, `referer`, `refinterbib`, `remarques`, `renouveler`, `saisie_par`, `service`, `sid`, `stade`, `supplement`, `tel`, `titre_article`, `titre_periodique`, `type_doc`, `uid`, `urgent`, `volume` FROM openillink_base.commandes;

-------------------------------------------------------------------------------
-- libraries
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.libraries (`code`,`default`,`id`,`name1`,`name2`,`name3`,`name4`,`name5`)
SELECT `code`,`default`,`id`,`name1`,`name2`,`name3`,`name4`,`name5` FROM openillink_v2.libraries;

-------------------------------------------------------------------------------
-- links
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.links (`active`,`id`,`library`,`openurl`,`order_ext`,`order_form`,`ordonnancement`,`search_atitle`, `search_btitle`, `search_isbn`, `search_issn`, `search_ptitle`, `title`, `url`, `url_encoded`)
SELECT `active`,`id`,`library`,`openurl`,`order_ext`,`order_form`,`ordonnancement`,`search_atitle`, `search_btitle`, `search_isbn`, `search_issn`, `search_ptitle`, `title`, `url`, `url_encoded` FROM openillink_v2.links;

-------------------------------------------------------------------------------
-- localizations
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.localizations (`code`,`id`,`library`,`name1`,`name2`,`name3`,`name4`,`name5`)
SELECT `code`,`id`,`library`,`name1`,`name2`,`name3`,`name4`,`name5` FROM openillink_v2.localizations;

-------------------------------------------------------------------------------
-- status
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.status (`code`,`color`,`help1`,`help2`,`help3`,`help4`,`help5`, `id`, `in`, `out`, `special`, `title1`,`title2`,`title3`,`title4`,`title5`, `trash`)
SELECT `code`,`color`,`help1`,`help2`,`help3`,`help4`,`help5`, `id`, `in`, `out`, `special`, `title1`,`title2`,`title3`,`title4`,`title5`, `trash` FROM openillink_v2.status;

-------------------------------------------------------------------------------
-- units
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.units (`code`,`department`,`externalipdisplay`,`faculty`,`id`,`internalip1display`,`internalip2display`, `library`,`name1`,`name2`,`name3`,`name4`,`name5`, `validation`)
SELECT `code`,`department`,`externalipdisplay`,`faculty`,`id`,`internalip1display`,`internalip2display`, `library`,`name1`,`name2`,`name3`,`name4`,`name5`, `validation` FROM openillink_v2.units;

-------------------------------------------------------------------------------
-- users
-------------------------------------------------------------------------------
INSERT INTO openillink_v2016.users (`admin`,`created_ip`,`created_on`,`email`,`library`,`login`,`name`, `password`,`status`,`user_id`)
SELECT `admin`,`created_ip`,`created_on`,`email`,`library`,`login`,`name`, `password`,`status`,`user_id` FROM openillink_v2.users;


