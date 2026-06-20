<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);
$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";



?><!-- BEGIN: Subheader -->

<div class="m-subheader pull-right">
    <div class="d-flex align-items-center">
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            <div class="pull-right" style="margin-left:15px;margin-top:2px">
           </div>
    </div>
</div>
	<!--
	<?php if($role >4): ?>
	<div class="m-subheader__title ">Productivity Goal: <span id="targetProd"></span>%
            </div>
	<?php endif; ?>
	-->

<!-- END: Subheader -->
<div class="m-content">
<div id="content">
<div id="hide1">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <div class="m-subheader__title ">
                <span id="dept1"></span>
            </div>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
 <div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var1">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber1">0</div>
                    <div class="desc">
                        <span id="variance1"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod1">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber1">0</div>
                    <div class="desc">
                        <span id="productivity1"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber1">0</div>
                    <div class="desc">
                        <span id="planned1"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber1">0</div>
                    <div class="desc">
                        <span id="procedures1"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc1">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked1">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked1"></span>
						<?php }else{ ?>
						<span id="blockedbeds1"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc1">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation1"></div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hide2">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept2"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var2">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber2">0</div>
                    <div class="desc">
                        <span id="variance2"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod2">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber2">0</div>
                    <div class="desc">
                        <span id="productivity2"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber2">0</div>
                    <div class="desc">
                        <span id="planned2"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber2">0</div>
                    <div class="desc">
                        <span id="procedures2"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc2">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked2">0</div>
                    
					<div class="desc">
						<?php if($role >4){ ?>
                        <span id="blocked2"></span>
						<?php }else{ ?>
						<span id="blockedbeds2"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc2">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation2">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hide3">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept3"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
 <div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var3">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber3">0</div>
                    <div class="desc">
                        <span id="variance3"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod3">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber3">0</div>
                    <div class="desc">
                        <span id="productivity3"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber3">0</div>
                    <div class="desc">
                        <span id="planned3"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber3">0</div>
                    <div class="desc">
                        <span id="procedures3"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc3">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked3">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked3"></span>
						<?php }else{ ?>
						<span id="blockedbeds3"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc3">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation3">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide4">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept4"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var4">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber4">0</div>
                    <div class="desc">
                        <span id="variance4"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod4">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber4">0</div>
                    <div class="desc">
                        <span id="productivity4"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber4">0</div>
                    <div class="desc">
                        <span id="planned4"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber4">0</div>
                    <div class="desc">
                        <span id="procedures4"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc4">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked4">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked4"></span>
						<?php }else{ ?>
						<span id="blockedbeds4"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc4">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation4">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide5">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept5"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var5">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber5">0</div>
                    <div class="desc">
                        <span id="variance5"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod5">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber5">0</div>
                    <div class="desc">
                        <span id="productivity5"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber5">0</div>
                    <div class="desc">
                        <span id="planned5"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber5">0</div>
                    <div class="desc">
                        <span id="procedures5"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc5">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked5">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked5"></span>
						<?php }else{ ?>
						<span id="blockedbeds5"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc5">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation5">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide6">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept6"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var6">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber6">0
					</div>
                    <div class="desc">
                        <span id="variance6"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod6">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber6">0
					</div>
                    <div class="desc">
                        <span id="productivity6"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber6">0
					</div>
                    <div class="desc">
                        <span id="planned6"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber6">0
					</div>
                    <div class="desc">
                        <span id="procedures6"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc6">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked6">0
					</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked6"></span>
						<?php }else{ ?>
						<span id="blockedbeds6"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc6">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation6">0
					</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide7">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept7"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var7">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber7">0</div>
                    <div class="desc">
                        <span id="variance7"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod7">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber7">0</div>
                    <div class="desc">
                        <span id="productivity7"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber7">0</div>
                    <div class="desc">
                        <span id="planned7"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber7">0</div>
                    <div class="desc">
                        <span id="procedures7"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc7">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked7">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked7"></span>
						<?php }else{ ?>
						<span id="blockedbeds7"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc7">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation7">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide8">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept8"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var8">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber8">0</div>
                    <div class="desc">
                        <span id="variance8"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod8">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber8">0</div>
                    <div class="desc">
                        <span id="productivity8"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber8">0</div>
                    <div class="desc">
                        <span id="planned8"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber8">0</div>
                    <div class="desc">
                        <span id="procedures8"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc8">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked8">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked8"></span>
						<?php }else{ ?>
						<span id="blockedbeds8"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc8">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation8">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide9">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept9"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var9">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber9">0</div>
                    <div class="desc">
                        <span id="variance9"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod9">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber9">0</div>
                    <div class="desc">
                        <span id="productivity9"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber9">0</div>
                    <div class="desc">
                        <span id="planned9"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber9">0</div>
                    <div class="desc">
                        <span id="procedures9"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc9">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked9">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked9"></span>
						<?php }else{ ?>
						<span id="blockedbeds9"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc9">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation9">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide10">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept10"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="var10">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber10">0</div>
                    <div class="desc">
                        <span id="variance10"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="prod10">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber10">0</div>
                    <div class="desc">
                        <span id="productivity10"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber10">0</div>
                    <div class="desc">
                        <span id="planned10"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber10">0</div>
                    <div class="desc">
                        <span id="procedures10"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="bloc10">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked10">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked10"></span>
						<?php }else{ ?>
						<span id="blockedbeds10"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat" id="esc10">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation10">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hide11">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <div class="m-subheader__title ">
                <span id="dept11"></span>
            </div>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
 <div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber11">0</div>
                    <div class="desc">
                        <span id="variance11"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber11">0</div>
                    <div class="desc">
                        <span id="productivity11"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber11">0</div>
                    <div class="desc">
                        <span id="planned11"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber11">0</div>
                    <div class="desc">
                        <span id="procedures11"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked11">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked11"></span>
						<?php }else{ ?>
						<span id="blockedbeds11"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation11">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hide12">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept12"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber12">0</div>
                    <div class="desc">
                        <span id="variance12"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber12">0</div>
                    <div class="desc">
                        <span id="productivity12"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber12">0</div>
                    <div class="desc">
                        <span id="planned12"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber12">0</div>
                    <div class="desc">
                        <span id="procedures12"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked12">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked12"></span>
						<?php }else{ ?>
						<span id="blockedbeds12"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation12">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hide13">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept13"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
 <div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber13">0</div>
                    <div class="desc">
                        <span id="variance13"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber13">0</div>
                    <div class="desc">
                        <span id="productivity13"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber13">0</div>
                    <div class="desc">
                        <span id="planned13"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber13">0</div>
                    <div class="desc">
                        <span id="procedures13"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked13">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked13"></span>
						<?php }else{ ?>
						<span id="blockedbeds13"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation13">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide14">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept14"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber14">0</div>
                    <div class="desc">
                        <span id="variance14"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber14">0</div>
                    <div class="desc">
                        <span id="productivity14"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber14">0</div>
                    <div class="desc">
                        <span id="planned14"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber14">0</div>
                    <div class="desc">
                        <span id="procedures14"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked14">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked14"></span>
						<?php }else{ ?>
						<span id="blockedbeds14"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation14">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide15">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept15"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber15">0</div>
                    <div class="desc">
                        <span id="variance15"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber15">0</div>
                    <div class="desc">
                        <span id="productivity15"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber15">0</div>
                    <div class="desc">
                        <span id="planned15"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber15">0</div>
                    <div class="desc">
                        <span id="procedures15"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked15">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked15"></span>
						<?php }else{ ?>
						<span id="blockedbeds15"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation15">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide16">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept16"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber16">0
					</div>
                    <div class="desc">
                        <span id="variance16"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber16">0
					</div>
                    <div class="desc">
                        <span id="productivity16"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber16">0
					</div>
                    <div class="desc">
                        <span id="planned16"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber16">0
					</div>
                    <div class="desc">
                        <span id="procedures16"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked16">0
					</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked16"></span>
						<?php }else{ ?>
						<span id="blockedbeds16"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation16">0
					</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide17">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept17"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber17">0</div>
                    <div class="desc">
                        <span id="variance17"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber17">0</div>
                    <div class="desc">
                        <span id="productivity17"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber17">0</div>
                    <div class="desc">
                        <span id="planned17"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber17">0</div>
                    <div class="desc">
                        <span id="procedures17"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked17">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked17"></span>
						<?php }else{ ?>
						<span id="blockedbeds17"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation17">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide18">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept18"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">  
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber18">0</div>
                    <div class="desc">
                        <span id="variance18"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber18">0</div>
                    <div class="desc">
                        <span id="productivity18"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber18">0</div>
                    <div class="desc">
                        <span id="planned18"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber18">0</div>
                    <div class="desc">
                        <span id="procedures18"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked18">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked18"></span>
						<?php }else{ ?>
						<span id="blockedbeds18"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation18">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide19">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept19"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber19">0</div>
                    <div class="desc">
                        <span id="variance19"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumbe19">0</div>
                    <div class="desc">
                        <span id="productivity19"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber19">0</div>
                    <div class="desc">
                        <span id="planned19"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber19">0</div>
                    <div class="desc">
                        <span id="procedures19"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked19">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked19"></span>
						<?php }else{ ?>
						<span id="blockedbeds19"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation19">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div id="hide20">
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <span id="dept20"></span>
            </h3>
        </div>
	 </div>
</div>

    <!--Begin::Main Portlet-->
<div class="row">   
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber20">0</div>
                    <div class="desc">
                        <span id="variance20"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                   
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber20">0</div>
                    <div class="desc">
                        <span id="productivity20"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber20">0</div>
                    <div class="desc">
                        <span id="planned20"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber20">0</div>
                    <div class="desc">
                        <span id="procedures20"></span>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    
                </div>
                <div class="details">
				
                    <div class="number" id="reportBlocked20">0</div>
                    
					<div class="desc">
                        <?php if($role >4){ ?>
                        <span id="blocked20"></span>
						<?php }else{ ?>
						<span id="blockedbeds20"></span>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-2 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportEscalation20">0</div>
                    <div class="desc">
                        Escalation
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<div class="row" id="hidden11">
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Variance
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="all_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		<div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Productivity% (est.)
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="postpartum_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		</div>
<div class="row" id="hidden12">
		        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Patients
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="labor_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Open Beds
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="antepartum_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
   

		</div>
    <!--End::Main Portlet-->
	</div>
		<div class="modal fade" id="blockbeds" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title"><span id="deptName"></span></div>
					
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
						<input id="accountId" type="hidden" value="" />
                        <div class="form-body">
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="blockedcount" class="col-7 col-form-label">
                                   Currently Blocked Beds:
                                </label>
                                <div class="col-5">
                                    <select id="blockedcount" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										<option value=6>6</option>
										<option value=7>7</option>
										<option value=8>8</option>
										<option value=9>9</option>
										<option value=10>10</option>
										<option value=11>11</option>
										<option value=12>12</option>
										<option value=13>13</option>
										<option value=14>14</option>
										<option value=15>15</option>
										<option value=16>16</option>
										<option value=17>17</option>
										<option value=18>18</option>
										<option value=19>19</option>
										<option value=20>20</option>
										</select>
                                </div>
                            </div>
												
                            <div class="form-group form-md-line-input">
                                <label for="blockedcomment">Comments:</label>
                                <textarea class="form-control" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
