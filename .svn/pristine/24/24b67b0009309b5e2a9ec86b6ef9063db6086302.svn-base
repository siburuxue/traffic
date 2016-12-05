<?php
namespace Lib;
class AliMail {

	private $Format = 'JSON';
	private $Version = '2015-11-23';
	private $AccessKeyId;
	private $AccessKeySecret;
	private $SignatureMethod = 'HMAC-SHA1';
	private $SignatureVersion = '1.0';
	private $Action = 'SingleSendMail';
	private $AccountName = '';
	private $ReplyToAddress = 'true';
	private $AddressType = '1';
	private $server = 'http://dm.aliyuncs.com/';

	public function __construct($AccessKeyId = '', $AccessKeySecret = '', $AccountName = '') {
		$this->AccessKeyId = empty($AccessKeyId) ? C('ALIMAIL.access_key_id') : $AccessKeyId;
		$this->AccessKeySecret = empty($AccessKeySecret) ? C('ALIMAIL.access_key_secret') : $AccessKeySecret;
		$this->AccountName = empty($AccountName) ? C('ALIMAIL.account_name') : $AccountName;
	}

	public function exec($address, $subject, $content) {
		$data['Format'] = $this->Format;
		$data['Version'] = $this->Version;
		$data['AccessKeyId'] = $this->AccessKeyId;
		$data['SignatureMethod'] = $this->SignatureMethod;
		date_default_timezone_set("GMT");
		$data['Timestamp'] = date('Y-m-d\TH:i:s\Z');
		date_default_timezone_set(C('DEFAULT_TIMEZONE'));
		$data['SignatureVersion'] = $this->SignatureVersion;
		$data['SignatureNonce'] = uniqid();
		$data['Action'] = $this->Action;
		$data['AccountName'] = $this->AccountName;
		$data['ReplyToAddress'] = $this->ReplyToAddress;
		$data['AddressType'] = $this->AddressType;
		$data['ToAddress'] = $address;
		$data['Subject'] = $subject;
		$data['HtmlBody'] = $content;
		$data['Signature'] = $this->getSign($data);
		return $this->send($data);
	}

	private function getSign($data) {
		ksort($data);
		$str = '';
		foreach ($data as $k => $v) {
			$str .= '&' . $this->percentEncode($k) . '=' . $this->percentEncode($v);
		}
		$str = substr($str, 1);
		$sign = 'GET&%2F&' . $this->percentEncode($str);
		$sign = hash_hmac('sha1', $sign, $this->AccessKeySecret . '&', true);
		$sign = base64_encode($sign);
		return $sign;
	}

	private function send($data) {
		$ch = curl_init();
		$str = '';
		foreach ($data as $k => $v) {
			$str .= '&' . $k . '=' . urlencode($v);
		}
		$str = substr($str, 1);
		curl_setopt($ch, CURLOPT_URL, $this->server . '?' . $str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $httpCode == '200' ? true : false;
	}

	private function percentEncode($str) {
		$res = urlencode($str);
		$res = preg_replace('/\+/', '%20', $res);
		$res = preg_replace('/\*/', '%2A', $res);
		$res = preg_replace('/%7E/', '~', $res);
		return $res;
	}
}
?>
