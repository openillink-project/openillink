<?php
/**
* PMIDS driver for RefLib
*
* Loads a list of comma-separated PMIDS
*/
class RefLib_Pmids{
	var $driverName = 'PMIDS';

	/**
	* The parent instance of the RefLib class
	* @var class
	*/
	var $parent;

	/**
	* Computes the default filename if given a $salt
	* @param string $salt The basic part of the filename to use
	* @return string The filename including extension to use as default
	*/
	function GetFilename($salt = 'PMIDS') {
		return "$salt.pmids";
	}

	function GetContents() {
		$pmids = array();
		foreach ($this->parent->refs as $ref) {
			if (isset($ref['accession-num']) && !empty($ref['accession-num'])) {
				$pmids[] = $ref['accession-num'];
			}
		}
		return implode(",", $pmids);
	}

	function SetContents($blob) {
		if (!preg_match_all('!([\d+,\s\n]+)!ms', $blob, $matches, PREG_SET_ORDER))
			return;
		$blob = preg_replace("/[\r\n]+/", ",", $blob);
		$blob = preg_replace("/[\s]/", "", $blob);
		$recno = 0;
		foreach (explode(",", $blob) as $pmid) {
			$recno++;
			$ref = array();
			$ref['accession-num'] = $pmid;

			// Append to $this->parent->refs {{{
			if (!$this->parent->refId) { // Use indexed array
				$this->parent->refs[] = $ref;
			} elseif (is_string($this->parent->refId)) { // Use assoc array
				if ($this->parent->refId == 'rec-number') {
					$this->parent->$refs[$recno] = $ref;
				} elseif (!isset($ref[$this->parent->refId])) {
					trigger_error("No ID found in reference to use as key");
				} else {
					$this->parent->refs[$ref[$this->parent->refId]] = $ref;
				}
			}
			// }}}
		}
	}
}
