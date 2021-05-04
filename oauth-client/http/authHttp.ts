import axios from 'axios';

const authHttp = axios.create({
    baseURL: process.env.AUTH_SERVER_URL || process.env.NEXT_PUBLIC_AUTH_SERVER_URL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

export default authHttp;
