<?php
include "initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
require_once '.././inc/FPDF/fpdf.php';

class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','R','R','R','R','R','R','R','R','L');


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
//$acct = 19;
//$header = array('Unit', 'Director', 'Compliance', 'Hours Variance');

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$acct} group by a.id");
if($newdata[0]['displayProd']==0){
	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'Prod%', 'WHPUOS', 'RN Var', 'Pts/UOS', 'Hours', 'RN', 'Tech', 'Other', 'Notes');
}else{
	$header = array('Unit', 'Leader', 'Compliance', 'Hours Variance');
	$header2 = array('Unit', 'Date/Shift', 'Prod%', 'WHPUOS', 'Hrs Var', 'Pts/UOS', 'Hours', 'RN', 'Tech', 'Other', 'Notes');
}
	
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
    $w = array(50, 20, 15, 20, 20, 20, 15, 15, 15, 15, 65);
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

function header2($header2){
    // Column widths
    $w = array(55, 30, 15, 20, 20, 20, 15, 15, 15, 65);
    // Header
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
}


function detailTable($header2, $data2){
    // Column widths
    $w = array(55, 30, 15, 20, 20, 20, 15, 15, 15, 65);
    // Header
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data2 as $row2)
    {
        $this->Cell($w[0],6,$row2['deptName'],'LRB');
        $this->Cell($w[1],6,$row2['shiftName'],'LRB');
		//$this->Cell($w[2],6,$row2['charge'],'LRB');
		$this->Cell($w[2],6,number_format($row2['prod'],1,'.',''),'LRB',0,'R');
        $this->Cell($w[3],6,number_format($row2['actualWHP'],2,'.',''),'LRB',0,'R');
        $this->Cell($w[4],6,number_format($row2['hrsVariance'],1,'.',','),'LRB',0,'R');
		$this->Cell($w[5],6,number_format($row2['atotal']),'LRB',0,'R');
        $this->Cell($w[6],6,number_format($row2['rn']),'LRB',0,'R');
		$this->Cell($w[7],6,number_format($row2['rn']),'LRB',0,'R');
        $this->Cell($w[8],6,number_format($row2['techs']),'LRB',0,'R');
		$this->MultiCell(0,6,$row2['note'],1);
        //$this->Ln();
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}

}
 //end class.fpdf