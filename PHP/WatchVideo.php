<html>
    <head>
        <title>Watch Video</title>
        <link rel="stylesheet" href="style.php" media="screen">
    </head>

    <body>
    <div class = "bg-image"></div>
        <div class = "bg-text">
        <h1 style="color:black;">Watch Video</h1>
            <div class="topBar">
		        <nav>
                <a href="WatchPage.php">LiveStream</a>
                <a class="active" href="WatchVideo.php">Video</a>
                <a href="WatchForum.php">Forum</a>
		        </nav>
	        </div>
                <h2>Filter Video by Columns (Projection)</h2><br/>
                <form method="GET" action="WatchVideo.php">
            <input type="hidden" id="projectionRequest" name="projectionRequest">
                <input type="checkbox" name="VideoID" id="VideoID">
                        <label for="VideoID">
                        VideoID
                        </label>
                        <input type="checkbox" name="Length" id="Length">
                        <label for="Length">
                        Length
                        </label>
                        <input type="checkbox" name="VideoStatus" id="VideoStatus">
                        <label for="VideoStatus">
                        VideoStatus
                        </label>
                        <input type="checkbox" name="Tag" id="Tag">
                        <label for="Tag">
                        Tag
                        </label><br /><br />
            <input type="submit" class= "button" value="Check all video from desired perspectives" name="doProjection"></p>
            </form>
            <h2>Filter Video by Rows (Selection)</h2><br/>

            <p1>Video Status:</p1>
            <form method="GET" action="WatchVideo.php">
                <input type="hidden" id="selectionRequest" name="selectionRequest">
                <select name="select">
                <option value="on_going" selected="select1"> on going</option>
                <option value="blocked" selected="select2"> blocked </option>
                </select><br /><br />
                <input type="submit" class= "button" value="Find Video" name="doSelection"></p>
            <p1>Sign Out! Back to -><a href="LoginPage.php" class = "link"> Home Page :)</a ></p1>
            </form>
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

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
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

        function printResultSelection($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>ChannelID</th><th>VideoLength</th><th>Tag</th><th>CreateTime</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2]. "</td><td>" . $row[3]. "</td><td>" . $row[4]. "</td><td>" . $row[5]. "</td></tr>";
            }

            echo "</table>";
        }

        function printResultProjectionID($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionLen($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoLength</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionTag($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Tag</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionStatus($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionIDVideoLength($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoLength</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionIDTag($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoTag</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionIDVideoStatus($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionLenTag($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoLength</th><th>VideoTag</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionLenVideoStatus($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoLength</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionTagVideoStatus($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoTag</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjection1($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoLength</th><th>VideoTag</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjection2($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoLength</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjection3($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoTag</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjection4($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoLength</th><th>VideoTag</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
            }
            echo "</table>";
        }
        function printResultProjectionALL($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>VideoID</th><th>VideoLength</th><th>VideoTag</th><th>VideoStatus</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>";
            }
            echo "</table>";
        }

        function handleDisplayRequestSelection() {
            global $db_conn;
            $sig = $_GET['select'];
            if ($sig == 'blocked'){
                $result = executePlainSQL("SELECT * FROM rvContain WHERE VideoStatus = 0");
                printResultSelection($result);
            }
            if ($sig == 'on_going'){
                $result = executePlainSQL("SELECT * FROM rvContain WHERE VideoStatus = 1");
                printResultSelection($result);
            }
        }

        function handleDisplayRequestProjection() {
            global $db_conn;
            $sig_id = $_GET['VideoID'];
            $sig_len = $_GET['Length'];
            $sig_tag = $_GET['Tag'];
            $sig_status = $_GET['VideoStatus'];

            if ($sig_id == "on" && $sig_len == "" && $sig_tag == "" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoID FROM rvContain");
                printResultProjectionID($result);
            }
            if ($sig_id == "" && $sig_len == "on" && $sig_tag == "" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoLength FROM rvContain");
                printResultProjectionLen($result);
            }
            if ($sig_id == "" && $sig_len == "" && $sig_tag == "on" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT Tag FROM rvContain");
                printResultProjectionTag($result);
            }
            if ($sig_id == "" && $sig_len == "" && $sig_tag == "" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoStatus FROM rvContain");
                printResultProjectionStatus($result);
            }
            if ($sig_id == "on" && $sig_len == "on" && $sig_tag == "" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoID, VideoLength FROM rvContain");
                printResultProjectionIDVideoLength($result);
            }
            if ($sig_id == "on" && $sig_len == "" && $sig_tag == "on" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoID, Tag FROM rvContain");
                printResultProjectionIDTag($result);
            }
            if ($sig_id == "on" && $sig_len == "" && $sig_tag == "" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoID, VideoStatus FROM rvContain");
                printResultProjectionIDVideoStatus($result);
            }
            if ($sig_id == "" && $sig_len == "on" && $sig_tag == "on" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoLength, Tag FROM rvContain");
                printResultProjectionLenTag($result);
            }
            if ($sig_id == "" && $sig_len == "on" && $sig_tag == "" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoLength, VideoStatus FROM rvContain");
                printResultProjectionLenVideoStatus($result);
            }
            if ($sig_id == "" && $sig_len == "" && $sig_tag == "on" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT Tag, VideoStatus FROM rvContain");
                printResultProjectionTagVideoStatus($result);
            }
            if ($sig_id == "on" && $sig_len == "on" && $sig_tag == "on" && $sig_status == "")
            {
                $result = executePlainSQL("SELECT VideoID, VideoLength, Tag FROM rvContain");
                printResultProjection1($result);
            }
            if ($sig_id == "on" && $sig_len == "on" && $sig_tag == "" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoID, VideoLength, VideoStatus FROM rvContain");
                printResultProjection2($result);
            }
            if ($sig_id == "on" && $sig_len == "" && $sig_tag == "on" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoID, Tag, VideoStatus FROM rvContain");
                printResultProjection3($result);
            }
            if ($sig_id == "" && $sig_len == "on" && $sig_tag == "on" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoLength, Tag, VideoStatus FROM rvContain");
                printResultProjection4($result);
            }
            if ($sig_id == "on" && $sig_len == "on" && $sig_tag == "on" && $sig_status == "on")
            {
                $result = executePlainSQL("SELECT VideoID, VideoLength, Tag, VideoStatus FROM rvContain");
                printResultProjectionALL($result);
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('doProjection', $_GET)) {
                    handleDisplayRequestProjection();
                } else if (array_key_exists('doSelection', $_GET)) {
                    handleDisplayRequestSelection();
                }
                disconnectFromDB();
            }
        }
        if (isset($_GET['projectionRequest']) || isset($_GET['selectionRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
