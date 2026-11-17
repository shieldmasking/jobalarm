<?php
include "initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
require_once '.././inc/FPDF/fpdf.php';


class PDF_MC_Table extends FPDF{
var $widths;
var $aligns = array('L','C','L','R','R','R','R','R','R','R','L');
//$acct = 2;

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
	
//$userId = Config::get('account')['id'];
//$accountId = Config::get('account')['accountId'];
//$role = Config::get('account')['role'];
//$enterpriseId =  Config::get('account')['enterpriseId'];


if(intval($role)<8){
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND a.active>0";
	}else if(intval($role)>=8 && intval($role)<=10) {
		$searchAdd = " AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = " AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = " AND t.enterpriseId={$enterpriseId} AND a.active>0";
	}else{
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
}

$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.dayDate = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d')$searchAdd group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}


$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

	$header = array('Unit', 'Director', 'Manager', 'Compliance');
	$header2 = array('Unit', 'Date/Shift', 'User', 'PTs/Procs', 'RNs', 'Techs', 'Other', 'RN Var', 'GRID Var' , 'HRS Var' , 'Notes');
   
	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
	if ($this->page <= $pages){
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
	$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(80, 40, 40, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
	}else{
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Compliance Report Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	//$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(55, 30, 20, 20, 10, 15, 15, 20, 20, 20, 55);
    // Header
	$this->SetFont('Arial','B',11);
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
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

function ImprovedTable($data){
    $w = array(80, 40, 40, 30);
    foreach($data as $row){
	if($row['compliance']>0.000){
		$compliance = ROUND($row['compliance'],1) . '%';
	}else{
		$compliance = 'No Data';
	}
    
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$row['Manager'],'LRB');
        $this->Cell($w[3],6,$compliance,'LRB',1,'R');
        
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}

function header2($header2){
    // Column widths
    $w = array(55, 30, 20, 20, 10, 15, 15, 20, 20, 20, 55);
    // Header
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
}


}
 //end class.fpdf