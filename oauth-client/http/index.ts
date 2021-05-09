import axios from 'axios';

const _axios = axios.create({
    baseURL: (process.env.API_URL || process.env.NEXT_PUBLIC_API_URL) + '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

export default _axios;
