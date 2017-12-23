<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style type="text/css">

body {
  position: relative;
  width: 100%;
  height: 100vh;
  font-family: Montserrat;
}

.wrap {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.text {
  color: #fbae17;
  display: inline-block;
  margin-left: 5px;
}

.bounceball {
  position: relative;
  display: inline-block;
  height: 37px;
  width: 15px;
}
.bounceball:before {
  position: absolute;
  content: '';
  display: block;
  top: 0;
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background-color: #fbae17;
  transform-origin: 50%;
  animation: bounce 500ms alternate infinite ease;
}

@keyframes bounce {
  0% {
    top: 30px;
    height: 5px;
    border-radius: 60px 60px 20px 20px;
    transform: scaleX(2);
  }
  35% {
    height: 15px;
    border-radius: 50%;
    transform: scaleX(1);
  }
  100% {
    top: 0;
  }
}

  </style>

</head>
<body>




<div class="wrap">
  <div class="loading">
    <div class="bounceball"></div>
    <div class="text">NOW LOADING</div>
  </div>
</div>
</body>
</html>




