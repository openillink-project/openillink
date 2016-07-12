-- --------------------------------------------------------

--
-- Contenu de la table `units`
--

INSERT INTO `units` (`id`, `code`, `name1`, `name2`, `name3`, `name4`, `name5`, `department`, `faculty`, `library`, `internalip1display`, `internalip2display`, `externalipdisplay`, `validation`) VALUES
(1, 'DAR', 'Radiologie', 'Radiology', 'Radiology', 'Radiology', 'Radiology', 'Radiology', 'Medicine', 'LIB1', 1, 0, 0, 0),
(2, 'EXT_ENT', 'Entreprise privée', 'Private Firm', 'Private Firm', 'Private Firm', 'Private Firm', '', '', 'LIB1', 0, 0, 1, 1),
(3, 'EXT_MEDECIN', 'Médecin en cabinet privé', 'Physician in private practice', 'Physician in private practice', 'Physician in private practice', 'Physician in private practice', NULL, NULL, 'LIB1', 0, 0, 1, 0),
(4, 'GCP', 'Gastroentrologie', 'Gastroenterology', 'Gastroenterology', 'Gastroenterology', 'Gastroenterology', 'Internal Medicine', 'Medicine', 'LIB1', 1, 0, 0, 0),
(5, 'GLN', 'Neurologie', 'Neurology', 'Neurology', 'Neurology', 'Neurology', 'Neurosciences', 'Medicine', 'LIB3', 1, 0, 0, 0),
(6, 'HCN', 'Neurochirurgie', 'Neurosurgery', 'Neurosurgery', 'Neurosurgery', 'Neurosurgery', 'Neurosciences', 'Medicine', 'LIB1', 1, 0, 0, 0),
(7, 'IUXYZSP', 'Sociologie', 'Sociology', 'Sociology', 'Sociology', 'Sociology', 'Sociology', 'Humanities', 'LIB2', 0, 1, 0, 0),
(8, 'NCP', 'Psychologie', 'Psychology', 'Psychology', 'Psychology', 'Psychology', 'Psychology', 'Humanities', 'LIB2', 0, 1, 0, 0),
(9, 'STUDENT', 'Etudiant', 'Student', 'Student', 'Student', 'Student', NULL, 'Medicine', 'LIB1', 1, 1, 1, 0),
(10, 'THCX', 'Transplantation', 'Transplantation', 'Transplantation', 'Transplantation', 'Transplantation', 'Transplantation', 'Medicine', 'LIB1', 1, 0, 0, 0),
(11, 'URG', 'Urgences', 'Emergency medicine', 'Emergency medicine', 'Emergency medicine', 'Emergency medicine', 'Emergency medicine', 'Medicine', 'LIB4', 1, 0, 0, 0);

