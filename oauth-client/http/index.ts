import axios from 'axios';
import CookieHolder from '@/http/cookieHolder';

const _axios = axios.create({
    baseURL: (process.env.API_URL || process.env.NEXT_PUBLIC_API_URL) + '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

_axios.interceptors.response.use(
    (success) => success,
    (error) => {
        let c = CookieHolder.cookies;
        let t = c.get('access_token');
        let a = error;
        return Promise.reject(error);
    }
)

export default _axios;
