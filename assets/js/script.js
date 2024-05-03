$(document).ready(function () {
  var table = $("#myTable").DataTable({
    dom: "Bfrtip",
    buttons: [
      "colvis",
      {
        extend: "excelHtml5",
        title: "SMART COEA LABORATORY EQUIPMENT MONITORING AND INVENTORY",
        exportOptions: {
          columns: ":visible",
        },
        className: "buttons-collection",
      },
      {
        extend: "pdfHtml5",
        title: "SMART COEA LABORATORY EQUIPMENT MONITORING AND INVENTORY",
        exportOptions: {
          columns: [0, ":visible"],
        },
        className: "buttons-collection",
        customize: function (doc) {
          doc.defaultStyle.fontSize = 11;
          doc.page = "a4";
          doc.pageMargins = [20, 20, 20, 20];
          doc.styles.tableHeader.fontSize = 12;
          doc.pageOrientation = "portrait";

          // Center the table
          var table = doc.content[1].table;
          table.widths = Array(table.body[0].length + 1)
            .join("*")
            .split("");
          table.alignment = "center";

          // Center the table data
          for (var i = 0; i < table.body.length; i++) {
            for (var j = 0; j < table.body[i].length; j++) {
              table.body[i][j].alignment = "center";
            }
          }
        },
      },

      {
        extend: "print",
        title: "SMART COEA LABORATORY EQUIPMENT MONITORING AND INVENTORY",
        text: "Print",
        customize: function (win) {
          $(win.document.body).find("h1").css("text-align", "center");
        },
        exportOptions: {
          columns: ":visible",
        },
        className: "buttons-collection",
        autoWidth: true,
        autoPrint: true,
      },
    ],
  });
});
