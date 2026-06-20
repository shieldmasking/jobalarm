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

function Header5($accountId){
/*
$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId where d.active=1 and d.accountId={$accountId} group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}
*/


	$rptName = "Active Safety Issues";
	$w = array(35, 30, 25, 45, 70);
	$header = array('Unit', 'Date (days)', 'Location', 'Issue', 'Notes');

	

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

	//$header = array('Unit', 'Report', 'Manager', 'Completed');
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
	$this->SetTextColor(0,0,0);
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
	$this->Cell(30,10,$rptName,0,0,'C');
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
    //$w = array(40, 70, 30, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function Header4($accountId){
/*
$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId where d.active=1 and d.accountId={$accountId} group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}
*/


	$rptName = "Reporting Summary";
	$w = array(40, 70, 30, 30);
	$header = array('Unit', 'Report', 'Manager', 'Completed');

	

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

	//$header = array('Unit', 'Report', 'Manager', 'Completed');
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
	$this->SetTextColor(0,0,0);
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
	$this->Cell(30,10,$rptName,0,0,'C');
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
    //$w = array(40, 70, 30, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function ImprovedTable($dat){
    $w = array(40, 70, 30, 30);
    foreach($dat as $row){
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
	}else if(floatval($row['compliance'])<80 && floatval($row['compliance'])>1){
		$this->SetTextColor(255,0,0);
		$compliance = ROUND($row['compliance'],1) . '%';
	}else if(intval($row['compliance'])==1){
		$this->SetTextColor(0,128,0);
		$compliance = 'Yes';
	}else{
		$this->SetTextColor(255,0,0);
		$compliance = 'No';
	}
        $this->Cell($w[3],6,$compliance,'LRB',1,'C');
        
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}

function ImprovedTable2($dat){
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','B',10);	
    $w = array(35, 130, 25, 45, 70);
	/*
	
	for($i=0;$i<count($dat);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		$this->SetFont('Arial','B',10);	
		$this->Rect($x,$y,$w[$i],10);
		$this->MultiCell($w[$i],5,$dat[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
	}
    $this->Ln();
	$this->Ln();
	*/
    
		$x=$this->GetX();
        $y=$this->GetY(); 
		
		$this->Rect($x,$y,$w[0],6);		
		//$this->MultiCell($w[0],6,$dat['deptName'],0,'L');
		$this->SetXY($x+$w[0],$y);
		
		$this->Rect($x,$y,$w[1],6);
		//$this->MultiCell($w[1],6,$dat['dayDate'] . ' (' . $dat['daysOpen'] . ')',0,'L');
		$this->SetXY($x+$w[1],$y);
		
		$this->Rect($x,$y,$w[2],6);
		//$this->MultiCell($w[2],6,$dat['roomId1'],0,'L');
		$this->SetXY($x+$w[2],$y);
		
		$this->Rect($x,$y,$w[3],6);
		//$this->MultiCell($w[3],6,addslashes($dat['safetyDesc']),0,'C');
		$this->SetXY($x+$w[3],$y);
		
		$this->Rect($x,$y,$w[4],6);
		//$this->MultiCell($w[4],6,$dat['note1'],0,'L');
		
		$this->Ln(6);
    
	
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
if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && intval($newdata[0]['showEpic'])==1 && intval($newdata[0]['showEpicNurse'])==0){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','Epic','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 25, 10, 20, $a1, $a2, $a3, $a4);
}else if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && intval($newdata[0]['showEpicNurse'])==1){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','WIT/RN','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
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


//$dbMaster = Config::get('db') -> get_results("select a.*, (SELECT COUNT(`id`) FROM `productiveEmailDist` where `accountId`=a.id AND `deptId`=0 AND `sendEmail`=1) as emailCount  FROM `productiveAccount` a where a.active>0 AND `emailTime` !='00:00:00' AND TIME_FORMAT(`emailTime` - INTERVAL 1 HOUR, '%H') = TIME_FORMAT(CURRENT_TIME(), '%H') GROUP BY `id`");
$dbMaster = Config::get('db') -> get_results("select a.*, (SELECT COUNT(`id`) FROM `productiveEmailDist` where `accountId`=26 AND `deptId`=0 AND `sendEmail`=1) as emailCount  FROM `productiveAccount`a where `id`=26 GROUP BY `id`");

if($dbMaster){
foreach($dbMaster as $master){
$accountId = $master['id'];
//$emailCount = $master['emailCount'];	

$addresses = Config::get('db') -> get_results("select e.*, u.logix, l.labelName, l.login from `productiveEmailDist` e LEFT JOIN `productiveUser` as u on u.id=e.userId LEFT JOIN `productiveAccount` as a on a.id=e.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label where e.accountId={$accountId} AND e.deptId=0 AND e.sendEmail=1 AND e.emailAddress LIKE '%@%' GROUP BY e.emailAddress");

if($addresses){
foreach($addresses as $address){
$userId = $address['userId'];

$pdf = new PDF_MC_Table();

$data1 = Config::get('db') -> get_results("SELECT c.deptId, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate, a.id as accountId, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, a.name as accountName, d.dept as deptName, l.title as Director, i.last_name as Manager, (CASE WHEN c.userId>0 OR c.viewId>0 THEN 1 ELSE 0 END) as compliance FROM `productivecrashLog` c LEFT JOIN `ProductiveDept` as d on d.id = c.deptId LEFT JOIN `productivelogData` as l on l.id=c.logId left join `productiveAccount` as a on a.id=c.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `unitAssigned` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 AND `unitAssigned`=0 order by `unitAssigned` ASC LIMIT 0,1) WHERE l.active=1 AND c.dayDate=DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') AND c.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) GROUP BY c.id ORDER BY d.dept ASC, l.title ASC");

$data4 = Config::get('db') -> get_results("select n.deptId, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate, a.id as accountId, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, a.name as accountName, d.dept as deptName, 'Staffing Reports' as Director, i.last_name as Manager, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `unitAssigned` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 AND `unitAssigned`=0 order by `unitAssigned` ASC LIMIT 0,1) where d.active=1 AND n.dayDate=DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') and n.accountId={$accountId} and n.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) group by n.accountId, n.deptId ORDER BY compliance DESC, d.dept ASC");

/*
$data5 = Config::get('db') -> get_results("SELECT c.*, IFNULL(t.safetyDesc,'') AS safetyDesc, IFNULL(s.typeDesc,'') AS typeDesc, IFNULL(r.first_name,'') as first, IFNULL(r.last_name,'') as last, u.role, (case when c.deptId IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId` ={$userId} and `primaryUnit`>5) then '1' else '0' end) as xaccess, u.practiceId, CONCAT(SUBSTRING(u.first_name,1,1), '. ', u.last_name) as submitted, (case when c.createdBy IN (SELECT `userId` FROM `productiveDeptXref` WHERE `deptId` IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND `primaryUnit`<8) then '1' else '0' end) as access, CURDATE() AS daytoday, p.id acctId, DATEDIFF(CURDATE(),c.openedDate) as daysOpen, DATE_FORMAT(c.dueDate, '%m/%d/%y') as newDate, DATE_FORMAT(c.openedDate, '%m/%d/%y') as opened, IFNULL(d.dept,'') as deptName 
	FROM `productiveSafety` c 
	LEFT JOIN `productiveUser` as u on u.id=c.createdBy
    LEFT JOIN `productiveAccount` as p on p.id=u.accountId
	LEFT OUTER JOIN `ProductiveDept` as d on d.id=c.deptId
	LEFT OUTER JOIN `productivesafetyConfig` as t on t.id=c.safetyConfig
	LEFT OUTER JOIN `productivesafetyType` as s on s.id=t.safetyType
	LEFT OUTER JOIN `productiveUser` as r on r.id=c.updatedBy
	WHERE p.id={$accountId} AND c.active=1 OR (c.dayDate >= u.lastStartDate AND c.dayDate <= u.lastEndDate)
	GROUP BY c.id
	ORDER BY c.priority DESC, c.dueDate ASC, c.active DESC, d.dept ASC, c.roomId1 ASC");
*/
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage4('P','',0,$accountId);
$pdf->ImprovedTable($data4);
$pdf->ImprovedTable($data1);

//$pdf->Ln();
//$pdf->Ln();
//$pdf->Header5($accountId);
//$pdf->SetFont('Arial','',8);
//if($data5){
//foreach($data5 as $dat){
//$pdf->ImprovedTable2($dat);
//}
//}
$filename1="/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image.pdf";
$pdf->Output($filename1,'F');
$pdf = escapeshellarg( "/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image.pdf" );
$save = escapeshellarg( "/home/prnadmin/public_html/dailyreports/". substr($data4[0]['accountName'],0,3)."Image".$data4[0]['filedate'].".jpg" );
//$result = 0;
exec("convert -verbose -density 150 $pdf -quality 100 -flatten -sharpen 0x1.0 $save");


$mail = new PHPMailer(true);

//$mail->SMTPDebug = 2;    
$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'productivern.com';                 // Specify main and backup server
//$mail->Host = gethostbyname("smtp.productivern.com"); 
$mail->Port = 465;                                    // Set the SMTP port
$mail->SMTPAuth = true;                               // Enable SMTP authentication
//$mail->Encoding = '7bit';
$mail->Username = 'noreply@productivern.com';                // SMTP username
$mail->Password = 'QL_X^8z@J)90';                  // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

$mail->From = 'noreply@productivern.com';
$mail->FromName = $addresses[0]['labelName'];

foreach ($addresses as $address) {
    //$mail->AddAddress(trim($address['emailAddress']));
	//$logix = $address['logix'];
	$login = $address['login'];
}

$mail->AddAddress('shannon_strenger@pbrmc.com');  // Add a recipient
$mail->AddAddress('heather_joiner@pbrmc.com');  // Add a recipient
$mail->AddBCC('rstrenger@productivern.com');  // Add a recipient
//$mail->AddAddress('rstrenger@productivern.com');  // Add a recipient


// Attachments
//$mail->addAttachment('/home/prnadmin/public_html/dailyreports/' . substr($data4[0]['accountName'],0,5) . $data4[0]['filedate'] . '.pdf');// Add attachments
$mail->AddEmbeddedImage('/home/prnadmin/public_html/dailyreports/'. substr($data4[0]['accountName'],0,3).'Image'.$data4[0]['filedate'].'.jpg', ''. substr($data4[0]['accountName'],0,3).'Image');
$mail->IsHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Daily Reporting Summary: ' . $data4[0]['accountName'] . ' ('. $data4[0]['reportDate'] . ')';
$mail->Body    = '<p><a href="https://www.productivern.com/' . $login . '">LOGIN HERE</a></p>'
.'<p>' . $data4[0]['accountName'] . ' Daily Reporting Summary ('. $data4[0]['reportDate'] .')</p><br><img src="cid:'. substr($data4[0]['accountName'],0,3).'Image">';


if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';

}
}
}

}



?>
