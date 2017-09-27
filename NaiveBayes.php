<?php
class NaiveBayes{
	
	private $SAMPLES, $LABELS, $INPUT, $LABELS_SUM, $LEN_SAMPLES_SUB;
	
	public function __construct(){
		$this->SAMPLES = array();
		$this->LABELS = array();
		$this->LABELS_SUM = array();
	}
	
	public function train($SAMPLES, $LABELS){
		// All multidimension SAMPLES must have the same size
		$SIZE = -1;
		foreach($SAMPLES as $S){
			if(count($S) != $SIZE && $SIZE != -1){
				echo "Error : Uneven SAMPLES size <br>";
				return null;
			}
			$SIZE = count($S);
		}
		
		// SAMPLES and LABELS must have the same size
		if(count($SAMPLES) != count($LABELS)){
			echo "Error : Uneven SAMPLES and LABELS size <br>";
			return null;
		}
		
		// LABELS should not be multidimension
		if (count($LABELS) != count($LABELS, COUNT_RECURSIVE)){
			echo "Error : LABELS should not be multidimensional <br>";
			return null;
		}
		
		$this->SAMPLES = $SAMPLES;
		$this->LABELS = $LABELS;
		
		// Sum of LABELS
		foreach($this->LABELS as $L){
			if(!array_key_exists($L, $this->LABELS_SUM)){
				$this->LABELS_SUM[$L] = 0;
			}
			$this->LABELS_SUM[$L]++;
		}
		
		$SAMPLESAMPLES_PROB = array();
		$LEN_SAMPLES = count($this->SAMPLES);
		// If SAMPLES are multidimension
		if (count($this->SAMPLES) != count($this->SAMPLES, COUNT_RECURSIVE)){
			// Calculate the probabilities of SAMPLES
			$this->LEN_SAMPLES_SUB = count($this->SAMPLES[0]);
			for($COL=0; $COL<$this->LEN_SAMPLES_SUB; $COL++){
				for($ROL=0; $ROL<$LEN_SAMPLES; $ROL++){
					$SAMPLES_PROB = $COL . "_" . $this->SAMPLES[$ROL][$COL];
					if(!isset($this->$SAMPLES_PROB)){
						$this->$SAMPLES_PROB = array_fill_keys(array_keys($this->LABELS_SUM), 0);
						array_push($SAMPLESAMPLES_PROB, $SAMPLES_PROB);
					}
					$this->$SAMPLES_PROB[$this->LABELS[$ROL]]++;
				}
			}
			
			foreach($SAMPLESAMPLES_PROB as $SAMPLES_PROB){
				echo $SAMPLES_PROB . "&emsp;";
				print_r($this->$SAMPLES_PROB);
				
				foreach($this->$SAMPLES_PROB as $KEY => $VALUE){
					$this->$SAMPLES_PROB[$KEY] /= $this->LABELS_SUM[$KEY];
				}
				
				print_r($this->$SAMPLES_PROB);
				echo "<br>";
			}
		}
		// If SAMPLES are single array
		else{
			$this->LEN_SAMPLES_SUB = count($this->SAMPLES);
			for($I=0; $I<$this->LEN_SAMPLES_SUB; $I++){
				$SAMPLES_PROB = $this->SAMPLES[$I];
				if(!isset($this->$SAMPLES_PROB)){
					$this->$SAMPLES_PROB = array_fill_keys(array_keys($this->LABELS_SUM), 0);
					array_push($SAMPLESAMPLES_PROB, $SAMPLES_PROB);
				}
				$this->$SAMPLES_PROB[$this->LABELS[$I]]++;
			}
			
			foreach($SAMPLESAMPLES_PROB as $SAMPLES_PROB){
				echo $SAMPLES_PROB . "&emsp;";
				print_r($this->$SAMPLES_PROB);
				
				foreach($this->$SAMPLES_PROB as $KEY => $VALUE){
					$this->$SAMPLES_PROB[$KEY] /= $this->LABELS_SUM[$KEY];
				}
				
				print_r($this->$SAMPLES_PROB);
				echo "<br>";
			}
		}
	}
	
	public function predict($INPUT){
		// INPUT should not be multidimension
		if (count($INPUT) != count($INPUT, COUNT_RECURSIVE)){
			echo "Error : INPUT should not be multidimensional <br>";
			return null;
		}
		
		// SAMPLES and INPUT must have the same size
		if (count($this->SAMPLES) != count($this->SAMPLES, COUNT_RECURSIVE)){
			if($this->LEN_SAMPLES_SUB != count($INPUT)){
				echo "Error : Uneven SAMPLES and INPUT size <br>";
				return null;
			}
		}
		else{
			if(count($INPUT) != 1){
				echo "Error : Invalid INPUT size <br>";
				return null;
			}
		}
		
		$this->INPUT = $INPUT;
		$LEN_INPUT = count($this->INPUT);
		$PROB_LIST = array();
		foreach($this->LABELS_SUM as $KEY => $VALUE){
			$PROB = 1;
			for($I=0; $I<$LEN_INPUT; $I++){
				(count($this->SAMPLES) != count($this->SAMPLES, COUNT_RECURSIVE)) ?	$SAMPLES_PROB = $I . "_" . $this->INPUT[$I] : $SAMPLES_PROB = $this->INPUT[$I];
				if(isset($this->$SAMPLES_PROB)){
					$PROB *= $this->$SAMPLES_PROB[$KEY];
				}
			}
			$PROB *= $this->LABELS_SUM[$KEY] / array_sum($this->LABELS_SUM);
			$PROB_LIST[$KEY] = $PROB;
		}
		$HIGHEST_PROB = array_search(max($PROB_LIST), $PROB_LIST);
		print_r($PROB_LIST);
		return $HIGHEST_PROB;
	}
}
?>