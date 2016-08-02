/*
Goal: migrate all orders being associated with obsolete service codes:
- [Zü100] UNI Zürich - Medizin Careum , previously :[Anna Schlosser] Medizinbibliothek Zürich
- [PMU] PMU, includes now inclut maintenant les anciens services: 
  [PMU_a] PMU - Autres services
  [1PH] PMU - Pharmacie
  [1MD] PMU - SMD
  [1UR]  PMU - Urgences
- [ANI] Animalerie, précédemment : [Animalerie] Animalerie
- [AUM] Aumôneries, précédemment : [Aumonerie] Aumôneries
- [CNP_PCN] Centre de Neurosciences Psychiatriques 
tHub , précédemment : [PCN] Centre de Neurosciences Psychiatriques
- [DFR] Département formation et Recherche, précédemment : [DEC] Direction département Formation et recherche (DFR)
- [BIO] Biomédecine inclut maintenant les anciens services: [PCL] Biomédecine et [BIO]Biomédecine
- [Dir_med] Direction département de Médecine inclut maintenant les anciens services: [Dir_med] Direction département de Médecine et [DMI] Direction département de Médecine
- [IST] Institut universitaire romand de santé au travail (IST) inclut maintenant les anciens services: [IST] Institut de santé au travail et [IURST] Institut universitaire romand de santé au travail (IST)



*/

/*select illinkid from orders where service = 'Anna Schlosse'; Zü100*/

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service IN ('PMU_a','1PH','1MD','1UR')) AS orderSource
SET oDest.service = 'PMU', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service = 'Animalerie') AS orderSource
SET oDest.service = 'ANI', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service = 'Aumonerie') AS orderSource
SET oDest.service = 'AUM', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service IN ('PCN', 'CNP_PCN')) AS orderSource
SET oDest.service = 'CNP-PCN', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service = 'DEC') AS orderSource
SET oDest.service = 'DFR', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service = 'PCL') AS orderSource
SET oDest.service = 'BIO', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;

UPDATE orders AS oDest,(SELECT illinkid, service FROM orders WHERE service = 'IURST') AS orderSource
SET oDest.service = 'IST', oDest.remarques = CONCAT(oDest.remarques, '\r\nMise à jour automatique du code service, valeur précédente ', orderSource.service ,', le ', NOW())
WHERE oDest.illinkid = orderSource.illinkid;
