<?php
include('simple_html_dom.php');

class AmicaParser{
    private $arcadaUrls = array(
		'fi' => 'http://www.amica.fi/fi/arcada#Ruokalista',
		'se' => 'http://www.amica.fi/arcada#Meny',
		'en' => 'http://www.amica.fi/en/arcada#Menu',
	);
	private $infoArrOrig = array(
	    "se" => array(
	        "*" => "Må gott",
	        "M" => "Mjölkfri",
	        "G" => "Glutenfri",
	        "L" => "Laktosfri",
	        "VL" => "Laktosfattig"
	    ),
	    "fi" => array(
	        "*" => "Voi hyvin",
	        "M" => "Maidoton",
	        "G" => "Gluteeniton",
	        "L" => "Laktoositon",
	        "VL" => "Vähälaktoosinen"
	    ),
	    "en" => array(
	        "*" => "Feel well",
	        "M" => "Milk-free",
	        "G" => "Gluten-free",
	        "L" => "Lactose-free",
	        "VL" => "Low in lactose"
	    ),
	);

	private $languages = array('fi', 'se', 'en');

	public function getData($lang){
		
		if (in_array($lang, $this->languages))
			return $this->__parse($lang);
		
		$e = array();
		return $e;
	}


	function __parse($lang){
		$html =  str_get_html(file_get_contents($this->arcadaUrls[$lang]));
		$ruokaLista = trim($html->find('body .ContentArea', 2)->innertext);
		$ruokaLista = preg_replace(
				array(
					'/<br \/>/',
					'/&nbsp;/',
					'/<p>/',
					'/<\/p>/',
					'/(\d)([^\d,])/',
					'/(\d{1})£/i',
					'/(\()(['.join("|",array_keys($this->infoArrOrig['se'])).'|,| ]+)(\))/i',
                    '/([A-Za-z])(\d)/'
				),
				array(
					'|',
					'',
					'|',
					'|',
					'$1£$2',
					'$1)£',
					'{$2}',
                    '$1{}$2'
				), $ruokaLista);

		$arr = array();
		$day = "";
		$split = 1;
		foreach (preg_split("/\|/", $ruokaLista) as $i => $line) {
			if (trim($line) == "") {
				$split = 1;
			} else if ($split) {
				$day = $line;
				$split = 0;
			} else {
				if(!isset($arr[trim($day)])) {
					$arr[trim($day)] = "";
				}
				$arr[trim($day)] .= $line;
			}
		}
		array_pop($arr);
		$days = array_keys($arr);
		foreach($arr as $day => $line){
			$arr[$day] = array();
			$line = preg_split("/£/", $line, 0, PREG_SPLIT_NO_EMPTY);
				foreach($line as $i => $var){
					$line[$i] = array();
					foreach(preg_split('/\}/', trim($var), 0, PREG_SPLIT_NO_EMPTY) as $j => $part){
						if(preg_match('/\{/', $part)){
							
							$part = preg_split('/\{/', trim($part), 0, PREG_SPLIT_NO_EMPTY);
							$infoArr = "";
							if (isset($part[1]))
								$infoArr = $part[1];

							$line[$i]['parts'][] = array(
								'name' => html_entity_decode(trim($part[0])),
								'info' => $infoArr,
							);

						} else {
							$prices = preg_split('/(\d{1,2},\d{2})/', trim($part," ()"), 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
							$line[$i]['price']= array(
								'student'  => str_replace(',', '.', $prices[0]),
								'other'    => str_replace(',', '.', $prices[1]),
								'staff'    => str_replace(',', '.', $prices[2]),
							);
							
						}
					}
				}
				unset($arr[$day]);

				$arr[date("Y-n-j", strtotime('Last Monday +'.array_search($day, $days).'days'))] = $line;
		}
		return $arr;

	} // Parse



} // AmicaParser

