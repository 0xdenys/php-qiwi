<?
require 'qiwi.class.php';

$Qiwi = new Qiwi(); // ������� ��������� ������


// C������� �����

$bill_id = rand(10000000, 99999999);

$create_result = $Qiwi->create(
    '79001234567', // �������
    100, // �����
    date('Y-m-d', strtotime(date('Y-m-d') . " + 1 DAY")) . "T00:00:00", // ���� � ������� ISO 8601. ����� ������������ ���� �� ���� �����, ��� �������
    $bill_id, // ID �������
    '�������� ������' // �����������
);

if($bill_create->result_code !== 0){
    echo '������ � �������� �����';
}
else{
    echo '���� ���������';
}

// ������������� �� �������� ������

$Qiwi->redir(              
    $bill_id, // ID �����
    'http://' . $_SERVER['SERVER_NAME'] . '/success_url', // URL, �� ������� ������������ ����� ���������� � ������ ��������� ���������� �������� (�� �����������)
    'http://' . $_SERVER['SERVER_NAME'] . '/fail_url' // URL, �� ������� ������������ ����� ���������� � ������ ���������� ���������� �������� (�� �����������)
);


// ��������� ���������� � �����

$info_result = $Qiwi->info($bill_id);

if($info_result->result_code !== 0){
    echo '������ � ��������� ���������� � �����';
}
else{
    echo '������ �����: ' . $info_result->bill->status;
}


// ������ �����

$reject_result = $Qiwi->reject($bill_id);

if($reject_result->bill->status === 'rejected'){
    echo '�� ������� �������� ����';
}
else{
    echo '���� �������';
}
?>