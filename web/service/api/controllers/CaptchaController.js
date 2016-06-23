// CaptchaController

var captcha = require('canvas-captcha');

module.exports = {

  login: function(req,res) {
    
    var options = {
      
      charPool: ('abcdefghijklmnopqrstuvwxyz' + 'abcdefghijklmnopqrstuvwxyz'.toUpperCase() + '1234567890').split(''),
      size: {
        width: 150,
        height: 30
      },
      textPos: {
        left: 26,
        top: 25
      },
      rotate: 0.0,
      charLength: 6,
      font: '24px Arial',
      strokeStyle: '#0088cc',
      bgColor: '#fff',
      confusion: true,
      cFont: '28px Arial',
      cStrokeStyle: '#adc',
      cRotate: 0.0
    }

    captcha(options,function(err,data){
      
      if(err) res.serverError(err);
      else {
        
        setTimeout(function(){
          req.session.loginCaptcha = data.captchaStr;
          res.end(data.captchaImg);
        },500);
      }
      
    });
    
  }
}