<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="test.css" />
  <title>Pokemon Magazine</title>
</head>

<body>
  <div class="flipbook">
    <div class="hard">My Pokemon Gallery <small>~ HankTheTank</small></div>
    <div class="hard"></div>
    <div>
      <small>Lets Look At Some Amazing Pokemon ❤️</small>
      <small>Gotta Catch 'Em All</small>
    </div>
    <div>
      <img src="images/img-1.png" alt="" />
      <small> Charmandar </small>
    </div>
    <div>
      <img src="images/img-2.png" alt="" />
      <small> Arbok </small>
    </div>
    <div>
      <img src="images/img-3.png" alt="" />
      <small> Pikachu </small>
    </div>
    <div>
      <img src="images/img-4.png" alt="" />
      <small> Mew </small>
    </div>
    <div>
      <img src="images/img-5.png" alt="" />
      <small> Darkrai </small>
    </div>
    <div class="hard"></div>
    <div class="hard">Thank You <small>~ HankTheTank</small></div>
  </div>

  <script src="jquery.js"></script>
  <script src="turn.js"></script>
  <script>
    $(".flipbook").turn();
  </script>
</body>

</html>