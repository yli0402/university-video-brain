<html>
    <head>
        <title>Administrator Opertation System</title>
        <link rel="stylesheet" href="style.php" media="screen">
    </head>

    <body>
    <div class = "bg-image"></div>
    <div class = "bg-text">
     <h1>Administrator Opertation System</h1>
        <div>
                <h2>Block & Manage</h2>
                        <form method="POST" action="AdminContent.php">
                        <input type="hidden" id="blockInformation" name="blockInformation">
                        <p>Select the Attributes to Modify</p>
                        <input type="checkbox" name="email" id="email">
                        <label for="email">
                        New Email: <input name="email_input">
                        </label><br /><br />
                        <input type="checkbox" name="password" id="password">
                        <label for="password">
                        New Password: <input name="password_input">
                        </label><br /><br />
                        <input type="checkbox" name="bd" id="bd">
                        <label for="bd">
                        New Birthdate: <input name="bd_input">
                        </label><br /><br />
                        <input type="checkbox" name="block" id="block">
                        <label for="block">
                        Block & Manage
                        </label><br /><br />
                        <p>Input UserID & AccountID or ChannelID & VideoID</p>
                            <label>AccountID: </label><input type="text" name="account_id"><br /><br />
                            <label>UserID: </label><input type="text" name="user_id"><br /><br />
                            <label>VideoID: </label><input type="text" name="video_id"><br /><br />
                            <label>ChannelID: </label><input type="text" name="channel_id"><br /><br />

                        <input type="submit" value="Block & Manage" class = "button" name="blockSubmit">
                        </form>
        </div>
        <hr />

        <h2>Display the Sponosor Info(input both)</h2>
        <form method="GET" action="AdminContent.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <select name="select">
                <option value="s_video" selected="s_video"> Video Sponosor</option>
                <option value="s_channel" selected="s_channel"> Channel Sponosor</option>
            </select>
            MaxBudget: <input type="number" name="max_input">
            MinBudget: <input type="number" name="min_input">
            <input type="submit" value="Display" class = "button" name="displayTuples"></p>
        </form>
        <hr />
        <h2>Display the Account Info</h2>
        <form method="GET" action="AdminContent.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayAccountRequest" name="displayAccountRequest">
            <input type="submit" value="Display" class = "button" name="displayAccount"></p>
        </form>

        <hr />
        <h2>Analyze the Sponsorship of Channels</h2>
        <form method="GET" action="AdminContent.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" value="Analyze" class = "button" name="countTuples"></p>
        </form>

        <hr />
        <h2>Display the Channels Sponosored by All Companys</h2>
        <form method="GET" action="AdminContent.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayChannelSponsor" name="displayChannelSponsor">
            <input type="submit" value="Check" class = "button" name="displaySponsorRelationship"></p>
        </form>

        <hr />
        <h2>Monitor (Selection)</h2><br/>

            <p1>ChooseTable:</p1>
            <form method="GET" action="AdminContent.php">
                <input type="hidden" id="tableRequest" name="tableRequest">
                <select name="select_table">
                <option value="channel" selected="channel"> Channel</option>
                <option value="rvContain" selected="rvContain">Video</option>
                </select><br /><br />
                <p>Select the Attributes to Check</p>
                        <p> Channel Attributes</p>
                        <input type="checkbox" name="channelID" id="channelID">
                        <label for="channelID">
                        channelID: <input name="cID_input">
                        </label>
                        <input type="checkbox" name="ChannelStatus" id="ChannelStatus">
                        <label for="ChannelStatus">
                        ChannelStatus: <input name="cStatus_input">
                        </label>
                        <!-- <p> Forum Attributes</p>
                        <input type="checkbox" name="PostID" id="PostID">
                        <label for="PostID">
                        PostID: <input name="pID_input">
                        </label>
                        <input type="checkbox" name="ForumTag" id="ForumTag">
                        <label for="ForumTag">
                        ForumTag: <input name="pTag_input">
                        </label> -->
                        <p> Video Attributes</p>
                        <input type="checkbox" name="VideoStatus" id="VideoStatus">
                        <label for="VideoStatus">
                        VideoStatus: <input name="vStatus_input">
                        </label>
                        <input type="checkbox" name="Tag" id="Tag">
                        <label for="Tag">
                        Tag: <input name="vTag_input">
                        </label><br /><br />
                        <!-- <p> Livestream Attributes</p>
                        <input type="checkbox" name="LiveStreamID" id="LiveStreamID">
                        <label for="LiveStreamID">
                        LiveStreamID: <input name="lID_input">
                        </label>
                        <input type="checkbox" name="PeriodLength" id="PeriodLength">
                        <label for="PeriodLength">
                        PeriodLength: <input name="lLen_input">
                        </label><br /><br /> -->
                <input type="submit" class= "button" value="Monitor" name="Monitor"></p>
            </form>
            <hr />
        </div>


        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages =FALSE; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            // echo "<br>running ".$cmdstr."<br>";
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
                    // echo $val;
                    // echo "<br>".$bind."<br>";
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

        function printResultVideo($result) { //prints results from a select statement
            // echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>SponsorID</th><th>VideoID</th><th>ChannelID</th><th>CompanyName</th><th>Budget</th><th>VideoStatus</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row["SPONSORID"] . "</td><td>" . $row["VIDEOID"] . "</td><td>" . $row["CHANNELID"] . "</td><td>" . $row["COMPANYNAME"] . "</td><td>" . $row["BUDGET"] . "</td><td>" . $row["VIDEOSTATUS"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultAccount($result) { //prints results from a select statement
            // echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>AccountID</th><th>UserID</th><th>AccountStatus</th><th>Email</th><th>Password</th><th>BirthDate</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultChannel($result) { //prints results from a select statement
            // echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>SponsorID</th><th>ChannelID</th><th>CompanyName</th><th>Budget</th><th>ChannelStatus</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row["SPONSORID"] . "</td><td>" . $row["CHANNELID"] . "</td><td>" . $row["COMPANYNAME"] . "</td><td>" . $row["BUDGET"] . "</td><td>" . $row["CHANNELSTATUS"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultChannelSelection($result){
            echo "<table>";
            echo "<tr><th>ChannelID</th><th>ChannelStatus</th><th>Showlist</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultVideoSelection($result){
            echo "<table>";
            echo "<tr><th>VideoID</th><th>ChannelID</th><th>VideoStatus</th><th>Tag</th><th>CreateTime</th><th>VideoStatus</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printSponsor($result){
            echo "<br>Display the Channels Sponosored by All Companys<br>";
            echo "<table>";
            echo "<tr><th>ChannelID</th><th>ChannelStatus</th><th>Showlist</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo "y";
                echo "<tr><td>" . $row["CHANNELID"] . "</td><td>" . $row["CHANNELSTATUS"] . "</td><td>" . $row["SHOWLIST"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultGroupBy($result) { //prints results from a select statement
            // echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>CompanyName</th><th>Total Number</th><th>Max Budget</th><th>Min Budget</th></tr>";


            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // echo $result;
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_ycai12", "a25616533", "dbhost.students.cs.ubc.ca:1522/stu");
            if ($db_conn) {
                // echo $db_conn;
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

        function handleUpdateRequest() {
            global $db_conn;

            $account_a_id = $_POST['account_id'];
            $user_a_id = $_POST['user_id'];
            $video_a_id = $_POST['video_id'];
            $channel_a_id = $_POST['channel_id'];
            $n_email = $_POST['email_input'];
            $n_password = $_POST['password_input'];
            $n_bd = $_POST['bd_input'];
            $sig1 = $_POST['email'];
            $sig2 = $_POST['password'];
            $sig3 = $_POST['bd'];
            $sig4 = $_POST['block'];

            $status = 0;
            // echo "<br>Retrieved data from table:<br>";
            // echo $account_a_id;
            // block is selected
            // echo $sig4 == "on";
            if ($sig4 == "on"){
            if ($account_a_id != ""){
                executePlainSQL("UPDATE accountOwn SET AccountStatus = 0 WHERE AccountID='" . $account_a_id . "' AND UserID='" . $user_a_id . "'");
            } else if ($video_a_id != ""){
                executePlainSQL("UPDATE rvContain SET VideoStatus = 0 WHERE VideoID='" . $video_a_id . "' AND ChannelID='" . $channel_a_id . "'");
            } else if ($channel_a_id != ""){
                executePlainSQL("UPDATE channel SET ChannelStatus = 0 WHERE ChannelID='" . $channel_a_id . "'");
            } else {
                echo "cannot be all empty";
            }
            }
            if ($sig3 == "on"){
                executePlainSQL("UPDATE accountOwn SET BirthDate = '" . $n_bd . "' WHERE AccountID='" . $account_a_id . "' AND UserID='" . $user_a_id . "'");
            }
            if ($sig2 == "on"){
                executePlainSQL("UPDATE accountOwn SET AccountPassword = '" . $n_password . "' WHERE AccountID='" . $account_a_id . "' AND UserID='" . $user_a_id . "'");
            }
            if ($sig1 == "on"){
                executePlainSQL("UPDATE accountOwn SET Email = '" . $n_email . "' WHERE AccountID='" . $account_a_id . "' AND UserID='" . $user_a_id . "'");
            }

            // you need the wrap the old name and new name values with single quotations
            OCICommit($db_conn);
        }

        function handleDisplayRequest() {
            global $db_conn;
            $sig = $_GET['select'];
            $max = $_GET['max_input'];
            $min = $_GET['min_input'];
            if ($sig == "s_video"){
                
                    $result = executePlainSQL("SELECT srv.SponsorID, srv.VideoID, srv.ChannelID, sc.CompanyName, sc.Budget, rvc.VideoStatus
                                           FROM rvContain rvc, scSponsorRV srv, sponsorCompany sc
                                           WHERE rvc.VideoID = srv.VideoID AND rvc.ChannelID = srv.ChannelID AND srv.SponsorID = sc.SponsorID
                                           AND sc.Budget >= '". $min ."' AND sc.Budget <= '". $max ."'");
                printResultVideo($result);
            } else if ($sig == "s_channel"){
                    $result = executePlainSQL("SELECT spc.SponsorID, spc.ChannelID, sc.CompanyName, sc.Budget, c.ChannelStatus
                                           FROM channel c, SCSponsorChannel spc, sponsorCompany sc
                                           WHERE c.ChannelID = spc.ChannelID AND spc.SponsorID = sc.SponsorID
                                           AND sc.Budget >= '". $min ."' AND sc.Budget <= '". $max ."'");
                printResultChannel($result);
            
            }


        }
        function handledisplaySponsor(){
            global $db_conn;
            $result = executePlainSQL("SELECT *
                                       FROM channel c
                                       WHERE NOT EXISTS
                                       ((SELECT sc.SponsorID
                                         FROM sponsorCompany sc)
                                         MINUS
                                         (SELECT spc.SponsorID
                                          FROM SCSponsorChannel spc
                                          WHERE spc.ChannelID = c.ChannelID))");
            printSponsor($result);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT CompanyName, Count(CompanyName), MAX(Budget), Min(Budget)
                                       FROM SCSponsorChannel spc, sponsorCompany sc
                                       WHERE spc.SponsorID = sc.SponsorID
                                       GROUP BY CompanyName");

            printResultGroupBy($result);
        }

        function handleSelection(){
            global $db_conn;
            $table = $_GET['select_table'];
            if ($table == "channel"){
                $sig_1 = $_GET['channelID'];
                $sig_2 = $_GET['ChannelStatus'];
                if ($sig_1 == "on" && $sig_2 == ""){
                    $id = $_GET['cID_input'];
                    $result = executePlainSQL("SELECT *
                                               FROM channel
                                               WHERE channelID = '". $id ."'
                                               ");
                    //echo "no max";
                    printResultChannelSelection($result);
                } else if ($sig_1 == "" && $sig_2 == "on"){
                    $status = $_GET['cStatus_input'];
                    $result = executePlainSQL("SELECT *
                                               FROM channel
                                               WHERE channelStatus = '". $status ."'
                                               ");
                    //echo "no min";
                    printResultChannelSelection($result);
                } else if ($sig_1 == "on" && $sig_2 == "on"){
                    $id = $_GET['cID_input'];
                    $status = $_GET['cStatus_input'];
                    //echo "yes";
                    $result = executePlainSQL("SELECT *
                                               FROM channel
                                               WHERE channelID = '". $id ."' AND channelStatus = '". $status ."'
                                               ");
                    printResultChannelSelection($result);
                }
            }
            // if ($table == "forum"){}
            if ($table == "rvContain"){
                $sig_1 = $_GET['VideoStatus'];
                $sig_2 = $_GET['Tag'];
                if ($sig_1 == "on" && $sig_2 == ""){
                    $v_status = $_GET['vStatus_input'];
                    $result = executePlainSQL("SELECT *
                                               FROM rvContain
                                               WHERE VideoStatus = '". $v_status ."'
                                               ");
                    //echo "no max";
                    printResultVideoSelection($result);
                } else if ($sig_1 == "" && $sig_2 == "on"){
                    $tag = $_GET['vTag_input'];
                    $result = executePlainSQL("SELECT *
                                               FROM rvContain
                                               WHERE Tag = '". $tag ."'
                                               ");
                    //echo "no min";
                    printResultVideoSelection($result);
                } else if ($sig_1 == "on" && $sig_2 == "on"){
                    $v_status = $_GET['vStatus_input'];
                    $tag = $_GET['vTag_input'];
                    //echo "yes";
                    $result = executePlainSQL("SELECT *
                                               FROM rvContain
                                               WHERE VideoStatus = '". $v_status ."' AND Tag = '". $tag ."'
                                               ");
                    printResultVideoSelection($result);
                }
            }
            // if ($table == "ls"){}
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('blockInformation', $_POST)) {
                    handleUpdateRequest();
                }
                disconnectFromDB();
            }
        }

         // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('countTuples', $_GET)) {
                handleCountRequest();
            }
            if (array_key_exists('displayTuples', $_GET)) {
                handledisplayRequest();
            } else if (array_key_exists('displaySponsorRelationship', $_GET)){
                handledisplaySponsor();
            } else if (array_key_exists('displayAccountRequest',$_GET)){
                printResultAccount((executePlainSQL("SELECT * FROM accountOwn")));
            } else if (array_key_exists('Monitor', $_GET)){
                handleSelection();
            }

            disconnectFromDB();
        }
    }

    if (isset($_POST['blockSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest']) || isset($_GET['displayChannelSponsor'])|| isset($_GET['displayAccountRequest']) || isset($_GET['tableRequest'])) {
        handleGETRequest();
    }
		?>
	</body>
</html>


