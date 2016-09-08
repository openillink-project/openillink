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

function cleanIllForm(){
    document.commande.atitle.value = '';
    document.commande.title.value = '';
    document.commande.auteurs.value = '';
    document.commande.date.value = '';
    document.commande.volume.value = '';
    document.commande.issue.value = '';
    document.commande.pages.value = '';
    document.commande.issn.value = '';
    document.commande.uid.value = '';
    document.commande.remarquespub.value = '';
}

function lookupid() {
    // si la valeur du champ uids est vide
    if (document.commande.uids.value == ""){
        // message d'alerte
        alert('entrez un identificateur avant');
    }
    if ((document.commande.uids.value != "") && (document.commande.tid.value == "pmid")){
        // alors on rempli automatiquement le formulaire, ceci ecrasse ce qu'y est inscrit dans le formulaire normal et l'envoie
        cleanIllForm();
        updateIllform();
    }
    if ((document.commande.uids.value != "") && (document.commande.tid.value == "reroid")){    
        cleanIllForm();
        updateIllform2();
    }
    if ((document.commande.uids.value != "") && (document.commande.tid.value == "isbn")){
        cleanIllForm();
        updateIllform3();
    }
    if ((document.commande.uids.value != "") && (document.commande.tid.value == "doi")){
        cleanIllForm();
        updateIllform4();
    }
    if ((document.commande.uids.value != "") && (document.commande.tid.value == "wosid")){
        cleanIllForm();
        updateIllform5();
    }
}

//
// ********************************************************************************************************
//

//
// START PMID
//

var url = 'lookup.php?pmid=';

function handleHttpResponse() {
    if (http.readyState == 4) {
        if ((http.responseText.indexOf('<!-- Error>XML not found for id') == -1) 
        && (http.responseText.indexOf('<ERROR>Empty id list') == -1) 
        && (http.responseText.indexOf('<ERROR>Invalid uid') == -1)){
            //alert(http.responseText);
            var xmlDocument = http.responseText;
            var atitled = xmlDocument.indexOf("<Item Name=\"Title\" Type=\"String\">");
            atitled = atitled+33;
            var atitlef = xmlDocument.indexOf("</Item>",atitled);
            var atitle = xmlDocument.substring(atitled,atitlef);
            var authorsd = xmlDocument.indexOf("<Item Name=\"Author\" Type=\"String\">");
            if (authorsd != -1) {
                authorsd = authorsd+34;
                var authorsf = xmlDocument.indexOf("</Item>",authorsd);
                var authors = xmlDocument.substring(authorsd,authorsf);
            }
            else {
                var authors = '';
            }
            var journald = xmlDocument.indexOf("<Item Name=\"FullJournalName\" Type=\"String\">");
            if (journald != -1){
                journald = journald+43;
                var journalf = xmlDocument.indexOf("</Item>",journald);
                var journal = xmlDocument.substring(journald,journalf);
            }
            else {
                journald = xmlDocument.indexOf("<Item Name=\"Source\" Type=\"String\">");
                journald = journald+34;
                var journalf = xmlDocument.indexOf("</Item>",journald);
                var journal = xmlDocument.substring(journald,journalf);
            }
            var anneed = xmlDocument.indexOf("<Item Name=\"PubDate\" Type=\"Date\">");
            anneed = anneed+33;
            var anneef = anneed + 4;
            var annee = xmlDocument.substring(anneed,anneef);
            var vold = xmlDocument.indexOf("<Item Name=\"Volume\" Type=\"String\">");
            if (vold != -1) {
                vold = vold+34;
                var volf = xmlDocument.indexOf("</Item>",vold);
                var vol = xmlDocument.substring(vold,volf);
            }
            else {
                var vol = '-';
            }
            var nod = xmlDocument.indexOf("<Item Name=\"Issue\" Type=\"String\">");
            if (nod != -1) {
                nod = nod+33;
                var nof = xmlDocument.indexOf("</Item>",nod);
                var no = xmlDocument.substring(nod,nof);
            }
            else {
                var vol = '-';
            }
            var pagesd = xmlDocument.indexOf("<Item Name=\"Pages\" Type=\"String\">");
            pagesd = pagesd+33;
            var pagesf = xmlDocument.indexOf("</Item>",pagesd);
            var pages = xmlDocument.substring(pagesd,pagesf);
            var issnd = xmlDocument.indexOf("<Item Name=\"ISSN\" Type=\"String\">");
            if (issnd != -1) {
                issnd = issnd+32;
                var issnf = xmlDocument.indexOf("</Item>",issnd);
                var issn = xmlDocument.substring(issnd,issnf);
            }
            else {
                var issn = '';
            }
            document.commande.atitle.value = atitle;
            document.commande.auteurs.value = authors;
            document.commande.title.value = journal;
            document.commande.date.value = annee;
            document.commande.volume.value = vol;
            document.commande.issue.value = no;
            document.commande.pages.value = pages;
            document.commande.issn.value = issn;
            document.commande.uid.value = "pmid:" + document.commande.uids.value;
            isWorking = false;
        }
        else {
            isWorking = false;
        }
    }
}

var isWorking = false;

function updateIllform() {
    if (!isWorking && http) {
        var pmidValue = document.commande.uids.value;
        http.open("GET", url + encodeURI(pmidValue), true);
        http.onreadystatechange = handleHttpResponse;
        isWorking = true;
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

var http = getHTTPObject();

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
function handleHttpResponse2() {
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
            document.commande.genre.value = docType;
            document.commande.title.value = titre;
            document.commande.atitle.value = article;
            document.commande.auteurs.value = authors;
            document.commande.date.value = annee;
            document.commande.edition.value = edition + editeur;
            document.commande.edition.value = edition + editeur;
            document.commande.issn.value = issn;
            document.commande.uid.value = "RERO:" + document.commande.uids.value;
            isWorking2 = false;
        }
        else {
            alert("Aucun resultat pour la recherche effectuée");
            isWorking2 = false;
        }
    }
}
var isWorking2 = false;

function updateIllform2() {
    if (!isWorking2 && http2) {
        var idrero = document.commande.uids.value;
        http2.open("GET", url2 + encodeURI(idrero), true);
        http2.onreadystatechange = handleHttpResponse2;
        isWorking2 = true;
        http2.send(null);
    }
}

function getHTTPObject2() {
    var xmlhttp2;
/*@cc_on
@if (@_jscript_version >= 5)
try {
xmlhttp2 = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
} catch (E) {
xmlhttp2 = false;
}
}
@else
xmlhttp2 = false;
@end @*/
    if (!xmlhttp2 && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp2 = new XMLHttpRequest();
        } catch (e) {
            xmlhttp2 = false;
        }
    }
    return xmlhttp2;
}

var http2 = getHTTPObject2();

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
function handleHttpResponse3() {
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
            document.commande.genre.value = "book";
            document.commande.title.value = titre;
            document.commande.auteurs.value = authors;
            document.commande.date.value = annee;
            document.commande.edition.value = edition + editeur;
            document.commande.issn.value = issn;
            document.commande.uid.value = "ISBN:" + document.commande.uids.value;
            isWorking3 = false;
        }
        else {
            isWorking3 = false;
        }
    }
}

var isWorking3 = false;

function updateIllform3() {
    if (!isWorking3 && http3) {
        var isbn = document.commande.uids.value;
        http3.open("GET", url3 + encodeURI(isbn), true);
        http3.onreadystatechange = handleHttpResponse3;
        isWorking3 = true;
        http3.send(null);
    }
}

function getHTTPObject3() {
   var xmlhttp3;
   /*@cc_on
   @if (@_jscript_version >= 5)
   try {
   xmlhttp3 = new ActiveXObject("Msxml3.XMLHTTP");
   } catch (e) {
   try {
   xmlhttp3 = new ActiveXObject("Microsoft.XMLHTTP");
   } catch (E) {
   xmlhttp3 = false;
   }
   }
   @else
   xmlhttp3 = false;
   @end @*/
   if (!xmlhttp3 && typeof XMLHttpRequest != 'undefined') {
       try {
           xmlhttp3 = new XMLHttpRequest();
       } catch (e) {
           xmlhttp3 = false;
       }
   }
   return xmlhttp3;
}

var http3 = getHTTPObject3();

//
// END ISBN
//
// ********************************************************************************************************
//
//
// START DOI
//
var url4 = 'lookup.php?doi=';
function handleHttpResponse4() {
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
                document.commande.genre.value = "book";
            }
            if (typedoc == "book_content") {
                document.commande.genre.value = "bookitem";
            }
            document.commande.atitle.value = atitle;
            document.commande.title.value = journal;
            document.commande.auteurs.value = authors;
            document.commande.date.value = annee;
            document.commande.volume.value = vol;
            document.commande.issue.value = no;
            document.commande.pages.value = pages;
            // document.commande.edition.value = typedoc;
            document.commande.issn.value = issn;
            document.commande.uid.value = "DOI:" + document.commande.uids.value;
            isWorking4 = false;
        }
        else {
            isWorking4 = false;
        }
    }
}


var isWorking4 = false;

function updateIllform4() {
    if (!isWorking4 && http4) {
        var doi = document.commande.uids.value;
        http4.open("GET", url4 + encodeURI(doi), true);
        http4.onreadystatechange = handleHttpResponse4;
        isWorking4 = true;
        http4.send(null);
    }
}

function getHTTPObject4() {
    var xmlhttp4;
/*@cc_on
@if (@_jscript_version >= 5)
try {
xmlhttp4 = new ActiveXObject("Msxml4.XMLHTTP");
} catch (e) {
try {
xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
} catch (E) {
xmlhttp4 = false;
}
}
@else
xmlhttp4 = false;
@end @*/
    if (!xmlhttp4 && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp4 = new XMLHttpRequest();
        } catch (e) {
            xmlhttp4 = false;
        }
    }
    return xmlhttp4;
}

var http4 = getHTTPObject4();

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

function handleHttpResponse5() {
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
                    doctype = result.return.records.doctype.value.toLowerCase();
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

    document.commande.genre.value = doctype;
    document.commande.atitle.value = atitle;
    document.commande.title.value = journal;
    document.commande.auteurs.value = authorsf;
    document.commande.date.value = annee;
    document.commande.volume.value = vol;
    document.commande.issue.value = no;
    document.commande.pages.value = pages;
    document.commande.issn.value = issn;
    document.commande.uid.value = "WOSUT:" + document.commande.uids.value;
    document.commande.remarquespub.value = notesn;
    isWorking5 = false;
    // entryForm.submit();
  }
  // Message d'erreur si le WOSID n'est pas valable
  else 
      if ( result && result.hasOwnProperty("return") && result.return.hasOwnProperty("recordsFound") && result.return.recordsFound == "0") {
          alert('WOS ID not found, please check your reference');
    isWorking5 = false;
  }
  else
  {
    alert("La recherche n'a pas abouti: le service distant n'a pas repondu");
    isWorking5 = false;
  }
  }
}


var isWorking5 = false;

function updateIllform5() {
  if (!isWorking5 && http5) {
    var wosid = document.commande.uids.value;
	console.log(url5 + encodeURI(wosid))
    http5.open("GET", url5 + encodeURI(wosid), true);
    http5.onreadystatechange = handleHttpResponse5;
    isWorking5 = true;
    http5.send(null);
  }
}

function getHTTPObject5() {
  var xmlhttp5;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp5 = new ActiveXObject("Msxml5.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp5 = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp5 = false;
      }
    }
  @else
  xmlhttp5 = false;
  @end @*/
  if (!xmlhttp5 && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp5 = new XMLHttpRequest();
    } catch (e) {
      xmlhttp5 = false;
    }
  }
  return xmlhttp5;
}

var http5 = getHTTPObject5();

//
// END Wos ID
//
//
// ********************************************************************************************************
//
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



function openlist( ) {
    if  (document.commande.title.value != "") {
        var monurl= "../openlist/search.php?search=simple&q=" + document.commande.title.value;
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

function remplirauto() {
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

	function get_url_parameter(key, defaultvalue = ""){
		/* Helper function to retrieve data from array */
		return (key in url_parameters) ? url_parameters[key] : defaultvalue;
	}

    // Attribution des valeurs recuperes de la requête dans les champs du formulaire
    if (location.search) {
        document.commande.uid.value = get_url_parameter("id");
        document.commande.title.value = get_url_parameter("title");
        if (get_url_parameter("jtitle"))
            document.commande.title.value = get_url_parameter("jtitle");
        if (get_url_parameter("btitle"))
            document.commande.title.value = get_url_parameter("btitle");
        document.commande.atitle.value = get_url_parameter("atitle");
        monauteur = get_url_parameter("aulast");
        if (get_url_parameter("aufirst"))
            monauteur = monauteur + ", " + get_url_parameter("aufirst");
        document.commande.auteurs.value = monauteur;
        document.commande.date.value = get_url_parameter("date");
        document.commande.volume.value = get_url_parameter("volume");
        document.commande.issue.value = get_url_parameter("issue");
        document.commande.pages.value = get_url_parameter("pages");
        if (!get_url_parameter("pages")) {
            if (get_url_parameter("spage"))
                document.commande.pages.value = get_url_parameter("spage")
            if (get_url_parameter("epage"))
                document.commande.pages.value = document.commande.pages.value + '-' + get_url_parameter("epage");
        }
        if (get_url_parameter("issn")) {
            monissn = get_url_parameter("issn");
            var i = monissn.indexOf('-');
            if (i < 0)
                monissn = monissn.substring(0,4) + '-' + monissn.substring(4, monissn.length);
            document.commande.issn.value = monissn;
        }
        if (get_url_parameter("isbn"))
            document.commande.issn.value = get_url_parameter("isbn");
        if (get_url_parameter("pmid"))
            document.commande.uid.value = 'pmid:' + get_url_parameter("pmid");
        if (get_url_parameter("id"))
            document.commande.uid.value = get_url_parameter("id");
        else{
            if (get_url_parameter("meduid"))
                document.commande.uid.value = 'pmid:' + get_url_parameter("meduid");
            if (get_url_parameter("doi"))
                document.commande.uid.value = 'doi:' + get_url_parameter("doi");
        }
        if (get_url_parameter("genre"))
            document.commande.genre.value = get_url_parameter("genre");
        if (get_url_parameter("remarques"))
            document.commande.remarques.value = get_url_parameter("remarques");
        if (get_url_parameter("remarquespub"))
            document.commande.remarquespub.value = get_url_parameter("remarquespub");
        if (get_url_parameter("pid"))
            document.commande.pid.value = get_url_parameter("pid");
        if (get_url_parameter("sid"))
            document.commande.sid.value = get_url_parameter("sid");
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