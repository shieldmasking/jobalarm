tj.alex.archiveTweet = function(jobId) {
     bootbox.confirm("Are you sure?", function(result) {
            if (result) {
	 $.ajax({
         url: 'dataTest.php?archiveTweet=1',
         data: {
             jid: jobId
         },
         method: 'post',
         success: function(response) {
            tj.getTweets(1)
             if (console && console.log) {
                 console.log(response);
             }
         }
     })
	}
  });
};
tj.alex = {};tj.alex.faceBookGrid = {};tj.alex.test = function(){	console.log('test3333');};