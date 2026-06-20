<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';
require_once '.././inc/FPDF/fpdf2.php';
//require_once '../inc/class.phpmailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../inc/PHPMailer/src/Exception.php';
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';


class PDF_MC_Table extends FPDF {

var $widths;
var $aligns = array('L','L','L','L','L');

function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2,$dept2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h,$dept2);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $a;
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation,'',0,$deptId);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header($accountId){


if(intval($accountId)>0){	
	$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate FROM `productiveAccount` a WHERE a.id={$accountId}");
	$deptName='';
	$header = array('Date/Time', 'Unit', 'Submitted By', 'Type', 'Comments');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Escalation Summary' . $deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	$this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',10);
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(35, 30, 30, 40, 100);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}



}

//$dbMaster = Config::get('db') -> get_results("select a.*, (SELECT COUNT(`id`) FROM `productiveEmailDist` where `accountId`=a.id AND `deptId`=2 AND `sendEmail`=1) as emailCount  FROM `productiveAccount`a where `emailTime` !='00:00:00' AND TIME_FORMAT(`emailTime` - INTERVAL 1 HOUR, '%H') = TIME_FORMAT(CURRENT_TIME(), '%H') GROUP BY `id`");

	
$getDept = Config::get('db') -> get_results("SELECT n.* from `productiveEscalations` n LEFT JOIN `productiveAccount` as d on d.id=n.deptId WHERE DATE_FORMAT(n.dateSubmitted,'%Y-%m-%d')=DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),'%Y-%m-%d') GROUP BY n.accountId");


if($getDept){
	
foreach($getDept as $newData){
$accountId = $newData['accountId'];
$pdf = new PDF_MC_Table();

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L','',0,$accountId);
$pdf->SetWidths(array(35, 30, 30, 40, 100));

$data2 = Config::get('db') -> get_results("select e.*, l.labelName, b.name as accountName, DATE_FORMAT(e.dateSubmitted,'%Y-%m-%d') as reportdate, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveAccount` as b on b.id=e.accountId LEFT JOIN `productiveLabel` as l on l.id=b.label LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId WHERE DATE_FORMAT(e.dateSubmitted,'%Y-%m-%d')=DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),'%Y-%m-%d') AND e.accountId={$accountId} ORDER BY d.dept ASC, e.dateSubmitted DESC");

foreach($data2 as $row2){
	$date = $row2['dateSubmitted']; 
	$deptId = $row2['deptId'];
	$escId = $row2['id'];
	if(strlen($row2['submittedby'] ?? '')>0){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['first_name'] . ' ' . $row2['last_name'];	
	}
	if(intval($row2['active'])==2){
			$type = $row2['escType'] . " (Closed by " . $row2['closedFirst'] . " " . $row2['closedLast'] . " on " . $row2['closedDate'] . ")";
			}else{
			$type = $row2['escType'];
			}
	$comment = '';
	$comment .= '';
	
	$respData = Config::get('db') -> get_results("select r.*, u.last_name, u.first_name FROM `productiveEscResponse` r LEFT OUTER JOIN `productiveUser` as u on u.id=r.userId WHERE r.escId={$escId} AND r.response !='' GROUP BY r.id ORDER BY r.responseTime DESC");
	if($respData){	
		foreach($respData as $resp){
		$comment .= $resp['responseTime'] . ' - ' . $resp['first_name'] . ' ' . $resp['last_name'] . ': ' . $resp['response'] . ' < > ';
		}
	}
	$pdf->Row(array($date,$row2['dept'],$submittedby,$type,$row2['note']),$deptId);


}

$filename="/home/prnadmin/public_html/dailyreports/esc". substr($data2[0]['accountName'],0,5) . $data2[0]['reportdate'] . ".pdf";
$pdf->Output($filename,'F');

$addresses = Config::get('db') -> get_results("select e.*, u.logix, l.login from `productiveEmailDist` e LEFT JOIN `productiveUser` as u on u.id=e.userId LEFT JOIN `productiveAccount` as a on a.id=e.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label where e.accountId={$accountId} AND e.deptId=0 AND e.sendEmail=1 AND e.emailAddress LIKE '%@%' GROUP BY e.emailAddress");

$filename1="/home/prnadmin/public_html/dailyreports/esc". substr($data2[0]['accountName'],0,3)."Image.pdf";
$pdf->Output($filename1,'F');
$pdf = escapeshellarg( "/home/prnadmin/public_html/dailyreports/esc". substr($data2[0]['accountName'],0,3)."Image.pdf" );
$save = escapeshellarg( "/home/prnadmin/public_html/dailyreports/esc". substr($data2[0]['accountName'],0,3)."Image".$data2[0]['reportdate'].".jpg" );
exec("convert -verbose -density 150 $pdf -quality 100 -flatten -sharpen 0x1.0 $save");

$mail = new PHPMailer(true);

$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'productivern.com';                 // Specify main and backup server
//$mail->Host = gethostbyname("smtp.productivern.com"); 
$mail->Port = 465;                                    // Set the SMTP port
$mail->SMTPAuth = true;                               // Enable SMTP authentication
//$mail->Encoding = '7bit';
$mail->Username = 'noreply@productivern.com';                // SMTP username
$mail->Password = 'QL_X^8z@J)90';                  // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted


$mail->From = 'noreply@productivern.com';
$mail->FromName = $data2[0]['labelName'];

foreach ($addresses as $address) {
    $mail->AddAddress(trim($address['emailAddress']));
	$login = $address['login'];
}

//$mail->AddAddress('rstrenger@productivern.com');  // Add a recipient
$mail->AddBCC('rstrenger@productivern.com');  // Add a recipient



// Attachments
$mail->addAttachment('/home/prnadmin/public_html/dailyreports/esc' . substr($data2[0]['accountName'],0,5) . $data2[0]['reportdate'] . '.pdf');// Add attachments
$mail->AddEmbeddedImage('/home/prnadmin/public_html/dailyreports/esc'. substr($data2[0]['accountName'],0,3).'Image'.$data2[0]['reportdate'].'.jpg', ''. substr($data2[0]['accountName'],0,3).'Image');
$mail->IsHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Escalation Summary: ' . $data2[0]['accountName'] . ' ('. $data2[0]['reportdate'] . ')';


$mail->Body    = '<p><a href="https://www.productivern.com/' . $login . '">LOGIN HERE</a></p>'
.'<img src="cid:'. substr($data2[0]['accountName'],0,3).'Image">';


if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';
}
}


?>
