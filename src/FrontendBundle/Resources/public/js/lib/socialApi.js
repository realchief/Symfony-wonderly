

function socialApiKey(googleSocialApiKey, facebookSocialApiKey) {

    //////////google+///////////////////////////
    var googleUser = {};

    var startApp = function() {
        gapi.load('auth2', function(){
            auth2 = gapi.auth2.init({
                client_id: googleSocialApiKey,
                cookiepolicy: 'single_host_origin',
                scope: 'profile'
            });
            attachSignin(document.getElementById('googleLogin'));
        });
    };
    startApp();

    //////////////facebook//////////////////////////
    window.fbAsyncInit = function() {
        FB.init({
            appId      : facebookSocialApiKey,
            cookie     : true,
            xfbml      : true,
            version    : 'v2.10'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
}

function attachSignin(element) {
    auth2.attachClickHandler(element, {},
        function(googleUser) {
            console.log(googleUser);
            var profile = googleUser.getBasicProfile();
            var userWonderly = {
                social: 'google',
                id: profile.getId(),
                firstname: profile.getGivenName(),
                lastname: profile.getFamilyName(),
                email: profile.getEmail()
            };
            sendSocialData(userWonderly);
        }, function(error) {
            alert(JSON.stringify(error, undefined, 2));
        });
}

//////////////facebook//////////////////////////


function facebook_click() {
    console.log('I did it');
    FB.getLoginStatus(function(response) {
        console.log(response);
        if (response.status === 'connected') {
            FB.api('/me', { fields: 'email,first_name,last_name' }, function(response) {
                var userWonderly = {
                    id: response.id,
                    social: 'facebook',
                    firstname: response.first_name,
                    lastname: response.last_name,
                    email: response.email
                };
                sendSocialData(userWonderly);
            });
        } else {
            FB.login(function (response) {
                if (response.status === 'connected') {
                    console.log(response);
                    FB.api('/me', { fields: 'email,first_name,last_name' }, function(response) {
                        var userWonderly = {
                            id: response.id,
                            social: 'facebook',
                            firstname: response.first_name,
                            lastname: response.last_name,
                            email: response.email
                        };
                        sendSocialData(userWonderly);
                    });
                } else {
                    console.log('Facebook account does not work');
                }
            }, {scope: 'public_profile,email', return_scopes: true});
        }
    });
}

