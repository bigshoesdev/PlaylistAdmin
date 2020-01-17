const mongoose = require('mongoose');
const crypto = require('crypto');
const jwt = require('jsonwebtoken');

const User = new mongoose.Schema({
  login: String,
  hash: String,
  salt: String,
});

User.methods.setPassword = function(password) {
  this.salt = crypto.randomBytes(16).toString('hex');
  this.hash = crypto.pbkdf2Sync(password, this.salt, 10000, 512, 'sha512').toString('hex');
};

User.methods.validatePassword = function(password) {
  const hash = crypto.pbkdf2Sync(password, this.salt, 10000, 512, 'sha512').toString('hex');
  return this.hash === hash;
};

User.methods.generateJWT = function() {
  const today = new Date();
  const expirationDate = new Date(today);
  expirationDate.setDate(today.getDate() + 60);

  return jwt.sign({
    login: this.login,
    id: this._id,
    exp: parseInt(expirationDate.getTime() / 1000, 10),
  }, SECRET);
}

User.methods.toAuthJSON = function() {
  return {
    _id: this._id,
    login: this.login,
    token: this.generateJWT(),
  };
};

module.exports = mongoose.model('User', User);
