import authHttp from '@/http/authHttp';
import Cookies from 'cookies';

export default async (req, res) => {
    let cookie = null;
    if (typeof req.headers.cookie !== 'undefined') {
        cookie = req.headers.cookie;
    }

    let data = null;
    try {
        let tokenResponse = await authHttp.post('/oauth/access_token', {
            grant_type: 'refresh_token',
            refresh_token: req.body.code,
            client_id: process.env.NEXT_PUBLIC_CLIENT_ID,
            client_secret: process.env.CLIENT_SECRET,
            scope: '*'
        });
        data = tokenResponse.data;
    } catch (e) {
        res.status(e.response.status);
        if (e.response && e.response.data) {
            return res.json(e.response.data);
        } else {
            return res.json(e);
        }
    }

    let unixNow = Math.floor(Date.now() / 1000);

    const cookies = new Cookies(req, res);
    cookies.set('access_token', data.access_token);
    cookies.set('refresh_token', data.access_token);
    cookies.set('expiration_time', unixNow + data.expires_in);
    cookies.set('token_type', data.token_type);

    return res.status(200).end();
}
