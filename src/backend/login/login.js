const express = require('express');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

app.use(bodyParser.json());

app.post('/post-endpoint', (req, res) => {

  const postData = req.body;

  if (postData && postData.username && postData.password) {
    const username = postData.username;
    const password = postData.password;

    if (username === 'admin' && password === 'adm24') {
      res.send('Anmeldung erfolgreich'); // response 200
    } else {
      res.status(401).send('Anmeldung fehlgeschlagen');
    }
  } else {
    res.status(400).send('Benutzername und Passwort erforderlich');
  }
});

app.listen(port, () => {
  console.log(`Server läuft auf http://localhost:${port}`);
});