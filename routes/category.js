var router = require('express').Router();
var Category = require('./../models/Category');

router.get('/:name', function (req, res) {
  var category = new Category({ name: req.params.name });
  category.save().then(category => {
    res.status(200).json(category);
  }).catch(err => {
    res.status(400).send("Error when saving to database");
  });
});

router.get('/', function (req, res) {
  Todo.find((err, todos) =>{
    if(err){
      console.log(err);
    } else {
      res.json(todos);
    }
  });
});

module.exports = router;
