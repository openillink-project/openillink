<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2007, 2008, 2009, 2010, 2011, 2012, 2013 UNIGE.
// Copyright (C) 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
// Original author(s): Jan Krause <pro@jankrause.net>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILLink is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILLink is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

/*********************************************************************************************/
/* FUNCTIONS */
/*********************************************************************************************/
/**
 * prepareValue add delimiters to the value and append a postfix corresponding to the separator
 */
function prepareValue($value, $delimiter, $separator){
  // only append a separator if defined
  if (empty($separator) or $separator=='')
    return $delimiter.$value.$delimiter;
  else
    return $delimiter.$value.$delimiter.$separator;
}

/**
 * prepareLine add all values sequentially into a single line 
 * $values : array of values to concatenate
 */
function prepareLine($values, $delimiter, $separator, $replacements){
  $line = '';
  $values = str_replace(array_keys($replacements), array_values($replacements), $values);
  if (! is_array($values))
    // values is actually a single value: the whole line consists of the value surrounded by delimiters
    $line = prepareValue($value, $delimiter, $separator);
  else{
    $line = $delimiter.implode($delimiter.$separator.$delimiter, $values).$delimiter;
  }
  return $line.PHP_EOL;
}

/*********************************************************************************************/
/* MAIN PROCESSING */
/*********************************************************************************************/
function do_report($datedu, $dateau, $type, $format, $stade, $monbib) {
	/* Output a report */
	
	// local constant variables
	$new_st = 0;
	$ordered_st = 1;
	$rec_invoice_st = 2;
	$paid_st = 3;
	$abbandon_st = 4;
	$validated_st = 5;
	$rejected_st = 6;
	$circulating_st = 7;
	$processing_st = 8;
	$renew_st = 9;
	$toValidate_st = 10;
	$suppressed_st = 11;

	// parse dates to an array
	$du = explode(".",$datedu);
	$au = explode(".",$dateau);

	// prepare date values to fit query format
	$datedu = date("Y-m-d",mktime(0, 0, 0, $du[1], $du[0], $du[2]));
	$dateau = date("Y-m-d",mktime(0, 0, 0, $au[1], $au[0], $au[2]));

	// CSV header
	$filename = "openillink_report_". date("Ymd");

	$charEncoding = 'UTF-8';
	if ($format=='csv') {
	  header('Content-Encoding: '.$charEncoding);
	  header("Content-type: application/csv; charset: ".$charEncoding);
	  $filename = $filename . ".csv";
	  $sep = ";";
	  $quote = "\"";
	  $esc = "\\"; 
	  $replacements = array('"' => '""');
	 }
	if ($format=='tab') {
	  header('Content-Encoding: '.$charEncoding);
	  header("Content-type: text/tab-separated-values; charset: ".$charEncoding);
	  $filename = $filename . ".tab.txt";
	  $sep = "\t";
	  $quote = "";
	  $esc = "";
	  $replacements = array();
	}

	// Stades à afficher - ne s'applique pas a l'option statistiques
	header("Content-Disposition: attachment; filename=$filename");

	// BOM header, as we output UTF-8
	echo chr(0xEF).chr(0xBB).chr(0xBF);

	$ligneTitre = (!empty($stade))? array("Rapport", "OpenILLink", $monbib, "du", $datedu, "au", $dateau, "Status: ", $stade) : array("Rapport", "OpenILLink", $monbib, "du", $datedu, "au", $dateau);
	echo prepareLine($ligneTitre, $quote, $sep, $replacements);
	echo PHP_EOL; // add empty line

	if ($type=='liste_tout') {
	  // NB : refinterbib[1,3] = organisation, service, numerus currens 
	  $req = "select orders.* ".
	  " from orders where".
	  " (envoye between ? and ?) or ( date between ? and ? )".
	  " order by date, envoye DESC";
	  $result2 = dbquery($req,array($datedu, $dateau, $datedu, $dateau), 'ssss') or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
	  $total_results = iimysqli_num_rows($result2);

	  for ($i=0 ; $i<$total_results ; $i++) { 
		$enreg = iimysqli_result_fetch_array($result2, MYSQLI_ASSOC);
		if ($i == 0)
			echo implode ($sep, array_keys($enreg)) . PHP_EOL;
		$values = str_replace(array_keys($replacements), array_values($replacements), $enreg);
		$iml = $quote.implode($quote.$sep.$quote, $values).$quote;
		$tr1 = str_replace("\n"," ",$iml);
		echo str_replace("\r"," ",$iml);
		echo PHP_EOL;
	  }
	}

	if ($type=='liste_service'){
	  // NB : refinterbib[1,3] = organaiation, service, numerus currens 
	  //$fields  = array('refinterbib1', 'refinterbib2', 'nom', 'prenom', 'mail', 'illinkid', 'date', 'prix', 'localisation', 'titre_periodique', 'annee', 'volume', 'numero', 'pages', 'titre_article', 'stade', 'uid', 'issn', 'eissn');
	  $fields  = array('refinterbib', 'nom', 'prenom', 'mail', 'illinkid', 'date', 'envoye', 'prix', 'localisation', 'type_doc', 'titre_periodique', 'annee', 'volume', 'numero', 'pages', 'titre_article', 'stade', 'uid', 'issn', 'eissn');
	  $tfields = implode($sep, $fields);
	  $cfields = implode(", ", $fields);
	  echo $tfields . PHP_EOL;
	  $req0 = "select " . $cfields; 
	  $req1 = " from orders where (stade like ?) and bibliotheque like ?";
	  $req2 = " and ( (envoye between ? and ?) or ( date between ? and ? ) ) ";
	  $req3 = " order by refinterbib, mail, illinkid";
	  $req = $req0 . $req1 . $req2 . $req3; 
	  //echo $req;
	  
	  $result2 = dbquery($req,array($rec_invoice_st, $monbib, $datedu, $dateau, $datedu, $dateau),'ssssss') or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
	  $total_results = iimysqli_num_rows($result2);
	  for ($i=0 ; $i<$total_results ; $i++){ 
		$enreg = iimysqli_result_fetch_array($result2);
		foreach ($fields as $field)
		  echo $quote.str_replace(array_keys($replacements), array_values($replacements), $enreg[$field]).$quote.$sep;
		 echo "\n";
	  }
	}

	if ($type=='resume_service'){
		$fields  = array('organisation', 'service', 'cgra', 'nombre', 'prix_total');
		$tfields = implode($sep, $fields);
		echo $tfields . PHP_EOL;

		// NB : refinterbib[1,3] = organaiation, service, numerus currens 
		$req = "select refinterbib as organisation,service, cgra, count(*) as nombre, sum(prix) as prix_total from orders where stade like ? and bibliotheque like ?"
		." and bibliotheque like ?"
		." and ( (envoye between ? and ?) or ( date between ? and ? ) ) "
		." group by service, cgra";
		$params = array($rec_invoice_st, $monbib, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'sssssss';
		$result2 = dbquery($req, $params, $typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$total_results = iimysqli_num_rows($result2);
		for ($i=0 ; $i<$total_results ; $i++) { 
			$enreg = iimysqli_result_fetch_array($result2);
			foreach ($fields as $field)
			  echo $quote.str_replace(array_keys($replacements), array_values($replacements), $enreg[$field]).$quote.$sep;
			echo "\n";
		}
	}


	//and ((envoye between \"$datedu\" and \"$dateau\") or (date between \"$datedu\" and \"$dateau\"))

	if ($type=='stats'){
		// totals
		$req = "select count(*) as resu ".
		"from orders ".
		"where stade in (?,?,?,?,?,?,?,?,?,?,?,?) ".
		"and bibliotheque like ? ".
		"and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($new_st, $ordered_st, $rec_invoice_st, $paid_st, $abbandon_st, $rejected_st, $renew_st,$validated_st, $circulating_st, $processing_st, $toValidate_st, $suppressed_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'sssssssssssssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$totals = (int)$resu;

		// en traitement
		$req = "select count(*) as resu ".
		"from orders ".
		"where (stade in (?,?,?,?,?,?,?)) ".
		"and bibliotheque like ? ".
		"and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($new_st,$ordered_st,$renew_st,$processing_st,$toValidate_st,$validated_st,$circulating_st, $monbib,$datedu,$dateau,$datedu,$dateau);
		$paramtype = 'ssssssssssss';
		$res = dbquery($req,$params,$paramtype) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$recues = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($recues/$totals)*100;
		$formatDirective = "%d";
		echo $quote. "Commandes en traitment (local et ILL):" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL ;

		// envoyées
		$req = "select count(*) as resu from orders where (stade = ?) and bibliotheque like ? and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($rec_invoice_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssss';
		$res = dbquery($req, $params, $typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$recues = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($recues/$totals)*100;
		$formatDirective = "%d";     
		echo $quote. "Commandes envoyées pas facturées:" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL ;

		// soldees
		$req = "select count(*) as resu ".
		"from orders where (stade = ?) and bibliotheque like ? ".
		"and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($paid_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$satis = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($satis/$totals)*100;
		$formatDirective = "%d";
		echo $quote."Commandes envoyées et facturés (soldées):" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL ;

		// abandonnees
		$req = "select count(*) as resu from orders where (stade = ?) and bibliotheque like ? and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($abbandon_st,$monbib,$datedu,$dateau,$datedu,$dateau);
		$typeparams = 'ssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$refus = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($refus/$totals)*100;
		$formatDirective = "%d";
		echo $quote. "Commandes abandonnées:" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL;

		// rejetees
		$req = "select count(*) as resu from orders where (stade = ?) and bibliotheque like ? and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($rejected_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$rejet = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($rejet/$totals)*100;
		$formatDirective = "%d";
		echo $quote."Commandes rejetées:" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL ;

		// supprimées
		$req = "select count(*) as resu from orders where (stade = ?) and bibliotheque like ? and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($suppressed_st, $monbib, $datedu,$dateau,$datedu,$dateau);
		$typeparams = 'ssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$rejet = (int)$resu;
		if ($recues==0)
		  $qu = "ND";
		else
		  $qu = ($rejet/$totals)*100;
		$formatDirective = "%d";
		echo $quote."Commandes supprimées:" .$quote. $sep .$quote. $resu .$quote. $sep .$quote. sprintf($formatDirective, $qu)."%" .$quote .PHP_EOL ;

		// TOTAL
		echo $quote."Commandes TOTAL:" .$quote. $sep .$quote. $totals .$quote. "\n". "\n" ;

		// etranger
		$req = "select count(*) as resu ".
		"from orders ".
		"where (stade = ?) ".
		"and bibliotheque like ? ".
		"and (localisation in (? , ? , ? , ?)) ".
		"and ((envoye between ? and ?) or (date between ? and ?))";
		$params = array($paid_st, $monbib, 'AutreEtranger', 'SUBITO', 'NLM', 'BritishLibrary', $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssssssss';
		$res = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$enreg = iimysqli_result_fetch_array($res);
		$resu = $enreg['resu'];
		$etranger = (int)$resu;
		if ($satis==0)
		  $qu = "ND";
		else
		  $qu = ($etranger/$satis)*100;
		$formatDirective = "%d";
		//echo $quote."% total à l'étranger:" .$quote. $sep.$quote.PHP_EOL;
		echo $quote."% total à l'étranger:" .$quote. $sep .$quote. sprintf($formatDirective, $qu). "%" .$quote .PHP_EOL.PHP_EOL;

		// par localisation
		echo $quote."Fractions par localisations".$quote."\n";

		$req = "select localisation, count(*) as cntr ".
		"from orders ".
		"where stade = ? ".
		"and bibliotheque like ? ". 
		/* MDV - correctif pour coherence avec la manière dans laquelle $recues est obtenu*/
		"and ((envoye between ? and ?) or (date between ? and ?)) ".
		"group by localisation";
		$params = array($rec_invoice_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssss';
		$result2 = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$total_results = iimysqli_num_rows($result2);
		echo $quote."Commandes envoyées pas facturées:".$quote."\n";
		for ($i=0 ; $i<$total_results ; $i++) { 
			$enreg = iimysqli_result_fetch_array($result2);
			if ($recues==0)
			  $qu = "ND";
			else {
			  $cntr = (int)$enreg["cntr"];
			  $qu = ($cntr/$recues)*100;
			}
			$formatDirective = "%d";
			//if ( ($enreg["localisation"] <> 'Autre') and ($enreg["localisation"] <> 'AutreEtranger') and ($enreg["localisation"] <> ''))
			if ($enreg["localisation"] == '')
			  echo $quote."<non attribué>".$quote.$sep.$quote.$cntr.$quote.$sep.$quote.$qu."%".$quote.PHP_EOL;
			else
			  echo $quote.$enreg["localisation"].$quote.$sep.$quote.$cntr.$quote.$sep.$quote.$qu."%".$quote.PHP_EOL;
		}
		echo PHP_EOL; // add empty line

		$req = "select localisation, count(*) as cntr ".
		"from orders ".
		"where stade = ? ".
		"and bibliotheque like ? ". 
		/* MDV - correctif pour coherence avec la manière dans laquelle $satis est obtenu*/
		"and ((envoye between ? and ?) or (date between ? and ?)) ".
		"group by localisation";
		$params = array($paid_st, $monbib, $datedu, $dateau, $datedu, $dateau);
		$typeparams = 'ssssss';
		$result2 = dbquery($req,$params,$typeparams) or die("Erreur exécution de la requête SQL. Contacter l'administrateur. ". mysqli_error()." ".$req);
		$total_results = iimysqli_num_rows($result2);
		echo $quote."Commandes facturées:".$quote."\n";
		for ($i=0 ; $i<$total_results ; $i++){ 
			$enreg = iimysqli_result_fetch_array($result2);
			if ($satis==0)
			  $qu = "ND";
			else {
			  $cntr = (int)$enreg["cntr"];
			  $qu = ($cntr/$satis)*100;
			}
			$formatDirective = "%d";
			//if ( ($enreg["localisation"] <> 'Autre') and ($enreg["localisation"] <> 'AutreEtranger') and ($enreg["localisation"] <> ''))
			if ($enreg["localisation"] == '')
			  echo $quote."<non attribué>".$quote.$sep.$quote.$cntr.$quote.$sep.$quote.$qu."%".$quote.PHP_EOL;
			else
			  echo $quote.$enreg["localisation"].$quote.$sep.$quote.$cntr.$quote.$sep.$quote.$qu."%".$quote.PHP_EOL;
		}
	}
}
 
?>