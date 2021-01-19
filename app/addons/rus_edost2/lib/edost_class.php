<?php
/*********************************************************************************
����� ������������ eDost.ru
������ 2.5.1, 08.01.2020
�����: ��� "������"

�������� �������� � ��������� ������� �������� � ������ �������� eDost.ru (��������� �����������: http://edost.ru/reg.php)
*********************************************************************************/

include_once 'edost_const.php';
if (defined('EDOST_FUNCTION') && EDOST_FUNCTION == 'Y') include_once 'edost_function.php';

define('EDOST_SERVER', 'api.edost.ru'); // ������ ������� ��������
define('EDOST_SERVER_ZIP', 'edostzip.ru'); // ���������� ������
define('EDOST_SERVER_RESERVE', 'xn--d1ab2amf.xn--p1ai'); // �������������� ������ (�����.��)
define('EDOST_SERVER_RESERVE2', 'edost.net'); // �������������� ������

class edost_class {
	public static $message;
	public static $result = null;
	public static $config = array();

	public static $setting_key = array(
		'id' => '', 'ps' => '', 'host' => '', 'hide_error' => 'N', 'show_zero_tariff' => 'N',
		'map' => 'N', 'cod_status' => '', 'send_zip' => 'Y', 'hide_payment' => 'Y', 'sort_ascending' => 'N',
		'template' => 'N', 'template_format' => 'odt', 'template_block' => 'off', 'template_block_type' => 'none', 'template_cod' => 'td', 'template_autoselect_office' => 'N', 'autoselect' => 'Y',
		'admin' => 'Y', 'template_map_inside' => 'N',
		'control' => 'Y', 'control_auto' => 'Y', 'control_status_arrived' => '', 'control_status_completed' => 'F', 'control_status_completed_cod' => 'F',
		'browser' => 'ie', 'register_status' => '',
		'sale_discount' => 'N', 'sale_discount_cod' => 'off',
		'edost_discount' => 'Y', 'template_ico' => 'C', 'template_script' => 'Y',
		'package' => 'B',
	);
	public static $setting_param_key = array('zero_tariff' => 0, 'module_id' => 0, 'active' => '');

	public static $tariff_shop = array(35,56,57,58, 31,32,33,34);
	public static $zip_required = array(1,2,3,61,62,68,69,70,71,72,73,74,77,43);
	public static $passport_required = array(14,16,15,22,39,40,41,42,48,49,50,51,52,53,54,55,59,60,63,64); // ��
	public static $post_office = array(1,2,61,68,69,70,71,72,73,74); // �������� � �������� ��������� (��� ��������)
	public static $register_tariff = array(23 => array(1,2,3,61,62,77), 5 => array(37,75,9,7,65, 38,76,6,8,10,17,66)); // ������ ��� ������� �������� ���������� ��������: �����, ���� (��� ��������)
	public static $register_no_required = array(1,2); // ��� ������ ������� ��������� ���������� �������� (��� ��������)
	public static $office_key = array('shop', 'office', 'terminal');
	public static $postamat = array(5, 6, 10, 11, 12); // ������ ������ � ����� "��������"

	public static $country_iso3 = array(0 => 'RUS', 1 => 'AUS', 2 => 'AUT', 3 => 'AZE', 4 => 'ALB', 5 => 'DZA', 6 => 'ASM', 7 => 'AIA', 8 => 'XEN', 9 => 'AGO', 10 => 'AND', 11 => 'ATG', 12 => 'ANT', 13 => 'ARG', 14 => 'ARM', 15 => 'ABW', 16 => 'AFG', 17 => 'BHS', 18 => 'BGD', 19 => 'BRB', 20 => 'BHR', 21 => 'BLR', 22 => 'BLZ', 23 => 'BEL', 24 => 'BEN', 25 => 'BMU', 26 => 'BGR', 27 => 'BOL', 184 => 'BES', 29 => 'BIH', 30 => 'BWA', 31 => 'BRA', 32 => 'BRN', 33 => 'BFA', 34 => 'BDI', 35 => 'BTN', 36 => 'WLF', 37 => 'VUT', 38 => 'GBR', 39 => 'HUN', 40 => 'VEN', 41 => 'VGB', 42 => 'VIR', 43 => 'TLS', 44 => 'VNM', 45 => 'GAB', 46 => 'HTI', 47 => 'GUY', 48 => 'GMB', 49 => 'GHA', 50 => 'GLP', 51 => 'GTM', 52 => 'GIN', 53 => 'GNQ', 54 => 'GNB', 55 => 'DEU', 56 => 'GGY', 57 => 'GIB', 58 => 'HND', 59 => 'HKG', 60 => 'GRD', 61 => 'GRL', 62 => 'GRC', 63 => 'GEO', 64 => 'GUM', 65 => 'DNK', 66 => 'JEY', 67 => 'DJI', 68 => 'DMA', 69 => 'DOM', 70 => 'EGY', 71 => 'ZMB', 72 => 'CPV', 73 => 'ZWE', 74 => 'ISR', 75 => 'IND', 76 => 'IDN', 77 => 'JOR', 78 => 'IRQ', 79 => 'IRN', 80 => 'IRL', 81 => 'ISL', 82 => 'ESP', 83 => 'ITA', 84 => 'YEM', 85 => 'KAZ', 86 => 'CYM', 87 => 'KHM', 88 => 'CMR', 89 => 'CAN', 192 => '', 91 => 'QAT', 92 => 'KEN', 93 => 'CYP', 94 => 'KIR', 95 => 'CHN', 96 => 'COL', 97 => 'COM', 98 => 'COG', 99 => 'COD', 100 => 'PRK', 101 => 'KOR', 102 => 'XKX', 103 => 'CRI', 104 => 'CIV', 105 => 'CUB', 106 => 'KWT', 107 => 'COK', 108 => 'KGZ', 109 => 'CUW', 110 => 'LAO', 111 => 'LVA', 112 => 'LSO', 113 => 'LBR', 114 => 'LBN', 115 => 'LBY', 116 => 'LTU', 117 => 'LIE', 118 => 'LUX', 119 => 'MUS', 120 => 'MRT', 121 => 'MDG', 122 => 'MYT', 123 => 'MAC', 124 => 'MKD', 125 => 'MWI', 126 => 'MYS', 127 => 'MLI', 128 => 'MDV', 129 => 'MLT', 130 => 'MAR', 131 => 'MTQ', 132 => 'MHL', 133 => 'MEX', 134 => 'FSM', 135 => 'MOZ', 136 => 'MDA', 137 => 'MCO', 138 => 'MNG', 139 => 'MSR', 140 => 'MMR', 141 => 'NAM', 142 => 'NRU', 180 => 'KNA', 144 => 'NPL', 145 => 'NER', 146 => 'NGA', 147 => 'NLD', 148 => 'NIC', 149 => 'NIU', 150 => 'NZL', 151 => 'NCL', 152 => 'NOR', 153 => 'ARE', 154 => 'OMN', 155 => 'PAK', 156 => 'PLW', 157 => 'PAN', 158 => 'PNG', 159 => 'PRY', 160 => 'PER', 161 => 'POL', 162 => 'PRT', 163 => 'PRI', 164 => 'REU', 165 => 'RWA', 166 => 'ROU', 167 => 'MNP', 168 => 'SLV', 169 => 'WSM', 170 => 'SMR', 171 => 'STP', 172 => 'SAU', 173 => 'SWZ', 174 => 'XNI', 175 => 'SYC', 176 => 'BLM', 177 => 'SEN', 178 => 'VCT', 181 => 'LCA', 182 => 'AN', 183 => 'GP', 185 => 'SRB', 186 => 'SGP', 187 => 'SYR', 188 => 'SVK', 189 => 'SVN', 190 => 'SLB', 191 => 'SOM', 193 => 'SDN', 194 => 'SUR', 195 => 'USA', 196 => 'SLE', 197 => 'TJK', 198 => 'THA', 222 => 'PYF', 200 => 'TWN', 201 => 'TZA', 202 => 'TGO', 203 => 'TON', 204 => 'TTO', 205 => 'TUV', 206 => 'TUN', 207 => 'TKM', 208 => 'TCA', 209 => 'TUR', 210 => 'UGA', 211 => 'UZB', 212 => 'UKR', 213 => 'URY', 214 => 'XWA', 215 => 'FRO', 216 => 'FJI', 217 => 'PHL', 218 => 'FIN', 219 => 'FLK', 220 => 'FRA', 221 => 'GUF', 223 => 'HRV', 224 => 'CAF', 225 => 'TCD', 226 => 'MNE', 227 => 'CZE', 228 => 'CHL', 229 => 'CHE', 230 => 'SWE', 231 => 'XSC', 232 => 'LKA', 233 => 'ECU', 234 => 'ERI', 235 => 'EST', 236 => 'ETH', 237 => 'ZAF', 238 => 'JAM', 239 => 'JPN');
	public static $country_iso2 = array(0 => 'RU', 1 => "AU", 2 => "AT", 3 => "AZ", 4 => "AL", 5 => "DZ", 6 => "AS", 7 => "AI", 8 => "", 9 => "AO", 10 => "AD", 11 => "AG", 12 => "AN", 13 => "AR", 14 => "AM", 15 => "AW", 16 => "AF", 17 => "BS", 18 => "BD", 19 => "BB", 20 => "BH", 21 => "BY", 22 => "BZ", 23 => "BE", 24 => "BJ", 25 => "BM", 26 => "BG", 27 => "BO", 28 => "BQ", 29 => "BA", 30 => "BW", 31 => "BR", 32 => "BN", 33 => "BF", 34 => "BI", 35 => "BT", 36 => "WF", 37 => "VU", 38 => "GB", 39 => "HU", 40 => "VE", 41 => "VG", 42 => "VI", 43 => "TL", 44 => "VN", 45 => "GA", 46 => "HT", 47 => "GY", 48 => "GM", 49 => "GH", 50 => "GP", 51 => "GT", 52 => "GN", 53 => "GQ", 54 => "GW", 55 => "DE", 56 => "GG", 57 => "GI", 58 => "HN", 59 => "HK", 60 => "GD", 61 => "GL", 62 => "GR", 63 => "GE", 64 => "GU", 65 => "DK", 66 => "JE", 67 => "DJ", 68 => "DM", 69 => "DO", 70 => "EG", 71 => "ZM", 72 => "CV", 73 => "ZW", 74 => "IL", 75 => "IN", 76 => "ID", 77 => "JO", 78 => "IQ", 79 => "IR", 80 => "IE", 81 => "IS", 82 => "ES", 83 => "IT", 84 => "YE", 85 => "KZ", 86 => "KY", 87 => "KH", 88 => "CM", 89 => "CA", 90 => "IC", 91 => "QA", 92 => "KE", 93 => "CY", 94 => "KI", 95 => "CN", 96 => "CO", 97 => "KM", 98 => "CG", 99 => "CD", 100 => "KP", 101 => "KR", 102 => "XK", 103 => "CR", 104 => "CI", 105 => "CU", 106 => "KW", 107 => "CK", 108 => "KG", 109 => "CW", 110 => "LA", 111 => "LV", 112 => "LS", 113 => "LR", 114 => "LB", 115 => "LY", 116 => "LT", 117 => "LI", 118 => "LU", 119 => "MU", 120 => "MR", 121 => "MG", 122 => "YT", 123 => "MO", 124 => "MK", 125 => "MW", 126 => "MY", 127 => "ML", 128 => "MV", 129 => "MT", 130 => "MA", 131 => "MQ", 132 => "MH", 133 => "MX", 134 => "FM", 135 => "MZ", 136 => "MD", 137 => "MC", 138 => "MN", 139 => "MS", 140 => "MM", 141 => "NA", 142 => "NR", 143 => "KN", 144 => "NP", 145 => "NE", 146 => "NG", 147 => "NL", 148 => "NI", 149 => "NU", 150 => "NZ", 151 => "NC", 152 => "NO", 153 => "AE", 154 => "OM", 155 => "PK", 156 => "PW", 157 => "PA", 158 => "PG", 159 => "PY", 160 => "PE", 161 => "PL", 162 => "PT", 163 => "PR", 164 => "RE", 165 => "RW", 166 => "RO", 167 => "MP", 168 => "SV", 169 => "WS", 170 => "SM", 171 => "ST", 172 => "SA", 173 => "SZ", 174 => "", 175 => "SC", 176 => "BL", 177 => "SN", 178 => "VC", 179 => "KN", 180 => "KN", 181 => "LC", 182 => "", 183 => "", 184 => "BQ", 185 => "RS", 186 => "SG", 187 => "SY", 188 => "SK", 189 => "SI", 190 => "SB", 191 => "SO", 192 => "", 193 => "SD", 194 => "SR", 195 => "US", 196 => "SL", 197 => "TJ", 198 => "TH", 199 => "PF", 200 => "TW", 201 => "TZ", 202 => "TG", 203 => "TO", 204 => "TT", 205 => "TV", 206 => "TN", 207 => "TM", 208 => "TC", 209 => "TR", 210 => "UG", 211 => "UZ", 212 => "UA", 213 => "UY", 214 => "", 215 => "FO", 216 => "FJ", 217 => "PH", 218 => "FI", 219 => "FK", 220 => "FR", 221 => "GF", 222 => "PF", 223 => "HR", 224 => "CF", 225 => "TD", 226 => "ME", 227 => "CZ", 228 => "CL", 229 => "CH", 230 => "SE", 231 => "", 232 => "LK", 233 => "EC", 234 => "ER", 235 => "EE", 236 => "ET", 237 => "ZA", 238 => "JM", 239 => "JP");
	public static $country_code = array(0 => "������", 1 => "���������", 2 => "�������", 3 => "�����������", 4 => "�������", 5 => "�����", 6 => "������������ �����", 7 => "�������", 8 => "������", 9 => "������", 10 => "�������", 11 => "������� � �������", 12 => "���������� �������", 13 => "���������", 14 => "�������", 15 => "�����", 16 => "����������", 17 => "��������� �������", 18 => "���������", 19 => "��������", 20 => "�������", 21 => "��������", 22 => "�����", 23 => "�������", 24 => "�����", 25 => "���������� �������", 26 => "��������", 27 => "�������", 28 => "�������", 29 => "������ � �����������", 30 => "��������", 31 => "��������", 32 => "������", 33 => "������� ����", 34 => "�������", 35 => "�����", 36 => "������ � ������ �������", 37 => "�������", 38 => "��������������", 39 => "�������", 40 => "���������", 41 => "���������� ������� (����������)", 42 => "���������� ������� (���)", 43 => "��������� �����", 44 => "�������", 45 => "�����", 46 => "�����", 47 => "������", 48 => "������", 49 => "����", 50 => "���������", 51 => "���������", 52 => "������", 53 => "������ ��������������", 54 => "������-�����", 55 => "��������", 56 => "������ (����������� �������)", 57 => "���������", 58 => "��������", 59 => "�������", 60 => "�������", 61 => "����������", 62 => "������", 63 => "������", 64 => "����", 65 => "�����", 66 => "������ (����������� �������)", 67 => "�������", 68 => "��������", 69 => "������������� ����.", 70 => "������", 71 => "������", 72 => "�������� ���� ������� (����-�����)", 73 => "��������", 74 => "�������", 75 => "�����", 76 => "���������", 77 => "��������", 78 => "����", 79 => "����", 80 => "��������", 81 => "��������", 82 => "�������", 83 => "������", 84 => "�����", 85 => "���������", 86 => "��������� �������", 87 => "��������", 88 => "�������", 89 => "������", 90 => "��������� �������", 91 => "�����", 92 => "�����", 93 => "����", 94 => "��������", 95 => "��������� �������� ����������", 96 => "��������", 97 => "��������� �������", 98 => "�����", 99 => "�����, ��������������� ����.", 100 => "�����, ��������", 101 => "�����, �����", 102 => "������", 103 => "�����-����", 104 => "���-�'�����", 105 => "����", 106 => "������", 107 => "���� �������", 108 => "����������", 109 => "�������", 110 => "����", 111 => "������", 112 => "������", 113 => "�������", 114 => "�����", 115 => "�����", 116 => "�����", 117 => "�����������", 118 => "����������", 119 => "��������", 120 => "����������", 121 => "����������", 122 => "�������", 123 => "�����", 124 => "���������", 125 => "������", 126 => "��������", 127 => "����", 128 => "����������� �������", 129 => "������", 130 => "�������", 131 => "���������", 132 => "���������� �������", 133 => "�������", 134 => "����������", 135 => "��������", 136 => "�������", 137 => "������", 138 => "��������", 139 => "����������", 140 => "������", 141 => "�������", 142 => "�����", 143 => "�����", 144 => "�����", 145 => "�����", 146 => "�������", 147 => "���������� (���������)", 148 => "���������", 149 => "����", 150 => "����� ��������", 151 => "����� ���������", 152 => "��������", 153 => "������������ �������� �������", 154 => "����", 155 => "��������", 156 => "�����", 157 => "������", 158 => "�����-����� ������", 159 => "��������", 160 => "����", 161 => "������", 162 => "����������", 163 => "������-����", 164 => "�������", 165 => "������", 166 => "�������", 167 => "������", 168 => "���������", 169 => "�����", 170 => "���-������", 171 => "���-���� � ��������", 172 => "���������� ������", 173 => "���������", 174 => "�������� ��������", 175 => "����������� �������", 176 => "���-���������", 177 => "�������", 178 => "����-�������", 179 => "����-����", 180 => "����-���������", 181 => "����-�����", 182 => "����-�������", 183 => "����-������", 184 => "����-�����", 185 => "������", 186 => "��������", 187 => "�����", 188 => "��������", 189 => "��������", 190 => "���������� �������", 191 => "������", 192 => "����������", 193 => "�����", 194 => "�������", 195 => "���", 196 => "������-�����", 197 => "�����������", 198 => "�������", 199 => "�����", 200 => "�������", 201 => "��������", 202 => "����", 203 => "�����", 204 => "�������� � ������", 205 => "������", 206 => "�����", 207 => "������������", 208 => "����� � ������", 209 => "������", 210 => "������", 211 => "����������", 212 => "�������", 213 => "�������", 214 => "�����", 215 => "��������� �������", 216 => "�����", 217 => "���������", 218 => "���������", 219 => "������������ (�����������) �������", 220 => "�������", 221 => "����������� ������", 222 => "����������� ���������", 223 => "��������", 224 => "����������� ����������� ����.", 225 => "���", 226 => "����������", 227 => "�����", 228 => "����", 229 => "���������", 230 => "������", 231 => "���������", 232 => "���-�����", 233 => "�������", 234 => "�������", 235 => "�������", 236 => "�������", 237 => "���", 238 => "������", 239 => "������");
	public static $region_code = array(
		0 => array(22 => '��������� ����', 28 => '�������� �������', 29 => '������������� �������', 30 => '������������ �������', 31 => '������������ �������', 32 => '�������� �������', 33 => '������������ �������', 34 => '������������� �������', 35 => '����������� �������', 36 => '����������� �������', 79 => '��������� ��', 75 => '������������� ����', 37 => '���������� �������', 38 => '��������� �������', 7 => '���������-���������� ����������', 39 => '��������������� �������', 40 => '��������� �������', 41 => '���������� ����', 9 => '���������-���������� ����������', 42 => '����������� �������', 43 => '��������� �������', 44 => '����������� �������', 23 => '������������� ����', 24 => '������������ ����', 45 => '���������� �������', 46 => '������� �������', 47 => '������������� �������', 48 => '�������� �������', 49 => '����������� �������', 50 => '���������� �������', 51 => '���������� �������', 83 => '�������� ��', 52 => '������������� �������', 53 => '������������ �������', 54 => '������������� �������', 55 => '������ �������', 56 => '������������ �������', 57 => '��������� �������', 58 => '���������� �������', 59 => '�������� ����', 25 => '���������� ����', 60 => '��������� �������', 1 => '���������� ������', 4 => '���������� �����', 2 => '���������� ������������', 3 => '���������� �������', 5 => '���������� ��������', 6 => '���������� ���������', 8 => '���������� ��������', 10 => '���������� �������', 11 => '���������� ����', 12 => '���������� ����� ��', 13 => '���������� ��������', 14 => '���������� ���� (������)', 15 => '���������� �������� ������ - ������', 16 => '���������� ���������', 17 => '���������� ����', 19 => '���������� �������', 61 => '���������� �������', 62 => '��������� �������', 63 => '��������� �������', 64 => '����������� �������', 65 => '����������� �������', 66 => '������������ �������', 67 => '���������� �������', 26 => '�������������� ����', 68 => '���������� �������', 69 => '�������� �������', 70 => '������� �������', 71 => '�������� �������', 72 => '��������� �������', 18 => '���������� ����������', 73 => '����������� �������', 27 => '����������� ����', 86 => '�����-���������� ��', 74 => '����������� �������', 20 => '��������� ����������', 21 => '��������� ����������', 87 => '��������� ��', 89 => '�����-�������� ��', 76 => '����������� �������', 90 => '��������', 91 => '���������� ����', 77 => '������', 78 => '�����-���������', 92 => '�����������'),
		85 => array(1 => '����������� �������', 2 => '����������� �������', 3 => '����������� �������', 4 => '���������� �������', 5 => '��������-������������� �������', 6 => '���������� �������', 7 => '�������-������������� �������', 8 => '�������������� �������', 9 => '������������ �������', 10 => '�������������� �������', 11 => '������������� �������', 12 => '������������ �������', 13 => '������-������������� �������', 14 => '����-������������� �������', 15 => '������', 16 => '������'),
		21 => array(1 => '��������� �������', 2 => '��������� �������', 3 => '���������� �������', 4 => '����������� �������', 5 => '������� �������', 6 => '����������� �������', 7 => '�����'),
		14 => array(1 => '������������� �������', 2 => '���������� �������', 3 => '����������� �������', 4 => '������������� �������', 5 => '�������������� �������', 6 => '���������� �������', 7 => '��������� �������', 8 => '��������� �������', 9 => '��������� �������', 10 => '��������� �������', 11 => '������'),
		108 => array(1 => '���������� �������', 2 => '������-�������� �������', 3 => '�����-�������� �������', 4 => '��������� �������', 5 => '������ �������', 6 => '��������� �������', 7 => '������� �������', 8 => '������', 9 => '��'),
	);
	public static $region_iso = array(
		0 => array(22 => 'ALT', 28 => 'AMU', 29 => 'ARK', 30 => 'AST', 31 => 'BEL', 32 => 'BRY', 33 => 'VLA', 34 => 'VGG', 35 => 'VLG', 36 => 'VOR', 79 => 'YEV', 75 => 'ZAB', 37 => 'IVA', 38 => 'IRK', 7 => 'KB', 39 => 'KGD', 40 => 'KLU', 41 => 'KAM', 9 => 'KC', 42 => 'KEM', 43 => 'KIR', 44 => 'KOS', 23 => 'KDA', 24 => 'KYA', 45 => 'KGN', 46 => 'KRS', 47 => 'LEN', 48 => 'LIP', 49 => 'MAG', 50 => 'MOS', 51 => 'MUR', 83 => 'NEN', 52 => 'NIZ', 53 => 'NGR', 54 => 'NVS', 55 => 'OMS', 56 => 'ORE', 57 => 'ORL', 58 => 'PNZ', 59 => 'PER', 25 => 'PRI', 60 => 'PSK', 1 => 'AD', 4 => 'AL', 2 => 'BA', 3 => 'BU', 5 => 'DA', 6 => 'IN', 8 => 'KL', 10 => 'KR', 11 => 'KO', 12 => 'ME', 13 => 'MO', 14 => 'SA', 15 => 'SE', 16 => 'TA', 17 => 'TY', 19 => 'KK', 61 => 'ROS', 62 => 'RYA', 63 => 'SAM', 64 => 'SAR', 65 => 'SAK', 66 => 'SVE', 67 => 'SMO', 26 => 'STA', 68 => 'TAM', 69 => 'TVE', 70 => 'TOM', 71 => 'TUL', 72 => 'TYU', 18 => 'UD', 73 => 'ULY', 27 => 'KHA', 86 => 'KHM', 74 => 'CHE', 20 => 'CE', 21 => 'CU', 87 => 'CHU', 89 => 'YAN', 76 => 'YAR', 91 => 'CRI', 77 => 'MOW', 78 => 'SPE', 92 => 'SEV'),
		85 => array(1 => 'AKM', 2 => 'AKT', 3 => 'ALM', 4 => 'ATY', 5 => 'VOS', 6 => 'ZHA', 7 => 'ZAP', 8 => 'KAR', 9 => 'KUS', 10 => 'KZY', 11 => 'MAN', 12 => 'PAV', 13 => 'SEV', 14 => 'YUZ', 15 => 'AST', 16 => 'ALA'),
		21 => array(1 => 'BR', 2 => 'VI', 3 => 'HO', 4 => 'HR', 5 => 'MI', 6 => 'MA', 7 => 'HM'),
		14 => array(1 => 'AG', 2 => 'AR', 3 => 'AV', 4 => 'VD', 5 => 'GR', 6 => 'KT', 7 => 'LO', 8 => 'SU', 9 => 'TV', 10 => 'SH', 11 => 'ER'),
		108 => array(1 => 'B', 2 => 'J', 3 => 'Y', 4 => 'N', 5 => 'O', 6 => 'T', 7 => 'C', 8 => 'GB', 9 => 'GO'),
	);

	public static $fed_city = array(
		'id' => array(77, 78, 92,  15, 16,  7,  8, 9), // 11
		'name' => array('������', '�����-���������', '�����������',  '������', '������',  '�����',  '������', '��'), // '������'
		'region' => array(50, 47, 91,  1, 3,  5,  7, 5),
	);

	public static $no_region_city = array('���������', '������������', '�����������', '������ ��������', '���������� �����', '�����������', '������ �����', '���������', '����', '���', '���������', '������', '����������', '���������', '��������', '�����������', '�����', '������', '�������', '���������', '�����������', '��������', '�������', '���������', '�����������', '�����', '�����', '������-��-����', '������', '����', '��������', '����', '������', '���������', '���������', '����', '���������', '�����-���������', '������', '�������', '����������', '�����������', '����', '��������', '������', '������', '������������', '��������', '������', '�������', '�������', '��������', '�����', '����', '�����', '�������', '������', '��������', '����������', '������', '�����-�������', '����-���', '���������', '�����', '�������', '������', '��������', '������������', '���������', '�����������', '������-���', '�������', '������', '�����������', '�����', '������', '�������', '�������������-����������', '������', '�������', '������� ��������', '�����', '����-���������', '������', '������', '������-���', '�����-��������', '�������', '��������');
	public static $country_flag = array(0, 21, 85, 212, 14, 108);

	public static $error = false;
	public static $cod_paysystem = null;
	public static $control_key = array('id', 'flag', 'tariff', 'tracking_code', 'country', 'region', 'city', 'order_paid',  'zip', 'address_data', 'order_number', 'user_data', 'phone', 'email', 'comment', 'basket_data', 'package_data', 'batch_data');
	public static $delimiter = array(array(',', ':'), array(';', '/'));
	public static $delimiter2 = array('size' => array('x', array(0,0,0)), 'DIMENSIONS' => array('x', array(0,0,0)), 'service' => array('/', array()), 'doc' => array(';', array()));
	public static $data_key = array(
		'user' => array('company', 'name', 'name_first', 'name_middle', 'name_last', 'passport', 'companytype', 'account', 'secure', 'contract', 'appointment', 'represented', 'basis', 'vat', 'inn', 'format'),
		'address' => array('address', 'street', 'house_1', 'house_2', 'house_3', 'house_4', 'door_1', 'door_2', 'city2', 'id', 'code', 'city_id', 'phone', 'lunch', 'call', 'comment'),
		'basket' => array('id', 'product_id', 'name', 'quantity', 'price', 'vat', 'vat_rate', 'weight', 'size', 'set' => array('name'), 'info'),
		'basket2' => array('ID', 'PRODUCT_ID', 'NAME', 'QUANTITY', 'PRICE', 'VAT', 'VAT_RATE', 'WEIGHT', 'size', 'set' => array('NAME'), 'INFO_DATA'),
		'package' => array('weight', 'size', 'insurance', 'cod', 'item' => array('id', 'quantity'), 'service'),
		'package2' => array('shipment_id', 'weight', 'size', 'insurance', 'cod', 'item' => array('id', 'quantity'), 'service'),
		'option' => array('id', 'service' => array('id', 'value')),
		'batch_first' => array('date', 'number', 'type', 'call', 'profile_shop', 'profile_delivery'),
	);
	public static $depend = array('count', '62');
	public static $cod_key = array('pricecod', 'pricecod_formatted', 'pricecash', 'pricecash_formatted', 'transfer', 'transfer_formatted', 'cod_tariff', 'codplus', 'codplus_formatted', 'pricecashplus', 'pricecashplus_formatted', 'pricecod_original', 'pricecod_original_formatted', 'pricecash_original', 'pricecash_original_formatted', 'codplus_original', 'codplus_original_formatted', 'cod', 'compact_cod', 'compact_link_cod', 'compact_head_cod');


	// ��������� ������ �� ��������� �����
	public static function GetMessage($key) {
		return (isset(self::$message[$key]) ? self::$message[$key] : '');
	}

	// ������ ������ � �����������
	public static function ParseConfig($s, $name = 'main') {

		if ($name == 'main') $key = self::$setting_key;
		else $key = self::$setting_param_key;

		$r = array();
		$ar = explode(';', $s);
		$i = 0;
		foreach ($key as $k => $v) {
			$r[$k] = (isset($ar[$i]) ? $ar[$i] : $v);
			$i++;
		}
		return $r;

	}

	// �������� �������� ������ edost �� 'option' ��� �� ������ $data
	public static function GetConfig($site_id = '', $data = false, $search = false) {

		if ($search) {
			$r = false;
			if (isset($data[$site_id])) $r = $data[$site_id];
			else if (isset($data['all'])) $r = $data['all'];
			return $r;
		}

		$r = array();
		$first = false;

		if ($data !== false) $r['all'] = $data;
		else {
			$s = edost_config();
			$r = ($s != '' ? unserialize($s) : array(''));
//			self::draw_data('file config', $r);
		}

		foreach ($r as $k => $v) {
			$v = explode(';param=', $v);
			$r[$k] = self::ParseConfig($v[0]);
			$r[$k]['param'] = self::ParseConfig(!empty($v[1]) ? $v[1] : '', 'param');
			if ($first === false) $first = $r[$k];
		}

		if ($site_id !== 'all')
			if ($site_id === '') $r = $first;
			else if (isset($r['all'])) $r = $r['all'];
			else if (isset($r[$site_id])) $r = $r[$site_id];
			else $r = false;

		return $r;

	}

	// ��������� ������ ��������� eDost
	public static function GetCity($country, $region, $s, $win = true) {
        if (!empty($s)) {
        	$e = self::GetMessage('EDOST_EQUALLY');
			if (!empty($e['sign'])) $s = str_replace($e['sign'][0], $e['sign'][1], $s);
        	if (!empty($e['city'][$country])) foreach ($e['city'][$country] as $v) if (in_array($s, $v)) { $s = $v[0]; break; }
        	if ($win) $s = self::win_charset($s);
		}
		return $s;
	}

	// ��������� ���� �������������� ��������� eDost �� �������� ������ (��� ���� ������ eDost � �������� �������)
	public static function GetEdostLocationID($country, $region = '', $equally = false, $convert_charset = false) {

		if ($country === '' || $country === false || $country < 0) return false;
		if ($convert_charset) {
			if ($region === '') $country = self::win_charset($country);
			else $region = self::win_charset($region);
		}
		if ($region === '') {
			if ($equally === 'iso2') return array_search($country, self::$country_iso2);
			if ($equally === 'iso3') return array_search($country, self::$country_iso3);
			if (!empty($equally)) foreach ($equally as $k => $v) if (in_array($country, $v)) return $k;
			return array_search($country, self::$country_code);
		}
		if (!empty(self::$region_code[$country])) {
			if ($equally === 'iso') return array_search($region, self::$region_iso[$country]);
			if (!empty($equally[$country])) foreach ($equally[$country] as $k => $v) if (in_array($region, $v)) return $k;
			return array_search($region, self::$region_code[$country]);
		}
		return false;

	}

	// ��������� �������� ������
	public static function GetZeroTariff($config) {
		return (!empty($config['param']['zero_tariff']) ? edost_shop_delivery($config['param']['zero_tariff']) : false);
	}

	// ��������� ������ �� ���� ��������
	public static function GetTariff($profile, $office = false) {

		$type = $options = 0;
		if (isset($office['type'])) $type = $office['type'];
		if (isset($office['office_type'])) $type = $office['office_type'];
		if (isset($office['options'])) $options = $office['options'];
		if (isset($office['office_options'])) $options = $office['office_options'];

		$data = self::$result;
		if (isset($data['data'][$profile])) {
			$v = $data['data'][$profile];
			if (isset($v['priceoffice'][$type])) {
				$v['priceoffice_active'] = true;
				foreach ($v['priceoffice'][$type] as $k2 => $v2) if ($k2 != 'type') $v[$k2] = $v2;
			}

			if (self::CodDisable($options)) $v['pricecash'] = -1;

			return $v;
		}

		// ����� �� ������ - ����� ������
		return array(
			'error' => self::GetError(isset($data['error']) ? $data['error'] : 0),
			'price' => 0
		);

	}

	// ��������� ���� ������ ���� (��� ��������������)
	public static function GetPropRequired($profile, $prop) {

		$tariff = ceil(intval($profile) / 2);

		if ($prop == 'zip') return (in_array($tariff, self::$zip_required) ? 'Y' : '');
		if ($prop == 'metro') return (in_array($tariff, array(31, 32, 33, 34)) ? 'S' : '');
		if ($prop == 'passport') return (in_array($tariff, self::$passport_required) ? 'Y' : '');

		return '';

	}

	// ��������� ������ ������������ �� ����
	public static function GetError($id, $type = 'delivery', $exclamation = true) {

		if ($type == 'office_unchecked') {
			$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
			$r = $sign['office_unchecked'];
		}
		else {
			$error = self::GetMessage('EDOST_DELIVERY_ERROR');
			$r = $error['head'].($type == 'office' ? $error['office'].' ['.$id.'] - ' : '');

			if (isset($error[$id.'_'.$type])) $r .= $error[$id.'_'.$type];
			else if (isset($error[$id])) $r .= $error[$id];
			else $r .= $error['no_delivery'];
		}

		if ($exclamation && $r != '') $r .= '!';
		return $r;

	}

	// ��������� �������������� ������������
	public static function GetWarning($id = false, $head = true) {

		$r = '';
		if ($id === false) $data = self::$result;
		if ($id !== false || !empty($data['warning'])) {
			$warning = self::GetMessage('EDOST_DELIVERY_WARNING');
			if ($id !== false) {
				if (!empty($warning[$id])) $r .= $warning[$id];
			}
			else {
				foreach ($data['warning'] as $v) if (!empty($warning[$v])) $r .= $warning[$v].'<br>';
				if ($r != '') $r = ($head ? $warning[0].'<br>' : '').$r;
			}
		}
		return $r;

	}

	// ������ ��������� ������ �� ������
	public static function ParseActive($s) {

		$id = $s;
		$v = explode(':', $s);
		$r = array();
		if ($v[0] === 'edost') {
			$profile = $v[1];
			$s = explode('_', $profile);
			if (isset($s[1])) {
				$id = $s[1];
				$profile = $s[0];
			}
			else $id = 'edost:'.$profile;

			$r['profile'] = $profile;
			if (isset($v[2])) {
				if (!empty($v[2])) $r['office_id'] = $v[2];
				if (!empty($v[3])) $r['cod_tariff'] = ($v[3] === 'Y' ? true : false);
			}
		}
		$r['id'] = $id;
		return $r;

	}

	// ��������� ������� (��� �� ��������� + �������� ����, ��������� � ���������)
	public static function SetItem(&$items, $total = false) {

		if (empty($items)) return;

		$weight_default = (defined('EDOST_WEIGHT_DEFAULT') ? EDOST_WEIGHT_DEFAULT : 0);

		if (class_exists('edost_item') && method_exists('edost_item', 'set')) edost_item::set($items); // �������������� ��������� ������� � ������

		if ($total) {
			$total = array(
				'weight_zero' => false,
				'weight' => 0,
				'price' => 0,
				'package' => array(),
				'package2' => array(),
			);
		}

		foreach ($items as $k => $item) if (empty($item['hidden'])) {
			$weight = (isset($item['weight']) && $item['weight'] > 0 ? $item['weight'] : 0);
			$s = (isset($item['size']) ? $item['size'] : array(0, 0, 0));

			// ���� ������ ������ ��� �������, ����� ���������, ��� ��� ����� (����� � �������)
			if ($s[0] > 0 && $s[1] > 0 && $s[2] == 0) $s[2] = $s[1];
			if ($s[0] > 0 && $s[2] > 0 && $s[1] == 0) $s[1] = $s[2];

			if ($weight == 0) $weight = $weight_default;

			$item['weight'] = $weight;
			$item['size'] = $s;

			if ($weight == 0) $total['weight_zero'] = true;
			$weight = $weight * $item['quantity'];

			if (!empty($total)) {
				self::PackItem($total, $s, $item['quantity']);
				$total['weight'] += $weight;
				$total['price'] += self::GetPrice('value', $item['price'], isset($item['currency']) ? $item['currency'] : 'RUB') * $item['quantity'];
			}

			$items[$k] = $item;
		}

		if (!empty($total)) return $total;

	}

	// ������ ��������
	public static function Calculate($order) {
//		self::draw_data('order', $order);

		$order['original'] = $order;

		if (!empty($order['config'])) {
			foreach ($order['config'] as $k => $v) if (is_array($v) && isset($v['VALUE'])) $order['config'][$k] = $v['VALUE']; // ���� ������� �������� ��������, ������ ��������� � ������� ��������
			self::$config = $order['config'];
		}
		$config = self::$config;
		foreach (self::$setting_key as $k => $v) if (!isset($config[$k])) $config[$k] = $v;
		$order['original']['config'] = $config;

		$a = false;
		if (self::$result != null && !empty(self::$result['order']['original']) && !(isset($order['NO_LOCAL_CACHE']) && $order['NO_LOCAL_CACHE'] == 'Y' || defined('EDOST_NO_LOCAL_CACHE') && EDOST_NO_LOCAL_CACHE == 'Y')) {
			$a = true;
			$o = self::$result['order']['original'];

			if ($o['config']['id'] != $config['id'] || $o['config']['ps'] != $config['ps']) $a = false;

			if (empty($o['location']) || empty($order['location'])) $a = false;
			else foreach ($o['location'] as $k => $v) if (!isset($order['location'][$k]) || $v != $order['location'][$k]) { $a = false; break; }

			if (empty($o['items']) || empty($order['items']) || count($o['items']) != count($order['items'])) $a = false;
			else foreach ($o['items'] as $k => $v)
				if (empty($v) || empty($order['items'][$k])) { $a = false; break; }
				else foreach ($v as $k2 => $v2) if (!isset($order['items'][$k][$k2]) || $k2 == 'size' && implode('x', $v2) != implode('x', $order['items'][$k][$k2]) || $k2 != 'size' && $v2 != $order['items'][$k][$k2]) { $a = false; break; }
		}
		if ($a) return self::$result;

		if (class_exists('edost_function') && method_exists('edost_function', 'BeforeCalculate')) {
			$v = edost_function::BeforeCalculate($order, $config);
			if ($v !== false && is_array($v)) return self::SetResult($v, $order, $config);
		}

		$cart = (!isset($order['CART']) ? 'Y' : $order['CART']);
		$write_log = (defined('EDOST_WRITE_LOG') && EDOST_WRITE_LOG == 'Y' ? true : false);

		$total = self::SetItem($order['items'], true);

		if (defined('EDOST_IGNORE_ZERO_WEIGHT') && EDOST_IGNORE_ZERO_WEIGHT == 'Y') $total['weight_zero'] = false;

		if ($cart == 'Y') {
			if ($total['weight_zero']) $order['weight'] = 0;
			else if ($total['weight'] > 0) $order['weight'] = $total['weight'];

			if ($total['price'] > 0) $order['price'] = $total['price'];
		}
		else {
			$s = (empty($order['size']) ? $order['size'] : array(0, 0, 0));
			$quantity = (isset($order['quantity']) && intval($order['quantity']) > 0 ? intval($order['quantity']) : 1);

			$order['weight'] = $order['weight'] * $quantity;
			$order['price'] = $order['price'] * $quantity;

			if ($cart != 'DOUBLE') $total['package'] = array();
			else {
				if ($total['weight_zero']) $order['weight'] = 0;
				else {
					$order['weight'] += $total['weight'];
					$order['price'] += $total['price'];
				}
			}

			self::PackItem($total, $s, $quantity);
		}

		// ������ ��������
		$order['size'] = self::PackItems($order['weight'] > 0 ? $total['package'] : '');
		if ($config['package'] != 'B' && !empty($total['package2']) && !(count($total['package2']) == 1 && $total['package2'][0]['quantity'] == 1) && !empty($order['size'])) {
			$volume = 0;
			$max = array(0, 0, 0);
			foreach ($total['package2'] as $v) {
				$volume += $v['volume']*$v['quantity'];
				foreach ($v['size'] as $i => $s) if ($s > $max[$i]) $max[$i] = $s;
			}
			if ($volume != 0 && $max[0] > 0 && $max[1] > 0 && $max[2] > 0) {
				$k = 0;
				if ($config['package'] == 'S2') $k = 0.25;
				else if ($config['package'] == 'M') $k = 0.5;
				else if ($config['package'] == 'B2') $k = 0.75;

				$volume += ($order['size'][0]*$order['size'][1]*$order['size'][2] - $volume)*$k;
				$s = round(pow($volume, 1/3));
				$size = array(0, $s, $s);

				if ($max[2] > $size[2]) {
					$size[2] = $max[2];
					$size[1] = round(pow($volume / $size[2], 0.5));
				}
				if ($max[1] > $size[1]) $size[1] = $max[1];
				$size[0] = round($volume / ($size[1] * $size[2]) + 0.5);

				$order['size'] = $size;
			}
		}

		$s = '';
		if (!(isset($config['send_zip']) && $config['send_zip'] == 'N') && isset($order['location']['zip'])) {
			$s = substr($order['location']['zip'], 0, 8);
			if ($s == '0' || $s == '.') $s = '';
			else if (strlen($s) == 7 && strlen(preg_replace("/[^0-9]/i", "", $s)) == 6 && substr($s, -1) == '.') $s = substr($s, 0, 6); // ����� � ����� ������� - ������ ��������� ��������
		}
		$order['location']['zip'] = $s;

		if (class_exists('edost_function') && method_exists('edost_function', 'BeforeCalculateRequest')) {
			$v = edost_function::BeforeCalculateRequest($order, $config);
			if ($v !== false && is_array($v)) return self::SetResult($v, $order, $config);
		}

		$order['weight'] = round($order['weight'], 3);
		if (!($order['weight'] > 0)) return self::SetResult(array('error' => empty($order['items']) ? 'no_item' : 11), $order, $config); // � ������� �� ����� ���

		if (empty($order['location']) || !isset($order['location']['country']) || $order['location']['country'] === false) return self::SetResult(array('error' => 'no_location'), $order, $config); // �� ������� ��������������
		if (isset(self::$region_code[$order['location']['country']]) && !isset(self::$region_code[$order['location']['country']][$order['location']['region']])) return self::SetResult(array('error' => 20), $order, $config); // �������� ��� �������

		// ��������� �����������
		$cache_id = 'edost_delivery_'.$config['id'].'_'.$order['location']['country'].'_'.$order['location']['region'].'_'.$order['location']['city'].'_'.$order['location']['zip'].'_'.$order['weight'].'_'.ceil($order['price']).'_'.implode('_', $order['size']);
		$cache_time = (defined('EDOST_CACHE_LIFETIME') ? EDOST_CACHE_LIFETIME : 18000);

		// ������ �� ������ �������
		$ar = array();
		$ar[] = 'country='.$order['location']['country'];
		$ar[] = 'region='.$order['location']['region'];
		$ar[] = 'city='.urlencode($order['location']['city']);
		$ar[] = 'weight='.urlencode($order['weight']);
		$ar[] = 'insurance='.urlencode($order['price']);
		$ar[] = 'size='.urlencode(implode('|', $order['size']));
		if ($order['location']['zip'] !== '') $ar[] = 'zip='.urlencode($order['location']['zip']);
		$r = self::RequestData($config['host'], $config['id'], $config['ps'], implode('&', $ar), 'delivery', array('id' => $cache_id, 'time' => $cache_time));

		if (class_exists('edost_function') && method_exists('edost_function', 'AfterCalculate')) edost_function::AfterCalculate($order, $config, $r);

		// ���������� ������� � ��� ����
		if ($write_log && empty($r['cache'])) {
			$s = '';
			if (isset($r['error'])) $s = self::GetError($r['error']);
			else if (!empty($r['data'])) $s = self::implode2(array("\r\n", ' | ', ' / '), $r['data']);
			self::WriteLog($order['location']['country'].', '.$order['location']['region'].', '.self::site_charset($order['location']['city']).', '.$order['location']['zip'].', '.$order['weight'].' kg, '.$order['price'].' rub, '.implode(' x ', $order['size']).' - '.date("Y.m.d H:i:s")."\r\n\r\n".$s);
		}

		return self::SetResult($r, $order, $config);

	}

	// ��������� ���������� � ���������� ������
	public static function SetResult($data, $order, $config) {

		$k = (isset($data['sizetocm']) ? $data['sizetocm'] : 0); // ����������� ��������� ��������� �������� � ���������� (�������� ����������� � ������ �������� edost)
		$size = (isset($order['size']) ? $order['size'] : array(0, 0, 0));

		$data['order'] = array(
			'location' => (isset($order['location']) ? $order['location'] : false),
			'weight' => $order['weight'],
			'price' => $order['price'],
			'size1' => ceil($size[0] * $k),
			'size2' => ceil($size[1] * $k),
			'size3' => ceil($size[2] * $k),
			'sizesum' => ceil(($size[0] + $size[1] + $size[2]) * $k),
			'config' => $config,
			'original' => $order['original'],
		);

		self::$result = $data;

		return $data;

	}

	public static function WriteLog($data) {
		$fp = fopen(dirname(__FILE__)."/edost.log", "a");
		fwrite($fp, "\r\n==========================================\r\n");
		fwrite($fp, $data);
		fclose($fp);
	}

	public static function RequestError($code, $msg, $file, $line) {
		self::$error = true;
		return true;
	}

	// ������ �� ������ edost
	public static function RequestData($url, $id, $ps, $post, $type, $cache_param = false) {

		if ($type != 'print') {
			if ($id === '' || $ps === '') return array('error' => 12);
			if (intval($id) == 0) return array('error' => 3);
		}
		if ($post === '') return array('error' => 4);


		if (!empty($cache_param)) {
			$cache_data = new edost_cache();
			$r = $cache_data->get($cache_param['id'], $cache_param['time']);
			if (!empty($r)) {
				if (!in_array($type, array('develop', 'print'))) {
					$r = self::ParseData($r, $type);
					$r['cache'] = true;
				}
				return $r;
			}
		}


		$cache = new edost_cache();
		$server_cache = $cache->get('edost_server', 86400*30);

		$post_original = $post;

		$auto = ($url == '' ? true : false);
		$server_type = (in_array($type, array('delivery', 'control', 'detail', 'print')) ? 'main' : 'zip');
		$server_default = ($server_type == 'main' ? EDOST_SERVER : EDOST_SERVER_ZIP);
		$server = ($auto ? $server_default : $url);
		if ($auto && isset($server_cache[$server_type])) $server = $server_cache[$server_type];
		if ($server == '') $server = $server_default;
		$url = 'http://'.$server.'/'.($server_type == 'main' ? 'api2.php' : 'api.php');

		if ($type != 'print') $post = 'id='.$id.'&p='.$ps.'&version=2.5.1&'.$post;
		$parse_url = parse_url($url);
		$path = $parse_url['path'];
		$host = $parse_url['host'];

		self::$error = false;
		set_error_handler(array('edost_class', 'RequestError'));

		$fp = fsockopen($host, 80, $errno, $errstr, 4); // 4 - ������������ ����� �������
		restore_error_handler();
//		echo '<br>error: '.($fp ? 'fsockopen TRUE' : 'fsockopen FALSE').' | '.(self::$error ? 'self::error TRUE' : 'self::error FALSE').' | '.$errno.' - '.$errstr;

		if ($errno == 13 || self::$error || !$fp) $r = array('error' => 14); // ��������� ������� �� ��������� ��������� ������ �� ������
		else {
			$out =	"POST ".$path." HTTP/1.0\r\n".
					"Host: ".$host."\r\n".
					"Referer: ".$url."\r\n".
					"Content-Type: application/x-www-form-urlencoded\r\n".
					"Content-Length: ".strlen($post)."\r\n\r\n".
					$post."\r\n\r\n";

			fputs($fp, $out);
			$r = '';
			while ($gets = fgets($fp, 512)) $r .= $gets;
			fclose($fp);

//			echo '<br>----------------<br>'.$out.'<br>----------------';
//			echo '<br><br>response from server (original): ----------------<br>'.self::site_charset($r).'<br>----------------';
//			if (!is_array($_SESSION['EDOST']['request'])) $_SESSION['EDOST']['request'] = array();
//			$_SESSION['EDOST']['request'][] = array('out' => $out, 'response' => self::site_charset($r));

			$r = stristr($r, 'api_data:', false);
			if ($r === false) $r = array('error' => 8); // ������ ������� �� ��������
			else {
				$r = substr($r, 9);
				if (!empty($cache_param)) $cache_data->set($r);
				if (!in_array($type, array('develop', 'print'))) $r = self::ParseData($r, $type);
			}
		}
//		self::draw_data('request result', $r);

		// ������������ �� ������ ����������� ������, ���� ������ �� ��������
		if (isset($r['error']) && in_array($r['error'], array(8, 14)) && $auto) {
			$server_new = '';
			$ar = array($server_default, EDOST_SERVER_RESERVE, EDOST_SERVER_RESERVE2);
			for ($i = 0; $i < count($ar)-1; $i++) if ($ar[$i] == $server) { $server_new = $ar[$i+1]; break; }
			if ($server_new == '') $server_new = $server_default;

			if (empty($server_cache)) $server_cache = array();
			$server_cache[$server_type] = $server_new;
			$cache->set($server_cache);
		}

		return $r;

	}

	// �������� ������
	public static function GetOffice($order, $company) {

		if (!isset($order['location']['country']) || empty($company)) return false;

		if (class_exists('edost_function') && method_exists('edost_function', 'BeforeGetOffice')) edost_function::BeforeGetOffice($order, $company);

		$data = array();
		$location = $order['location'];
		$config = $order['config'];
		$company = implode(',', $company);

		$cache_id = 'edost_office_'.$order['location']['country'].'_'.$order['location']['region'].'_'.$order['location']['city'].'_'.$company;

		$ar = array();
		$ar[] = 'type=office';
		$ar[] = 'country='.$location['country'];
		$ar[] = 'region='.$location['region'];
		$ar[] = 'city='.urlencode($location['city']);
		$ar[] = 'company='.urlencode($company);

		$data = self::RequestData('', $config['id'], $config['ps'], implode('&', $ar), 'office', array('id' => $cache_id, 'time' => 86400));

		if (class_exists('edost_function') && method_exists('edost_function', 'AfterGetOffice')) edost_function::AfterGetOffice($order, $data);

		if (!isset($data['error'])) self::AddOfficeParam($data);
//		self::draw_data('get office', $data);

		// ����������� �� ���������� ������
		if (!empty($data['data']) && !empty($data['limit']))
			foreach ($data['limit'] as $v) if (isset($data['data'][$v['company_id']]))
				foreach ($data['data'][$v['company_id']] as $k2 => $v2) if ($v2['type'] == $v['type']) {
					$a = false;
					if ($order['weight'] < $v['weight_from'] || $v['weight_to'] != 0 && $order['weight'] > $v['weight_to']) $a = true;

					$ar = array('size1', 'size2', 'size3', 'sizesum');
					foreach ($ar as $s) if ($v[$s] != 0 && $order[$s] > $v[$s]) $a = true;

					if ($a) unset($data['data'][$v['company_id']][$k2]);
					else if ($v['price'] != 0) $data['data'][$v['company_id']][$k2]['codmax'] = intval($v['price'] - $order['price'] - 1);
				}

		return $data;

	}

	public static function AddOfficeParam(&$data) {
		foreach ($data['data'] as $k => $v) foreach ($v as $k2 => $v2) {
			$data['data'][$k][$k2]['address_full'] = $v2['address'].($v2['address2'] != '' ? ', ' : '').$v2['address2'];
			$data['data'][$k][$k2]['cod_disable'] = self::CodDisable($v2['options']);
		}
	}

	public static function CodDisable($options) {
		return (($options & 6) == 2 ? true : false); // ������ �� ������ ��������� � ���������� ������ ������ (0 - ������ �������, 4 - �������� � �����, 6 - ������ �����, 2 - ��� ������ ��� ���������)
	}

	// �������� �������, ���������� � ������ �������� eDost
	public static function GetActiveTariff(&$config) {

		$ar = self::GetMessage('EDOST_DELIVERY_ERROR');
		$zero_company = $ar['tariff_zero'];

		$cache = new edost_cache();
		$data = $cache->get('edost_delivery_active_'.$config['id'], 60);
		if (empty($data)) {
			$data = self::RequestData($config['host'], $config['id'], $config['ps'], 'active=Y', 'delivery');
			$cache->set($data);
		}

		$error = (isset($data['error']) ? self::GetError($data['error']) : '');
		$data = (!empty($data['data']) ? $data['data'] : array());
		$data = array('0' => array('profile' => 0, 'company' => $zero_company, 'name' => '', 'description' => '')) + $data;
		foreach ($data as $k => $v) {
			$s = self::GetTitle($v);
			$data[$k]['title'] = (!empty($config['tariff'][$k]['title']) ? $config['tariff'][$k]['title'] : $s);
			$data[$k]['default'] = $s;
			$data[$k]['description'] = (!empty($config['tariff'][$k]['description']) ? $config['tariff'][$k]['description'] : '');
		}
		$config['tariff'] = $data;

		return $data;

	}

	// �������������� �������
	public static function FormatTariff($shop_data = false, $active = false, $config = array()) {

		$r = array();
		$data = array();
		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
		$rename = self::GetMessage('EDOST_DELIVERY_RENAME');
		$tariff_data = self::GetMessage('EDOST_DELIVERY_TARIFF');
		$order = (isset(self::$result['order']) ? self::$result['order'] : array());

		if (!empty($order['config'])) $config = $config + $order['config'];
		foreach (self::$setting_key as $k => $v) if (!isset($config[$k])) $config[$k] = $v;
		$template_2019 = (!empty($config['COMPACT']) ? true : false);
		if (!$template_2019) {
			$config['COMPACT'] = 'off';
			$config['PRIORITY'] = '';
		}
		if ($config['COMPACT'] == 'off') $config['PRIORITY'] = 'P';
		if (!empty($config['CATALOGDELIVERY'])) {
			if (in_array($config['template_block_type'], array('bookmark2'))) $config['template_block_type'] = 'bookmark1';
			if (!empty($config['SHOW_ERROR'])) {
				$config['hide_error'] = 'N';
				$config['show_zero_tariff'] = 'Y';
			}
			else {
				$config['hide_error'] = 'Y';
				$config['show_zero_tariff'] = 'N';
			}
		}
		if ($config['template'] != 'Y') {
			$config['template_autoselect_office'] = 'N';
			$config['template_format'] = 'off';
			$config['template_cod'] = 'off';
		}
		if ($config['COMPACT'] != 'off') {
			if ($config['template_format'] == 'off') $config['template_format'] = 'odt';
			$config['template_block'] = 'all';
			$config['template_block_type'] = 'none';
			$config['template_cod'] = 'td';
			$config['template_map_inside'] = 'N';
		}
		else {
			if ($config['template_autoselect_office'] == 'Y') $config['template_map_inside'] = 'N';
			if ($config['template_format'] == 'off') $config['template_block'] = 'off';
			if ($config['template_block'] == 'off') {
				$config['template_block_type'] = 'none';
				$config['template_map_inside'] = 'N';
			}
			else if ($config['template_block'] != 'all' && (in_array($config['template_block_type'], array('bookmark1', 'bookmark2')))) $config['template_block'] = 'auto2';
			if (empty($config['template_map_inside'])) $config['template_map_inside'] = 'N';
		}
		$order['config'] = $config;
//		self::draw_data('config', $config);
//		self::draw_data('order', $order);

		$office_get = array();
		$bookmark = in_array($config['template_block_type'], array('bookmark1', 'bookmark2'));
		$edost_enabled = false;
		$edost_error = false;
		$show_error = (!isset($config['SHOW_ERROR']) || $config['SHOW_ERROR'] ? true : false);


		if ($shop_data === false && !empty(self::$result['data'])) {
			$shop_data = (isset(self::$result['data']) ? self::$result['data'] : array());
			if (!empty($shop_data)) foreach ($shop_data as $k => $v) {
				$v['id'] = $v['profile'];
				$v['automatic'] = 'edost';
				$shop_data[$k] = $v;
			}
		}

		$edost_sort = false;
		if (!empty($shop_data)) foreach ($shop_data as $k => $v) {
			$sort = (isset($v['sort']) ? $v['sort'] : $k)*1000;

			if ($v['automatic'] === 'edost') {
				$edost_enabled = true;
				if ($edost_sort === false) $edost_sort = $sort;

				$tariff = (!isset($v['format']) ? self::GetTariff($v['profile']) : $v);
                if (isset($tariff['error'])) continue;

				$v['format_original'] = $tariff['format'];

				// ������� "�� ��������", "�� ���������" � "�� ��������" � ����� ������
				if ($config['template_map_inside'] == 'Y' || $config['COMPACT'] != 'off') {
					$ar = array('office' => array('shop', 'terminal'), 'door' => array('house'));
					foreach ($ar as $k2 => $v2) if ($k2 == 'office' || $config['COMPACT'] != 'off') if (in_array($tariff['format'], $v2)) { $tariff['format'] = $k2; break; }
				}

				$v = array_merge($tariff, $v);
				$v['sort'] = ($config['template'] == 'N' ? $sort : $v['sort'] + $edost_sort);
				$v['ico'] = $v['tariff_id'] = $tariff['id'];
				if (isset($v['title'])) $v = array_merge($v, self::ParseName($v['title'], '', $v['description'], $sign['insurance']));

				if ($v['pricecash'] == -1) unset($v['pricecash']);
				if (!empty($v['priceoffice'])) foreach ($v['priceoffice'] as $k2 => $v2) if ($v2['pricecash'] == -1) unset($v['priceoffice'][$k2]['pricecash']);
			}
			else {
				$v['sort'] = $sort;
				$v['format'] = 'shop_'.($edost_sort === false ? 1 : 2);
				$v['price'] = (isset($v['shop_price']) ? $v['shop_price'] : 0);
				$v['format_original'] = '';
				if (isset($v['title'])) $v = array_merge($v, self::ParseName($v['title'], '', $v['description']));
			}

			$shop_data[$k] = $v;
		}
//		self::draw_data('shop_data', $shop_data);


		// ���������� � �������������� ������ ��� ������� ��� ����������
		if (!empty($active['bookmark'])) {
			$s = explode('_', $active['bookmark']);
			if ($config['template_block_type'] == 'bookmark2' && $s[0] != 'show') $active = array('id' => '', 'bookmark' => $s[0]);
			else if (isset($s[1]) && $s[1] == 's') $active = (isset($_SESSION['EDOST']['delivery_default'][$s[0]]) ? $_SESSION['EDOST']['delivery_default'][$s[0]] : array('id' => '', 'bookmark' => $s[0]));
			else $_SESSION['EDOST']['delivery_default'][$s[0]] = $active;
		}

		if (!empty($shop_data)) foreach ($shop_data as $k => $v)
			if ($v['automatic'] !== 'edost') $data[] = $v;
			else {
				if (isset($active['id']) && $active['id'] == $v['id'] || isset($active['profile']) && $active['profile'] == $v['profile']) {
					$active['profile'] = $v['profile'];
					if (isset($v['format'])) $active['format'] = $v['format'];
				}

				if ($v['profile'] != 0) {
					if ($config['template_ico'] == 'T2' && !empty($v['img_path'])) $v['ico'] = $v['img_path'];
					else if ($config['template_ico'] == 'C') $v['company_ico'] = self::GetCompanyIco(!empty($v['company_id']) ? $v['company_id'] : 0, $v['tariff_id']);

					$v['title'] = self::GetTitle($v);
					if (!isset($v['description'])) $v['description'] = '';
					$v['name'] = trim(str_replace($sign['insurance'], '', $v['name']));

					if (in_array($v['format'], self::$office_key)) $office_get[$v['company_id']] = $v['company_id'];

					if ($v['tariff_id'] == 3 && empty($order['location']['city'])) $v['warning'] = $sign['ems_warning'];

					$price_discount = false;
					if (isset($v['shop_price'])) {
						$p = self::GetPrice('value', $v['shop_price']);
						if (abs($p - $v['price']) > 5) {
							$price_discount = $p;
							$price_original = $v['price'];
						}
					}

					$v_save = $v;
					$s = array();
					if ($config['map'] == 'Y' && !empty($v['priceoffice'])) foreach ($v['priceoffice'] as $v2) {
						$o = $v;
						$o['to_office'] = $v2['type'];
						$key = array('price', 'priceinfo', 'pricecash', 'priceoriginal');
						foreach ($key as $p) if (isset($v2[$p])) $o[$p] = $v2[$p];
						$s[] = $o;
					}
					$s[] = $v;
					foreach ($s as $tariff) {
						$v = $v_save;

						// ������ �� ������� �������� eDost
						if ($config['edost_discount'] == 'Y' && !empty($tariff['priceoriginal']['price'])) {
							if ($tariff['priceoriginal']['price'] - $tariff['price'] > 5) {
								$v += self::GetPrice('price_original', $tariff['priceoriginal']['price']);
								$v += self::GetPrice('pricetotal_original', $tariff['priceoriginal']['price'] + $tariff['priceinfo']);
							}
							if (!empty($tariff['priceoriginal']['pricecash']) && ($tariff['priceoriginal']['pricecash'] - $tariff['pricecash'] > 5)) {
								$v += self::GetPrice('pricecod_original', $tariff['priceoriginal']['pricecash'] + $tariff['transfer']);
								$v += self::GetPrice('pricecash_original', $tariff['priceoriginal']['pricecash']);
							}
						}

						// ������ ��������
						if ($price_discount !== false) {
							if (!empty($tariff['pricecash']) && $config['sale_discount_cod'] != 'off') {
								$pricecash_discount = self::SetDiscount($tariff['pricecash'], $price_original, $price_discount, $config['sale_discount_cod']);
								if ($tariff['pricecash'] - $pricecash_discount > 5) {
									$v += self::GetPrice('pricecod_original', $tariff['pricecash'] + $tariff['transfer']);
									$v += self::GetPrice('pricecash_original', $tariff['pricecash']);
								}
								$tariff['pricecash'] = $pricecash_discount;
							}

							if ($config['sale_discount'] == 'Y' && $tariff['price'] > 0 && $price_original - $price_discount > 5) {
								$v += self::GetPrice('price_original', $tariff['price']);
								$v += self::GetPrice('pricetotal_original', $tariff['price'] + $tariff['priceinfo']);
							}
							$tariff['price'] = (isset($tariff['to_office']) ? self::SetDiscount($tariff['price'], $price_original, $price_discount) : $price_discount);
	                	}

						$v = array_merge($v, self::GetPrice('price', $tariff['price']));
						$v += self::GetPrice('pricetotal', $tariff['price'] + $tariff['priceinfo']);
						if ($tariff['priceinfo'] > 0) $v += self::GetPrice('priceinfo', $tariff['priceinfo']);
						if (isset($tariff['pricecash'])) {
							$v += self::GetPrice('pricecod', $tariff['pricecash'] + $tariff['transfer']);
							$v = array_merge($v, self::GetPrice('pricecash', $tariff['pricecash']));
							$v = array_merge($v, self::GetPrice('transfer', $tariff['transfer']));
						}

						if (isset($tariff['to_office'])) {
							$v['to_office'] = $tariff['to_office'];
							$data[] = $v;
						}
					}
				}
				else {
					if (!$show_error) continue;

					if ($config['hide_error'] != 'Y') $error = self::GetError(isset(self::$result['error']) ? self::$result['error'] : 0); else $error = '';

					$edost_error = true;
					$v['error'] = $error;
					$v['price'] = 0;
					$v['sort'] = 0;
					$v['name'] = '';
					$v['format'] = '';
					$v['ico'] = 0;
					if ($config['template_ico'] == 'C') $v['company_ico'] = 0;
				}

				$data[] = $v;
			}
//		self::draw_data('data', $data);

		// �������� ��� ����������� ������� ��������� �������
		foreach ($data as $k => $v) $data[$k]['cod_tariff'] = false;
		if ($config['template_cod'] == 'tr') {
			$ar = array();
			foreach ($data as $k => $v) if (isset($v['pricecash'])) {
				$a = true;
				foreach ($ar as $k2 => $v2) if ($v2['tariff_id'] == $v['tariff_id'] && (!isset($v2['to_office']) && !isset($v['to_office']) || isset($v2['to_office']) && isset($v['to_office']) && $v2['to_office'] == $v['to_office'])) {
					$a = false;
					if ($v2['sort'] <= $v['sort']) $ar[$k2]['sort'] = $v['sort'] + 1;
				}
				if (!$a) continue;

				$v['cod_tariff'] = true;
				$v['sort']++;
				$v['price'] = $v['pricetotal'] = $v['pricecash'];
				$v['price_formatted'] = $v['pricetotal_formatted'] = $v['pricecash_formatted'];
				if ($v['insurance'] == 0 && !in_array($v['tariff_id'], self::$tariff_shop)) $v['insurance'] = 1; // ������������ ���������
				if (!empty($v['transfer'])) $v['warning'] = str_replace('%transfer%', $v['transfer_formatted'], $sign['transfer']);

				if (isset($v['price_original'])) {
					$v['price_original'] = $v['pricetotal_original'] = $v['pricecod_original'];
					$v['price_original_formatted'] = $v['pricetotal_original_formatted'] = $v['pricecod_original_formatted'];
				}

				$ar[] = $v;
			}
			if (!empty($ar)) $data = array_merge($data, $ar);
		}
//		self::draw_data('data + cod', $data);

		// �������� �������� ������
		$office_default = array();
		if (isset($_SESSION['EDOST']['office_default'])) $office_default = $_SESSION['EDOST']['office_default'];
		else if (isset($_COOKIE['edost_office'])) {
			$o = explode('|', substr($_COOKIE['edost_office'], 0, 250));
			foreach ($o as $k => $v) {
				$s = explode('_', $v);
				if (!empty($s[2])) $office_default[$s[0]] = array('id' => intval($s[2]), 'profile' => intval($s[1]), 'cod_tariff' => $s[3] == 'Y' ? true : false);
			}
		}

		// �������� �������� ������, ���� ���� ������ ������� ��������
		if ($edost_error && $config['hide_error'] == 'Y' && count($data) > 1)
			foreach ($data as $k => $v) if ($v['automatic'] == 'edost') unset($data[$k]);

		// �������������� ����� �� ������� ����������
		if (!empty($order['location']['address'])) {
			$o = self::ParseOfficeAddress($order['location']['address']);
			if (!empty($o['id'])) $office_default['profile'] = array('id' => $o['id'], 'profile' => $o['profile'], 'cod_tariff' => $o['cod_tariff']);
        }
		if (!empty($office_default['profile'])) {
			$ar = $office_default['profile'];
			foreach ($data as $k => $v) if ($v['profile'] == $ar['profile'] && $v['cod_tariff'] == $ar['cod_tariff'] && !isset($office_default[$v['format']])) $office_default[$v['format']] = $ar;
			unset($office_default['profile']);
			$_SESSION['EDOST']['office_default'] = $office_default;
		}
//		self::draw_data('office_default', $office_default);

		// ����������
		if ($config['template'] != 'Y' || $config['template_format'] == 'off') $sorted = false;
		else {
			self::SortTariff($data, $config);
			$sorted = true;
		}


		// ������ �������
		$ar = array(
			'odt' => array('shop', 'office', 'terminal', 'door', 'house', 'post', 'general'),
			'dot' => array('door', 'house', 'shop', 'office', 'terminal', 'post', 'general'),
			'tod' => array('post', 'shop', 'office', 'terminal', 'door', 'house', 'general'),
		);
		$ar = (isset($ar[$config['template_format']]) ? $ar[$config['template_format']] : $ar['odt']);

		$format = array_fill_keys($ar, '');
		$format_data = self::GetMessage('EDOST_DELIVERY_FORMAT');
		foreach ($format as $f_key => $f) {
			$f = (isset($format_data[$f_key]) ?  $format_data[$f_key] : array());
			if (!isset($f['name'])) $f['name'] = '';
			$f['data'] = array();
			$format[$f_key] = $f;
		};

		// ������������� ������� �� �������
		foreach ($data as $k => $v) {
			$f_key = ($config['template'] == 'Y' && !empty($v['format']) && isset($format[$v['format']]) ? $v['format'] : 'general');
			$format[$f_key]['data'][] = $v;
		}
//		self::draw_data('format start', $format);


		// ����������� �������� ������� ��� ������ eDost
		if ($config['template'] == 'Y') {
			$hide = array();;
			foreach ($sign['hide'] as $v) $hide[] = '- '.$v;
			$hide = array_merge($hide, $sign['hide']);

			foreach ($format as $f_key => $f) if (!empty($f['data'])) {
				// �������� �������� ������, ���� � ���� ������� �������� ���������� �������� (��� ����� ������ ����)
				if (empty($config['NAME_NO_CHANGE'])) {
					$n = count($f['data']);
					for ($i = 0; $i < $n; $i++) if ($f['data'][$i]['automatic'] === 'edost' && empty($f['data'][$i]['error']) && !isset($f['data'][$i]['deleted'])) {
						$p = $p2 = 0;
						for ($i2 = $i+1; $i2 < $n; $i2++) if ($f['data'][$i]['company'] == $f['data'][$i2]['company']) {
							$p++;
							if ($f['data'][$i]['name'] == $f['data'][$i2]['name']) $p2++;
							$f['data'][$i2]['deleted'] = true;
						}
						if ($p == $p2) for ($i2 = $i; $i2 < $n; $i2++) if ($f['data'][$i]['company'] == $f['data'][$i2]['company']) $format[$f_key]['data'][$i2]['name'] = '';
					}
				}

				// �������� �� �������� ������ ������ '�������� �� �����', '�� ������ ������', ...
				if (empty($config['NAME_NO_CHANGE']) || in_array($f_key, array('office', 'terminal'))) {
					foreach ($format[$f_key]['data'] as $k => $v) if ($v['name'] != '' && in_array($v['format'], array('door', 'office', 'terminal', 'house'))) {
						$s = str_replace($hide, '', $v['name']);
						$format[$f_key]['data'][$k]['name'] = trim($s);
					}
				}
			}
		}
//		self::draw_data('format name', $format);


		// �������� ������ � ������� edost
		$office = array();
		$office_error = false;
		if ($config['map'] == 'Y' || $config['template'] != 'Y') {
			$office = self::GetOffice($order, $office_get);
			if (isset($office['error'])) $office_error = $office['error'];
			if ($config['map'] == 'Y' && !empty($office['pickpointmap'])) $r['pickpointmap'] = $office['pickpointmap'];
			$office = (!empty($office['data']) ? $office['data'] : array());
			if ($config['template'] != 'Y') $r['office'] = $office;
		}
//		self::draw_data('office', $office);


		// ������ � ���������� �������� ��� ��������� � ������� ���� ������ �� ����������
		foreach ($format as $f_key => $f) if (!empty($f['data'])) foreach ($f['data'] as $k => $v) if (isset($v['pricecod']) && $v['insurance'] == 1)
			foreach ($f['data'] as $k2 => $v2) if ($k != $k2 && isset($v2['pricecod']) && $v['tariff_id'] == $v2['tariff_id'] && $v2['insurance'] != 1) {
				if ($v['pricecod'] >= $v2['pricecod']) $format[$f_key]['data'][$k]['cod_hide'] = true;
				else $format[$f_key]['data'][$k2]['cod_hide'] = true;
				break;
			}

		$delivery_bonus = (!empty($config['DELIVERY_BONUS']) || $config['PRIORITY'] == 'B' || $config['PRIORITY'] == 'C' ? true : false);
		$cod_filter = (!empty($config['COD_FILTER']) ? true : false);
		$cod_filter_zero_tariff = ($config['PRIORITY'] == 'C' && (empty($config['COD_FILTER_ZERO_TARIFF']) || $config['COD_FILTER_ZERO_TARIFF'] == 'Y') ? true : false);
		$zero_tariff = ($config['hide_error'] != 'Y' || $config['show_zero_tariff'] == 'Y' ? true : false);

		if ($delivery_bonus || $cod_filter) {
			$office_id = array();
			$tariff_id = array('delete' => array(), 'cod' => array());

			// ����� ��� �������
			foreach ($format as $f_key => $f) if (in_array($f_key, self::$office_key) && !empty($f['data'])) foreach ($f['data'] as $k => $v) {
				$id = $v['company_id'];
				if (!isset($office[$id])) continue;

				// ��������� ����������� ���������� ����� �������� ��� ���������� ������ ��� ���������
				if (isset($v['pricecash'])) foreach ($office[$id] as $o_key => $o) if (isset($o['codmax']) && $v['pricecash'] > $o['codmax'] || !empty($o['cod_disable'])) $office_id[$o_key] = array($id, $o_key);

				// ������������ �����
				if (!isset($v['pricecash']) && isset($v['to_office'])) foreach ($office[$id] as $o_key => $o) if ($o['type'] == $v['to_office']) $office_id[$o_key] = array($id, $o_key);
			}

			// ������ � ��������                                                                                                                                      && !isset($v['error'])
			foreach ($format as $f_key => $f) if (!empty($f['data'])) foreach ($f['data'] as $k => $v) $tariff_id[(!isset($v['pricecash']) || !empty($v['cod_hide'])) ? 'delete' : 'cod'][] = array($f_key, $k);

			// ������ ����������
			if ($delivery_bonus) $r['bonus'] = array('cod' => array(), 'normal' => array());
		}

		// ��������� ������� � �������� � ��� ������� (��� ���������� ������ ��� ��������� � ������ ������� � �������� ������)
		for ($i_compare = 1; $i_compare <= 3; $i_compare++) {
			if ($i_compare == 2 && !$delivery_bonus || $i_compare == 3 && !($cod_filter && !empty($r['cod_delete']))) break;

			if ($i_compare == 1) $original = array($format, $office, $active);
			else { $format = $original[0]; $office = $original[1]; $active = $original[2]; }

			// �������� ������ � ������� ��� ������� + ������ 'price' �� 'pricecod'
			if ($i_compare == 1 && $cod_filter && !$delivery_bonus || $i_compare == 1 && !$cod_filter && $delivery_bonus || $i_compare == 2 && $cod_filter) {
				foreach ($office_id as $k) unset($office[$k[0]][$k[1]]);
				foreach ($tariff_id['delete'] as $k) unset($format[$k[0]]['data'][$k[1]]);

				$n = count($tariff_id['cod']);
				foreach ($tariff_id['cod'] as $i => $k) {
					$v = $format[$k[0]]['data'][$k[1]];

					$v['price'] = $v['pricetotal'] = $v['pricecod'];
					$v['price_formatted'] = $v['pricetotal_formatted'] = $v['pricecod_formatted'];

					if (isset($v['price_original']))
						if (!isset($v['pricecod_original'])) {
							unset($v['price_original']);
							unset($v['price_original_formatted']);
							unset($v['pricetotal_original']);
							unset($v['pricetotal_original_formatted']);
						}
						else {
							$v['price_original'] = $v['pricetotal_original'] = $v['pricecod_original'];
							$v['price_original_formatted'] = $v['pricetotal_original_formatted'] = $v['pricecod_original_formatted'];
						}

					unset($v['pricecod']);
					$format[$k[0]]['data'][$k[1]] = $v;
				}
			}

			$active_id = (isset($active['id']) ? $active['id'] : '');
			$active_profile = (isset($active['profile']) ? $active['profile'] : '');
			$active_cod = (!empty($active['cod_tariff']) ? true : false);
			$active_bookmark = (!empty($active['bookmark']) ? $active['bookmark'] : '');

			$ar = (isset($_SESSION['EDOST']['office_default']) ? $_SESSION['EDOST']['office_default'] : array());
			if (isset($active['format']) && !empty($active['office_id'])) {
				$c = false;
				foreach ($office as $o_key => $o) if (isset($o[$active['office_id']])) { $c = $o_key; break; }

				$ar[$active['format']] = $ar['all'] = array('id' => $active['office_id'], 'profile' => $active['profile'], 'cod_tariff' => $active_cod);
				if ($c !== false) $ar[$c] = $ar['all'];

				if ($i_compare == 1 && !$delivery_bonus || $i_compare == 2) if ($config['template_block_type'] != 'bookmark2' || $active_bookmark == 'show') $_SESSION['EDOST']['office_default'] = $ar;
			}
			$active_office = $ar;
			$active = false; // �������� �����

			// �������� �� ������������� ��������� ������ + ����������� 'type'
			foreach ($active_office as $k => $v) foreach ($office as $o) if (isset($o[$v['id']])) {
				$active_office[$k]['type'] = $o[$v['id']]['type'];
				break;
			}

			// �������� ������� ��� ������ ��� ������������ �������
			if ($config['template'] != 'Y') foreach ($format['general']['data'] as $k => $v) if (isset($v['format']) && in_array($v['format'], self::$office_key))
				if (($v['company_id'] != 26 || empty($r['pickpoint_widget'])) && empty($office[$v['company_id']]) || $v['company_id'] == 26 && !empty($r['pickpoint_widget']) && empty($r['pickpointmap'])) unset($format['general']['data'][$k]);

			// �������� ������� ��� ������ ��� ������� eDost + ��������� ��������� ������ (�������������)
			foreach ($format as $f_key => $f) if (in_array($f_key, self::$office_key) && !empty($f['data'])) {
				// ���������� ������ � ������� ������ (������� � ������������ �����, ����� ���������)
				$office_count = array();
				$office_count_total = 0;
				for ($i = 0; $i <= 1; $i++) foreach ($f['data'] as $k => $v) {
					$id = $v['company_id'];
					if (!isset($office_count[$id])) {
						$office_count[$id]['total'] = (isset($office[$id]) ? count($office[$id]) : 0);
						$office_count_total += $office_count[$id]['total'];
					}

					if ($i == 0 && isset($v['to_office'])) {
						$n = 0;
						if (isset($office[$id])) foreach ($office[$id] as $o) if ($o['type'] == $v['to_office']) $n++;
						$f['data'][$k]['office_count'] = $n;
						$office_count[$id][$v['to_office']] = $n;

						// ��������� ��������� ������ (�������������)
						if ($n > 0 && isset($active_office[$f_key]['type']) && $v['profile'] == $active_office[$f_key]['profile'] && $v['cod_tariff'] == $active_office[$f_key]['cod_tariff'] && $v['to_office'] == $active_office[$f_key]['type']) {
							if ($v['id'] == $active_id) {
								$f['data'][$k]['checked'] = true;
								$active = $v;
							}
							$active_office[$f_key]['tariff_key'] = $k;
						}
					}
					else if ($i == 1 && !isset($v['to_office'])) {
						$n = $office_count[$id]['total'];
						foreach ($office_count[$id] as $k2 => $v2) if ($k2 !== 'total') $n -= $v2;
						$f['data'][$k]['office_count'] = $n;
					}
				}

				foreach ($f['data'] as $k => $v) if ($v['office_count'] == 0) unset($f['data'][$k]);
				if ($office_count_total > 0) $f['office_count'] = $office_count_total;

				$format[$f_key] = $f;
			}

			// ���������� ����������� �������
			if ($config['PRIORITY'] == 'C') {
				$count = 0;
				foreach ($format as $f_key => $f) if (!empty($f['data'])) $count += count($f['data']);
				if ($count == 0 && ($i_compare == 1 && !$cod_filter || $i_compare == 2 && $cod_filter) && (!$zero_tariff || !$cod_filter_zero_tariff)) $r['cod_delete'] = true;
			}

			// ����� ������� (���������� ������� ������ � ����� ����������� ���� �� ������� ��� ������� �������� � � ���������� ��������)
			if ($delivery_bonus)
				if ($i_compare == 1) $format2 = $format;
				else if ($i_compare == 2) {
					$ar = ($cod_filter ? array($format, $format2) : array($format2, $format)); // ������ ������� � ��������
					for ($i = 0; $i <= 1; $i++) {
						$office_count = 0;
						foreach ($ar[$i] as $f_key => $f) if (!empty($f['data'])) {
							$office_count += (!empty($f['office_count']) ? $f['office_count'] : 0);
							$min = -1;
							foreach ($f['data'] as $k => $v) if (isset($v['pricetotal']) && ($min == -1 || $v['pricetotal'] < $min)) $min = $v['pricetotal'];
							$ar[$i][$f_key]['min'] = $min;
							if ($min >= 0) $r['bonus'][$i == 0 ? 'cod' : 'normal'][$f_key] = $min;
						}
						if ($office_count != 0) $r['bonus'][$i == 0 ? 'cod' : 'normal']['office_count'] = $office_count;
					}
				}
		}
		if (!empty($r['cod_delete'])) $cod_filter = false;


		// ��������� ��������� ������ (�� �������������)
		foreach ($format as $f_key => $f) foreach ($f['data'] as $k => $v) if (!isset($v['to_office'])) {
			if ($active === false && ($v['automatic'] != 'edost' && $v['id'] == $active_id || $v['automatic'] == 'edost' && $v['profile'] == $active_profile && $v['cod_tariff'] == $active_cod)) {
				$format[$f_key]['data'][$k]['checked'] = true;
				$active = $v;
			}
			$o = $v['format'];
			if (isset($active_office[$o]['type']) && !isset($active_office[$o]['tariff_key']) && $v['profile'] == $active_office[$o]['profile'] && $v['cod_tariff'] == $active_office[$o]['cod_tariff']) $active_office[$o]['tariff_key'] = $k;
			if (isset($v['company_id'])) if (isset($active_office[$v['company_id']]['type']) && !isset($active_office[$v['company_id']]['tariff_key']) && $v['profile'] == $active_office[$v['company_id']]['profile'] && $v['cod_tariff'] == $active_office[$v['company_id']]['cod_tariff']) $active_office[$v['company_id']]['tariff_key'] = $k;
		}
//		self::draw_data('active_office', $active_office);
//		self::draw_data('format', $format);
//		self::draw_data('active', $active);


		// �������� �� ������� 'priceinfo'
		$priceinfo = false;
		foreach ($format as $f_key => $f) if (!empty($f['data']))
			foreach ($f['data'] as $k => $v) if (!empty($v['priceinfo'])) $priceinfo = true;


		// ������ ��� �����
		if ($config['map'] == 'Y' && !empty($office)) {
			$location = $order['location'];

			if (empty($config['CLONE'])) {
				$map_key = implode('|', $order['location']).'|'.$order['weight'].'|'.$order['price'].'|'.$order['size1'].'|'.$order['size2'].'|'.$order['size2'].'|'.(!empty($config['COD_FILTER']) ? 'cod_filter' : '').'|'.(!empty($config['PAY_SYSTEM_ID']) ? $config['PAY_SYSTEM_ID'] : '');
				if (!isset($_SESSION['EDOST']['map_key']) || $_SESSION['EDOST']['map_key'] != $map_key) $r['map_update'] = true;
				$_SESSION['EDOST']['map_key'] = $map_key;
			}

			$s = array('format' => $format, 'office' => $office, 'config' => $config, 'location' => $order['location'], 'sorted' => $sorted); //, 'currency' => $currency);
			$r['map_json'] = self::GetOfficeJson($s);
			if (!empty($config['MAP_DATA'])) $r['map_data'] = $s;
		}


		// ���������� ������ ��� ������������ �������
		if ($config['template'] != 'Y') {
			$cod_warning = $sign['cod_warning'];
			foreach ($format['general']['data'] as $k => $v) {
				$office_count = 0;

				$o = false;
				if (isset($v['format']) && in_array($v['format'], self::$office_key)) {
					$office_count = count($office[$v['company_id']]);

					if (isset($v['company_id']) && isset($active_office[$v['company_id']]['tariff_key'])) $o = $office[$v['company_id']][$active_office[$v['company_id']]['id']];
					else if ($office_count == 1) foreach ($office[$v['company_id']] as $k2 => $v2) $o = $v2;

					// �������� ������� �� �������� � ��������� ������� ������
					if ($o !== false) $type = $o['type'];
					else foreach ($office[$v['company_id']] as $k2 => $v2) { $type = $v2['type']; break; }
					if (isset($v['priceoffice'][$type]) && (!isset($v['to_office']) || $v['to_office'] != $type) || !isset($v['priceoffice'][$type]) && isset($v['to_office'])) {
						unset($format['general']['data'][$k]);
						if (!empty($v['checked'])) foreach ($format['general']['data'] as $k2 => $v2) if ($v['id'] == $v2['id']) { $format['general']['data'][$k2]['checked'] = true; break; }
						continue;
					}

					if ($o === false) {
						$v['office_map'] = 'get';
						$v['office_link'] = $v['compact_link'] = $sign['compact_office_get'];
					}
					else {
						self::AddOfficeData($v, $o, $sign);

						if ($office_count == 1) $v['office_link'] = $sign['map2'];
						else {
							$v['office_map'] = 'change';
							$v['office_link'] = $v['compact_link'] = $sign['compact_office_change'];
						}
					}
				}

				if (isset($v['pricecash'])) {
					$v += edost_class::GetPrice('pricecashplus', $v['pricecash'] - $v['price']);
					$s = 'pricecash';
					if (!empty($v['transfer'])) $s = (!empty($v['pricecashplus']) ? 'full' : 'transfer');
					$v['cod_note'] = str_replace(array('%pricecashplus%', '%transfer%'), array($v['pricecashplus_formatted'], $v['transfer_formatted']), $cod_warning[$s][1]);
				}

				if (!empty($v['priceinfo']) && !empty($v['price'])) $v['note'] =  str_replace(array('%price%', '%priceinfo%'), array($v['price_formatted'], $v['priceinfo_formatted']), $sign['priceinfo_warning_compact2']);
				if (isset($v['format'])) {
					$s = array();
					if ($v['format'] == 'terminal' && $office_count > 1 && $o !== false && $o['type'] <= 2) $s[] = $sign['terminal_warning'];
					if ($v['format'] == 'house') $s[] = $sign['house_warning'];
					if (!empty($v['priceinfo'])) $s[] = $sign['priceinfo_warning_compact'];
					if (!empty($s)) $v['warning'] = implode('<br>', $s);
				}

				$format['general']['data'][$k] = $v;
			}
		}


		// �������� ������ � ������� � ���� ����� (������������� ��� � ������� �� �����)
		$tariff_count = $office_count = 0;
		$ico = false;
		$f2 = $format['office'];
		$f2['data'] = array();
		$f2['office_count'] = 0;
		$f2['head'] = $sign['bookmark']['office'];
		foreach ($format as $f_key => $f) if (isset($f['office_count'])) {
			$n = count($f['data']);
			$tariff_count += $n;
			$office_count += $f['office_count'];

			// ������� ��������� ������
			$checked = false;
			foreach ($f['data'] as $v) if (isset($v['checked'])) { $checked = true; break; }

			self::FormatRange($f, $config['template_cod'] != 'off' ? true : false);
			if ($ico === false && !empty($f['ico'])) $ico = $f['ico'];

			// ��������� ������ ����� ��������������� ����� �� ��� ���������� �� ������
			if (($config['template_map_inside'] == 'Y' || $config['COMPACT'] != 'off') && empty($active_office['all']) && isset($active_office[$f_key]['tariff_key'])) $active_office['all'] = $active_office[$f_key];

			// ��������� ������������� ����� (��� ������ �������, ���� �������� � ���������� ������ 'template_autoselect_office' ��� 'bookmark2')
			if (!isset($active_office[$f_key]['tariff_key']) && ($f['office_count'] == 1 && ($n == 1 || $config['COMPACT'] != 'off') || $config['template_autoselect_office'] == 'Y') || $config['template_block_type'] == 'bookmark2' && $active_bookmark != 'show') {
				$k = $f['min']['key'];
				$v = $f['data'][$k];
				$id = false;
				if (isset($v['to_office'])) {
					foreach ($office[$v['company_id']] as $o) if ($o['type'] == $v['to_office']) { $id = $o['id']; break; }
				}
				else foreach ($office[$v['company_id']] as $o) {
					$a = true;
					foreach ($f['data'] as $k2 => $v2) if ($k2 !== $k && $v2['company_id'] == $v['company_id'] && isset($v2['to_office']) && $v2['to_office'] == $o['type']) $a = false;
					if ($a) { $id = $o['id']; break; }
				}
				$active_office[$f_key] = array('id' => $id, 'profile' => $v['profile'], 'cod_tariff' => $v['cod_tariff'], 'type' => $office[$v['company_id']][$id]['type'], 'tariff_key' => $k);
			}

			// ��������� ������ ��� ���������� ������ ������
			$sort = 0;
			$company_id = false;
			foreach ($f['data'] as $k => $v) {
				if ($sort == 0) $sort = $v['sort'];

				if ($company_id === false) {
					$company_id = $v['company_id'];
					$tariff = $v;
				}
				else if ($v['company_id'] != $company_id) {
					$company_id = false;
					break;
				}
			}
			if ($company_id == 26) $office_link = $sign['postamat']['format_get'];
			else $office_link = $f['get'];
			$v = array(
				'id' => '',
				'automatic' => 'edost',
				'profile' => $f_key,
				'company' => (!empty($company_id) ? $tariff['company'] : ''),
				'name' => '',
				'description' => '',
				'ico' => (!empty($company_id) ? $tariff['ico'] : 35),
				'company_id' => (!empty($company_id) ? $company_id : ''),
				'format' => $f_key,
				'sort' => $sort,
				'price' => $f['price']['max']['value'],
				'price_formatted' => self::GetRange($f['price']),
				'price_long' => ($f['price']['min']['value'] == $f['price']['max']['value'] ? 'normal' : 'light'),
				'day' => '',
				'office_map' => 'get',
				'office_mode' => $f_key,
				'office_link' => $office_link,
				'office_link_head' => $sign['office'],
				'office_link2' => $sign['get'],
				'office_count' => 0,
				'office_address_full' => '',
				'cod_tariff' => false,
			);
			if ($config['template_ico'] == 'C') $v['company_ico'] = (!empty($company_id) ? $company_id : 's1');
			if ($f['pricecod']['max']['value'] >= 0) {
				if ($config['COMPACT'] != 'off') {
					$v['pricecod'] = $f['pricecod']['min']['value'];
					$v['pricecod_formatted'] = ($f['pricecod']['min']['value'] != $f['pricecod']['max']['value'] ? $sign['from'] : '') . $f['pricecod']['min']['formatted'];
				}
				else {
					$v['pricecod'] = $f['pricecod']['max']['value'];
					$v['pricecod_formatted'] = self::GetRange($f['pricecod']);
				}
			}
			$v_get = $v;

			$cod = 'start';
			if (isset($active_office[$f_key]['tariff_key'])) {
				// ����� � �������� ������� ������

				$p = $active_office[$f_key];
				$v = $f['data'][$p['tariff_key']];
				$o = $office[$v['company_id']][$p['id']];

				if ($f['office_count'] != 1 || $n != 1) {
					$v['office_map'] = 'change';
					$v['office_link'] = $sign['change'];
					foreach ($f['data'] as $v2) { $v['sort'] = $v2['sort']; break; }
				}
				else {
					$v['office_link'] = $sign[!empty($config['COMPACT']) ? 'map2' : 'map'];
					$v['office_link2'] = $sign['get'];
				}

				$v['office_mode'] = $f_key;
				self::AddOfficeData($v, $o, $sign);

				// ��������� ������, ���������� ����������� ��� 'template_map_inside' + ���������� ���������� �����
				if ($config['template_map_inside'] == 'Y' && !empty($active_office['all']['id']) && $active_office['all']['id'] == $p['id']) {
					$v['checked_inside'] = true;
					$config['template_map_inside'] = 'tariff';
				}

				if (in_array($config['template_map_inside'], array('Y', 'tariff')) && isset($v['checked']) && empty($v['checked_inside'])) unset($v['checked']);

				if (isset($v['checked'])) $active = $v;
				else if ($checked) {
					$active_id = '';
					$active = false;
				}

				$cod = (isset($v['pricecash']) ? true : false);
			}
			else {
				// ����� ��� ���������� ������ ������

				$v = $v_get;

				if ($checked) {
					$active_id = '';
					$active = false;
				}

				if ($active_profile === $f_key) {
					$v['checked'] = true;
					$active = $v;
				}

				if ($config['PRIORITY'] == 'B' && !empty($r['bonus']['cod']['office_count'])) $v['compact_cod'] = true;
			}

			if ($config['PRIORITY'] == 'B' && !empty($r['bonus']['cod']['office_count'])) {
				$v_get['compact_cod_copy'] = true;
				$v_get['compact_cod'] = true;
				$v_get['compact_head'] = $sign['compact_head'][$v['format']];
				$v_get['compact_link'] = $sign['compact_office_get'];
				if ($r['bonus']['cod']['office_count'] != 1) {
					$v_get['compact_link_cod'] = $sign['compact_office_get'];
					$v_get['compact_head_cod'] = $v_get['compact_head'];
				}
				$f2['data'][] = $v_get;
			}

			if ($config['COMPACT'] != 'off') self::FormatHead($v, $f['name'], false, true, $config);
			else self::FormatHead($v, $f['name']);

			$v['pricehead'] = $f['pricehead'];
			$v['dayhead'] = $f['day'];

			if (!isset($f2['min']) || $f['min']['price'] < $f2['min']['price']) {
				$f2['min'] = $f['min'];
				$f2['min']['key'] = count($f2['data']);
			}

			$f2['data'][] = $v;
			$f2['office_count'] += $f['office_count'];
			$format[$f_key]['data'] = array();
		}

		if ($config['template_map_inside'] == 'tariff' && !empty($f2['data'])) {
			if ($tariff_count > 1 || $office_count > 1) foreach ($f2['data'] as $k => $v) {
				// ���������� '������� ������...' ��� ���� �������
				$v['office_map'] = 'change';
				$v['office_link'] = $sign['change'];
				$f2['data'][$k]	= $v;
			}
		}
		if ($config['template_map_inside'] == 'Y' && ($config['template_block_type'] != 'bookmark2' || $active_bookmark == 'show')) {
			if ($tariff_count == 1 && $office_count == 1) {
				// ��������� ������, ����� ��� ������ + ���������� ���������� �����
				foreach ($f2['data'] as $k => $v) $f2['data'][$k]['checked_inside'] = true;
				$config['template_map_inside'] = 'tariff';
			}
			else {
				// ����� ���������� �����, ���� ������� ��������������� �����
				foreach ($f2['data'] as $k => $v) if (isset($v['office_id'])) {
					unset($v['office_id']);
					$v['profile'] = $v['office_mode'];
					$v['id'] = $v['office_address_full'] = $v['office_detailed'] = '';
					$f2['data'][$k] = $v;
				}
			}
		}

		// ������������ ��������� ��� ��� ��������� ������
		$pricehead = $day = false;
		foreach ($f2['data'] as $k => $v) if (isset($v['pricehead'])) {
			$pricehead = self::AddRange($pricehead, $v['pricehead']);
			$day = self::AddRange($day, $v['dayhead']);
			unset($f2['data'][$k]['pricehead']);
			unset($f2['data'][$k]['dayhead']);
		}
		$f2['pricehead'] = $pricehead;
		$f2['day'] = $day;
		if ($ico !== false) $f2['ico'] = $ico;

		$format['office'] = $f2;


		// ����������� ����� � ����� ������ 'general'
		$count_format = 0;
		$count_tariff = 0;
		$count = 0;
		$auto = false;
		if ($config['template_block'] == 'auto2') {
			$n = ($bookmark ? 1 : 2);
			foreach ($format as $f_key => $f) if (!in_array($f_key, array('general')) && count($f['data']) > $n) $auto = true;
		}
		foreach ($format as $f_key => $f) if (!empty($f['data'])) {
			$count_format++;
			$count_tariff += count($f['data']);
		}
		foreach ($format as $f_key => $f) if (!empty($f['data'])) {
			if ($f_key == 'general') {
				$format[$f_key]['pack'] = 'normal';
				$count++;
			}
			else if ($config['COMPACT'] == 'off' && empty($config['CATALOGDELIVERY_INSIDE']) && ($count_format == 1 && $count_tariff <= 2 || $config['template_format'] == 'off' || $config['template_block'] == 'off' || ($config['template_block'] == 'auto1' && count($f['data']) <= 2) || ($config['template_block'] == 'auto2' && !$auto))) {
				$format[$f_key]['pack'] = 'head';
				$count++;
			}
		}


		if ($config['template_block'] == 'off' || $count_format == 1 && !($config['template_map_inside'] == 'Y' && !empty($format['office']['data'])) || $count > 1) {
			$f2 = $format['general'];
			$f2['data'] = array();
			foreach ($format as $f_key => $f) if (isset($f['pack'])) {
				if ($f['pack'] == 'normal') $data = $f['data'];
				else if ($f['pack'] == 'head') {
					$data = array();

					foreach ($f['data'] as $k => $v) if (isset($v['id'])) {
						self::FormatHead($v, $f['name'], true, $template_2019);
						$data[] = $v;
					}
				}

				if (count($f2['data']) != 0 && $config['template_format'] != 'off') $f2['data'][] = array('delimiter' => true);
				$f2['data'] = array_merge($f2['data'], $data);
				$format[$f_key]['data'] = array();
			}
			$format['general'] = $f2;
		}
//		self::draw_data('format', $format);


		// ������� ����������� ������� � ������
		$cod = false;
		if ($config['template_cod'] != 'off') foreach ($format as $f_key => $f) foreach ($f['data'] as $v) if (isset($v['pricecod'])) {
			$format[$f_key]['cod'] = true;
			$cod = true;
			break;
		}


		// ������� �������������� ��� ����� "�� ��������"
		if (!empty($format['house']['data'])) {
			$f = $format['house'];
			$c = ($template_2019 ? '_compact' : '');

			$count = count($f['data']);
			$count_priceinfo = 0;
			foreach ($f['data'] as $v) if (!empty($v['priceinfo'])) $count_priceinfo++;

			$p = -1;
			foreach ($f['data'] as $v) {
				if (empty($v['priceinfo'])) $p = -1;
				else if ($p < 0) {
					$p = $v['price'];
					$p_formatted = $v['price_formatted'];
				}
				else if ($p != $v['price']) $p = -1;
				if ($p < 0) break;
			}

			// ����� �������������� � ���������
			$f['warning'] = $sign['house_warning'];
			if ($count == $count_priceinfo) {
				$f['warning'] .= ($f['warning'] != '' ? '<br>' : '').$sign['priceinfo_warning'.$c];
				if ($p > 0) $f['description'] = str_replace('%price%', $p_formatted, $sign['priceinfo_description'.$c]);
			}

			// �������������� � �������
			foreach ($f['data'] as $k => $v) if (!empty($v['priceinfo'])) {
				if ($count != $count_priceinfo) $v['warning'] = $sign['priceinfo_warning'.$c];
				if (!$template_2019 && $p < 0 && $v['price'] > 0) $v['description'] = str_replace('%price%', $v['price_formatted'].(isset($v['price_original']) ? ' <span class="edost_price_original">('.$v['price_original_formatted'].')</span> ' : ''), $sign['priceinfo_description'.$c]).($v['description'] != '' ? '<br>' : '').$v['description'];
				$f['data'][$k] = $v;
			}

			$format['house'] = $f;
		}


		// ����������
		if (!$sorted) self::SortTariff($format['general']['data'], $config);


		// ������� ����� (����� ������ ��� ��������� "��������� �������� ����� ������������� �����")
		if ($zero_tariff && (!$cod_filter || $cod_filter_zero_tariff)) {
			$count = 0;
			$count_edost = 0;
			foreach ($format as $f_key => $f) foreach ($f['data'] as $v) if (isset($v['id'])) {
				$count++;
				if ($v['automatic'] == 'edost') $count_edost++;
			}

			if ($count == 0 && (!empty($shop_data) || !empty($config['ADD_ZERO_TARIFF'])) || $config['hide_error'] != 'Y' && ($office_error !== false || $edost_enabled && $count_edost == 0)) {
				$error = '';
				if ($config['hide_error'] != 'Y') {
					if (isset(self::$result['error'])) $error = self::GetError(self::$result['error']);
					else if ($office_error !== false) $error = self::GetError($office_error, 'office');
					else if ($count == 0) $error = self::GetError(0);
				}

				$tariff = self::GetZeroTariff($config);
				if (!empty($tariff)) {
					$v = array(
						'id' => $tariff['id'],
						'automatic' => 'edost',
						'title' => $tariff['name'],
						'profile' => 0,
						'name' => '',
						'company' => '',
						'description' => $tariff['description'],
						'error' => $error,
						'price' => 0,
						'ico' => 0,
						'cod_tariff' => false,
					);
					if ($config['template_ico'] == 'C') $v['company_ico'] = 0;
					else if (!empty($tariff['image'])) $v['image'] = $tariff['image'];
					if ($tariff['id'] == $active_id) {
						$active = $v;
						$v['checked'] = true;
					}
					$format['general']['data'][] = $v;
				}
			}
		}


		// �������������� ��������� ��� ��������� + ����� ������ �������� ������ � ������
		foreach ($format as $f_key => $f) if (!empty($f['data']) && !isset($f['pricehead'])) {
			self::FormatRange($f, $config['template_cod'] != 'off' ? true : false);
			$format[$f_key] = $f;
		}
//		self::draw_data('format', $format);


		// ����� ��������� ��������, ���� ������ ���������� + ����� ��������� ������, ���� ������� ������ ������
		if ($config['template_block_type'] == 'bookmark1' && $active_bookmark != '') {
			if (empty($format[$active_bookmark]['data'])) $active_bookmark = '';
			else if ($active !== false) foreach ($format as $f_key => $f) if (!empty($f['data']) && $f_key != $active_bookmark) {
				foreach ($f['data'] as $k => $v) if (isset($v['checked'])) {
					unset($format[$f_key]['data'][$k]['checked']);
					$active_id = '';
					$active = false;
					break;
				}
				if ($active === false) break;
			}
		}

		// ����� ��������� ��������, ���� ������ ���������� + ��������� �������� "������..."
		if ($config['template_block_type'] == 'bookmark2') {
			$bookmark_show = false;
			foreach ($format as $f_key => $f) if (!empty($f['data']) && (count($f['data']) > 1 || isset($f['office_count']) && $f['office_count'] > 1 || $f_key == 'general')) { $bookmark_show = true; break; }
			if ($active_bookmark != '' && $active_bookmark != 'show' && empty($format[$active_bookmark]['data']) || $active_bookmark == 'show' && !$bookmark_show) $active_bookmark = '';
		}


		// ��������� ����������, ���� �������� ������ ���� �����
		if ($active === false && $config['autoselect'] != 'Y') {
			$count_all = 0;
			foreach ($format as $f_key => $f) if (!empty($f['data'])) {
				$count = 0;
				foreach ($f['data'] as $k => $v) if (isset($v['id'])) $count++;
				$count_all += $count;
				if ($config['template_block_type'] == 'bookmark1' && $f_key == $active_bookmark && $count == 1) $config['autoselect'] = 'Y';
			}
			if ($count_all == 1) $config['autoselect'] = 'Y';
		}

		// ����� ������ ��������, ���� ������ �� �������
		$key = false;
		if ($active === false && $config['template_block_type'] == 'bookmark2') {
			if ($active_bookmark == '' && $config['autoselect'] == 'Y') foreach ($format as $f_key => $f) if (!empty($f['data']) && $f_key != 'general') { $active_bookmark = $f_key; break; }
			if (!empty($format[$active_bookmark]['data'])) $key = array($active_bookmark, $format[$active_bookmark]['min']['key']);
		}
		if ($active === false && $key === false && $config['autoselect'] == 'Y') {
			$i = false;
			if ($config['template_block_type'] == 'bookmark1' && !empty($format[$active_bookmark]['data'])) $i = $active_bookmark;
			else foreach ($format as $f_key => $f) if (!empty($f['data'])) { $i = $f_key; break; }
			if ($i !== false) foreach ($format[$i]['data'] as $k => $v) if (isset($v['id']) && empty($v['compact_cod_copy']) && ($config['template_map_inside'] != 'tariff' || !isset($v['office_mode']) || !empty($v['checked_inside']))) { $key = array($i, $k); break; }
		}
		if ($key !== false) {
			$active = $format[$key[0]]['data'][$key[1]];
			$active_id = $active['profile'];
			$format[$key[0]]['data'][$key[1]]['checked'] = true;
		}


		// �������� ����� ������� � ���� ����� ������
		$data = array();
		$day = false;
		$count_tariff = 0;
		$count_bookmark = 0;
		$count_bookmark_cod = 0;
		$supercompact_format = false;
		if ($bookmark) foreach ($format as $f_key => $f) if (!empty($f['data']) && $f_key != 'general') {
			$count_bookmark++;
			if ($config['template_block_type'] == 'bookmark1' && isset($f['cod']) || isset($f['min']['pricecash'])) $count_bookmark_cod++;
		}
		foreach ($format as $f_key => $f) if (!empty($f['data'])) {
			if ($supercompact_format === false) $supercompact_format = $f_key;
			else { $supercompact_format = false; break; }
		}
		if ($cod_filter) foreach ($format as $f_key => $f) if (!empty($f['data'])) foreach ($f['data'] as $k => $v) if (!empty($v['insurance'])) $format[$f_key]['data'][$k]['insurance'] = 0;
		foreach ($format as $f_key => $f) if (!empty($f['data'])) {
			if ($f_key == 'general' && count($data) == 0) $head = '';
			else if ($count_bookmark > 1 && ($config['template_block_type'] != 'bookmark2' || $f_key != 'general')) $head = (isset($sign['bookmark'][$f_key]) ? $sign['bookmark'][$f_key] : '');
			else $head = (isset($f['head']) ? $f['head'] : $f['name']);

			$insurance = (!in_array($f_key, array('office', 'general')) && self::FormatInsurance($f) ? $sign['insurance_head'] : ''); // ����� ������� "��������� �������� �� ��� ������"

			$compact = false;
			$compact_cod = false;
			$supercompact = false;
			$compact_link = false;
			if ($config['COMPACT'] != 'off') {
				$compact = $f['min']['key'];
				if (!empty($_SESSION['EDOST']['compact_tariff'][$f_key])) foreach ($f['data'] as $k => $v) if (isset($v['id']) && $v['id'] == $_SESSION['EDOST']['compact_tariff'][$f_key]) $compact = $k;

				$min = false;
				$count_cod = 0;
				foreach ($f['data'] as $k => $v) if (isset($v['pricecash'])) {
					if (empty($v['cod_hide'])) $count_cod++;
					if ($min === false || $v['pricecash'] < $f['data'][$min]['pricecash']) $min = $k;
				}
				if ($min !== false) $compact_cod = $min;

				$office_change = false;
				$tariff_count = 0;
				foreach ($f['data'] as $k => $v) if (isset($v['id'])) {
					if (empty($v['compact_cod_copy'])) {
						$tariff_count++;
						if ($f_key == 'office' && !empty($v['office_map'])) $office_change = true;
					}
					if (!empty($v['checked'])) {
						$_SESSION['EDOST']['compact_tariff'][$f_key] = $v['id'];
						$compact = $supercompact = $k;
					}
				}
				$compact_link = ($tariff_count > 1 || $office_change ? true : false);
			}

			$ar = array();
			foreach ($f['data'] as $k => $v) {
				if ($config['COMPACT'] != 'off' && $f_key != 'general') self::FormatHead($v, $f['name'], $f_key == 'office' ? false : true, true, $config);

				if (isset($v['profile']) || isset($v['price'])) {
					$count_tariff++;
					if (!empty($v['checked']) && $active_bookmark == '') $active_bookmark = $f_key;
					$v['html_id'] = self::GetHtmlID($v);
					$v['html_value'] = self::GetHtmlValue($v);
					if ($config['template'] == 'Y') $v['name'] = self::RenameTariff($v['name'], $rename['name']);
					$v['insurance'] = (isset($v['insurance']) && $v['insurance'] == 1 ? $sign['insurance'] : '');
					if (empty($v['priceinfo']) && isset($v['price']) && $v['price'] == 0 && !isset($v['error'])) $v['free'] = $sign['free'];
					if (isset($v['pricecod']) && $v['pricecod'] == 0) $v['cod_free'] = $sign['free'];
					if (!empty($v['day'])) $day = true;

					if (!empty($v['pricecod'])) $v += self::GetPrice('codplus', $v['pricecod'] - $v['price']); // ��������� ������ ������� �� ������� 'codplus'

					if ($compact === $k || $compact_cod === $k || $f_key == 'general') {
						if (!empty($v['pricecod_original'])) $v += self::GetPrice('codplus_original', $v['pricecod_original'] - $v['price_original'], ''); // ��������� ������ ������� �� ������� 'codplus'
						if ($compact === $k || $f_key == 'general') {
							$v['compact'] = true;
							if ($supercompact === $k) $v['supercompact'] = true;
						}
						if ($compact_link && $f_key != 'general') {
							$v['compact_head'] = $sign['compact_head'][$v['format']];
							$v['compact_link'] = (!empty($v['office_map']) && $f['office_count'] != 1 ? $sign['compact_office_'.$v['office_map']] : $sign['change_company']);
							if ($count_cod > 1 || $f_key == 'office' && !empty($r['bonus']['cod']['office_count']) && $r['bonus']['cod']['office_count'] != 1) {
								$v['compact_link_cod'] = (!empty($v['office_map']) && $f['office_count'] != 1 ? $sign['compact_office_'.$v['office_map']] : $sign['change_company']);
								$v['compact_head_cod'] = $v['compact_head'];
							}
						}
						if ($compact_cod === $k) $v['compact_cod'] = true;
					}

					if ($template_2019) {
						$s = $sign['cod_warning'];
						if ($config['PRIORITY'] == 'C') {
							if ($cod_filter && isset($v['pricecash']) && !empty($v['transfer']) && !empty($v['checked'])) $v['note'] = str_replace(array('%pricecash%', '%transfer%'), array($v['pricecash_formatted'], $v['transfer_formatted']), $s[!empty($v['pricecash']) ? 'full' : 'transfer'][0]);
						}
						else if (isset($v['pricecash'])) $v += edost_class::GetPrice('pricecashplus', $v['pricecash'] - $v['price']);

						if (!empty($v['price']) && !empty($v['priceinfo']) && !empty($v['checked'])) $v['note'] = str_replace(array('%price%', '%priceinfo%'), array($v['price_formatted'], $v['priceinfo_formatted']), $sign['priceinfo_warning_compact2']);
					}
				}
				$ar[] = $v;
			}

			$data[$f_key] = array(
				'head' => $head,
				'cod' => (isset($f['cod']) ? true : false),
				'description' => (isset($f['description']) ? $f['description'] : ''),
				'warning' => (isset($f['warning']) ? $f['warning'] : ''),
				'insurance' => $insurance,
				'tariff' => $ar,
			);
			if ($config['template_block_type'] == 'bookmark1') {
				if ($f['pricehead']['min']['value'] == -1) $data[$f_key]['price_formatted'] = '';
				else {
					$data[$f_key]['price_formatted'] = self::GetRange($f['pricehead']);
					if (empty($data[$f_key]['price_formatted'])) $data[$f_key]['free'] = $sign['free'];

					// ����������� ������� ��� �������� ������
					if (empty($f['pricehead']['min']['value'])) $data[$f_key]['short']['free'] = $sign['free'];
					else $data[$f_key]['short']['price_formatted'] = ($f['pricehead']['min']['value'] != $f['pricehead']['max']['value'] ? $sign['from'] : '') . $f['pricehead']['min']['formatted'];
				}

//				if (!empty($f['day'])) $data[$f_key]['short']['day'] = self::GetDay(round(($f['day']['min']['value'] + $f['day']['max']['value'])/2));
				if (!empty($f['day'])) $data[$f_key]['short']['day'] = self::GetDay($f['day']['min']['value'], $f['day']['max']['value']);
				if (!empty($f['ico'])) $data[$f_key]['short']['ico'] = $f['ico'];
			}
			if (in_array($config['template_block_type'], array('bookmark2')) || $config['COMPACT'] != 'off') {
				if ($f['min']['price'] == 0) $f['min']['free'] = $sign['free'];

				if ($f_key == 'office') {
					$k = $f['min']['key'];
					$ar = array('office_map', 'office_link', 'office_mode', 'office_type', 'office_options', 'office_address');
					foreach ($ar as $v) if (isset($f['data'][$k][$v])) $f['min'][$v] = $f['data'][$k][$v];
				}

				$data[$f_key]['min'] = $f['min'];
			}
		}

		if ($config['template_block_type'] == 'bookmark2' && $count_bookmark > 1 && $bookmark_show) $data['show'] = array('head' => $sign['bookmark']['show']); // ���������� ������ 'show' (�������� ��� ������)

		$r['data'] = $data;
		$r['count'] = $count_tariff;
		$r['cod'] = ($count_tariff == 1 || $config['template_cod'] != 'td' ? false : $cod); // ���� ������ � ���������� �������� � ������� ����� � ��������� �������
		$r['cod_bookmark'] = ($config['template_cod'] != 'off' && $count_bookmark > 1 && $count_bookmark != $count_bookmark_cod ? true : false); // ����������� � ��������� "+ �������� ������ ��� ���������"
		$r['cod_tariff'] = ($config['template_cod'] == 'tr' ? true : false); // ������� ����� ����������� ������� ��������� �������
		$r['priceinfo'] = $priceinfo; // ���� ������ � ���������������
		$r['day'] = $day; // ���� ������ �� ������ ��������
		$r['border'] = ($config['template_block_type'] == 'border' && count($data) > 1 ? true : false); // ���� � ��������
		$r['warning'] = self::GetWarning(false, $template_2019 ? false : true);
		if (in_array($config['template_map_inside'], array('Y', 'tariff'))) $r['map_inside'] = $config['template_map_inside'];
		if ($config['COMPACT'] != 'off') $r['compact'] = $config['COMPACT'];
		$r['priority'] = $config['PRIORITY'];
		$r['company_ico'] = $config['template_ico'];

		if ($supercompact_format !== false) $r['supercompact_format'] = $supercompact_format;

		if (!empty($config['CATALOGDELIVERY_INSIDE'])) $r['bookmark'] = 1;
		else if ($count_bookmark > 1) $r['bookmark'] = str_replace('bookmark', '', $config['template_block_type']); // �������� �������� ��� ������� ������

		$r['active'] = array(
			'id' => (isset($active['id']) ? $active['id'] : ''),
			'profile' => (isset($active['profile']) ? $active['profile'] : ''),
			'cod' => (isset($active['pricecash']) ? true : false),
			'cod_tariff' => (!empty($active['cod_tariff']) ? true : false),
			'bookmark' => $active_bookmark,
			'name' => (isset($active['name_save']) ? $active['name_save'] : ''),
		);
		if (isset($active['office_type'])) $r['active']['office_type'] = $active['office_type'];
		if (isset($active['office_options'])) $r['active']['office_options'] = $active['office_options'];
		if (isset($active['office_id'])) $r['active']['office_id'] = $active['office_id'];
		if (isset($active['office_address_full'])) $r['active']['address'] = $active['office_address_full'];

		if (isset($active['pricecash'])) {
			$r['active'] += edost_class::GetPrice('pricecashplus', $active['pricecash'] - $active['price']);
			if (isset($active['transfer'])) {
				$r['active']['transfer'] = $active['transfer'];
				$r['active']['transfer_formatted'] = $active['transfer_formatted'];
				if (!empty($active['transfer']) && !empty($r['active']['pricecashplus'])) $r['active'] += edost_class::GetPrice('codtotal', $r['active']['pricecashplus'] + $active['transfer']);
				if ($template_2019 && $config['PRIORITY'] != 'C')
					if (!empty($active['transfer'])) {
						$s = $sign['cod_warning'];
						$r['active']['cod_note'] = str_replace(array('%pricecashplus%', '%transfer%'), array($r['active']['pricecashplus_formatted'], $r['active']['transfer_formatted']), $s[!empty($r['active']['pricecashplus']) ? 'full' : 'transfer'][1]);
					}
					else if (!empty($config['SHOW_COD_NOTE']) && !empty($r['active']['pricecashplus'])) $r['active']['cod_note'] = str_replace('%pricecashplus%', $r['active']['pricecashplus_formatted'], $s['pricecash'][1]);
			}
		}

//		self::draw_data('format result', $r);

		return $r;

	}


	// ���� ��� ������ � ������ �� ����������, ����� �������� 'insurance' ��������� � ������������ true
	public static function FormatInsurance(&$f) {

		$n = count($f['data']);
		if ($n <= 1) return false;

		$i = 0;
		foreach ($f['data'] as $v) if (!empty($v['insurance'])) $i++;

		if ($i != $n) return false;
		else {
			foreach ($f['data'] as $k => $v) unset($f['data'][$k]['insurance']);
			return true;
		}

	}


	// ���������� ������� � �������� ������ ($format) �� ��������������� ($format_cod)
	public static function AddCod(&$format, $format_cod, $cod_tariff = false) {

		self::AddCodData($format['data'], $format_cod['data'], $cod_tariff);
		self::array_add($format['active'], $format_cod['active'], self::$cod_key);
		$format['cod'] = $format_cod['cod'];

		if (!empty($format['map_data']) && !empty($format_cod['map_data']))	{
			self::AddCodData($format['map_data']['format'], $format_cod['map_data']['format'], $cod_tariff, 'data');
			$format['map_json'] = self::GetOfficeJson($format['map_data']);
		}

	}

	// ���������� ������� ������� � �������� ������ ($format) �� ��������������� ($format_cod)
	public static function AddCodData(&$format, $format_cod, $cod_tariff = false, $tariff_key = 'tariff') {

		$s = $s2 = array();
		foreach ($format as $f_key => $f) if (!empty($f[$tariff_key])) foreach ($f[$tariff_key] as $k => $v) $s[$v['id'].(!empty($v['cod_tariff']) ? '_Y' : '')] = array($f_key, $k);
		foreach ($format_cod as $f_key => $f) if (!empty($f[$tariff_key])) foreach ($f[$tariff_key] as $k => $v) $s2[$v['id'].(!empty($v['cod_tariff']) ? '_Y' : '')] = array($f_key, $k);

		if ($cod_tariff) {
			foreach ($s as $k => $v) if (!isset($s2[$k])) unset($format[$v[0]][$tariff_key][$v[1]]);
			else if (!empty($format[$v[0]][$tariff_key][$v[1]]['cod_tariff'])) {
				$v2 = $s2[$k];
				$format[$v[0]][$tariff_key][$v[1]] = $format_cod[$v2[0]][$tariff_key][$v2[1]];
			}
		}
		else {
			foreach ($s as $k => $v) if (isset($s2[$k])) {
				$v2 = $s2[$k];
				self::array_add($format[$v[0]][$tariff_key][$v[1]], $format_cod[$v2[0]][$tariff_key][$v2[1]], self::$cod_key);
			}
			foreach ($format as $f_key => $f) if (!empty($format_cod[$f_key])) $format[$f_key]['cod'] = $format_cod[$f_key]['cod'];
		}

	}


	// �������� � json ������� ������
	public static function GetOfficeJson($param) {

		$rename = self::GetMessage('EDOST_DELIVERY_RENAME');

		$point = array();
		foreach ($param['office'] as $k => $v) $point[] = '{"company_id": "'.$k.'", "data": '.self::GetJson($v, array('id', 'name', 'address', 'schedule', 'gps', 'type', 'metro', 'codmax', 'detailed', 'code', 'options')).'}';

		$tariff = array();
		foreach ($param['format'] as $f_key => $f) if (!empty($f['data']) && (isset($f['office_count']) || $f_key == 'general')) {
			self::FormatInsurance($f); // �������� '�� ����������', ���� � ������ ��� ������ �� ����������
			if (!$param['sorted']) self::SortTariff($f['data'], $param['config']);
			foreach ($f['data'] as $k => $v) if (isset($v['format']) && in_array($v['format'], self::$office_key)) {
				if ($f_key == 'general') {
					$v['name'] = '';
					$v['insurance'] = '';
				}
				if ($param['config']['template_cod'] == 'tr') $v['cod_tariff'] = ($v['cod_tariff'] ? 'Y' : 'N'); else $v['cod_tariff'] = '';
				$v['profile'] = $v['profile'].'_'.$v['id'];
//				$v['price'] = $v['pricetotal'];
//				$v['price_formatted'] = $v['pricetotal_formatted'];
				if (isset($v['pricecod'])) $v += self::GetPrice('codplus', $v['pricecod'] - $v['pricetotal']); // �� ����� ��������� ������ ������� �� ������� 'codplus'
				$v['company'] = self::RenameTariff($v['company'], $rename['company']);
				$tariff[] = $v;
			}
		}

		return '"city": "'.self::site_charset($param['location']['city']).'", '.
				'"region": "'.$param['location']['region'].', '.$param['location']['country'].'", '.
				'"point": ['.implode(', ', $point).'], '.
				'"tariff": '.self::GetJson($tariff, array('profile', 'company', 'name', 'tariff_id', 'pricetotal', 'pricetotal_formatted', 'pricecash', 'codplus', 'codplus_formatted', 'day', 'insurance', 'to_office', 'company_id', 'format', 'cod_tariff', 'ico', 'format_original', 'pricetotal_original_formatted', 'pricecod', 'pricecod_formatted', 'pricecod_original_formatted'));

	}


	// �������� � json �� �������� ������
	public static function GetJson($data, $key, $array = true, $pack = true) {

		if (!$array) $data = array($data);
		else if (!is_array($data) || count($data) == 0) return '[]';

		if ($key === false) foreach ($data as $v) { $key = array_keys($v); break; }

		$s = array();
		foreach ($data as $v) {
			$s2 = array();
			if ($pack) {
				foreach ($key as $v2) $s2[] = (isset($v[$v2]) ? str_replace(array('"', "'"), array('', ''), $v[$v2]) : '');
				$s[] = '"'.implode('|', $s2).'"';
			}
            else {
				foreach ($v as $k2 => $v2) if (in_array($k2, $key))
					if (!is_array($v2)) $s2[] = '"'.$k2.'": "'.str_replace(array('"', "\t"), array('\"', ' '), $v2).'"';
					else if ($k2 === 'size') $s2[] = '"'.$k2.'": ['.implode(',', $v2).']';
					else $s2[] = '"'.$k2.'": '.self::GetJson($v2, false, true, false);
				$s[] = '{'.implode(', ', $s2).'}';
			}
		}

		if (!$array) return $s[0];
		else return '['.implode(', ', $s).']';

	}


	// ������ ������������ ������� (1,2,... : 3,4,... : ...)
	public static function ParseArray($array, $id, &$data, $level = 0) {

		if (in_array($id, array('field', 'control'))) $array = self::site_charset(substr($array, 0, 10000));
		else $array = preg_replace("/[^0-9.:,;-]/i", "", substr($array, 0, 1000));
		if ($array == '') return;

		if ($id == 'priceoffice') $key = array('type', 'price', 'priceinfo', 'pricecash', 'priceoriginal');
		else if ($id == 'priceoriginal') $key = array('price', 'pricecash');
		else if ($id == 'limit') $key = array('company_id', 'type', 'weight_from', 'weight_to', 'price', 'size1', 'size2', 'size3', 'sizesum');
		else if ($id == 'field') $key = array('name', 'value');
		else if ($id == 'control') $key = array('id', 'count', 'site');
		else return;

		$key_count = count($key);
		$default = array_fill_keys($key, 0);
		if ($id == 'priceoffice') {
			$default['pricecash'] = -1;
			unset($default['priceoriginal']);
		}
		if ($id == 'priceoriginal') unset($default['pricecash']);

		$r = array();
		$delimiter = self::$delimiter[$level];
		$array = explode($delimiter[1], $array);
		foreach ($array as $v) {
			$v = explode($delimiter[0], $v);
			if ($v[0] == '' || !isset($v[1]) && $id != 'priceoriginal') continue;

			$ar = $default;
			foreach ($v as $k2 => $v2) if ($k2 < $key_count && $v2 !== '')
				if ($key[$k2] == 'priceoriginal') self::ParseArray($v2, $key[$k2], $ar, 1);
				else $ar[$key[$k2]] = str_replace(array('%c', '%t'), array(',', ':'), $v2);

			if ($id == 'priceoriginal') $r = $ar;
			else if (in_array($id, array('priceoffice', 'control'))) $r[$v[0]] = $ar;
			else $r[] = $ar;
		}
		if (!empty($r)) $data[$id] = $r;

	}

	// ������ ������ �������
	public static function ParseData($data, $type = 'delivery') {

		if ($type == 'delivery') $key = array('id', 'price', 'priceinfo', 'pricecash', 'priceoffice', 'transfer', 'day', 'insurance', 'company', 'name', 'format', 'company_id', 'priceoriginal');
		else if ($type == 'document') $key = array('id', 'data', 'data2', 'name', 'size', 'quantity', 'mode', 'cod', 'delivery', 'length', 'space');
		else if ($type == 'office') $key = array('id', 'code', 'name', 'address', 'address2', 'tel', 'schedule', 'gps', 'type', 'metro', 'options');
		else if ($type == 'location') $key = array('city', 'region', 'country');
		else if ($type == 'location_street') $key = array('street', 'zip', 'city');
		else if ($type == 'location_zip') $key = array('zip');
		else if ($type == 'location_robot') $key = array('ip_from', 'ip_to');
		else if ($type == 'control') $key = array('id', 'flag', 'tariff', 'tracking_code', 'status', 'status_warning', 'status_string', 'status_info', 'status_date', 'status_time', 'day_arrival', 'day_delay', 'day_office', 'register', 'batch');
		else if ($type == 'detail') $key = array('status', 'status_warning', 'status_string', 'status_info', 'status_date', 'status_time');
		else if ($type == 'tracking') $key = array('id', 'tariff', 'example', 'format');

		else if ($type == 'param') $key = array();
		else return array('error' => 4);

		$r = array();
		$key_count = count($key);
		$data = explode('|', $data);

		// ����� ���������: error=2;warning=1;sizetocm=1;...
		$p = explode(';', $data[0]);
		foreach ($p as $v) {
			$s = explode('=', $v);
			$s[0] = preg_replace("/[^0-9_a-z]/i", "", substr($s[0], 0, 20));
			if (isset($s[1]) && $s[0] != '')
				if ($s[0] == 'limit') self::ParseArray($s[1], 'limit', $r);
				else if ($s[0] == 'field') self::ParseArray($s[1], 'field', $r);
				else if ($s[0] == 'control') self::ParseArray($s[1], 'control', $r);
				else if ($s[0] == 'warning') $r[$s[0]] = explode(':', $s[1]);
				else $r[$s[0]] = $s[1];
		}

		if (isset($r['error']) || $key_count == 0) return $r;

		$r['data'] = array();
		$array_id = '';
		$sort = 0;
		foreach ($data as $k => $v) if ($k == 0 || $v == 'end') {
			if ($k != 0 && isset($parse[$key[0]]) && ($key_count == 1 || isset($parse[$key[1]]))) {
				$sort++;
				if ($type == 'delivery') {
					$profile = $parse['id']*2 + ($parse['insurance'] == 1 ? 0 : -1);
					$parse['profile'] = $profile;
					$parse['sort'] = $sort*2;
					if ($profile > 0) $r['data'][$profile] = $parse;
				}
				else if ($array_id !== '') $r['data'][$array_id][$parse['id']] = $parse;
				else if (isset($parse['id'])) $r['data'][$parse['id']] = $parse;
				else $r['data'][] = $parse;
			}
			$i = 0;
			$parse = array();
		}
		else if ($v === 'key') $array_id = 'get';
		else if ($array_id === 'get') $array_id = $v;
		else if ($i < $key_count) {
			$p = $key[$i];
			$i++;

			if ($type == 'delivery') {
				if (in_array($p, array('day', 'company', 'name'))) $v = self::site_charset(substr($v, 0, 80));
				else if (in_array($p, array('price', 'priceinfo', 'pricecash', 'transfer'))) {
					$v = preg_replace("/[^0-9.-]/i", "", substr($v, 0, 11));
					if ($v === '') $v = ($p == 'pricecash' ? -1 : 0);
				}
				else if (in_array($p, array('id', 'insurance'))) $v = intval($v);
				else if ($p == 'company_id') $v = preg_replace("/[^a-z0-9]/i", "", substr($v, 0, 3));
				else if ($p == 'format') $v = preg_replace("/[^a-z]/i", "", substr($v, 0, 10));
				else if ($p == 'priceoffice') {
					self::ParseArray($v, $p, $parse);
					continue;
				}
				else if ($p == 'priceoriginal') {
					self::ParseArray($v, $p, $parse);
					continue;
				}
			}

			if ($type == 'document') {
				if ($p == 'insurance' || $p == 'cod') $v = ($v == 1 ? true : false);
				else if ($p == 'delivery') $v = ($v != '' ? explode(',', $v) : false);
				else if ($p == 'size') $v = explode('x', $v);
				else if ($p == 'length' || $p == 'space') {
					$v = explode(',', $v);
					$o = array();
					foreach ($v as $s) if ($s != '') {
						$s = explode('=', $s);
						if ($s[0] != '') $o[$s[0]] = (isset($s[1]) ? intval($s[1]) : 0);
					}
					$v = $o;
				}
			}

			if ($type == 'office') {
				if ($p == 'type') $v = intval($v);
				else if (in_array($p, array('id', 'gps'))) $v = preg_replace("/[^a-z0-9.,]/i", "", substr($v, 0, 30));
				else $v = self::site_charset(trim(substr($v, 0, 160)));
			}

			if ($type == 'location') {
				if ($p == 'country' || $p == 'region') $v = intval($v);
				else $v = self::site_charset(substr($v, 0, 160));
			}

			if ($type == 'location_street') {
				if (in_array($p, array('street', 'city'))) $v = self::site_charset(substr($v, 0, 160));
			}

			if ($type == 'location_zip') {
				$v = preg_replace("/[^0-9]/i", "", substr($v, 0, 6));
			}

			if ($type == 'location_robot') {
				$v = preg_replace("/[^0-9.]/i", "", substr($v, 0, 15));
			}

			if ($type == 'control' || $type == 'detail') {
				if (in_array($p, array('id', 'flag', 'status', 'tariff', 'status_warning', 'day_arrival', 'day_delay', 'day_office'))) $v = intval($v);
				else if ($p == 'batch') {
					$v = self::UnPackDataArray($v, 'batch');
					if (!empty($v['date']) && !empty($v['number'])) $parse['batch_code'] = $v['date'].'_'.$v['number'];
				}
				else $v = self::site_charset(substr($v, 0, 500));
			}

			if ($type == 'tracking') {
				if (in_array($p, array('company_id'))) $v = intval($v);
				else if ($p == 'tariff') $v = explode(',', $v);
				else $v = self::site_charset(substr($v, 0, 500));
			}

			$parse[$p] = $v;
		}

		return $r;

	}


	// ��������� id ������ ��� html
	public static function GetHtmlID($v) {
		if ($v['automatic'] == 'edost') {
			if (isset($v['office_mode'])) $s = $v['office_mode'];
			else $s = $v['profile'].($v['cod_tariff'] ? '_Y' : '');
			return 'edost_'.$s;
		}
		if ($v['automatic'] !== $v['id']) return $v['id'];
		return $v['automatic'].'_'.$v['profile'];
	}
	// ��������� value ������ ��� html
	public static function GetHtmlValue($v) {
		if ($v['automatic'] == 'edost') {
			$value = 'edost:'.$v['profile'].($v['id'] != '' && $v['automatic'] !== $v['id'] ? '_'.$v['id'] : '');
			if (isset($v['office_id']) || $v['cod_tariff']) $value .=  ':'.(isset($v['office_id']) ? $v['office_id'] : '').':'.($v['cod_tariff'] ? 'Y' : '');
		}
		else $value = ($v['automatic'] !== $v['id'] ? $v['id'] : $v['automatic'].':'.$v['profile']);
		return $value;
	}
	// ��������� title ������
	public static function GetTitle($v, $full = false) {
		$r = ($full && isset($v['head']) && !isset($v['company_head']) ? $v['head'] : $v['company']);
		$s = $v['name'];
		if ($full) $s .= ($s != '' && $v['insurance'] != '' ? ' ' : '').$v['insurance'];
		return $r.($s != '' ? ' ('.$s.')' : '');
	}

	// ������ �������� �� �������� �������� � ����� + �������� ������ '<br>' � �������� + �������� '�� ����������'
	public static function ParseName($s, $company = '', $description = '', $insurance = '') {

		$r = array('name' => '');

		$o = $s;
		if ($insurance != '') $s = str_replace($insurance, '', $s);
		if ($company != '' && strpos($s, $company) !== false) $company = '';
		if ($company != '') {
			$r['company'] = trim($company);
			$r['name'] = trim($s);
		}
		else {
			$s = explode('(', $s);
			$r['company'] = trim($s[0]);
			if (isset($s[1])) {
				$s = explode(')', $s[1]);
				$r['name'] = trim($s[0]);
			}

			// ������������ �������� ������
			$o = explode('(', $o);
			if (isset($o[1])) {
				$o = explode(')', $o[1]);
				$r['name_original'] = trim($o[0]);
			}
		}

		$s = trim($description);
		if ($s === '<br>' || $s === '<br />') $s = '';
		$r['description'] = $s;

		return $r;

	}


	// ��������� ��������� ������ � ������� � ����������������� ���� � �������� ������ ($key == 'value' - ������������ ������ ��������,  $key == 'formatted' - ������������ ������ ����������������� ������)
	public static function GetPrice($key, $price, $currency = 'base') {

		$r = array();
		if ($price == '') $price = 0;

		$r[$key] = $price;

		if (class_exists('edost_currency') && method_exists('edost_currency', 'convert')) $price = edost_currency::convert($price, $currency);

		if ($key != 'value')
			if (class_exists('edost_currency') && method_exists('edost_currency', 'format')) $r[$key.'_formatted'] = ($price == '0' ? '0' : edost_currency::format($price));
			else $r[$key.'_formatted'] = ($price == '0' ? '0' : self::draw_digit($price).' '.self::site_charset('���.', 'utf'));

		if ($key == 'value') return $price;
		if ($key == 'formatted') return $r[$key.'_formatted'];
		return $r;

	}


	// ��������� ����� �������� ���� '5-8 ����'
	public static function GetDay($from = '', $to = '', $name = 'D') {

		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
		$from = intval($from);
		$to = intval($to);
		if (!in_array($name, array('D', 'H', 'M', 'MIN'))) $name = 'D';

		$r = '';
		$n = 0;

		if ($from > 0) {
			$n = $from;
			$r .= $from;
		}
		if ($to > 0 && $to != $from) {
			$n = $to;
			$r .= ($r != '' ? '-' : '').$to;
		}

		if ($n == 0 || !isset($sign['day'])) return '';

		$s = '';
		$ar = $sign['day'];
		if ($n >= 11 && $n <= 19) $s = $ar[$name][2];
		else {
			$n = $n % 10;
			if ($n == 1) $s = $ar[$name][0];
			else if ($n >= 2 && $n <= 4) $s = $ar[$name][1];
			else $s = $ar[$name][2];
		}

		return $r.' '.$s;

	}


	// ���������� �������
	public static function SortTariff(&$data, $config) {

		if (count($data) <= 1) return;

		$sort_max = 0;
		foreach ($data as $k => $v) {
			if (empty($v['sort'])) $data[$k]['sort'] = $v['sort'] = 0;
			if ($v['sort'] > $sort_max) $sort_max = $v['sort'];
		}

		$ar = array();
		foreach ($data as $k => $v) {
			if ($config['sort_ascending'] == 'Y') {
				// �� ��������� ��������
				$i = ((isset($v['price']) ? floatval($v['price']) : 0) + (isset($v['priceinfo']) ? floatval($v['priceinfo']) : 0))*1000 + (!empty($sort_max) ? 5*$v['sort']/$sort_max : 0);
				$ar[] = $i;
			}
			else {
				// �� ���� ����������
				$ar[] = $v['sort'];
			}
		}
		array_multisort($ar, SORT_ASC, SORT_NUMERIC, $data);

	}


	// ��������� ������ ����� (���� ������� $tariff, ����� ����������� ������ ����� � ����������, ����������� ������ � �.�.)
	public static function GetOfficeAddress($office, $tariff = false, $delete = array(), $delimiter = '') {

		$r = '';
		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
		$metro = ($office['metro'] != '' ? $sign['metro'].$office['metro'] : '');
		$r = $office['name'];
		$r .= ($r != '' && $metro != '' ? ', ' : '').$metro;
		$r = ($r != '' ? ' ('.$r.')' : '');

		if ($tariff === false) return $office['address'].$r;

		$shop = (in_array($tariff['company_id'], array('s1', 's2', 's3', 's4')) ? true : false);
		$shop_company_default = (in_array($tariff['company'], $sign['shop_company_default']) ? true : false);

		$c = $office['code'];
		if ($c == '') $c = ($shop ? 'S' : 'T');

		if (in_array($office['type'], self::$postamat)) $head = ($tariff['company_id'] == 72 ? $sign['pochtomat']['name'] : $sign['postamat']['name']);
		else $head = $sign[$tariff['format']];

		$s = array();
		if ($office['tel'] != '' && !in_array('tel', $delete)) $s[] = $sign['tel'].': '.$office['tel'];
		if ($office['schedule'] != '' && !in_array('schedule', $delete)) $s[] = $sign['schedule'].': '.$office['schedule'];
		$s[] = $sign['code'].': '.$c.'/'.$office['id'].'/'.$office['type'].(!empty($office['options']) ? '-'.$office['options'] : '').'/'.$tariff['profile'].(!empty($tariff['cod_tariff']) ? '-Y' : '');
		$s = implode(', ', $s);

		$r = $head.(!$shop_company_default && $tariff['format'] != 'shop' ? ' '.$tariff['company'] : '').': '.$office['address_full'] . $r . ($delimiter != '' ? $delimiter : ', ') . $s;

		return $r;

	}

	// ��������� ������ ����� �� ������ (���������: false - ����� ���,  true - ���� ����, �� ��� ������,  array - ������ �����)
	public static function ParseOfficeAddress($address) {

		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');

		$s = explode(', '.$sign['code'].': ', $address);
		if (empty($s[1])) return false;

		$s1 = explode(':', $s[0]);
		$head = $s1[0];

		$s1 = explode($head.': ', $s[0]);
		$s1 = $s1[1];

		$address = $tel = $schedule = '';
		$ar = array(', '.$sign['schedule'].': ', ', '.$sign['tel'].': ');
		foreach ($ar as $k => $v) {
			$s2 = explode($v, $s1);
			if (!empty($s2[1])) {
				if ($k == 0) $schedule = $s2[1];
				else $tel = $s2[1];
				$s1 = $s2[0];
			}
		}
		$address = $s1;

		$s = explode('/', $s[1]);
		if (empty($s[3])) return true;
		$profile = explode('-', $s[3]);

		$v = explode('-', $s[2]);
		$type = intval($v[0]);
		$options = (!empty($v[1]) ? intval($v[1]) : 0);

		return array(
			'code' => $s[0],
			'id' => preg_replace("/[^0-9A]/i", "", substr($s[1], 0, 20)),
			'type' => $type,
			'options' => $options,
			'profile' => intval($profile[0]),
			'cod_tariff' => (!empty($profile[1]) && $profile[1] == 'Y' ? true : false),
			'head' => $head,
			'address' => $address,
			'tel' => $tel,
			'schedule' => $schedule,
		);

	}


	// ������ �������� �� ������� ������������ $data
	public static function RenameTariff($s, $data) {
		if ($s != '' && isset($data[1])) {
			$i = array_search($s, $data[0]);
			if ($i !== false) $s = $data[1][$i];
		}
		return $s;
	}


	// �������������� ������ ��� ������ � ����� 'general'
	public static function FormatHead(&$v, $head, $add_company = true, $compact = false, $config = false) {

		if (isset($v['head']) || $v['format'] == 'post' && !$compact) return;

		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');

		$v['head'] = $head;

		if ($compact && isset($v['format_original']) && $v['format_original'] == 'shop') {
			$format_data = self::GetMessage('EDOST_DELIVERY_FORMAT');
			$v['head'] = $format_data['shop']['name'];
		}
		if (isset($v['office_type']) && in_array($v['office_type'], self::$postamat)) $v['head'] = $sign['postamat']['head'];

		if ($add_company) {
			$shop_company_default = (in_array($v['company'], $sign['shop_company_default']) ? true : false);
			$a = false;
			if (isset($v['office_count']) && !isset($v['compact'])) $a = true;
			else if ($v['company_id'] != 27 || !$shop_company_default) $v['company_head'] = $sign['delivery_company']; // ����� �������� ������ �������� ��������� ������� (����� ������� ������)
			if ($a && $v['format'] != 'shop' && !$shop_company_default) {
				$rename = self::GetMessage('EDOST_DELIVERY_RENAME');
				$v['head'] .= ' '.self::RenameTariff($v['company'], $rename['company']); // ���������� �������� �������� � ��������� (��� ������� � �������)
			}
		}

		if ($compact) $compact = '_compact';

		// ������� ��������������
		$w = array();
		if ($v['format'] == 'terminal' && isset($v['office_count']) && $v['office_count'] > 1) $w[] = $sign['terminal_warning'];
		if ($v['format'] == 'house') $w[] = $sign['house_warning'];
		if (!empty($v['priceinfo'])) {
			$w[] = $sign['priceinfo_warning'.$compact];
			if (($config === false || $config['COMPACT'] == 'off') && $v['price'] > 0) $v['description'] = str_replace('%price%', $v['price_formatted'], $sign['priceinfo_description'.$compact]).(!empty($v['description']) ? '<br>'.$v['description'] : '');
		}
		if (!empty($w)) $v['warning'] = implode('<br>', $w);

	}


	// ������� ������ �������� �� ������������ ����� � �������
	public static function SetDiscount($value, $price, $price_discount, $sale_discount_cod = '') {

		if ($value == -1) return $value;

		if ($value == $price) $r = $price_discount;
		if ($price == 0) $r = $value;
		else if ($sale_discount_cod == 'P') $r = $value - ($price - $price_discount);
		else if ($sale_discount_cod == 'F' || $sale_discount_cod == '') $r = round($value * $price_discount / $price);
		else $r = $value;

		if ($r < 0) $r = 0;

		return $r;
	}


	// ��������� ��������� ����: �� '�����������' �� '������������' (�� 100 ���. �� 200 ���.) + ����� ������ �������� ������
	public static function FormatRange(&$format, $cod) {
		$price = $pricecod = $day = $day2 = self::SetRange();
		$ico = '';
		$min = false;
		foreach ($format['data'] as $k => $v) if (isset($v['id']) && !isset($v['error'])) {
			if ($ico == '' && !empty($v['ico'])) $ico = $v['ico'];

			$p = $v['price'] + (isset($v['priceinfo']) ? $v['priceinfo'] : 0);
			if ($min === false || $p < $min['price']) $min = array('price' => $p, 'key' => $k);
			$price = self::SetRange($price, $p);

			if (!empty($v['day'])) {
				$s = preg_replace("/[^0-9-]/i", "", $v['day']);
				$s = explode('-', $s);

				$day = self::SetRange($day, $s[0]);
				if (!empty($s[1])) $day = self::SetRange($day, $s[1]);
			}

			if ($cod && isset($v['pricecod'])) $pricecod = self::SetRange($pricecod, $v['pricecod'], $v['pricecod_formatted']);
		}
		if ($min !== false) {
			$v = $min + $format['data'][$min['key']];
			$v['price_formatted'] = self::GetPrice('formatted', $v['price']);
			$format['min'] = $v;
		}
		$price['min']['formatted'] = self::GetPrice('formatted', $price['min']['value']);
		$price['max']['formatted'] = self::GetPrice('formatted', $price['max']['value']);
		$format['price'] = $price;
		$format['pricecod'] = $pricecod;
		$format['pricehead'] = self::AddRange($price, $pricecod);
		$format['day'] = $day; //self::GetDay($day['min']['value'], $day['max']['value']);
		$format['ico'] = $ico;
	}
	public static function SetRange($range = false, $value = 0, $formatted = '') {
		if ($range === false) return array('min' => array('value' => -1, 'formatted' => ''), 'max' => array('value' => -1, 'formatted' => ''));
		if ($range['min']['value'] == -1 || $value < $range['min']['value']) $range['min'] = array('value' => $value, 'formatted' => $formatted);
		if ($range['max']['value'] == -1 || $value > $range['max']['value']) $range['max'] = array('value' => $value, 'formatted' => $formatted);
		return $range;
	}
	public static function AddRange($range = false, $range2) {
		if ($range === false) return $range2;
		if ($range2['min']['value'] >= 0) $range = self::SetRange($range, $range2['min']['value'], $range2['min']['formatted']);
		if ($range2['max']['value'] >= 0) $range = self::SetRange($range, $range2['max']['value'], $range2['max']['formatted']);
		return $range;
	}
	public static function GetRange($range) {
		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
		$r = ($range['min']['value'] != $range['max']['value'] && $range['min']['value'] !== '0' ? '<br>' : '');
		$r = ($range['min']['value'] != $range['max']['value'] ? $sign['from'] . $range['min']['formatted'] . $r . $sign['to'] : '') . $range['max']['formatted'];
		return $r;
	}

	// ��������� ������� ������� �������� �� ��������� � ���������� ��������
	public static function GetDeliveryBonus($data, $cod, $template_format) {

		$r = array();
		$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
		$bonus = $sign['bonus'];

		$ar = array(
			'odt' => array('office', 'door', 'post'),
			'dot' => array('door', 'office', 'post'),
			'tod' => array('post', 'office', 'door'),
		);
		$ar = (isset($ar[$template_format]) ? $ar[$template_format] : $ar['odt']);

		foreach ($data as $k => $v) foreach ($ar as $v2) if (!isset($v[$v2]) || $v[$v2] === '') $data[$k][$v2] = -1;

		$c = $data[$cod];
		if (!isset($c['office_count'])) $c['office_count'] = 0;
		foreach ($data as $k => $v) if ($k != $cod) {
			if (!isset($v['office_count'])) $v['office_count'] = 0;

			$s = array();

			$u = array('free' => array(), 'lower' => array(), 'exists' => array());
			foreach ($ar as $f) {
				if ($v[$f] == 0 && $c[$f] != 0) $u['free'][] = $bonus[$f];
				else if ($v[$f] > 0 && $c[$f] > 0 && $v[$f] < $c[$f]*0.9) $u['lower'][] = $bonus[$f];
				else if ($v[$f] > 0 && $c[$f] < 0) $u['exists'][] = $bonus[$f];
			}
			foreach ($u as $k2 => $v2) if (!empty($v2)) $s[] = $bonus[$k2].' '.self::GetStringList($v2);

			if (count($s) < 3) {
				$n = $v['office_count'] - $c['office_count'];
				if ($n > 0 && $c['office_count'] != 0) $s[] = '+'.self::draw_string('office', $n);
			}

			if (!empty($s)) $r[$k] = $s;
		}

		return $r;

	}

	// ������� ������� � ������ ���� "1, 2 � 3"
	public static function GetStringList($ar) {
        $r = '';
		if (!empty($ar)) {
			$sign = self::GetMessage('EDOST_DELIVERY_SIGN');
			$r = $ar[0];
			$n = count($ar) - 1;
			for ($i = 1; $i <= $n; $i++) $r .= ($i != $n ? ', ' : $sign['and']).$ar[$i];
		}
		return $r;
	}

	public static function GetProtocol() {
		return 'https://';
	}

	// ��������� ������ �� ����
	public static function GetOfficeLink($office) {
		$protocol = self::GetProtocol();
		$s = (!empty($office['detailed']) ? str_replace('%id%', $office['id'], $office['detailed']) : $protocol.'edost.ru/office.php?c='.$office['id']);
		return ($s == 'N' ? '' : $s);
	}

	// ���������� ����������� �������, ���� ��������� ����������� ���������� ����� �������� ��� ���������� ������ ��� ��������� ��� ���������� �����
	public static function FilterOfficeCod(&$v, $o) {
		if (isset($v['pricecash']) && (isset($o['codmax']) && $v['pricecash'] > $o['codmax'] || !empty($o['cod_disable']))) {
			$ar = array('pricecash', 'pricecash_formatted', 'pricecod', 'pricecod_formatted');
			foreach ($ar as $v2) unset($v[$v2]);
		}
	}

	// ���������� ������ �� ������ ������
	public static function AddOfficeData(&$v, $o, $sign) {

		$v['office_id'] = $o['id'];
		$v['office_type'] = $o['type'];
		$v['office_options'] = $o['options'];
		$v['office_address'] = self::GetOfficeAddress($o);
		$v['office_address_full'] = self::GetOfficeAddress($o, $v);
		$v['office_detailed'] = self::GetOfficeLink($o);
		$v['office_link_head'] = (in_array($o['type'], self::$postamat) ? $sign['postamat']['name'] : $sign['office']);
		$v['office'] = $o;
		if ($o['options'] & 256) $v['warning'] = $sign['full_warning'];
		self::FilterOfficeCod($v, $o);

	}


	// �������� ������ ������
	public static function PackItem(&$total, $s, $quantity) {

		if (!($s[0] > 0 && $s[1] > 0 && $s[2] > 0 && $quantity > 0)) return false;

		sort($s); // ���������� ��������� �� �����������

		$total['package2'][] = array('size' => $s, 'quantity' => $quantity, 'volume' => $s[0]*$s[1]*$s[2]);

		if ($quantity == 1) {
			$total['package'][] = array('X' => $s[0], 'Y' => $s[1], 'Z' => $s[2]);
			return true;
		}

		$x1 = $y1 = $z1 = $l = 0;
		$max1 = floor(sqrt($quantity));
		for ($y = 1; $y <= $max1; $y++) {
			$i = ceil($quantity / $y);
			$max2 = floor(sqrt($i));

			for ($z = 1; $z <= $max2; $z++) {
				$x = ceil($i/$z);

				$l2 = $x*$s[0] + $y*$s[1] + $z*$s[2];
				if ($l == 0 || $l2 < $l) {
					$l = $l2;
					$x1 = $x;
					$y1 = $y;
					$z1 = $z;
				}
			}
		}

		$total['package'][] = array('X' => $x1*$s[0], 'Y' => $y1*$s[1], 'Z' => $z1*$s[2]);
		return true;

	}

	// �������� ������ �������
	public static function PackItems($a) {

		if (empty($a)) return array(0, 0, 0);

		$n = count($a);
		for ($i3 = 1; $i3 < $n; $i3++) {
			// ���������� �������� �� ��������
			for ($i2 = $i3-1; $i2 < $n; $i2++) {
				for ($i = 0; $i <= 1; $i++) {
					if ($a[$i2]['X'] < $a[$i2]['Y']) {
						$a1 = $a[$i2]['X'];
						$a[$i2]['X'] = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a1;
					};
					if ($i == 0 && $a[$i2]['Y'] < $a[$i2]['Z']) {
						$a1 = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a[$i2]['Z'];
						$a[$i2]['Z'] = $a1;
					}
				}
				$a[$i2]['sum'] = $a[$i2]['X'] + $a[$i2]['Y'] + $a[$i2]['Z']; // ����� ������
			}

			// ���������� ������� �� �����������
			for ($i2 = $i3; $i2 < $n; $i2++)
				for ($i = $i3; $i < $n; $i++)
					if ($a[$i-1]['sum'] > $a[$i]['sum']) {
						$a2 = $a[$i];
						$a[$i] = $a[$i-1];
						$a[$i-1] = $a2;
					}

			// �������� ���� ����� ��������� �������
			if ($a[$i3-1]['X'] > $a[$i3]['X']) $a[$i3]['X'] = $a[$i3-1]['X'];
			if ($a[$i3-1]['Y'] > $a[$i3]['Y']) $a[$i3]['Y'] = $a[$i3-1]['Y'];
			$a[$i3]['Z'] = $a[$i3]['Z'] + $a[$i3-1]['Z'];
			$a[$i3]['sum'] = $a[$i3]['X'] + $a[$i3]['Y'] + $a[$i3]['Z']; // ����� ������
		}

		$r = array(round($a[$n-1]['X'], 3), round($a[$n-1]['Y'], 3), round($a[$n-1]['Z'], 3));
		sort($r); // ���������� ��������� �� �����������

		return $r;

	}


	// ������ ��� ����������� �������� � ������
	public static function GetScriptData($config, $path = '') {

		$protocol = self::GetProtocol();
		$date = date('dmY');
		$win = (!defined('EDOST_CHARSET') || EDOST_CHARSET != 'UTF' ? 'win/' : '');
		$server = (!isset($config['template_script']) || $config['template_script'] == 'Y' ? true : false);
		$path = ($server ? $protocol.'edostimg.ru/shop/' : $path.$win);
		$charset = ($server ? ' charset="utf-8"' : '');

		return array(
			'protocol' => $protocol,
			'server' => $server,
			'path' => $path,
			'office_css' => $path.'office2.css?a='.$date,
			'office_js' => '<script type="text/javascript" src="'.$path.'office2.js?a='.$date.'"'.$charset.'></script>',
			'office_src' => $path.'office2.js?a='.$date,
			'catalogdelivery_js' => '<script type="text/javascript" src="'.$path.'catalogdelivery2.js?a='.$date.'"'.$charset.'></script>',
			'catalogdelivery_src' => $path.'catalogdelivery2.js?a='.$date,
			'location' => '<script id="edost_location_script" '.(!$server ? 'data-src="'.$path.'"' : '').' type="text/javascript" src="'.$path.'location.js?a='.$date.'"'.$charset.'></script>',
			'location_src' => $path.'location.js?a='.$date,
			'profile_location' => '<script type="text/javascript" src="'.$protocol.'edostimg.ru/js/location_data.js?v=4" charset="windows-1251"></script>', // ������ ������� ��� �������� ����������
		);

	}

	// ��������� ������ ��������
	public static function GetCompanyIco($company_id, $tariff_id) {
		$r = intval($company_id);
		if ($tariff_id == 35) $r = 's1';
		if ($tariff_id >= 56 && $tariff_id <= 58) $r = 's'.($tariff_id - 54);
		if ($tariff_id >= 31 && $tariff_id <= 34) $r = 'v'.($tariff_id - 30);
		return $r;
	}



	// ������ ������
	public static function ParseAddress($s) {

		$prop2 = array();
		if ($s === '') return $prop2;

		$address = self::GetMessage('EDOST_LOCATIONS_ADDRESS');
		$delivery_sign = self::GetMessage('EDOST_DELIVERY_SIGN');

//		$s = '�. ����, �����. ������, �. 100, ����. 5, ��. 10; �. �����';
//		$s = '������ 1-� ������������, �. 100, ����. 5, ��. 10';
//		$s = '����� ������ PickPoint: ������ ��-��, 15 �,  ��. 119 (PickPoint: ������ 3302-007), ��� �������: 3302-007';
//		$s = $GLOBALS['APPLICATION']->ConvertCharset($s, 'windows-1251', LANG_CHARSET);

		if (!empty($delivery_sign['code'])) {
			$ar = explode(', '.$delivery_sign['code'].': ', $s);
			if (!empty($ar[1])) return $prop2;
		}

		$s = trim(preg_replace('/ {2,}/', ' ', $s));
		$s = explode('; ', $s);
		$prop2['city2'] = (!empty($s[1]) ? $s[1] : '');
		$s = explode(', ', $s[0]);

		self::draw_data('order', $s);

		foreach ($s as $k => $v) if (!empty($v)) foreach ($address as $k2 => $v2) if (!empty($v2['name2']) && strpos($v, $v2['name2']) === 0) {
			$i = explode($v2['name2'].' ', $s[$k]);
			$prop2[$k2] = (!empty($i[1]) ? $i[1] : '');
			$s[$k] = '';
		}

		$ar = array();
		foreach ($s as $k => $v) if (!empty($v)) $ar[] = $v;
		$prop2['street'] = implode(' ', $ar);

		self::FilterProp2($prop2);

		return $prop2;

	}


	// ����� ����� � ������� '1 234.56'
	public static function draw_digit($n, $zero = true, $d = 3) {

		if ($n == 0 && !$zero) return '';
		else {
			$n = round($n + 0, 3);

			$s = ' '.$n;
			$p = strpos($s, '.');
			if ($p > 0) {
				$d2 = strlen($s) - $p - 1;
				if ($d2 < $d) $d = $d2;
			}
			else $d = 0;

			return number_format($n, $d, '.', ' ');
		}

	}

	public static function draw_data($name, $data) {
		echo '<br><b>'.$name.':</b> '.(is_array($data) ? '<pre style="font-size: 12px">'.print_r($data, true).'</pre>' : $data);
	}

	// ������� �������� ������� � ������ ($delimiter - ������ ������������, ���� ������������ ������ ����������� �������� �������)
	public static function implode2($delimiter, $data, $n = 0) {

		if (empty($data)) return '';
		if (!is_array($data)) return $data;

		$s = '';
		$n++;

		$a = $delimiter;
		if (is_array($a)) {
			if (isset($a[$n-1])) $a = $a[$n-1];
			else if (isset($a[count($a)-1])) $a = $a[count($a)-1];
			else $a = '';
		}

		$s = array();
		foreach ($data as $v) $s[] = (is_array($v) ? self::implode2($delimiter, $v, $n) : $v);
		$s = implode($a, $s);

		return $s;

	}

	// ������� � ��������� �����
	public static function site_charset($s, $charset = '') {
		$utf = (defined('EDOST_CHARSET') && EDOST_CHARSET == 'UTF' ? true : false);
		if ($charset == 'utf' && !$utf) $s = self::utf8_win($s);
		else if ($charset != 'utf' && $utf) $s = self::win_utf8($s);
		return $s;
	}

	// ������� �� ��������� ����� � WIN
	public static function win_charset($s) {
		$utf = (defined('EDOST_CHARSET') && EDOST_CHARSET == 'UTF' ? true : false);
		if ($utf) $s = self::utf8_win($s);
		return $s;
	}

	// ������������� �� UTF8 � WIN
	public static function utf8_win($s) {
		$out = '';
		$c1 = '';
		$byte2 = false;
		$n = (function_exists('mb_strlen') ? mb_strlen($s, 'windows-1251') : strlen($s));
		for ($c = 0; $c < $n; $c ++) {
			$i = ord($s[$c]);
			if ($i <= 127) $out .= $s[$c];
			if ($byte2) {
				$new_c2 = ($c1 & 3) * 64 + ($i & 63);
				$new_c1 = ($c1 >> 2) & 5;
				$new_i = $new_c1 * 256 + $new_c2;
				if ($new_i == 1025) {
					$out_i = 168;
				}
				else {
					if ($new_i == 1105)	$out_i = 184;
					else $out_i = $new_i-848;
				}
				$out .= chr($out_i);
				$byte2 = false;
			}
			if (($i >> 5) == 6) {
				$c1 = $i;
				$byte2 = true;
			}
		}
		return $out;
	}

	// ������������� �� WIN � UTF8
	public static function win_utf8($s) {
		$utf = '';
		$mb = (function_exists('mb_substr') ? true : false);
		$n = (function_exists('mb_strlen') ? mb_strlen($s, 'windows-1251') : strlen($s));
		for ($i = 0; $i < $n; $i++) {
			$donotrecode = false;
			$c = ord($mb ? mb_substr($s, $i, 1, 'windows-1251') : substr($s, $i, 1));
			if ($c == 0xA8) $res = 0xD081;
			elseif ($c == 0xB8) $res = 0xD191;
			elseif ($c < 0xC0) $donotrecode = true;
			elseif ($c < 0xF0) $res = $c + 0xCFD0;
			else $res = $c + 0xD090;
			$c = ($donotrecode) ? chr($c) : (chr($res >> 8) . chr($res & 0xff));
			$utf .= $c;
		}
		return $utf;
	}


	// ������ ������/������� �� ����
	public static function WriteData($name, $data, $array = false) {

		if ($array) {
			if (!is_array($data) && !is_object($data)) return;
			$data = serialize($data);
		}

		$f = fopen($name, 'w');
		fwrite($f, $data);
		fclose($f);

	}

	// ������ ������/������� � �����
	public static function ReadData($name, $array = false) {

		if (!file_exists($name)) return ($array ? array() : false);

		$f = fopen($name, 'r');
		$r = fread($f, filesize($name));
		fclose($f);

		if ($array) {
			$r = unserialize($r);
			if (!is_array($r) && !is_object($r)) $r = false;
		}

		return $r;

	}

	// �������� ��������� �������� �� cookie
	public static function GetCookie() {
		$r = array(
			'setting_tariff_show' => 'N', // ������������� �������� ������� (Y, N)
/*
			'filter_days' => '5', // ������ ����������� �� ��������� 'filter_days' ����
			'docs_active' => '', // �������� ��������� ��� ������ ������
			'setting_active' => 'module', // �������� ��������� (module, paysystem, document)
			'admin_type' => '', // ��������� ������������� ��������
			'control_day_delay' => '5', // �������� ���� ��������
			'control_day_office' => '2', // ����� � ������ ������
			'control_day_complete' => '15', // ������� ���������� �������
			'control_show_total' => 'N', // ������ �� ��������� ��������
			'control_setting' => 'N', // �������� ���� � ����������� ��������
			'control_delete' => 'Y', // �������� ������ "����� � ��������" ��� �������� �������
			'control_paid' => 'Y', // �������� ������ "��������� ������" ��� �������� ������� � ���������� ��������
			'control_changed' => 'Y', // �������� ������ � ��������, � ������� ������� ��������� ������
			'control_complete_delay' => 'N', // �������� �� ������� �������� ���� �������� � ����������� �������
*/
		);
		$ar = (!empty($_COOKIE['edost_admin']) ? explode('|', preg_replace("/[^0-9a-z_|-]/i", "", $_COOKIE['edost_admin'])) : array());
		$i = 0;
		foreach ($r as $k => $v) {
			$r[$k] = (isset($ar[$i]) ? $ar[$i] : $v);
			$i++;
		}
		return $r;
	}

}
?>