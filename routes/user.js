var router = require('express').Router();
var User = require('./../models/User');

router.get('/:login/:pass', function (req, res) {
  const user = new User({
    login: req.params.login,
  });

  user.setPassword(req.params.pass);

  return user.save().then(() => res.json(user));
});

router.get('/validate-pass/:login/:pass', function (req, res) {
  var result = User.findOne({
    login: req.params.login
  }).then(user => {
    res.json({
      success: user.validatePassword(req.params.pass),
    });
  });
});

router.get('/', function (req, res) {
  User.find((err, todos) => {
    if(err){
      console.log(err);
    } else {
      res.json(todos);
    }
  });
});

module.exports = router;
