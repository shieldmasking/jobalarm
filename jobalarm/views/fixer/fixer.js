$(function(){
    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
    $('#layout').w2layout({
        name: 'layout',
        panels: [
          { type: 'top', style: pstyle, size: 50, content: '<h1>Fix Utilities</h1>' },
          { type: 'main', style: pstyle }
        ]
    })

});