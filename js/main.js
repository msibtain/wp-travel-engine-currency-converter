function setCookie (name, value) 
{
            var date = new Date(),
                expires = 'expires=';
            //date.setTime(date.getTime() + 31536000000);
            date.setDate(date.getDate() + 1);
            
            expires += date.toGMTString();
            document.cookie = name + '=' + value + '; ' + expires + '; path=/';
        }
        
function getCookie (name) 
{
            var allCookies = document.cookie.split(';'),
                cookieCounter = 0,
                currentCookie = '';
            for (cookieCounter = 0; cookieCounter < allCookies.length; cookieCounter++) {
                currentCookie = allCookies[cookieCounter];
                while (currentCookie.charAt(0) === ' ') {
                    currentCookie = currentCookie.substring(1, currentCookie.length);
                }
                if (currentCookie.indexOf(name + '=') === 0) {
                    return currentCookie.substring(name.length + 1, currentCookie.length);
                }
            }
            return false;
        }