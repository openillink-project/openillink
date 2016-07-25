/*****************************************************************************/
/* Add column to link table to manage words trimm and subchain option        */
/*****************************************************************************/

/* Skip given listo of words of a journal/book title                         */
ALTER TABLE `links` ADD `skip_words` TINYINT(1) NOT NULL ;
/* Skip words of journal/book title after a set of predefined marks         */
ALTER TABLE `links` ADD `skip_txt_after_mark` TINYINT(1) NOT NULL ;