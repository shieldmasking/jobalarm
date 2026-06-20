var wus = wus || {};

$(function () {
  var adminConfig = {
    layout: {
      name: 'adminLayout',
      padding: 0,
      panels: [
        { type: 'top', size: 30, content: '<h1>System Administration</h1>' },
        { type: 'left', size: 200 },
        { type: 'main', minSize: 550, style: 'margin:2px', overflow: 'hidden' }
      ]
    },
    sidebar: {
      name: 'sidebar',
      nodes: [
        { id: 'managesurvey', text: 'Manage Surveys', img: 'fa fa-tasks fa-lg', selected: true },
        { id: 'importsurvey', text: 'Import Surveys', img: 'fa fa-cloud-download fa-lg' },
        { id: 'globalsettings', text: 'Global Settings', img: 'fa fa-globe fa-lg' },
        { id: 'companyadmin', text: 'Company Manager', img: 'fa fa-list fa-lg' },
        { id: 'useradmin', text: 'User Admin', icon: 'fa fa-users fa-lg' },
        { id: 'emailtemplates', text: 'Email Templates', icon: 'fa fa-envelope fa-lg' },
        { id: 'smstemplates', text: 'SMS Templates', icon: 'fa fa-mobile fa-lg' }
      ],
      onClick: function (event) {
        switch (event.target) {
          case 'importsurvey':
            w2ui.adminLayout.content('main', w2ui.livesurveygrid);
            break;
          case 'managesurvey':
            w2ui.adminLayout.content('main', w2ui.systemsurveygrid);
            break;
          case 'globalsettings':
            w2ui.adminLayout.content('main', w2ui.globaladminview);
            w2ui.globaladminview.content('left', w2ui.globalstagesgrid);
            w2ui.globaladminview.content('main', w2ui.globaleventsgrid);
            break;
          case 'companyadmin':
              w2ui.companyeditorlayout.content('main', w2ui.companygrid);
              w2ui.companyeditorsublayout.content('left', w2ui.companykeywordgrid);
              w2ui.companyeditorsublayout.content('main', w2ui.companyaccessgrid);
              w2ui.companyeditorsublayout.content('right', w2ui.companyadmingrid);
              w2ui.companyeditorlayout.content('right', w2ui.companyeditorsublayout);
              w2ui.adminLayout.content('main', w2ui.companyeditorlayout);
            break;
          case 'useradmin':
            w2ui.usereditorlayout.content('main', w2ui.usergrid);
            w2ui.adminLayout.content('main', w2ui.usereditorlayout);
            break;
          case 'smstemplates':
            w2ui.adminLayout.content('main', w2ui.templates_sms);
            break;
          default:
            break;
        }
      }
    }
  };

  $('#adminLayout').w2layout(adminConfig.layout);
  w2ui.adminLayout.content('left', $().w2sidebar(adminConfig.sidebar));
  w2ui.adminLayout.content('main', $().w2grid(wus.systemsurveygrid));
  // in memory initialization
  $().w2grid(wus.livesurveygrid);
  $().w2grid(wus.companygrid);
  $().w2grid(wus.usergrid);
  $().w2grid(wus.companykeywordgrid);
  $().w2grid(wus.companyaccessgrid);
  $().w2grid(wus.companyadmingrid);
  $().w2grid(wus.templates_sms);
  $().w2layout(wus.usereditorlayout);
  $().w2layout(wus.companyeditorlayout);
  $().w2layout(wus.companyeditorsublayout);
  
});