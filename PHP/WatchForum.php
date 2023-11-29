<html>
    <head>
        <title>Watch Forum</title>
        <link rel="stylesheet" href="style.php" media="screen">
    </head>

    <body>
    <div class = "bg-image"></div>
        <div class = "bg-text">
        <h1 style="color:black;">Watch Forum</h1>
        <div class="topBar">
		        <nav>
                <a href="WatchPage.php">LiveStream</a>
                <a href="WatchVideo.php">Video&Channel</a>
                <a class="active" href="WatchForum.php">Forum</a>
		        </nav>
	        </div>
                <h2>Forum filter</h2>
                <!-- dropdown select template -->
            <form method="POST" action="WatchForum.php">
            <p1 style="color:black;">Delete Forum</p1>
            <input type="hidden" id="displayRequest" name="deleteRequest">
            <label>Forum ID: </label><input type="text" name="id_to_delete"><br /><br />
            </label><br /><br />
            <input type="submit" class= "button" name="deleteForum"></p>
            </form>
            <form method="GET" action="WatchForum.php">
            <p1 style="color:black;">Display Forum</p1>
            <input type="hidden" id="displayRequest" name="displayForumRequest">
            </label><br /><br />
            <input type="submit" class= "button" name="displayForum"></p>
            </form>
            <form method="GET" action="WatchForum.php">
            <p1 style="color:black;">Display HelpCentre</p1>
            <input type="hidden" id="displayRequest" name="displayHelpcentreRequeset">
            </label><br /><br />
            <input type="submit" class= "button" name="displayHelpcentre"></p>
            </form>
            <p></p>
            <p1>Sign Out! Back to -><a href="LoginPage.php" class = "link"> Home Page:)</a ></p1>
        </style>
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
            // $db_conn = OCILogon("ora_qliu20", "a89451330", "dbhost.students.cs.ubc.ca:1522/stu");

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

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";

            }
        }

        function handleDeleteIssueRequest() {
            global $db_conn;
            $id_to_delete = $_POST['id_to_delete'];
            executePlainSQL("DELETE FROM forum WHERE PostID ='" .$id_to_delete . "'");
            $message = 'Delete Suceesfully!';

            OCICommit($db_conn);
        }
        
        function printResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>ForumID</th><th>ForumTag</th><th>Author</th><th>Title</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }
        function printResultHC($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>HelpIssueID</th><th>ForumID</th><th>Type</th><th>Content</th><th>Status</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[5] . "</td><td>" . $row[9] . "</td><td>" . $row[8] . "</td><td>" . $row[7] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('deleteForum', $_POST)) {
                handleDeleteIssueRequest();
            } 
            disconnectFromDB();
        }
    }
    
    function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('displayForum', $_GET)) {
                    $res = executePlainSQL("SELECT * FROM forum");
                    printResult($res);
                } else if (array_key_exists('displayHelpcentre', $_GET)){
                    $res = executePlainSQL("SELECT * FROM helpcenter");
                    printResultHC($res);
                }
                disconnectFromDB();
            }
        }

        if (isset($_POST['deleteRequest'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayForumRequest']) || isset($_GET['displayHelpcentreRequeset'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
