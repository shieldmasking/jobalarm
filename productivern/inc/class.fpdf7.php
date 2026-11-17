<?php
include "initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
require_once '.././inc/FPDF/fpdf.php';

class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','R','R','R','R','R','R','R','R','R','L');


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

function Header(){
	// Column headings
//$acct = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';
$acct = Config::get('account')['accountId'];
$userId = Config::get('account')['id'];
$acct = 8;
$userId = 411;
//$header = array('Unit', 'Director', 'Compliance', 'Hours Variance');

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$acct} group by a.id");

	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'Patients', 'Charge', 'Tri/BLR/Res', 'Tech/CNA', 'RN', 'PSA S.I.', 'Orient', 'Other', 'PSA Med', 'Notes');

	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Variance Report Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(45, 20, 18, 15, 25, 19, 15, 17, 14, 14, 18, 60);
    // Header
	$this->SetFont('Arial','B',11);
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
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

function ImprovedTable($data){
    $w = array(75, 30, 40, 40);
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
        $this->Cell($w[2],6,number_format($row['compliance'],1,'.','') . '%','LRB',0,'R');
		if($row['displayProd']==1){
        $this->Cell($w[3],6,number_format($row['hrsVarianceTotal'],1,'.',','),'LRB',1,'R');
		}else{
		$this->Cell($w[3],6,number_format($row['varAvg'],1,'.',','),'LRB',1,'R');
		}
        //$this->Ln();
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}


var $widths2;
var $aligns2 = array('L','C','R','R','R','R','R','R','R','R','R','L');


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

function Header2(){
	// Column headings
//$acct = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';
$acct = Config::get('account')['accountId'];
$userId = Config::get('account')['id'];
//$acct = 8;
//$userId = 411;
//$header = array('Unit', 'Director', 'Compliance', 'Hours Variance');

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$acct} group by a.id");
$header3 = array('Unit', 'Date/Shift', 'Patients', '1:1', '1:2', '1:3', '1:4', '1:5', 'RN Variance', 'Care RNs', 'Resources', 'Note');

	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Shift Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(30, 20, 16, 13, 13, 13, 13, 13, 25, 20, 25, 60);
    // Header
	$this->SetFont('Arial','B',11);
    for($i=0;$i<count($header3);$i++)
        $this->Cell($w[$i],7,$header3[$i],1,0,'C');
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
 //end class.fpdf