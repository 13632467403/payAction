<?php


	function xml_create($version,$merchant,$terminal,$data,$sign){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><chinabank/>');
		$xml->addChild('version',$version);
		$xml->addChild('merchant',$merchant);
		$xml->addChild('terminal',$terminal);
		$xml->addChild('data',$data);
		$xml->addChild('sign',$sign);

		return $xml->asXML();
	}

	function v_data_xml_create($card_bank,$card_type,$card_no,
								$card_exp,$card_cvv2,$card_name,
								$card_idtype,$card_idno,$card_phone,
								$trade_type,$trade_id,$trade_amount,$trade_currency){
		$v_data = '<?xml version="1.0" encoding="UTF-8"?>'.
					'<DATA>'.
						'<CARD>'.
							'<BANK>'.$card_bank.'</BANK>'.
							'<TYPE>'.$card_type.'</TYPE>'.
							'<NO>'.$card_no.'</NO>'.
							'<EXP>'.$card_exp.'</EXP>'.
							'<CVV2>'.$card_cvv2.'</CVV2>'.
							'<NAME>'.$card_name.'</NAME>'.
							'<IDTYPE>'.$card_idtype.'</IDTYPE>'.
							'<IDNO>'.$card_idno.'</IDNO>'.
							'<PHONE>'.$card_phone.'</PHONE>'.
						'</CARD>'.
						'<TRADE>'.
							'<TYPE>'.$trade_type.'</TYPE>'.
							'<ID>'.$trade_id.'</ID>'.
							'<AMOUNT>'.$trade_amount.'</AMOUNT>'.
							'<CURRENCY>'.$trade_currency.'</CURRENCY>'.
						'</TRADE>'.
					'</DATA>';
		return $v_data;
	}
	function s_data_xml_create($card_bank,$card_type,$card_no,
								$card_exp,$card_cvv2,$card_name,
								$card_idtype,$card_idno,$card_phone,
								$trade_type,$trade_id,$trade_amount,$trade_currency,
								$trade_date,$trade_time,$trade_notice,$trade_note,$trade_code){
		$v_data = '<?xml version="1.0" encoding="UTF-8"?>'.
					'<DATA>'.
						'<CARD>'.
							'<BANK>'.$card_bank.'</BANK>'.
							'<TYPE>'.$card_type.'</TYPE>'.
							'<NO>'.$card_no.'</NO>'.
							'<EXP>'.$card_exp.'</EXP>'.
							'<CVV2>'.$card_cvv2.'</CVV2>'.
							'<NAME>'.$card_name.'</NAME>'.
							'<IDTYPE>'.$card_idtype.'</IDTYPE>'.
							'<IDNO>'.$card_idno.'</IDNO>'.
							'<PHONE>'.$card_phone.'</PHONE>'.
						'</CARD>'.
						'<TRADE>'.
							'<TYPE>'.$trade_type.'</TYPE>'.
							'<ID>'.$trade_id.'</ID>'.
							'<AMOUNT>'.$trade_amount.'</AMOUNT>'.
							'<CURRENCY>'.$trade_currency.'</CURRENCY>'.
							'<DATE>'.$trade_date.'</DATE>'.
							'<TIME>'.$trade_time.'</TIME>'.
							'<NOTICE>'.$trade_notice.'</NOTICE>'.
							'<NOTE>'.$trade_note.'</NOTE>'.
							'<CODE>'.$trade_code.'</CODE>'.
						'</TRADE>'.
					'</DATA>';
		return $v_data;
	}
	function r_data_xml_create($trade_type,$trade_id,$trade_oid,$trade_amount,
									$trade_currency,$trade_date,$trade_time,$trade_notice,$trade_note){
		$v_data = '<?xml version="1.0" encoding="UTF-8"?>'.
			'<DATA>'.
				'<TRADE>'.
					'<TYPE>'.$trade_type.'</TYPE>'.
					'<ID>'.$trade_id.'</ID>'.
					'<OID>'.$trade_oid.'</OID>'.
					'<AMOUNT>'.$trade_amount.'</AMOUNT>'.
					'<CURRENCY>'.$trade_currency.'</CURRENCY>'.
					'<DATE>'.$trade_date.'</DATE>'.
					'<TIME>'.$trade_time.'</TIME>'.
					'<NOTICE>'.$trade_notice.'</NOTICE>'.
					'<NOTE>'.$trade_note.'</NOTE>'.
				'</TRADE>'.
			'</DATA>';
		return $v_data;
	}
	function q_data_xml_create($trade_type,$trade_id){
		$v_data = '<?xml version="1.0" encoding="UTF-8"?>'.
			'<DATA>'.
				'<TRADE>'.
					'<TYPE>'.$trade_type.'</TYPE>'.
					'<ID>'.$trade_id.'</ID>'.
				'</TRADE>'.
			'</DATA>';
		return $v_data;
	}
?>
