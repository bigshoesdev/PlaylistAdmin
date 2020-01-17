window.axios = require('axios');
axios.defaults.baseURL = 'https://playlist.motowelove.com';
// axios.defaults.baseURL = 'http://localhost:8000';
axios.interceptors.response.use(res => {
  return res;
}, err => {
  console.log(err);
  if(err.code === 'ECONNABORTED') {
    app.$store.commit('alert', 'אין חיבור אינטרנט');
    return;
  } else {
    if(err.response.status >= 500) {
      app.$store.commit('alert', 'שגיאת שרת, נסה מאוחר יותר');
      app.debug({ 'message': err.config });
      return;
    } else {
      return Promise.reject(err.response);
    }
  }
});
axios.defaults.timeout = 5000;


if(!Element.prototype.triggerEvent) {
  Element.prototype.triggerEvent = function (eventName) {
    var event;

    if (document.createEvent) {
      event = document.createEvent("HTMLEvents");
      event.initEvent(eventName, true, true);
    } else {
      event = document.createEventObject();
      event.eventType = eventName;
    }

    event.eventName = eventName;
    if (document.createEvent) {
      this.dispatchEvent(event);
    } else {
      this.fireEvent("on" + event.eventType, event);
    }
  };
}


Number.prototype.map = function (in_min, in_max, out_min, out_max) {
  return (this - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}


Math.easeInOutQuad = function (t, b, c, d) {
  t /= d/2;
  if (t < 1) return c/2*t*t + b;
  t--;
  return -c/2 * (t*(t-2) - 1) + b;
};


window.scrollSmoothTo = function(to, duration) {
  return new Promise(function(resolve, reject) {
    const element = document.scrollingElement;
    const start = (element && element.scrollTop) || window.pageYOffset,
      change = to - start,
      increment = 20;
    let currentTime = 0;

    const animateScroll = function() {
      currentTime += increment;
      const val = Math.easeInOutQuad(currentTime, start, change, duration);
      window.scrollTo(0, val);
      if(currentTime < duration) {
        window.setTimeout(animateScroll, increment);
      } else {
        resolve();
      }
    };
    animateScroll();
  });
}
