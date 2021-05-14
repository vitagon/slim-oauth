import authHttp from '@/http/authHttp';
import Cookies from 'cookies';

export default async (req, res) => {
    const cookies = new Cookies(req, res);
    let refreshToken = cookies.get('refresh_token');
    if (!refreshToken) {
        return res.status(400).json({ error: 'refresh_token was not found in cookies' });
    }

    let data = null;
    try {
        let tokenResponse = await authHttp.post('/oauth/access_token', {
            grant_type: 'refresh_token',
            refresh_token: refreshToken,
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

    cookies.set('access_token', data.access_token);
    cookies.set('refresh_token', data.refresh_token);
    cookies.set('expiration_time', unixNow + data.expires_in);
    cookies.set('token_type', data.token_type);

    return res.json(data);
}
