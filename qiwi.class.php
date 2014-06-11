<?
/**
 * Qiwi - ����� ��� ������ � ��������� API �� QIWI
 * @package Qiwi
 * @author atnartur (��������� �����)
 * @copyright 2014 atnartur (��������� �����)
 */


class Qiwi{
    /**
    * ID ��������
    * @var int
    */
    public $shop_id = 000000;
    
    
    /**
    * API DI (REST ID) ��� BASIC �����������
    * @var int
    */
    public $rest_id = 00000000;
    
    
    /**
    * ������ API
    * @var string
    */
    public $rest_pass = 'PASSWORD';
    
    
    /**
    * ������
    * @var string
    */
    public $currency = 'RUB';
    
    
    /**
    * �������� ������: mobile - ������ � ���������� �������� ������������, qw - � ����� ���������� ������ Visa Qiwi Wallet
    * @var string
    */
    public $pay_source = 'qw';
    
    
    /**
    * �������� ����������
    * @var string
    */
    public $prv_name = 'My store';
    
    
    
    /**
	* ����������� �����
	* 
	* @param {string} tel ������� ������������, �� �������� ������������ ����
	* @param {int} amount ����� �����
	* @param {string} date ���� �������� ����� (� ������� ISO 8601)
	* @param {string} bill_id ���������� ����� �����
	* @param {string} comment ����������� � ������� (�� �����������)
	* @returns {object} ������ ������ �� ������� QIWI
	*/
    function create($tel, $amount, $date, $bill_id, $comment = null){
        $parameters = array(
            'user' => 'tel:+'.$tel, // ������� ���������� � +
            'amount' => $amount, 
            'ccy' => $this->currency, 
            'comment' => $comment,
            'pay_source' => $this->pay_source,
            'lifetime' => $date,
            'prv_name' => $this->prv_name,
        );
                
        
        $ch = curl_init('https://w.qiwi.com/api/v2/prv/'.$this->shop_id.'/bills/'.$bill_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: text/json",
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8"
        ));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->rest_id . ':' . $this->rest_pass);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $httpResponse = curl_exec($ch);
        
        if (!$httpResponse) {
            // �������� ������, � ������� 
            throw new Exception(curl_error($ch).'('.curl_errno($ch).')');
            return false;
        }
        $httpResponseAr = json_decode($httpResponse);
        return $httpResponseAr->response;
    }
    
    
    /**
	* ������������� �� �������� ������ �����
	* 
	* @param {string} bill_id ���������� ����� �����
	* @param {string} success_url URL, �� ������� ������������ ����� ���������� � ������ ��������� ���������� �������� (�� �����������)
	* @param {string} fail_url URL, �� ������� ������������ ����� ���������� � ������ ���������� ���������� �������� (�� �����������)
	*/
    function redir($bill_id, $success_url = '', $fail_url = ''){
        header("Location: https://w.qiwi.com/order/external/main.action?shop=" . $this->shop_id . "&transaction=" . $bill_id .
               "&successUrl=" . $success_url . "&failUrl=" . $fail_url);
    }
    
    
    /**
	* ���������� � �����
	* 
	* @param {string} bill_id ���������� ����� �����
	* @returns {object} ������ ������ �� ������� QIWI
	*/
    function info($bill_id){
        $ch = curl_init('https://w.qiwi.com/api/v2/prv/'.$this->shop_id.'/bills/'.$bill_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: text/json",
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8"
        ));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->rest_id . ':' . $this->rest_pass);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $httpResponse = curl_exec($ch);
        
        if (!$httpResponse) {
            // �������� ������, � ������� 
            throw new Exception(curl_error($ch).'('.curl_errno($ch).')');
            return false;
        }
        $httpResponseAr = json_decode($httpResponse);
        return $httpResponseAr->response;
    }
    
    
    /**
	* ������ �������
	* 
	* @param {string} bill_id ���������� ����� �����
	* @returns {object} ������ ������ �� ������� QIWI
	*/
    function reject($bill_id){
        $ch = curl_init('https://w.qiwi.com/api/v2/prv/'.$this->shop_id.'/bills/'.$bill_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: text/json",
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8"
        ));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->rest_id . ':' . $this->rest_pass);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $httpResponse = curl_exec($ch);
        
        if (!$httpResponse) {
            // �������� ������, � ������� 
            throw new Exception(curl_error($ch).'('.curl_errno($ch).')');
            return false;
        }
        $httpResponseAr = json_decode($httpResponse);
        return $httpResponseAr->response;
    }
}
?>