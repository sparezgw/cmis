$("select[name='mID']").selectize({
  create: true,
  sortField: {
    field: 'value',
    direction: 'asc'
  },
  render: {
    option_create: function(data, escape) {
      return '<div class="create">添加 <strong>' + escape(data.input) + '</strong>&hellip;</div>';
    }
  }
});
$("select").selectize();