var wus = wus || {};

$(function () {

  wus.livesurveygrid = {
    name: 'livesurveygrid',
    url: 'surveys/getAllLive',
    multiSelect: false,
    show: {
      header: true
    },
    header: 'FluidSurvey Surveys',
    columns: [
      //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
      {field: 'sname', caption: 'Survey Name', size: '100%', sortable: false},
      {field: 'updated', caption: 'Last Update', size: '300px', sortable: false},
      {field: 'responses', caption: 'Responses', size: '200px', sortable: false}
    ],
    records: []
  };

  wus.loadLiveSurveyManager = function (surveyId) {

  };

});