
$("select[name='type']").selectize({
  onChange: function(value) {
    if (!value.length) return;
    $.get('i/s/'+value, "", function(data) {
      $("tbody").empty();
      var obj = JSON.parse(data);
      $.each(obj, function(index, val) {
        var tr = '<tr>';
        tr = tr + '<th>' + val.iID + '</th>';
        tr = tr + '<td><a href="i/' + val.iID + '">' + val.name + '</a></td>';
        tr = tr + '<td>' + val.itemtype + '</td>';
        tr = tr + '<td>' + val.brand + '</td>';
        tr = tr + '<td>' + val.type + '</td>';
        tr = tr + '<td>' + val.unit + '</td>';
        tr = tr + '<td>' + val.memo + '</td>';
        tr = tr + '</tr>'
        $("tbody").append(tr);
      });
      // if(data) $("#error").html("<strong>错误！</strong>"+data).show('slow');
      // else location.href="/home";  
    });

  }
});


$("select").selectize();