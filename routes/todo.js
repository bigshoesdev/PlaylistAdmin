var router = require('express').Router();
var Todo = require('./../models/Todo');

router.get('/:name/:category', function (req, res) {
  var todo = new Todo({
    name: req.body.name,
    category: req.params.category,
  });
  todo.save().then(todo => {
    res.status(200).json(todo);
  }).catch(err => {
    res.status(400).send("Error when saving to database");
  });
});

router.get('/', function (req, res) {
  Todo.find().populate('category').exec((err, todos) => {
    if(err){
      console.log(err);
    } else {
      res.json(todos);
    }
  });
});

module.exports = router;
