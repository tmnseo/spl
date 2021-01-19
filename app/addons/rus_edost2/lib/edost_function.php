<?
/*********************************************************************************
���������������� ������� ������ eDost (��� ���������� ������ ���� �� ��������������)

��� ����������� � ����� 'edost_const.php' ������ ���� ����������� ���������:
define('EDOST_FUNCTION', 'Y');
*********************************************************************************/

class edost_function {

	// ���������� ����� �������� ��������
	public static function BeforeCalculate(&$order, &$config) {
/*
		$order - ������������ ��������� ������
		$config - ��������� ������

		return false; // ���������� ���������� �������
		return array('hide' => true); // ��������� ������ (�� ������������ ������ �� ������, �� ��������� ������)
		return array('data' => array( ������ �������� )); // �������� ������ � �������� ��������� �������� 'data' (������ ������ ��������������� ��������� eDost)
*/
//		echo '<br><b>BeforeCalculate - order:</b> <pre style="font-size: 12px">'.print_r($order, true).'</pre>';
/*
		// ������ ������� ������ � ���
		if (!empty($order['original'])) {
			$fp = fopen(dirname(__FILE__)."/edost.log", "a");
			fwrite($fp, date("Y.m.d H:i:s").' | '.getenv('REMOTE_ADDR').' | '.edost_class::implode2(array(", ", ' | '), $order['original']).' | '.$GLOBALS['APPLICATION']->GetCurPage()."\r\n");
			fclose($fp);
		}
*/

//		echo '<br>SERVER[REQUEST_URI]:'.$_SERVER['REQUEST_URI'];
//		$_SESSION['EDOST']['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
//		unset($_SESSION['EDOST']['compact_tariff']); // �������� ��������� �������� � ���������� �������
//		unset($_SESSION['EDOST']['delivery_default']); // �������� ��������� �������� � ���������
//		unset($_SESSION['EDOST']['office_default']); // �������� ��������� �� ����� ������ ������

/*
		// ������� ����������� ����� ��� ��������� �������������� (������ ��������� �������)
		$ar = array('������');
		if (in_array($order['location']['city'], $ar)) {
			return array(
				'sizetocm' => '1', // ����������� ��������� ��������� �������� � ����������
				'data' => array(
					9 => array( // ����� "���� ��������"
						'id' => 5,
						'price' => 400,
						'priceinfo' => 0,
						'pricecash' => 500,
						'transfer' => 0,
						'day' => '3-4 ���',
						'insurance' => 0,
						'company' => '���� ��������',
						'name' => '�������-��������',
						'format' => 'door',
						'company_id' => 1,
						'city' => '',
						'profile' => 9,
						'sort' => 4,
					)
				)
			);
		}
*/

/*
		// �������� �� � ������ �� ������� eDost (��������, ����� � �������� ��������� �������� � ������ �������, � ��������� �������� ����� �������� � ����������� �� ��������������� ����������)
		$config['id'] = '12345';
		$config['ps'] = 'aaaaa';
*/

		// ��������� ������ �� �������� ���������� ������
//		if (strpos($_SERVER['REQUEST_URI'], '/checkout') !== false) return array('hide' => true);

/*
		// ��������� ������ ��� ��������� ��������������
		$ar = array('������');
		if (in_array($order['location']['city'], $ar)) return array('hide' => true);
*/

		return false;

	}


	// ���������� ����� ��������� ���������� ������ � ����� �������� �� ������ eDost
	public static function BeforeCalculateRequest(&$order, &$config) {
/*
		$order - ���������������� ��������� ������
		$config - ��������� ������

		return false; // ���������� ���������� �������
		return array('hide' => true); // ��������� ������ (�� ������������ ������ �� ������, �� ��������� ������)
		return array('data' => array( ������ �������� )); // �������� ������ � �������� ��������� �������� 'data' (������ ������ ��������������� ��������� eDost)

		������ ������������ �� ����������:
			$order['location'] - ������, ������, ����� � ������� ������
			$order['weight'] - ��� ������ � �������
			$order['price'] - ���� ������ � ������
			$order['size'] - ������ � ���������� ������ (������� ��������� ������ ��������� � ������������ � ������ �������� eDost)
				��������������: �� ������ �������� ������ ���� ������������� �� ����������� - ������: $order['size'] = array(30, 10, 20);  sort($order['size']);
*/

//		echo '<br><b>BeforeCalculateRequest - order:</b> <pre style="font-size: 12px">'.print_r($order, true).'</pre>';

//		$order['size'] = array(10, 20, 30);
//		$order['weight'] = 5;
//		$order['weight'] += 1;
//		$order['price'] = 5000;

/*
		// �������� ��� �� �������� ��� ��������� ��������������
		$ar = array('������');
		if (in_array($order['location']['city'], $ar)) $order['weight'] += 0.1;
*/

		return false;

	}


	// ���������� ����� ������� ��������
	public static function AfterCalculate($order, $config, &$result) {
/*
		$order - ���������������� ������ �������� � ����������� �������
		$config - ��������� ������
		$result - ��������� �������
*/
//		echo '<br><b>AfterCalculate - order:</b> <pre style="font-size: 12px">'.print_r($order, true).'</pre>';
//		echo '<br><b>AfterCalculate - result:</b> <pre style="font-size: 12px">'.print_r($result, true).'</pre>';

/*
		if (empty($result['cache'])) {
			// ������ ������ (������ �������� � ������� eDost)
		}
		else {
			// ��������� ������ (������ ���� ��������� �� ���� ��������)
		}
*/

/*
		// �������� ������� �����, ���� ���� ����� ������ ������
		if (!empty($result['data'])) {
			$n = 0;
			foreach ($result['data'] as $k => $v) if ($v['format'] == 'post') $n++;
			if (count($result['data']) != $n) foreach ($result['data'] as $k => $v) if ($v['format'] == 'post') unset($result['data'][$k]);
		}
*/

/*
		// �������� ������� ����, ���� ���� ������ boxberry
		$company_id_delete = array(5); // id �������� ������� ���������� ������� (����)
		$company_id_exists = array(30); // id �������� ������� ������ �������� (boxberry)
		if (!empty($result['data'])) {
			$ar = array(array('office'), array('door', 'house')); // ������ �������� �� �������� �������� ��������
			foreach ($ar as $f) {
				$a = false;
				foreach ($result['data'] as $k => $v) if (in_array($v['company_id'], $company_id_exists) && in_array($v['format'], $f)) { $a = true; break; }
				if ($a) foreach ($result['data'] as $k => $v) if (in_array($v['company_id'], $company_id_delete) && in_array($v['format'], $f)) unset($result['data'][$k]);
			}
		}
*/

/*
		// ������ �� �������� (������������� ���������, ���������� �������) � ����������� �� �������������� (�������, ������)
		$ar = array(
			array(
//				'region_code' => array(22), // �������� �������� ��� ������� ����� ����������� ������  -  array(22 => '��������� ����', 28 => '�������� �������', 29 => '������������� �������', 30 => '������������ �������', 31 => '������������ �������', 32 => '�������� �������', 33 => '������������ �������', 34 => '������������� �������', 35 => '����������� �������', 36 => '����������� �������', 79 => '��������� ��', 75 => '������������� ����', 37 => '���������� �������', 38 => '��������� �������', 7 => '���������-���������� ����������', 39 => '��������������� �������', 40 => '��������� �������', 41 => '���������� ����', 9 => '���������-���������� ����������', 42 => '����������� �������', 43 => '��������� �������', 44 => '����������� �������', 23 => '������������� ����', 24 => '������������ ����', 45 => '���������� �������', 46 => '������� �������', 47 => '������������� �������', 48 => '�������� �������', 49 => '����������� �������', 50 => '���������� �������', 51 => '���������� �������', 83 => '�������� ��', 52 => '������������� �������', 53 => '������������ �������', 54 => '������������� �������', 55 => '������ �������', 56 => '������������ �������', 57 => '��������� �������', 58 => '���������� �������', 59 => '�������� ����', 25 => '���������� ����', 60 => '��������� �������', 1 => '���������� ������', 4 => '���������� �����', 2 => '���������� ������������', 3 => '���������� �������', 5 => '���������� ��������', 6 => '���������� ���������', 8 => '���������� ��������', 10 => '���������� �������', 11 => '���������� ����', 12 => '���������� ����� ��', 13 => '���������� ��������', 14 => '���������� ���� (������)', 15 => '���������� �������� ������ - ������', 16 => '���������� ���������', 17 => '���������� ����', 19 => '���������� �������', 61 => '���������� �������', 62 => '��������� �������', 63 => '��������� �������', 64 => '����������� �������', 65 => '����������� �������', 66 => '������������ �������', 67 => '���������� �������', 26 => '�������������� ����', 68 => '���������� �������', 69 => '�������� �������', 70 => '������� �������', 71 => '�������� �������', 72 => '��������� �������', 18 => '���������� ����������', 73 => '����������� �������', 27 => '����������� ����', 86 => '�����-���������� ��', 74 => '����������� �������', 20 => '��������� ����������', 21 => '��������� ����������', 87 => '��������� ��', 89 => '�����-�������� ��', 76 => '����������� �������', 90 => '��������', 91 => '���������� ����', 77 => '������', 78 => '�����-���������', 92 => '�����������')
//				'country_code' => array(0), // �������� ����� ��� ������� ����� ����������� ������  -  array(0 => ������)
//				'tariff_id' => array(37), // id ������ � ������� eDost: http://edost.ru/kln/help.html#DeliveryCode  -  ���� �������, ����� ��������� 'company_id' � 'format' ������������
//				'company_id' => array(5), // id �������� ��������� eDost ��� ������� ����� ����������� ����� (5 - ����)  -  ���� ����� ������, ����� ������ ��������� ��� ���� �������� ��������
//				'format' => array('office'), // ������ �������� ��� �������� ����� ����������� ������ ('office' - ������ ������)  -  ���� ����� ������, ����� ������ ��������� ��� ���� �������� ��������
				'normal' => array( // ������ ��� ��������� ��������������/�������� (���� ��� ��������� �������������� ������ �� �����, ����� ������ ������ ���������� ������� - �������� ������ 'invert')
//					'price_from' => 0, // ��������� ������ �� ������� ��������� ������
					'price_to' => 5000, // ��������� ������ �� ������� ��������� ������
//					'discount_percent' => 20, // ������� ������
//					'discount_fix' => 0, // ������������� ������
//					'pricecash_discount_disable' => true, // �� ��������� ������ ��� �������� � ���������� ��������
					'change' => array( // ���� �������, �������� ������������ ��������� �������� ���� ��������� (����� ��������� ��� ��������, ��� ������ 'price', ��� ������ 'pricecash')
//						'price' => 100, // ��������� ��������
						'pricecash' => -1 // ��������� �������� ��� ���������� ������� ('-1' - ������� ���������, ����� ������������ ��� ���������� ������� � ������������ ��������)
					)
				),
//				'invert' => array( // ������ ��� ���� ��������� ��������������/�������� (���� ��� ��������� �������������� ������ �� �����, ����� ������ ������ ���������� ������� - �������� ������ 'normal')
//					'price_from' => 0, // ��������� ������ �� ������� ��������� ������
//					'price_to' => 0, // ��������� ������ �� ������� ��������� ������
//					'discount_percent' => 100, // ������� ������
//					'discount_fix' => 350, // ������������� ������
//					'pricecash_discount_disable' => true, // �� ��������� ������ ��� �������� � ���������� ��������
//					'change' => array( // ���� �������, �������� ������������ ��������� �������� ���� ��������� (����� ��������� ��� ��������, ��� ������ 'price', ��� ������ 'pricecash')
//						'price' => 250, // ��������� ��������
//						'pricecash' => -1 // ��������� �������� ��� ���������� ������� ('-1' - ������� ���������, ����� ������������ ��� ���������� ������� � ������������ ��������)
//					)
//				),
			),
			// ���������� ������� ��� ���� �����, ����� ������
			array(
				'country_code' => array(0), // ��� ������ ������ ���������, ������� ���� ������ ���� � ��������� ��������!
				'invert' => array(
					'change' => array(
						'pricecash' => -1 // ��������� �������� ��� ���������� ������� ('-1' - ������� ���������)
					)
				),
			),
			// ���������� �������� �� ������ �� 10000 ���.
		    array(
		        'country_code' => array(0), // ��� ������ ������ ���������, ������� ���� ������ ���� � ��������� ��������!
		        'normal' => array(
		            'price_from' => 10000, // ��������� ������ �� ������� ��������� ������
		            'change' => array(
		                'price' => 0, // ��������� ��������
		            )
		        ),
		    ),
//			array(... ��� ���� ������ � ������� ����������� (������ ����� ���� ������� ������)
		);
		if (!empty($result['data'])) {
			foreach ($ar as $param) {
				$a = (!empty($param['region_code']) && in_array($order['location']['region_code'], $param['region_code']) ||
					!empty($param['country_code']) && in_array($order['location']['country_code'], $param['country_code']) ? true : false);

				foreach ($result['data'] as $k => $v)
					if (!empty($param['tariff_id']) && in_array($v['id'], $param['tariff_id']) ||
						empty($param['tariff_id']) && (empty($param['company_id']) || !empty($param['company_id']) && in_array($v['company_id'], $param['company_id'])) && (empty($param['format']) || in_array($v['format'], $param['format']))) {

						$p = $s = $s2 = false;
						if ($a && isset($param['normal'])) $p = $param['normal'];
						if (!$a && isset($param['invert'])) $p = $param['invert'];
						if (empty($p) || isset($p['price_from']) && $order['PRICE'] <= $p['price_from'] || isset($p['price_to']) && $order['PRICE'] > $p['price_to']) continue;
						if (!empty($p['change'])) {
							if (isset($p['change']['price'])) $s = $p['change']['price'];
							if (isset($p['change']['pricecash'])) $s2 = $p['change']['pricecash'];
						}
						else {
							$s = $v['price']*(!empty($p['discount_percent']) ? (100 - $p['discount_percent'])/100 : 1) - (!empty($p['discount_fix']) ? $p['discount_fix'] : 0);
							if ($s < 0) $s = 0;
							if ($v['pricecash'] >= 0 && !isset($p['pricecash_discount_disable'])) {
								$s2 = $v['pricecash']*(!empty($p['discount_percent']) ? (100 - $p['discount_percent'])/100 : 1) - (!empty($p['discount_fix']) ? $p['discount_fix'] : 0);
								if ($s2 < 0) $s2 = 0;
							}
						}
						if ($s !== false) {
							if ($result['data'][$k]['price'] > $s) $result['data'][$k]['priceoriginal']['price'] = $result['data'][$k]['price'];
							$result['data'][$k]['price'] = $s;
						}
						if ($s2 !== false) {
							if ($result['data'][$k]['pricecash'] != -1 && $result['data'][$k]['pricecash'] > $s2) $result['data'][$k]['priceoriginal']['pricecash'] = $result['data'][$k]['pricecash'];
							$result['data'][$k]['pricecash'] = $s2;
						}
					}
			}
		}
*/

/*
		// ��������� ������� �������� EMS � ������� �� �����
		$company_id = array(2); // id �������� ������� ���������� �������� ������ �������� (EMS)
		$new_format = 'post'; // ����� ������ �������� (���� "�����")
		if (!empty($result['data'])) foreach ($result['data'] as $k => $v) if (in_array($v['company_id'], $company_id)) $result['data'][$k]['format'] = $new_format;
*/

/*
		// �������� '���' �� '������� ���'
		if (!empty($result['data'])) foreach ($result['data'] as $k => $v) if (!empty($v['day'])) {
			$result['data'][$k]['day'] = str_replace(array('����', '���', '����'), array('������� ����', '������� ���', '������� ����'), $v['day']);
		}
*/

/*
		// ���������� ��������� �������� �� ����� (��� ����� � EMS)
		$id = array(1, 2, 3, 61, 62, 68); // id ������� ��������� eDost
		if (!empty($result['data'])) foreach ($result['data'] as $k => $v)
			if (in_array($v['id'], $id) && $v['price'] > 0) {
				$result['data'][$k]['priceinfo'] = $v['price'];
				$result['data'][$k]['price'] = 0;
			}
*/

		// �������� �� ������� ������ "DPD (parcel �� ������ ������)" (��� 91)
//		if (isset($result['data']['91'])) unset($result['data']['91']);

/*
		// 50% ������ �� ����� "������ 1" (��� 61) ��� ������ � �������-����������� (��������������: ������������ ����� ������� - ��� ����� ���������� �� �������� ����� �������� � ����������)
		if (isset($result['data']['61'])) {
			$result['data']['61']['price_original'] = $result['data']['61']['price'];
			$p = $result['data']['61']['price_original'];
			if (date('N') >= 6) $p = round($p*0.5);
			$result['data']['61']['price'] = $p;
		}
*/

/*
		// ��������� ��������� �������� ������ "PickPoint"
		$id = 57; // PickPoint
		if (isset($result['data'][$id])) {
			// ��������� ������������� ��������� �������� ��� ��������� ��������������
			$ar = array('0000073738', '0000103664'); // CODE ��������������
			if (in_array($order['LOCATION_TO'], $ar)) {
				$result['data'][$id]['price'] = 250; // ��������� ��������
				$result['data'][$id]['pricecash'] = 250; // ��������� �������� ��� ���������� ������� (-1 - ��������� ���������� ������)
			}

			// ��������� ������������ ��������� ��� ������� ������ � ����� 5
			$result['data'][$id]['priceoffice'] = array(
				5 => array(
					'type' => 5,
					'price' => $result['data'][$id]['price'] + 100, // ����������� ���� �������� + 100 ���.
					'priceinfo' => 0,
					'pricecash' => 800, // �������
				),
			);
		}
*/
	}


	// ���������� ����� ��������� ������ �� ������� ������
	public static function BeforeGetOffice($order, &$company) {
/*
		$order - ��������� ������
		$company - ���� eDost �������� �������� ��� ������� ��������� ��������� ������
*/
//		echo '<br><b>BeforeGetOffice - order:</b> <pre style="font-size: 12px">'.print_r($order, true).'</pre>';
//		echo '<br><b>BeforeGetOffice - company:</b> <pre style="font-size: 12px">'.print_r($company, true).'</pre>';

	}


	// ���������� ����� �������� ������ �� ������� ������
	public static function AfterGetOffice($order, &$result) {
/*
		$order - ��������� ������
		$result - ������ ������
*/
//		echo '<br><b>AfterGetOffice - order:</b> <pre style="font-size: 12px">'.print_r($order, true).'</pre>';
//		echo '<br><b>AfterGetOffice - result:</b> <pre style="font-size: 12px">'.print_r($result, true).'</pre>';
//		echo '<br><b>AfterGetOffice - result:</b> <pre style="font-size: 12px">'.print_r(edost_class::$result, true).'</pre>';

/*
		// ���������� ������� ������ �� ��������
		if (!empty($result['data'])) foreach ($result['data'] as $k => $v) {
			$ar = array();
			foreach ($v as $v2) $ar[] = trim(str_replace(array('��.', '��������', '��������', '�������'), '', $v2['address']));
			array_multisort($ar, SORT_ASC, SORT_STRING, $v);
			$ar = array();
			foreach ($v as $v2) $ar[$v2['id']] = $v2;
			$result['data'][$k] = $ar;
		}
*/

/*
		// �������� ������� ������ �������� ��� � ������� ����� (��������� ������ ���������)
		if (!empty($result['data'])) foreach ($result['data'] as $k => $v) if (in_array($k, array(19, 21))) foreach ($v as $k2 => $v2) if ($v2['type'] != 2) {
			unset($result['data'][$k][$k2]);
		}
*/

/*
		// ���������� � ������ ����� ������ "���������..." (����������/������ ���������� ����������� � ������� "AfterGetOrderOffice" !!!)
		$id = 5; // ��� ������ �� ������� "����"
		if (!empty($result['data'][$id])) foreach ($result['data'][$id] as $k => $v) $result['data'][$id][$k]['detailed'] = 'N'; // ��� ����� �������� �� ���� ������: 'http://myshop.ru/delivery.html?id=%id%' (%id% ����� ������� id �����) - ���� � ������ �������� '&frame=Y', ����� �������� ����� ����������� � ������ (��� ����� �������)
*/

/*
		// ������� ������ ���� � ����� "��������� 1"
		$from = 5; // ��� "����"
		$to = 's1'; // ��� "��������� 1"
		if (!empty($result['data'][$from])) {
			$result['data'][$to] = $result['data'][$from];
			unset($result['data'][$from]);
			if (!empty($result['limit'])) foreach ($result['limit'] as $k => $v) if ($v['company_id'] == $from) $result['limit'][$k]['company_id'] = $to;
		}
*/

		// �������� ������ ������ ������ '��������� 1' (��� 's1')
//		if (isset($result['data']['s1'])) unset($result['data']['s1']);


		// ��������� ������������ ������ ������ ��� ������ '��������� 1' (��� 's1')
/*
		���������� ��� ��������� ��������� ������ ������ ������ (�������� ��� � ������� eDost):
		1. ��������� ���������� id (��������, 1000000, 1000001, 1000002, � �.�.)
		2. ������� � ���������� ���� �� ���������:
			'detailed' => 'http://myshop.ru/delivery.html?id=%id%', // ��������� ����������� ������ �� ��������� ���������� (���� � ������ �������� '&frame=Y', ����� �������� ����� ����������� � ������)
			���
			'detailed' => 'N', // ��������� ������ �� ��������� ����������
		3. ��� ������������� ����������� ������ � ��������� �����������, �� ����� ���������� ������������ � ������� "AfterGetOrderOffice"
*/
/*
		$result['data']['s1'] = array(
			'12345A12345' => array( // ���� � id ������ ���������
				'id' => '12345A12345', // id � ������ "A" � ��� ������������ ������������� ������ ������ ���������� � ������ �������� eDost, � �� ����� �������������� ����� ������� ��� �������� � ��������� ����������� �� ����� edost.ru (��� �����, ��������, ����� � �.�.).
				'code' => '',
				'name' => '�� �����',
				'address' => '������, ��. ��������� ������, �. 6, ����. 1',
				'address2' => '��. 5',
				'tel' => '+7-123-123-45-67',
				'schedule' => '� 10 �� 20, ��� ��������',
				'gps' => '37.592311,55.596037',
				'type' => 3,
				'metro' => '',
//				'detailed' => 'http://myshop.ru/delivery.html?id=%id%', // ��������� ����������� ������ �� ��������� ����������
//				'detailed' => 'N', // ��������� ������ �� ��������� ����������
			),
		);
*/

/*
		// ��������� ������ ������ ��� ������ '����'
		$result['data']['5'][100000] = array(
				'id' => '100000',
				'code' => '', // ���� ��� �� ������, ����� ��� ���������� ���������� � ������ ������ ������ � ������ � ������� ���� ����� �������� ����� 'S' (��� ������� ���������) ��� 'T' (��� ���� ��������� �������)
				'name' => '�� �����',
				'address' => '������, ��. ��������� ������, �. 6, ����. 1',
				'address2' => '��. 5',
				'tel' => '+7-123-123-45-67',
				'schedule' => '� 10 �� 20, ��� ��������',
				'gps' => '37.592311,55.596037',
				'type' => 3,
				'metro' => '',
//				'detailed' => 'http://myshop.ru/delivery.html?id=%id%', // ��������� ����������� ������ �� ��������� ����������
				'detailed' => 'N', // ��������� ������ �� ��������� ����������
		);
*/
	}


	// �������� IP �� ��������� � ��������
	public static function IPRange($ip, $from, $to) {
		$ip = sprintf('%u', ip2long($ip));
		$from = sprintf('%u', ip2long($from));
		$to = sprintf('%u', ip2long($to));
		return ($ip >= $from && $ip <= $to ? true : false);
	}

}
?>