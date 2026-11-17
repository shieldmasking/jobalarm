<?php
error_reporting(E_ALL); 
include '../inc/initializer.php';
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
$startDate = isset($_REQUEST['start']) && ($_REQUEST['start'] != '') ? $_REQUEST['start'] : '';
$endDate = isset($_REQUEST['end']) && ($_REQUEST['end'] != '') ? $_REQUEST['end'] : '';
$userId = Config::get('account')['id'];
$acct = 19;
//$acct = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];

//$header = array('Unit', 'Director', 'Compliance', 'Hours Variance');

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT('{$startDate}','%m/%d/%y') AS startDay, DATE_FORMAT('{$endDate}','%m/%d/%y') AS endDay FROM `productiveAccount` a where a.id={$acct} group by a.id");
if($newdata[0]['displayProd']==0){
	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'Prod%', 'WHPUOS', 'RN Var', 'Pts/UOS', 'Hours', 'RNs', 'Techs', 'Notes');
}else{
	$header = array('Unit', 'Leader', 'Compliance', 'Hours Variance');
	$header2 = array('Unit', 'Date/Shift', 'Prod%', 'WHPUOS', 'Hrs Var', 'Pts/UOS', 'Hours', 'RNs', 'Techs', 'Notes');
}
	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
	if ($this->page == 1){
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Variance Report Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(75, 30, 40, 40);
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
    $w = array(55, 30, 15, 20, 20, 20, 15, 15, 15, 65);
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
$pdf = new PDF_MC_Table();
//$acct2 = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';
$startDate = isset($_REQUEST['start']) && ($_REQUEST['start'] != '') ? $_REQUEST['start'] : '2019-09-15';
$endDate = isset($_REQUEST['end']) && ($_REQUEST['end'] != '') ? $_REQUEST['end'] : '2019-09-15';
$userId = Config::get('account')['id'];
$acct2 = 19;
//$acct2 = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];

if(intval($acct2)==8){
	$sort = "ASC";
}else{
	$sort = "DESC";
}

$data = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.productivityPosNeg, n.actualWHP, a.name as accountName, a.displayProd, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, ROUND(SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN (n.activeRecord=1 and n.userId=0 and n.planSubmitted=1) THEN 1 ELSE 0 END)) as plans, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + SUM(CASE WHEN (n.planSubmitted>0 AND n.userId=0) THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.accountId={$acct2} AND n.dayDate >= '{$startDate}' AND n.dayDate <= '{$endDate}' group by n.accountId, n.deptId  
ORDER BY hrsVarianceTotal $sort");
$data2 = Config::get('db') -> get_results("select n.atotal, n.deptId, n.id as recordId, n.ltotal, n.ocount, n.nvariance, n.ldcount, n.obed, a.displayProd, n.procedureCount, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, n.actualWHP, n.antecount, n.chargecount, n.customNurse, n.seccount, n.sittercount, n.techcount, n.totalHours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.shiftsperDay, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=n.userId where n.activeRecord>0 AND d.active>0 AND n.accountId={$acct2} AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <= '{$endDate}' group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");
//$data3 = Config::get('db') -> get_results("select n.atotal, (CASE n.deptId>=0 then 2 ELSE 2 END) as type, n.totalHours as hours, n.procedureCount as visits, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d'), ' - ', s.shift) as shiftName, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.userId=0 THEN 1 ELSE 0 END)) as noreport, ((SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) + SUM((CASE WHEN n.userId=0 THEN 1 ELSE 0 END)))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=n.userId where d.active>0 AND n.accountId=19 AND d.prodMeasure=2 AND n.activeRecord>0 AND n.dayDate BETWEEN DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AND DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) group by n.accountId, n.deptId, n.dayDate, n.shift  
//ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P');
$pdf->ImprovedTable($data);
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(55, 30, 15, 20, 20, 20, 15, 15, 15, 65));
//$pdf->header2($header2);
foreach($data2 as $row2){
	
if(intval($row2['shiftsperDay'])>1){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
}else{
	$date = $row2['newDate'];
}

if(intval($row2['deptId'])==1){
	$prod = '-';
}else{
	$prod = $row2['prod'];
}

if(intval($row2['prodMeasure'])==2){
	$rn = '-';
	$techs = '-';
	$patients = $row2['procedureCount'];
	$totalHours = $row2['Hours'];
	$actualWHP = $row2['actualWHPTotal'];
	$note = $row2['note'];
	//$prod = $row2['prod'];
	$record ="https://productivern.com/dashboard.php#reportView?i=".$row2["recordId"];
	//$record = "<a href=\"https://productivern.com/dashboard.php#reportView?i=".$row2['recordId']."\" >" . $row2['deptName']."</a>";
}else{
	$rn = $row2['antecount'] + $row2['chargecount'] + $row2['ocount'] + $row2['ldcount'];
	$techs = $row2['techs'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'];
	$actualWHP = '-';
	$note = $row2['note'];
	//$prod = $row2['prod'];
	$totalHours = round((($row2['antecount'] + $row2['ocount'] + $row2['ldcount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24 / $row2['shiftsperDay']),1);
	$record ="https://productivern.com/dashboard.php#reportView?i=".$row2["recordId"];
	//$record = "<a href=\"https://productivern.com/dashboard.php#reportView?i=".$row2['recordId']."\" >" . $row2['deptName']."</a>";
}	

if(intval($row2['displayProd'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}

$pdf->Row(array($row2['deptName'],$date,$prod,$actualWHP,$variance,$patients,$totalHours,$rn,$techs,$note));
}

//$pdf->AddPage('L');
//$pdf->SetWidths(array(55, 30, 30, 15, 20, 15, 20, 60));
//$pdf->header2($header2);
//foreach($data3 as $row3){
//$pdf->Row(array($row3['deptName'],$row3['shiftName'],$row3['charge'],$row3['prod'],$row3['hrsVariance'],$row2['visits'],$row3['hours'],$row3['note']));
//}
//$pdf->DetailTable($header2,$data2);
$filename="/home/prnadmin/public_html/dailyreports/" . substr($data[0]['accountName'],0,3) . $data[0]['filedate'] . ".pdf";
//$pdf->Output($filename,'F');
$pdf->Output($filename,'D');

/*
$mail = new PHPMailer(true);
$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'productivern.com';                 // Specify main and backup server
$mail->Port = 465;                                    // Set the SMTP port
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'noreply@productivern.com';                // SMTP username
$mail->Password = 'QL_X^8z@J)90';                  // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted


$mail->From = 'noreply@productivern.com';
$mail->FromName = 'ProductiveRN Reports';
if(intval($acct2)==1){
$mail->AddAddress('rstrenger@productivern.com');  // Add a recipient
}else{
$mail->AddAddress('kevin.spellman@perfectshift.com');  // Add a recipient
$mail->AddAddress('ron.hughes@perfectshift.com');  // Add a recipient
$mail->AddAddress('melanie.eld@perfectshift.com');  // Add a recipient
}
//$mail->AddAddress($email1);  // Add a recipient

// Attachments
$mail->addAttachment('/home/prnadmin/public_html/dailyreports/' . substr($data[0]['accountName'],0,3) . $data[0]['filedate'] . '.pdf');// Add attachments
$mail->IsHTML(true);                                  // Set email format to HTML
$mail->Subject = 'ProductiveRN Variance Summary';
$mail->Body    = 'Your ProductiveRN Variance Summary is attached.';
$mail->AltBody = 'Your ProductiveRN Variance Summary is attached.';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}
*/
echo 'Message has been sent';


?>
