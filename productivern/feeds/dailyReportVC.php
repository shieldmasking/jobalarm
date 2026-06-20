<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';
require_once '../inc/FPDF/fpdf.php';
require_once '../inc/class.phpmailer.php';




class PDF_MC_Table extends FPDF{
var $widths;
var $aligns = array('L','C','R','R','R','R','R','R','R','L');

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
	// Column headings
//$newDate = '2019-08-16';
$acct = 20;
$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.accountId={$acct} AND n.dayDate = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$acct} group by a.id");

	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'Prod%', 'WHPUOS', 'Hrs Var', 'Pts or UOS', 'Hours', 'RNs', 'Total', 'Notes');
  
	
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
	$this->Cell(30,10,'Compliance Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(95, 30, 30, 35);
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
	$this->Cell(30,10,'Variance Detail',0,0,'C');
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
    $w = array(55, 30, 15, 20, 20, 22, 15, 15, 15, 65);
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
    $w = array(95, 30, 30, 35);
    foreach($data as $row){
		
	if($row['compliance']>0.000){
		$compliance = ROUND($row['compliance'],0) . '%';
		$hrsVar = ROUND($row['hrsVarianceTotal'],1);
		$varAvg = ROUND($row['varAvg'],1);
	}else{
		$compliance = 'No Data';
		$hrsVar = 'No Data';
		$varAvg = 'No Data';
	}
	
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$compliance,'LRB',0,'R');
        //$this->Cell($w[2],6,number_format($row['compliance'],0,'.','') . '%','LRB',0,'R');
        if($row['displayProd']==1){
		$this->Cell($w[3],6,$hrsVar,'LRB',1,'R');
        //$this->Cell($w[3],6,number_format($row['hrsVarianceTotal'],1,'.',','),'LRB',1,'R');
		}else{
		$this->Cell($w[3],6,$varAvg,'LRB',1,'R');
		//$this->Cell($w[3],6,number_format($row['varAvg'],1,'.',','),'LRB',1,'R');
		}
        //$this->Ln();
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}

function header2($header2){
    // Column widths
    $w = array(55, 30, 15, 20, 20, 22, 15, 15, 15, 65);
    // Header
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
}


function detailTable($header2, $data2){
    // Column widths
    $w = array(55, 30, 15, 20, 20, 22, 15, 15, 15, 65);
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
$pdf = new PDF_MC_Table();
$acct2 = 20;

$data = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, CEILING(AVG(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) as nuser, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where n.type!=2 AND d.active>0 AND n.accountId={$acct2} AND n.dayDate = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') group by n.accountId, n.deptId  
ORDER BY nuser DESC, hrsVarianceTotal ASC, compliance DESC, Director ASC, deptName ASC");

$data2 = Config::get('db') -> get_results("select n.atotal, n.otherNurse1, n.otherNurse2, n.otherNurse3, n.customNurse2, n.deptId, n.ltotal, n.ocount, n.nvariance, l.noreply, l.alertName, n.ldcount, n.obed, a.displayProd, n.procedureCount, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, n.actualWHP, n.antecount, n.chargecount, n.customNurse, n.seccount, n.sittercount, n.techcount, n.totalHours, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d') as newDate, s.shift as shiftName, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, d.prodMeasure, n.antecount, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label left outer Join `productiveUser` as u on u.id=n.userId where d.indMeasure=0 AND d.deptType !=3 AND d.active>0 AND n.accountId={$acct2} AND n.dayDate= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");



// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P');
$pdf->ImprovedTable($data);
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(55, 30, 15, 20, 20, 22, 15, 15, 15, 65));
//$pdf->header2($header2);
foreach($data2 as $row2){
	
if(intval($row2['shiftsperDay'])>1){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
}else{
	$date = $row2['newDate'];
}
	
if(intval($row2['prodMeasure'])==2){
	$rn = '-';
	$techs = '-';
	$patients = NUMBER_FORMAT(ROUND($row2['procedureCount'],0));
	$totalHours = ROUND($row2['Hours'],1);
	$actualWHP = $row2['actualWHPTotal'];
	$newProd = ROUND($row2['prod'],0) . '%';
}else{
	$rn = $row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'];
	$techs = round(($row2['antecount'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['customNurse2'] + $row2['chargecount'] + $row2['customNurse'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),0);
	$patients = NUMBER_FORMAT(ROUND(($row2['atotal'] + $row2['ltotal'] + $row2['obed']),0));
	$actualWHP = '-';
	$newProd = ROUND($row2['prod'],0) . '%';
	$totalHours = round((($row2['antecount'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['customNurse2'] + $row2['chargecount'] + $row2['customNurse'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24 / $row2['shiftsperDay']),1);
}

if(intval($row2['displayProd'])==1){
	$variance = ROUND($row2['hrsVarianceTotal'],1);
}else{
	$variance = ROUND($row2['nvariance'],1);
}


$pdf->Row(array($row2['deptName'],$date,$newProd,$actualWHP,$variance,$patients,$totalHours,$rn,$techs,$row2['note']));
}

$filename="/home/prnadmin/public_html/dailyreports/" . substr($data[0]['accountName'],0,3) . $data[0]['filedate'] . ".pdf";
$pdf->Output($filename,'F');
//$pdf->Output();

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


$mail->From = '' . $data2[0]['noreply'] . '';
$mail->FromName = '' . $data2[0]['alertName'] . '';
$mail->AddAddress('rstrenger@jobalarm.com');  // Add a recipient
//$mail->AddAddress('ron.hughes@perfectshift.com');  // Add a recipient
//$mail->AddAddress('susan.costello@perfectshift.com');  // Add a recipient

$mail->AddBCC('rstrenger@jobalarm.com');  // Add a recipient

//$newBCC = Config::get('db') -> get_results("select u.* FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id where u.active>0 AND u.accountId=8 AND u.email LIKE '%tuality.org' AND ((x.primaryUnit=6 OR x.primaryUnit=7) or (u.role>7 AND u.role<10)) group by u.email");
//foreach($newBCC as $bcc){
//$mail->AddBCC('' . $bcc['email'] . '');  // Add a recipient
//}

// Attachments
$mail->addAttachment('/home/prnadmin/public_html/dailyreports/' . substr($data[0]['accountName'],0,3) . $data[0]['filedate'] . '.pdf');// Add attachments

$mail->IsHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Dashboard Daily Compliance Report - Valley Care';
$mail->Body    = $data2[0]['alertName'] . ' Summary attached.';
//$mail->AltBody = 'Your ProductiveRN Variance Summary is attached.';
$mail->Send();

//if(!$mail->Send()) {
//   echo 'Message could not be sent.';
//   echo 'Mailer Error: ' . $mail->ErrorInfo;
//   exit;
//}

echo 'Message has been sent';


?>
