<?php

/**
 * Query parser for Dismax querying
 * 
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class EDismaxSolrSearchBuilder extends SolrQueryBuilder {
	public $title = 'Solr Extended Dismax';
	
	// @TODO Remove this, and use 'minimum match' instead. 
	protected $enableQueryPlus = true;
	
	public function toString() {
		return $this->parse($this->userQuery);
	}

	public function getParams() {
		parent::getParams();
		
		$fields = '';
		$sep = '';
		foreach ($this->fields as $field) {
			$fields .= $sep . $field;
			if (isset($this->boost[$field])) {
				$fields .= '^'.$this->boost[$field];
			}
			$sep = ' ';
		}

		$this->params['qf'] = $fields;
		$this->params['defType'] = 'edismax';
		
		$filterVals = isset($this->params['fq']) ? $this->params['fq'] : array();
		
		foreach ($this->and as $field => $valArray) {
			foreach ($valArray as $value) {
				$filterVals[] = $field. ':' .$value;
			}
		}

		$this->params['fq'] = $filterVals;
		
		$bq = array();
		foreach ($this->boostFieldValues as $field => $boost) {
			$bq[] = "$field^$boost";
		}
		if (count($bq)) {
			$this->params['bq'] = $bq;
		}
		
		if ($this->sort) {
			$this->params['sort'] = $this->sort;
		}
		
		$this->facetParams();
		
		return $this->params;
	}
	
	public function parse($string) {
		
		if ($string == '') return '*';

		$w = ($this->enableQueryWildcard) ? '*' : '';
		$p = ($this->enableQueryPlus) ? '+' : '';

		$searchString = '';
		preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $string, $words);

		foreach ($words[0] as $word) {
			//if quotes only add a +
			//if hyphenated wrap in quotes to prevent solr exploding the term
			//else add + wrap in *
			if (strpos($word, '"') !== FALSE) {
				$searchString .= " {$p}{$word} ";
			} elseif (preg_match('/[A-Za-z0-9_-]+[-\/]+[A-Za-z0-9_-]+/', $word)) {
				//catches: X-ray 12-pound SS-build/14 etc
				$searchString .= " {$p}\"{$word}\" ";
			} else {
				$searchString .= " {$p}{$w}{$word}{$w} ";
			}
		}
	
		return $searchString;
	}
	
	public function plus($string) {
		$words = explode(' ', $string);
		array_walk($words, function(&$word) {
			$word = '+' . $word;
		});
		
		return implode(' ', $words);
	}
}
