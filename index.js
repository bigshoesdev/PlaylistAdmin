const express = require('express'),
  path = require('path'),
  bodyparser = require('body-parser'),
  cors = require('cors'),
  mongoose = require('mongoose');

const app = express();
app.use(express.static('public'));
app.use(bodyparser.json());
app.use(cors());
app.use(require('./routes'));

// if(!PRODUCTION) {
//   app.use((err, req, res) => {
//     res.status(err.status || 500);
//
//     res.json({
//       errors: {
//         message: err.message,
//         error: err,
//       },
//     });
//   });
// }

mongoose.connect("mongodb://localhost:27017/node-test").then(() => {
    console.log('Database connection is successful')
  }, err => {
    console.log('Error when connecting to the database'+ err)
  }
);

var port = process.env.PORT || 3000;

app.listen(port, () => {
  console.log('Listening on port ' + port);
});
