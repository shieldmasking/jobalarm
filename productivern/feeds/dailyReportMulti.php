<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';
require_once '../inc/FPDF/fpdfMaster.php';
//require_once '../inc/class.phpmailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../inc/PHPMailer/src/Exception.php';
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';

class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','L','C','C','C','C','C','C','C','C','C','C');


function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
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

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
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

function Header($dept){

$newdata = Config::get('db') -> get_results("SELECT d.*, a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId WHERE d.active=1 and d.id={$dept} GROUP BY d.id");
//$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

$otherDesc = "Other";
$w5=13;
$w6=13;
$w7=18;
$w8=14;
$w9=14;
$w10=14;
$w11=14;

$x4=19;
$x5=18;
$x6=18;
$x7=19;
$x8=19;
$x9=19;
$x10=19;

if(strlen($newdata[0]['chargeDesc'] ?? '')==0){
	$w5 = 0;
}
if(strlen($newdata[0]['nurseDesc'] ?? '')==0){
	$w6 = 0;
}
if(strlen($newdata[0]['nurse1Desc'] ?? '')==0){
	$w7 = 0;
}
if(strlen($newdata[0]['nurse2Desc'] ?? '')==0){
	$w8 = 0;
}
if(strlen($newdata[0]['techLabel'] ?? '')==0){
	$w9 = 0;
}
if(strlen($newdata[0]['secLabel'] ?? '')==0){
	$w10 = 0;
}

if(strlen($newdata[0]['sittersNEWDesc'] ?? '')==0 && strlen($newdata[0]['other1Desc'] ?? '')==0 && strlen($newdata[0]['other2Desc'] ?? '')==0 && strlen($newdata[0]['other3Desc'] ?? '')==0){
	$w11 = 0;
}
if(intval($newdata[0]['skill1'])==0){
	$x4 = 0;
}
if(intval($newdata[0]['skill2'])==0){
	$x5 = 0;
}
if(intval($newdata[0]['skill3'])==0){
	$x6 = 0;
}
if(intval($newdata[0]['skill4'])==0){
	$x7 = 0;
}
if(intval($newdata[0]['skill5'])==0){
	$x8 = 0;
}
if(intval($newdata[0]['skill6'])==0){
	$x9 = 0;
}
if(intval($newdata[0]['skill7'])==0 && intval($newdata[0]['skill8'])==0 && intval($newdata[0]['skill9'])==0 && intval($newdata[0]['skill10'])==0){
	$x10 = 0;
	$otherDesc = '';
}
if(intval($newdata[0]['useGrid'])==0 || intval($newdata[0]['useGrid'])==5){
	$w15 = 0;
	$gridVar = '';
}else{
	$w15 = 15;
	$gridVar = "Grid Var";
}
	
if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3){
$header2 = array('Unit', 'Date/Shift', 'User', 'Pts', ''. $newdata[0]['chargeDesc'] .'', ''. $newdata[0]['nurseDesc'] .'', ''. $newdata[0]['nurse1Desc'] .'', ''. $newdata[0]['nurse2Desc'] .'', ''. $newdata[0]['techLabel'] .'', ''. $newdata[0]['secLabel'] .'', 'Other', 'RN Var', $gridVar, 'Notes');
$w = array(33, 27, 24, 10, $w5, $w6, $w7, $w8, $w9, $w10, $w11, 12, $w15, 62);
}else{
$header2 = array('Unit', 'Date/Shift', 'User', ''. $newdata[0]['skilldesc1'] .'', ''. $newdata[0]['skilldesc2'] .'', ''. $newdata[0]['skilldesc3'] .'', ''. $newdata[0]['skilldesc4'] .'', ''. $newdata[0]['skilldesc5'] .'', ''. $newdata[0]['skilldesc6'] .'','' . $otherDesc . '', 'Variance', 'Notes');
$w = array(33, 27, 24, $x4, $x5, $x6, $x7, $x8, $x9, $x10, 17, 55);
}

$dName = "Staffing Detail: " . $newdata[0]['dept'];
	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$dName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    
    // Header
	//$this->SetFont('Arial','B',10);
    //for($i=0;$i<count($header2);$i++)
       // $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    //$this->Ln();
	for($i=0;$i<count($header2);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		if(strlen($header2[$i] ?? '')>9){
		$this->SetFont('Arial','B',7);	
		}else{
		$this->SetFont('Arial','B',8);	
		}
        $this->Rect($x,$y,$w[$i],14);
		$this->MultiCell($w[$i],7,substr($header2[$i],0,12),0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
    $this->Ln();
	$this->Ln();
	
	 
    
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

function Header4($accountId){

$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId where d.active=1 and d.accountId={$accountId} group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}


$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

	$header = array('Unit', 'Director', 'Manager', 'Compliance');
	//$header2 = array('Unit', 'Date/Shift', 'User', 'PTs/Procs', 'RNs', 'Techs', 'Other', 'RN Var', 'GRID Var' , 'HRS Var' , 'Notes');
  
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Compliance Report Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(85, 37, 37, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function ImprovedTable($data4){
    $w = array(85, 37, 37, 30);
    foreach($data4 as $row){
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$row['Manager'],'LRB');
	if(floatval($row['compliance'])>99.9){
		$this->SetTextColor(0,128,0);
		$compliance = ROUND($row['compliance'],1) . '%';
	}else if(floatval($row['compliance'])<100 && floatval($row['compliance'])>79){
		$this->SetTextColor(255,140,0);
		$compliance = ROUND($row['compliance'],1) . '%';
	}else if(floatval($row['compliance'])<80 && floatval($row['compliance'])>0){
		$this->SetTextColor(255,0,0);
		$compliance = ROUND($row['compliance'],1) . '%';
	}else{
		$this->SetTextColor(255,0,0);
		$compliance = 'No Data';
	}
        $this->Cell($w[3],6,$compliance,'LRB',1,'C');
        
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}


var $widths2;
var $aligns2 = array('L','C','L','C','C','C','C','C','C','C','C','L');


function SetWidths2($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns2($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row2($data2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines2($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak2($h);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns2[$i]) ? $this->aligns2[$i] : 'L';
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

function CheckPageBreak2($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage2($this->CurOrientation);
}

function NbLines2($w,$txt){
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

function Header3($dept){
//$user2 = Config::get('account')['id'];
//$newdata = Config::get('db') -> get_results("SELECT d.*, a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId left join `productiveUser` as u on u.id={$user2} WHERE d.id={$dept} GROUP BY d.id");
$newdata = Config::get('db') -> get_results("SELECT d.* from `ProductiveDept` d WHERE d.id={$dept} GROUP BY d.id");
if($newdata){

$count = 0;

if(strlen($newdata[0]['desc7'] ?? '')==0){
$b7=0;
}else{
$b7=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc8'] ?? '')==0){
$b8=0;	
}else{
$b8=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc9'] ?? '')==0){
$b9=0;	
}else{
$b9=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc10'] ?? '')==0){
$b10=0;	
}else{
$b10=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc11'] ?? '')==0){
$b11=0;	
}else{
$b11=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc12'] ?? '')==0){
$b12=0;	
}else{
$b12=1;	
$count=$count+1;
}

if($count>0){
	$count1 = ROUND((170 / $count),0);
}else{
	$count1= 0;
}

if($count1 >40){
	$count1 = 40;
}

$b7= $b7 * $count1;
$b8= $b8 * $count1;
$b9= $b9 * $count1;
$b10= $b10 * $count1;
$b11= $b11 * $count1;
$b12= $b12 * $count1;

$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['desc7'] . '','' . $newdata[0]['desc8'] . '','' . $newdata[0]['desc9'] . '','' . $newdata[0]['desc10'] . '','' . $newdata[0]['desc11'] . '','' . $newdata[0]['desc12'] . '');
$w = array(42, 27, 25, 10, $b7, $b8, $b9, $b10, $b11, $b12);



    for($i=0;$i<count($header3);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		if(strlen($header3[$i] ?? '')>12){
		$this->SetFont('Arial','B',8);	
		}else{
		$this->SetFont('Arial','B',10);	
		}
        $this->Rect($x,$y,$w[$i],10);
		$this->MultiCell($w[$i],5,$header3[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
    $this->Ln();
	$this->Ln();
	
}	 
    
}

function Header2($dept){
$newdata = Config::get('db') -> get_results("SELECT d.*, a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId WHERE d.active=1 and d.id={$dept} GROUP BY d.id");
$a1=38;
$a2=38;
$a3=38;
$a4=40;

$b1=30;
$b2=30;
$b3=30;
$b4=25;
$b5=25;
$b6=25;


if(strlen($newdata[0]['track1Desc'] ?? '')==0){
$a1=0;
$b3=0;	
}
if(strlen($newdata[0]['track2Desc'] ?? '')==0){
$a2=0;	
$b4=0;
}
if(strlen($newdata[0]['track3Desc'] ?? '')==0){
$a3=0;
$b5=0;	
}
if(strlen($newdata[0]['track4Desc'] ?? '')==0){
$a4=0;
$b6=0;	
}
if(strlen($newdata[0]['customDesc'] ?? '')==0){
$b1=0;	
}
if(strlen($newdata[0]['customDesc2'] ?? '')==0){
$b2=0;	
}
if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && intval($newdata[0]['showEpic'])==1){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','Epic','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 25, 10, 20, $a1, $a2, $a3, $a4);
}else if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && intval($newdata[0]['showEpic'])==0){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 25, 10, $a1, $a2, $a3, $a4);
}else{
$header3 = array('Unit', 'Date/Shift','User','' . $newdata[0]['uosDesc'] . '','' . $newdata[0]['customDesc'] . '','' . $newdata[0]['customDesc2'] . '','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 30, 20, $b1, $b2, $b3, $b4, $b5, $b6);
//$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['desc7'] . '','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
}

$deptName = "Tracking Detail: " . $newdata[0]['dept'];

	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    
    // Header
	//$this->SetFont('Arial','B',8);
    for($i=0;$i<count($header3);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		if(strlen($header3[$i] ?? '')>12){
		$this->SetFont('Arial','B',8);	
		}else{
		$this->SetFont('Arial','B',9);	
		}
        $this->Rect($x,$y,$w[$i],14);
		$this->MultiCell($w[$i],7,substr($header3[$i],0,35),0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
    $this->Ln();
	$this->Ln();
	
	 
    
}

// Page footer
function Footer2(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


}

$dbMaster = Config::get('db') -> get_results("select e.*, a.emailTime FROM `productiveEmailDist` e LEFT JOIN `productiveAccount` as a on a.id=e.accountId where e.deptId=1 AND e.sendEmail=1 AND e.unitsId>0 AND a.emailTime !='00:00:00' AND TIME_FORMAT(a.emailTime - INTERVAL 1 HOUR, '%H') = TIME_FORMAT(CURRENT_TIME(), '%H') GROUP BY e.unitsId");

if($dbMaster){
foreach($dbMaster as $master){
	$accountId = $master['accountId'];
	//$emailCount = $master['emailCount'];
	$unitsId = $master['unitsId'];

$pdf = new PDF_MC_Table();

$data4 = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) where d.id IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$unitsId} AND `primaryUnit`>0) AND d.active=1 AND n.dayDate=DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY compliance DESC, d.dept ASC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage4('P','',0,$accountId);
$pdf->ImprovedTable($data4);
$filename1="/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image.pdf";
$pdf->Output($filename1,'F');
$pdf = escapeshellarg( "/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image.pdf" );
$save = escapeshellarg( "/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image".$data4[0]['filedate'].".jpg" );
//$result = 0;
exec("convert -verbose -density 150 $pdf -quality 100 -flatten -sharpen 0x1.0 $save");
//exec("convert $pdf $save");

$pdf = new PDF_MC_Table();

$getDept = Config::get('db') -> get_results("SELECT n.deptId, n.accountId, n.dayDate, d.active, d.dept, d.prodMeasure1, a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `productiveNewData` n LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `ProductiveDept` as d on d.id=n.deptId WHERE n.dayDate=DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),'%Y-%m-%d') and d.active=1 and n.accountId={$accountId} AND n.deptId IN(SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$unitsId}) GROUP BY n.deptId ORDER BY d.prodMeasure ASC, d.dept ASC");

if($getDept){
	
foreach($getDept as $newData){

$deptId = $newData['deptId'];
$accountId =  $newData['accountId'];
	
$data2 = Config::get('db') -> get_results("select n.*, e.name as enterpriseName, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportdate, d.prodMeasure1, d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, (n.skill1val-g.cn) as leadvar, o.practiceId, (n.skill8val-g.other3) as lfswvar, (n.skill9val-g.pct) as skill9var, (n.skill10val-g.sitter) as skill10var, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, (n.skill2val-g.rn) as cookvar, (n.skill3val-g.rn1) as dcvar, (n.skill4val-g.rn2) as fswvar, (n.skill5val-g.sec) as retailvar, (n.skill6val-g.other1) as coffeevar, (n.skill7val-g.other2) as lleadvar,l.noreply, l.labelName, a.name as accountName, a.displayProd, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.chargeDesc, d.sittersNEWDesc, (CASE WHEN d.customDesc='' THEN '' ELSE d.customDesc END) as customDesc, (CASE WHEN d.customDesc2='' THEN '' ELSE d.customDesc2 END) as customDesc2, (CASE WHEN d.skilldesc1='' THEN '' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN '' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN '' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN '' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN '' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN '' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN '' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN '' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN '' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN '' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.nurseDesc='' THEN '' ELSE d.nurseDesc END) as nurseDesc, (CASE WHEN d.nurse1Desc='' THEN '' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.nurse2Desc='' THEN '' ELSE d.nurse2Desc END) as nurse2Desc, (CASE WHEN d.other1Desc='' THEN '' ELSE d.other1Desc END) as nother1Desc, (CASE WHEN d.techLabel='' THEN '' ELSE d.techLabel END) as techLabel, (CASE WHEN d.secLabel='' THEN '' ELSE d.secLabel END) as secLabel, (CASE WHEN d.other1Desc='' THEN '' ELSE d.other1Desc END) as other1Desc, (CASE WHEN d.other2Desc='' THEN '' ELSE d.other2Desc END) as other2Desc, (CASE WHEN d.other3Desc='' THEN '' ELSE d.other3Desc END) as other3Desc, (CASE WHEN d.desc7='' THEN '' ELSE d.desc7 END) as desc7, (CASE WHEN d.desc8='' THEN '' ELSE d.desc8 END) as desc8, (CASE WHEN d.desc9='' THEN '' ELSE d.desc9 END) as desc9, (CASE WHEN d.desc10='' THEN '' ELSE d.desc10 END) as desc10, (CASE WHEN d.skilldesc1='' THEN '' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN '' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN '' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN '' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN '' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN '' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN '' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN '' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN '' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN '' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.skilldesc11='' THEN '' ELSE d.skilldesc11 END) as skilldesc11, (CASE WHEN d.track1Desc='' THEN '' ELSE d.track1Desc END) as track1Desc, (CASE WHEN d.track2Desc='' THEN '' ELSE d.track2Desc END) as track2Desc, (CASE WHEN d.track3Desc='' THEN '' ELSE d.track3Desc END) as track3Desc, (CASE WHEN d.track4Desc='' THEN '' ELSE d.track4Desc END) as track4Desc, d.shiftsperDay, d.prodMeasure, d.rnCount, d.rn1Count, d.rn2Count, d.showEpic, CONCAT(ROUND(n.nproductivity,1),'%') as prod, u.last_name as charge, o.last_name as submitted, d.oneto1Acuity, d.oneto2Acuity, d.oneto3Acuity, d.oneto4Acuity, d.oneto5Acuity, d.oneto6Acuity, d.dept as deptName, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal, d.useGrid FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.dow=DAYOFWEEK(n.dayDate) left join `productiveAccount` as a on a.id=n.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=a.label LEFT OUTER JOIN `productiveEnterprise` as e on e.id=n.accountId left outer join `productiveUser` as o on o.id=n.userId left outer Join `productiveUser` as u on u.id=n.userId where d.active=1 AND n.dayDate =DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') AND n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.prodMeasure1 ASC, d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L','',0,$deptId);

$w5=13;
$w6=13;
$w7=18;
$w8=14;
$w9=14;
$w10=14;
$w11=14;
$w15=15;
$x4=19;
$x5=18;
$x6=18;
$x7=19;
$x8=19;
$x9=19;
$x10=19;
if(strlen($data2[0]['chargeDesc'] ?? '')==0){
	$w5 = 0;
}
if(strlen($data2[0]['nurseDesc'] ?? '')==0){
	$w6 = 0;
}
if(strlen($data2[0]['nurse1Desc'] ?? '')==0){
	$w7 = 0;
}
if(strlen($data2[0]['nurse2Desc'] ?? '')==0){
	$w8 = 0;
}
if(strlen($data2[0]['techLabel'] ?? '')==0){
	$w9 = 0;
}
if(strlen($data2[0]['secLabel'] ?? '')==0){
	$w10 = 0;
}
if(strlen($data2[0]['sittersNEWDesc'] ?? '')==0 && strlen($data2[0]['other1Desc'] ?? '')==0 && strlen($data2[0]['other2Desc'] ?? '')==0 && strlen($data2[0]['other3Desc'] ?? '')==0){
	$w11 = 0;
}
if(intval($data2[0]['skill1'])==0){
	$x4 = 0;
}
if(intval($data2[0]['skill2'])==0){
	$x5 = 0;
}
if(intval($data2[0]['skill3'])==0){
	$x6 = 0;
}
if(intval($data2[0]['skill4'])==0){
	$x7 = 0;
}
if(intval($data2[0]['skill5'])==0){
	$x8 = 0;
}
if(intval($data2[0]['skill6'])==0){
	$x9 = 0;
}
if(intval($data2[0]['skill7'])==0 && intval($data2[0]['skill8'])==0 && intval($data2[0]['skill9'])==0 && intval($data2[0]['skill10'])==0){
	$x10 = 0;
}
if(intval($data2[0]['useGrid'])==0 || intval($data2[0]['useGrid'])==5){
	$w15 = 0;
}
	

if(intval($data2[0]['prodMeasure'])==2 || intval($data2[0]['prodMeasure'])==3){
$pdf->SetWidths(array(33, 27, 24, $x4, $x5, $x6, $x7, $x8, $x9, $x10, 17, 55));
}else{
$pdf->SetWidths(array(33, 27, 24, 10, $w5, $w6, $w7, $w8, $w9, $w10, $w11, 12, $w15, 62));	
}


foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	
	if($row2['userId']==0){
	$charge = '';
	$triage = '';
	$blt = '';
	$rn = '';
	$techs = '';
	$sec = '';
	$nurse2 = '';
	$sitters = '';
	$orien = '';
	$other = '';
	$patients = '';
	$note = '';
	$nvariance = '';
	$gvariance = '';
	$submittedby = '';
	$res1 ='';
	$res2 ='';
	$res3 ='';
	$res4 ='';
	$res5 ='';
	$res6 ='';
	$res8 ='';
	$otherval ='';
	}else{
	$deptId = $row2['deptId'];
	
	if(floatval($row2['leadvar'])==0 || intval($row2['useGrid'])!=2){
	$leadvar = '';
	}else{
	$leadvar = "(" . floatval($row2['leadvar']) . ")";
	}
	if(floatval($row2['dcvar'])==0 || intval($row2['useGrid'])!=2){
	$dcvar = '';
	}else{
	$dcvar = "(" . floatval($row2['dcvar']) . ")";
	}
	if(floatval($row2['fswvar'])==0 || intval($row2['useGrid'])!=2){
	$fswvar = '';
	}else{
	$fswvar = "(" . floatval($row2['fswvar']) . ")";
	}
	if(floatval($row2['retailvar'])==0 || intval($row2['useGrid'])!=2){
	$retailvar = '';
	}else{
	$retailvar = "(" . floatval($row2['retailvar']) . ")";
	}
	if(floatval($row2['cookvar'])==0 || intval($row2['useGrid'])!=2){
	$cookvar = '';
	}else{
	$cookvar = "(" . floatval($row2['cookvar']) . ")";
	}
	if(floatval($row2['coffeevar'])==0 || intval($row2['useGrid'])!=2){
	$coffeevar = '';
	}else{
	$coffeevar = "(" . floatval($row2['coffeevar']) . ")";
	}
	if(floatval($row2['lleadvar'])==0 || intval($row2['useGrid'])!=2){
	$lleadvar = '';
	}else{
	$lleadvar = "(" . floatval($row2['lleadvar']) . ")";
	}
	if(floatval($row2['lfswvar'])==0 || intval($row2['useGrid'])!=2){
	$lfswvar = '';
	}else{
	$lfswvar = "(" . floatval($row2['lfswvar']) . ")";
	}
	if(floatval($row2['skill9var'])==0 || intval($row2['useGrid'])!=2){
	$skill9var = '';
	}else{
	$skill9var = "(" . floatval($row2['skill9var']) . ")";
	}
	if(floatval($row2['skill10var'])==0 || intval($row2['useGrid'])!=2){
	$skill10var = '';
	}else{
	$skill10var = "(" . floatval($row2['skill10var']) . ")";
	}
	if(intval($row2['useGrid'])==2 && (floatval($row2['lleadvar'])!=0 || floatval($row2['lfswvar'])!=0 || floatval($row2['skill9var'])!=0 || floatval($row2['skill10var'])!=0)){
	$othervar1 = floatval($row2['lleadvar']) + floatval($row2['lfswvar']) + floatval($row2['skill9var']) + floatval($row2['skill10var']);
	$othervar = "(" . $othervar1 . ")";
	}else{
	$othervar = '';
	}
	if(intval($row2['skill7'])==0 && intval($row2['skill8'])==0 && intval($row2['skill9'])==0 && intval($row2['skill10'])==0){
	$otherval = '';
	}else{
	$otherval2 = floatval($row2['skill8val']) + floatval($row2['skill8val']) + floatval($row2['skill9val']) + floatval($row2['skill10val']);
	$otherval = $otherval2 . $othervar;
	}
	//$prod = $row2['prod'];
	if(intval($row2['skill1'])==0){
	$res1 ='';
	}else{
	$res1 = floatval($row2['skill1val']) . $leadvar;
	}
	
	if(intval($row2['skill2'])==0){
	$res2 ='';
	}else{
	$res2 = floatval($row2['skill2val']) . $cookvar;
	}
	
	if(intval($row2['skill3'])==0){
	$res3 ='';
	}else{
	$res3 = floatval($row2['skill3val']) . $dcvar;
	}
	
	if(intval($row2['skill4'])==0){
	$res4 ='';
	}else{
	$res4 = floatval($row2['skill4val']) . $fswvar;
	}
	
	if(intval($row2['skill5'])==0){
	$res5 ='';
	}else{
	$res5 = floatval($row2['skill5val']) . $retailvar;
	}
	
	if(intval($row2['skill6'])==0){
	$res6 ='';
	}else{
	$res6 = floatval($row2['skill6val']) . $coffeevar;
	}
	
	if(intval($row2['skill7'])==0 && intval($row2['skill8'])==0 && intval($row2['skill9'])==0 && intval($row2['skill10'])==0){
	$res8 ='';
	}else{
	$res8 = $otherval;
	}
	
	if(intval($row2['practiceId'])==1){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}

	//$charge = round($row2['chargecount'],1);
	if(strlen($row2['chargeDesc'] ?? '')==0){
	$charge='';
	}else{
	$charge = round(floatval($row2['chargecount']),0);
	}
	if(strlen($row2['nurseDesc'] ?? '')==0){
	$rn='';
	}else{
	$rn = round(floatval($row2['antecount']),0);
	}
	if(strlen($row2['nurse1Desc'] ?? '')==0){
	$triage='';
	}else{
	$triage = $row2['customNurse'];
	}
	if(strlen($row2['nurse2Desc'] ?? '')==0){
	$nurse2='';
	}else{
	$nurse2 = $row2['customNurse2'];
	}
	if(strlen($row2['techLabel'] ?? '')==0){
	$techs='';
	}else{
	$techs = $row2['techcount'];
	}
	if(strlen($row2['secLabel'] ?? '')==0){
	$sec='';
	}else{
	$sec = $row2['seccount'];
	}
	$other =  $row2['sittercount'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$nvariance = round(floatval($row2['nvariance']),1);
	//$gvariance = round(floatval($row2['gvariance']),1);
	if(intval($row2['useGrid'])==0 || intval($row2['useGrid'])==5){
	$gvariance = '';
	}else{
	$gvariance = round(floatval($row2['gvariance']),1);
	}

		
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	
	//$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	}
if(intval($data2[0]['prodMeasure'])!=2 && intval($data2[0]['prodMeasure'])!=3){
$pdf->Row(array($row2['deptName'],$date,$submittedby,$patients,$charge,$rn,$triage,$nurse2,$techs,$sec,$other,$nvariance,$gvariance,$note));
}else{
$pdf->Row(array($row2['deptName'],$date,$submittedby,$res1,$res2,$res3,$res4,$res5,$res6,$otherval,$gvariance,$note));	
}

}
if(strlen($row2['track1Desc'] ?? '')>0 || strlen($row2['track2Desc'] ?? '')>0 || strlen($row2['track3Desc'] ?? '')>0 || strlen($row2['track4Desc'] ?? '')>0){
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage2('L','',0,$deptId);

$a1=38;
$a2=38;
$a3=38;
$a4=40;

$b1=30;
$b2=30;
$b3=30;
$b4=25;
$b5=25;
$b6=25;


if(strlen($row2['track1Desc'] ?? '')==0){
$a1=0;
$b3=0;	
}
if(strlen($row2['track2Desc'] ?? '')==0){
$a2=0;	
$b4=0;
}
if(strlen($row2['track3Desc'] ?? '')==0){
$a3=0;
$b5=0;	
}
if(strlen($row2['track4Desc'] ?? '')==0){
$a4=0;
$b6=0;	
}
if(strlen($row2['customDesc'] ?? '')==0){
$b1=0;	
}
if(strlen($row2['customDesc2'] ?? '')==0){
$b2=0;	
}


if(intval($row2['prodMeasure'])==2 || intval($row2['prodMeasure'])==3){
$pdf->SetWidths2(array(42, 27, 30, 20, $b1, $b2, $b3, $b4, $b5, $b6));
}else if(intval($row2['prodMeasure'])!=2 && intval($row2['prodMeasure'])!=3 && intval($row2['showEpic'])==1){
$pdf->SetWidths2(array(42, 27, 25, 10, 20, $a1, $a2, $a3, $a4));	
}else{
$pdf->SetWidths2(array(42, 27, 25, 10, $a1, $a2, $a3, $a4));	
}

foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName'];
	$oneto1 = 0;
	$oneto2 = 0;
	$oneto3 = 0;
	$oneto4 = 0;
	$oneto5 = 0;
	$oneto6 = 0;
	$twoto1 = 0;
	
	if($row2['userId']==0){
	$es1 = '';
	$es2 = '';
	$es3 = '';
	$es4 = '';
	$es5 = '';
	$esp = '';
	$gr10 = '';
	$gr5 = '';
	$pdg = '';
	$brd = '';
	$wait = '';
	$patients = '';
	$note = '';
	$track1 = '';
	$track2 = '';
	$track3 = '';
	$track4 = '';
	$submittedby = '';
	$custom1 = '';
	$custom2 = '';
	$custom3 = '';
	$custom4 = '';
	$census = '';
	}else{

	if(intval($row2['showEpicNurse'])==1){
	$gr10 = ROUND(intval($row2['epicScore']) / intval($row2['antecount']),1);
	}else{
	$gr10 = $row2['epicScore'];
	}
	
	$patients = $row2['atotal'] + $row2['patientCount2'];
	if(strlen($row2['track1Desc'] ?? '')==0){
	$track1='';
	}else{
	$track1 = $row2['track1'];
	}
	if(strlen($row2['track2Desc'] ?? '')==0){
	$track2='';
	}else{
	$track2 = $row2['track2'];
	}
	if(strlen($row2['track3Desc'] ?? '')==0){
	$track3='';
	}else{
	$track3 = $row2['track3'];
	}
	if(strlen($row2['track4Desc'] ?? '')==0){
	$track4='';
	}else{
	$track4 = $row2['track4'];
	}
	$note = stripslashes($row2['note']);
	$census = intval($row2['procedureCount']);
	
	if(strlen($row2['customDesc'] ?? '')==0){
	$custom1 ='';
	}else{
	$custom1 = intval($row2['whpCustom']);
	}
	if(strlen($row2['customDesc2'] ?? '')==0){
	$custom2 ='';
	}else{
	$custom2 = intval($row2['whpCustom2']);
	}
	$custom3 = intval($row2['whpCustom3']);
	$custom4 = intval($row2['whpCustom4']);
	if(intval($row2['practiceId'])==1){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}
	}	
if(intval($row2['prodMeasure'])==2 || intval($row2['prodMeasure'])==3){
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$census,$custom1,$custom2,$track1,$track2,$track3,$track4));	
}else if(intval($row2['prodMeasure'])!=2 && intval($row2['prodMeasure'])!=3 && intval($row2['showEpic'])==1){
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$gr10,$track1,$track2,$track3,$track4));	
}else{
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$track1,$track2,$track3,$track4));
}

}
}
if(intval($row2['prodMeasure'])!=2 && intval($row2['prodMeasure'])!=3 && (strlen($row2['desc7'] ?? '')>0 || strlen($row2['desc8'] ?? '')>0 || strlen($row2['desc9'] ?? '')>0 || strlen($row2['desc10'] ?? '')>0 || strlen($row2['desc11'] ?? '')>0 || strlen($row2['desc12'])>0) ?? ''){
//if(strlen($row2['desc7'])>0 || strlen($row2['desc8'])>0 || strlen($row2['desc9'])>0 || strlen($row2['desc10'])>0 || strlen($row2['desc11'])>0 || strlen($row2['desc12'])>0){
//$pdf->AliasNbPages();
//$pdf->SetFont('Arial','',9);
//$pdf->AddPage2('L','',0,$deptId);
$pdf->Ln();
$pdf->Ln();
$pdf->Header3($deptId);
$pdf->SetFont('Arial','',9);

$count = 0;

if(strlen($row2['desc7'] ?? '')==0){
$b7=0;
}else{
$b7=1;	
$count=$count+1;
}
if(strlen($row2['desc8'] ?? '')==0){
$b8=0;	
}else{
$b8=1;	
$count=$count+1;
}
if(strlen($row2['desc9'] ?? '')==0){
$b9=0;	
}else{
$b9=1;	
$count=$count+1;
}
if(strlen($row2['desc10'] ?? '')==0){
$b10=0;	
}else{
$b10=1;	
$count=$count+1;
}
if(strlen($row2['desc11'] ?? '')==0){
$b11=0;	
}else{
$b11=1;	
$count=$count+1;
}
if(strlen($row2['desc12'] ?? '')==0){
$b12=0;	
}else{
$b12=1;	
$count=$count+1;
}

if($count>0){
	$count1 = ROUND((170 / $count),0);
}else{
	$count1= 0;
}

if($count1 >40){
	$count1 = 40;
}

$b7= $b7 * $count1;
$b8= $b8 * $count1;
$b9= $b9 * $count1;
$b10= $b10 * $count1;
$b11= $b11 * $count1;
$b12= $b12 * $count1;

$pdf->SetWidths2(array(42, 27, 25, 10, $b7, $b8, $b9, $b10, $b11, $b12));


foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName'];
	$deptId = $row2['deptId'];
	
	if($row2['userId']==0){
	$note = "-";
	$submittedby = "-";
	$ptd7 = '';
	$ptd8 = '';
	$ptd9 = '';
	$ptd10 = '';
	$ptd11 = '';
	$ptd12 = '';
	$patients = '';
	}else{
		
	$patients = $row2['atotal'];
	if(strlen($row2['desc7'] ?? '')==0){
	$ptd7='';
	}else{
	$ptd7 = $row2['oneto7'];
	}
	if(strlen($row2['desc8'] ?? '')==0){
	$ptd8='';
	}else{
	$ptd8 = $row2['oneto8'];
	}
	if(strlen($row2['desc9'] ?? '')==0){
	$ptd9='';
	}else{
	$ptd9 = $row2['oneto9'];
	}
	if(strlen($row2['desc10'] ?? '')==0){
	$ptd10='';
	}else{
	$ptd10 = $row2['oneto10'];
	}
	if(strlen($row2['desc11'] ?? '')==0){
	$ptd11='';
	}else{
	$ptd11 = $row2['oneto11'];
	}
	if(strlen($row2['desc12'] ?? '')==0){
	$ptd12='';
	}else{
	$ptd12 = $row2['oneto12'];
	}
	
	if(intval($row2['practiceId'])==1){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}
	}	

$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$ptd7,$ptd8,$ptd9,$ptd10,$ptd11,$ptd12),$deptId);	




}
}
}
}

$filename="/home/prnadmin/public_html/dailyreports/". substr($data2[0]['accountName'],0,5) . $data2[0]['filedate'] . ".pdf";
$pdf->Output($filename,'F');
//$pdf->Output();


$addresses = Config::get('db') -> get_results("select * from `productiveEmailDist` where `accountId`={$accountId} AND `deptId`=1 AND `unitsId`={$unitsId} AND `sendEmail`=1 AND `emailAddress` LIKE '%@%' GROUP BY `emailAddress`");


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
}

$mail->AddBCC('rstrenger@productivern.com');  // Add a recipient



// Attachments
$mail->addAttachment('/home/prnadmin/public_html/dailyreports/' . substr($data2[0]['accountName'],0,5) . $data2[0]['filedate'] . '.pdf');// Add attachments
$mail->AddEmbeddedImage('/home/prnadmin/public_html/dailyreports/'. substr($data2[0]['accountName'],0,3).'Image'.$data2[0]['filedate'].'.jpg', ''. substr($data4[0]['accountName'],0,3).'Image');
$mail->IsHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Daily Compliance Reporting Details: ' . $data2[0]['accountName'] . ' ('. $data2[0]['reportdate'] . ')';
$mail->Body    = '<p>' . $data2[0]['accountName'] . ' Daily Compliance Reporting ('. $data2[0]['reportdate'] .')</p>'
.'</br>'
.'<img src="cid:'. substr($data2[0]['accountName'],0,3).'Image">';
//$mail->AltBody = 'Your ProductiveRN Variance Summary is attached.';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';


}
}


?>
