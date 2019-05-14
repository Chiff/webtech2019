<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="MSF8nltigNlCWnsp5OzxANLiQrnyKkkAKl-DhoW6GuU"/>
    <title>Zaver</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/css/text_editor/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/css/text_editor/info.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/css/text_editor/widgContent.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/css/text_editor/widgEditor.css">
    <script src="../../assets/js/text_editor/widgEditor.js"></script>
</head>
<body>
<div class="mainContainer">

    <h1>Import CSV file</h1>
    <form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='post' enctype="multipart/form-data">
        Import File : <input type='file' name='sel_file' size='20'>
        <br>
        Delimiter
        <input type="text" name="delimiter" value=";" required>
        <input type='submit' name='submit' value='submit'>
    </form>

    <?php

    function generatePassword($length = 15)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    $server_csv = "server_info.csv";

    $verejnaIP = "147.175.121.210";
    $privatnaIP = "192.168.0.";
    $ssh_start = 2200;
    $http_start = 8000;
    $https_start = 4000;
    $misc1_start = 9000;
    $misc2_start = 1900;

    $csv_array = array();

    $message = "";
    $number_of_people = 0;

    // MALY CSV

    if (isset($_POST['submit'])) {
        $fname = $_FILES['sel_file']['name'];
        echo 'upload file name: ' . $fname . ' ';
        $chk_ext = explode(".", $fname);

        if (strtolower(end($chk_ext)) == "csv") {
            $filename = $_FILES['sel_file']['tmp_name'];
            $handle = fopen($filename, "r");

            // DOWNLOAD CSV
            $download_file = "download.csv";
            $fp = fopen($download_file, 'w');
            $header = array("ID", "meno", "email", "login", "heslo");
            fputcsv($fp, $header, $_POST['delimiter']);

            echo "<table id='my_table'>";
            echo "<tr><th>ID</th><th>Meno a priezvisko</th><th>email</th><th>login</th></tr>";

            $row = 1;
            if (($handle = fopen($filename, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, $_POST['delimiter'])) !== FALSE) {
                    $index = count($data);
                    if ($row == 1) {
                        $row++;
                        continue;
                    }
                    $num = count($data);
                    $number_of_people = $num;
                    $row++;
                    echo "<tr>";
                    for ($c = 0; $c < $num; $c++) {
                        $csv_array[$row - 3][$c] = $data[$c];
                        echo "<td>" . $data[$c] . "</td>";
                    }

                    $csv_array[$row - 3][$index] = generatePassword(15);
                    fputcsv($fp, $csv_array[$row - 3], $_POST['delimiter']);
                    $csv_array[$row - 3][$index + 1] = $verejnaIP;
                    $csv_array[$row - 3][$index + 2] = $privatnaIP . (string)($row - 2);
                    $csv_array[$row - 3][$index + 3] = (string)($ssh_start + ($row - 2));
                    $csv_array[$row - 3][$index + 4] = (string)($http_start + ($row - 2));
                    $csv_array[$row - 3][$index + 5] = (string)($https_start + ($row - 2));
                    $csv_array[$row - 3][$index + 6] = (string)($misc1_start + ($row - 2));
                    $csv_array[$row - 3][$index + 7] = (string)($misc2_start + ($row - 2));

                    echo "</tr>";
                }
                fclose($handle);
            }
            echo "</table>";
            echo "Successfully Imported<br>";
            fclose($handle);

            fclose($fp);

            echo "<a style='color: #ff0000;' href=\"download.csv\">Download CSV file</a>";

            //downloadCsv($csv_array);

            // VELKY CSV

            $sender = "Janko Mrkvicka";

            $fp = fopen($server_csv, 'w');

            $header = array("ID", "meno", "email", "login", "heslo", "verejnaIP", "privatnaIP", "ssh", "http", "https", "misc1", "misc2");

            fputcsv($fp, $header, $_POST['delimiter']);

            foreach ($csv_array as $fields) {
                fputcsv($fp, $fields, $_POST['delimiter']);
            }

            echo "<table id='my_table'>";
            echo "<tr><th>ID</th><th>Meno a priezvisko</th><th>email</th><th>login</th><th>heslo</th><th>verejnaIP</th><th>privatnaIP</th><th>ssh</th><th>http</th><th>https</th><th>misc1</th><th>misc2</th></tr>";

            $row = 1;
            if (($fp = fopen($server_csv, "r")) !== FALSE) {
                while (($data = fgetcsv($fp, 1000, $_POST['delimiter'])) !== FALSE) {
                    if ($row == 1) {
                        $row++;
                        continue;
                    }
                    $num = count($data);
                    $row++;
                    echo "<tr>";
                    for ($c = 0; $c < $num; $c++) {
                        echo "<td>" . $data[$c] . "</td>";
                    }
                    echo "</tr>";
                }
            }

            echo "</table>";


            ?>
            <div>
                <form method="post" action="send-mail.php">
                    <label>
                        Subject:
                        <input type="text" name="subject">
                    </label>
                    <br>
                    <label>
                        mail:
                        <input type="email" name="sender_email">
                    </label>
                    <br>
                    <label>
                        pass:
                        <input type="password" name="sender_pass">
                    </label>
                    <br>
                    <label>
                        file:
                        <input type="file" name="attachment">
                    </label>
                    <br>
                    <input type="hidden" name="rows" value="<?php echo $number_of_people ?>">
                    <input type="hidden" name="delimiter" value="<?php echo $_POST['delimiter']; ?>">
                    <input type="submit" value="submit">
                </form>
            </div>

            <div>
                <form action="submit.php"
                      onsubmit="alert('Your submitted HTML was:\n\n' + document.getElementById('noise').value); return false;">
                    <fieldset>
                        <label for="noise">
                            Make some noise:
                        </label>
                        <textarea id="noise" name="noise" class="widgEditor nothing">&lt;p&gt;widgEditor &lt;strong&gt;automatically&lt;/strong&gt; integrates the content that was in the textarea!&lt;/p&gt;</textarea>
                    </fieldset>
                    <fieldset class="submit">
                        <input type="submit" value="Check the submitted code"/>
                    </fieldset>
                </form>
            </div>
            <?php

            $conn->close();

            fclose($fp);

        } else {
            echo "Invalid File";
        }
    }


    ?>


</div>
</body>

</html>