<html>
    <head>
        <title>Watch LiveStream</title>
        <link rel="stylesheet" href="style.php" media="screen">
    </head>

    <body>
    <div class = "bg-image"></div>
        <div class = "bg-text">
        <h1 style="color:black;">Watch LiveStream</h1>
        <div class="topBar">
		        <nav>
                <a class="active" href="WatchPage.php">LiveStream</a>
                <a href="WatchVideo.php">Video&Channel</a>
                <a href="WatchForum.php">Forum</a>
		        </nav>
	        </div>
                <h2>Display Livestream List with Given Tag</h2>
                <!-- dropdown select template -->
                <form method="GET" action="WatchPage.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
                    <select name="select">
                    <option value="music" selected="music"> Music</option>
                    <option value="talk show" selected="talk show"> Talk Show</option>
                    <option value="shopping" selected="shopping"> Shopping</option>
                    <option value="sports" selected="sports"> Sports</option>
                    <option value="jam" selected="jam"> Jam</option>
                    </select>
                    <br></br>
                    <input type="submit" value="Display" class = "button" name="displayTuples"></p >
                </form>
                <hr />
               
                <h2 style ="&#8226";>Display the Tag and Average Timelong </h2>
                <h2>contains at Least Two Qualified Livestreams</h2>        
                <form method="GET" action="WatchPage.php">
                    <input type="hidden" id="detail_purpose" name="detail_purpose">
                    <input type="checkbox" name="minValue" id="minValue">
                    <label for="minValue">
                    Longer Than: <input type="number" name="min_input"  step="0.01">
                    </label><br /><br />
                    <input type="checkbox" name="maxValue" id="maxValue">
                    <label for="maxValue">
                    Shorter Than: <input type="number" name="max_input"  step="0.01">
                    </label><br /><br />
                    <input type="submit" class= "button" name="submitRequest"></p >
                </form>
                <hr />

                <h2 style ="&#8226";>Display the tag with corresponding timelong of livestream </h2>
                <h2>which is shorter than the average of period length of each kind of tag </h2>
                <h2>(short filter)</h2>        
                <form method="GET" action="WatchPage.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="displayFilterRequest" name="displayFilterRequest">
                    <input type="submit" value="Display" class = "button" name="displayFilter"></p >
                </form>

            <p></p >
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

        function printResultLiveStream($result) { //prints results from a select statement
            //echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            //echo "<tr><th>Tag</th><th>LiveStreamID</th><th>PeriodLength</th><th>BeginTime</th> </tr>";
            echo "<tr><th>Tag</th><th>Average PeriodLength</th></tr>";
           
            // echo "here";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultTag($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Tag</th><th>LiveStreamID</th><th>PeriodLength</th><th>BeginTime</th></tr>";
           
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultNested($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Tag</th><th>PeriodLength</th></tr>";
           
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultMINtimeong($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Tag</th><th>PeriodLength</th></tr></th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
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

        //Find the list of all qualified videos in the LiveStream Filter, for each Tag with at least 2 such videos. (Method use: Aggregation with Having)
        function handleMaxRequest() {
            global $db_conn;
            $maxsig = $_GET['maxValue'];
            $minsig = $_GET['minValue'];

           
            //echo ($_GET['maxValue']);
            if ($maxsig == "on" && $minsig == ""){
                $maxinput = $_GET['max_input'];
                $result = executePlainSQL("SELECT Tag, AVG(PeriodLength)
                                           FROM LivestreamMain
                                           WHERE PeriodLength < $maxinput
                                           GROUP BY Tag
                                           HAVING COUNT(*) > 1
                                           ");
                //echo "no max";
                printResultLiveStream($result);
            } else if ($maxsig == "" && $minsig == "on"){
                $mininput = $_GET['min_input'];
                $result = executePlainSQL("SELECT Tag, AVG(PeriodLength)
                                           FROM LivestreamMain
                                           WHERE PeriodLength > $mininput
                                           GROUP BY Tag
                                           HAVING COUNT(*) > 1");
                //echo "no min";
                printResultLiveStream($result);
            } else if ($maxsig == "on" && $minsig == "on"){
                $maxinput = $_GET['max_input'];
                $mininput = $_GET['min_input'];
                //echo "yes";
                $result = executePlainSQL("SELECT Tag, AVG(PeriodLength)
                                           FROM LivestreamMain
                                           WHERE PeriodLength > $mininput AND PeriodLength < $maxinput
                                           GROUP BY Tag
                                           HAVING COUNT(*) > 1");
                printResultLiveStream($result);
            }
        }

        function handleDisplayRequest() {
            global $db_conn;
            $sig = $_GET['select'];
            if ($sig == "music"){
                $result = executePlainSQL("SELECT Tag, PeriodLength
                                       FROM LivestreamMain
                                       WHERE Tag = 'music'");
                printResultTag($result);
                }
            if ($sig == "talk show"){
                $result = executePlainSQL("SELECT Tag, LiveStreamID, PeriodLength, BeginTime
                                           FROM LivestreamMain
                                           WHERE Tag = 'talk show'");
                printResultTag($result);
                }
            if ($sig == "shopping"){
                $result = executePlainSQL("SELECT Tag, LiveStreamID, PeriodLength, BeginTime
                                            FROM LivestreamMain
                                            WHERE Tag = 'shopping'");
                printResultTag($result);
                }
            if ($sig == "sports"){
                $result = executePlainSQL("SELECT Tag, LiveStreamID, PeriodLength, BeginTime
                                           FROM LivestreamMain
                                           WHERE Tag = 'sports'");
                printResultTag($result);
                }
            if ($sig == "jam"){
                $result = executePlainSQL("SELECT Tag, LiveStreamID, PeriodLength, BeginTime
                                            FROM LivestreamMain
                                            WHERE Tag = 'jam'");
                printResultTag($result);
                }    
        }
        function handleMINtimelongRequest() {
            global $db_conn;
                $result = executePlainSQL("SELECT Tag, PeriodLength
                                       FROM LivestreamMain lsm
                                       WHERE lsm.PeriodLength <= ALL(
                                                                    SELECT AVG(lsm2.PeriodLength)
                                                                    FROM LivestreamMain lsm2
                                                                    GROUP BY Tag)");
                printResultNested($result); 
        }
        // HANDLE ALL GET ROUTES
 // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('max_input', $_GET)) {
                    handleMaxRequest();
                }
                if (array_key_exists('displayTuples', $_GET)) {
                    handledisplayRequest();
                }
                if (array_key_exists('displayFilter', $_GET)) {
                    handleMINtimelongRequest();
                }
                disconnectFromDB();
            }
        }

        if (isset($_GET['submitRequest']) || isset($_GET['displayTupleRequest']) || isset($_GET['displayFilterRequest'])) {
            handleGETRequest();
        }
  ?>
 </body>
</html>