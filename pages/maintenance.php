<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wartungsmodus</title>
  <style>
    body {
      background-color: #0f0f0f;
      color: #fefefe;
      font-family: system-ui, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100dvh;
      margin: 0;
      text-align: center;
      opacity: 0;
      animation: fadein 1.2s ease-out forwards;
      flex-direction: column;
    }

    @keyframes fadein {
      to { opacity: 1; }
    }

    @keyframes rainbow-move {
      0% { background-position: 0% center; }
      100% { background-position: 100% center; }
    }

    .rainbow {
      font-size: 2.8rem;
      font-weight: bold;
      background: linear-gradient(90deg, red, orange, yellow, lime, cyan, blue, violet, red, orange, yellow, lime, cyan, blue, violet);
      background-size: 218% auto;
      background-position: 0% center;
      animation: rainbow-move 10s linear infinite;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.5rem;
    }

    .fadein-delay {
      opacity: 0;
      animation: fadein-text 1s ease-out forwards;
    }

    @keyframes fadein-text {
      to { opacity: 1; }
    }

    p {
      font-size: 1.2rem;
      max-width: 40ch;
      margin: 0.5rem auto;
    }
  </style>
</head>
<body>
  <div class="rainbow">KilianWos.DE</div>
  <p class="fadein-delay">Diese Website befindet sich im Wartungsmodus.<br>Bitte schauen Sie später noch einmal vorbei.</p>
  <p class="fadein-delay">This website is currently under maintenance.<br>Please check back again later.</p>
</body>
</html>