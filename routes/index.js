var router = require('express').Router();

router.use('/todo', require('./todo.js'));
router.use('/category', require('./category.js'));

module.exports = router;
