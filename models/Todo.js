const mongoose = require('mongoose');

const TodoSchema = mongoose.Schema({
  name: String,
  category: { type: mongoose.Schema.Types.ObjectId, ref: 'Category' },
}, {
  timestamps: true
});

module.exports = mongoose.model('Todo', TodoSchema);
