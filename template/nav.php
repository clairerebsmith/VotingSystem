<link rel="shortcut icon" href="../includes/img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../includes/img/favicon.ico" type="image/x-icon">

<script>

    var hasBeenClicked; //variable for checking the current state of the pages font size
    var assistant;

    function voiceOver() {


        if (localStorage.getItem("voice")) {
            //if there is something stored in the local storage, store this value
            assistant = localStorage.getItem("voice");
        } else {

            localStorage.setItem("voice", "TRUE");
            assistant = "TRUE";
        }
        if (assistant === "FALSE") {
            //if the current state is true, loop through all elements and activate the voice assistant

            localStorage.setItem("voice", "TRUE");
            setTimeout(responsiveVoice.speak("Welcome to Our Voting Website. highlight any text with your mouse if you want to be read back at you "), 15000);


            function getSelectionText() {
                let text;
                if (window.getSelection) {
                    text = window.getSelection().toString();
                    // for Internet Explorer 8 and below. For Blogger, you should use &amp;&amp; instead of &&.
                } else if (document.selection && document.selection.type != "Control") {
                    text = document.selection.createRange().text;
                }
                return text;
            }
        } else if (assistant === "TRUE") {
            //if the current state is true, turn off the voice assistant and promp the user
            setTimeout(responsiveVoice.speak("Voice Off"), 15000);

            localStorage.setItem("voice", "FALSE");
        }


        $(document).ready(function () { // when the document has completed loading
            $(document).mouseup(function (e) { // attach the mouseup event for all div and pre tags
                setTimeout(function () { // When clicking on a highlighted area, the value stays highlighted until after the mouseup event, and would therefore stil be captured by getSelection. This micro-timeout solves the issue.
                    responsiveVoice.cancel(); // stop anything currently being spoken
                    responsiveVoice.speak(getSelectionText()); //speak the text as returned by getSelectionText
                }, 1);
            });
        });

    }

    window.onload = function () {

        if (localStorage.getItem("voice")) {
            //if there is something stored in the local storage, store this value
            assistant = localStorage.getItem("voice");
        } else {
            //otherwise keep the font size normal
            localStorage.setItem("voice", "FALSE");
            assistant = "FALSE";
        }

        if (assistant === "TRUE") {


            function getSelectionText() {
                let text;
                if (window.getSelection) {
                    text = window.getSelection().toString();
                    // for Internet Explorer 8 and below. For Blogger, you should use &amp;&amp; instead of &&.
                } else if (document.selection && document.selection.type != "Control") {
                    text = document.selection.createRange().text;
                }
                return text;
            }

            $(document).ready(function () { // when the document has completed loading
                $(document).mouseup(function (e) { // attach the mouseup event for all div and pre tags
                    setTimeout(function () { // When clicking on a highlighted area, the value stays highlighted until after the mouseup event, and would therefore stil be captured by getSelection. This micro-timeout solves the issue.
                        responsiveVoice.cancel(); // stop anything currently being spoken
                        responsiveVoice.speak(getSelectionText()); //speak the text as returned by getSelectionText
                    }, 1);
                });
            });

        }

        if (localStorage.getItem("clicked")) {
            //if there is something stored in the local storage, store this value
            hasBeenClicked = localStorage.getItem("clicked");
        } else {
            //otherwise keep the font size normal
            localStorage.setItem("clicked", "FALSE");
            hasBeenClicked = "FALSE";
        }
        if (hasBeenClicked === "TRUE") {
            //if the current state is true, loop through all elemenets and enlarge the font size
            $('[id]').each(function () {
                document.getElementById(this.id).style.fontSize = "18px";
                localStorage.setItem("clicked", "TRUE");
            })
        }
    }

    function changeSize() {

        hasBeenClicked = localStorage.getItem("clicked"); //retrieve current state from the local storage
        if (hasBeenClicked === "FALSE") {
            //enlarge font size 
            $('[id]').each(function () {
                document.getElementById(this.id).style.fontSize = "18px";
                localStorage.setItem("clicked", "TRUE");
            });
        } else if (hasBeenClicked === "TRUE") {
            //go back to normal size text 
            $('[id]').each(function () {
                document.getElementById(this.id).style.fontSize = "12px";
                localStorage.setItem("clicked", "FALSE");
            });
        }
    }
</script>

<!--Include external scripts and internal Navbar CSS file -->
<script src="https://code.responsivevoice.org/responsivevoice.js"></script>

<link rel="stylesheet" href="./includes/css/navBar.css" type="text/css"/>


<?php
if (basename($_SERVER['PHP_SELF']) == "home.php") {   // Check whether the navbar is being called in the homepage.
    ?>
    <nav class="navbar navbar-default">
        <div>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">Voting System</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="#votingWay">How to Vote</a></li>
                    <li><a href="#news">Live News</a></li>
                    <li><a href="#choosing">Overview</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (isset($_SESSION['status']) && $_SESSION['status'] == "authorized") {    // Check whether a session exists and if does whether it is authorized
                    if ($_SESSION['admin'] == '0') {                                            // Check if the session user is an admin
                        ?>
                        <li><a href="electionList.php"><span class="glyphicon glyphicon-inbox"></span>Voting Area</a>
                        </li>
                        <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>My Profile</a></li>
                        <?php
                    } else {                                                                    // If the session user is not an admin
                        ?>
                        <li><a href="admin.php"><span class="glyphicon glyphicon-user"></span>Admin Area</a></li>
                        <?php
                    }
                    ?>
                    <li><a class="glyphicon glyphicon-text-size" onclick="changeSize()" id="m" value="changeSize"></a>
                    <li>
                    <li><a href="home.php?status=logout"><span class="glyphicon glyphicon-log-out"></span>Log out</a>
                    </li>
                    <li><a style="font-size: 20px;" class="glyphicon glyphicon-volume-up" onclick="voiceOver()"
                           href="#voice"></a></li>
                </ul>
                <?php
                } else {                                                                        // If there is no session (User has not logged in)
                    ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="results.php">Voting Result</a></li>
                        <li><a class="glyphicon glyphicon-text-size" onclick="changeSize()" id="m"
                               value="changeSize"></a>
                        <li>
                        <li><a href="registerVoter.php"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li>
                        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
                        <li><a style="font-size: 20px;" class="glyphicon glyphicon-volume-up" onclick="voiceOver()"
                               href="#voice"></a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>
    <?php
} else { // If the navbar is not being called in homepage.
    ?>
    <nav class="navbar navbar-default">
        <div>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">Voting System</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (isset($_SESSION['status']) && $_SESSION['status'] == "authorized") {                                // If an active session exists
                    if ($_SESSION['admin'] == '0') {                                                                        // If the logged in user is not an admin
                        ?>
                        <li><a href="electionList.php""><span class="glyphicon glyphicon-inbox"></span>Voting Area</a>
                        </li>
                        <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>My Profile</a></li>
                        <?php
                    } else {                                                                                                // If the logged in user is an admin
                        ?>
                        <li><a href="admin.php""><span class="glyphicon glyphicon-user"></span>Admin Area</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="results.php">Voting Result</a></li>
                    <li><a class="glyphicon glyphicon-text-size" onclick="changeSize()" id="m" value="changeSize"></a>
                    <li>
                    <li><a href="home.php?status=logout"><span class="glyphicon glyphicon-log-out"></span>Log out</a>
                    </li>
                    <li><a style="font-size: 20px;" class="glyphicon glyphicon-volume-up" onclick="voiceOver()"
                           href="#voice"></a></li>
                </ul>
                <?php
                } else {                                                                                                    // If no session exists (User has not logged in)
                    ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="results.php">Voting Result</a></li>
                        <li><a class="glyphicon glyphicon-text-size" onclick="changeSize()" id="m"
                               value="changeSize"></a>
                        <li>
                        <li><a href="registerVoter.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li><a style="font-size: 20px;" class="glyphicon glyphicon-volume-up" onclick="voiceOver()"
                               href="#voice"></a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>

    </nav>
    <?php
}
?>