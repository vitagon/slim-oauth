import axios from 'axios';
import CookieHolder from '@/http/CookieHolder';
import { refreshToken } from '@/services/AuthService';
import RequestContext from '@/http/RequestContext';
import ResponseContext from '@/http/ResponseContext';

const _axios = axios.create({
    baseURL: (process.env.API_URL || process.env.NEXT_PUBLIC_API_URL) + '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

const ignoreUrls = [
    '/auth/refresh-token',
]

_axios.interceptors.response.use(
    (success) => success,
    async (error) => {
        let req = RequestContext.getRequest();
        const status = error.response ? error.response.status : null;
        let l = null;
        try {
            l = ResponseContext.getResponse();
        } catch (e) {
            console.error(e);
        }

        if (status === 401 && !ignoreUrls.includes(error.config.url)) {
            let cookies = error.config.headers?.cookie || '';

            let refreshedTokenData = null;
            try {
                refreshedTokenData = await refreshToken(cookies);
            } catch (e) {
                return Promise.reject(error);
            }

            if (!refreshedTokenData) {
                return Promise.reject(error);
            }

            let unixNow = Math.floor(Date.now() / 1000);
            try {
                CookieHolder.set('access_token', refreshedTokenData.access_token);
                CookieHolder.set('refresh_token', refreshedTokenData.refresh_token);
                CookieHolder.set('expiration_time', unixNow + refreshedTokenData.expires_in);
                CookieHolder.set('token_type', refreshedTokenData.token_type);
            } catch (e) {
                return Promise.reject(error);
            }

            try {
                let ae = ResponseContext.getResponse().getHeader('set-cookie');
                error.config.headers.cookie = [
                    `access_token=${refreshedTokenData.access_token}`,
                    `refresh_token=${refreshedTokenData.refresh_token}`,
                    `expiration_time=${unixNow + refreshedTokenData.expires_in}`,
                    `token_type=${refreshedTokenData.token_type}`,
                ].join('; ');
            } catch (e) {

            }

            // return Promise.reject(error);
            return _axios.request(error.config);
        }

        return Promise.reject(error);
    }
)

export default _axios;
