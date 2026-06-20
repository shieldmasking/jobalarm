<?php

include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';

/*
"mc_gross":"50.00",
"protection_eligibility":"Ineligible",
"payer_id":"E2A73YUET2CAW",
"tax":"0.00",
"payment_date":"01:25:48 Mar 09, 2015 PDT",
"payment_status":"Completed",
"charset":"windows-1252",
"first_name":"Lord",
"mc_fee":"1.75",
"notify_version":"3.8",
"custom":"2",
"payer_status":"verified",
"business":"setzor@gmail.com",
"quantity":"1",
"verify_sign":"AFAxppvmp34imEA5WGyd69ElyyzrAR2g7EV1woucotZqcCtolFtiJWjx",
"payer_email":"khaotix@yahoo.com",
"txn_id":"8YM91363B1968313G",
"payment_type":"instant",
"last_name":"Khaotix",
"receiver_email":"isllam160@gmail.com",
"payment_fee":"1.75",
"receiver_id":"MPNGSDNC5ZJV2",
"txn_type":"web_accept",
"item_name":"Campaign Funds",
"mc_currency":"USD",
"item_number":"",
"residence_country":"US",
"test_ipn":"1",
"handling_amount":"0.00",
"transaction_subject":"2",
"payment_gross":"50.00",
"shipping":"0.00",
"ipn_track_id":"cef9428578011"
 */


//$verify_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate&' . http_build_query( $_POST );   
$verify_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate&' . http_build_query( $_POST );   

file_put_contents('paymentverify.txt',$verify_url );

$verify_string = file_get_contents( $verify_url );

file_put_contents('paymentdebug.txt',date('Y-m-d H:i:s')."\r\n".print_r($_REQUEST,true)."\r\n".$verify_string."\r\n".$verify_url."\r\n\r\n\r\n",FILE_APPEND);


if( !strstr( $verify_string, 'VERIFIED' ) ) die('Failed');

$query = "SELECT * FROM payment WHERE accountId=".$account_id." AND refId=".$_REQUEST['txn_id'];
$dbData = Config::get('db')->get_results($query);
if ($dbData && count($dbData) > 0) {
    file_put_contents('alreadyfound.txt','damnit');
    // TODO: already received payment confirmation for this transaction, handle accordingly
    
} else
    
if ($_REQUEST['payment_status'] == 'Pending' || $_REQUEST['payment_status'] == 'Completed') {
    $customData = isset($_REQUEST['custom']) ? $_REQUEST['custom'] : ';
    
    $customValues = explode('|',$customData);
    
    if (count($customValues) > 0) {
        $account_id = $customValues[0];
        $accountData = Config::get('db')->get_results("select * from account where id={$account_id}");
        
        if (!$accountData || count($accountData) == 0) {
            exit();
        } else {
            $account = $accountData[0];
        }

        $paymentdata = null;
        
        if (isset($_REQUEST['item_number']) && $_REQUEST['item_number'] == 'tjmp') {
                
            $account_data = array('billing_hold'=>0,'hold_date'=>'0000-00-00 00:00:00');
            Config::get('db')->update('account',$account_data,array('id'=>$account_id));
            
            $paymentdata = array(
            'accountId'=>$account_id,
            'refId'=>$_REQUEST['txn_id'],
            'paymentDate'=>date('Y-m-d H:i:s'),
            'amount'=>$_REQUEST['payment_gross'],
            'status'=>2,
            'rawData'=>json_encode($_REQUEST),
            'ppFee'=>$_REQUEST['payment_fee'],
            'item_number'=>$_REQUEST['item_number']
            );

            
        } else
            
            if (isset($_REQUEST['item_number']) && $_REQUEST['item_number'] == 'tjcf') {
                $currentBalance = $account['balance'];
                $couponCode = ';
                if (count($customValues) > 1) {
                    $couponCode = $customValues[1];
                }

                $addBalance = $_REQUEST['payment_gross'];
                $bonusAdd = 0.00;    
                if (strlen($couponCode) > 0) {
                    
                    $dbData = Config::get('db')->get_results("select * from coupon where code='{$couponCode}' and expireDate > NOW() and status > 0");
                    if (count($dbData) > 0) {
                        $coupon = $dbData[0];
                        switch($coupon['type']) {
                            case 0: 
                            default:
                                break;
                            case 1:
                                //$couponType = 'Multiplier';
                                if ($addBalance > $coupon['requiredAmount']) {
                                    $bonusAdd = ($addBalance * $coupon['value']) - $addBalance;    
                                }
                                break;
                            case 2:
                                //$couponType = 'Percentage';
                                if ($addBalance > $coupon['requiredAmount']) {
                                    $bonusAdd = round($addBalance * ($coupon['value']/100));
                                }
                                break;
                            case 3:
                                //$couponType = 'AddedBonus';
                                if ($addBalance > $coupon['requiredAmount']) {
                                    $bonusAdd =  $coupon['value'];
                                }
                                break;
                        }
                    }
                }
                $addBalance += $bonusAdd;
                $query = "update account set balance=balance+'{$addBalance}' where id={$account_id}";
                Config::get('db')->query($query);        

                $paymentdata = array(
                'accountId'=>$account_id,
                'refId'=>$_REQUEST['txn_id'],
                'paymentDate'=>date('Y-m-d H:i:s'),
                'amount'=>$_REQUEST['payment_gross'],
                'status'=>2,
                'rawData'=>json_encode($_REQUEST),
                'ppFee'=>$_REQUEST['payment_fee'],
                'couponCode'=>$couponCode,
                'couponAdd'=>$bonusAdd,
                'total'=>$addBalance
                );

            }
        
                
        if ($paymentdata)
            Config::get('db')->insert('payment',$paymentdata);

    }
    
}
