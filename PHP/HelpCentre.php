<html>
    <head>
        <title>Help Centre</title>
        <link rel="stylesheet" href="style.php" media="screen">
        <script>
        var subjectObject = {
        "Account": {
            "Sign Up": [""],
            "Log In": [""],
            "Others":[""]
        },
        "Channel": {
            "Content": [""],
            "Channel Upload": [""],
            "Channel Download": [""],
            "Others":[""]
        },
        "Forum": {
            "Send Failed": [""],
            "Others":[""]
        },
        "LiveStream": {
            "Create Failed": [""],
            "Others":[""]
        }
        }
        window.onload = function() {
        var subjectSel = document.getElementById("area");
        var topicSel = document.getElementById("issue_type");
        for (var x in subjectObject) {
            subjectSel.options[subjectSel.options.length] = new Option(x, x);
        }
        subjectSel.onchange = function() {
            topicSel.length = 1;
            for (var y in subjectObject[this.value]) {
            topicSel.options[topicSel.options.length] = new Option(y, y);
            }
        }
    }
    </script>
    </head>

    <body>
    <div class="bg-image"></div>
    <div class="bg-text">
     <h1>Welcome to Help Centre!</h1><br /> <br />
        <div class = "box">
            <h2>Help Issue History</h2><br /> <br />
            <form method="GET" action="HelpCentre.php">
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
                <label for="history">Review of All Your Help Request:</label><br />
                <p><input type="submit" value="show" name="show" class = "c_button"></p >
            </form>
            <form method="POST" action="HelpCentre.php">
            <input type="hidden" id="deleteRequest" name="deleteRequest">
                <label>To Delete Your Help Request:</label><br /><br />
                Issue ID: <input type="text" id="id_to_delete" name="id_to_delete"></p ><br />
                <p><input type="submit" value="Delete" name="deleteIssue"></p >
            </form>
        </div>
        <div class = "box">
            <h2>Add Help Request</h2>
            <form method="POST" name="form1" id="form1" action="HelpCentre.php">
            <input type="hidden" id="addRequest" name="addRequest">
                Area: <select name="area" id="area">
                <option value="" selected="selected">Select area</option>
                </select>
                ID: <input type="text" id="id" name="id"></p ><br />
                Type: <select name="issue_type" id="issue_type">
                <option value="" selected="selected">Please select area first</option>
                </select>
                <br><br>
                User ID: <input type="text" id="user_id" name="user_id"></p ><br />
                <label for="issue_content">Please briefly state your problem:</label><br /> <br />
                <textarea id="issue_content" name="issue_content" rows= 9 cols=50%></textarea> <br /> <br />
                <input type="submit" value="Add" name = "addIssue">  
            </form>
        </div>
     </div>
        <?php
  //this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

   return $statement;
  }

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
  In this case you don't need to create the statement several times. Bound variables cause a statement to only be
  parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
  See the sample code below for how this function is used */

   global $db_conn, $success;
   $statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
    }

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
   // ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_ycai12", "a25616533", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function printResultHelpIssue($result) {
            echo "<table>";
            echo "<tr><th>HelpIssueID</th><th>Type</th><th>Content</th><th>Status</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[9] . "</td><td>" . $row[8] . "</td><td>" . $row[7] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function handleDeleteIssueRequest() {
            global $db_conn;
            $id_to_delete = $_POST['id_to_delete'];
            executePlainSQL("DELETE FROM helpcenter WHERE HelpIssueID ='" .$id_to_delete . "'");
            $message = 'Delete Suceesfully!';

            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;
            $help_issue_id = 'H-'.rand(11111111,99999999);
            // echo $_POST['area'];
            $ID = ":".$_POST['area']."ID";
            //Getting the values from user and insert data into the table
            if ($_POST['area'] == "Account"){
                $tuple = array (
                ":HelpIssueID" => $help_issue_id,
                ":ChannelID" => "",
                ":UserID" => $_POST['user_id'],
                ":VideoID" => "",
                ":AccountID" => $_POST['id'],
                ":PostID" => "",
                ":LiveStreamID" => "",
                ":ProcessStatus" => 0,
                ":IssueContent" => $_POST['issue_content'],
                ":IssueType" => $_POST['issue_type']
                );
                $alltuples = array (
                    $tuple
                );
                executeBoundSQL("INSERT INTO helpcenter 
                                 VALUES (:HelpIssueID,:ChannelID,:UserID,:VideoID,:AccountID,:PostID,:LiveStreamID,:ProcessStatus,:IssueContent,:IssueType)", $alltuples);
                
            } else if ($_POST['area'] == "Channel"){
                // $c_id = executePlainSQL("SELECT ChannelID FROM channel
                //                         WHERE ChannelID = '". $_POST['id'] ."'");
                $tuple = array (
                ":HelpIssueID" => $help_issue_id,
                ":ChannelID" => $_POST['id'],
                ":UserID" => $_POST['user_id'],
                ":VideoID" => "",
                ":AccountID" => "",
                ":PostID" => "",
                ":LiveStreamID" => "",
                ":ProcessStatus" => 0,
                ":IssueContent" => $_POST['issue_content'],
                ":IssueType" => $_POST['issue_type']
                );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("INSERT INTO helpcenter 
                             VALUES (:HelpIssueID,:ChannelID,:UserID,:VideoID,:AccountID,:PostID,:LiveStreamID,:ProcessStatus,:IssueContent,:IssueType)", $alltuples);
            } else if ($_POST['area'] == "LiveStream"){
                
                $tuple = array (
                ":HelpIssueID" => $help_issue_id,
                ":ChannelID" => "",
                ":UserID" => $_POST['user_id'],
                ":VideoID" => "",
                ":AccountID" => "",
                ":PostID" => "",
                ":LiveStreamID" => $_POST['id'],
                ":ProcessStatus" => 0,
                ":IssueContent" => $_POST['issue_content'],
                ":IssueType" => $_POST['issue_type']
                );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("INSERT INTO helpcenter 
                             VALUES (:HelpIssueID,:ChannelID,:UserID,:VideoID,:AccountID,:PostID,:LiveStreamID,:ProcessStatus,:IssueContent,:IssueType)", $alltuples);
            } else if ($_POST['area'] == "Forum"){
                
                $tuple = array (
                ":HelpIssueID" => $help_issue_id,
                ":ChannelID" => "",
                ":UserID" => $_POST['user_id'],
                ":VideoID" => "",
                ":AccountID" => "",
                ":PostID" => $_POST['id'],
                ":LiveStreamID" => "",
                ":ProcessStatus" => 0,
                ":IssueContent" => $_POST['issue_content'],
                ":IssueType" => $_POST['issue_type']
                );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("INSERT INTO helpcenter 
                             VALUES (:HelpIssueID,:ChannelID,:UserID,:VideoID,:AccountID,:PostID,:LiveStreamID,:ProcessStatus,:IssueContent,:IssueType)", $alltuples);
            }
            OCICommit($db_conn);
        }

        function handleDisplayRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM helpcenter");
            printResultHelpIssue($result);
        }


        // HANDLE ALL POST ROUTES
 // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('deleteRequest', $_POST)) {
                handleDeleteIssueRequest();
            } else if (array_key_exists('addRequest', $_POST)) {
                handleInsertRequest();
            }
            disconnectFromDB();
        }
    }

    // HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('show', $_GET)) {
                // echo "here";
                handleDisplayRequest();
            }

            disconnectFromDB();
        }
    }

    if (isset($_POST['deleteIssue']) || isset($_POST['addIssue'])) {
        handlePOSTRequest();
    } else if (isset($_GET['displayTupleRequest'])) {
        handleGETRequest();
    }
  ?>
 </body>
</html>