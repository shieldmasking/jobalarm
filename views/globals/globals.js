var wus = wus || {};
var link;
var extraParam;
wus.onSelectGlobalResponse = function (responseId) {
  wus.SelectedResponse = responseId;
  w2ui.grid.getSelection();

  if (w2ui.grid.getSelection().length > 0) {
    //w2ui.layout.lock('right', 'Loading...', true);
    //w2ui.layout.load('right', 'responses/getForm/' + responseId, '', function () {
    //  w2ui.layout.unlock('right');
    //  var params = {
    //    'responseId': wus.SelectedResponse
    //  };
    //  wus.updateUploadObject(params);
	
    //};


    //@TODO: FIX THIS

    wus.SelectedPersonSMSURL = 'sms/getAllCandidate/' + wus.SelectedResponse;
    wus.SelectedPersonNotesURL = 'notes/getCandidateNotes/' + wus.SelectedResponse;
		
    if (w2ui.detailslayout) {
      w2ui.detailssmsgrid.load(wus.SelectedPersonSMSURL);
      w2ui.detailsnotesgrid.load(wus.SelectedPersonNotesURL);

      var gridData = w2ui.grid.get(w2ui.grid.getSelection());
      var fullname = gridData['firstname'] + ' ' + gridData['lastname'];

      if (fullname.length > 1)
        $('#candidate_fullname').html(toTitleCase(fullname));
      else
        $('#candidate_fullname').html("Name Not Provided");

      if (gridData['referral']) {
        $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
      } else {
        $('#candidate_referredby').html('Referred By: ');
      }

      if (gridData['group'])
        $('#candidate_current_event').html('Group/Stage: ' + toTitleCase(gridData['group']));
      else
        $('#candidate_current_event').html('Group/Stage: ');

      if (gridData['stage'])
        $('#candidate_current_stage').html('Stage: ' + toTitleCase(gridData['stage']));
      else
        $('#candidate_current_stage').html('Stage: ');

    }

    //w2ui.layout.panels[2].toolbar.enable('sendmail_button');
    //w2ui.layout.panels[2].toolbar.enable('saveresponsebtn');
  }
};



$(function () {
  var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
  var filtering = '<div id="filterBox" class="filterBox"><form id="filterForm" name="filterForm" action="" method="GET">' +
          '<label>Brand</label><select style="width:150px;height:23px;" id="filter_brand" name="filter_brand"></select><br />' +
		  //'<label>Messaging</label><select style="width:150px;height:23px;" id="filter_opt" name="filter_opt"></select><br />' +
          '<label>Keyword Search</label><input type="text" id="filter_keyword" name="filter_keyword" value="" /><br />' +
          //'<label>Stage</label><select multiple id="filter_stageid" name="filter_stageid"><option value="-1"></option></select><br />' +*/
          '<label>Group/Stage</label><select multiple id="filter_groupid" name="filter_groupid"><option value="-1"></option></select><br />' +
          '<label>Zip Code</label><input style="width:45px" type="text" id="filter_zipCode" name="filter_zipCode" value="" /> - <select style="width:85px;height:23px;" id="filter_zipdist" name="filter_zipdist"><option value="0">Distance</option><option value="2">2 Miles</option><option value="5">5 Miles</option><option value="10">10 Miles</option><option value="15">15 Miles</option><option value="30">30 Miles</option><option value="50">50 Miles</option><option value="100">100 Miles</option></select><br />' +
          '<label>Zip Code Lookup: <a target="_blank" href="http://www.geonames.org/postalcode-search.html?q=&country=us">Click Here</a></label>' +
          '</form><br /><br /><hr /><center><button onclick="wus.doSearch();">Search</button> <button onclick="$(\'#filterForm\')[0].reset();window.location=\'http://admin.jobalarm.com/globals\';">Reset</button></center></div>';
  $().w2layout({
    name: 'searchlayout',
    panels: [
      {type: 'main', content: filtering}
    ]
  });


  $('#layout').w2layout({
    name: 'layout',
    panels: [
	{type: 'top', size: 50, style: pstyle, content: '<h1 style="float:left">Candidate Manager</h1><div class="pull-right"><button onclick="window.location=\'http://www.jobalarm.com/dashboard/\';">DASHBOARD <span class="fa fa-back"></span>  </button>&nbsp;&nbsp;&nbsp;<button onclick="window.location=\'http://admin.jobalarm.com/smsview\';">SMS INBOX <span class="fa fa-people"></span></button></div>'},
      {type: 'left', size: 200, style: pstyle, resizable: false, content: w2ui.searchlayout},
      {type: 'main', style: pstyle, content: 'main'},
      {
        type: 'preview', size: 250, hidden: true, resizable: true,
        toolbar: {
          items: [
            //{ type: 'button', id: 'detailsopenmsgr', caption: 'Messenger', icon: 'fa fa-comment-o' },
            {type: 'html', html: '<div id="candidate_container"><div id="fullname_container"><span id="candidate_fullname"></span></div><div id="event_container"><span id="candidate_current_event">Group/Stage: </span></div></div>'},
            {type: 'spacer'},
            {type: 'button', id: 'detailsaddnote', caption: 'Add Note', icon: 'fa fa-pencil-square-o'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'detailsopenmsgr':
                var selectedItems = w2ui['grid'].getSelection();
                if (selectedItems.length > 0)
                  wus.createSMSChatWindow(wus.SelectedResponse, wus.SelectedResponse);
                break;
              case 'detailsaddnote':
                var selectedItems = w2ui['grid'].getSelection();
                if (selectedItems.length > 0)
                  wus.openNotesDialog(wus.SelectedResponse);
                break;
            }
          }
        }
      }
    ]
  });

  $().w2layout({
    name: 'sublayout',
    panels: [
      {type: 'top', size: 28},
      {type: 'main'}
    ]
  });


  $().w2toolbar({
    name: 'toolbar',
    items: [
      {type: 'check', id: 'hideleft', caption: '', icon: 'fa fa-long-arrow-left', hint: 'Filter Toggle', checked: true},
      {type: 'break', id: 'break0'},
      {type: 'button', id: 'sendMessage', caption: 'Send Message', icon: 'fa fa-mobile', hint: 'Send Message'},
      {type: 'break', id: 'break1'},
      {type: 'button', id: 'actions', caption: 'Action', icon: 'fa fa-gear', hint: 'Change Group/Stage'},
      {type: 'break', id: 'break2'},
      {type: 'check', id: 'details', caption: 'Details', icon: 'fa fa-file-text', hint: 'Detailed View of Candidate'},
      {type: 'break', id: 'break3'},
      {type: 'button', id: 'refresh', caption: 'Show All', icon: 'fa fa-refresh', hint: 'Show All Candidates'}
      //{ type: 'break', id: 'break4' }
    ],
    onClick: function (event) {
      switch (event.target) {
        case 'hideleft':
          w2ui['layout'].toggle('left', window.instant);
          break;
        case 'sendMessage':
          wus.openSMSDialog(null, addLinkSms);
          break;
        case 'actions':
          wus.openActionsDialog(w2ui.grid.getSelection()); //in views/shared/sharedfunctions.js
          break;
        case 'hideright':
          w2ui['layout'].toggle('right', window.instant);
          break;
        case 'refresh':
		$('#filterForm')[0].reset();
		window.location='http://admin.jobalarm.com/globals';
          //wus.doSearch();
          //w2ui['grid'].reset();
          //w2ui['grid'].reload();
          break;
        case 'details':
          w2ui['layout'].toggle('preview', true);
          if (!event.item.checked) {
            $().w2grid({
              name: 'detailsnotesgrid',
              header: 'Note History',
              show: {header: true},
              multiSelect: false,
              fixedBody: true,
              columns: [
                {field: 'noteDate', caption: 'Date', size: '100px'},
                {field: 'noteBody', caption: 'Note', size: '250px'},
				{field: 'recruiter', caption: 'Created By', size: '100%'}
              ]
            });
            $().w2grid({
              name: 'detailssmsgrid',
              //        url: 'sms/getAllResponse/',
              header: 'SMS History',
              show: {header: true},
              multiSelect: false,
              fixedBody: true,
              columns: [
                {field: 'smsDate', caption: 'Date', size: '100px'},
                {field: 'smsMsg', caption: 'Message', size: '100%'}
              ]
            });

            $().w2layout({
              name: 'detailslayout',
              panels: [
                {type: 'left', size: '50%', resizable: true},
                {type: 'main'}
              ]
            });

            w2ui.layout.content('preview', w2ui.detailslayout);
            w2ui.detailslayout.content('left', w2ui.detailssmsgrid);
            w2ui.detailslayout.content('main', w2ui.detailsnotesgrid);

            if (wus.SelectedResponse > 0) {
              w2ui.detailssmsgrid.load(wus.SelectedPersonSMSURL);
              w2ui.detailsnotesgrid.load(wus.SelectedPersonNotesURL);

              var gridData = w2ui.grid.get(w2ui.grid.getSelection());

              var fullname = gridData['firstname'] + ' ' + gridData['lastname'];

              if (fullname.length > 1)
                $('#candidate_fullname').html(toTitleCase(fullname));
              else
                $('#candidate_fullname').html("Name Not Provided");

              if (gridData['referral']) {
                $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
              } else {
                $('#candidate_referredby').html('Referred By: ');
              }

              if (gridData['group'])
                $('#candidate_current_event').html('Group/Stage: ' + toTitleCase(gridData['group']));
              else
                $('#candidate_current_event').html('Group/Stage: ');

              if (gridData['stage'])
                $('#candidate_current_stage').html('Stage: ' + toTitleCase(gridData['stage']));
              else
                $('#candidate_current_stage').html('Stage: ');

            }
          } else {
            if (w2ui.detailssmsgrid)
              w2ui['detailssmsgrid'].destroy();
            if (w2ui.detailsnotesgrid)
              w2ui['detailsnotesgrid'].destroy();
            if (w2ui.detailslayout)
              w2ui['detailslayout'].destroy();
          }
          break;
      }
    }
  });
  wus.addURL = '';
  if (wus.refZip) wus.addURL = wus.addURL + '&z='+wus.refZip;
  if (wus.refBrand) wus.addURL = wus.addURL + '&b='+wus.refBrand;

  var index1 = window.location.href.indexOf('&m');
  if(index1 != -1){
    extraParam = window.location.href.substring(window.location.href.indexOf('&m'));
  }else{
    extraParam = '';
  }

  $().w2grid({
    name: 'grid',
    url: 'responses/getCandidates?a='+wus.accountId+wus.addURL + extraParam,
    multiSelect: true,
	sortable: true,
    limit: 100,
    markSearchResults: false,
    show: {
      toolbar: false,
	  selectColumn: true,
      footer: true
    },
    autoLoad:false,
    multiSearch: false,
	searches: [
            { field: 'lastname', caption: 'Last Name', type: 'text', operator: 'begins with' },
            { field: 'firstname', caption: 'First Name', type: 'text', operator: 'begins with' },
			{ field: 'mobilenum', caption: 'Mobile', type: 'text', operator: 'begins with'},
            { field: 'email', caption: 'Email', type: 'text', operator: 'begins with'},
            { field: 'resume', caption: 'Skills', type: 'text', operator: 'contains' }
        ],
    columns: [
      {field: 'surveyname', caption: 'Survey', size: '120px', hidden: true, resizable: true, sortable: false},
      {field: 'status', caption: '', size: '23px', hidden: true, resizable: true, sortable: false},
      {field: 'msgcount', caption: '#', size: '12px', hidden: true, resizable: true, sortable: true},
	  {field: 'brand', caption: 'Brand', size: '70px', resizable: true, sortable: true},
	  //{field: 'stage', caption: 'Stage', size: '80px', resizable: true, sortable: true},
      {field: 'group', caption: 'Group/Stage', size: '75px', resizable: true, sortable: true},
	  {field: 'firstname', caption: 'First', size: '65px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'lastname', caption: 'Last Name', size: '90px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'position', caption: 'Position', size: '75px', resizable: true, sortable: true },
      {field: 'mobilenum', caption: 'Mobile', size: '80px', resizable: true, sortable: true },
      {field: 'resume', caption: 'Skills / Resume', size: '120px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'zipcode', caption: 'Zip', size: '45px', resizable: true, sortable: true},
	  {field: 'recruiter', caption: 'Recruiter', size: '75px', resizable: true, sortable: true},
	  {field: 'email', caption: 'Email', size: '150px', resizable: true, sortable: true, editable: {type: 'text'}},
	  {field: 'updated', caption: 'Received On', size: '140px', resizable: true, sortable: true},
	  {field: 'account1', caption: 'acct', size: '15px', hidden: true, resizable: true},
	  {field: 'recId', caption: 'recId', size: '15px', hidden: true, resizable: true},
	  {field: 'brand1', caption: 'brandId', size: '15px', hidden: true, resizable: true},
	  {field: 'opt', caption: 'opt', size: '15px', hidden: true, resizable: true}

      /*{ field: 'resume', caption: 'Resume', size: '150px', hidden: true, resizable: true, sortable: true }*/
    ],
	onClick: function (event) {
      event.onComplete = function (event) {
        w2ui['grid'].set(event.recid, {style: 'font-weight:normal'});
        //w2ui['grid'].buffered = 50;
		var selectedItems = w2ui['grid'].getSelection();
        if (selectedItems.length > 0)
          wus.onSelectGlobalResponse(event.recid);
      };
    }
  });

  w2ui.sublayout.content('top', w2ui.toolbar);
  w2ui.sublayout.content('main', w2ui['grid']);
  w2ui.layout.content('main', w2ui.sublayout);

  wus.updateGroupList(wus.accountId);
  setTimeout(function(){
    $.ajax({
      url:'/brand/getBrands/'+wus.accountId,
      method:'get',
      dataType:'json',
      success:function(data) {
        $('#filter_brand').append('<option value="0">All</option>');
        $.each(data.records,function(k,v) {
          $('#filter_brand').append('<option value="'+v.brand+'">'+v.brand+'</option>');
        });
        $("#filter_brand").val($("#filter_brand option:first").val());
//        wus.doSearch();
        $('#filter_brand').on('change',function(){
          wus.doSearch();
        });
        addLink();
      }
    });

   }, 1000);
//alex modding THIS
var index = window.location.href.indexOf('www.jobalarm');
if(index != -1){
  link = window.location.href.substring(window.location.href.indexOf('www.jobalarm'));
}else{
  link = '';
}




});


function addLink(){
  try{
    $('#linkWrapper').remove();
  }catch(e){}
  var wrapper = $('#filterBox');
  var html = '';
  html += '<div id="linkWrapper" style="width: 95%;margin-top: 10px; border-top: 1px solid black;">';
    html += '<div style="width:100%; font-weight: 700;text-align: center; margin-bottom: 5px;">';
      //html += 'Job URL';
    html += '</div>';
    html += '<div style="width:100%;word-wrap: break-word;">';
      html += link;
    html += '</div>';
  html += '</div>';

  wrapper.append(html);
}

var addLinkSms = function(){
  console.log('test');
  $('#smsmessage').val(link);
};
