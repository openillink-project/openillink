-- --------------------------------------------------------

--
-- Contenu de la table `links`
--

INSERT INTO `links` (`id`, `title`, `url`, `search_issn`, `search_isbn`, `search_ptitle`, `search_btitle`, `search_atitle`, `order_ext`, `order_form`, `openurl`, `library`, `active`, `ordonnancement`, `url_encoded`, `skip_words`, `skip_txt_after_mark`) VALUES
(3, 'Google', 'http://www.google.ch/search?hl=fr&newwindow=1&q=%22XTITLEX%22', 0, 0, 0, 0, 1, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(2, 'PubMed', 'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?otool=mycode&orig_db=PubMed&db=PubMed&cmd=Search&otool=mycode&term=XTITLEX', 0, 0, 0, 0, 1, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(6, 'perUnil', 'https://www2.unil.ch/perunil/biomed/index.php/site/simpleSearchResults?q=XTITLEX&field=twords&support=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(9, 'NLM Locator Plus', 'https://catalog.nlm.nih.gov/discovery/search?query=title,contains,XTITLEX,AND&tab=LibraryCatalog&search_scope=MyInstitution&vid=01NLM_INST:01NLM_INST&mode=advanced&offset=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(10, 'Uni Genève', 'https://slsp-unige.primo.exlibrisgroup.com/discovery/jsearch?query=any,contains,XISSNX&tab=jsearch_slot&vid=41SLSP_UGE:VU1&offset=0&journals=any,XISSNX', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(11, 'EPFL', 'https://slsp-epfl.primo.exlibrisgroup.com/discovery/search?query=title,contains,XTITLEX,AND&pfilter=rtype,exact,journals,AND&tab=41SLSP_EPF_MyInst_and_CI&search_scope=MyInst_and_CI&sortby=rank&vid=41SLSP_EPF:prod&mode=advanced&offset=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(13, 'RP/VZ', 'https://nb-helveticat.primo.exlibrisgroup.com/discovery/jsearch?query=issn,exact,XISSNX&tab=jsearch_slot&vid=41SNL_51_INST:helveticat&offset=0', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(14, 'Helveticat', 'https://www.helveticat.ch/discovery/search?query=issn,exact,XISSNX&tab=LibraryCatalog&search_scope=Helveticat&vid=41SNL_51_INST:helveticat&offset=0', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(15, 'RERO CC', 'https://bib.rero.ch/global/search/documents?q=XISSNX', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(18, 'ZDB', 'https://zdb-katalog.de/list.xhtml?t=iss%3D%22XISSNX%22&key=cql&asc=false&sort=jahr_sort', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(19, 'Uni Genève', 'https://slsp-unige.primo.exlibrisgroup.com/discovery/jsearch?query=any,contains,XTITLEX&tab=jsearch_slot&vid=41SLSP_UGE:VU1&offset=0&journals=any,XTITLEX', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(20, 'Uni Genève', 'https://slsp-unige.primo.exlibrisgroup.com/discovery/openurl?institution=41SLSP_UGE&vid=41SLSP_UGE:VU1&ctx_ver=Z39.88-2004', 0, 0, 0, 0, 0, 1, 0, 1, 'LIB1', 1, 0, 0, 0, 0),
(22, 'Uni Basel', 'https://ub-webform.ub.unibas.ch/form_koplink/header?title=XATITLEX&man_benutzernummer=&jbtitle=XTITLEX&author=XAULASTX&year=XDATEX&volume=XVOLUMEX&issue=XISSUEX&pages=XPAGESX&issn=XISSNX&man_kommentar=[Internal%20Ref.%20%3A%20XPIDX]&man_lieferart=WEB&man_ausland=Nur%20in%20der%20Schweiz%20bestellen&docurl=XDOIX&meduid=XPMIDX&source=openillink', 0, 0, 0, 0, 0, 1, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(42, 'Uni Bern', 'https://bib.unibe.ch/sfx/en', 0, 0, 0, 0, 0, 1, 0, 1, 'LIB1', 1, 0, 0, 0, 0),
(24, 'Doctor-Doc', 'http://www.doctor-doc.com/version1.0/daia.do?issn=XISSNX', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(25, 'ZDB', 'https://zdb-katalog.de/list.xhtml?t=XTITLEX&key=tit&asc=false&sort=jahr_sort', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(26, 'Ulrichs', 'http://ulrichsweb.com/ulrichsweb/Search/ViewSearchResults.asp?navPage=1&SortOrder=Asc&SortField=f_display_title&collection=SERIAL&QueryMode=Simple&ScoreThreshold=0&ResultCount=25&ResultTemplate=quickSearchResults.hts&QueryText=sn=XISSNX', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(27, 'perUnil', 'https://www2.unil.ch/perunil/biomed/index.php/site/advSearchResults?advsearch=advsearch&C1[op]=AND&C1[search_type]=titre&C1[text]=&C2[op]=AND&C2[search_type]=editeur&C2[text]=&C3[op]=AND&C3[search_type]=issn&C3[text]=XISSNX&support=0&accessunil=1&openaccess=1&yt0=Chercher&sujet=&plateforme=&licence=&statutabo=&localisation=', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(28, 'RP/VZ', 'https://nb-helveticat.primo.exlibrisgroup.com/discovery/jsearch?query=title,contains,XTITLEX&tab=jsearch_slot&vid=41SNL_51_INST:helveticat&offset=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(29, 'Helveticat', 'https://www.helveticat.ch/discovery/search?query=title,contains,XTITLEX&tab=LibraryCatalog&search_scope=Helveticat&vid=41SNL_51_INST:helveticat&offset=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(30, 'RERO CC', 'https://bib.rero.ch/global/search/documents?q=XTITLEX', 0, 0, 0, 0, 1, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(47, 'RERO CC', 'https://bib.rero.ch/global/search/documents?q=XTITLEX', 0, 0, 0, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(50, 'Swisscovery', 'https://swisscovery.slsp.ch/discovery/search?query=issn,exact,XISSNX,AND&pfilter=rtype,exact,journals,AND&tab=41SLSP_NETWORK&search_scope=DN_and_CI&sortby=rank&vid=41SLSP_NETWORK:VU1_UNION&mode=advanced&offset=0', 1, 0, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(51, 'Swisscovery', 'https://swisscovery.slsp.ch/discovery/search?query=title,contains,XTITLEX,AND&tab=41SLSP_NETWORK&search_scope=DN_and_CI&sortby=rank&vid=41SLSP_NETWORK:VU1_UNION&mode=advanced&offset=0', 0, 0, 0, 0, 1, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(52, 'Swisscovery', 'https://swisscovery.slsp.ch/discovery/search?query=isbn,exact,XISBNX,AND&tab=41SLSP_NETWORK&search_scope=DN_and_CI&sortby=rank&vid=41SLSP_NETWORK:VU1_UNION&mode=advanced&offset=0', 0, 1, 0, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(34, 'Doctor-Doc', 'http://www.doctor-doc.com/version1.0/daia.do?jtitle=XTITLEX', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(35, 'Ulrichs', 'http://ulrichsweb.com/ulrichsweb/Search/ViewSearchResults.asp?navPage=1&SortOrder=Asc&SortField=f_display_title&collection=SERIAL&QueryMode=Simple&ScoreThreshold=0&ResultCount=25&ResultTemplate=quickSearchResults.hts&QueryText=kt=XTITLEX', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(36, 'NLM', 'http://www.ncbi.nlm.nih.gov/sites/entrez?Db=nlmcatalog&Cmd=DetailsSearch&Term=XTITLEX', 0, 0, 1, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(37, 'Google', 'http://www.google.ch/search?hl=fr&newwindow=1&q=%22XTITLEX%22', 0, 0, 1, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(38, 'Amazon', 'http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=XTITLEX', 0, 0, 0, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 1, 0, 0),
(39, 'SAPHIR', 'https://opac.saphirdoc.ch/cgi-bin/koha/opac-search.pl?idx=ti&q=XTITLEX', 0, 0, 0, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(43, 'Uni Zürich', 'https://uzb.swisscovery.slsp.ch/discovery/openurl?institution=41SLSP_UZB&vid=41SLSP_UZB:UZB&lang=fr&ctx_ver=Z39.88-2004', 0, 0, 0, 0, 0, 1, 0, 1, 'LIB1', 1, 0, 1, 0, 0),
(44, 'NLM', 'https://www.docline.gov/docline/login/?next=/docline/', 0, 0, 0, 0, 0, 1, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(49, 'Swisscovery', 'https://swisscovery.slsp.ch/discovery/search?query=title,contains,XTITLEX,AND&pfilter=rtype,exact,books,AND&tab=41SLSP_NETWORK&search_scope=DN_and_CI&sortby=rank&vid=41SLSP_NETWORK:VU1_UNION&mode=advanced&offset=0', 0, 0, 0, 1, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(45, 'SUBITO', 'http://www.subito-doc.de/order/openurl.php?sid=[my_subito_broker_id]:[my_subito_customer_code]%2F[my_subito_password]&&issn=XISSNX&title=XTITLEX&volume=XVOLUMEX&issue=XISSUEX&date=XDATEX&pages=XPAGESX&atitle=XATITLEX&aulast=XAULASTX', 0, 0, 0, 0, 0, 1, 0, 0, 'LIB1', 1, 0, 0, 0, 0),
(48, 'Swisscovery', 'https://swisscovery.slsp.ch/discovery/search?query=title,contains,XTITLEX,AND&pfilter=rtype,exact,journals,AND&tab=41SLSP_NETWORK&search_scope=DN_and_CI&sortby=rank&vid=41SLSP_NETWORK:VU1_UNION&&mode=advanced&offset=0', 0, 0, 1, 0, 0, 0, 0, 0, 'LIB1', 1, 0, 0, 0, 0);

