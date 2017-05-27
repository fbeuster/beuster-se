<!DOCTYPE html>
<html>
  <head>
    <title>{{title}}</title>
    <style>
      body {
        background: #fafafa;
        color: #191f1f;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
      }

      footer,
      h1,
      p {
        margin: 0 auto;
        max-width: 800px;
        padding-top: 2em;
        width: 80%;
      }

      a {
        color: #0ea86c;
        text-decoration: none;
      }

      a:hover {
        color: #1686c6;
      }

      footer {
        color: #666666;
      }
    </style>
  </head>
  <body>
    <h1>{{title}}</h1>
    <p>{{description}}</p>
    <p>{{message}}</p>
    <footer>
      {{footer}}
      <br><br>
      {{copy}}
    </footer>
  </body>
</html>
