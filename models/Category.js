const mongoose = require('mongoose');

const Category = mongoose.Schema({
  name: String,
}, {
  timestamps: true
});

module.exports = mongoose.model('Category', Category);
