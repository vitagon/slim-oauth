import authHttp from "@/http/authHttp";
import Cookies from 'cookies';

export default async (req, res) => {
    const cookies = new Cookies(req, res);
    let accessToken = cookies.get('access_token');
    if (typeof req.headers.cookie == 'undefined' || !accessToken) {
        return res.status(401).end();
    }

    let data = null;
    try {
        let tokenResponse = await authHttp.get('/oauth/user', {
            headers: {
                authorization: `Bearer ${accessToken}`
            }
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

    return res.status(200).json(data);
}