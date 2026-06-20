<?php
require_once('views/shared/header.php');
?>
<div class="careers_main">
    
    <div class="careers_body">
        <div class="careers_filter">
           &nbsp;
        </div>
        <div class="careers_listings">
            <?php
                $jobList = Config::get('joblist');
                foreach($jobList as $job):
                    $jobLink = Config::get('base_url').'job/view/'.$job['id'];
                    if (strlen($job['description']) >  180) {
                        $job['description'] = substr($job['description'], 0,177)."...";
                    }

                    $daysAgo = floor(abs(time()-strtotime($job['postDate']))/86400);
                    if ($daysAgo == 0) $daysAgo = 'Today';
                    if ($daysAgo > 0 && $daysAgo <= 30) $daysAgo = $daysAgo.' days ago';
                    if ($daysAgo > 30) $daysAgo = '30+ days ago';
            ?>
            <div class="career_item">                
                <a class="career_link" href="<?php echo $jobLink; ?>"><?php echo $job['position']; ?></a>
                <span class="career_company"><?php echo $job['company']; ?></span> - 
                <span class="career_location"><?php echo $job['city'].', '.$job['state']; ?></span>
                <?php
                    if (strlen(trim($job['compensation'])) > 0) {
                        echo '<div class="career_compensation">Compensation: '.$job['compensation'].'</div>';
                    }
                ?>
                <p class="career_description"><?php echo $job['description']; ?> </p>
                <div class="career_posttime">Posted <?php echo $daysAgo; ?> </div>
            </div>
            <?php
                endforeach;;
            ?>
        </div>
    </div>
    
</div>
<?php
require_once('views/shared/footer.php');
?>