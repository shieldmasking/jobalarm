var wus = wus || {};

$(function() {
  wus.on_select_template = function(recid) {
    wus.selected_sms_template = recid;
  };
  
  wus.add_template = function(sid, name, message) {
    w2popup.lock('Adding...', true);
    
    $.ajax({
      url: 'templates/add',
      method: 'POST',
      data: {
        template_survey_id: sid,
        template_name: name,
        template_message: message
      },
      success: function(d) {
        w2popup.unlock();
        w2popup.close();
        w2ui.templates_sms.reload();
      }
    });
  };
  
  wus.templates_sms_add_popup_body = '<div style="padding: 15px;">Survey ID: <input type="text" id="template_survey_id" /><br />Name: <input type="text" id="template_name" /><br />Message: <textarea id="template_message"></textarea></div>';
  wus.templates_sms_add_popup_buttons = '<button onclick="wus.add_template($(\'#template_survey_id\').val(), $(\'#template_name\').val(), $(\'#template_message\').val());">Add</button><button onclick="w2popup.close();">Cancel</button>';
  
  wus.templates_sms_add_popup = function() {
    $().w2popup({
      name: 'add_sms_template_popup',
      title: 'Add SMS Template',
      width: 500,
      height: 400,
      showMax: false,
      body: wus.templates_sms_add_popup_body,
      buttons: wus.templates_sms_add_popup_buttons
    });
  };
  
  wus.templates_sms = {
    name: 'templates_sms',
    url: 'templates/get_sms_list',
    multiSelect: false,
    show: {
      header: true,
      toolbar: true,
      toolbarAdd: true,
      toolbarEdit: true,
      toolbarDelete: true
    },
    header: 'SMS Templates',
    columns: [
      { field: 'template_survey_id', caption: 'Survey ID', size: '80px', sortable: false },
      { field: 'template_name', caption: 'Name', size: '200px', sortable: false },
      { field: 'template_message', caption: 'Message', size: '100%', sortable: false }
    ],
    records: [
      
    ],
    onAdd: function(event) {
      wus.templates_sms_add_popup();
    },
    onClick: function(event) {
      event.onComplete = function(event) {
        var selection = w2ui['templates_sms'].getSelection();
        
        if (selection.length > 0)
          wus.on_select_template(event.recid);
      };
    }
  };
});