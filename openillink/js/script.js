/* 
   This file is part of OpenILLink software.
   Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020 CHUV.
   Original author(s): Pablo Iriarte <pablo@iriarte.ch>
   Other contributors are listed in the AUTHORS file at the top-level directory of this distribution.
   
   OpenILLink is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   OpenILLink is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
*/

function checkAll(commande) {
    for (i=0, n=commande.elements.length; i<n; i++){
        var objName = commande.elements[i].name;
        var objType = commande.elements[i].type;
        if (objType = "checkbox"){
            box = eval(commande.elements[i]);
            if (box.checked == false) box.checked = true;
        }
    }
}

function unCheckAll(commande) {
    for (i=0, n=commande.elements.length; i<n; i++){
        var objName = commande.elements[i].name;
        var objType = commande.elements[i].type;
        if (objType = "checkbox"){
            box = eval(commande.elements[i]);
            if (box.checked == true) box.checked = false;
        }
    }
}

if(!Array.isArray) {
  Array.isArray = function(arg) {
    return Object.prototype.toString.call(arg) === '[object Array]';
  };
}

function cleanIllForm(item_index){
    /* Clear content of order form at given index. Index can be an array too */
    var items_indexes = [];
    if (Array.isArray(items_indexes)) {
        items_indexes = item_index;
    } else {
        items_indexes = [item_index];
    }
    for (var i = 0; i < items_indexes.length; i++) {
        var this_item_index = item_index[i];
        document.commande["atitle_"+this_item_index].value = '';
        document.commande["title_"+this_item_index].value = '';
        document.commande["auteurs_"+this_item_index].value = '';
        document.commande["date_"+this_item_index].value = '';
        document.commande["volume_"+this_item_index].value = '';
        document.commande["issue_"+this_item_index].value = '';
        document.commande["pages_"+this_item_index].value = '';
        document.commande["issn_"+this_item_index].value = '';
        document.commande["uid_"+this_item_index].value = '';
        document.commande["remarquespub_"+this_item_index].value = '';
    }
}

var resolved_data = {};
function resolve(item_index, max_retry, tid, uids, genre, title, date, volume, issue, suppl, pages, atitle, author, edition, issn, uid, src_page) {
    /* Retrieve location where requested document can be accessed.
       Resolves using based on provided parameters (tid, uids, genre, title, date, volume, issue, suppl, pages, atitle, author, edition, issn, uid) or based on values in form at given 'item_index'.
    
    */
    // TODO: only try to resolve if enough data provided ? Only resolve if article-level resolution is possible (pages or pmid or doi provided)?
    var http = getHTTPObject();
    var url = "resolve.php";
    if (http && resolver_enabled) {
        url_parameters = {}
        if (tid === undefined) {
            _tid_code = document.commande["tid_"+item_index].value.trim();
            if (_tid_code != "") {
                url_parameters['tid'] = _tid_code;
            }
        } else {
            url_parameters['tid'] = tid;
        }
        if (uids === undefined) {
            _uids = document.commande["uids_"+item_index].value.trim();
            if (_uids != "") {
                url_parameters['uids'] = _uids;
            }
        } else {
            url_parameters['uids'] = uids;
        }
        if (genre === undefined) {
            _genre = document.commande["genre_"+item_index].value.trim();
            if (_genre != "") {
                url_parameters['genre'] = _genre;
            }
        } else {
            url_parameters['genre'] = genre;
        }
        if (title === undefined) {
            _title = document.commande["title_"+item_index].value.trim();
            if (_title != "") {
                url_parameters['title'] = _title;
            }
        } else {
            url_parameters['title'] = title;
        }
        if (date === undefined) {
            _date = document.commande["date_"+item_index].value.trim();
            if (_date != "") {
                url_parameters['date'] = _date;
            }
        } else {
            url_parameters['date'] = date;
        }
         if (volume === undefined) {
            _volume = document.commande["volume_"+item_index].value.trim();
            if (_volume != "") {
                url_parameters['volume'] = _volume;
            }
        } else {
            url_parameters['volume'] = volume;
        }
        if (issue === undefined) {
            _issue = document.commande["issue_"+item_index].value.trim();
            if (_issue != "") {
                url_parameters['issue'] = _issue;
            }
        } else {
            url_parameters['issue'] = issue;
        }
        if (suppl === undefined) {
            _suppl = document.commande["suppl_"+item_index].value.trim();
            if (_suppl != "") {
                url_parameters['suppl'] = _suppl;
            }
        } else {
            url_parameters['suppl'] = suppl;
        }
        if (pages === undefined) {
            _pages = document.commande["pages_"+item_index].value.trim();
            if (_pages != "") {
                url_parameters['pages'] = _pages;
            }
        } else {
            url_parameters['pages'] = pages;
        }
        if (atitle === undefined) {
            _atitle = document.commande["atitle_"+item_index].value.trim();
            if (_atitle != "") {
                url_parameters['atitle'] = _atitle;
            }
        } else {
            url_parameters['atitle'] = atitle;
        }
        if (author === undefined) {
            _author = document.commande["auteurs_"+item_index].value.trim();
            if (_author != "") {
                url_parameters['auteurs'] = _author;
            }
        } else {
            url_parameters['auteurs'] = author;
        }
        if (edition === undefined) {
            _edition = document.commande["edition_"+item_index].value.trim();
            if (_edition != "") {
                url_parameters['edition'] = _edition;
            }
        } else {
            url_parameters['edition'] = edition;
        }
        if (issn === undefined) {
            _issn_isbn = document.commande["issn_"+item_index].value.trim();
            if (_issn_isbn != "") {
                url_parameters['issn'] = _issn_isbn;
            }
        } else {
            url_parameters['issn'] = issn;
        }
        if (uid === undefined) {
            _uid = document.commande["uid_"+item_index].value.trim();
            if (_uid != "") {
                url_parameters['uid'] = _uid;
            }
        } else {
            url_parameters['uid'] = uid;
        }
        var sorted_keys = Object.keys(url_parameters).sort(); 
        var sorted_url_parameters = [];
        for (var i = 0; i < sorted_keys.length; i++) {
            sorted_url_parameters.push(encodeURIComponent(sorted_keys[i]) + '=' + encodeURIComponent(url_parameters[sorted_keys[i]]));
        }
        /*var url_parameters_string = Object.keys(url_parameters).sort().map(key => {
                                        return encodeURIComponent(key) + '=' + encodeURIComponent(url_parameters[key])
                                    }).join('&');*/
        var url_parameters_string = sorted_url_parameters.join('&');
        if (item_index in resolved_data && 'cache' in resolved_data[item_index] && url_parameters_string in resolved_data[item_index]['cache']) {
            handle_resolve_response(null, item_index, 0, url_parameters_string, resolved_data[item_index]['cache'][url_parameters_string], src_page);
        } else {
            http.open("GET", url + "?" + url_parameters_string + "&referer=" + encodeURIComponent(referer), true);
            http.onreadystatechange = function(){handle_resolve_response(http, item_index, max_retry, url_parameters_string, null, src_page)};
            http.send(null);
        }
    }
}

function handle_resolve_response(http, item_index, max_retry, url_parameters_string, cached, src_page){
    if (src_page === undefined) {
        src_page = '';
    }
    if ((typeof cached !== 'undefined') && cached != null){
        cached_html = cached['msg'];
        if (cached['nb'] > 0) {
            document.getElementById("resolvedurlblock_" + item_index).style.display="";
        } else if (src_page != 'orders_detail') {
            document.getElementById("resolvedurlblock_" + item_index).style.display="none";
        }
        if (src_page == 'orders_detail'){
            cached_html = cached_html.replace("is-warning", "is-success");
        }
        document.getElementById("resolvedurlblock_" + item_index).innerHTML = cached_html;
    } else {
        if (http.readyState == 4) {
            if (http.status === 200) {
                var response = JSON.parse(http.responseText);
                if (response['nb'] > 0) {
                    document.getElementById("resolvedurlblock_" + item_index).style.display="";
                } else if (src_page != 'orders_detail') {
                    document.getElementById("resolvedurlblock_" + item_index).style.display="none";
                }
                var response_msg = response['msg'];
                if (src_page == 'orders_detail'){
                    response_msg = response_msg.replace("is-warning", "is-success");
                }
                document.getElementById("resolvedurlblock_" + item_index).innerHTML = response_msg;
                document.getElementById("resolver_search_params_" + item_index).value = response['search_params'];
                // cache for later reuse
                if (!(item_index in resolved_data)) {
                    resolved_data[item_index] = {};
                }
                if (!('cache' in resolved_data[item_index])) {
                    resolved_data[item_index]['cache'] = {};
                }
                resolved_data[item_index]['cache'][url_parameters_string] = response;

            } else if (http.status === 429) {
                // retry later
                setTimeout(function () {resolve(item_index, max_retry - 1);}, 1000);
            }
        }
    }
}

function lookupid(item_index, openillink_config_email) {
    /* Fill in order form at given index. item_index can be an index too in case all items must be looked up by PMID */
    // reset resolver message, if any
    document.getElementById("resolvedurlblock_" + item_index).style.display="none";
    document.getElementById("resolvedurlblock_" + item_index).innerHTML = "";
    // si la valeur du champ uids est vide
    if (document.commande["uids_"+item_index].value == ""){
        // message d'alerte
        alert('entrez un identificateur avant');
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "pmid")){
        // alors on remplit automatiquement le formulaire, ceci écrase ce qui est inscrit dans le formulaire normal et l'envoie
        cleanIllForm(item_index);
        lookup_pmids([item_index], openillink_config_email, 3);
        
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "reroid")){    
        cleanIllForm(item_index);
        updateIllform2(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "isbn")){
        cleanIllForm(item_index);
        updateIllform3(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "doi")){
        cleanIllForm(item_index);
        updateIllform4(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "wosid")){
        cleanIllForm(item_index);
        updateIllform5(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "isbn_swissbib")){
        cleanIllForm(item_index);
        updateIllform6(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "renouvaudmms_swissbib")){
        cleanIllForm(item_index);
        updateIllform7(item_index);
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "sru_marcxml_isbn")){
        cleanIllForm(item_index);
        updateIllform8(item_index, 'sru-marcxml-isbn');
    }
    if ((document.commande["uids_"+item_index].value != "") && (document.commande["tid_"+item_index].value == "sru_marcxml_mms")){
        cleanIllForm(item_index);
        updateIllform8(item_index, 'sru-marcxml-mms');
    }
}

var http_objects = [];
function get_http_obj(http_type, item_index) {
    if (!(http_type in http_objects) || !(item_index in http_objects[http_type])) {
        if (!(http_type in http_objects)) {
            http_objects[http_type] = [];
        }
        http_objects[http_type][item_index] = getHTTPObject();
    }
    return http_objects[http_type][item_index];
}

function get_node_name_value(root, nodeName, defaultValue, concatenate, attribute_condition, attribute_condition_value) {
    /*
    Return the value of the given nodeName in the tree.
    if concatenate is false, return only first node value. Else use value of concatenate parameter as string to join all values.
    
    if 'attribute_condition' and 'attribute_condition_value' are specified, only nodes that also match the given attribute and value are returned
    */
    var nodes = root.getElementsByTagName(nodeName);
    if (nodes.length > 0) {
        var values = [];
        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i].childNodes.length > 0) {
                if (typeof attribute_condition === 'undefined' || nodes[i].getAttribute(attribute_condition) == attribute_condition_value) {
                    if (concatenate === false) {
                        return nodes[i].childNodes[0].nodeValue;
                    } else {
                        values.push(nodes[i].childNodes[0].nodeValue);
                    }
                }
            }
        }
        return values.join(concatenate);
    }
    return "";
}

function get_node_name_value_old(root, nodeName, defaultValue, concatenate) {
    /*
    Return the value of the given nodeName in the tree.
    if concatenate is false, return only first node value. Else use value of concatenate parameter as string to join all values
    */
    var nodes = root.getElementsByTagName(nodeName);
    if (nodes.length > 0) {
        if (concatenate === false) {
            if (nodes[0].childNodes.length > 0) {
                return nodes[0].childNodes[0].nodeValue;
            }
        } else {
            var values = [];
            for (var i = 0; i < nodes.length; i++) {
                if (nodes[i].childNodes.length > 0) {
                    values.push(nodes[i].childNodes[0].nodeValue);
                }
            }
            return values.join(concatenate);
        }
    }
    return "";
}

function lookup_pmids(items_indexes, openillink_config_email, max_retry) {
    var http = getHTTPObject();
    var url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?version=2.0&db=pubmed&retmode=xml&tool=OpenLinker&email=" + openillink_config_email + "&id="
    if (http) {
        var pmids = [];
        var pmids_to_item_index = {};
        for (var i = 0; i < items_indexes.length; i++) {
            var pmid = document.commande["uids_"+items_indexes[i]].value.trim();
            pmids.push(pmid);
            if (!(pmid in pmids_to_item_index)) {
                pmids_to_item_index[pmid] = [];
            }
            pmids_to_item_index[pmid].push(items_indexes[i]);
        }
        http.open("GET", url + encodeURI(pmids.join(",")), true);
        http.onreadystatechange = function(){handle_pubmed_response(http, pmids_to_item_index, openillink_config_email, max_retry)};
        http.send(null);
    }
}

function handle_pubmed_response(http, pmids_to_item_index, openillink_config_email, max_retry){
    if (http.readyState == 4) {
        if (http.status === 200) {
            var xmlDoc = http.responseXML;
            var documentSummaryNodes = xmlDoc.getElementsByTagName("DocumentSummary")
            for (i = 0; i <documentSummaryNodes.length; i++) {
                var documentSummaryNode = documentSummaryNodes[i];
                var pmid = documentSummaryNode.getAttribute("uid");
                var atitle = get_node_name_value(documentSummaryNode, "Title", "", false);
                var aVernacularTitle = get_node_name_value(documentSummaryNode, "VernacularTitle", "", false);
                var authors = get_node_name_value(documentSummaryNode, "Name", "", false);
                var journal = get_node_name_value(documentSummaryNode, "FullJournalName", "", false);
                if (journal == "") {
                    journal = get_node_name_value(documentSummaryNode, "Source", "", false);
                }
                var annee = get_node_name_value(documentSummaryNode, "PubDate", "", false).substring(0,4);
                var vol = get_node_name_value(documentSummaryNode, "Volume", "-", false);
                var no = get_node_name_value(documentSummaryNode, "Issue", "-", false);
                var pages = get_node_name_value(documentSummaryNode, "Pages", "", false);
                var issn = get_node_name_value(documentSummaryNode, "ISSN", "", false);

                if (pmid in pmids_to_item_index) {
                    for (j = 0; j <pmids_to_item_index[pmid].length; j++) {
                        item_index = pmids_to_item_index[pmid][j];
                        document.commande["atitle_"+item_index].value = aVernacularTitle + (aVernacularTitle && atitle ? " " : "") + atitle;
                        document.commande["auteurs_"+item_index].value = authors;
                        document.commande["title_"+item_index].value = journal;
                        document.commande["date_"+item_index].value = annee;
                        document.commande["volume_"+item_index].value = vol;
                        document.commande["issue_"+item_index].value = no;
                        document.commande["pages_"+item_index].value = pages;
                        document.commande["issn_"+item_index].value = issn;
                        document.commande["uid_"+item_index].value = "pmid:" + document.commande["uids_"+item_index].value;
                        resolve(item_index, 1);
                    }
                }
            }
        } else if (http.status === 429) {
            // retry later
            setTimeout(function () {lookup_pmids(items_indexes, openillink_config_email, max_retry - 1);}, 1000);
        }
    }
}

//
// ********************************************************************************************************
//

//
// START PMID
//

var url = 'lookup.php?pmid=';

function handleHttpResponse(item_index) {
    var http = get_http_obj(1, item_index);
    if (http.readyState == 4) {
        if ((http.responseText.indexOf('<!-- Error>XML not found for id') == -1) 
        && (http.responseText.indexOf('<ERROR>Empty id list') == -1) 
        && (http.responseText.indexOf('<ERROR>Invalid uid') == -1)){
            //alert(http.responseText);
            var xmlDocument = http.responseText;
            var atitled = xmlDocument.indexOf("<Title>");
            atitled = atitled+7;
            var atitlef = xmlDocument.indexOf("</Title>",atitled);
            var atitle = xmlDocument.substring(atitled,atitlef);

            var aVernacularTitled = xmlDocument.indexOf("<VernacularTitle>");
            aVernacularTitled = aVernacularTitled+17;
            var aVernacularTitlef = xmlDocument.indexOf("</VernacularTitle>",aVernacularTitled);
            var aVernacularTitle = xmlDocument.substring(aVernacularTitled,aVernacularTitlef);

            var authorsd = xmlDocument.indexOf("<Name>");
            if (authorsd != -1) {
                authorsd = authorsd+6;
                var authorsf = xmlDocument.indexOf("</Name>",authorsd);
                var authors = xmlDocument.substring(authorsd,authorsf);
            }
            else {
                var authors = '';
            }
            var journald = xmlDocument.indexOf("<FullJournalName>");
            if (journald != -1){
                journald = journald+17;
                var journalf = xmlDocument.indexOf("</FullJournalName>",journald);
                var journal = xmlDocument.substring(journald,journalf);
            }
            else {
                journald = xmlDocument.indexOf("<Source>");
                journald = journald+8;
                var journalf = xmlDocument.indexOf("</Source>",journald);
                var journal = xmlDocument.substring(journald,journalf);
            }
            var anneed = xmlDocument.indexOf("<PubDate>");
            anneed = anneed+9;
            var anneef = anneed + 4;
            var annee = xmlDocument.substring(anneed,anneef);
            var vold = xmlDocument.indexOf("<Volume>");
            if (vold != -1) {
                vold = vold+8;
                var volf = xmlDocument.indexOf("</Volume>",vold);
                var vol = xmlDocument.substring(vold,volf);
            }
            else {
                var vol = '-';
            }
            var nod = xmlDocument.indexOf("<Issue>");
            if (nod != -1) {
                nod = nod+7;
                var nof = xmlDocument.indexOf("</Issue>",nod);
                var no = xmlDocument.substring(nod,nof);
            }
            else {
                var vol = '-';
            }
            var pagesd = xmlDocument.indexOf("<Pages>");
            pagesd = pagesd+7;
            var pagesf = xmlDocument.indexOf("</Pages>",pagesd);
            var pages = xmlDocument.substring(pagesd,pagesf);
            var issnd = xmlDocument.indexOf("<ISSN>");
            if (issnd != -1) {
                issnd = issnd+6;
                var issnf = xmlDocument.indexOf("</ISSN>",issnd);
                var issn = xmlDocument.substring(issnd,issnf);
            }
            else {
                var issn = '';
            }

            document.commande["atitle_"+item_index].value = unescape_string(aVernacularTitle) + (aVernacularTitle && atitle ? " " : "") + unescape_string(atitle);
            document.commande["auteurs_"+item_index].value = unescape_string(authors);
            document.commande["title_"+item_index].value = unescape_string(journal);
            document.commande["date_"+item_index].value = unescape_string(annee);
            document.commande["volume_"+item_index].value = unescape_string(vol);
            document.commande["issue_"+item_index].value = unescape_string(no);
            document.commande["pages_"+item_index].value = unescape_string(pages);
            document.commande["issn_"+item_index].value = unescape_string(issn);
            document.commande["uid_"+item_index].value = "pmid:" + document.commande["uids_"+item_index].value;
            isWorking[item_index] = false;
            resolve(item_index, 1);
        }
        else {
            isWorking[item_index] = false;
        }
    }
}

var isWorking = [];

function updateIllform(item_index) {
    var http = get_http_obj(1, item_index);
    if ((!item_index in isWorking || !isWorking[item_index]) && http) {
        var pmidValue = document.commande["uids_"+item_index].value;
        http.open("GET", url + encodeURI(pmidValue), true);
        http.onreadystatechange = function(){handleHttpResponse(item_index)};
        isWorking[item_index] = true;
        http.send(null);
    }
}

function getHTTPObject() {
    var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
  try {
  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
  try {
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  } catch (E) {
  xmlhttp = false;
  }
  }
  @else
  xmlhttp = false;
  @end @*/
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }
    return xmlhttp;
}

//var http = getHTTPObject();

//
// END PMID
//
//
// ********************************************************************************************************
//
//
// START RERO ID
//

var url2 = 'lookup.php?reroid=';
function handleHttpResponse2(item_index) {
    var http2 = get_http_obj(2, item_index);
    if (http2.readyState == 4) {
        // when no match for given reroid is found, the text "Pas de résultat trouvé" is outputted twice otherwise only once
        var notFoundTxt = (http2.responseText.match(/Pas de résultat trouvé/g) || []).length;
        if (http2.responseText.indexOf('>Error</font>') == -1  && notFoundTxt <= 1) {
            var docType = "book";
            var authors = '';
            var article = '';
            var annee = '';
            var editeur = '';
            var issn = '';
            var edition = '';
            var titre = '';
            var xmlDocument2 = http2.responseText;
            var titred = xmlDocument2.indexOf(">245<");
            titred = xmlDocument2.indexOf("$a",titred);
            titred = titred + 3;
            var titref2 = xmlDocument2.indexOf(" </td>",titred);
            var titref1 = xmlDocument2.indexOf("$c",titred);
            if (titref1 != -1) {
                if (titref1 < titref2){
                    titre = xmlDocument2.substring(titred,titref1);
                }
                else {
                    titre = xmlDocument2.substring(titred,titref2);
                }
                titre = titre.replace('$b ','');
            }
            var authorsd = xmlDocument2.indexOf(">100<");
            if (authorsd != -1) {
                authorsd = xmlDocument2.indexOf("$a",authorsd);
                authorsd = authorsd + 3;
                var authors2 = xmlDocument2.indexOf(" </td>",authorsd);
                if (authors2 != -1) {
                    authors = xmlDocument2.substring(authorsd,authors2);
                }
            }
            else {
                var authorsd = xmlDocument2.indexOf(">700<");
                if (authorsd != -1) {
                    authorsd = xmlDocument2.indexOf("$a",authorsd);
                    authorsd = authorsd + 3;
                    var authors2 = xmlDocument2.indexOf(" </td>",authorsd);
                    if (authors2 != -1) {
                        authors = xmlDocument2.substring(authorsd,authors2);
                        authors = authors.replace('$e','');
                    }
               }
            }
            var field260d = xmlDocument2.indexOf(">260<");
            var anneed = field260d;
            if (anneed != -1) {
                var field260f = xmlDocument2.indexOf(" </tr>", field260d);
                var editeurd = xmlDocument2.indexOf("$a",anneed);
                if (editeurd < field260f) {
                    var editeurf = xmlDocument2.indexOf(" </td>",anneed);
                    editeur = xmlDocument2.substring(editeurd + 3,editeurf);
                    editeur = editeur.replace('$b ','');
                }
                anneed = xmlDocument2.indexOf("$c",anneed);
                if (anneed < field260f){
                    anneed = anneed + 3;
                    var anneef = xmlDocument2.indexOf(" </td>",anneed);
                    annee = xmlDocument2.substring(anneed,anneef);
                }
            }
            var issnd = xmlDocument2.indexOf(">020<");
            if (issnd != -1) {
                issnd = xmlDocument2.indexOf("$a",issnd);
                var issnf = xmlDocument2.indexOf(" </td>",issnd);
                var issn = xmlDocument2.substring(issnd + 3,issnf);
            }
            var editiond = xmlDocument2.indexOf(">250<");
            if (editiond != -1) {
                editiond = xmlDocument2.indexOf("$a",editiond);
                var editionf = xmlDocument2.indexOf(" </td>",editiond);
                edition = xmlDocument2.substring(editiond + 3,editionf);
                if (editeur != '') {
                    edition = edition + " - ";
                }
            }
            var periodiqued = xmlDocument2.indexOf(">580<");
            var periodique = '';
            if (periodiqued != -1) {
                periodiqued = xmlDocument2.indexOf("$a",periodiqued);
                periodiquef = xmlDocument2.indexOf(" </td>",periodiqued);
                var periodique = xmlDocument2.substring(periodiqued + 3,periodiquef);
                if (periodique.length > 0) {
                    article = titre;
                    titre = periodique;
                    docType = "article";
                }
            }
            // alert("titred = " + titred + " titre2 = " + titre2 + " titre = " + titre);
            document.commande["genre_"+item_index].value = unescape_string(docType);
            document.commande["title_"+item_index].value = unescape_string(titre);
            document.commande["atitle_"+item_index].value = unescape_string(article);
            document.commande["auteurs_"+item_index].value = unescape_string(authors);
            document.commande["date_"+item_index].value = unescape_string(annee);
            document.commande["edition_"+item_index].value = unescape_string(edition + editeur);
            document.commande["edition_"+item_index].value = unescape_string(edition + editeur);
            document.commande["issn_"+item_index].value = unescape_string(issn);
            document.commande["uid_"+item_index].value = "RERO:" + document.commande["uids_"+item_index].value;
            isWorking2[item_index] = false;
            resolve(item_index, 1);
        }
        else {
            alert("Aucun resultat pour la recherche effectuée");
            isWorking2[item_index] = false;
        }
    }
}
var isWorking2 = [];

function updateIllform2(item_index) {
    var http2 = get_http_obj(2, item_index);
    if ((!item_index in isWorking || !isWorking2[item_index]) && http2) {
        var idrero = document.commande["uids_"+item_index].value;
        http2.open("GET", url2 + encodeURI(idrero), true);
        http2.onreadystatechange = function(){handleHttpResponse2(item_index)};
        isWorking2[item_index] = true;
        http2.send(null);
    }
}


//
// END RERO ID
//
//
// ********************************************************************************************************
//
//
// START ISBN
//
var url3 = 'lookup.php?isbn=';
function handleHttpResponse3(item_index) {
    var http3 = get_http_obj(3, item_index);
    if (http3.readyState == 4) {
        if (http3.responseText.indexOf('invalid') == -1 && http3.responseText.indexOf('>Pas de résultat trouvé<') == -1) {
            // alert(http3.responseText);
            var xmlDocument3 = http3.responseText;
            // alert(xmlDocument3);
            var titred = xmlDocument3.indexOf(">245<");
            titred = xmlDocument3.indexOf("$a",titred);
            titred = titred + 3;
            var titre2 = xmlDocument3.indexOf("$c",titred);
            if (titre2 != -1) {
                var titre = xmlDocument3.substring(titred,titre2 - 3);
                titre = titre.replace('$b ','');
            }
            var authorsd = xmlDocument3.indexOf(">100<");
            if (authorsd != -1) {
                authorsd = xmlDocument3.indexOf("$a",authorsd);
                authorsd = authorsd + 3;
                var authors2 = xmlDocument3.indexOf(" </td>",authorsd);
                if (authors2 != -1) {
                    var authors = xmlDocument3.substring(authorsd,authors2);
                }
            }
            else {
                var authorsd = xmlDocument3.indexOf(">700<");
                if (authorsd != -1) {
                    authorsd = xmlDocument3.indexOf("$a",authorsd);
                    authorsd = authorsd + 3;
                    var authors2 = xmlDocument3.indexOf(" </td>",authorsd);
                    if (authors2 != -1) {
                        var authors = xmlDocument3.substring(authorsd,authors2);
                        authors = authors.replace('$e','');
                    }
                }
            }
            var anneed = xmlDocument3.indexOf(">260<");
            if (anneed != -1) {
                var editeurd = xmlDocument3.indexOf("$a",anneed);
                anneed = xmlDocument3.indexOf("$c",anneed);
                anneed = anneed + 3;
                var anneef = xmlDocument3.indexOf(" </td>",anneed);
                var annee = xmlDocument3.substring(anneed,anneef);
                var editeur = xmlDocument3.substring(editeurd + 3,anneed - 5);
                editeur = editeur.replace('$b ','');
            }
            else {
                var annee = '';
                var editeur = '';
            }
            var issnd = xmlDocument3.indexOf(">020<");
            if (issnd != -1) {
                issnd = xmlDocument3.indexOf("$a",issnd);
                var issnf = xmlDocument3.indexOf(" </td>",issnd);
                var issn = xmlDocument3.substring(issnd + 3,issnf);
            }
            else {
                var issn = '';
            }
            var editiond = xmlDocument3.indexOf(">250<");
            if (editiond != -1) {
                editiond = xmlDocument3.indexOf("$a",editiond);
                var editionf = xmlDocument3.indexOf(" </td>",editiond);
                var edition = xmlDocument3.substring(editiond + 3,editionf);
                if (editeur != '') {
                    edition = edition + " - ";
                }
            }
            else {
                var edition = '';
            }
            // alert("titred = " + titred + " titre2 = " + titre2 + " titre = " + titre);
            document.commande["genre_"+item_index].value = "book";
            document.commande["title_"+item_index].value = unescape_string(titre);
            document.commande["auteurs_"+item_index].value = unescape_string(authors);
            document.commande["date_"+item_index].value = unescape_string(annee);
            document.commande["edition_"+item_index].value = unescape_string(edition + editeur);
            document.commande["issn_"+item_index].value = unescape_string(issn);
            document.commande["uid_"+item_index].value = "ISBN:" + document.commande["uids_"+item_index].value;
            isWorking3[item_index] = false;
            resolve(item_index, 1);
        }
        else {
            isWorking3[item_index] = false;
        }
    }
}

var isWorking3 = [];

function updateIllform3(item_index) {
    var http3 = get_http_obj(3, item_index);
    if ((!item_index in isWorking || !isWorking3[item_index]) && http3) {
        var isbn = document.commande["uids_"+item_index].value;
        http3.open("GET", url3 + encodeURI(isbn), true);
        http3.onreadystatechange = function(){handleHttpResponse3(item_index)};
        isWorking3[item_index] = true;
        http3.send(null);
    }
}

//
// END ISBN
//
// ********************************************************************************************************
//
//
// START DOI
//
var url4 = 'lookup.php?doi=';
function handleHttpResponse4(item_index) {
    var http4 = get_http_obj(4, item_index);
    if (http4.readyState == 4) {
        if (http4.responseText.indexOf('<error>DOI not found') == -1 && http4.responseText.indexOf('>Malformed DOI') == -1) {
            // alert(http4.responseText);
            var xmlDocument4 = http4.responseText;
            // alert(xmlDocument4);
            var atitled = xmlDocument4.indexOf("<article_title>");
            atitled = atitled+15;
            var atitlef = xmlDocument4.indexOf("</article_title>",atitled);
            var atitle = xmlDocument4.substring(atitled,atitlef);
            var typedocd = xmlDocument4.indexOf("<doi type=");
            typedocd = typedocd+11
            var typedocf = xmlDocument4.indexOf(">",typedocd);
            var typedoc = xmlDocument4.substring(typedocd,typedocf-1);
            var issnd = xmlDocument4.indexOf("<issn type=\"print\">");
            if (issnd != -1) {
                issnd = issnd+19
                var issnf = xmlDocument4.indexOf("</issn>",issnd);
                var issn = xmlDocument4.substring(issnd,issnf);
                // issn = issn.substring(0,4) + "-" + issn.substring(4,8);
            }
            else {
                var issnd = xmlDocument4.indexOf("<issn type=\"electronic\">");
                if (issnd != -1) {
                    issnd = issnd+24
                    var issnf = xmlDocument4.indexOf("</issn>",issnd);
                    var issn = xmlDocument4.substring(issnd,issnf);
                    // issn = issn.substring(0,4) + "-" + issn.substring(4,8);
                }
            }
            var journald = xmlDocument4.indexOf("<journal_title>");
            if (journald != -1) {
                journald = journald+15;
                var journalf = xmlDocument4.indexOf("</journal_title>",journald);
                var journal = xmlDocument4.substring(journald,journalf);
            }
            else {
                var journal = "";
            }
            var authorspd = xmlDocument4.indexOf("<given_name>");
            if (authorspd != -1) {
                authorspd = authorspd+12;
                var authorspf = xmlDocument4.indexOf("</given_name>",authorspd);
                var authorsp = xmlDocument4.substring(authorspd,authorspf);
            }
            else {
                var authorsp = "";
            }
            var authorsd = xmlDocument4.indexOf("<surname>");
            if (authorsd != -1) {
                authorsd = authorsd+9;
                var authorsf = xmlDocument4.indexOf("</surname>",authorsd);
                var authors = xmlDocument4.substring(authorsd,authorsf);
                authors = authors + " " + authorsp;
            }
            else {
                var authors = "";
            }
            var anneef = xmlDocument4.indexOf("</year>");
            if (anneef != -1) {
                anneed = anneef-4;
                // var anneef = xmlDocument4.indexOf("</year>",anneed);
                var annee = xmlDocument4.substring(anneed,anneef);
            }
            else {
                var annee = "";
            }
            var vold = xmlDocument4.indexOf("<volume>");
            if (vold != -1) {
                vold = vold+8;
                var volf = xmlDocument4.indexOf("</volume>",vold);
                var vol = xmlDocument4.substring(vold,volf);
            }
            else {
                var vol = "";
            }
            var nod = xmlDocument4.indexOf("<issue>");
            if (nod != -1) {
                nod = nod+7;
                var nof = xmlDocument4.indexOf("</issue>",nod);
                var no = xmlDocument4.substring(nod,nof);
            }
            else {
                var no = "";
            }
            var pagesd = xmlDocument4.indexOf("<first_page>");
            if (pagesd != -1) {
                pagesd = pagesd+12;
                var pagesf = xmlDocument4.indexOf("</first_page>",pagesd);
                var pagesi = xmlDocument4.substring(pagesd,pagesf);
            }
            else {
                var pagesi = "";
            }
            var pagesfd = xmlDocument4.indexOf("<last_page>");
            if (pagesfd != -1) {
                pagesfd = pagesfd+11;
                var pagesff = xmlDocument4.indexOf("</last_page>",pagesfd);
                var pagesf = xmlDocument4.substring(pagesfd,pagesff);
                var pages = pagesi + "-" + pagesf;
            }
            else  {
                var pages = pagesi;
            }
            // alert("titred = " + titred + " titre2 = " + titre2 + " titre = " + titre);
            if (typedoc == "book_title") {
                document.commande["genre_"+item_index].value = "book";
            }
            if (typedoc == "book_content") {
                document.commande["genre_"+item_index].value = "bookitem";
            }
            document.commande["atitle_"+item_index].value = unescape_string(atitle);
            document.commande["title_"+item_index].value = unescape_string(journal);
            document.commande["auteurs_"+item_index].value = unescape_string(authors);
            document.commande["date_"+item_index].value = unescape_string(annee);
            document.commande["volume_"+item_index].value = unescape_string(vol);
            document.commande["issue_"+item_index].value = unescape_string(no);
            document.commande["pages_"+item_index].value = unescape_string(pages);
            // document.commande["edition_"+item_index].value = typedoc;
            document.commande["issn_"+item_index].value = unescape_string(issn);
            document.commande["uid_"+item_index].value = "DOI:" + document.commande["uids_"+item_index].value;
            isWorking4[item_index] = false;
            resolve(item_index, 1);
        }
        else {
            isWorking4[item_index] = false;
        }
    }
}


var isWorking4 = [];

function updateIllform4(item_index) {
    var http4 = get_http_obj(4, item_index);
    if ((!item_index in isWorking || !isWorking4[item_index]) && http4) {
        var doi = document.commande["uids_"+item_index].value;
        http4.open("GET", url4 + encodeURI(doi), true);
        http4.onreadystatechange = function(){handleHttpResponse4(item_index)};
        isWorking4[item_index] = true;
        http4.send(null);
    }
}

//
// END DOI
//
// ********************************************************************************************************
//
// START WoS ID
// UT exemple 000266183100022
//
var url5 = 'lookup.php?wosid=';
// Wos ID d'exemple : A1991FK71500008

function handleHttpResponse5(item_index) {
    var http5 = get_http_obj(5, item_index);
    if (http5.readyState == 4) {
        try {
  console.log(http5.responseText);
            result = JSON.parse(http5.responseText.trim());
        } catch (e) {
            console.error("Parsing error:", e); 
            console.error("Response", http5.responseText); 
        }
        if (result && result.hasOwnProperty("return") && result.return.hasOwnProperty("recordsFound") && result.return.recordsFound != "0") {
            // initialisation des variables target
            var atitle = '';
            var authorsf = '';
            var journal = '';
            var annee = '';
            var vol = '';
            var no = '';
            var pages = '';
            var issn = '';
            var pmid = '';
            var isiid = '';
            var doi = '';
            var notesn = '';
            // fin initialisation
            if (result.return.records.hasOwnProperty("title")) {
                atitle = result.return.records.title.value;
            }
            if(result.return.records.hasOwnProperty("doctype")){
                    doctype = result.return.records.doctype.value;
                    if (Array.isArray(doctype)) {
                        doctype = doctype.join("; ");
                    }
                    doctype = doctype.toLowerCase();
            }
            if (result.return.records.hasOwnProperty("authors")) {
                console.log(typeof result.return.records.authors.value);
                if(Array.isArray(result.return.records.authors.value)){
                    authorsf = result.return.records.authors.value.join("; ");
                }
                else{
                    authorsf = result.return.records.authors.value;
                }
            }
            if (result.return.records.hasOwnProperty("source")) {
                var inode;
            for (var i in result.return.records.source) {
            inode = result.return.records.source[i];
            switch(inode.label) {
                case "Volume":
                    vol = inode.value;
                    break;
                case "Issue":
                    no = inode.value;
                    break;
                case "Pages":
                    pages = inode.value;
                    break;
                case "SourceTitle":
                    journal = inode.value;
                    break;
                case "Published.BiblioYear":
                    annee = inode.value;
                    break;
            }
        }
    }

    if (result.return.records.hasOwnProperty("other"))
    {
        for (var i in result.return.records.other) {
           inode = result.return.records.other[i];
           switch(inode.label)
           {
               case "Identifier.Doi":
                   doi = inode.value;
                   break;
               case "Identifier.Issn":
                   issn = inode.value;
                   break;
               case "Identifier.Ids":
                   isiid = inode.value;
                   break;
           }
        }
    }

    if (doi !== '')
    {
        notesn = "DOI:" + doi;
    }

    document.commande["genre_"+item_index].value = doctype;
    document.commande["atitle_"+item_index].value = atitle;
    document.commande["title_"+item_index].value = journal;
    document.commande["auteurs_"+item_index].value = authorsf;
    document.commande["date_"+item_index].value = annee;
    document.commande["volume_"+item_index].value = vol;
    document.commande["issue_"+item_index].value = no;
    document.commande["pages_"+item_index].value = pages;
    document.commande["issn_"+item_index].value = issn;
    document.commande["uid_"+item_index].value = "WOSUT:" + document.commande["uids_"+item_index].value;
    document.commande["remarquespub_"+item_index].value = notesn;
    isWorking5[item_index] = false;
    resolve(item_index, 1);
    // entryForm.submit();
  }
  // Message d'erreur si le WOSID n'est pas valable
  else 
      if ( result && result.hasOwnProperty("return") && result.return.hasOwnProperty("recordsFound") && result.return.recordsFound == "0") {
          alert('WOS ID not found, please check your reference');
    isWorking5[item_index] = false;
  }
  else
  {
    alert("La recherche n'a pas abouti: le service distant n'a pas repondu");
    isWorking5[item_index] = false;
  }
  }
}


var isWorking5 = [];

function updateIllform5(item_index) {
  var http5 = get_http_obj(5, item_index);
  if ((!item_index in isWorking || !isWorking5[item_index]) && http5) {
    var wosid = document.commande["uids_"+item_index].value;
	console.log(url5 + encodeURI(wosid))
    http5.open("GET", url5 + encodeURI(wosid), true);
    http5.onreadystatechange = function(){handleHttpResponse5(item_index)};
    isWorking5[item_index] = true;
    http5.send(null);
  }
}

//
// END Wos ID
// ********************************************************************************************************
//
// START ISBN swissbib swissbib
// Any supported identifier. For eg: 9780444632746
//
var url6 = 'lookup.php?swissbib-identifier=';
// sample ISBN : 9780444632746

function handleHttpResponse6(item_index) {
    var http6 = get_http_obj(6, item_index);
    if (http6.readyState == 4) {
        try {
  console.log(http6.responseText);
            result = JSON.parse(http6.responseText.trim());
        } catch (e) {
            console.error("Parsing error:", e);
            console.error("Response", http6.responseText);
        }
        if (result && result.hasOwnProperty("numberOfRecords") && result.numberOfRecords != "0") {
            // initialisation des variables target
            var docType = "book";
			var authorslist = [];
            var authors = '';
            var article = '';
            var annee = '';
            var editeur = '';
            var issn = '';
            var edition = '';
            var titre = '';

            // fin initialisation
			var record = result.collection[0];
			var datafield;
			var tag;
			var ind1;
			var ind2;
			var subfield;
			for (var i in record.fields) {
				datafield = record.fields[i];
				tag = Object.keys(datafield)[0];
				if (tag == "245") {
					// title
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						/*
						if (datafield[tag].hasOwnProperty("ind1")) {
							ind1 = datafield[tag].ind1;
						} else {
							ind1 = "";
						}
						if (datafield[tag].hasOwnProperty("ind2")) {
							ind2 = datafield[tag].ind2;
						} else {
							ind2 = "";
						}*/
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a" || code == "b" || code == "c") {
								if (titre != "") {titre += " ";}
								titre += subfield[code];
							}
						}
					}
				} else if (tag == "100" || tag == "700") {
					// authors
					var this_author = "";
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a" || code == "D") {
								if (this_author != "") {this_author += ", ";}
								this_author += subfield[code];
							}
						}
						if (this_author += "") {
							authorslist.push(this_author);
						}
					}
				} else if (tag == "260" || tag == "264") {
					// year / editor
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "b") {
								editeur = subfield[code];
							} else if (code == "c") {
								annee += subfield[code];
							}
						}
					}
				} else if (tag == "020") {
					// issn
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								issn = subfield[code];
							}
						}
					}
				} else if (tag == "250") {
					// edition
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								edition = subfield[code];
							}
						}
					}
				} else if (tag == "580") {
					// article ?
					var periodique = '';
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								periodique = subfield[code];
								if (periodique) {
									article = titre;
									titre = periodique;
									docType = "article";
								}
							}
						}
					}
				}
			}

		authors = authorslist.join("; ");
		if (edition && editeur) {
			edition += " - ";
		}

		document.commande["genre_"+item_index].value = docType;
		document.commande["title_"+item_index].value = titre;
		document.commande["atitle_"+item_index].value = article;
		document.commande["auteurs_"+item_index].value = authors;
		document.commande["date_"+item_index].value = annee;
		document.commande["edition_"+item_index].value = edition + editeur;
		document.commande["issn_"+item_index].value = issn;
		document.commande["uid_"+item_index].value = "ISBN:" + document.commande["uids_"+item_index].value;
		isWorking6[item_index] = false;
        resolve(item_index, 1);
    // entryForm.submit();
  }
  // Message d'erreur si le ISBN n'est pas valable
  else if (result && result.hasOwnProperty("numberOfRecords") && result.numberOfRecords == "0") {
          alert('Identifier not found, please check your reference');
    isWorking6[item_index] = false;
  }
  else
  {
    alert("La recherche n'a pas abouti: le service distant n'a pas repondu");
    isWorking6[item_index] = false;
  }
  }
}


var isWorking6 = [];

function updateIllform6(item_index) {
  var http6 = get_http_obj(6, item_index);
  if ((!item_index in isWorking || !isWorking6[item_index]) && http6) {
    var swissbib_identifier = document.commande["uids_"+item_index].value;
	console.log(url6 + encodeURI(swissbib_identifier))
    http6.open("GET", url6 + encodeURI(swissbib_identifier), true);
    http6.onreadystatechange = function(){handleHttpResponse6(item_index)};
    isWorking6[item_index] = true;
    http6.send(null);
  }
}

//
// END ISBN swissbib
//
//
// ********************************************************************************************************
//
//
// START Renouvaud MMS swissbib
// Any supported identifier. For eg: 9780444632746
//
var url7 = 'lookup.php?swissbib-renouvaud-mms=';
// sample MMS: 991007671199702851 or 991009462209702852

function handleHttpResponse7(item_index) {
    var http7 = get_http_obj(7, item_index);
    if (http7.readyState == 4) {
        try {
  console.log(http7.responseText);
            result = JSON.parse(http7.responseText.trim());
        } catch (e) {
            console.error("Parsing error:", e);
            console.error("Response", http7.responseText);
        }
        if (result && result.hasOwnProperty("numberOfRecords") && result.numberOfRecords != "0") {
            // initialisation des variables target
            var docType = "book";
			var authorslist = [];
            var authors = '';
            var article = '';
            var annee = '';
            var editeur = '';
            var issn = '';
            var edition = '';
            var titre = '';

            // fin initialisation
			var record = result.collection[0];
			var datafield;
			var tag;
			var ind1;
			var ind2;
			var subfield;
			for (var i in record.fields) {
				datafield = record.fields[i];
				tag = Object.keys(datafield)[0];
				if (tag == "245") {
					// title
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						/*
						if (datafield[tag].hasOwnProperty("ind1")) {
							ind1 = datafield[tag].ind1;
						} else {
							ind1 = "";
						}
						if (datafield[tag].hasOwnProperty("ind2")) {
							ind2 = datafield[tag].ind2;
						} else {
							ind2 = "";
						}*/
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a" || code == "b" || code == "c") {
								if (titre != "") {titre += " ";}
								titre += subfield[code];
							}
						}
					}
				} else if (tag == "100" || tag == "700") {
					// authors
					var this_author = "";
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a" || code == "D") {
								if (this_author != "") {this_author += ", ";}
								this_author += subfield[code];
							}
						}
						if (this_author += "") {
							authorslist.push(this_author);
						}
					}
				} else if (tag == "260" || tag == "264") {
					// year / editor
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "b") {
								editeur += subfield[code];
							} else if (code == "c") {
								annee = subfield[code];
							}
						}
					}
				} else if (tag == "008" && annee == "") {
					// year from controlfield
					controlfield = datafield[tag]
					year_from_controlfield = controlfield.substring(7, 11);
					if (!isNaN(year_from_controlfield)) {
						annee = year_from_controlfield;
					}
				} else if (tag == "020") {
					// issn
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								issn = subfield[code];
							}
						}
					}
				} else if (tag == "250") {
					// edition
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								edition = subfield[code];
							}
						}
					}
				} else if (tag == "580") {
					// article ?
					var periodique = '';
					if (datafield[tag].hasOwnProperty("subfields")) {
						subfields = datafield[tag].subfields;
						for (var j in subfields) {
							subfield = subfields[j];
							code = Object.keys(subfield)[0];
							if (code == "a") {
								periodique = subfield[code];
								if (periodique) {
									article = titre;
									titre = periodique;
									docType = "article";
								}
							}
						}
					}
				}
			}

		authors = authorslist.join("; ");
		if (edition && editeur) {
			edition += " - ";
		}

		document.commande["genre_"+item_index].value = docType;
		document.commande["title_"+item_index].value = titre;
		document.commande["atitle_"+item_index].value = article;
		document.commande["auteurs_"+item_index].value = authors;
		document.commande["date_"+item_index].value = annee;
		document.commande["edition_"+item_index].value = edition + editeur;
		document.commande["issn_"+item_index].value = issn;
		document.commande["uid_"+item_index].value = "MMS:" + document.commande["uids_"+item_index].value.trim();
		isWorking7[item_index] = false;
        resolve(item_index, 1);
    // entryForm.submit();
  }
  // Message d'erreur si l'identifiant n'est pas valable
  else if (result && result.hasOwnProperty("numberOfRecords") && result.numberOfRecords == "0") {
          alert('Identifier not found, please check your reference');
    isWorking7[item_index] = false;
  }
  else
  {
    alert("La recherche n'a pas abouti: le service distant n'a pas repondu");
    isWorking7[item_index] = false;
  }
  }
}


var isWorking7 = [];

function updateIllform7(item_index) {
  var http7 = get_http_obj(7, item_index);
  if ((!item_index in isWorking || !isWorking7[item_index]) && http7) {
    var swissbib_identifier = document.commande["uids_"+item_index].value;
	console.log(url7 + encodeURI(swissbib_identifier))
    http7.open("GET", url7 + encodeURI(swissbib_identifier), true);
    http7.onreadystatechange = function(){handleHttpResponse7(item_index)};
    isWorking7[item_index] = true;
    http7.send(null);
  }
}

//
// END Renouvaud MMS swissbib
//
//
// ********************************************************************************************************
//
//
// START SRU retrieval (marcxml format)
//

function updateIllform8(item_index, lookup_index) {
    var http = getHTTPObject();
    var url = "lookup.php?" + lookup_index + "="
    if (http) {
        var identifier = document.commande["uids_"+item_index].value
        http.open("GET", url + encodeURI(identifier), true);
        http.onreadystatechange = function(){handleHttpResponse8(http, item_index, lookup_index)};
        http.send(null);
    }
}

function handleHttpResponse8(http, item_index, lookup_index){
    if (http.readyState == 4) {
        if (http.status === 200) {
            var xmlDoc = http.responseXML;
            var nb_results = xmlDoc.getElementsByTagName('numberOfRecords');
            if (nb_results.length > 0 && parseInt(nb_results[0].textContent)>0) {
                var datafields = xmlDoc.getElementsByTagName('datafield');
                var docType = "book";
                var title = "";
                var article = "";
                var publisher = "";
                var edition = "";
                var authors = "";
                var journal = "";
                var year = "";
                var vol = "";
                var no = "";
                var pages = "";
                var issn = "";
                for (i = 0; i <datafields.length; i++) {
                    var datafield = datafields[i];
                    var tag = datafield.getAttribute("tag");
                    var ind1 = datafield.getAttribute("ind1");
                    var ind2 = datafield.getAttribute("ind2");
                    switch(tag){
                        case '020':
                            issn = get_node_name_value(datafield, "subfield", issn, false, 'code', 'a');
                            break;
                        case '245':
                            title = get_node_name_value(datafield, "subfield", title, false, 'code', 'a');
                            title2 = get_node_name_value(datafield, "subfield", "", false, 'code', 'b');
                            if (title != "" && title2 != "") {title += " ";}
                            title += title2;
                            title3 = get_node_name_value(datafield, "subfield", "", false, 'code', 'c');
                            if (title != "" && title3 != "") {title += " ";}
                            title += title3;
                            break;
                        case '100':
                        case '700':
                            if (authors != "") {authors += ", ";}
                            authors += get_node_name_value(datafield, "subfield", "", false, 'code', 'a');
                            break;
                        case '260':
                        case '264':
                            year = get_node_name_value(datafield, "subfield", year, false, 'code', 'c').replace(".", "");
                            publisher = get_node_name_value(datafield, "subfield", publisher, false, 'code', 'b');
                            break;
                        case '250':
                            edition = get_node_name_value(datafield, "subfield", edition, false, 'code', 'a');
                            break;
                        case '580':
                            journal = get_node_name_value(datafield, "subfield", journal, false, 'code', 'a');
                            article = title;
                            title = journal;
                            docType = "article";
                            break;
                    }
                }
                if (edition && publisher) {
                    edition += " - ";
                }
                if (year == "") {
                    var controlfield_008 = xmlDoc.querySelectorAll("controlfield[tag='008']");
                    if (controlfield_008.length > 0) {
                        var controlfield_008_node = controlfield_008[0];
                        var controlfield_008_text = controlfield_008_node.textContent;
                        var year_from_controlfield = controlfield_008_text.substring(7, 11);
                        if (!isNaN(year_from_controlfield)) {
                            year = year_from_controlfield;
                        }
                    }
                }
               
                var controlfield_001 = xmlDoc.querySelectorAll("controlfield[tag='001']");
                var mms_identifier = controlfield_001[0].textContent;
                if (lookup_index == 'sru-marcxml-mms') {
                    uid_prefix = 'MMS';
                } else {
                    uid_prefix = 'ISBN';
                }
                document.commande["genre_"+item_index].value = docType;
                document.commande["title_"+item_index].value = title;
                document.commande["atitle_"+item_index].value = article;
                document.commande["auteurs_"+item_index].value = authors;
                document.commande["date_"+item_index].value = year;
                document.commande["edition_"+item_index].value = edition + publisher;
                document.commande["issn_"+item_index].value = issn;
                document.commande["uid_"+item_index].value = uid_prefix+":" + document.commande["uids_"+item_index].value.trim();
                resolve(item_index, 1);
            } else {
                alert("Identifier not found, please check your reference");
            }
        } else {;
            alert("Could not perform auto-fill: remote service has not replied");
        }
    }
}
//
// END SRU retrieval (marcxml format)
//
//
// ********************************************************************************************************
//
// START OTHER FUNCTIONS
//


function directory(urlpass) {
    if  ((document.commande.nom.value != "") || (document.commande.prenom.value != "")) {
        monurl = urlpass.replace('XNAMEX',document.commande.nom.value);
        monurl = monurl.replace('XFIRSTNAMEX',document.commande.prenom.value);
        window.open(monurl); 
    }
    else
        alert("Rentrez un nom d'abord")
}



function openlist(urlbase, item_index) {
	if (urlbase === undefined || urlbase == "") {
		urlbase = "../openlist/search.php?search=simple&q=";
	}
    if  (document.commande["title_"+item_index].value != "") {
        var monurl = urlbase + encodeURIComponent(document.commande["title_"+item_index].value);
        window.open(monurl); }
    else
        alert("Rentrez un titre d'abord")
}


/*
   name - name of the cookie
   value - value of the cookie
   [expires] - expiration date of the cookie
     (defaults to end of current session)
   [path] - path for which the cookie is valid
     (defaults to path of calling document)
   [domain] - domain for which the cookie is valid
     (defaults to domain of calling document)
   [secure] - Boolean value indicating if the cookie transmission requires
     a secure transmission
   * an argument defaults when it is assigned null as a placeholder
   * a null placeholder is not required for trailing omitted arguments
*/

function setCookie(name, value, expires, path, domain, secure) {
  var curCookie = name + "=" + escape(value) +
      ((expires) ? "; expires=" + expires.toGMTString() : "") +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      ((secure) ? "; secure" : "");
  document.cookie = curCookie;
}


/*
  name - name of the desired cookie
  return string containing value of specified cookie or null
  if cookie does not exist
*/

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}


/*
   name - name of the cookie
   [path] - path of the cookie (must be same as path used to create cookie)
   [domain] - domain of the cookie (must be same as domain used to
     create cookie)
   path and domain default if assigned null or omitted if no explicit
     argument proceeds
*/

function deleteCookie(name, path, domain) {
  if (getCookie(name)) {
    document.cookie = name + "=" +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}

// date - any instance of the Date object
// * hand all instances of the Date object to this function for "repairs"

function fixDate(date) {
  var base = new Date(0);
  var skew = base.getTime();
  if (skew > 0)
    date.setTime(date.getTime() - skew);
}

function coocout() {
    deleteCookie("nom");
    deleteCookie("prenom");
    deleteCookie("service");
    deleteCookie("servautre");
    deleteCookie("cgra");
    deleteCookie("cgrb");
    deleteCookie("mail");
    deleteCookie("tel");
    deleteCookie("adresse");
    deleteCookie("cp");
    deleteCookie("ville");
    deleteCookie("envoi");
    alert("LE COOKIE A ETE SUPPRIME");
}

function okcooc() {
    if (document.commande.cooc.checked == true){
        // create an instance of the Date object
        var now = new Date();
        // fixDate(now);

/*
cookie expires in one year (actually, 365 days)
365 days in a year
24 hours in a day
60 minutes in an hour
60 seconds in a minute
1000 milliseconds in a second
*/

        now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);

// set the new cookie
        var nom = document.commande.nom.value;
        var prenom = document.commande.prenom.value;
        var service = document.commande.service.value;
        var servautre = document.commande.servautre.value;
        var cgra = document.commande.cgra.value;
        var cgrb = document.commande.cgrb.value;
        var mail = document.commande.mail.value;
        var tel = document.commande.tel.value;
        var adresse = document.commande.adresse.value;
        var cp = document.commande.postal.value;
        var ville = document.commande.localite.value;
        var envois = document.commande.envoi;
        var envoi = "";
        var envoipos = 0;
        for(var i = 0; i < envois.length; i++){
            if(envois[i].checked){
                envoi = envois[i].value;
                envoipos = i;
            }
        }
        setCookie("nom", nom, now);
        setCookie("prenom", prenom, now);
        setCookie("service", service, now);
        setCookie("servautre", servautre, now);
        setCookie("cgra", cgra, now);
        setCookie("cgrb", cgrb, now);
        setCookie("mail", mail, now);
        setCookie("tel", tel, now);
        setCookie("adresse", adresse, now);
        setCookie("cp", cp, now);
        setCookie("ville", ville, now);
        setCookie("envoi", envoi, now);
        setCookie("envoipos", envoipos, now);
    }
}

function unescape_string(value) {
	/* Helper function to unescape XML/HTML encoded strings.
	   Useful since we are not parsing XML, but extracting the raw content.
	*/
    if (value == undefined) {
        return "";
    }
    var textarea = document.createElement("textarea");
    textarea.innerHTML = value;
    return textarea.value;
}

function remplirauto(openillink_config_email) {
    if (getCookie("nom") != null)
        document.commande.nom.value = getCookie("nom");
    if (getCookie("prenom") != null)
        document.commande.prenom.value = getCookie("prenom");
    if (getCookie("service") != null)
        document.commande.service.value = getCookie("service");
    if (getCookie("servautre") != null)
        document.commande.servautre.value = getCookie("servautre");
    if (getCookie("cgra") != null)
        document.commande.cgra.value = getCookie("cgra");
    if (getCookie("cgrb") != null)
        document.commande.cgrb.value = getCookie("cgrb");
    if (getCookie("mail") != null)
        document.commande.mail.value = getCookie("mail");
    if (getCookie("tel") != null)
        document.commande.tel.value = getCookie("tel");
    if (getCookie("adresse") != null)
        document.commande.adresse.value = getCookie("adresse");
    if (getCookie("cp") != null)
        document.commande.postal.value = getCookie("cp");
    if (getCookie("ville") != null)
        document.commande.localite.value = getCookie("ville");
    if (getCookie("envoi") != null)
        document.commande.envoi[getCookie("envoipos")].checked = true;

	// Retrieve parameters from URL
	var url_parameters = new QueryData();

	function get_url_parameter(key, defaultvalue, optional_prefix){
		/* Helper function to retrieve data from array 
		   'optional_prefix' allows to search for a key that might be prefixed
		*/
		   if(defaultvalue === undefined) {
			defaultvalue = "";
		}
		if (optional_prefix === undefined) {
			optional_prefix = "";
		}
		return (key in url_parameters) ? url_parameters[key] : (( optional_prefix + key in url_parameters) ? url_parameters[optional_prefix+key] : defaultvalue);
	}

    // Attribution des valeurs recuperes de la requête dans les champs du formulaire
    if (location.search) {
        var item_index = 0;
        document.commande["uid_"+item_index].value = get_url_parameter("id", "", "rft.");
        document.commande["title_"+item_index].value = get_url_parameter("title", "", "rft.");
        if (get_url_parameter("jtitle", "", "rft."))
            document.commande["title_"+item_index].value = get_url_parameter("jtitle", "", "rft.");
        if (get_url_parameter("btitle", "", "rft."))
            document.commande["title_"+item_index].value = get_url_parameter("btitle", "", "rft.");
        document.commande["atitle_"+item_index].value = get_url_parameter("atitle", "", "rft.");
        monauteur = get_url_parameter("aulast", "", "rft.");
        if (get_url_parameter("aufirst", "", "rft."))
            monauteur = monauteur + ", " + get_url_parameter("aufirst", "", "rft.");
		monauteur = get_url_parameter("rft.au", monauteur);
        document.commande["auteurs_"+item_index].value = monauteur;
		document.commande["edition_"+item_index].value = get_url_parameter("rft.edition");
        document.commande["date_"+item_index].value = get_url_parameter("date", "", "rft.");
        document.commande["volume_"+item_index].value = get_url_parameter("volume", "", "rft.");
        document.commande["issue_"+item_index].value = get_url_parameter("issue", "", "rft.");
        document.commande["pages_"+item_index].value = get_url_parameter("pages", "", "rft.");
        if (!get_url_parameter("pages", "", "rft.")) {
            if (get_url_parameter("spage", "", "rft."))
                document.commande["pages_"+item_index].value = get_url_parameter("spage", "", "rft.")
            if (get_url_parameter("epage", "", "rft."))
                document.commande["pages_"+item_index].value = document.commande["pages_"+item_index].value + '-' + get_url_parameter("epage", "", "rft.");
        }
        if (get_url_parameter("issn", "", "rft.")) {
            monissn = get_url_parameter("issn", "", "rft.");
            var i = monissn.indexOf('-');
            if (i < 0)
                monissn = monissn.substring(0,4) + '-' + monissn.substring(4, monissn.length);
            document.commande["issn_"+item_index].value = monissn;
        }
        if (get_url_parameter("isbn", "", "rft."))
            document.commande["issn_"+item_index].value = get_url_parameter("isbn", "", "rft.");
        if (get_url_parameter("pmid"))
            document.commande["uid_"+item_index].value = 'pmid:' + get_url_parameter("pmid");
        if (get_url_parameter("id"))
            document.commande["uid_"+item_index].value = get_url_parameter("id");
        else{
            if (get_url_parameter("meduid"))
                document.commande["uid_"+item_index].value = 'pmid:' + get_url_parameter("meduid");
            if (get_url_parameter("doi"))
                document.commande["uid_"+item_index].value = 'doi:' + get_url_parameter("doi");
            if (get_url_parameter("id", "", "rft_") && get_url_parameter("rfr_id").includes('webofscience')) {
                document.commande["uid_"+item_index].value = 'doi:' + get_url_parameter("id", "", "rft_").replace('info:doi/','');
                document.commande["uids_"+item_index].value = get_url_parameter("id", "", "rft_").replace('info:doi/','');
                document.commande["tid_"+item_index].value = "doi";
            }
        }
        if (get_url_parameter("genre", "", "rft."))
            document.commande["genre_"+item_index].value = get_url_parameter("genre", "", "rft.");
        if (get_url_parameter("remarques"))
            document.commande.remarques.value = get_url_parameter("remarques");
        if (get_url_parameter("remarquespub"))
            document.commande["remarquespub_"+item_index].value = get_url_parameter("remarquespub");
        if (get_url_parameter("pid"))
            document.commande.pid.value = get_url_parameter("pid");
        if (get_url_parameter("sid"))
            document.commande.sid.value = get_url_parameter("sid");
		if (get_url_parameter("sid") == "Entrez:PubMed" && get_url_parameter("id")) {
			// PubMed Linkout / Outside Tool: https://www.ncbi.nlm.nih.gov/books/NBK3803/
            document.commande["uids_"+item_index].value = get_url_parameter("id").substr(5);
			document.commande["tid_"+item_index].value = "pmid";
			lookupid(item_index, openillink_config_email);
		}
        resolve(item_index, 1);
    }
}


function QueryData(queryString, preserveDuplicates){
	/* Creates an object containing data parsed from the specified query string. The
	 * parameters are:
	 *
	 * queryString        - the query string to parse. The query string may start
	 *                      with a question mark, spaces may be encoded either as
	 *                      plus signs or the escape sequence '%20', and both
	 *                      ampersands and semicolons are permitted as separators.
	 *                      This optional parameter defaults to query string from
	 *                      the page URL.
	 * preserveDuplicates - true if duplicate values should be preserved by storing
	 *                      an array of values, and false if duplicates should
	 *                      overwrite earler occurrences. This optional parameter
	 *                      defaults to false.
	 *
	 * Initially created by Stephen Morley - http://code.stephenmorley.org/
	 *
	 */

  // if a query string wasn't specified, use the query string from the URL
  if (queryString == undefined){
    queryString = location.search ? location.search : '';
  }

  // remove the leading question mark from the query string if it is present
  if (queryString.charAt(0) == '?') queryString = queryString.substring(1);

  // check whether the query string is empty
  if (queryString.length > 0){

    // replace plus signs in the query string with spaces
    queryString = queryString.replace(/\+/g, ' ');

    // split the query string around ampersands and semicolons
    var queryComponents = queryString.split(/[&;]/g);

    // loop over the query string components
    for (var index = 0; index < queryComponents.length; index ++){

      // extract this component's key-value pair
      var keyValuePair = queryComponents[index].split('=');
	  var key = "";
	  try {
		key = decodeURIComponent(keyValuePair[0]);
	  } catch (err) {
		// key is not properly encoded. Nothing we can do, keep as such
		key = keyValuePair[0];
	  }
	  var value = "";
	  if (keyValuePair.length > 1) {
		try {
			value = decodeURIComponent(keyValuePair[1]);
		} catch (err) {
			// value is not properly encoded. Nothing we can do, keep as such
			value = keyValuePair[1];
	    }
	  }

      // check whether duplicates should be preserved
      if (preserveDuplicates){

        // create the value array if necessary and store the value
        if (!(key in this)) this[key] = [];
        this[key].push(value);

      }else{

        // store the value
        this[key] = value;

      }

    }

  }

}

function showAdvancedSearch() {
    /* Toogle visibility of elements for the advanced search */
    document.getElementById("advancedsearchlink").style.display="none";
    document.getElementById("simplesearchlink").style.display="";
    if (document.getElementsByName('champ')[0].value!="id") {
        document.getElementById("advancedsearchmatchfieldone").style.display="";
    }
    document.getElementById("advancedsearchcondition2").style.display="";
    document.getElementById("advancedsearchcondition3").style.display="";
    document.getElementById("searchtype").value="advanced";
}

function showSimpleSearch() {
    /* Toogle visibility of elements for the simple search */
    document.getElementById("advancedsearchlink").style.display="";
    document.getElementById("simplesearchlink").style.display="none";
    document.getElementById("advancedsearchmatchfieldone").style.display="none";
    document.getElementById("advancedsearchcondition2").style.display="none";
    document.getElementById("advancedsearchcondition3").style.display="none";
    document.getElementById("searchtype").value="simple";
    /* Reset form element */
    document.getElementsByName('term2')[0].value="";
    document.getElementsByName('term3')[0].value=""; 
    document.getElementsByName('match')[0].value="starts";
}