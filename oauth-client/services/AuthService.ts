import Http from '@/http';
import RequestContext from '@/http/RequestContext';
import CookieHolder from '@/http/CookieHolder';

export async function getUser(cookie) {
    if (typeof cookie === 'undefined') {
        cookie = null;
    }

    let removeToken = false;
    let data = null;
    try {
        let res = await Http.get('/auth/user', {
            headers: { cookie }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
        removeToken = true;
    }

    return { user: data, removeToken };
}

export async function refreshToken(cookieHeader) {
    let data = null;
    try {
        let res = await Http.post('/auth/refresh-token', {}, {
            headers: { cookie: cookieHeader }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
        return null;
    }

    return data;
}
