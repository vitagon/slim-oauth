import axios from 'axios';

const _axios = axios.create({
    baseURL: process.env.NEXT_PUBLIC_AUTH_SERVER_URL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

export default _axios;
