export default {


  permissions: [ 'public_profile', 'email' ],


  is_web: typeof facebookConnectPlugin === 'undefined',

  // для того что бы получить 'user_birthday', 'user_gender' надо пройти App Review
  _enterFb() {
    return new Promise((resolve, reject) => {
      facebookConnectPlugin.login(this.permissions, auth => {
        facebookConnectPlugin.api('/me?fields=email,first_name,last_name,picture', this.permissions, data => {
          resolve({ auth, data });
        }, err => {
          reject(err);
          main.$root.debug(err);
        })
      }, err => {
        reject(err);
        main.$root.debug(err);
      })
    });
  },


  _enterWeb() {
    return new Promise((resolve, reject) => {
      FB.login(auth => {
        if (auth.authResponse) {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me?fields=email,first_name,last_name,picture', data => {
            console.log('Good to see you, ' + data.email + '.');
            resolve({ auth, data });
          });
        } else {
          reject();
          console.log('User cancelled login or did not fully authorize.');
        }
      }, { scope: this.permissions.join(',') });
    });
  },


  enter() {
    if(this.is_web) {
      return this._enterWeb();
    } else {
      return this._enterFb();
    }
  },

}
