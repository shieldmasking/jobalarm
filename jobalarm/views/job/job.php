<?php
require_once('views/shared/header.php');
?>
<style type="text/css">
    body {
        background:#ddd;
        padding-bottom:100px;
    }
</style>
<div class="view_job">
    <div class="job_header">
        <?php 
            echo Config::get('postimage'); 
        ?>
    </div>
    <div class="job_title">
        <div class="title_section">
            <h1><?php echo Config::get('posttitle');?></h1>
            <div class="sub_info">
                <?php echo Config::get('postcity').', '.Config::get('poststate').' '.Config::get('postzip');?> &nbsp;&nbsp;&bull;&nbsp;&nbsp; <?php echo Config::get('postdate'); ?>
            </div>
        </div>
        <div class="apply_section">
            <a class="apply_btn" href="<?php echo Config::get('joburl'); ?>">Apply Now</a>
        </div>
        <div class="text_msg_section">
            Text <span style="color:blue"><?php echo Config::get('postkeyword'); ?></span> to <span style="color:blue">313131</span> to apply from your mobile phone.
        </div>
        <div class="text_msg_rates">(Msg & data rates may apply)</div>
    </div>
    <div class="job_body">

        <div class="job_description">
            <h1>JOB DESCRIPTION</h1>
            <div class="desc_body">

                <?php echo Config::get('postdescription');?>
                
            </div>
            <h1>JOB REQUIREMENTS</h1>
            <div class="desc_body">

                <?php echo Config::get('postrequirements');?>

            </div>
            <h1>JOB SNAPSHOT</h1>
            <div class="desc_body">
                <table class="job_desc_table">
                    <tr><td class="leftcolumncell">Company</td><td class="rightcolumncell"><?php echo Config::get('postcompany');?></td></tr>
                    <tr><td class="leftcolumncell">Post Date</td><td class="rightcolumncell"><?php echo Config::get('postdate');?></td></tr>
                    <tr><td class="leftcolumncell">Job Title</td><td class="rightcolumncell"><?php echo Config::get('posttitle');?></td></tr>
                    <tr><td class="leftcolumncell">Compensation</td><td class="rightcolumncell"><?php echo Config::get('postcompensation');?></td></tr>
                    <tr>
                        <td class="leftcolumncell">Location </td>
                        <td class="rightcolumncell"><?php echo Config::get('postcity').', '.Config::get('poststate').' '.Config::get('postzip');?></td>
                    </tr>
                </table>
            </div>
            <center><a class="apply_btn" href="<?php echo Config::get('joburl'); ?>">Apply Now</a></center>
            <div class="text_msg_section">
                Text <span style="color:blue"><?php echo Config::get('postkeyword'); ?></span> to <span style="color:blue">313131</span> to apply from your mobile phone.
            </div>
            <div class="text_msg_rates">(Msg & data rates may apply)</div>
        </div>
        <div class="ad_block">
            
			<img name="TJad" src="../../img/TJad.jpg" width="160" height="300" border="0" id="TJad" usemap="#m_TJad" alt="" />
<map name="m_TJad" id="m_TJad">
  <area shape="rect" coords="0,0,160,300" href="http://www.tweetedjobs.com" target="_blank" alt="" />
</map>

<div>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- jobalarm -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:160px;height:600px"
                 data-ad-client="ca-pub-2545585330917467"
                 data-ad-slot="3767690486"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){

    if ($('.job_description').height() < $('.ad_block').height()) $('.job_description').height($('.ad_block').height()-35);

});
</script>
<?php
require_once('views/shared/footer.php');
?>