<?php

require "../../config/config.php";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
$sql = "SELECT * FROM `mail_statistics`";

$result = $conn->query($sql);

$data = [];
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

?>
    <!DOCTYPE html>
    <html lang="sk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="MSF8nltigNlCWnsp5OzxANLiQrnyKkkAKl-DhoW6GuU"/>
        <title>Štatistiky odoslaných mailov</title>
        <?php include('../head.php'); ?>
        <!-- data tables-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
        <!-- jQuery UI -->
        <link rel="stylesheet"
              href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <!-- jQuery UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <!-- data tables-->
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

    </head>
    <body>
    <?php include('../nav.php'); ?>
    <div class="mainContainer">
        <table id="MailTable">
            <caption><h3>Odoslané emaily</h3></caption>
            <thead>
            <tr>
                <th>Študent</th>
                <th>Dátum</th>
                <th>Predmet</th>
                <th>Šablóna</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($data as $row) {
                echo "<tr>",
                "<td>", $row["student_name"], "</td>",
                "<td>", $row["date"], "</td>",
                "<td>", $row["subject"], "</td>",
                "<td>", $row["template_id"], "</td>",
                "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        const table = $('#MailTable').DataTable({
            autoWidth: true,
            ordering: true,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            orderCellsTop: true,
            paging: true,
            processing: true,
            searching: false,
            stateSave: true,
            language:
                {
                    "sEmptyTable": "Nie sú k dispozícii žiadne dáta",
                    "sInfo": "Záznamy _START_ až _END_ z celkom _TOTAL_",
                    "sInfoEmpty": "Záznamy 0 až 0 z celkom 0 ",
                    "sInfoFiltered": "(vyfiltrované spomedzi _MAX_ záznamov)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Zobraz _MENU_ záznamov",
                    "sLoadingRecords": "Načítavam...",
                    "sProcessing": "Spracúvam...",
                    "sSearch": "Hľadať:",
                    "sZeroRecords": "Nenašli sa žiadne vyhovujúce záznamy",
                    "oPaginate": {
                        "sFirst": "Prvá",
                        "sLast": "Posledná",
                        "sNext": "Nasledujúca",
                        "sPrevious": "Predchádzajúca"
                    },
                    "oAria": {
                        "sSortAscending": ": aktivujte na zoradenie stĺpca vzostupne",
                        "sSortDescending": ": aktivujte na zoradenie stĺpca zostupne"
                    }
                },
            select: true
        });
    </script>
    </body>
    </html>
<?php