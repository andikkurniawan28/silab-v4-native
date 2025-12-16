// Call the dataTables jQuery plugin
// $(document).ready(function() {
//   $('#dataTable').DataTable( {
//       "order": [[ 0, "desc" ]],
//       dom: 'Bfrtip',
//       buttons: [
//           'copy', 'csv', 'excel', 'pdf', 'print'
//       ]

//     } );
// });

$(document).ready(function() {
  $('#dataTable').DataTable( {
      "paging": true,
      "displayLength":100,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      "order": [[ 0, "desc" ]],
  } );
} );
