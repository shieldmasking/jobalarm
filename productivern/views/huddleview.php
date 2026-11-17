<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
?>

<style>
body {
    font-size: 14px;
} 


</style>
<style>
@media print {
  @page {
    size: landscape;
  }
}
</style>


<!-- BEGIN: Subheader -->

<!-- END: Subheader -->
<div class="content">
   
				
		<div class="col-lg-12">
		      
			
						<div class="form-group">
						<button class="btn btn-sm btn-success" onclick="window.print()">Print This Page</button>
						<div class="col-lg" align="center">
							
						
						<div class="title"><h5><span id="huddleTitle"></span></h5></div>
				
				
					</div>
					</div>
					
						<div class="form-group">
						<div class="col-lg" align="center">
							
						
						<div class="title" style="text-align: center"><h5><span id="huddleUnit"></span></h5>							
							</div>						
							
		
					</div>
					</div>
						<div class="form-group">
						<div class="col-lg" align="center">
						
						<div class="title" style="text-align: center"><h5><span id="huddleDate"></span></h5>							
							</div>					
				
					</div>
					</div>
           
		   <div class="form-group" id="sk1">
		   <div class="row">
			<div class="col-2">
		   <label for="huddleroom">
               Room / Location
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddleroom" />
            
			</div>
			
			<div class="col-4">
		   <label for="huddleselect">
               Primary Safety Concern
               </label>                  
            <input type="text" class="form-control border border-dark" id="huddleselect" />
            
			</div>
				<div class="col-2">
		   <label for="huddletask1">
               Debrief Completed
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddletask1" />
           
			</div>
			
			<div class="col-2">
		   <label for="huddletask2">
               ERS Completed
            </label>                     
            <input type="text" class="form-control border border-dark" id="huddletask2" />
        
			</div>
			</div>
			<div class="row">
			<div class="col-10">
		   <label for="huddlenotes">
               Details
              </label>                   
             <textarea rows="3" type="text" class="form-control border border-dark" id="huddlenotes" ></textarea>
           
			</div>
			
		
		   </div>
		   </div>
		   
		   <div class="form-group" id="sk2">
		   <div class="row">
			<div class="col-2">
		   <label for="huddleroom2">
               Room / Location
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddleroom2" />
            
			</div>
			
			<div class="col-4">
		   <label for="huddleselect2">
               Primary Safety Concern
               </label>                  
            <input type="text" class="form-control border border-dark" id="huddleselect2" />
            
			</div>
				<div class="col-2">
		   <label for="huddletask12">
               Debrief Completed
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddletask12" />
           
			</div>
			
			<div class="col-2">
		   <label for="huddletask22">
               ERS Completed
            </label>                     
            <input type="text" class="form-control border border-dark" id="huddletask22" />
        
			</div>
			</div>
			<div class="row">
			<div class="col-10">
		   <label for="huddlenotes2">
               Details
              </label>                   
             <textarea rows="3" type="text" class="form-control border border-dark" id="huddlenotes2" ></textarea>
           
			</div>
			
		
		   </div>
		   </div>
		   
		   <div class="form-group" id="sk3">
		   <div class="row">
			<div class="col-2">
		   <label for="huddleroom3">
               Room / Location
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddleroom3" />
            
			</div>
			
			<div class="col-4">
		   <label for="huddleselect3">
               Primary Safety Concern
               </label>                  
            <input type="text" class="form-control border border-dark" id="huddleselect3" />
            
			</div>
				<div class="col-2">
		   <label for="huddletask13">
               Debrief Completed
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddletask13" />
           
			</div>
			
			<div class="col-2">
		   <label for="huddletask23">
               ERS Completed
            </label>                     
            <input type="text" class="form-control border border-dark" id="huddletask23" />
        
			</div>
			</div>
			<div class="row">
			<div class="col-10">
		   <label for="huddlenotes3">
               Details
              </label>                   
            <textarea rows="3" type="text" class="form-control border border-dark" id="huddlenotes3" ></textarea>
           
			</div>
			
		
		   </div>
		   </div>
		   <!--
		   <div class="form-group" id="sk4">
		   <div class="row">
			<div class="col-2">
		   <label for="huddleroom4">
               Room:
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddleroom4" />
            
			</div>
			
			<div class="col-4">
		   <label for="huddleselect4">
               Primary Concern:
               </label>                  
            <input type="text" class="form-control border border-dark" id="huddleselect4" />
            
			</div>
				<div class="col-2">
		   <label for="huddletask14">
               Debrief:
             </label>                    
            <input type="text" class="form-control border border-dark" id="huddletask14" />
           
			</div>
			
			<div class="col-2">
		   <label for="huddletask24">
               ERS:
            </label>                     
            <input type="text" class="form-control border border-dark" id="huddletask24" />
        
			</div>
			</div>
			<div class="row">
			<div class="col-10">
		   <label for="huddlenotes4">
               Details:
              </label>                   
            <textarea class="form-control border border-dark" rows="3" id="huddlenotes4" ></textarea>
           
			</div>
			
		
		   </div>
		   </div>
		
			-->
				
				<div class="row">
				<div class="col-10">
                                <label for="dhuddle" class="form-label">
                                    <h3>Leadership Comments</h3>
                                 </label>
								 <span id="dhuddle"></span>
                                    <!--<textarea rows="3" class="form-control border border-dark" id="dhuddle"></textarea>-->
                                
							</div>	
							</div>
		
	</div>
</div>	
					
                      
               
