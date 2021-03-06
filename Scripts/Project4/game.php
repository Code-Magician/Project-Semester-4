<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>MAZE GENERATOR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="Stylesheet" type="text/css" />
</head>

<body>
    <main>
        <section id="hero-image">
            <img src="logo.png" height="200px" width="200px">
            <a href="index.php" id="login-register-button" style="float: right;">Login / Register</a>
            <div class="hero-marketing-text">
                <h1>The Best <span>Game</span> of the ERA!</h1>
                <h4>The Maze Generator: Easy to enter, but hard to escape !! </h4><br>
            </div>
        </section>

        <section id="latest-news">
        </section>

        <section id="game-types-boxes">
            <div class="flex">
                <div class="box new">
                    <div class="shade"></div>
                    <span class="badge new">New</span>
                    <div class="contents">
                        <h4>Mazoandom</h4>
                        <p>Zombie Shooter & Procedural Maze Generations</p>
                        <a href="https://warlock-perry.itch.io/mazoandom" class="comments">Click here to play the game.</a>
                    </div>
                </div>

                <div class="box strategy">
                    <div class="shade"></div>
                    <span class="badge strategy">New</span>
                    <div class="contents">
                        <h4>Eutopia</h4>
                        <p>The Hidden World</p>
                        <a href="https://play.google.com/store/apps/details?id=com.UnleashGames.EutopiaHiddenWorlds" class="comments">Click here to play the game.</a>
                    </div>
                </div>

                <div class="box rpg">
                    <div class="shade"></div>
                    <span class="badge rpg">New</span>
                    <div class="contents">
                        <h4>Ping Pong Game</h4>
                        <p>A fun game</p>
                        <a href="https://play.unity.com/mg/other/webgl-builds-78293" class="comments">Click here to play the game.</a>
                    </div>
                </div>

                <div class="box racing">
                    <div class="shade"></div>
                    <span class="badge racing">New</span>
                    <div class="contents">
                        <h4>Warlock's Food War</h4>
                        <p>A War Game</p>
                        <a href="https://play.unity.com/mg/other/webgl-builds-75036" class="comments">Click here to play the game.</a>
                    </div>
                </div>
            </div>
        </section>



        <section id="tournaments">
            <div class="flex">
                <span class="badge tournaments">Tournaments</span>
                <div class="box">
                    <span class="badge premium-tournament">Premium Tournament</span>
                    <div class="tournaments-image">
                        <img src="jumper.png" height=200px>
                    </div>
                    <div class="tournaments-content">
                        <h3>Jumper</h3>
                        <div><label>Tournament Begins:</label> <strong>May 25, 2022</strong></div>
                        <div><label>Tournament Ends:</label> <strong>May 31, 2022</strong></div>
                        <div><label>Participants:</label> <strong>10 teams</strong></div>
                        <div><label class="prizes">Prizes:</label> <label>1st place $2000, 2nd place: $1000, 3rd place: $500</label></div>
                    </div>
                </div>

                <div class="box">
                    <span class="badge premium-tournament">Premium Tournament</span>
                    <div class="tournaments-image">
                        <img src="color.png" height="200px">
                    </div>
                    <div class="tournaments-content">
                        <h3>Color Switcher</h3>
                        <div><label>Tournament Begins:</label> <strong>June 2, 2022</strong></div>
                        <div><label>Tournament Ends:</label> <strong>June 5, 2022</strong></div>
                        <div><label>Participants:</label> <strong>10 teams</strong></div>
                        <div><label class="prizes">Prizes:</label> <label>1st place $2000, 2nd place: $1000, 3rd place: $500</label></div>
                    </div>
                </div>
            </div>
        </section>




        <footer>
            <div class="flex">
                <small>Copyright &copy; 2022 All rights reserved</small>
                <ul>
                    <li>
                        <a href="#">Back to top</a>
                    </li>
                </ul>
            </div>
        </footer>

        <script>
            document.getElementById('nav-toggle').addEventListener('click', function() {
                let navMenu = document.getElementById('nav-menu-container');
                navMenu.style.display = navMenu.offsetParent === null ? 'block' : 'none';
            });
        </script>
</body>

</html>